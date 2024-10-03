<?php
/**
 * Created by PhpStorm.
 * User: DaveTang
 * Date: 2017/12/18
 * Time: 15:56
 */

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\I18n\Time;
use Cake\Utility\Security;
use Cake\Cache\Cache;

class JwtComponent extends Component
{
    /**
     * 创建一个JWT
     *
     * @param string         $exp     JWT过期时间，以当前时间开始计算
     * @param Array          $data    向JWT中添加的额外数据
     * @param string         $domain    向JWT中添加的额外数据
     *
     * @return JWT
     *
     */
    public function create(array $data = array(), $exp = '7 days', $domain = '')
    {
        $header = array(
            'typ'=>'JWT',
            'alg'=>'HS256'
        );

        $now = Time::now();
        $exp = $now->modify('+'.$exp)->toUnixString();

        $payload = array(
            'iss' => $domain,
            'exp' => $exp
        );

        $payload = array_merge($payload, $data);

        $segments = array();
        $segments[] = $this->_urlsafeB64Encode(json_encode($header));
        $segments[] = $this->_urlsafeB64Encode(json_encode($payload));
        $signing_input = implode('.', $segments);
        $signature = hash_hmac('sha256', $signing_input, Security::getSalt(), true);

        $segments[] = $this->_urlsafeB64Encode($signature);
        return implode('.', $segments);
    }


    /**
     * 获取JWT中的一个值
     *
     * @param string         $key     值对应的$key
     * @param string         $jwt     jwt数据
     *
     * @return key对应的值
     *
     */
    public function get($key, $jwt = null){
        if (empty($jwt)) {
            $controller = $this->getController();
            $jwt = $controller->getRequest()->getEnv('HTTP_AUTHORIZATION') ?? $controller->getRequest()->getQuery('JWT');
        }
        $jwtArr = $this->_format($jwt);
        if(empty($jwtArr)) {
            return null;
        }
        $_payload = $jwtArr['payload'];
        if(empty($_payload)){
            return null;
        }
        $payload = json_decode($this->_urlsafeB64Decode($_payload), true);
        return $payload[$key] ?? null;
    }

    /**
     * 检查JWT是否正确
     *
     * @param string         $jwt     jwt数据
     * @return true | false
     *
     */
    public function check($jwt) {
        $jwtArr = $this->_format($jwt);
        if (empty($jwtArr)) {
            return false;
        }

        if (empty($jwtArr['signature']))
        {
            return false;
        }

        $hash = hash_hmac('sha256', $jwtArr['header'] . '.' . $jwtArr['payload'], Security::getSalt(), true);
        if (!hash_equals($this->_urlsafeB64Decode($jwtArr['signature']), $hash))
        {
            return false;
        }

        $exp = $this->get('exp', $jwt);

        if(time() > $exp)
        {
            return false;
        }

        return true;

    }

    private function _format($jwt){
        $authHeader = substr($jwt,0, 6);
        if ($authHeader == 'Bearer'){
            $jwt = substr($jwt, 7);
        }

        $jwt = explode('.', $jwt);
        if (count($jwt) === 3) {
            $jwtArr = [
              'header' => $jwt[0],
              'payload' => $jwt[1],
              'signature' => $jwt[2]
            ];
            return $jwtArr;
        } else {
            return null;
        }
    }

    private function _urlsafeB64Encode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    private function _urlsafeB64Decode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

}
