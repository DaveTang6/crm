<template>
	<div class="main-top-bar">
		<div class="logo">Customer Sales Management System</div>
		<div class="searcher"></div>
		<div class="user">
			<el-dropdown>
			  <span class="el-dropdown-link">
			    {{ user }}<i class="el-icon-arrow-down el-icon--right"></i>
			  </span>
			  <template #dropdown>
			    <el-dropdown-menu>
			      <el-dropdown-item @click="handleSelfUpdate" >Setting</el-dropdown-item>
			      <el-dropdown-item divided @click="loginOut">Login out</el-dropdown-item>
			    </el-dropdown-menu>
			  </template>
			</el-dropdown>
		</div>
	</div>
</template>
<script>
export default {
  data () {
    return {
		  user: this.$store.getters.user
    }
  },
  inject: ['checkMenu'],
  methods: {
    handleSelfUpdate() {
      this.$router.push('/admin/selfUpdate')
    },
    loginOut (v) {
      this.$store.dispatch('loginOut')
      this.$router.push('/admin/login')
    },
  },
}	
	
</script>
<style scoped>
.main-top-bar{
	position: relative;
	display: flex;
	justify-content: space-between;
	align-items: center;
	box-sizing: border-box;
	height: 48px;
	color:#c1c6c8;
}
.logo{
	color: #FFFFFF;
	font-weight: bold;
}
.el-dropdown-link {
	cursor: pointer;
  color: #f2f2f2;
}
</style>
