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
use Cake\Cache\Cache;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class AdminAcosController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
    }

    //获取所以分类列表
    public function getKeyPath()
    {
        $query = $this->AdminAcos->find('treeList', [
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
        $data = $this->AdminAcos->find()
            ->select(['id','title','parent_id','status', 'alias'])
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
        $adminAcos = $this->AdminAcos->get($this->request->getData('id'));
        if($adminAcos) {
            $adminAcos['memo'] = json_decode($adminAcos['memo'], true);
            $this->G->success('Success', $adminAcos);
        } else {
            $this->G->error($adminAcos->getErrors());
        }

	}

	//新增分类
	public function add()
	{
        $data = $this->request->getData();
        $data['memo'] = json_encode($data['memo']);
		$adminAcos = $this->AdminAcos->newEntity($data);

		if ($this->AdminAcos->save($adminAcos)){
			$this->G->success('success', $adminAcos->id);
		} else {
			$this->G->error($adminAcos->getErrors());
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
        $adminAcos = $this->AdminAcos->get($id);
        $data = $this->request->getData();
        $data['memo'] = json_encode($data['memo']);
        $adminAcos = $this->AdminAcos->patchEntity($adminAcos, $data);
        if ($adminAcos->getErrors()) {
            $this->G->error($adminAcos->getErrors());
        }

        if ($this->AdminAcos->save($adminAcos)){
            Cache::delete('admin_aco_' . $id, 'long');
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
		$adminAcos = $this->AdminAcos->get($id);
        $this->AdminAcos->removeFromTree($adminAcos);
        $this->AdminAcos->delete($adminAcos);
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
        $adminAcos = $this->AdminAcos->get($id);
        if ($action == 'up'){
            $result = $this->AdminAcos->moveUp($adminAcos);
        } else {
            $result = $this->AdminAcos->moveDown($adminAcos);
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
