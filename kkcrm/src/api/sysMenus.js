import request from '@/utils/request'

export function addCategory(data) {
  return request({
    url: 'SysMenus/add',
    method: 'post',
    data
  })
}

export function updateCategory(data) {
  return request({
    url: 'SysMenus/update',
    method: 'post',
    data
  })
}

export function getCategories() {
  return request({
    url: 'SysMenus/getList',
    method: 'post',
  })
}

export function getMenu() {
  return request({
    url: 'SysMenus/getMenu',
    method: 'post',
  })
}

export function getKeyPath() {
  return request({
    url: 'SysMenus/getKeyPath',
    method: 'post',
  })
}

export function moveCategory(data) {
  return request({
    url: 'SysMenus/move',
    method: 'post',
    data
  })
}

export function removeCategory(data) {
  return request({
    url: 'SysMenus/delete',
    method: 'post',
    data
  })
}

export function getView(data) {
  return request({
    url: 'SysMenus/view',
    method: 'post',
    data
  })
}
