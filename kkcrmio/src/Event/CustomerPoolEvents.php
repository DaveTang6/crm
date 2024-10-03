<?php
namespace App\Event;

use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

class CustomerPoolEvents implements EventListenerInterface
{
    public function implementedEvents(): array
    {
        return [
            'CustomerPool.updateCustomer' => 'updateCustomer',
            'CustomerPool.addCustomer' => 'addCustomer',
            'Order.add' => 'addSaleInfo',
            'Order.update' => 'updateSaleInfo',
            'Order.delete' => 'deleteSaleInfo'
        ];
    }

    public function addCustomer($event, $data) {
        $customer = $data['customer'];
        $CustomerPools = TableRegistry::getTableLocator()->get('CustomerPools');
        $profile = [
            'wechat' => $customer['wechat'],
            'truename' => $customer['truename'],
            'mobile' => $customer['mobile'],
            'gender' => $customer['gender'],
            'area' => $customer['area'],
            'company' => $customer['company'],
            'department' => $customer['department'],
            'brand' => $customer['brand'],
            'is_bl_member' => $customer['is_bl_member'],
            'add_time' => time(),
        ];

        $CustomerPool = $CustomerPools->newEntity($profile);
        $result = $CustomerPools->save($CustomerPool);

        if($result) {
            $event->setResult(['result' => true, 'id' => $CustomerPool->id]);
        } else {
            $event->setResult(['result' => false]);
        }
    }

    public function updateCustomer($event, $data)
    {
        $customer = $data['customer'];
        $customerPoolId = $data['customerPoolId'];
        $CustomerPools = TableRegistry::getTableLocator()->get('CustomerPools');
        $profile = [
            'truename' => $customer['truename'],
            'mobile' => $customer['mobile'],
            'gender' => $customer['gender'],
            'area' => $customer['area'],
            'company' => $customer['company'],
            'department' => $customer['department'],
            'brand' => $customer['brand'],
            'is_bl_member' => $customer['is_bl_member'],
        ];

        $CustomerPool = $CustomerPools->get($customerPoolId);
        $CustomerPool = $CustomerPools->patchEntity($CustomerPool, $profile);

        $result = $CustomerPools->save($CustomerPool);

        if($result) {
            $event->setResult(['result' => true, 'id' => $CustomerPool->id]);
        } else {
            $event->setResult(['result' => false]);
        }

    }

    public function addSaleInfo($event, $data) {
        $CustomerPools = TableRegistry::getTableLocator()->get('CustomerPools');
        $saleInfo = $data['saleInfo'];
        $lastBuy = $this->_getLastBuy($saleInfo['customerWechat'], $saleInfo['salerId']);
        $CustomerPools
            ->query()
            ->update()
            ->set(['locked_by_user' => $saleInfo['salerName'],
                'locked_by_user_id' => $saleInfo['salerId'],
                'last_buy' => $lastBuy,
                'locked_time' => $saleInfo['orderTime'],
                'order_count = order_count + 1'
            ])
            ->where(['id' => $saleInfo['customerPoolId']])
            ->execute();
    }

    public function updateSaleInfo($event, $data) {
        $lastBuy = null;
        $lockedByUser =null;
        $lockedByUserId = 0;
        $lockedTime = null;
        $CustomerPools = TableRegistry::getTableLocator()->get('CustomerPools');
        $saleInfo = $data['saleInfo'];
        $lastOrder = $this->_getLastOrder($saleInfo['customerWechat']);
        if(!empty($lastOrder)) {
            $lastBuy= $lastOrder['last_buy'] ;
            //if order_time is longer than setting time, the saler in the order is the same as the saler of the customer
            if(($lastOrder['order_time'] + $saleInfo['availableTime']) > time()){
                $lockedByUser = $lastOrder['saler_name'];
                $lockedByUserId = $lastOrder['saler_id'];
                $lockedTime = $lastOrder['order_time'] ;
            }
        }

        $CustomerPools
            ->query()
            ->update()
            ->set([
                'locked_by_user' => $lockedByUser,
                'locked_by_user_id' => $lockedByUserId,
                'last_buy' => $lastBuy,
                'locked_time' => $lockedTime,
            ])
            ->where(['id' => $saleInfo['customerPoolId']])
            ->execute();
        if($saleInfo['salerId'] != $lockedByUserId){
            $Customers = TableRegistry::getTableLocator()->get('Customers');
            $Customers
                ->query()
                ->update()
                ->set(['locked_by_user' => $lockedByUser])
                ->where(['wechat' => $saleInfo['customerWechat']])
                ->execute();
        }
    }

    public function deleteSaleInfo($event, $data) {
        $CustomerPools = TableRegistry::getTableLocator()->get('CustomerPools');
        $saleInfo = $data['saleInfo'];
        $lockedByUser = null;
        $lockedByUserId = 0;
        $lockedTime = null;

        $lastBuy = $this->_getLastBuy($saleInfo['customerWechat']);
        //if $lastBuy is NULL, that means no orders with the customer
        if(!empty($lastBuy)){
            $lastOrder = $this->_getLastOrder($saleInfo['customerWechat']);
            if(!empty($lastOrder)) {
                //if add_time is longer than setting time, the saler in the order is the same as the saler of the customer
                if(($lastOrder['order_time'] + $saleInfo['availableTime']) > time()){
                    $lockedByUser = $lastOrder['saler_name'];
                    $lockedByUserId = $lastOrder['saler_id'];
                    $lockedTime = $lastOrder['order_time'] ;
                }
            }
        }

        $CustomerPools
            ->query()
            ->update()
            ->set(['locked_by_user' => $lockedByUser,
                'locked_by_user_id' => $lockedByUserId,
                'last_buy' => $lastBuy,
                'locked_time' => $lockedTime,
                'order_count = order_count - 1'
            ])
            ->where(['id' => $saleInfo['customerPoolId']])
            ->execute();

        $Customers = TableRegistry::getTableLocator()->get('Customers');
        $Customers
            ->query()
            ->update()
            ->set(['locked_by_user' => $lockedByUser])
            ->where(['wechat' => $saleInfo['customerWechat']])
            ->execute();
    }

    private function _getLastBuy($wechat){
        $Orders = TableRegistry::getTableLocator()->get('Orders');
        $Order = $Orders->find()
            ->where(['customer_wechat' => $wechat])
            ->order(['order_time' => 'DESC'])
            ->first();
        return empty($Order)? null : $Order['order_time'];
    }

    private function _getLastOrder($wechat){
        $Orders = TableRegistry::getTableLocator()->get('Orders');
        $Order = $Orders->find()
            ->where(['customer_wechat' => $wechat])
            ->order(['order_time' => 'DESC'])
            ->first();
        return empty($Order)? null : $Order;
    }
}
