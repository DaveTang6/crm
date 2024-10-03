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
class ProductsController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
    }


    public function index($type = null) {
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
	    if(!empty($data['title'])){
	       $where['title LIKE'] = '%' . $data['title'] . '%';
        }

        if(!empty($data['category_id'])){
            $pid = $data['category_id'];
            $allCateId = [$pid];
            $cates=  $this->getTableLocator()->get('ProductCategories');
            $children = $cates->find("children", ['for' => $pid]);
            foreach($children as $vv) {
                $allCateId[] = $vv['id'];
            }
            $where['category_id in'] = $allCateId;
        }

        if(!empty($data['status']) && is_numeric($data['status'])){
            $where['status'] = $data['status'];
        }

        if($type == 'limit'){
            $where['status'] = 1;
        }

        $result= $this->Products->find()
            ->select(['id','title', 'price', 'cost','cost_type','category_id', 'status'])
            ->where($where)
            ->order(['id DESC'])
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result
            ->map(function ($row) use ($type){
                $row->price = round($row->price/100, 2);
                if($type == 'limit') {
                    unset($row->cost);
                } else {
                    $row->cost = round($row->cost/100, 2);
                }
                return $row;
            })->toArray();

        $ProductCategories = $this->getTableLocator()->get('ProductCategories');
        $categories = $ProductCategories->find('treeList', ['spacer' => ''])->toArray();
        foreach($result as $k=>$v) {
            $result[$k]['category'] = empty($categories[$v['category_id']])? '未分类' : $categories[$v['category_id']];

        }


        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }


    public function add()
    {
        $data = $this->request->getData();
        $data['price'] = round($data['price'] * 100);
        $data['cost'] = round($data['cost'] * 100);
        $Product = $this->Products->newEntity($data);
        if($Product->getErrors()){
            $this->G->error($Product->getErrors());
            return null;
        }
        $Product->add_time = time();
        if ($this->Products->save($Product)){
            $this->G->success('success', $Product->id);
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
        $data['price'] = round($data['price'] * 100);
        $data['cost'] = round($data['cost'] * 100);
        $Product = $this->Products->get($id);
        $Product = $this->Products->patchEntity($Product, $data);
        if($Product->getErrors()){
            $this->G->error($Product->getErrors());
        }
        if ($this->Products->save($Product)){
            $this->G->success('success');
        } else {
            $this->G->error('update_error');
        }
    }

    public function delete()
    {

        $Product = $this->Products->newEntity(
            $this->request->getData(),
            ['validate' => 'delete']
        );
        if ($Product->getErrors()){
            $this->G->error($Product->getErrors());
            return null;
        }
        $result = $this->Products->deleteAll(['id IN' =>  $this->request->getData('ids')]);
        if($result == 0){
            $this->G->error('no_data_deleted');
            return null;
        }
        $this->G->success('success');
    }

    public function setStatus()
    {
        $Product = $this->Products->newEntity(
            $this->request->getData(),
            ['validate' => 'update']
        );
        if ($Product->getErrors()){
            $this->G->error($Product->getErrors());
            return null;
        }

        $result = $this->Products
            ->query()
            ->update()
            ->set(['status' => $this->request->getData('status')])
            ->where(['id IN' => $this->request->getData('ids')])
            ->execute();
        if(!$result){
            $this->G->error($result);
            return null;
        }
        $this->G->success('success');
    }

    public  function view($type = null)
    {
        $Product = $this->Products->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );
        if ($Product->getErrors()){
            $this->G->error($Product->getErrors());
            return null;
        }
        $result = $this->Products->find()
        ->where( ['id' => $this->request->getData('id')])
        ->first();
        if(!$result){
            $this->G->error($result);
            return null;
        }
        $result['price'] = round($result['price'] / 100,2);
        if($type == 'limit') {
            unset($result['cost']);
        } else {
            $result['cost'] = round($result['cost'] / 100,2);
        }
        $this->G->success('success', $result);
    }



}
