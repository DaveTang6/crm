export default {
  loginIn: function (userinfo) {
    localStorage.setItem('auth', JSON.stringify(userinfo))
  },
  loginOut: function () {
    localStorage.removeItem('auth')
  },
  getAuth: function (cbIn, cbOut) {
    let auth = localStorage.getItem('auth')
    if (auth !== null) {
      auth = JSON.parse(auth)
      cbIn(auth)
    } else {
      cbOut()
    }
  },
  update: function (p) {
    let auth = localStorage.getItem('auth')
    if (auth === null) {
      return null
    }
    auth = JSON.parse(auth)
    for (let k in p) {
      auth[k] = p[k]
    }
    localStorage.setItem('auth', JSON.stringify(auth))
  }
}
