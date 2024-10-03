import request from '@/utils/request'

export function customerAll(data) {
  return request({
    url: 'CustomerPools/statisticAll',
    method: 'post',
    data
  })
}

export function orderAll(data) {
  return request({
    url: 'orders/statisticAll',
    method: 'post',
    data
  })
}

export function customerDepartment(data) {
  return request({
    url: 'CustomerPools/statisticDepartment',
    method: 'post',
    data
  })
}

export function orderDepartment(data) {
  return request({
    url: 'orders/statisticDepartment',
    method: 'post',
    data
  })
}

export function customerMine(data) {
  return request({
    url: 'CustomerPools/statisticMine',
    method: 'post',
    data
  })
}

export function orderMine(data) {
  return request({
    url: 'orders/statisticMine',
    method: 'post',
    data
  })
}

