<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class AdminsTable extends Table
{
    public function initialize(array $config): void
    {
        $this->hasMany('AdminGroups')
            ->setForeignKey('admin_id')
            ->setDependent(true);

        $this->hasMany('Departments')
            ->setForeignKey('staff_id');

        $this->hasMany('Customers')
            ->setForeignKey('saler_id');
    }

	public function validationDefault(Validator $validator): Validator
   {

        $validator
            ->setStopOnFailure()

            ->requirePresence('id', 'update')
            ->naturalNumber('id')

            ->requirePresence('username', 'create')
            ->notEmptyString('username')
            ->lengthBetween('username', array(2, 10))

            ->requirePresence('password', 'create')
            ->lengthBetween('password', array(32, 32))

            ->requirePresence('truename', true)
            ->notEmptyString('truename', 'We need your name')

            ->requirePresence('group_id', true)
            ->notEmptyArray('group_id')

            ->requirePresence('email', false)
            ->allowEmptyString('email')
            ->email('email')
            ->lengthBetween('email', array(5, 30))

            ->requirePresence('mobile', false)
            ->allowEmptyString('mobile')
            ->naturalNumber('mobile')
            ->lengthBetween('mobile', array(11, 11));



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

    public function validationEnabled(Validator $validator): Validator
    {
        $validator = $this->validationDelete($validator);
        $validator
            ->requirePresence('enabled', true)
            ->inList('enabled', [0, 1]);

        return $validator;

    }


    public function validationView(Validator $validator): Validator
    {
        $validator
            ->requirePresence('id', true)
            ->naturalNumber('id');

        return $validator;
    }

    public function validationLogin(Validator $validator): Validator
    {
        $validator
            ->requirePresence('username', true)
            ->notEmptyString('username')
            ->lengthBetween('username', array(2, 10))

            ->requirePresence('password', true)
            ->lengthBetween('password', array(32, 32));

        return $validator;
    }

    public function validationSelf(Validator $validator): Validator
    {

        $validator
            ->setStopOnFailure()

            ->requirePresence('password', false)
            ->lengthBetween('password', array(32, 32))

            ->requirePresence('truename', false)
            ->notEmptyString('truename', 'We need your name')

            ->requirePresence('email', false)
            ->allowEmptyString('email')
            ->email('email')
            ->lengthBetween('email', array(5, 30))

            ->requirePresence('mobile', false)
            ->allowEmptyString('mobile')
            ->naturalNumber('mobile')
            ->lengthBetween('mobile', array(11, 11));
        return $validator;
    }

}
