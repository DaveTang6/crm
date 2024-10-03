<template>
	<MainTitle  data-sub-title="修改" data-back="true" >管理组</MainTitle>
	<MainBody>
			  <el-form ref="form" :model="form" :rules="rules" label-width="100px" class="category-form">
				  <el-form-item label="管理组名" prop="alias">
					<el-input v-model="form.alias"></el-input>
				  </el-form-item>

				  <el-form-item>
					  <el-button type="primary" @click="append()" >确认修改</el-button>
					  <el-button @click="goBack">取消</el-button>

				  </el-form-item>
			</el-form>
	</MainBody>
</template>
<script>
	import {updateAdminAro} from '@/api/adminAros'
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
			  id: this.$route.params.id,
			  alias: this.$route.params.alias,
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
          updateAdminAro(this.form).then((res)=>{
						this.$message.success({
							message: '修改成功',
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

