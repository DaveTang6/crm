const formatDateTime = (odate, format = 'yyyy-mm-dd hh:ii:ss') => {
  let myDate = new Date(odate)
  if(format === 'stamp') {
    return myDate.getTime()
  }
  let m = myDate.getMonth() + 1
  let d = myDate.getDate()
  let h = myDate.getHours()
  let i = myDate.getMinutes()
  let s = myDate.getSeconds()
  format = format.replace('yyyy', myDate.getFullYear())
  format = format.replace('mm', m < 10 ? ('0' + m) : m)
  format = format.replace('dd', d < 10 ? ('0' + d) : d)
  format = format.replace('hh', h < 10 ? ('0' + h) : h)
  format = format.replace('ii', i < 10 ? ('0' + i) : i)
  format = format.replace('ss', s < 10 ? ('0' + s) : s)
  return format
}

export default formatDateTime
