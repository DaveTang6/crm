<template>
	<MainTitle :data-menu="menu" data-active="0" >合同类别</MainTitle>
	<MainBody>
			  <el-tree
					class="tree-containter"
			        :data="categoryList"
					:props="defaultProps"
			        node-key="id"
			        default-expand-all
			        :expand-on-click-node="false">
			        <template #default="{ node, data }">
						<span class="tree-node">
						  <span>{{node.label}}</span>
						  <span>
							<a title="上移"
								href="javaScript:;"
							    @click="move(data.id, 'up')">
							    <i class="el-icon-top"></i>
							</a>
							  <a title="下移"
								href="javaScript:;"
							    @click="move(data.id, 'down')">
							    <i class="el-icon-bottom"></i>
							  </a>
							<a title="添加子类别"
								href="javaScript:;"
							  @click="append(data.id)">
							 <i class="el-icon-circle-plus-outline"></i>
							</a>
							<a title="修改"
								href="javaScript:;"
							  @click="update(data.id)">
							  <i class="el-icon-edit"></i>
							</a>
							<a title="删除"
								href="javaScript:;"
							  @click="remove(data.id)">
							  <i class="el-icon-close"></i>
							</a>
						  </span>
						</span>
			        </template>
			      </el-tree>
	</MainBody>
</template>
<script>
	import {getCategories, moveCategory, removeCategory} from '@/api/contracts'
export default {
	data () {
		return {
			menu: [{
				title: '列表',
				path: '/contract/Category'
			},{
				title: '新增',
				path: '/contract/AddCategory/'
			}],
			categoryList: [],
			defaultProps: {
			  children: 'children',
			  label: 'title',
			  status: 'status'
			},

		}
	},
	mounted() {
		this.getList()
	},
	methods: {
		remove(id) {
			this.$confirm('此操作将删除该类别,该分类下的子类将归于未分类，是否继续?', '提示', {
			  confirmButtonText: '确定',
			  cancelButtonText: '取消',
			  type: 'warning'
			}).then(() => {
				removeCategory({id: id})
				.then(res=>{
					this.getList()
					this.$message({
						showClose: true,
						type: 'success',
						message: '删除成功!'
					});
				})

			})
			return false
		},
		move(id, action) {
			moveCategory({id: id, action: action})
				.then(res=>{
					this.getList()
				})
		},
		append(pid) {
			this.$router.push('/contract/addCategory/' + pid)
		},
		update(id) {
			this.$router.push('/contract/updateCategory/' + id)
		},
		getList () {
			getCategories()
				.then(res => {
					this.categoryList = res.memo.list
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
</style>
