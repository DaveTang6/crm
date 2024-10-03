import request from '@/utils/request'

export function addCategory(data) {
  return request({
    url: 'AdminAcos/add',
    method: 'post',
    data
  })
}

export function updateCategory(data) {
  return request({
    url: 'AdminAcos/update',
    method: 'post',
    data
  })
}

export function getCategories() {
  return request({
    url: 'AdminAcos/getList',
    method: 'post',
  })
}

export function getKeyPath() {
  return request({
    url: 'AdminAcos/getKeyPath',
    method: 'post',
  })
}

export function moveCategory(data) {
  return request({
    url: 'AdminAcos/move',
    method: 'post',
    data
  })
}

export function removeCategory(data) {
  return request({
    url: 'AdminAcos/delete',
    method: 'post',
    data
  })
}

export function getView(data) {
  return request({
    url: 'AdminAcos/view',
    method: 'post',
    data
  })
}
