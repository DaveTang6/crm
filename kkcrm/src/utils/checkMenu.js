import store from '@/store'

const checkMenu = (menu) => {
  let roles = store.getters.role

  if(!roles) {
    return false
  }

  if(roles.allowed.indexOf(menu) < 0) {
    return false
  }

  if(roles.denied.indexOf(menu) >= 0) {
    return false
  }

  return true
}

export default checkMenu
