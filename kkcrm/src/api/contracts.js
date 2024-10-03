import request from '@/utils/request'

export function addCategory(data) {
  return request({
    url: 'ContractCategories/add',
    method: 'post',
    data
  })
}

export function updateCategory(data) {
  return request({
    url: 'ContractCategories/update',
    method: 'post',
    data
  })
}

export function getCategories() {
  return request({
    url: 'ContractCategories/getList',
    method: 'post',
  })
}

export function getKeyPath() {
  return request({
    url: 'ContractCategories/getKeyPath',
    method: 'post',
  })
}

export function moveCategory(data) {
  return request({
    url: 'ContractCategories/move',
    method: 'post',
	data
  })
}

export function removeCategory(data) {
  return request({
    url: 'ContractCategories/delete',
    method: 'post',
	data
  })
}

export function getView(data) {
  return request({
    url: 'ContractCategories/view',
    method: 'post',
	data
  })
}

export function addContract(data) {
  return request({
    url: 'Contracts/add',
    method: 'post',
    data
  })
}

export function getContracts(data) {
  return request({
    url: 'Contracts/index',
    method: 'post',
    data
  })
}

export function setStatus(data) {
  return request({
    url: 'Contracts/setStatus',
    method: 'post',
    data
  })
}

export function deleteContracts(data) {
  return request({
    url: 'Contracts/delete',
    method: 'post',
    data
  })
}

export function updateContract(data) {
  return request({
    url: 'Contracts/update',
    method: 'post',
    data
  })
}

export function viewContract(data) {
  return request({
    url: 'Contracts/view',
    method: 'post',
    data
  })
}
