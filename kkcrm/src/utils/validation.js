const validation = {
  mobile: (rule, value, callback) => {
    if( value=== '') {
      return callback()
    }
    let p = /^(13|14|15|16|17|18|19)\d{9}$/
    if( !p.test(value) ) {
      callback(new Error('手机号码格式错误'))
    }

    callback();
  }
}

export default validation
