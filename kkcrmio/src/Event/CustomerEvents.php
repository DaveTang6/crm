<?php
namespace App\Event;

use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

class CustomerEvents implements EventListenerInterface
{
    public function implementedEvents(): array
    {
        return [
            'Order.add' => 'addSaleInfo',
            'Order.update' => 'updateSaleInfo',
            'Order.delete' => 'deleteSaleInfo'
        ];
    }

    public function addSaleInfo($event, $data) {
        $Customers = TableRegistry::getTableLocator()->get('Customers');
        $saleInfo = $data['saleInfo'];
        $lastBuy = $this->_getLastBuy($saleInfo['customerWechat'], $saleInfo['salerId']);
        $Customers
            ->query()
            ->update()
            ->set([
                'last_buy' => $lastBuy,
                'order_count = order_count + 1'
            ])
            ->where(['id' => $saleInfo['customerId']])
            ->execute();

        $Customers
            ->query()
            ->update()
            ->set(['locked_by_user' => $saleInfo['salerName']])
            ->where(['wechat' => $saleInfo['customerWechat']])
            ->execute();
    }

    public function updateSaleInfo($event, $data) {
        $Customers = TableRegistry::getTableLocator()->get('Customers');
        $saleInfo = $data['saleInfo'];
        $lastBuy = $this->_getLastBuy($saleInfo['customerWechat'], $saleInfo['salerId']);
        $Customers
            ->query()
            ->update()
            ->set([
                'last_buy' => $lastBuy,
            ])
            ->where(['wechat' => $saleInfo['customerWechat'], 'saler_id' => $saleInfo['salerId']])
            ->execute();
    }

    public function deleteSaleInfo($event, $data) {
        $Customers = TableRegistry::getTableLocator()->get('Customers');
        $saleInfo = $data['saleInfo'];

        $saleInfo['last_buy'] = $this->_getLastBuy($saleInfo['customerWechat'], $saleInfo['salerId']);

        $Customers
            ->query()
            ->update()
            ->set([
                'last_buy' => $saleInfo['last_buy'],
                'order_count = order_count - 1'
            ])
            ->where(['saler_id' => $saleInfo['salerId'], 'wechat' => $saleInfo['customerWechat']])
            ->execute();

    }

    private function _getLastBuy($wechat, $salerId){
        $Orders = TableRegistry::getTableLocator()->get('Orders');
        $Order = $Orders->find()
            ->where(['customer_wechat' => $wechat, 'saler_id' => $salerId])
            ->order(['order_time' => 'DESC'])
            ->first();
        return empty($Order)? null : $Order['order_time'];
    }
}
