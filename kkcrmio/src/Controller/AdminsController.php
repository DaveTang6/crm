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
use Cake\Cache\Cache;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class AdminsController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('HardConfig');
        $this->loadComponent('JWT');
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
        if(!empty($this->request->getData('username'))){
            $where['username'] = $this->request->getData('username');
        }

        if(!empty($this->request->getData('truename'))){
            $where['truename LIKE'] = '%' . $this->request->getData('truename') . '%';
        }

        if(is_numeric($this->request->getData('enabled'))){
            $where['enabled'] = $this->request->getData('enabled');
        }

        $result= $this->Admins->find()
            ->select(['Admins.id','username', 'truename', 'group_id', 'last_login', 'enabled', 'locked'])
            ->where($where)
            ->order(['Admins.id DESC'])
            ->limit($pageSize)
            ->page($page);

        if(!empty($this->request->getData('group_id'))){
            $result->matching('AdminGroups', function ($q) {
                return $q->where(['AdminGroups.admin_aro_id' => $this->request->getData('group_id')]);
            });
        }

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

        foreach($result as $k=>$v) {
            $adminGroups = empty($v['group_id']) ? [] : json_decode($v['group_id'], true);
            array_walk($adminGroups, function(&$v1, $k1, $g){
                if(!empty($g[$v1])) {
                    $v1 = $g[$v1];
                }
            }, $groupList);
            $result[$k]['group_id'] = join(', ', $adminGroups);
            $result[$k]['last_login'] = empty($v['last_login']) ? '未登录' : date('Y-m-d H:i:s', $v['last_login']);
            $result[$k]['locked_time'] = empty($v['locked']) ? '' :  date('Y-m-d H:i:s', $v['locked']);
        }

        $this->G->success('Success', ['list'=>$result, 'total'=> $total, 'groups'=> $groupList, 'page' => $page, 'pageSize' => $pageSize]);

    }

	public function view(){
        $admin = $this->Admins->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );
        if ($admin->getErrors()){
            $this->G->error($admin->getErrors());
            return null;
        }
        $result = $this->Admins
            ->get( $this->request->getData('id'));
        if(!$result){
            $this->G->error($result);
            return null;
        }

        $AdminAros = $this->getTableLocator()->get('AdminAros');
        $groups = $AdminAros->find()
            ->select(['id', 'alias'])
            ->toArray();

        $groupList = [];
        foreach($groups as $group){
            $groupList[] = [ 'id' => $group['id'], 'alias' => $group['alias']];
        }

        $result['group_id'] = json_decode($result['group_id'], true);
        $result['groups'] = $groupList;
        unset($result['password']);
        unset($result['salt']);

        $this->G->success('success', $result);

	}

	public function add()
	{
        $data = $this->request->getData();

        $groups = $data['group_id'];
        $data['admin_groups'] = [];
        foreach($groups as $group) {
            $data['admin_groups'][] =['admin_aro_id' => $group];
        }
		$admin = $this->Admins->newEntity($data, [
		    'associated' => ['AdminGroups']
        ]);
        if($admin->getErrors()){
            $this->G->error($admin->getErrors());
            return null;
        }
        $salt = rand(100000, 999999);
        $admin->password = md5($data['password'].$salt);
        $admin->salt = $salt;
        $admin->reg_time = time();
        $admin->group_id = json_encode($data['group_id']);

        $username = $this->Admins->findByUsername($this->request->getData('username'))->select(['id'])->first();
        if(!empty($username)){
            $this->G->error("username_exist");
            return null;
        }


		if ($this->Admins->save($admin)) {
            $this->G->success('Success');
		} else {
            $this->G->error("Admin_save_error");
        }
	}

    public function update()
    {
        if(!is_numeric( $this->request->getData('id'))) {
            $this->G->error('id_error');
            return null;
        }

        $data = $this->request->getData();

        $groups = $data['group_id'];
        $data['admin_groups'] = [];
        foreach($groups as $group) {
            $data['admin_groups'][] =['admin_aro_id' => $group];
        }

        $data['group_id'] = json_encode($data['group_id']);

        if(empty($data['password'])) {
            unset($data['password']);
        } else {
            $salt = rand(100000, 999999);
            $data['password'] =  md5($data['password'].$salt);
            $data['salt'] = $salt;
        }

        if(!empty($data['username'])) {
            $this->G->error('username_error');
            return null;
        }

        $admin = $this->Admins->get($data['id']);
        $admin = $this->Admins->patchEntity($admin, $data, [
            'associated' => ['AdminGroups']
        ]);
        if($admin->getErrors()){
            $this->G->error($admin->getErrors());
            return null;
        }

        $AdminGroup = $this->getTableLocator()->get('AdminGroups');
        $AdminGroup->deleteAll(['admin_id' => $data['id']]);

        if ($this->Admins->save($admin)){
            if($data['enabled'] == 0) {
                $this->_deleteUserCache([$data['id']]);
            }
            $this->G->success('success');
        } else {
            $this->G->error('update_error');
        }
    }

    public function selfUpdate()
    {
        $adminId =  $this->Jwt->get('userId');
        $data = $this->request->getData();

        if(empty($data['password'])) {
            unset($data['password']);
        } else {
            $salt = rand(100000, 999999);
            $data['password'] =  md5($data['password'].$salt);
            $data['salt'] = $salt;
        }

        if(!empty($data['username'])) {
            $this->G->error('username_error');
            return null;
        }

        if(!empty($data['group_id'])) {
            $this->G->error('group_error');
            return null;
        }

        if(!empty($data['enabled'])) {
            $this->G->error('enabled_error');
            return null;
        }

        $admin = $this->Admins->get($adminId);
        $admin = $this->Admins->patchEntity($admin, $data, ['validate' => 'self']);
        if($admin->getErrors()){
            $this->G->error($admin->getErrors());
            return null;
        }

        if ($this->Admins->save($admin)){
            $this->G->success('success');
        } else {
            $this->G->error('update_error');
        }
    }

    public function selfView(){
        $adminId =  $this->Jwt->get('userId');
        $result = $this->Admins
            ->get( $adminId);
        if(!$result){
            $this->G->error($result);
            return null;
        }
        unset($result['enabled']);
        unset($result['group_id']);
        unset($result['password']);
        unset($result['salt']);

        $this->G->success('success', $result);

    }

    public function delete()
    {
        $admin = $this->Admins->newEntity(
            $this->request->getData(),
            ['validate' => 'delete']
        );
        if ($admin->getErrors()){
            $this->G->error($admin->getErrors());
            return null;
        }
        $ids = $this->request->getData('ids');
        foreach($ids as $id) {
            $entity = $this->Admins->get($id);
            $result = $this->Admins->delete($entity);
            if(!empty($result)) {
                $this->_deleteUserCache([$id]);
            }
        }
        $this->G->success('success');
    }

    /*
     *验证用户名是否存在
     * 验证用户是否可用
     * 验证是否已经
     *
     */
	public function login()
    {
        $data = $this->request->getData();
        $admin = $this->Admins->newEntity(
            $data,
            ['validate' => 'login']
        );
        if ($admin->getErrors()){
            $this->G->error($admin->getErrors());
            return null;
        }

        //读取系统登录配置
        $adminControl = $this->HardConfig->get('admin_control');
        if(is_null($adminControl)) {
            $this->G->error('系统错误');
            return null;
        }

        $loginDeny = $adminControl['loginDeny'];
        $loginErrorNum = $adminControl['loginErrorNum'];
        $loginLockTime = $adminControl['loginLockTime'];
        $userNum = $adminControl['userNum'];
        $usingTime = $adminControl['usingTime'];

        //系统关闭了登录
        if(is_null($loginDeny)) {
            $this->G->error('系统错误');
            return null;
        } elseif($loginDeny == 1) {
            $this->G->error('系统正在维护中，无法登录，具体原因请联系管理员');
            return null;
        }

        //查询用户是否存在
        $user= $this->Admins->findByUsername($data['username'])
            ->select(['id', 'username', 'password', 'email', 'mobile', 'truename', 'salt', 'group_id', 'locked', 'enabled',  'visit_count'])
            ->first();
        if(!$user) {
            $this->G->error('用户名或密码错误');
            return null;
        }
        if($user['enabled'] <> 1) {
            $this->G->error('用户已不可用，请联系管理员开通');
            return null;
        }

        //处理用户登录错误问题
        $locked = Cache::read('admin_locked_' . $user['id'], 'long'); //记录错误次数和锁定时间,[n：次数，t：时间]
        if(!empty($locked)) {

            if(!$loginErrorNum || !$loginLockTime) {
                $this->G->error('系统错误');
                return null;
            }
            $loginLockTime = intval($loginLockTime) * 60;
            $expireTime = time() - $locked['t'];
            if(($loginLockTime > $expireTime) && ($locked['n'] > $loginErrorNum)) {
                $this->G->error('该账号已被锁定，请于'.date('m-d H:i', $locked).'后登录');
                return null;
            }
        }

        //验证密码是否错误
        $pwd = md5($data['password'].$user['salt']);
        if($pwd <> $user['password']) {
            $n = 1;
            if(!empty($locked)) {
                if ($loginLockTime > $expireTime) {//如果还没有过限制登录时间
                    $n = $locked['n'] + 1;
                } else { //如果过了限制登录时间
                    if($user['locked'] == 1) {
                        $result = $this->Admins
                            ->query()
                            ->update()
                            ->set(['locked' => 0])
                            ->where(['id' => $user['id']])
                            ->execute();

                        if (!$result){
                            $this->G->error($result);
                            return null;
                        }
                    }
                }
            }

            if(($loginErrorNum - $n) > 0) {
                $this->G->error('用户名或密码错误,您还可尝试' . ($loginErrorNum - $n) . '次');
                Cache::write('admin_locked_' . $user['id'], ['n' => $n, 't' => time()], 'long');
            } else {
                if($user['locked'] == 0) {
                    $result = $this->Admins
                        ->query()
                        ->update()
                        ->set(['locked' => 1])
                        ->where(['id' => $user['id']])
                        ->execute();

                    if (!$result){
                        $this->G->error($result);
                        return null;
                    }
                    Cache::write('admin_locked_' . $user['id'], ['n' => $n, 't' => time()], 'long');
                }

                $this->G->error('用户名或密码错误,该账户已被锁定' . $loginLockTime . '分钟');
            }

            return null;
        } else {
            //登录成功删除锁定信息
            if(!empty($locked)) {
                Cache::delete('admin_locked_' . $user['id'], 'long');
            }
        }

        //生成用户缓存
        $userCache = Cache::read('admin_auth_' . $user['id'], 'admin_auth');
        if(!empty($userCache)) {

            if(!$userNum) {
                $this->G->error('系统错误');
                return null;
            }
            if(count($userCache['loginTime']) >= $userNum) {
                array_shift($userCache['loginTime']);
            }
        } else {
            $userCache = [
              'loginTime' => [],
              'enabled' => 1,
              'groups' => []
            ];
        }
        $loginTime = time();
        $userCache['loginTime'][] = $loginTime;
        $userCache['groups'] = json_decode($user['group_id'], true);
        Cache::write('admin_auth_' . $user['id'], $userCache, 'admin_auth');

        //生成jwt
        if(empty($usingTime)) {
            $this->G->error('系统错误');
            return null;
        }
        $jwt = $this->JWT->create(['userId' => $user['id'], 'loginTime' => $loginTime], $usingTime . 'hours');
        $result = [
            'jwt' => $jwt,
            'user' => $user['truename'] ?? $user['username']
        ];

        //保存登录信息
        $this->Admins
            ->query()
            ->update()
            ->set(['last_login' => $loginTime, 'visit_count' =>($user['visit_count'] + 1), 'last_ip' => $this->request->clientIp()])
            ->where(['id' => $user['id']])
            ->execute();

        $this->G->success('登录成功', $result);
    }

    public function setEnabled() {
        $admin = $this->Admins->newEntity(
            $this->request->getData(),
            ['validate' => 'enabled']
        );
        if ( $admin->getErrors() ){
            $this->G->error($admin->getErrors());
            return null;
        }

        $result = $this->Admins
            ->query()
            ->update()
            ->set(['enabled' => $this->request->getData('enabled')])
            ->where(['id IN' => $this->request->getData('ids')])
            ->execute();

        if (!$result){
            $this->G->error($result);
            return null;
        }

        if ($this->request->getData('enabled') == 0) {
            $this->_deleteUserCache($this->request->getData('ids'));
        }

        $this->G->success('success');
    }

    public function setUnLocked() {
        $admin = $this->Admins->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );

        if ($admin->getErrors()){
            $this->G->error($admin->getErrors());
            return null;
        }

        $result = $this->Admins
            ->query()
            ->update()
            ->set(['locked' => 0])
            ->where(['id' => $this->request->getData('id')])
            ->execute();

        if (!$result){
            $this->G->error($result);
            return null;
        }
        Cache::delete('admin_locked_' .  $this->request->getData('id'), 'long');
        $this->G->success('success');
    }

    public function getAdmins()
    {

        $where = ['enabled' => 1];

        if(!empty($this->request->getData('truename'))){
            $where['truename LIKE'] = '%' . $this->request->getData('truename') . '%';
        }

        $result= $this->Admins->find('list',['keyField' => 'id', 'valueField' => 'truename'])
            ->where($where)
            ->limit(10)
            ->toArray();

        $admins = [];
        foreach ($result as $k=>$v) {
            $admins[] = ['key' => $k, 'value' => $v];
        }

        $this->G->success('Success', ['list'=>$admins]);

    }

    private function _deleteUserCache($ids) {
        foreach($ids as $id) {
            if (!empty(Cache::read('admin_auth_' . $id, 'admin_auth'))) {
                Cache::delete('admin_auth_' . $id, 'admin_auth');
            }
        }
    }

}
