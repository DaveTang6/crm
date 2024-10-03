<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class DepartmentsTable extends Table
{
    public function initialize(array $config): void
    {

        $this->belongsTo('Admins')
            ->setForeignKey('staff_id');
    }

	public function validationDefault(Validator $validator): Validator
   {

        $validator
            ->setStopOnFailure()
            ->requirePresence('manager_id', true)
            ->naturalNumber('manager_id')

            ->requirePresence('id', 'update')
            ->naturalNumber('id')

            ->requirePresence('staff_id', true)
            ->naturalNumber('staff_id');



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
        $validator
            ->requirePresence('status', true)
            ->inList('status', [0, 1]);

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
