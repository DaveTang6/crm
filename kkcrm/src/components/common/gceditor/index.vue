<template>
  <div ref="gceditor" class="gceditor">
    <Toolbar
        style="border-bottom: 1px solid #ccc"
        :editor="editorRef"
        :defaultConfig="toolbarConfig"
        :mode="mode"
    />
    <Editor
        style="height: 380px; overflow-y: hidden;"
        v-model="valueHtml"
        :defaultConfig="editorConfig"
        :mode="mode"
        @onCreated="handleCreated"
    />
  </div>
</template>
<script>
import '@wangeditor/editor/dist/css/style.css'
import { onBeforeUnmount, ref, shallowRef, onMounted, createElementBlock, computed } from 'vue'
import { Editor, Toolbar } from '@wangeditor/editor-for-vue'
import siteConfig from '@/config/config.js'
import store from '@/store/index.js'
import { ElMessage } from 'element-plus'

export default {
  components: { Editor, Toolbar },
  props: ['modelValue'],
  emits: ['update:modelValue'],
  setup(props, {emit}) {
    // 编辑器实例，必须用 shallowRef
    const editorRef = shallowRef()

    // 内容 HTML
    const valueHtml = computed({
      get() {
        return props.modelValue
      },
      set(value) {
        emit('update:modelValue', value)
      }
    })

    const imgTypes = ['image/png', 'image/jpeg', 'image/gif']
    const videoTypes = ['video/mp4']
    const imgTypeMsg = ['png', 'jpg', 'gif']
    const videoTypeMsg = ['mp4']
    const imgSize = 1024 * 1024 * 10
    const videoSize = 1024 * 1024 * 100

    const toolbarConfig = {}

    const editorConfig = {
      placeholder: '请输入内容...',
      MENU_CONF: {
        uploadImage: {
          fieldName: 'file',
          server: siteConfig('updateURL'),
          maxFileSize: imgSize,
          maxNumberOfFiles: 5,
          allowedFileTypes: imgTypes,
          headers: {
            Authorization: `Bearer ${store.getters.jwt}`
          },
          onBeforeUpload(file) {
            let fileObj = Object.values(file)
            fileObj = fileObj[0]
            if(imgTypes.indexOf(fileObj.type) < 0) {
              ElMessage.error({
                message: '只能上传' + imgTypeMsg.join(',') + '类型的图片',
                type: 'error',
                showClose: true,
              })
              return false
            }
            return file
          },
          customInsert(res, insertFn){
            if(res.status === 400){
              ElMessage.error({
                message: res.msg,
                type: 'error',
                showClose: true,
              })
            } else {
              let url = siteConfig('apiURL') + 'files/' + res.memo.fullName
              insertFn(url, res.memo.oriName, url)
            }
          },
          onError(file, err, res){
            ElMessage.error({
              message: err,
              type: 'error',
              showClose: true,
            })
          }
        },
        uploadVideo: {
          fieldName: 'file',
          server: siteConfig('updateURL'),
          maxFileSize: videoSize,
          maxNumberOfFiles: 5,
          allowedFileTypes: videoTypes,
          headers: {
            Authorization: `Bearer ${store.getters.jwt}`
          },
          onBeforeUpload(file) {
            let fileObj = Object.values(file)
            fileObj = fileObj[0]
            if(videoTypes.indexOf(fileObj.type) < 0) {
              ElMessage.error({
                message: '只能上传' + videoTypeMsg.join(',') + '类型的视频',
                type: 'error',
                showClose: true,
              })
              return false
            }
            return file
          },
          customInsert(res, insertFn){
            if(res.status === 400){
              ElMessage.error({
                message: res.msg,
                type: 'error',
                showClose: true,
              })
            } else {
              let url = siteConfig('apiURL') + 'files/' + res.memo.fullName
              insertFn(url, res.memo.oriName, url)
            }

          },
          onError(file, err, res){
            ElMessage.error({
              message: err,
              type: 'error',
              showClose: true,
            })
          }
        }
      }
    }

    // 组件销毁时，也及时销毁编辑器
    onBeforeUnmount(() => {
      const editor = editorRef.value
      if (editor == null) return
      editor.destroy()
    })

    const handleCreated = (editor) => {
      editorRef.value = editor // 记录 editor 实例，重要！
    }

    return {
      editorRef,
      valueHtml,
      mode: 'default', // 或 'simple'
      toolbarConfig,
      editorConfig,
      handleCreated
    };
  }
}	
	
</script>
<style>
.gceditor{
  z-index: 999;
  width: 100%;
  border: 1px solid #DCDFE6;
}
.gceditor ul li{
  list-style: disc!important;
}
.gceditor ol li{
  list-style: decimal!important;
}
</style>
