<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class OrderProductsTable extends Table
{

	public function validationDefault(Validator $validator): Validator
   {

        $validator
            ->setStopOnFailure()
            ->requirePresence('product_name', 'create')
            ->notEmptyString('product_name')

            ->requirePresence('category_name', 'create')
            ->notEmptyString('category_name')

            ->requirePresence('category_id', 'create')
            ->naturalNumber('category_id')

            ->requirePresence('order_id', 'create')
            ->naturalNumber('order_id')

            ->requirePresence('product_id', 'create')
            ->naturalNumber('product_id')

            ->requirePresence('total', 'create')
            ->numeric('total')

            ->requirePresence('cost', 'create')
            ->numeric('cost')

            ->requirePresence('num', 'create')
            ->numeric('num')

            ->requirePresence('id', 'update')
            ->naturalNumber('id');



        return $validator;
   }
}
