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
class OrderProductsController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
    }

    //$role: 'user', 'department', 'admin'
    public function getList($role) {

        $data = $this->request->getData();
        if(!isset($data['orderId']) || !is_numeric($data['orderId'])){
            $this->G->error('错误的订单信息');
            return null;
        }
	    $pageSize = $data['pageSize'];
        $page = $data['page'];

        if(empty($pageSize) || !is_numeric($pageSize) || $pageSize <1 || $pageSize > 100){
            $pageSize = 30;
        }
        if(empty($page) || !is_numeric($page)){
            $page = 1;
        }
        if(!isset($data['orderId']) || !is_numeric($data['orderId'])){
            $this->G->error('错误的订单信息');
            return null;
        }

	    $where = ['order_id' => $data['orderId']];

        if($role == 'user' || $role == 'department'){
            $userId = $this->Jwt->get('userId');
            $staffs = [$userId];
            if($role == 'department'){
                $Departments = $this->getTableLocator()->get('Departments');
                $Department = $Departments->find()
                    ->select(['staff_id'])
                    ->where(['manager_id' => $userId])
                    ->toArray();
                foreach($Department as $v) {
                    $staffs[] = $v['staff_id'];
                }
            }
            $orders = $this->getTableLocator()->get('Orders');
            $result = $orders->find()
                ->select(['id'])
                ->where(['id'=>$data['orderId'], 'saler_id IN' => $staffs])
                ->first();
            if(empty($result)){
                $this->G->error('订单不存在');
                return null;
            }
        }

        $result= $this->OrderProducts->find()
            ->where($where)
            ->order(['id DESC'])
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result
            ->map(function ($row){
                $row->total = round($row->total/100, 2);
                $row->cost = round($row->cost/100, 2);
                return $row;
            })->toArray();

        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }

}
