import request from '@/utils/request'

export function addCategory(data) {
  return request({
    url: 'ProductCategories/add',
    method: 'post',
    data
  })
}

export function updateCategory(data) {
  return request({
    url: 'ProductCategories/update',
    method: 'post',
    data
  })
}

export function getCategories() {
  return request({
    url: 'ProductCategories/getList',
    method: 'post',
  })
}

export function getKeyPath() {
  return request({
    url: 'ProductCategories/getKeyPath',
    method: 'post',
  })
}

export function moveCategory(data) {
  return request({
    url: 'ProductCategories/move',
    method: 'post',
	data
  })
}

export function removeCategory(data) {
  return request({
    url: 'ProductCategories/delete',
    method: 'post',
	data
  })
}

export function getView(data) {
  return request({
    url: 'ProductCategories/view',
    method: 'post',
	data
  })
}

export function addProduct(data) {
  return request({
    url: 'Products/add',
    method: 'post',
    data
  })
}

export function getProducts(data, role='') {
  return request({
    url: 'Products/index/' + role,
    method: 'post',
    data
  })
}

export function setProductStatus(data) {
  return request({
    url: 'Products/setStatus',
    method: 'post',
    data
  })
}

export function deleteProducts(data) {
  return request({
    url: 'Products/delete',
    method: 'post',
    data
  })
}

export function updateProduct(data) {
  return request({
    url: 'Products/update',
    method: 'post',
    data
  })
}

export function viewProduct(data) {
  return request({
    url: 'Products/view',
    method: 'post',
    data
  })
}

export function viewProductLimit(data) {
  return request({
    url: 'Products/view/limit',
    method: 'post',
    data
  })
}

export function getProductLimit(data) {
  return request({
    url: 'Products/index/limit',
    method: 'post',
    data
  })
}
