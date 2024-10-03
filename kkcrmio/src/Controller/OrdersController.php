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
use App\Event\CustomerEvents;
use App\Event\LogInfoEvents;
use App\Event\OrderRecycleEvents;
use App\Event\OrderProductEvents;
use PFinal\Excel\Excel;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class OrdersController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
        $CustomerPoolEvents= new CustomerPoolEvents();
        $this->getEventManager()->on($CustomerPoolEvents);
        $CustomerEvents= new CustomerEvents();
        $this->getEventManager()->on($CustomerEvents);
        $LogInfoEvents= new LogInfoEvents();
        $this->getEventManager()->on($LogInfoEvents);
        $OrderRecycleEvents= new OrderRecycleEvents();
        $this->getEventManager()->on($OrderRecycleEvents);

        $OrderProductEvents= new OrderProductEvents();
        $this->getEventManager()->on($OrderProductEvents);

        $this->loadComponent('HardConfig');
    }


    public function myOrders() {
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
	       $where['customer_wechat LIKE'] = '%' . $data['wechat'] . '%';
        }

        if(!empty($data['customer_name'])){
            $where['customer_name LIKE'] = '%' . $data['customer_name'] . '%';
        }

        if(isset($data['check_status']) && is_numeric($data['check_status'])){
            $where['check_status'] = $data['check_status'];
        }

        if(!empty($data['order_no'])){
            $where['order_no'] = $data['order_no'];
        }

        if(!empty($data['order_time'])  && $data['order_time'] != 'null'){
            $in_time = $data['order_time'];
            $in_time[0] = strtotime($in_time[0]);
            $in_time[1] = strtotime($in_time[1]);
            $where['order_time >='] = $in_time[0];
            $where['order_time <='] = $in_time[1];
        }


        if(!empty($data['product_category_id'])){
            $pid = $data['product_category_id'];
            $allCateId = [$pid];
            $cates=  $this->getTableLocator()->get('ProductCategories');
            $children = $cates->find("children", ['for' => $pid]);
            foreach($children as $vv) {
                $allCateId[] = $vv['id'];
            }
            $where['product_category_id in'] = $allCateId;
        }

        if(!empty($data['product_id'])){
            $where['product_id'] = $data['product_id'];
        }

        $result= $this->Orders->find()
            ->select(['id','order_no', 'customer_wechat', 'check_status',
                'settle_status', 'settle_time', 'order_type', 'product_category',
                'customer_name', 'product_name','price', 'num', 'order_time', 'saler_name', 'pay_status'])
            ->where($where)
            ->order(['id DESC'])
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result
            ->map(function ($row){
                $row->settle_time = empty($row->settle_time)? '无' : date('Y-m-d', $row->settle_time);
                $row->order_time = empty($row->order_time)? '无' : date('Y-m-d', $row->order_time);
                $row->price = round($row->price/100, 2);
                return $row;
            })->toArray();

        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }

    public function departmentOrders() {
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
            $where['customer_wechat LIKE'] = '%' . $data['wechat'] . '%';
        }

        if(!empty($data['customer_name'])){
            $where['customer_name LIKE'] = '%' . $data['customer_name'] . '%';
        }

        if(!empty($data['order_no'])){
            $where['order_no'] = $data['order_no'];
        }

        if(!empty($data['order_time'])  && $data['order_time'] != 'null'){
            $in_time = $data['order_time'];
            $in_time[0] = strtotime($in_time[0]);
            $in_time[1] = strtotime($in_time[1]);
            $where['order_time >='] = $in_time[0];
            $where['order_time <='] = $in_time[1];
        }


        if(!empty($data['product_category_id'])){
            $pid = $data['product_category_id'];
            $allCateId = [$pid];
            $cates=  $this->getTableLocator()->get('ProductCategories');
            $children = $cates->find("children", ['for' => $pid]);
            foreach($children as $vv) {
                $allCateId[] = $vv['id'];
            }
            $where['product_category_id in'] = $allCateId;
        }

        if(isset($data['check_status']) && is_numeric($data['check_status'])){
            $where['check_status'] = $data['check_status'];
        }

        if(!empty($data['product_id'])){
            $where['product_id'] = $data['product_id'];
        }

        if(!empty($data['saler_name'])){
            $where['saler_name LIKE'] = '%' .$data['saler_name']. '%';
        }

        $result= $this->Orders->find()
            ->select(['id','order_no', 'customer_wechat', 'customer_name',
                'settle_status', 'settle_time', 'order_type','product_category',
                'product_name','price', 'num', 'order_time', 'saler_name', 'pay_status', 'check_status'])
            ->where($where)
            ->order(['id DESC'])
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result
            ->map(function ($row){
                $row->settle_time = empty($row->settle_time)? '无' : date('Y-m-d', $row->settle_time);
                $row->order_time = empty($row->order_time)? '无' : date('Y-m-d', $row->order_time);
                $row->price = round($row->price/100, 2);
                return $row;
            })->toArray();

        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }

    public function getOrders() {
        $pageSize = $this->request->getData('pageSize');
        $page = $this->request->getData('page');
        $data = $this->request->getData();
        if(empty($pageSize) || !is_numeric($pageSize) || $pageSize <1 || $pageSize > 100){
            $pageSize = 30;
        }
        if(empty($page) || !is_numeric($page)){
            $page = 1;
        }

        //更新老版单支付凭证数据
        if(!empty($data['order_no']) && !empty($data['wechat']) && $data['order_no'] == 'update' && $data['wechat'] == 'pay_proof_url'){
            $this->_formatProof();
            $this->G->success('Success');
            return null;
        }

        //更新老版订单数据
        if(!empty($data['order_no']) && !empty($data['wechat']) && $data['order_no'] == 'update' && $data['wechat'] == 'new_product_orders'){
            $this->_formatProducts();
            $this->G->success('Success');
            return null;
        }
        //================

        $where = [];

        if(!empty($data['wechat'])){
            $where['customer_wechat LIKE'] = '%' . $data['wechat'] . '%';
        }

        if(!empty($data['customer_name'])){
            $where['customer_name LIKE'] = '%' . $data['customer_name'] . '%';
        }

        if(!empty($data['order_no'])){
            $where['order_no'] = $data['order_no'];
        }

        if(isset($data['check_status']) && is_numeric($data['check_status'])){
            $where['check_status'] = $data['check_status'];
        }

        if(isset($data['pay_status']) && is_numeric($data['pay_status'])){
            $where['pay_status'] = $data['pay_status'];
        }

        if(!empty($data['order_time'])  && $data['order_time'] != 'null'){
            $in_time = $data['order_time'];
            $in_time[0] = strtotime($in_time[0]);
            $in_time[1] = strtotime($in_time[1]);
            $where['order_time >='] = $in_time[0];
            $where['order_time <='] = $in_time[1];
        }


        if(!empty($data['product_category_id'])){
            $pid = $data['product_category_id'];
            $allCateId = [$pid];
            $cates=  $this->getTableLocator()->get('ProductCategories');
            $children = $cates->find("children", ['for' => $pid]);
            foreach($children as $vv) {
                $allCateId[] = $vv['id'];
            }
            $where['product_category_id in'] = $allCateId;
        }

        if(!empty($data['product_id'])){
            $where['product_id'] = $data['product_id'];
        }

        if(!empty($data['saler_name'])){
            $where['saler_name LIKE'] = '%' .$data['saler_name']. '%';
        }

        if(isset($data['order_type']) && is_numeric($data['order_type'])){
            $where['order_type'] = $data['order_type'];
        }

        if(isset($data['settle_status']) && is_numeric($data['settle_status'])){
            $where['settle_status'] = $data['settle_status'];
        }

        if(!empty($data['settle_time'])  && $data['settle_time'] != 'null'){
            $in_time = $data['settle_time'];
            $in_time[0] = strtotime($in_time[0]);
            $in_time[1] = strtotime($in_time[1]);
            $where['settle_time >='] = $in_time[0];
            $where['settle_time <='] = $in_time[1];
        }

        $order = [];
        if(isset($data['sortSetting'])){
            if(!empty($data['sortSetting']['field'])){
                $order[$data['sortSetting']['field']] = $data['sortSetting']['order'] == 'ascending' ? 'ASC' : 'DESC';
            }
        } else {
            $order['order_time'] = 'DESC';
        }
        $order['id'] = 'DESC';

        $result= $this->Orders->find()
            ->select(['id','order_no', 'customer_wechat',
                'settle_status', 'settle_time', 'order_type',
                'customer_name', 'product_name', 'product_category',
                'price', 'num', 'cost', 'pay_proof_url',
                'order_time', 'saler_name', 'check_status',
                'pay_status', 'pay_time', 'refund_status', 'refund'])
            ->where($where)
            ->order($order)
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result->map(function ($row){
            $row->settle_time = empty($row->settle_time)? '无' : date('Y-m-d', $row->settle_time);
            $row->order_time = empty($row->order_time)? '无' : date('Y-m-d', $row->order_time);
            $row->pay_time = empty($row->pay_time)? null : date('Y-m-d', $row->pay_time);
            $row->price = round($row->price/100, 2);
            $row->cost = round($row->cost/100, 2);
            $row->refund = round($row->refund/100, 2);
            $row->pay_proof_url = empty($row->pay_proof_url)? null : json_decode($row->pay_proof_url, true);
            return $row;
        })->toArray();

        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }

    public function add()
    {
        $data = $this->request->getData();
        $salerId = $this->Jwt->get('userId');
        $Admins= $this->getTableLocator()->get('Admins');
        $admin = $Admins->get($salerId);
        $salerName = $admin['truename'] ?? $admin['username'];

        //check whether customer is in the customer pools and whether customer belongs to the saler
        $CustomerPools = $this->getTableLocator()->get('CustomerPools');


        $CustomerPool = $CustomerPools->find()
            ->where(['wechat'=>$data['customer_wechat']])
            ->first();

        $customerPoolId = 0;
        if(!empty($CustomerPool)) {
            if($CustomerPool['locked_by_user_id'] !=0 && $CustomerPool['locked_by_user_id']!=$salerId){
                $this->G->error('该客户被他人锁定中');
                return null;
            }
            $customerPoolId = $CustomerPool['id'];
        }

        //check whether customer's profile was complete or not
        $Customers = $this->getTableLocator()->get('Customers');
        $customer = $Customers->find()
            ->where(['wechat'=>$data['customer_wechat'], 'saler_id' => $salerId])
            ->first();
        if(empty($customer)) {
            $this->G->error('请填写相关客户信息后提交订单', 'add');
            return null;
        }

        $requiredFields = ['wechat', 'truename', 'mobile', 'area'];
        $oneFields = ['company', 'department', 'brand'];
        foreach($requiredFields as $v) {
            if(empty($customer[$v])) {
                $this->G->error('请到客户信息中将客户微信号、真实姓名、手机和所在地区填写完整后再提交订单', $customer);
                return null;
            }
        }

//        $i = 0;
//        foreach($oneFields as $v) {
//            if(!empty($customer[$v])) {
//                break;
//            }
//            $i++;
//        }
//
//        if(count($oneFields) == $i){
//            $this->G->error('请到客户信息中将客户公司、地区、品牌三选一填写', $customer);
//            return null;
//        }


        // check whether the product information is correct or not
        $ProductTable = $this->getTableLocator()->get('Products');
        $ProductCategories = $this->getTableLocator()->get('ProductCategories');
        $products = $data['order_products'];
        $data['price'] = 0;
        $data['cost'] = 0;
        $data['num'] = 0;

        foreach($products as $k=>$v){
            $product = $ProductTable->find()
                ->where(['id'=>$v['product_id']])
                ->first();
            $productCategory = $ProductCategories->find()
                ->where(['id'=>$v['category_id']])
                ->first();

            if(empty($product)){
                $this->G->error('没有找到相关的产品信息');
                return null;
            } else {
                $v['product_name'] = $product['title'];
                $v['total'] = round($v['total'] * 100);
                if($product['cost_type'] == 1){
                    $v['cost'] = round($v['total'] * $product['cost']/10000);
                } else {
                    $v['cost'] = $product['cost'] * $v['num'];
                }
                $data['price'] = round($data['price'] + $v['total']);
                $data['cost'] = round($data['cost'] + $v['cost']);
                $data['num'] = round($data['num'] + $v['num']);

            }

            if(empty($productCategory)){
                $this->G->error('产品分类不存在');
                return null;
            } else {
                $v['category_name'] = $productCategory['title'];
            }
            $products[$k] = $v;
        }
        unset($data['order_products']);

        //synchronise customer's profile to customer pool
        $act = $customerPoolId == 0 ? 'CustomerPool.addCustomer' : 'CustomerPool.updateCustomer';
        $log = [
            'customerPoolId' => $customerPoolId,
            'customerWechat' => $customer['wechat'],
            'act' => $customerPoolId == 0 ? 'create' : 'update',
            'userId' => $salerId,
            'username' => $salerName
        ];
        $event = new Event($act, $this, ['data' =>['customer' => $customer, 'customerPoolId' => $customerPoolId, 'logInfo' => $log]]);
        $this->getEventManager()->dispatch($event);
        $result = $event->getResult();
        if(!$result['result']) {
            $this->G->error('An error occurred while saving the customer profile');
            return null;
        }


        $data['order_no'] = date('Ymdi') . str_pad(strval(mt_rand(1,99999)), 5, '0', STR_PAD_LEFT);
        $data['add_time'] = time();
        $data['order_time'] = strtotime($data['order_time']);
        $data['settle_time'] = empty($data['settle_time'])? null : strtotime($data['settle_time']);
        $data['pay_status'] = 0;
        $data['saler_name'] = $salerName;
        $data['saler_id'] = $salerId;
        $data['customer_name'] = $customer['truename'];
        $data['refund'] = 0;
        $data['refund_status'] = 0;
        $data['customer_pool_id'] = $result['id'];
        $data['pay_proof_url'] = empty($data['pay_proof_url'])? null : json_encode($data['pay_proof_url']);
        $productKind = count($products);
        $data['product_name'] = $productKind > 1 ? $products[0]['product_name'] . '等' . $productKind . '种产品' : $products[0]['product_name'];
        $data['product_category'] = $products[0]['category_name'];
        $data['product_id'] = $products[0]['product_id'];
        $data['product_category_id'] = $products[0]['category_id'];

        $Order = $this->Orders->newEntity($data);

        if($Order->getErrors()){
            $this->G->error($Order->getErrors());
            return null;
        }

        if ($this->Orders->save($Order)){

            $log = [
                'orderId' => $Order->id,
                'orderNo' => $Order->order_no,
                'act' => 'create',
                'userId' => $salerId,
                'username' => $salerName
            ];

            $saleInfo = [
                'salerId' => $salerId,
                'salerName' => $salerName,
                'customerPoolId' => $data['customer_pool_id'],
                'customerId' => $customer['id'],
                'customerWechat' => $data['customer_wechat'],
                'orderTime' => $data['order_time']
            ];

            //update relevant information to customer_pool and customer
            $event = new Event('Order.add', $this, ['data' =>['saleInfo' =>$saleInfo, 'logInfo' => $log, 'products'=>$products, 'orderId'=>$Order->id,]]);
            $this->getEventManager()->dispatch($event);
            $this->G->success('success', $Order->id);
        } else {
            $this->G->error('add_error');
        }
    }

    public function update()
    {
        $id = $this->request->getData('id');
        if(!is_numeric($id)) {
            $this->G->error('id_error');
            return null;
        }

        $data = $this->request->getData();
        $salerId = $this->Jwt->get('userId');

        //check whether the order is belong to the saler
        $Order = $this->Orders->find()
            ->where(['id' => $id, 'saler_id' => $salerId])
            ->first();

        if(empty($Order) || empty($Order['id'])){
            $this->G->error('The order cannot found');
            return null;
        }

        if($Order['pay_status'] == 1){
            $this->G->error('订单已被确认，无法修改');
            return null;
        }

        // check whether the product information is correct or not
        $ProductTable = $this->getTableLocator()->get('Products');
        $ProductCategories = $this->getTableLocator()->get('ProductCategories');
        $products = $data['order_products'];
        $data['price'] = 0;
        $data['cost'] = 0;
        $data['num'] = 0;

        foreach($products as $k=>$v){
            $product = $ProductTable->find()
                ->where(['id'=>$v['product_id']])
                ->first();
            $productCategory = $ProductCategories->find()
                ->where(['id'=>$v['category_id']])
                ->first();

            if(empty($product)){
                $this->G->error('没有找到相关的产品信息');
                return null;
            } else {
                $v['product_name'] = $product['title'];
                $v['total'] = round($v['total'] * 100);
                if($product['cost_type'] == 1){
                    $v['cost'] = round($v['total'] * $product['cost']/10000);
                } else {
                    $v['cost'] = $product['cost'] * $v['num'];
                }
                $data['price'] = round($data['price'] + $v['total']);
                $data['cost'] = round($data['cost'] + $v['cost']);
                $data['num'] = round($data['num'] + $v['num']);

            }

            if(empty($productCategory)){
                $this->G->error('产品分类不存在');
                return null;
            } else {
                $v['category_name'] = $productCategory['title'];
            }
            $products[$k] = $v;
        }
        unset($data['order_products']);


        if(!empty($data['order_no'])){
            unset($data['order_no']);
        }
        if(!empty($data['add_time'])){
            unset($data['add_time']);
        }
        if(!empty($data['saler_id'])){
            unset($data['saler_id']);
        }
        if(!empty($data['saler_name'])){
            unset($data['saler_name']);
        }
        if(!empty($data['customer_name'])){
            unset($data['customer_name']);
        }
        if(!empty($data['refund'])){
            unset($data['refund']);
        }
        if(!empty($data['refund_status'])){
            unset($data['refund_status']);
        }
        if(!empty($data['customer_pool_id'])){
            unset($data['customer_pool_id']);
        }
        if(!empty($data['pay_status'])){
            unset($data['pay_status']);
        }
        $data['order_time'] = strtotime($data['order_time']);
        $data['check_status'] = 0;
        $data['settle_time'] = empty($data['settle_time'])? null : strtotime($data['settle_time']);
        $data['pay_proof_url'] = empty($data['pay_proof_url'])? null : json_encode($data['pay_proof_url']);
        $productKind = count($products);
        $data['product_name'] = $productKind > 1 ? $products[0]['product_name'] . '等' . $productKind . '种产品' : $products[0]['product_name'];
        $data['product_category'] = $products[0]['category_name'];
        $data['product_id'] = $products[0]['product_id'];
        $data['product_category_id'] = $products[0]['category_id'];

        $Order = $this->Orders->patchEntity($Order, $data);

        if($Order->getErrors()){
            $this->G->error($Order->getErrors());
            return null;
        }

        if ($this->Orders->save($Order)){

            $log = [
                'orderId' => $Order->id,
                'orderNo' => $Order->order_no,
                'userId' => $Order->saler_id,
                'username' => $Order->saler_name,
                'act' => 'update',
            ];

            $adminControl = $this->HardConfig->get('admin_control');
            $saleInfo = [
                'salerId' => $salerId,
                'customerPoolId' => $Order['customer_pool_id'],
                'customerWechat' => $Order['customer_wechat'],
                'availableTime' =>$adminControl['lockedDays'] * 60 * 60 * 24
            ];

            //update relevant information to customer_pool and customer
            $event = new Event('Order.update', $this, ['data' =>[
                'saleInfo' =>$saleInfo,
                'logInfo' => $log,
                'products'=>$products,
                'orderId'=>$Order->id]]);
            $this->getEventManager()->dispatch($event);
            $this->G->success('success', $Order);
        } else {
            $this->G->error('add_error');
        }
    }

    public function delete()
    {

        $Order = $this->Orders->newEntity(
            $this->request->getData(),
            ['validate' => 'delete']
        );
        if ($Order->getErrors()){
            $this->G->error($Order->getErrors());
            return null;
        }

        $id = $this->request->getData('id');
        $salerId = $this->Jwt->get('userId');

        // check whether the Order exists
        $order = $this->Orders->find()
            ->where(['id' => $id])
            ->first();
        if(empty($order) || empty($order['id'])){
            $this->G->error(' The order dose not exist');
            return null;
        }

        if($order['saler_id'] != $salerId) {
            $this->G->error(' The order dose not belong to you');
            return null;
        }

        if($order['pay_status'] == 1) {
            $this->G->error(' The order cannot be deleted with financial confirmed');
            return null;
        }

        $result = $this->Orders->delete($order);
        if(!$result){
            $this->G->error('no_data_deleted');
            return null;
        }

        $adminControl = $this->HardConfig->get('admin_control');
        $saleInfo = [
            'salerId' => $order['saler_id'],
            'customerPoolId' => $order['customer_pool_id'],
            'customerWechat' => $order['customer_wechat'],
            'availableTime' =>$adminControl['lockedDays'] * 60 * 60 * 24
        ];


        $log = [
            'orderId' => $order['id'],
            'orderNo' => $order['order_no'],
            'act' => 'delete',
            'userId' => $order['saler_id'],
            'username' => $order['saler_name']
        ];

        $event = new Event('Order.delete', $this, ['data' =>['orderId'=> $order['id'], 'saleInfo' => $saleInfo, 'order' => $order->toArray(), 'logInfo' => $log]]);
        $this->getEventManager()->dispatch($event);

        $this->G->success('success');
    }



    public  function view()
    {
        $Order = $this->Orders->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );
        if ($Order->getErrors()){
            $this->G->error($Order->getErrors());
            return null;
        }
        //$result = $this->Orders->get( $this->request->getData('id'));
        $result = $this->Orders->find()
            ->where(['id' => $this->request->getData('id')])
            ->first();
        if(!$result){
            $this->G->error($result);
            return null;
        }
        $result['order_time'] = empty($result['order_time'])? '' : date('Y-m-d', $result['order_time']);
        $result['add_time'] = date('Y-m-d', $result['add_time']);
        $result['settle_time'] = empty($result['settle_time'])? '' : date('Y-m-d', $result['settle_time']);
        $result['price'] = round($result['price']/100, 2);
        $result['refund'] = round($result['refund']/100, 2);
        $result['pay_proof_url'] = empty($result['pay_proof_url'])? [] : json_decode($result['pay_proof_url'], true);
        $result['pay_time'] = empty($result['pay_time'])? '' : date('Y-m-d', $result['pay_time']);

        $Departments = $this->getTableLocator()->get('Departments');
        $userId = $this->Jwt->get('userId');
        $staff = $Departments->find()
            ->where(['manager_id' => $userId, 'staff_id' => $result['saler_id']])
            ->first();

        if((!empty($staff) && $staff['staff_id'] == $result['saler_id']) || $userId == $result['saler_id'] ){
            unset($result['cost']);
            $this->G->success('success', $result);
        } else {
            $this->G->error('customer_profile_error');
        }
    }

    public  function viewPlus()
    {
        $Order = $this->Orders->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );
        if ($Order->getErrors()){
            $this->G->error($Order->getErrors());
            return null;
        }

        $result = $this->Orders->find()
            ->where(['id' => $this->request->getData('id')])
            ->first();
        if(!$result){
            $this->G->error($result);
            return null;
        }
        $result['order_time'] = empty($result['order_time'])? '' : date('Y-m-d', $result['order_time']);
        $result['pay_time'] = empty($result['pay_time'])? '' : date('Y-m-d', $result['pay_time']);
        $result['add_time'] = date('Y-m-d', $result['add_time']);
        $result['settle_time'] = empty($result['settle_time'])? '' : date('Y-m-d', $result['settle_time']);
        $result['price'] = round($result['price']/100, 2);
        $result['cost'] = round($result['cost']/100, 2);
        $result['refund'] = round($result['refund']/100, 2);
        $result['pay_proof_url'] = json_decode($result['pay_proof_url'], true);
        $this->G->success('success', $result);

    }

    public function updateFinance() {
	    $data = $this->request->getData();
        $Order = $this->Orders->newEntity(
            $data,
            ['validate' => 'finance']
        );
        if ($Order->getErrors()){
            $this->G->error($Order->getErrors());
            return null;
        }
        $Admins= $this->getTableLocator()->get('Admins');
        $adminId = $this->Jwt->get('userId');
        $admin = $Admins->find()
            ->where(['id' => $adminId])
            ->first();
        $adminName = $admin['truename'] ?? $admin['username'];

        $order = $this->Orders->find()
            ->where(['id' => $data['id']])
            ->first();

        if(empty($order)) {
            $this->G->error('Cannot found the order');
            return null;
        }

        $this->Orders
            ->query()
            ->update()
            ->set([
                'pay_status' => $data['pay_status'],
                'refund_status' => $data['refund_status'],
                'settle_status' => $data['settle_status'],
                'refund' => empty($data['refund'])? 0 : round($data['refund'] * 100),
                'pay_time' => empty($data['pay_time'])? null : strtotime($data['pay_time']),
                'settle_time' => (empty($data['settle_time']) || $data['settle_time'] == '无')? null : strtotime($data['settle_time']),
                ])
            ->where(['id' => $data['id']])
            ->execute();

        if($data['pay_status'] != $order['pay_status']) {
            $log = [
                'orderId' => $order['id'],
                'orderNo' => $order['order_no'],
                'act' => 'pay',
                'userId' => $adminId,
                'username' => $adminName
            ];

            $event = new Event('Order.addLog', $this, ['data' =>['logInfo' => $log]]);
            $this->getEventManager()->dispatch($event);
        }
        if($data['refund_status'] != $order['refund_status']) {
            $log = [
                'orderId' => $order['id'],
                'orderNo' => $order['order_no'],
                'act' => 'refund',
                'userId' => $adminId,
                'username' => $adminName
            ];

            $event = new Event('Order.addLog', $this, ['data' =>['logInfo' => $log]]);
            $this->getEventManager()->dispatch($event);
        }

        $this->G->success('success');
    }

    public function updateCost() {
        $data = $this->request->getData();
        $Order = $this->Orders->newEntity(
            $data,
            ['validate' => 'cost']
        );
        if ($Order->getErrors()){
            $this->G->error($Order->getErrors());
            return null;
        }
        $Admins= $this->getTableLocator()->get('Admins');
        $adminId = $this->Jwt->get('userId');
        $admin = $Admins->find()
            ->where(['id' => $adminId])
            ->first();
        $adminName = $admin['truename'] ?? $admin['username'];

        $order = $this->Orders->find()
            ->where(['id' => $data['id']])
            ->first();

        if(empty($order)) {
            $this->G->error('Cannot found the order');
            return null;
        }

        if($order['pay_status'] == 1) {
            $this->G->error('Order has been confirmed');
            return null;
        }

        $this->Orders
            ->query()
            ->update()
            ->set([
                'cost' => round($data['cost'] * 100)
            ])
            ->where(['id' => $data['id']])
            ->execute();

        $log = [
            'orderId' => $order['id'],
            'orderNo' => $order['order_no'],
            'act' => 'cost',
            'userId' => $adminId,
            'username' => $adminName
        ];

        $event = new Event('Order.addLog', $this, ['data' =>['logInfo' => $log]]);
        $this->getEventManager()->dispatch($event);

        $this->G->success('success');
    }

    public function statisticAll() {
        $data = $this->request->getData();
        if(empty($data)){
            $this->G->error('The range of the time is invalid');
            return null;
        }
        if(!is_array($data['orderTime'])) {
            $this->G->error('The range of the time is invalid');
            return null;
        }

        $times = $data['orderTime'];
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
        if(!is_array($data['orderTime'])) {
            $this->G->error('The range of the time is invalid');
            return null;
        }

        $times = $data['orderTime'];
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
            unset($result['profit']);
            $this->G->success('success', $result);
        }

    }

    public function statisticMine() {
        $data = $this->request->getData();
        if(empty($data)){
            $this->G->error('The range of the time is invalid');
            return null;
        }
        if(!is_array($data['orderTime'])) {
            $this->G->error('The range of the time is invalid');
            return null;
        }

        $times = $data['orderTime'];
        $times[0] = strtotime($times[0]);
        $times[1] = strtotime($times[1]) + 24*60*60;

        $userId = $this->Jwt->get('userId');

        $result = $this->_statistic($times, $userId);
        if($result == false){
            $this->G->error('A error occurred during statistics');
        } else {
            unset($result['profit']);
            $this->G->success('success', $result);
        }

    }

    public function updateStatus($status = null) {
        $data = $this->request->getData();
        $Order = $this->Orders->newEntity(
            $data,
            ['validate' => 'check']
        );
        if ($Order->getErrors()){
            $this->G->error($Order->getErrors());
            return null;
        }
        $Admins= $this->getTableLocator()->get('Admins');
        $adminId = $this->Jwt->get('userId');
        $admin = $Admins->find()
            ->where(['id' => $adminId])
            ->first();
        $adminName = $admin['truename'] ?? $admin['username'];

        $order = $this->Orders->find()
            ->select(['check_status', 'id', 'order_no'])
            ->where(['id IN' => $data['id']])
            ->toArray();

        if(count($order)!=count($data['id'])) {
            $this->G->error('订单信息错误');
            return null;
        }
        $statusArr = [
            '-1' => '审核不通过',
            1=> '初审通过',
            2=> '复审通过'
        ];

        foreach($order as $v){
            if($status != $v['check_status'] || !empty($data['memo'])) {
                $this->Orders
                    ->query()
                    ->update()
                    ->set([
                        'check_status' => $status,
                    ])
                    ->where(['id' => $v['id']])
                    ->execute();
                $log = [
                    'orderId' => $v['id'],
                    'orderNo' => $v['order_no'],
                    'act' => 'check',
                    'userId' => $adminId,
                    'username' => $adminName,
                    'memo' => $statusArr[$status] . ' ' .$data['memo']
                ];

                $event = new Event('Order.addLog', $this, ['data' =>['logInfo' => $log]]);
                $this->getEventManager()->dispatch($event);
            }

        }

        $this->G->success('success');
    }

    //$role: null-all data, director- 对应部门的
    public function getExcel($role=null) {

        $data = $this->request->getQueryParams();
        $where = [];
        $order = ['id DESC'];

        $where = [];

        if(!empty($data['wechat'])){
            $where['customer_wechat LIKE'] = '%' . $data['wechat'] . '%';
        }

        if(!empty($data['customer_name'])){
            $where['customer_name LIKE'] = '%' . $data['customer_name'] . '%';
        }

        if(!empty($data['order_no'])){
            $where['order_no'] = $data['order_no'];
        }

        if(isset($data['check_status']) && is_numeric($data['check_status'])){
            $where['check_status'] = $data['check_status'];
        }

        if(!empty($data['order_time'])  && $data['order_time'] != 'null'){
            $in_time = explode(',', $data['order_time']);
            $in_time[0] = strtotime($in_time[0]);
            $in_time[1] = strtotime($in_time[1]);
            $where['order_time >='] = $in_time[0];
            $where['order_time <='] = $in_time[1];
        }

        if(!empty($data['product_category_id'])){
            $pid = $data['product_category_id'];
            $allCateId = [$pid];
            $cates=  $this->getTableLocator()->get('ProductCategories');
            $children = $cates->find("children", ['for' => $pid]);
            foreach($children as $vv) {
                $allCateId[] = $vv['id'];
            }
            $where['product_category_id in'] = $allCateId;
        }

        if(!empty($data['product_id'])){
            $where['product_id'] = $data['product_id'];
        }

        if(!empty($data['saler_name'])){
            $where['saler_name LIKE'] = '%' .$data['saler_name']. '%';
        }

        if(isset($data['order_type']) && is_numeric($data['order_type'])){
            $where['order_type'] = $data['order_type'];
        }

        if($role=='director'){
            $userId = $this->Jwt->get('userId');
            $Departments = $this->getTableLocator()->get('Departments');
            $staffs = $Departments->find()
                ->select(['staff_id'])
                ->distinct()
                ->where(['manager_id'=>$userId]);
            $where['saler_id IN'] = $staffs;
        }

        $result= $this->Orders->find()
            ->where($where)
            ->order($order);

        $result = $result->toArray();
        $checkStatusArr = [
            0=> '未审核',
            1=>'初审通过',
            2=> '复审通过',
            '-1' => '未通过'
        ];
        foreach($result as $k=>$v) {
            $result[$k]['price'] = round($v['price']/100, 2);
            $result[$k]['cost'] = round($v['cost']/100, 2);
            $result[$k]['refund'] = round($v['refund']/100, 2);
            $result[$k]['profit'] = round(($v['price']-$v['cost']-$v['refund']), 2);
            $result[$k]['order_time'] = empty($v['order_time']) ? '' : date('Y-m-d', $v['order_time']);
            $result[$k]['pay_time'] = empty($v['pay_time']) ? '' : date('Y-m-d', $v['pay_time']);
            $result[$k]['add_time'] = empty($v['add_time']) ? '' : date('Y-m-d', $v['add_time']);
            $result[$k]['refund_status'] = $v['refund_status'] > 0 ? '有' : '无';
            $result[$k]['pay_status'] = $v['pay_status'] > 0 ? '已确认' : '未确认';
            $result[$k]['check_status'] = $checkStatusArr[$v['check_status']];
            $result[$k]['order_no'] = 'K' . $v['order_no'];
            $result[$k]['order_type'] = $v['order_type'] > 0 ? '全款' : '定金';
            $result[$k]['settle_status'] = $v['settle_status'] > 0 ? '已结算' : '未结算';
            $result[$k]['settle_time'] = empty($result[$k]['settle_time'])? '无' : date('Y-m-d', $result[$k]['settle_time']);

            unset($result[$k]['id']);
            unset($result[$k]['product_id']);
            unset($result[$k]['product_category_id']);
            unset($result[$k]['contract_url']);
            unset($result[$k]['pay_proof_url']);
            unset($result[$k]['saler_id']);
            if($role=='director'){
                unset($result[$k]['cost']);
                unset($result[$k]['profit']);
            }
        }

        $excelTitle = [
            'order_no' => '订单号',
            'customer_wechat' => '客户微信号',
            'product_name' => '购买产品',
            'product_category' => '产品类别',
            'num' => '购买数量',
            'price' => '销售价格(元)',
            'cost' => '成本(元)',
            'profit' => '利润(元)',
            'pay_status' => '支付确认',
            'refund_status' => '是否退款',
            'refund' => '退款金额(元)',
            'order_time' => '下单时间',
            'pay_time' => '支付时间',
            'add_time' => '订单生成时间',
            'saler_name' => '销售人',
            'check_status' => '审核状态',
        ];

        if($role=='director'){
            unset($excelTitle['cost']);
            unset($excelTitle['profit']);
        }

        $map = [
            'title'=> $excelTitle,
        ];

        $file = 'orders_' . date('Y-m-d');

        //浏览器直接下载
        Excel::exportExcel($result, $map, $file, '订单信息');
        exit();

    }

    private function _statistic($times, $userId= null){
        $where = [
            'order_time >=' => $times[0],
            'order_time <=' => $times[1],
            'pay_status' => 1
        ];

        if(!empty($userId)){
            if(is_array($userId)){
                $where['saler_id IN'] = $userId;
            } else {
                $where['saler_id'] = $userId;
            }
        }

        $countAll = $this->Orders->find()
            ->select(['orders' => 'COUNT(id)', 'turnover' => 'SUM(price)', 'refunds' => 'SUM(refund)', 'costs' => 'SUM(cost)'])
            ->where($where)
            ->first();

        return [
            'orders' => $countAll['orders'],
            'profit' => round(($countAll['turnover'] - $countAll['costs'] - $countAll['refunds'])/100,2),
            'turnover' => round($countAll['turnover']/100, 2),
        ];

    }

    private function _formatProof(){
	    $order = $this->Orders->find()
            ->select(['id', 'pay_proof_url']);
        $order=$order->toArray();
	    foreach($order as $v){
	        if(empty($v['pay_proof_url']) || strpos($v['pay_proof_url'], '[') !== false){
	            continue;
            }
	        $proof = [$v['pay_proof_url']];
            $this->Orders
                ->query()
                ->update()
                ->set([
                    'pay_proof_url' => json_encode($proof),
                ])
                ->where(['id' => $v['id']])
                ->execute();
        }

    }

    private function _formatProducts(){
	    $order = $this->Orders->find()
            ->select(['id', 'product_id', 'product_category_id', 'price', 'cost', 'num', 'product_name', 'product_category']);
        $order=$order->toArray();

	    foreach($order as $v){
            $products = [];
            $products[] = [
                'category_id' => $v['product_category_id'],
                'product_id' => $v['product_id'],
                'category_name' => $v['product_category'],
                'product_name' => $v['product_name'],
                'total' => $v['price'],
                'cost' => $v['cost'],
                'num' => $v['num'],
            ];
            $event = new Event('Order.productUpdate', $this, ['data' =>['products' =>$products, 'orderId' => $v['id']]]);
            $this->getEventManager()->dispatch($event);
        }
    }

}
