<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2017/12/18
 * Time: 15:56
 */

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\Core\Configure;

class GComponent extends Component
{

    public function error($msg = null, $memo = null)
    {
        $this->msg(400, $msg, $memo);
    }

    public function success($msg = null, $memo = null)
    {
        $this->msg(200, $msg, $memo);
    }

    private function msg($status = 400, $msg = null, $memo = null)
    {
        $controller = $this->getController();
        $controller->set('data', array(
            'status' => $status,
            'msg' => $msg,
            'memo' => $memo
        ));
    }

}
