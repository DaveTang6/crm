<template>
  <div class="gceditor-view" v-html="content">
  </div>
</template>
<script>
import '@wangeditor/editor/dist/css/style.css'

export default {
  props: ['content'],
}	
	
</script>
<style>
.gceditor-view{
  width: 100%;
}
.gceditor-view ul li{
  list-style: disc!important;
  list-style-position: inside !important;
}
.gceditor-view ol li{
  list-style: decimal!important;
  list-style-position: inside !important;
}
</style>
