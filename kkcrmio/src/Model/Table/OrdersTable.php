<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class OrdersTable extends Table
{

	public function validationDefault(Validator $validator): Validator
   {

        $validator
            ->setStopOnFailure()
            ->requirePresence('customer_wechat', 'create')
            ->notEmptyString('customer_wechat')

            ->requirePresence('customer_pool_id', 'create')
            ->naturalNumber('customer_pool_id')

            ->requirePresence('id', 'update')
            ->naturalNumber('id');



        return $validator;
   }

   public function validationDelete(Validator $validator): Validator
   {
       $validator
           ->requirePresence('id', true)
           ->naturalNumber('id');

       return $validator;

   }


    public function validationView(Validator $validator): Validator
    {
        $validator
            ->requirePresence('id', true)
            ->naturalNumber('id');

        return $validator;

    }
    public function validationFinance(Validator $validator): Validator
    {
        $validator
            ->requirePresence('id', 'update')
            ->naturalNumber('id')
            ->requirePresence('refund_status', 'update')
            ->inList('refund_status', [0, 1])
            ->requirePresence('refund', 'update')
            ->numeric('refund')
            ->requirePresence('pay_status', 'update')
            ->inList('pay_status', [0, 1]);

        return $validator;

    }
    public function validationCost(Validator $validator): Validator
    {
        $validator
            ->requirePresence('id', 'update')
            ->naturalNumber('id')
            ->requirePresence('cost', 'update')
            ->numeric('cost');

        return $validator;

    }
    public function validationCheck(Validator $validator): Validator
    {
        $validator
            ->requirePresence('id', true)
            ->isArray('id');

        return $validator;

    }
}
