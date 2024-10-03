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


use Cake\Collection\Collection;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class ContractCategoriesController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
    }

    //获取所以分类列表
    public function getKeyPath()
    {
        $query = $this->ContractCategories->find('treeList', [
            'keyPath' => 'id',
            'valuePath' => 'title',
            'spacer' => '— '
        ]);
        $data = [];
        foreach($query as $k=>$v){
            $data[] = ['id'=> $k, 'title'=> $v];
        }
        $this->G->success('Success', ['list'=>$data]);
    }

	//获取所以分类列表
    public function getList()
    {
        $data = $this->ContractCategories->find()
            ->select(['id','title','parent_id','status'])
            ->order(['lft ASC'])
            ->toArray();
        if(!empty($data)){
            $collection = new Collection($data);
            $data = $collection->nest('id', 'parent_id')->toList();
            foreach ($data as $k=>$v){
                $data[$k] = $this->_format($v);
            }
        }
        $this->G->success('Success', ['list'=>$data]);
    }

	public function view(){
        $imgCategory = $this->ContractCategories->get($this->request->getData('id'));;
        $this->G->success('Success', $imgCategory);
	}

	//新增分类
	public function add()
	{

		$imgCategory = $this->ContractCategories->newEntity($this->request->getData());

		if ($this->ContractCategories->save($imgCategory)){
			$this->G->success('success', $imgCategory->id);
		} else {
			$this->G->error($imgCategory->getErrors());
		}

	}

		//修改分类
	public function update()
	{
        $id = $this->request->getData('id');
        if(!is_numeric($id)) {
            $this->G->error('id_error');
            return null;
        }
        $ContractCategories = $this->getTableLocator()->get('ContractCategories');
		$imgCategory = $ContractCategories->get($id);
        $ContractCategories->patchEntity($imgCategory, $this->request->getData());
        if ($imgCategory->getErrors()) {
            $this->G->error($imgCategory->getErrors());
        }

		if ($ContractCategories->save($imgCategory)){
			$this->G->success('success');
		} else {
			$this->G->error('save_error');
		}

	}
	//删除分类
	public function delete()
    {
        $id = $this->request->getData("id");
        if(!is_numeric($id)){
            $this->G->error("id_error");
            return null;
        }
		$imgCategory = $this->ContractCategories->get($id);
        $this->ContractCategories->removeFromTree($imgCategory);
        $this->ContractCategories->delete($imgCategory);
        //修改所有该id对应的内容的父id为0
        $this->G->success('success');
    }

	//移动分类
	 public function move()
    {

        $id = $this->request->getData("id");
        $action = $this->request->getData("action");
        if(!is_numeric($id)){
            $this->G->error("id_error");
            return null;
        }
        if(!in_array($action, ['up','down'])){
            $this->G->error("action_error");
            return null;
        }
        $imgCategory = $this->ContractCategories->get($id);
        if ($action == 'up'){
            $result = $this->ContractCategories->moveUp($imgCategory);
        } else {
            $result = $this->ContractCategories->moveDown($imgCategory);
        }

        if(is_string($result)){
            $this->G->error($result);
            return null;
        }
        $this->G->success('Success', $result);
    }

    public function _format($item = [])
    {
        if(!empty($item['children'])){
            foreach ($item['children'] as $k=>$v){
                $item['children'][$k] = $this->_format($v);
            }
        }else{
            unset($item['children']);
        }
        return $item;
    }

}
