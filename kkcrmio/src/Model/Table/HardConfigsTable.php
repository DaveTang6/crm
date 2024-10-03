<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class HardConfigsTable extends Table
{

	public function validationDefault(Validator $validator): Validator
   {

        $validator
            ->setStopOnFailure()
            ->requirePresence('alias', true)
            ->notEmptyString('alias', 'need Alias')

            ->requirePresence('value', true);

        return $validator;
   }

    public function validationView(Validator $validator): Validator
    {
        $validator
            ->requirePresence('alias', true)
            ->notEmptyString('alias')
            ->lengthBetween('alias', [3,20]);

        return $validator;
    }


}
