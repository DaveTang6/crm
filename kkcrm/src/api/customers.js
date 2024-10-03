import request from '@/utils/request'

export function addCustomer(data) {
  return request({
    url: 'Customers/add',
    method: 'post',
    data
  })
}

export function getMyCustomers(data) {
  return request({
    url: 'Customers/myCustomers',
    method: 'post',
    data
  })
}

export function getDepartmentCustomers(data) {
  return request({
    url: 'Customers/departmentCustomers',
    method: 'post',
    data
  })
}

export function customers(data) {
  return request({
    url: 'CustomerPools/customers',
    method: 'post',
    data
  })
}

export function deleteCustomers(data) {
  return request({
    url: 'Customers/delete',
    method: 'post',
    data
  })
}

export function updateCustomer(data) {
  return request({
    url: 'Customers/update',
    method: 'post',
    data
  })
}

export function viewCustomer(data) {
  return request({
    url: 'Customers/view',
    method: 'post',
    data
  })
}

export function addCustomerSaleLog(data) {
  return request({
    url: 'CustomerSaleLogs/add',
    method: 'post',
    data
  })
}

export function updateCustomerSaleLog(data) {
  return request({
    url: 'CustomerSaleLogs/update',
    method: 'post',
    data
  })
}

export function getCustomerSaleLogs(data) {
  return request({
    url: 'CustomerSaleLogs/index',
    method: 'post',
    data
  })
}

export function deleteCustomerSaleLogs(data) {
  return request({
    url: 'CustomerSaleLogs/delete',
    method: 'post',
    data
  })
}

export function updateCustomerMemo(data) {
  return request({
    url: 'CustomerPools/updateMemo',
    method: 'post',
    data
  })
}

export function releaseCustomers(data) {
  return request({
    url: 'CustomerPools/releaseCustomers',
    method: 'post',
    data
  })
}

export function assignCustomers(data) {
  return request({
    url: 'CustomerPools/assignCustomers',
    method: 'post',
    data
  })
}

export function viewPlusCustomer(data) {
  return request({
    url: 'CustomerPools/view',
    method: 'post',
    data
  })
}

export function deletePlusCustomer(data) {
  return request({
    url: 'CustomerPools/delete',
    method: 'post',
    data
  })
}

export function customerLogs(data) {
  return request({
    url: 'CustomerActLogs/actLogs',
    method: 'post',
    data
  })
}

export function findCustomers(data) {
  return request({
    url: 'CustomerPools/findCustomers',
    method: 'post',
    data
  })
}
