<?php
namespace App\Event;

use Cake\Event\EventListenerInterface;
use Cake\ORM\TableRegistry;

class LogInfoEvents implements EventListenerInterface
{
    public function implementedEvents(): array
    {
        return [
            'Order.add' => ['callable' => 'addOrderLog', 'priority' => 99],
            'Order.delete' => ['callable' => 'addOrderLog', 'priority' => 99],
            'Order.update' => ['callable' => 'addOrderLog', 'priority' => 99],
            'Order.addLog' => 'addOrderLog',
            'CustomerPool.updateCustomer' => ['callable' => 'addCustomerLog', 'priority' => 99],
            'CustomerPool.addCustomer' => ['callable' => 'addCustomerLog', 'priority' => 99],
            'CustomerPool.delete' => ['callable' => 'addCustomerLog', 'priority' => 99],
            'CustomerPool.addLog' => 'addCustomerLog'
        ];
    }

    public function addCustomerLog($event, $data)
    {
        $CustomerActLogs = TableRegistry::getTableLocator()->get('CustomerActLogs');
        $logInfo = $data['logInfo'];
        $save = [
            'customer_pool_id' => $logInfo['customerPoolId'],
            'customer_wechat' => $logInfo['customerWechat'],
            'act' => $logInfo['act'],
            'act_user' => $logInfo['username'],
            'act_user_id' => $logInfo['userId'],
            'add_time' => time(),
        ];
        $CustomerActLog = $CustomerActLogs->newEntity($save);
        $result = $CustomerActLogs->save($CustomerActLog);
    }

    public function addOrderLog($event, $data)
    {
        $OrderActLogs = TableRegistry::getTableLocator()->get('OrderActLogs');
        $logInfo = $data['logInfo'];
        $save = [
            'order_id' => $logInfo['orderId'],
            'order_no' => $logInfo['orderNo'],
            'act' => $logInfo['act'],
            'act_user' => $logInfo['username'],
            'act_user_id' => $logInfo['userId'],
            'memo' => $logInfo['memo']?? null,
            'add_time' => time(),
        ];
        $OrderActLog = $OrderActLogs->newEntity($save);
        $result = $OrderActLogs->save($OrderActLog);
    }
}
