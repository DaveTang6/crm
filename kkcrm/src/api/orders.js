import request from '@/utils/request'

export function orderAdd(data) {
  return request({
    url: 'Orders/add',
    method: 'post',
    data
  })
}

export function orderUpdate(data) {
  return request({
    url: 'Orders/update',
    method: 'post',
    data
  })
}

export function orderDelete(data) {
  return request({
    url: 'Orders/delete',
    method: 'post',
    data
  })
}

export function orderMines(data) {
  return request({
    url: 'Orders/myOrders',
    method: 'post',
    data
  })
}

export function orders(data) {
  return request({
    url: 'Orders/getOrders',
    method: 'post',
	data
  })
}

export function orderDepartments(data) {
  return request({
    url: 'Orders/departmentOrders',
    method: 'post',
	data
  })
}

export function orderView(data) {
  return request({
    url: 'Orders/view',
    method: 'post',
	data
  })
}

export function orderViewPlus(data) {
  return request({
    url: 'Orders/ViewPlus',
    method: 'post',
    data
  })
}

export function orderLogs(data) {
  return request({
    url: 'OrderActLogs/actLogs',
    method: 'post',
    data
  })
}

export function orderFinance(data) {
  return request({
    url: 'Orders/updateFinance',
    method: 'post',
    data
  })
}

export function orderCost(data) {
  return request({
    url: 'Orders/updateCost',
    method: 'post',
    data
  })
}

export function orderCheck(status, data) {
  return request({
    url: 'Orders/updateStatus/' + status,
    method: 'post',
    data
  })
}

export function orderCheckLogs(data) {
  return request({
    url: 'OrderActLogs/checkLogs',
    method: 'post',
    data
  })
}

export function orderProducts(data, role='') {
  return request({
    url: 'OrderProducts/getList/' + role ,
    method: 'post',
    data
  })
}
