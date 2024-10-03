<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class AdminArosTable extends Table
{

	public function validationDefault(Validator $validator): Validator
   {

        $validator
            ->setStopOnFailure()
            ->requirePresence('alias', 'create')
            ->notEmptyString('alias')
            ->lengthBetween('alias', array(2, 10))

            ->requirePresence('id', 'update')
            ->naturalNumber('id');;

        return $validator;
   }


    public function validationList(Validator $validator): Validator
    {
        $validator
            ->requirePresence('type', true)
            ->inList('type', ['all', 'simple']);

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
