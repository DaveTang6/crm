<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class ProductCategoriesTable extends Table
{
	public function initialize(array $config): void
   {
        $this->addBehavior('Tree');
   }

	public function validationDefault(Validator $validator): Validator
   {

        $validator
            ->setStopOnFailure()
            ->requirePresence('title', true)
            ->notEmptyString('title')

            ->requirePresence('id', 'update')
            ->naturalNumber('id')

            ->requirePresence('status', false)
            ->inList('status', [0, 1])

            ->requirePresence('parent_id', false)
            ->allowEmptyString('parent_id')
            ->naturalNumber('parent_id');

        return $validator;
   }


}
