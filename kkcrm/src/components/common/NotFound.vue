<template>
  <div class="warning-container">
    <div class="warning-form">
      <el-result
          icon="warning"
          :title="msg"
      ></el-result>
      <h5>您可以：</h5>
      <el-button type="primary" class="warning-btn" @click="goBack()">点此返回上一页</el-button>
    </div>
  </div>
</template>
<script>

export default {
	data () {
		return {
      msg: '404！无法找到您访问的页面'
		}
	},
	methods: {
    goBack() {
      this.$router.go(-2)
    },
    goLogin() {
      this.$router.push('/admin/login')
    }

	},
}

</script>
<style scoped>
  body {
    background-color: #1e222d !important;
  }
	.warning-container{
    background-color: #1e222d;
    width: 100%;
    height: 100%;
    padding-top: 10%;
	}
  .warning-form{
    margin: 0 auto;
    padding: 0 30px 50px 30px;
    width: 500px;
    background-color: rgb(50, 50, 50);
    border-radius: 5px;
  }
  .warning-form h3 {
    color: #f2f2f2;
    text-align: center;
    font-size: 22px;
  }
  .warning-form h5 {
    margin: 20px 0;
    color: #7c878e;
    font-weight: normal;
  }

  .warning-title{
    font-size: 24px;
    font-weight: 700;
    color: #f2f2f2;
    text-align: center;
    margin-bottom: 30px;
  }
  .el-row{
    margin-top: 20px;
  }
  .warning-btn{
    width: 100%;
  }
</style>
<style>
.el-divider__text{
  background-color: rgb(50, 50, 50) !important;
  color: #7c878e!important;
  font-size: 12px!important;
}
.el-result__title p{
  color: #f2f2f2!important;
}
</style>
