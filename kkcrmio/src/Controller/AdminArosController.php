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
class AdminArosController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    public function getList()
    {
        $adminAro = $this->AdminAros->newEntity(
            $this->request->getData(),
            ['validate' => 'list']
        );
        if ($adminAro->getErrors()){
            $this->G->error($adminAro->getErrors());
            return null;
        }

        $fields = ['id', 'alias'];
        if ($this->request->getData('type') == 'all') {
            $fields = ['id', 'alias', 'allowed', 'denied'];
        }

        $result= $this->AdminAros->find()
            ->select($fields)
            ->order(['id ASC'])
            ->toArray();

        $this->G->success('Success', ['list'=>$result]);

    }

	public function view(){
        $adminAro = $this->AdminAros->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );
        if ($adminAro->getErrors()){
            $this->G->error($adminAro->getErrors());
            return null;
        }
        $result = $this->AdminAros
            ->get( $this->request->getData('id'));
        if(!$result){
            $this->G->error($result);
            return null;
        }

        $result['allowed'] = empty($result['allowed'])? [] : json_decode($result['allowed'], true);
        $result['denied'] = empty($result['denied'])? [] : json_decode($result['denied'], true);
        $this->G->success('success', $result);

	}

	public function add()
	{
        $data = $this->request->getData();

		$adminAro = $this->AdminAros->newEntity($data);
        if($adminAro->getErrors()){
            $this->G->error($adminAro->getErrors());
            return null;
        }



		if ($this->AdminAros->save($adminAro)) {
            $this->G->success('Success');
		} else {
            $this->G->error("Admin_save_error");
        }
	}

    public function update()
    {
        $data = $this->request->getData();

        $id = $data['id'];
        if(!is_numeric($id)) {
            $this->G->error('id_error');
            return null;
        }

        $AdminAros = $this->getTableLocator()->get('AdminAros');
        $adminAro = $AdminAros->get($id);
        $AdminAros->patchEntity($adminAro, $data);
        if ($adminAro->getErrors()) {
            $this->G->error($adminAro->getErrors());
            return null;
        }

        if ($AdminAros->save($adminAro)){
            Cache::delete('admin_aro_' . $id, 'long');
            $this->G->success('success');
        } else {
            $this->G->error('save_error');
        }
    }

    public function copyAro()
    {
        $data = $this->request->getData();

        $id = $data['id'];
        if(!is_numeric($id)) {
            $this->G->error('id_error');
            return null;
        }

        $adminAro = $this->AdminAros->get($id);
        if ($adminAro->getErrors()) {
            $this->G->error($adminAro->getErrors());
        }

        $data = [
            'alias' => $adminAro['alias'] . '_copy',
            'allowed' => $adminAro['allowed'],
            'denied' => $adminAro['denied']
        ];
        $adminAro = $this->AdminAros->newEntity($data);
        if ($this->AdminAros->save($adminAro)){
            $this->G->success('success');
        } else {
            $this->G->error('save_error');
        }
    }

    public function delete()
    {
        $adminAro = $this->AdminAros->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );
        if ($adminAro->getErrors()){
            $this->G->error($adminAro->getErrors());
            return null;
        }
        $id = $this->request->getData('id');
        $entity = $this->AdminAros->get($id);
        $this->AdminAros->delete($entity);
        Cache::delete('admin_aro_'. $id, 'long');
        $this->G->success('success');
    }

    public function getSelfAros() {
        $adminId = $this->Jwt->get('userId');
        if(empty($adminId)){
            $this->G->success('success', []);
            return null;
        }
        $adminAuth = Cache::read('admin_auth_'. $adminId,'admin_auth');
        $aros = $this->_getAros($adminAuth['groups']);
        $this->G->success('success', $aros);
    }

    private function _getAros($gids) {
        $allowed = [];
        $denied = [];
        $allowedAlias = [];
        $deniedAlias = [];
        $adminAros = null;

        foreach($gids as $gid) {
            $aro = Cache::read('admin_aro_' . $gid, 'long');
            if (!empty($aro)) {
                if(!empty($aro['allowed'])) {
                    $allowed = array_merge($allowed, $aro['allowed']);
                }
                if(!empty($aro['denied'])) {
                    $denied = array_merge($denied, $aro['denied']);
                }
                if(!empty($aro['allowedAlias'])) {
                    $allowedAlias = array_merge($allowedAlias, $aro['allowedAlias']);
                }
                if(!empty($aro['deniedAlias'])) {
                    $deniedAlias = array_merge($deniedAlias, $aro['deniedAlias']);
                }

            } else {
                if(empty($adminAros)) {
                    $adminAros = $this->AdminAros;
                }


                $aro= $adminAros->find()
                    ->where(['id' => $gid])
                    ->select(['allowed', 'denied'])
                    ->first();

                if(!empty($aro)) {
                    $aroAllowed = empty($aro['allowed']) ? [] : json_decode($aro['allowed'], true);
                    $aroDenied = empty($aro['denied']) ? [] : json_decode($aro['denied'], true);
                    $aroAllowedAlias =  $this->_getAlias($aroAllowed);
                    $aroDeniedAlias =  $this->_getAlias($aroDenied);

                    Cache::write('admin_aro_' . $gid, [
                        'allowed' => $aroAllowed,
                        'denied' => $aroDenied,
                        'allowedAlias' => $aroAllowedAlias,
                        'deniedAlias' => $aroDeniedAlias
                        ], 'long');

                    $allowed = array_merge($allowed, $aroAllowed);
                    $denied = array_merge($denied, $aroDenied);
                    $allowedAlias = array_merge($allowedAlias, $aroAllowedAlias);
                    $deniedAlias = array_merge($deniedAlias, $aroDeniedAlias);
                }

            }
        }

        return ['allowed' => array_values(array_unique($allowed)),
            'denied' => array_values(array_unique($denied)),
            'allowedAlias' => array_values(array_unique($allowedAlias)),
            'deniedAlias' => array_values(array_unique($deniedAlias)),
            ];
    }

    private function _getAlias($aro) {
        if(!empty($aro)) {
            $AdminAcos = $this->getTableLocator()->get('AdminAcos');
            $data = $AdminAcos->find()
                ->select(['alias'])
                ->where(['id IN' => $aro, 'alias IS NOT NULL'])
                ->toArray();
            $aroAlias = [];
            foreach($data as $alias) {
                $aroAlias[] = $alias['alias'];
            }
            return $aroAlias;
        }
        return [];
    }

}
