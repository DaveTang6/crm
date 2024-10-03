<template>
	<MainTitle :data-menu="menu" data-active="1" data-sub-title="新增" >管理组</MainTitle>
	<MainBody>
			  <el-form ref="form" :model="form" :rules="rules" label-width="100px" class="category-form">
				  <el-form-item label="管理组名" prop="alias">
					<el-input v-model="form.alias"></el-input>
				  </el-form-item>

				  <el-form-item>
					  <el-button type="primary" @click="append()" >确认创建</el-button>
					  <el-button @click="goBack">取消</el-button>

				  </el-form-item>
			</el-form>
	</MainBody>
</template>
<script>
	import {addAdminAro} from '@/api/adminAros'
export default {
	data () {
		return {
			menu: [{
				title: '列表',
				path: '/adminAro/index'
			},{
				title: '新增',
				path: '/adminAro/add/'
			}],
			form: {
			  alias: '',
			},
			rules: {
				alias: [
					{required: true, message: '请输入组名', trigger: 'blur'}
				],
			}
		}
	},
	methods: {
		goBack(){
			this.$router.push('/adminAro/index')
		},
		append() {
			this.$refs['form'].validate((valid) => {
				if (valid) {
          addAdminAro(this.form).then((res)=>{
						this.$refs['form'].resetFields()
						this.$message.success({
							message: '添加成功',
							type: 'success',
							showClose: true,
						})
					})

				} else {
					return false;
				}
			});

		},
	},
}

</script>
<style lang="scss" scoped>

	.category-form {
		width: 500px;
		padding: 10px;

	}
</style>

