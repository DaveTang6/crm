<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;


use Cake\Event\Event;
use App\Event\CustomerPoolEvents;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class CustomersController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
        $CustomerPoolEvents= new CustomerPoolEvents();
        $this->getEventManager()->on($CustomerPoolEvents);
    }


    public function myCustomers() {
	    $pageSize = $this->request->getData('pageSize');
        $page = $this->request->getData('page');
        $data = $this->request->getData();
        if(empty($pageSize) || !is_numeric($pageSize) || $pageSize <1 || $pageSize > 100){
            $pageSize = 30;
        }
        if(empty($page) || !is_numeric($page)){
            $page = 1;
        }

	    $where = ['saler_id' => $this->Jwt->get('userId')];

        if(!empty($data['wechat'])){
            $where['wechat LIKE'] = '%' . $data['wechat'] . '%';
        }

	    if(!empty($data['turename'])){
	       $where['turename LIKE'] = '%' . $data['turename'] . '%';
        }

        if(!empty($data['level'])){
            $where['level'] = $data['level'];
        }

        if(is_numeric($data['locked_by_user']) && $data['locked_by_user']>0){
            if($data['locked_by_user'] == 1) {
                $where['locked_by_user IS'] = null;
            } else {
                $where['locked_by_user IS NOT'] = null;
            }
        }

        $result= $this->Customers->find()
            ->select(['id','wechat', 'truename', 'locked_by_user','area', 'level', 'last_buy', 'order_count', 'memo'])
            ->where($where)
            ->order(['id DESC'])
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result
            ->map(function ($row){
                $row->last_buy = empty($row->last_buy)? '无' : date('Y-m-d', $row->last_buy);
                return $row;
            })->toArray();


        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }

    public function departmentCustomers() {
        $pageSize = $this->request->getData('pageSize');
        $page = $this->request->getData('page');
        $data = $this->request->getData();
        if(empty($pageSize) || !is_numeric($pageSize) || $pageSize <1 || $pageSize > 100){
            $pageSize = 30;
        }
        if(empty($page) || !is_numeric($page)){
            $page = 1;
        }

        $userId = $this->Jwt->get('userId');
        $Departments = $this->getTableLocator()->get('Departments');
        $Department = $Departments->find()
            ->select(['staff_id'])
            ->where(['manager_id' => $userId])
            ->toArray();
        $staffs = [$userId];
        foreach($Department as $v) {
            $staffs[] = $v['staff_id'];
        }
        $where = ['saler_id IN' => $staffs];

        if(!empty($data['wechat'])){
            $where['Customers.wechat LIKE'] = '%' . $data['wechat'] . '%';
        }

        if(!empty($data['truename'])){
            $where['Customers.truename LIKE'] = '%' . $data['truename'] . '%';
        }

        if(!empty($data['level'])){
            $where['Customers.level'] = $data['level'];
        }

        if(is_numeric($data['locked_by_user']) && $data['locked_by_user']>0){
            if($data['locked_by_user'] == 1) {
                $where['Customers.locked_by_user IS'] = null;
            } else {
                $where['Customers.locked_by_user IS NOT'] = null;
            }
        }


        $result= $this->Customers->find()
            ->select(['id' => 'Customers.id',
                'wechat'=> 'Customers.wechat',
                'truename'  => 'Customers.truename',
                'locked_by_user'=> 'Customers.locked_by_user',
                'area'=> 'Customers.area',
                'level'=> 'Customers.level',
                'last_buy'=> 'Customers.last_buy',
                'order_count'=> 'Customers.order_count',
                'memo'=> 'Customers.memo',])
            ->innerJoinWith('Admins', function ($q) use ($data) {
                if(empty($data['saler'])) {
                    return $q->select(['saler' => 'Admins.truename']);
                }
                return $q->select(['saler' => 'Admins.truename'])
                    ->where(['Admins.truename LIKE' => '%'. $data['saler'] .'%']);
            })
            ->where($where)
            ->order(['Customers.id DESC'])
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result->map(function ($row){
            $row->last_buy = empty($row->last_buy)? '无' : date('Y-m-d', $row->last_buy);
            return $row;
        })->toArray();


        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }

    public function add()
    {
        $Customer = $this->Customers->newEntity($this->request->getData());
        $Customer->add_time = time();
        $Customer->saler_id = $this->Jwt->get('userId');
        if($Customer->getErrors()){
            $this->G->error($Customer->getErrors());
            return null;
        }
        $existCustomer = $this->Customers->find()
            ->where(['wechat' => $Customer->wechat, 'saler_id' => $Customer->saler_id])
            ->first();
        if(!empty($existCustomer)) {
            $this->G->error('该客户信息已存在');
            return null;
        }

        $CustomerPools = $this->getTableLocator()->get('CustomerPools');
        $poolCustomer = $CustomerPools->find()
            ->where(['wechat' => $Customer->wechat])
            ->first();
        if(!empty($poolCustomer['wechat']) && $poolCustomer['locked_by_user_id'] > 0) {
            $Customer->locked_by_user = $poolCustomer['locked_by_user'];
        }

        if ($this->Customers->save($Customer)){
            $this->G->success('success', $Customer->id);
        } else {
            $this->G->error('add_error');
        }
    }

    public function update()
    {
        $id = $this->request->getData('id');
        $salerId = $this->Jwt->get('userId');
        if(!is_numeric($id)) {
            $this->G->error('id_error');
            return null;
        }
        $Customer = $this->Customers->get($id);
        if($Customer['saler_id'] != $salerId) {
            $this->G->error('customer_profile_error');
            return null;
        }
        $Customer = $this->Customers->patchEntity($Customer, $this->request->getData());
        if($Customer->getErrors()){
            $this->G->error($Customer->getErrors());
        }
        if ($this->Customers->save($Customer)){
            $CustomerPools = $this->getTableLocator()->get('CustomerPools');
            $CustomerPool = $CustomerPools->find()
                ->where(['wechat' => $Customer['wechat']])
                ->first();
            if(!empty($CustomerPool)){
                if($CustomerPool['locked_by_user_id'] == $salerId) {
                    //synchronise customer's profile to customer pool
                    $log = ['customerPoolId' => $CustomerPool['id'],
                        'customerWechat' => $CustomerPool['wechat'],
                        'act' => 'update',
                        'userId' => $salerId,
                        'username' => $CustomerPool['locked_by_user']
                    ];

                    $event = new Event('CustomerPool.updateCustomer', $this, ['data' =>['customer' => $Customer, 'customerPoolId' => $CustomerPool['id']], 'logInfo' => $log]);
                    $this->getEventManager()->dispatch($event);
                    $result = $event->getResult();
                    if(!$result['result']) {
                        $this->G->error('An error occurred while saving the customer profile');
                        return null;
                    }
                    //====
                }
            }
            $this->G->success('success');
        } else {
            $this->G->error('update_error');
        }
    }

    public function delete()
    {

        $Customer = $this->Customers->newEntity(
            $this->request->getData(),
            ['validate' => 'delete']
        );
        if ($Customer->getErrors()){
            $this->G->error($Customer->getErrors());
            return null;
        }
        $result = $this->Customers->deleteAll(['id IN' =>  $this->request->getData('ids'), 'saler_id' => $this->Jwt->get('userId')]);
        if($result == 0){
            $this->G->error('no_data_deleted');
            return null;
        }
        $this->G->success('success');
    }


    public  function view()
    {
        $Customer = $this->Customers->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );
        if ($Customer->getErrors()){
            $this->G->error($Customer->getErrors());
            return null;
        }

        $result = $this->Customers->find()
        ->where(['id' => $this->request->getData('id')])
        ->first();
        if(empty($result)){
            $this->G->error($result);
            return null;
        }
        $result['last_buy'] = empty($result['last_buy']) ? '无' : date('Y-m-d', $result['last_buy']);
        $result['add_time'] = date('Y-m-d', $result['add_time']);
        $userId = $this->Jwt->get('userId');
        $Departments = $this->getTableLocator()->get('Departments');
        $staff = $Departments->find()
            ->where(['manager_id' => $userId, 'staff_id' => $result['saler_id']])
            ->first();

        if((!empty($staff) && $staff['staff_id'] == $result['saler_id']) || $userId == $result['saler_id'] ){
            $this->G->success('success', $result);
        } else {
            $this->G->error('customer_profile_error');
        }

    }

}
