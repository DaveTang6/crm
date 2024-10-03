<?php
namespace App\Event;

use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

class CustomerPoolRecycleEvents implements EventListenerInterface
{
    public function implementedEvents(): array
    {
        return [
            'CustomerPool.delete' => 'recycleCustomer'
        ];
    }

    public function recycleCustomer($event, $data) {
        $CustomerPoolRecycles = TableRegistry::getTableLocator()->get('CustomerPoolRecycles');
        $CustomerPoolRecycle = $CustomerPoolRecycles->newEmptyEntity();
        $CustomerPoolRecycle->id = $data['customer']['id'];
        $CustomerPoolRecycle = $CustomerPoolRecycles->patchEntity($CustomerPoolRecycle, $data['customer']);
        $CustomerPoolRecycles->save($CustomerPoolRecycle);
    }
}
