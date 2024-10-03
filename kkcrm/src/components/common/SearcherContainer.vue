<template>
	<div class="searcher-container">
		<slot></slot>
	</div>
</template>
<script>
</script>
<style scoped>
	.searcher-container{
    width: 100%;
    border-bottom: 1px solid #dfe6ee;
    background-color: #fff;
    padding: 5px 0;
    flex: 0 0 auto;
	}
  .searcher-container .el-form-item__label{
    font-size: 12px!important;
  }
</style>
