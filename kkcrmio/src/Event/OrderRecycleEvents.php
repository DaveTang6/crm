<?php
namespace App\Event;

use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

class OrderRecycleEvents implements EventListenerInterface
{
    public function implementedEvents(): array
    {
        return [
            'Order.delete' => 'recycleOrder'
        ];
    }

    public function recycleOrder($event, $data) {
        $OrderRecycles = TableRegistry::getTableLocator()->get('OrderRecycles');
        $OrderRecycle = $OrderRecycles->newEmptyEntity();
        $OrderRecycle->id = $data['order']['id'];
        $OrderRecycle = $OrderRecycles->patchEntity($OrderRecycle, $data['order']);
        $OrderRecycles->save($OrderRecycle);
    }
}
