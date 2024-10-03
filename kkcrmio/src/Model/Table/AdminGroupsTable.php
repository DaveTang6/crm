<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

//use App\Model\Rule\MobileRule;

class AdminGroupsTable extends Table
{

    public function initialize(array $config): void
    {
        $this->belongsTo('Admins')
            ->setForeignKey('admin_id');
    }



}
