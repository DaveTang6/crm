import router from '@/router/index.js'
const g = {
  gotoWarning: (msg) => {
    router.push('/common/warning/' + encodeURIComponent(msg))
  }
}

export default g
