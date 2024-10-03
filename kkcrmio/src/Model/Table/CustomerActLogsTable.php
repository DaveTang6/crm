<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class CustomerActLogsTable extends Table
{

	public function validationDefault(Validator $validator): Validator
   {

        $validator
            ->setStopOnFailure()
            ->requirePresence('customer_pool_id', 'create')
            ->requirePresence('customer_wechat', 'create')
            ->requirePresence('id', 'update')
            ->naturalNumber('id');

        return $validator;
   }

}
