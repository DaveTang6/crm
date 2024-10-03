import axios from 'axios'
import { ElMessage, ElLoading } from 'element-plus'
import router from '@/router'
import store from '@/store'
import siteConfig from '@/config/config'
import g from '@/utils/g'

// create an axios instance
const service = axios.create({
  baseURL: siteConfig('apiURL'),
  // responseType: 'json',
  transformResponse: [
    function(data) {
      return typeof data === 'string' ? JSON.parse(data) : data
    }
  ],
  timeout: 5000 // request timeout
})

// 取消重复请求
const pending = []
const CancelToken = axios.CancelToken
const removePending = config => {
  for (const p in pending) {
    const item = p
    const list = pending[p]
    if (list.url === config.url + '&request_type=' + config.method) {
      // 执行取消操作
      list.cancel()
      // 从数组中移除记录
      pending.splice(item, 1)
    }
  }
}

let loadingInstance = null
const showLoading = (config) => {
	if(!!config.showLoading){
		loadingInstance = ElLoading.service({fullscreen: true})
	}
}

const hideLoading = () => {
	if(!!loadingInstance){
		loadingInstance.close()
	}	
}

// 请求拦截器
service.interceptors.request.use(
  config => {
    showLoading(config)
    config.timeout = config.setTimeout || config.timeout

    if (store.getters.jwt) {
      config.headers['Authorization'] = `Bearer ${store.getters.jwt}`
    }
	
	//console.log(config.url, siteConfig.apiURL + 'LL')
    // 是否允许重复请求
    if (!config.noPending) {
      removePending(config)

      // 是否自定义cacelToken
      if (!config.cacelSource) {
        config.cancelToken = new CancelToken(c => {
          pending.push({
            url: `${config.url}&request_type=${config.method}`,
            cancel: c
          })
        })
      } else {
        pending.push({
          url: `${config.url}&request_type=${config.method}`,
          cancel: config.cacelSource.cancel
        })
      }
    }
    return config
  },
  error => {
    hideLoading()
    // do something with request error
    console.debug(error) // for debug
    return Promise.reject(error)
  }
)

// 响应拦截器
service.interceptors.response.use(
  response => {
    hideLoading()
    const res = response.data
    let msg = res.msg
    if (res.status !== 200) {
      // 登录失效退出
      const authErrors = ['auth_timeout', 'auth_control_error', 'auth_error', 'auth_disabled', 'auth_overload', 'auth_banned', 'auth_denied']
      if (res.status === 400 && authErrors.includes(res.msg)) {
        let errorMsg = {
          'auth_timeout': '未登录或登录已过期',
          'auth_error': '用户权限错误',
          'auth_disabled': '您已被禁止登录，请联系管理员',
          'auth_overload': '该账号已超过同时登录人数限制，您被迫下线',
          'auth_banned': '账户有效期已过期，请重新登录',
          'auth_control_error': '系统错误，请联系管理员'
        }
        if(res.msg === 'auth_denied') {
          g.gotoWarning('您没有该页面的访问权限')
        } else {
          store.dispatch('loginOut')
          ElMessage({
            showClose: true,
            message: errorMsg[res.msg],
            type: 'error',
            duration: 3000
          })
          router.push('/admin/login/2')
        }


      } else {

		if(msg.constructor !== String){
			let tmpString = ''
			for(let i in msg) {
				tmpString += i + '_error '
			}
			msg = tmpString
		}  
        ElMessage({
          message: msg || '请求错误，请稍后重试!',
          type: 'error',
          duration: 5 * 1000
        })
      }
      return Promise.reject({msg: msg, memo: res.memo})
    } else {
      return res
    }
  },
  error => {
    hideLoading()
    if (axios.isCancel(error)) {
      console.log('取消重复请求')
      return new Promise(() => {})
    }
    // 取消addForm 提交按钮loading
    if (typeof error.message === 'string') {
      error.message = error.message.toLowerCase().includes('network')
        ? '网络错误,无法连接到服务器'
        : error.message
      error.message = error.message.toLowerCase().includes('timeout')
        ? '连接服务器超时'
        : error.message

      // if (error.message.toLowerCase().includes('unexpected')) {
      //   // 可能是被后端重定向到登录页，所以清除TOKEN强制跳转登录页
      //   store.dispatch('user/resetToken').then(() => {
      //     location.reload()
      //   })
      // }
    }
    if (error && error.response && error.response.status) {
      console.debug('err', error.response.status) // for debug
      switch (error.response.status) {
        case 400:
          error.message = '请求错误'
          break
        case 401:
          error.message = '未授权，请登录'
          break
        case 403:
          error.message = '拒绝访问'
          break
        case 404:
          error.message = `请求地址出错: ${error.response.config.url}`
          break
        case 408:
          error.message = '请求超时'
          break
        case 500:
          error.message = '服务器内部错误'
          break
        case 501:
          error.message = '服务未实现'
          break
        case 502:
          error.message = '网关错误'
          break
        case 503:
          error.message = '服务不可用'
          break
        case 504:
          error.message = '网关超时'
          break
        case 505:
          error.message = 'HTTP版本不受支持'
          break
        default:
          error.message = '网络错误,无法连接到服务器'
          break
      }
    }
    error.message = error.message || '网络错误,无法连接到服务器'
    ElMessage({
      showClose: true,
      message: error.message || error.msg,
      type: 'error',
      duration: 5000
    })
    return Promise.reject(error)
  }
)

export default service
