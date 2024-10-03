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
class OrderActLogsController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
    }


    public function actLogs() {
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
	    if(!empty($data['act_user'])){
	       $where['act_user LIKE'] = '%' . $data['act_user'] . '%';
        }

        if(!empty($data['order_no'])){
            $where['order_no'] = $data['order_no'];
        }

        if(!empty($data['add_time'])  && $data['add_time'] != 'null'){
            $in_time = $data['add_time'];
            $in_time[0] = strtotime($in_time[0]);
            $in_time[1] = strtotime($in_time[1]) + 60*60*24;
            $where['add_time >='] = $in_time[0];
            $where['add_time <='] = $in_time[1];
        }

        if(!empty($data['act'])){
            $where['act'] = $data['act'];
        }

        $result= $this->OrderActLogs->find()
            ->where($where)
            ->order(['id DESC'])
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result
            ->map(function ($row){
                $row->add_time = empty($row->add_time)? '无' : date('Y-m-d H:i:s', $row->add_time);
                return $row;
            })->toArray();

        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }

    public function checkLogs() {

	    $data = $this->request->getData();

        if(!isset($data['order_id']) || !is_numeric($data['order_id'])){
            $this->G->error('No related order');
            return null;
        }
        $where = [
            'act'=> 'check'
        ];
        $where['order_id'] = $data['order_id'];
        $result= $this->OrderActLogs->find()
            ->where($where)
            ->order(['id DESC'])
            ->map(function ($row){
                $row->add_time = empty($row->add_time)? '无' : date('Y-m-d H:i:s', $row->add_time);
                return $row;
            });

        $result = $result->toArray();

        $this->G->success('Success', ['list'=>$result]);
    }

}
