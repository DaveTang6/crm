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
class DepartmentsController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
    }


    public function index()
    {
        $pageSize = $this->request->getData('pageSize');
        $page = $this->request->getData('page');
        if(empty($pageSize) || !is_numeric($pageSize) || $pageSize <1 || $pageSize > 100){
            $pageSize = 30;
        }
        if(empty($page) || !is_numeric($page)){
            $page = 1;
        }

        $where = [];

        if(!empty($this->request->getData('truename'))){
            $where['truename LIKE'] = '%' . $this->request->getData('truename') . '%';
        }

        $Admins = $this->getTableLocator()->get('Admins');
        $result= $Admins->find()
            ->select(['id', 'truename', 'group_id'])
            ->where($where)
            ->order(['Admins.id DESC'])
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result->toArray();

        $AdminAros = $this->getTableLocator()->get('AdminAros');
        $groups = $AdminAros->find()
            ->select(['id', 'alias'])
            ->toArray();

        $groupList = [];
        foreach($groups as $group){
            $groupList[$group['id']] = $group['alias'];
        }

        $Departments = $this->Departments->find()
            ->select(['manager_id', 'num' => 'COUNT(staff_id)'])
            ->group(['manager_id'])
            ->toArray();

        $managers = [];
        foreach($Departments as $v) {
            $managers[$v['manager_id']] = $v['num'];
        }

        foreach($result as $k=>$v) {
            $adminGroups = empty($v['group_id']) ? [] : json_decode($v['group_id'], true);
            array_walk($adminGroups, function(&$v1, $k1, $g){
                if(!empty($g[$v1])) {
                    $v1 = $g[$v1];
                }
            }, $groupList);
            $result[$k]['group_id'] = join(', ', $adminGroups);
            $result[$k]['staff_num'] =  $managers[$v['id']] ?? 0;
        }

        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);

    }

    public function addStaff()
    {
        $data = $this->request->getData();

        foreach($data['staff_ids'] as $v) {
            $staff = $this->Departments->find()
                ->where(['manager_id' => $data['manager_id'], 'staff_id' => $v])
                ->first();

            if(!empty($staff)) {
                continue;
            }

            $Department = $this->Departments->newEntity([
                'manager_id' => $data['manager_id'],
                'staff_id' => $v
            ]);
            if($Department->getErrors()){
                $this->G->error($Department->getErrors());
                return null;
            }
            if (!$this->Departments->save($Department)){
                $this->G->error('add_error', $v);
                return false;
            }
        }
        $this->G->success('Success');

    }


    public function delete()
    {

        $Department = $this->Departments->newEntity(
            $this->request->getData(),
            ['validate' => 'delete']
        );
        if ($Department->getErrors()){
            $this->G->error($Department->getErrors());
            return null;
        }
        $result = $this->Departments->deleteAll(['id IN' =>  $this->request->getData('ids')]);
        if($result == 0){
            $this->G->error('no_data_deleted');
            return null;
        }
        $this->G->success('success');
    }

    public function getStaffs() {
        $data = $this->request->getData();
        $pageSize = $data['pageSize'];
        $page =  $data['page'];

        if(empty($pageSize) || !is_numeric($pageSize) || $pageSize <1 || $pageSize > 100){
            $pageSize = 30;
        }
        if(empty($page) || !is_numeric($page)){
            $page = 1;
        }
        $where = ['manager_id' => $data['manager_id']];
        if(!empty($data['truename'])){
            $where['Admins.truename LIKE'] = '%' . $data['truename'] . '%';
        }

        $result= $this->Departments->find()
            ->innerJoinWith('Admins', function($q) {
                return $q->select(['truename' => 'Admins.truename']);
            })
            ->where($where)
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result->toArray();

        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }

    public function myStaffs() {
        $data = $this->request->getData();
        $pageSize = $data['pageSize'];
        $page =  $data['page'];

        if(empty($pageSize) || !is_numeric($pageSize) || $pageSize <1 || $pageSize > 100){
            $pageSize = 30;
        }
        if(empty($page) || !is_numeric($page)){
            $page = 1;
        }
        $where = ['manager_id' => $this->Jwt->get('userId')];
        if(!empty($data['truename'])){
            $where['Admins.truename LIKE'] = '%' . $data['truename'] . '%';
        }

        $result= $this->Departments->find()
            ->innerJoinWith('Admins', function($q) {
                return $q->select(['truename' => 'Admins.truename']);
            })
            ->where($where)
            ->limit($pageSize)
            ->page($page);

        $total = $result->count();
        $result = $result->toArray();

        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'page' => $page, 'pageSize' => $pageSize]);
    }

}
