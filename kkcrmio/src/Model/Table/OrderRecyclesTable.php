<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class OrderRecyclesTable extends Table
{

	public function validationDefault(Validator $validator): Validator
   {

        $validator
            ->setStopOnFailure()
            ->requirePresence('customer_wechat', 'create')
            ->notEmptyString('customer_wechat')

            ->requirePresence('customer_pool_id', 'create')
            ->naturalNumber('customer_pool_id');

        return $validator;
   }

    public function validationView(Validator $validator): Validator
    {
        $validator
            ->requirePresence('id', true)
            ->naturalNumber('id');

        return $validator;

    }

}
