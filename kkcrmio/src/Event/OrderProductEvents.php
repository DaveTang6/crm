<?php
namespace App\Event;

use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

class OrderProductEvents implements EventListenerInterface
{
    public function implementedEvents(): array
    {
        return [
            'Order.add' => 'addProduct',
            'Order.update' => 'updateProduct',
            'Order.productUpdate' => 'updateProduct',
            'Order.delete' => 'deleteProduct'
        ];
    }

    public function addProduct($event, $data) {
        $this->_add($data);
    }

    public function updateProduct($event, $data) {
        $this->_delete($data['orderId']);
        $this->_add($data);
    }

    public function deleteProduct($event, $data) {
        $this->_delete($data['orderId']);
    }

    private function _delete($orderId){
        $OrderProducts = TableRegistry::getTableLocator()->get('OrderProducts');
        $OrderProducts
            ->deleteAll(['order_id'=>$orderId]);
    }

    private function _add($data){
        $OrderProducts = TableRegistry::getTableLocator()->get('OrderProducts');
        $products = $data['products'];
        foreach($products as $v){
            $v['order_id'] = $data['orderId'];
            $orderProduct = $OrderProducts->newEntity($v);
            if($orderProduct->getErrors()){
                pr($orderProduct->getErrors());
                continue;
            }
            $OrderProducts->save($orderProduct);
        }
    }

}
