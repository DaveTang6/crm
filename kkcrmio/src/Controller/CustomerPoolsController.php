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
use App\Event\LogInfoEvents;
use App\Event\CustomerPoolRecycleEvents;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class CustomerPoolsController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
        $LogInfoEvents= new LogInfoEvents();
        $this->getEventManager()->on($LogInfoEvents);
        $CustomerPoolRecycleEvents = new CustomerPoolRecycleEvents();
        $this->getEventManager()->on($CustomerPoolRecycleEvents);
    }

    public function customers() {
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
        $where = [];

        if(!empty($data['wechat'])){
            $where['CustomerPools.wechat LIKE'] = '%' . $data['wechat'] . '%';
        }

        if(!empty($data['truename'])){
            $where['CustomerPools.truename LIKE'] = '%' . $data['truename'] . '%';
        }

        if(!empty($data['level'])){
            $where['CustomerPools.level'] = $data['level'];
        }

        if(isset($data['locked_by_user']) && is_numeric($data['locked_by_user']) && $data['locked_by_user']>0){
            if($data['locked_by_user'] == 1) {
                $where['CustomerPools.locked_by_user_id'] = 0;
            } else {
                $where['CustomerPools.locked_by_user_id >'] = 0;
            }
        }

        if(!empty($data['saler'])){
            $where['CustomerPools.locked_by_user LIKE'] = '%' . $data['saler'] . '%';
        }

        $this->loadComponent('HardConfig');
        $adminControl = $this->HardConfig->get('admin_control');
        $expiredTime = $adminControl['lockedDays'] * 60 * 60 * 24;
        $result= $this->CustomerPools->find()
            ->select(['id' => 'CustomerPools.id',
                'wechat'=> 'CustomerPools.wechat',
                'truename'  => 'CustomerPools.truename',
                'locked_by_user'=> 'CustomerPools.locked_by_user',
                'area'=> 'CustomerPools.area',
                'last_buy'=> 'CustomerPools.last_buy',
                'order_count'=> 'CustomerPools.order_count',
                'locked_time'=> 'CustomerPools.locked_time',
                'expired_time'=> 'CustomerPools.locked_time+' . $expiredTime,
                'memo'=> 'CustomerPools.memo',])
            ->where($where)
            ->order(['CustomerPools.id DESC'])
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result
            ->map(function ($row){
                $row->last_buy = empty($row->last_buy)? '无' : date('Y-m-d', $row->last_buy);
                $row->expired_time = empty($row->locked_time)? '无' : date('Y-m-d', intval($row->expired_time));
                $row->locked_time = empty($row->locked_time)? '无' : date('Y-m-d', $row->locked_time);
                return $row;
            })->toArray();


        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }


    public function updateMemo()
    {
        $id = $this->request->getData('id');
        if(!is_numeric($id)) {
            $this->G->error('id_error');
            return null;
        }
        $this->CustomerPools
            ->query()
            ->update()
            ->set([
                'memo' => $this->request->getData('memo'),
            ])
            ->where(['id' => $id])
            ->execute();
        $this->G->success('Success');
    }


    public  function view()
    {
        $CustomerPool = $this->CustomerPools->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );
        if ($CustomerPool->getErrors()){
            $this->G->error($CustomerPool->getErrors());
            return null;
        }

        $result = $this->CustomerPools->find()
            ->where(['id' => $this->request->getData('id')])
            ->first();
        if(!$result){
            $this->G->error($result);
            return null;
        }

        $this->loadComponent('HardConfig');
        $adminControl = $this->HardConfig->get('admin_control');
        $result['last_buy'] = empty($result['last_buy']) ? '无' : date('Y-m-d', $result['last_buy']);
        $result['add_time'] = date('Y-m-d', $result['add_time']);
        $result['expired_time'] = empty($result['last_buy']) ? '无' : date('Y-m-d', intval($result['locked_time']) + intval($adminControl['lockedDays']) * 60 * 60 * 24);
        $result['locked_time'] = empty($result['locked_time']) ? '无' : date('Y-m-d', $result['locked_time']);
        $this->G->success('success', $result);

    }

    public function releaseCustomers()
    {
        $admin = $this->CustomerPools->newEntity(
            $this->request->getData(),
            ['validate' => 'delete']
        );
        if ($admin->getErrors()){
            $this->G->error($admin->getErrors());
            return null;
        }
        $adminId= $this->Jwt->get('userId');
        $Admins= $this->getTableLocator()->get('Admins');
        $admin = $Admins->get($adminId);
        $adminName = $admin['truename'] ?? $admin['username'];

        $ids = $this->request->getData('ids');
        $errorIds = [];
        foreach($ids as $id) {
            $customerPool = $this->CustomerPools->find()
                ->select(['wechat'])
                ->where(['id' => $id])
                ->first();
            if(!empty($customerPool['wechat'])) {
                $this->CustomerPools
                    ->query()
                    ->update()
                    ->set([
                        'locked_by_user_id' => 0,
                        'locked_by_user' => null,
                        'locked_time' => null,
                    ])
                    ->where(['id' => $id])
                    ->execute();
                $Customers = $this->getTableLocator()->get('Customers');
                $Customers
                    ->query()
                    ->update()
                    ->set([
                        'locked_by_user' => null,
                    ])
                    ->where(['wechat' => $customerPool['wechat']])
                    ->execute();

                $log = [
                    'customerPoolId' => $id,
                    'customerWechat' => $customerPool['wechat'],
                    'act' => 'release',
                    'userId' => $adminId,
                    'username' => $adminName
                ];
                $event = new Event('CustomerPool.addLog', $this, ['data' =>['logInfo' => $log]]);
                $this->getEventManager()->dispatch($event);
            } else {
                $errorIds[] = $id;
            }
        }
        $this->G->success('success', $errorIds);
    }

    public function assignCustomers()
    {
        $admin = $this->CustomerPools->newEntity(
            $this->request->getData(),
            ['validate' => 'delete']
        );
        if ($admin->getErrors()){
            $this->G->error($admin->getErrors());
            return null;
        }

        $salerId = $this->request->getData('salerId');
        $Admins= $this->getTableLocator()->get('Admins');
        $saler = $Admins->find()
            ->select(['truename', 'username'])
            ->where(['id' => $salerId])
            ->first();
        if(empty($saler['username'])){
            $this->G->error('Cannot found saler');
            return null;
        }
        $salerName = $saler['truename'] ?? $saler['username'];

        $adminId= $this->Jwt->get('userId');
        $admin = $Admins->get($adminId);
        $adminName = $admin['truename'] ?? $admin['username'];

        $ids = $this->request->getData('ids');
        $errorIds = [];

        $Customers = $this->getTableLocator()->get('Customers');
        foreach($ids as $id) {
            $customerPool = $this->CustomerPools->find()
                ->select(['wechat'])
                ->where(['id' => $id])
                ->first();
            if(!empty($customerPool['wechat'])) {
                $this->CustomerPools
                    ->query()
                    ->update()
                    ->set([
                        'locked_by_user_id' => $salerId,
                        'locked_by_user' => $salerName,
                        'locked_time' => time(),
                    ])
                    ->where(['id' => $id])
                    ->execute();

                $Customers
                    ->query()
                    ->update()
                    ->set([
                        'locked_by_user' => $salerName,
                    ])
                    ->where(['wechat' => $customerPool['wechat']])
                    ->execute();

                $log = [
                    'customerPoolId' => $id,
                    'customerWechat' => $customerPool['wechat'],
                    'act' => 'assign',
                    'userId' => $adminId,
                    'username' => $adminName
                ];
                $event = new Event('CustomerPool.addLog', $this, ['data' =>['logInfo' => $log]]);
                $this->getEventManager()->dispatch($event);
            } else {
                $errorIds[] = $id;
            }
        }
        $this->G->success('success', $errorIds);
    }

    public function delete()
    {

        $CustomerPool = $this->CustomerPools->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );
        if ($CustomerPool->getErrors()){
            $this->G->error($CustomerPool->getErrors());
            return null;
        }

        $id = $this->request->getData('id');

        // check whether the Order exists
        $customerPool = $this->CustomerPools->find()
            ->where(['id' => $id])
            ->first();
        if(empty($customerPool) || empty($customerPool['id'])){
            $this->G->error(' The customer dose not exist');
            return null;
        }

        $result = $this->CustomerPools->delete($customerPool);
        if(!$result){
            $this->G->error('no_data_deleted');
            return null;
        }

        $Admins= $this->getTableLocator()->get('Admins');
        $adminId= $this->Jwt->get('userId');
        $admin = $Admins->get($adminId);
        $adminName = $admin['truename'] ?? $admin['username'];
        $log = ['customerPoolId' => $customerPool['id'],
            'customerWechat' => $customerPool['wechat'],
            'act' => 'delete',
            'userId' => $adminId,
            'username' => $adminName
        ];

        $event = new Event('CustomerPool.delete', $this, ['data' =>['customer' => $customerPool->toArray(), 'logInfo' => $log]]);
        $this->getEventManager()->dispatch($event);

        $this->G->success('success');
    }

    public function findCustomers() {
        $pageSize = $this->request->getData('pageSize');
        $page = $this->request->getData('page');
        $data = $this->request->getData();
        if(empty($pageSize) || !is_numeric($pageSize) || $pageSize <1 || $pageSize > 100){
            $pageSize = 30;
        }
        if(empty($page) || !is_numeric($page)){
            $page = 1;
        }

        $where = [];

//        if(!empty($data['wechat'])){
//            $where['CustomerPools.wechat LIKE'] = '%' . $data['wechat'] . '%';
//        }
//
//        if(!empty($data['truename'])){
//            $where['CustomerPools.truename LIKE'] = '%' . $data['truename'] . '%';
//        }

        if(empty(trim($data['keyword']))){
            $this->G->error('Keyword is invalid');
            return null;
        }
        $where['or'] = ['CustomerPools.wechat LIKE' => '%' . $data['keyword'] . '%',
            'CustomerPools.truename LIKE' => '%' . $data['keyword'] . '%'
            ];

        $this->loadComponent('HardConfig');
        $adminControl = $this->HardConfig->get('admin_control');
        $expiredTime = $adminControl['lockedDays'] * 60 * 60 * 24;
        $result= $this->CustomerPools->find()
            ->select([
                'wechat'=> 'CustomerPools.wechat',
                'truename'  => 'CustomerPools.truename',
                'locked_by_user'=> 'CustomerPools.locked_by_user',
                'locked_time'=> 'CustomerPools.locked_time',
                'expired_time'=> 'CustomerPools.locked_time+' . $expiredTime,
               ])
            ->where($where)
            ->order(['CustomerPools.id DESC'])
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result->map(function ($row){
            $row->last_buy = empty($row->last_buy)? '无' : date('Y-m-d', $row->last_buy);
            $row->expired_time = empty($row->locked_time)? '无' : date('Y-m-d', intval($row->expired_time));
            $row->locked_time = empty($row->locked_time)? '无' : date('Y-m-d', $row->locked_time);
            return $row;
        })->toArray();


        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }

    //release cutomers who are expired
    public function getTimeoutCustomers() {
        $this->loadComponent('HardConfig');
        $adminControl = $this->HardConfig->get('admin_control');
        $expiredTime = time() - $adminControl['lockedDays'] * 60 * 60 * 24;
        $result = $this->CustomerPools->find()
            ->select(['id', 'wechat', 'truename'])
            ->where(['locked_time <' => $expiredTime])
            ->toArray();

        $Customers = $this->getTableLocator()->get('Customers');
        foreach($result as $v) {
            $this->CustomerPools
                ->query()
                ->update()
                ->set([
                    'locked_by_user_id' => 0,
                    'locked_by_user' => null,
                    'locked_time' => null,
                ])
                ->where(['id' => $v['id']])
                ->execute();

            $Customers
                ->query()
                ->update()
                ->set([
                    'locked_by_user' => null,
                ])
                ->where(['wechat' => $v['wechat']])
                ->execute();

            $log = [
                'customerPoolId' => $v['id'],
                'customerWechat' => $v['wechat'],
                'act' => 'release',
                'userId' => 0,
                'username' => 'System'
            ];
            $event = new Event('CustomerPool.addLog', $this, ['data' =>['logInfo' => $log]]);
            $this->getEventManager()->dispatch($event);
        }
        $this->G->success('success');
    }

    public function statisticAll() {
	    $data = $this->request->getData();
	    if(empty($data)){
            $this->G->error('The range of the time is invalid');
            return null;
        }
	    if(!is_array($data['customerTime'])) {
            $this->G->error('The range of the time is invalid');
            return null;
        }

	    $times = $data['customerTime'];
	    $times[0] = strtotime($times[0]);
	    $times[1] = strtotime($times[1]) + 24*60*60;
        $result = $this->_statistic($times);
        if($result == false){
            $this->G->error('A error occurred during statistics');
        } else {
            $this->G->success('success', $result);
        }

    }

    public function statisticDepartment() {
        $data = $this->request->getData();
        if(empty($data)){
            $this->G->error('The range of the time is invalid');
            return null;
        }
        if(!is_array($data['customerTime'])) {
            $this->G->error('The range of the time is invalid');
            return null;
        }

        $times = $data['customerTime'];
        $times[0] = strtotime($times[0]);
        $times[1] = strtotime($times[1]) + 24*60*60;

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

        $result = $this->_statistic($times, $staffs);
        if($result == false){
            $this->G->error('A error occurred during statistics');
        } else {
            $this->G->success('success', $result);
        }

    }

    public function statisticMine() {
        $data = $this->request->getData();
        if(empty($data)){
            $this->G->error('The range of the time is invalid');
            return null;
        }
        if(!is_array($data['customerTime'])) {
            $this->G->error('The range of the time is invalid');
            return null;
        }

        $times = $data['customerTime'];
        $times[0] = strtotime($times[0]);
        $times[1] = strtotime($times[1]) + 24*60*60;

        $userId = $this->Jwt->get('userId');

        $result = $this->_statistic($times, $userId);
        if($result == false){
            $this->G->error('A error occurred during statistics');
        } else {
            $this->G->success('success', $result);
        }

    }

    private function _statistic($times, $userId= null){
        $where = [];
        $whereLog = [];

        if(!empty($userId)){
            if(is_array($userId)){
                $where['locked_by_user_id IN'] = $userId;
                $whereLog['saler_id IN'] = $userId;
            } else {
                $where['locked_by_user_id'] = $userId;
                $whereLog['saler_id'] = $userId;
            }
        }

        $countAll = $this->CustomerPools->find()
            ->where($where)
            ->count();

        $where['add_time >='] = $times[0];
        $where['add_time <='] = $times[1];
        $whereLog['timestamp >='] = $times[0];
        $whereLog['timestamp <='] = $times[1];

        $countAdd = $this->CustomerPools->find()
            ->where($where)
            ->count();

        $CustomerSaleLogs = $this->getTableLocator()->get('CustomerSaleLogs');
        $countSaleLog = $CustomerSaleLogs->find()
            ->where($whereLog)
            ->count();

        return [
            'all' => $countAll,
            'add' => $countAdd,
            'saleLog' => $countSaleLog
        ];

    }

}
