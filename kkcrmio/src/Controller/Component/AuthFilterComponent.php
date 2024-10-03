<?php
/**
 * Created by PhpStorm.
 * User: DaveTang
 * Date: 2017/12/18
 * Time: 15:56
 */

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\Cache\Cache;

class AuthFilterComponent extends Component
{
	public $components = ['Jwt', 'HardConfig'];
    /**
     * 过滤权限
     *1、用户可以分配到多个用户组
	  *2、权限通过访问地址判断，地址可以使用正则式进行判断
	  *3、采用allow，deny顺序判断权限，如果权限未处于allow中，默认为deny权限
	  *4、前端可以使用api地址判断用户是否有权限，也可以显示和隐藏功能
	  *5、jwt可以开启单用户登录和不限用户登录判断，通过登录时间来判断
	  *6、jwt可以判断是否需要用户重新登录，通过登录时间是否<要求更新时间来判断
	  * jwt的payload需要包含userId,loginTime;如果在开启单用户登录的情况下，loginTime不一致则下线;
	  * 用户权限信息缓存admin_auth_{userId}:{loginTime,enabled,groups}groups可以是多个权限组aro_id，loginTime为数组，如果JWT的loginTime不在其中，则强迫该用户下线
     * 用户组缓存admin_aro_{aro_id},保存的是该组对应的权限id
	  * 用户统一控制中心admin_control:{guestGroup:0,registerGroup:1,blockTime: 0,loginDeny: 0, userNum:0 },
     * guestGroup游客组id，如果blockTime时间大于用户loginTime,则强迫用户下线;loginDeny,如果为1禁止所有用户登录；userNum,可以同时在线多少人
     * 用到的缓存：systemConfig，admin_auth，long
	  *@return void
     *
     */
    public function checkAuth()
    {
		$controller = $this->getController();
		$request = $controller->getRequest();
		$jwt = $request->getEnv('HTTP_AUTHORIZATION') ?? $request->getQuery('JWT');
		$ctr = $request->getParam('controller');
		$act = $request->getParam('action');
		$pass =  $request->getParam('pass');
        $adminControl = $this->HardConfig->get('admin_control');
        if(is_null($adminControl)) {
            $this->_error('auth_control_error');
        }


		if(empty($jwt)){
			$groupId = [$adminControl['guestGroup']];
		} else {
			$re = $this->Jwt->check($jwt);
			if ($re === false) { //jwt验证不通过，没有权限或权限已失效
				$this->_error('auth_timeout');
			}

			$loginTime =  $this->Jwt->get('loginTime');
			$adminId =  $this->Jwt->get('userId');

			$adminAuth = Cache::read('admin_auth_'. $adminId,'admin_auth');

			if(empty($adminAuth)){//用户权限不存在
				$this->_error('auth_error');
			}

			if($adminAuth['enabled']!== 1){
				$this->_error('auth_disabled');//用户被屏蔽了
			}

			if(!in_array($loginTime, $adminAuth['loginTime'])){
				$this->_error('auth_overload');//用户在其他地方登录了
			}

			if($adminControl['blockTime'] > $loginTime){
				$this->_error('auth_banned');//权限失效
			}
			$groupId = $adminAuth['groups']; //获取所有的权限id
		}

		$aros = $this->_getAros($groupId);

		$allowed = $this->_getAcos($aros['allowed']);
		$denied = $this->_getAcos($aros['denied']);

		//在允许的权限中，则放通
		if(!$this->_findPath($ctr, $act, $pass, $allowed)) {
            $this->_error('auth_denied'); //没权限
        }

        //在禁止的权限中，则阻止
        if($this->_findPath($ctr, $act, $pass, $denied)) {
            $this->_error('auth_denied');
        }

    }

    /**
     * 采用层级方式检测用户权限中是否包含该方法/pass的权限，每层检测采用正则式，如果检测到合格则返回true，检测完毕，没有合格的则返回false
     * $authArr为用户权限合集
     */
    private function _findPath($ctr, $act, $pass, $authArr) {
        $path = $ctr . '/' . $act;
        if(!empty($pass)) {
            foreach($pass as $p){
                $path .= '/' .$p;
            }
        }
        $path= strtolower($path);

        foreach($authArr as $auth) {
            if($auth['p'] == 0) {
                if($path == $auth['r']) {
                    return true;
                }
            } else {
                if (preg_match('/'. $auth['r'] . '/', $path)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function _getAros($gids) {
        $allowed = [];
        $denied = [];
        $adminAros = null;

        foreach($gids as $gid) {
            $aro = Cache::read('admin_aro_' . $gid, 'long');
            if (!empty($aro)) {
                if(!empty($aro['allowed'])) {
                    $allowed = array_merge($allowed, $aro['allowed']);
                }
                if(!empty($aro['denied'])) {
                    $denied = array_merge($denied, $aro['denied']);
                }

            } else {
                if(empty($adminAros)) {
                    $adminAros = $this->getController()
                        ->getTableLocator()
                        ->get('AdminAros');
                }


                $aro= $adminAros->find()
                    ->where(['id' => $gid])
                    ->select(['allowed', 'denied'])
                    ->first();

                if(!empty($aro)) {
                    $aroAllowed = empty($aro['allowed']) ? [] : json_decode($aro['allowed'], true);
                    $aroDenied = empty($aro['denied']) ? [] : json_decode($aro['denied'], true);
                    $allowed = array_merge($allowed, $aroAllowed);
                    $denied = array_merge($denied, $aroDenied);
                    //Cache::write('admin_aro_' . $gid, ['allowed' => $aroAllowed, 'denied' => $aroDenied], 'long');
                }

            }
        }

        return ['allowed' => array_values(array_unique($allowed)), 'denied' => array_values(array_unique($denied))];
    }

    private function _getAcos($acoIds) {
        $result= [];
        $adminAcos = null;
        foreach($acoIds as $acoId) {
            $aco = Cache::read('admin_aco_' . $acoId, 'long');
            if (!empty($aco)) {
                $result = array_merge($result, $aco);
            } else {
                if(empty($adminAcos)) {
                    $adminAcos = $this->getController()
                        ->getTableLocator()
                        ->get('AdminAcos');
                }


                $aco= $adminAcos->find()
                    ->where(['id' => $acoId, 'status' => 1])
                    ->select(['memo'])
                    ->first();

                if(!empty($aco)) {
                    $acoData = empty($aco['memo']) ? [] : json_decode($aco['memo'], true);
                    $result = array_merge($result, $acoData);
                    Cache::write('admin_aco_' . $acoId, $acoData, 'long');
                }

            }
        }

        return $result;
    }

	/**
	* 报错信息
	*
	*/
	private function _error($msg) {
		$re = [
			'status' => 400,
			'msg' => $msg,
			'memo' => null
		];
		echo json_encode($re);
		exit;
	}


}
