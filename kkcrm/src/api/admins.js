import request from '@/utils/request'

export function addAdmin(data) {
  return request({
    url: 'Admins/add',
    method: 'post',
    data
  })
}

export function indexAdmin(data) {
  return request({
    url: 'Admins/index',
    method: 'post',
    data
  })
}

export function setAdminEnabled(data) {
  return request({
    url: 'Admins/setEnabled',
    method: 'post',
    data
  })
}

export function setAdminUnlocked(data) {
  return request({
    url: 'Admins/setUnLocked',
    method: 'post',
    data
  })
}

export function deleteAdmin(data) {
  return request({
    url: 'Admins/delete',
    method: 'post',
    data
  })
}

export function updateAdmin(data) {
  return request({
    url: 'Admins/update',
    method: 'post',
    data
  })
}

export function viewAdmin(data) {
  return request({
    url: 'Admins/view',
    method: 'post',
    data
  })
}

export function login(data) {
  return request({
    url: 'Admins/login',
    method: 'post',
    data
  })
}

export function selfUpdate(data) {
  return request({
    url: 'Admins/selfUpdate',
    method: 'post',
    data
  })
}

export function selfView() {
  return request({
    url: 'Admins/selfView',
    method: 'post',
  })
}

export function getAdmins(data) {
  return request({
    url: 'Admins/getAdmins',
    method: 'post',
    data
  })
}
