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
class ContractsController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
    }


    public function index() {
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
            $cates=  $this->getTableLocator()->get('ContractCategories');
            $children = $cates->find("children", ['for' => $pid]);
            foreach($children as $vv) {
                $allCateId[] = $vv['id'];
            }
            $where['category_id in'] = $allCateId;
        }

        if(is_numeric($data['status'])){
            $where['status'] = $data['status'];
        }

        $result= $this->Contracts->find()
            ->select(['id','title', 'content', 'file_url','category_id', 'status'])
            ->where($where)
            ->order(['id DESC'])
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result->toArray();

        $ContractCategories = $this->getTableLocator()->get('ContractCategories');
        $categories = $ContractCategories->find('treeList', ['spacer' => ''])->toArray();
        foreach($result as $k=>$v) {
            $result[$k]['category'] = empty($categories[$v['category_id']])? '未分类' : $categories[$v['category_id']];

        }


        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }

    public function add()
    {
        $Contract = $this->Contracts->newEntity($this->request->getData());
        $Contract->add_time = time();
        if($Contract->getErrors()){
            $this->G->error($Contract->getErrors());
            return null;
        }
        if ($this->Contracts->save($Contract)){
            $this->G->success('success', $Contract->id);
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
        $Contract = $this->Contracts->get($id);
        $Contract = $this->Contracts->patchEntity($Contract, $this->request->getData());
        if($Contract->getErrors()){
            $this->G->error($Contract->getErrors());
        }
        if ($this->Contracts->save($Contract)){
            $this->G->success('success');
        } else {
            $this->G->error('update_error');
        }
    }

    public function delete()
    {

        $Contract = $this->Contracts->newEntity(
            $this->request->getData(),
            ['validate' => 'delete']
        );
        if ($Contract->getErrors()){
            $this->G->error($Contract->getErrors());
            return null;
        }
        $result = $this->Contracts->deleteAll(['id IN' =>  $this->request->getData('ids')]);
        if($result == 0){
            $this->G->error('no_data_deleted');
            return null;
        }
        $this->G->success('success');
    }

    public function setStatus()
    {
        $Contract = $this->Contracts->newEntity(
            $this->request->getData(),
            ['validate' => 'update']
        );
        if ($Contract->getErrors()){
            $this->G->error($Contract->getErrors());
            return null;
        }

        $result = $this->Contracts
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

    public  function view()
    {
        $Contract = $this->Contracts->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );
        if ($Contract->getErrors()){
            $this->G->error($Contract->getErrors());
            return null;
        }
        $result = $this->Contracts->get( $this->request->getData('id'));
        if(!$result){
            $this->G->error($result);
            return null;
        }
        $this->G->success('success', $result);
    }

}
