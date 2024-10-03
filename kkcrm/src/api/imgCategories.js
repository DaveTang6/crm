import request from '@/utils/request'

export function addCategory(data) {
  return request({
    url: 'ImgCategories/add',
    method: 'post',
    data
  })
}

export function updateCategory(data) {
  return request({
    url: 'ImgCategories/update',
    method: 'post',
    data
  })
}

export function getCategories() {
  return request({
    url: 'ImgCategories/getList',
    method: 'post',
  })
}

export function getKeyPath() {
  return request({
    url: 'ImgCategories/getKeyPath',
    method: 'post',
  })
}

export function moveCategory(data) {
  return request({
    url: 'ImgCategories/move',
    method: 'post',
	data
  })
}

export function removeCategory(data) {
  return request({
    url: 'ImgCategories/delete',
    method: 'post',
	data
  })
}

export function getView(data) {
  return request({
    url: 'ImgCategories/view',
    method: 'post',
	data
  })
}
