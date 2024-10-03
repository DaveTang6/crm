import request from '@/utils/request'


export function getDepartments(data) {
  return request({
    url: 'Departments/index',
    method: 'post',
    data
  })
}

export function getDepartmentStaffs(data) {
  return request({
    url: 'Departments/getStaffs',
    method: 'post',
    data
  })
}

export function deleteDepartmentStaffs(data) {
  return request({
    url: 'Departments/delete',
    method: 'post',
    data
  })
}

export function addDepartmentStaffs(data) {
  return request({
    url: 'Departments/addStaff',
    method: 'post',
    data
  })
}

export function getDepartmentMyStaffs(data) {
  return request({
    url: 'Departments/myStaffs',
    method: 'post',
    data
  })
}
