<?php
namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;

class MobileRule
{
    public function __invoke(EntityInterface $entity, array $options)
    {
        // Do work
		pr('do work');
        return false;
    }
}
