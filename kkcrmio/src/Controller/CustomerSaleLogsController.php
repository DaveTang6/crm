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


/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class CustomerSaleLogsController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
    }


    public function index() {

	    $customerId = $this->request->getData('customer_id');
	    $where = ['customer_id' => $customerId];

        $result= $this->CustomerSaleLogs->find()
            ->where($where)
            ->order(['timestamp DESC', 'id DESC'])
            ->map(function ($row){
                $row->timestamp = date('Y-m-d', $row->timestamp);
                return $row;
            });

        $result = $result->toArray();

        if(!empty($result)) {
            $salerId = $result[0]['saler_id'];
            $userId = $this->Jwt->get('userId');
            if($salerId != $userId) {
                $Departments = $this->getTableLocator()->get('Departments');
                $staff = $Departments->find()
                    ->where(['manager_id' => $userId, 'staff_id' => $salerId])
                    ->first();
                if(!empty($staff) && $staff['staff_id'] != $salerId) {
                    $this->G->error('customer_profile_error');
                    return null;
                }
            }
        }

        $this->G->success('Success', ['list'=>$result]);
    }

    public function add()
    {
        $CustomerSaleLog = $this->CustomerSaleLogs->newEntity($this->request->getData());
        $CustomerSaleLog->saler_id = $this->Jwt->get('userId');
        $CustomerSaleLog->timestamp = strtotime($this->request->getData('timestamp'));
        if($CustomerSaleLog->getErrors()){
            $this->G->error($CustomerSaleLog->getErrors());
            return null;
        }

        $Customers= $this->getTableLocator()->get('Customers');
        $customer = $Customers->find()
            ->where(['id' => $CustomerSaleLog->customer_id, 'saler_id' => $CustomerSaleLog->saler_id])
            ->first();
        if(empty($customer)) {
            $this->G->error('customer_profile_error');
            return null;
        }

        if ($this->CustomerSaleLogs->save($CustomerSaleLog)){
            $this->G->success('success', $CustomerSaleLog->id);
        } else {
            $this->G->error('add_error');
        }
    }

    public function update()
    {
        $id = $this->request->getData('id');
        $data =  $this->request->getData();
        $data['timestamp'] = $data['timestamp'] ? strtotime($data['timestamp']) : '';
        if(!is_numeric($id)) {
            $this->G->error('id_error');
            return null;
        }
        $CustomerSaleLog = $this->CustomerSaleLogs->get($id);
        if($CustomerSaleLog['saler_id'] != $this->Jwt->get('userId')) {
            $this->G->error('CustomerSaleLog_profile_error');
            return null;
        }
        unset($data['customer_id']);
        unset($data['saler_id']);
        $CustomerSaleLog = $this->CustomerSaleLogs->patchEntity($CustomerSaleLog, $data);
        if($CustomerSaleLog->getErrors()){
            $this->G->error($CustomerSaleLog->getErrors());
        }
        if ($this->CustomerSaleLogs->save($CustomerSaleLog)){
            $this->G->success('success');
        } else {
            $this->G->error('update_error');
        }
    }

    public function delete()
    {

        $CustomerSaleLog = $this->CustomerSaleLogs->newEntity(
            $this->request->getData(),
            ['validate' => 'delete']
        );
        if ($CustomerSaleLog->getErrors()){
            $this->G->error($CustomerSaleLog->getErrors());
            return null;
        }
        $result = $this->CustomerSaleLogs->deleteAll(['id IN' =>  $this->request->getData('ids'), 'saler_id' => $this->Jwt->get('userId')]);
        if($result == 0){
            $this->G->error('no_data_deleted');
            return null;
        }
        $this->G->success('success');
    }


    public  function view()
    {
        $CustomerSaleLog = $this->CustomerSaleLogs->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );
        if ($CustomerSaleLog->getErrors()){
            $this->G->error($CustomerSaleLog->getErrors());
            return null;
        }

        $result = $this->CustomerSaleLogs->get( $this->request->getData('id'));
        if(!$result){
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
            $this->G->error('CustomerSaleLog_profile_error');
        }

    }

}
