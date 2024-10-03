<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class CustomerSaleLogsTable extends Table
{

	public function validationDefault(Validator $validator): Validator
   {

        $validator
            ->setStopOnFailure()
            ->requirePresence('content', true)
            ->notEmptyString('content')
            ->requirePresence('customer_id', 'create')
            ->naturalNumber('customer_id')
            ->requirePresence('saler_id', 'create')
            ->naturalNumber('saler_id')
            ->requirePresence('id', 'update')
            ->naturalNumber('id');

        return $validator;
   }

   public function validationDelete(Validator $validator): Validator
   {
       $validator->setProvider('extra', 'App\Model\Validation\ExtraValidation');
       $validator
           ->requirePresence('ids', true)
           ->add('ids', 'validArrayNum', [
               'rule' => 'isArrayNum',
               'provider' => 'extra'
           ]);


       return $validator;

   }

    public function validationUpdate(Validator $validator): Validator
    {
        $validator = $this->validationDelete($validator);

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
