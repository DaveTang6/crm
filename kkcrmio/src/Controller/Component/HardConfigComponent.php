<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2017/12/18
 * Time: 15:56
 */

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\Cache\Cache;

class HardConfigComponent extends Component
{

    public function get($k, $v = null)
    {
        $data = Cache::read($k, 'hard_config');
        if(empty($data)) {
            $hardConfig = $this->getController()
                    ->getTableLocator()
                    ->get('HardConfigs');
            $result = $hardConfig->find()
                ->where(['alias' => $k])
                ->select(['value'])
                ->first();
            if(!$result){
                $this->G->error($result);
                return null;
            }
            $data = json_decode($result['value'], true);
            Cache::write( $k, $data, 'hard_config');
        }
        return empty($v) ? $data : $data[$v];
    }

}
