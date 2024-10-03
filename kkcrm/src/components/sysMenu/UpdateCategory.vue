<template>
	<MainTitle  data-sub-title="修改" :data-back="true">菜单设置</MainTitle>
	<MainBody>
			  <el-form ref="form" :model="form" :rules="rules"  label-width="100px" class="category-form">
          <el-form-item label="菜单名" prop="title">
            <el-input v-model="form.title"></el-input>
          </el-form-item>
          <el-form-item label="地址" prop="path">
            <el-input v-model="form.path"></el-input>
          </el-form-item>
          <el-form-item label="父菜单">
            <el-select v-model="form.parent_id" value-key="id" placeholder="请选择父权限">
              <el-option :label="item.title" :value="item.id" :key="item.id" v-for="item in categoryList"></el-option>
            </el-select>
          </el-form-item>

          <el-form-item label="权限ID" prop="role">
            <el-input v-model.number="form.role"></el-input>
          </el-form-item>

				  <el-form-item>
					<el-button type="primary" @click="append()">修改</el-button>
					<el-button @click="goBack">取消</el-button>
				  </el-form-item>
			</el-form>
	</MainBody>
</template>
<script>
	import {updateCategory, getKeyPath, getView} from '@/api/sysMenus'

export default {
	data () {
		return {
			menu: [],
			keyPath: [],
			categoryList: [],
			form: {
			  id: '',
			},
			rules: {
        title: [
          {required: true, message: '请输入菜单名称', trigger: 'blur'}
        ],
        role: [
          {required: true, message: '请输入权限ID', trigger: 'blur'},
          {type: 'number', message: '权限ID输入错误'}
        ],
			}
		}
	},
	mounted() {
		this.getList()
	},

	methods: {
    goBack(){
      this.$router.push('/sysMenu/index')
    },
    addRole() {
      this.form.memo.push({
        r: '',
        p: 0
      })
    },
    deleteRole(index) {
      this.form.memo.splice(index, 1)
    },
		append() {
			this.$refs['form'].validate((valid) => {
				if (valid) {
          for(let i in this.form.memo) {
            this.form.memo[i].r = this.form.memo[i].r.toLowerCase()
          }
					updateCategory(this.form).then((res)=>{
						this.$message.success({
							message: '菜单修改成功',
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
					this.getInfo()
			     })
		},
		getInfo() {
			getView({id: this.$route.params.id})
				.then(res => {
					let data = res.memo
					this.form = {
						id: data.id,
						title: data.title,
						parent_id: data.parent_id,
						path: data.path,
            role: data.role,
					}
			})
		}
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
