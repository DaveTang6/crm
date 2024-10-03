<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class OrderActLogsTable extends Table
{

	public function validationDefault(Validator $validator): Validator
   {

        $validator
            ->setStopOnFailure()
            ->requirePresence('order_id', 'create')
            ->naturalNumber('order_id')
            ->requirePresence('order_no', 'create')
            ->requirePresence('id', 'update')
            ->naturalNumber('id');

        return $validator;
   }

}
