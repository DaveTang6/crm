import request from '@/utils/request'

export function addAdminAro(data) {
  return request({
    url: 'AdminAros/add',
    method: 'post',
    data
  })
}

export function updateAdminAro(data) {
  return request({
    url: 'AdminAros/update',
    method: 'post',
    data
  })
}

export function deleteAdminAro(data) {
  return request({
    url: 'AdminAros/delete',
    method: 'post',
    data
  })
}

export function getAdminAro(data) {
  return request({
    url: 'AdminAros/getList',
    method: 'post',
    data
  })
}


export function getView(data) {
  return request({
    url: 'AdminAros/view',
    method: 'post',
    data
  })
}

export function copyAdminAro(data) {
  return request({
    url: 'AdminAros/copyAro',
    method: 'post',
    data
  })
}

export function getSelfAros() {
  return request({
    url: 'AdminAros/getSelfAros',
    method: 'post',
  })
}
