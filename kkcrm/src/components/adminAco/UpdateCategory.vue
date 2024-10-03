<template>
	<MainTitle  data-sub-title="修改" :data-back="true">权限设置</MainTitle>
	<MainBody>
			  <el-form ref="form" :model="form" :rules="rules"  label-width="100px" class="category-form">
				  <el-form-item label="权限名" prop="title">
					<el-input v-model="form.title"></el-input>
				  </el-form-item>
          <el-form-item label="别名" prop="alias">
            <el-input v-model="form.alias"></el-input>
          </el-form-item>
				  <el-form-item label="父权限">
					<el-select v-model="form.parent_id" value-key="id" placeholder="请选择父分类">
					  <el-option :label="item.title" :value="item.id" v-for="(item,index) in categoryList" :key="item.id"></el-option>
					</el-select>
				  </el-form-item>

          <el-form-item label="权限设置" prop="memo" >
            <template v-for="(role,index) in form.memo">
              <el-input v-model="role.r" class="input-with-select">
                <template #prepend>
                  <el-select v-model="role.p" placeholder="请选择">
                    <el-option label="匹配" :value="0"></el-option>
                    <el-option label="正则" :value="1"></el-option>
                  </el-select>
                </template>
                <template #append>
                  <el-button  icon="el-icon-close" @click="deleteRole(index)"></el-button>
                </template>
              </el-input>
            </template>
            <el-button @click="addRole()" size="mini" type="info" >
              <el-icon class="el-icon-plus"></el-icon> 添加权限
            </el-button>
          </el-form-item>

				  <el-form-item label="是否可用">
					<el-switch v-model="form.status" :active-value="1" :inactive-value="0"></el-switch>
				  </el-form-item>

				  <el-form-item>
					<el-button type="primary" @click="append()">修改</el-button>
					<el-button @click="goBack">取消</el-button>
				  </el-form-item>
			</el-form>
	</MainBody>
</template>
<script>
	import {updateCategory, getKeyPath, getView} from '@/api/adminAcos'

export default {
	data () {
		return {
			menu: [],
			keyPath: [],
			categoryList: [],
			form: {
			  id: '',
			  title: '',
        alias: '',
			  parent_id: '',
			  status: 1,
        memo: []
			},
			rules: {
				title: [
					{required: true, message: '请输入分类名称', trigger: 'blur'}
				],
			}
		}
	},
	mounted() {
		this.getList()
	},

	methods: {
    goBack(){
      this.$router.push('/adminAco/index')
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
							message: '权限修改成功',
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
						status: data.status,
            alias: data.alias,
            memo: data.memo
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
