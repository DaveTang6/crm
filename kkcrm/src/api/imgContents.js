import request from '@/utils/request'

export function addContent(data) {
  return request({
    url: 'ImgContents/add',
    method: 'post',
    data
  })
}

export function getContents(data) {
  return request({
    url: 'ImgContents/imgList',
    method: 'post',
    data
  })
}

export function setStatus(data) {
  return request({
    url: 'ImgContents/setStatus',
    method: 'post',
    data
  })
}

export function deleteContent(data) {
  return request({
    url: 'ImgContents/delete',
    method: 'post',
    data
  })
}

export function updateContent(data) {
  return request({
    url: 'ImgContents/update',
    method: 'post',
    data
  })
}

export function viewContent(data) {
  return request({
    url: 'ImgContents/view',
    method: 'post',
    data
  })
}

