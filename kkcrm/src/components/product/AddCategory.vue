<template>
	<MainTitle :data-menu="menu" data-active="1" data-sub-title="新增" >产品类别</MainTitle>
	<MainBody>
			  <el-form ref="form" :model="form" :rules="rules" label-width="100px" class="category-form">
				  <el-form-item label="类别名称" prop="title">
					<el-input v-model="form.title"></el-input>
				  </el-form-item>

				  <el-form-item label="父类">
					<el-select v-model="form.parent_id" value-key="id" placeholder="请选择父类">
					  <el-option :label="item.title" :value="item.id" :key="item.id" v-for="item in categoryList"></el-option>
					</el-select>
				  </el-form-item>

				  <el-form-item label="是否可用">
					<el-switch v-model="form.status" :active-value="1" :inactive-value="0"></el-switch>
				  </el-form-item>

				  <el-form-item>
					<el-button type="primary" @click="append()">立即创建</el-button>
					<el-button @click="goBack">取消</el-button>
				  </el-form-item>
			</el-form>
	</MainBody>
</template>
<script>
	import {addCategory, getKeyPath} from '@/api/products'
export default {
	data () {
		return {
			menu: [{
				title: '列表',
				path: '/product/Category'
			},{
				title: '新增',
				path: '/product/AddCategory/'
			}],
			keyPath: [],
			categoryList: [],
			form: {
			  title: '',
			  parent_id: '',
			  status: 1,
			},
			rules: {
				title: [
					{required: true, message: '请输入类别名称', trigger: 'blur'}
				],
			}
		}
	},
	mounted() {
		this.getList()

	},
	methods: {
		goBack(){
			this.$router.back()
		},
		append() {
			this.$refs['form'].validate((valid) => {
				if (valid) {
					addCategory(this.form).then((res)=>{
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
		getList () {
			getKeyPath()
				.then(res => {
					this.categoryList = res.memo.list
					if(!!this.$route.params.pid){
						this.form.parent_id = parseInt(this.$route.params.pid)

					}
			     })
		}, // 1
	},
}

</script>
<style lang="scss" scoped>
	.tree-containter{
		padding: 10px 0;
	}
	.tree-node{
		flex: 1;
		display: flex;
		align-items: center;
		justify-content: space-between;
		font-size: 14px;
		padding-right: 8px;
		a {
			padding: 10px;
		}
	}
	.category-form {
		width: 500px;
		padding: 10px;

	}
</style>
