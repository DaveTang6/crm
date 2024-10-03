<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class DemosTable extends Table
{
	public function initialize(array $config): void
   {
        $this->addBehavior('Tree');
   }

	public function validationDefault(Validator $validator): Validator
   {

//		 $validator->setProvider('custom', 'App\Model\Validation\ExtraValidation');
//         $validator
//            ->requirePresence('title') //是否必要
//				->add('title', 'host-name', [
//				'rule' => 'exHostname',
//				'message' => 'Hostname_error' ,
//				'provider' => 'custom'
//				])
//            ->boolean('title');//不能为空字符

        $validator
            ->setStopOnFailure()
            ->requirePresence('title', true)
            ->notEmptyString('title')

            ->requirePresence('id', 'update')
            ->naturalNumber('id')

            ->requirePresence('status', false)
            ->inList('status', [0, 1])

            ->requirePresence('parent_id', false)
            ->naturalNumber('parent_id');

        return $validator;
   }


	public function buildRules(RulesChecker $rules): RulesChecker
	{
//		$rules->add(new MobileRule(), 'isMobile', [
//			'errorField' => 'title',
//			'message' => 'mobile_error'
//		]);
        $rules->add(function($entity) {
            $data = $entity->extract($this->getSchema()->columns(), true);
            $validator = $this->getValidator('default');
            $errors = $validator->validate($data, $entity->isNew());
            $entity->setErrors($errors);

            return empty($errors);
        });
		return $rules;
	}

}
