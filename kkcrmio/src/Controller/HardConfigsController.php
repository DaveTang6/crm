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
class HardConfigsController extends AppController
{
	public function initialize(): void
    {
        parent::initialize();
    }

    public function update($alias)
    {

        $HardConfig = $this->HardConfigs
            ->find()
            ->where(['alias' =>  $alias])
            ->first();
        if(empty($HardConfig)) {
            $this->G->error('data_not_found');
            return null;
        }

        $HardConfig = $this->HardConfigs->patchEntity($HardConfig, $this->request->getData());
        if($HardConfig->getErrors()){
            $this->G->error($HardConfig->getErrors());
            return null;
        }
        if ($this->HardConfigs->save($HardConfig)){
            Cache::delete( $this->request->getData('alias'), 'hard_config');
            $this->G->success('success');
        } else {
            $this->G->error('update_error');
        }
    }

    public function view($alias)
    {
        $HardConfig = $this->HardConfigs->newEntity(
            $this->request->getData(),
            ['validate' => 'view']
        );
        if ($HardConfig->getErrors()){
            $this->G->error($HardConfig->getErrors());
            return null;
        }

        $result = $this->HardConfigs->find()
            ->where(['alias' => $alias])
            ->select(['value'])
            ->first();
        if(!$result){
            $this->G->error($result);
            return null;
        }

        $this->G->success('success', $result['value']);
    }

}
