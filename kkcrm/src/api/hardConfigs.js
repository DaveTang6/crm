import request from '@/utils/request'

export function controlView(data) {
  return request({
    url: 'HardConfigs/view/' + data.alias,
    method: 'post',
    data
  })
}

export function controlUpdate(data) {
  return request({
    url: 'HardConfigs/update/' + data.alias,
    method: 'post',
    data
  })
}
