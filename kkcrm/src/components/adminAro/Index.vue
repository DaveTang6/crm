<template>
	<MainTitle :data-menu="menu" data-active="0" data-sub-title="管理组">权限中心</MainTitle>
	<MainBody>
    <MainContainer>
			<el-table
				:data="tableData"
				stripe
        width="100%"
        height="100%"
			>
				<el-table-column
          prop="id"
          label="ID"
				  width="60">
				</el-table-column>
				<el-table-column
					prop="alias"
					label="管理组"
          width="300"
        >
				</el-table-column>

				<el-table-column
					label="操作">
				  <template #default="scope">
					<el-button
					  size="mini"
            type="info"
					  @click="handleEdit(scope.row.id, scope.row.alias)">编辑</el-button>
            <el-button
                size="mini"
                type="danger"
                @click="handleDelete(scope.row.id)">删除</el-button>
            <el-button
                size="mini"
                type="primary"
                @click="handleCopy(scope.row.id)">复制</el-button>
            <el-button
                size="mini"
                type="success"
                @click="handleAllow(scope.row.id, scope.row.alias)">可用权限</el-button>
            <el-button
                size="mini"
                type="warning"
                @click="handleDeny(scope.row.id, scope.row.alias)">禁用权限</el-button>
				  </template>
				</el-table-column>
			</el-table>
    </MainContainer>
	</MainBody>
</template>
<script>
	import {getAdminAro, deleteAdminAro, copyAdminAro} from '@/api/adminAros'
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
			tableData: [],
		}
	},
	mounted() {
		this.getList()
	},
	methods: {
    handleCopy(id) {
      this.$confirm('此操作将复制选中的组，是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        copyAdminAro({id: id})
            .then(res=>{
              this.getList()
              this.$message({
                showClose: true,
                type: 'success',
                message: '复制成功!'
              });
            })
      })
      return false
    },
		handleDelete(id) {
			this.$confirm('此操作将删除选中的组，是否继续?', '提示', {
			  confirmButtonText: '确定',
			  cancelButtonText: '取消',
			  type: 'warning'
			}).then(() => {
        deleteAdminAro({id: id})
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
		handleEdit(id, alias) {
			this.$router.push('/adminAro/update/' + id + '/' + alias)
		},
    handleAllow(id, alias) {
			this.$router.push('/adminAro/allowed/' + id + '/' + alias)
		},
    handleDeny(id, alias) {
      this.$router.push('/adminAro/denied/' + id + '/' + alias)
    },
		getList () {
      getAdminAro({type: 'simple'})
				.then(res => {
					this.tableData = res.memo.list
			})
		},
	},
}

</script>
<style lang="scss" scoped>
	.el-icon-close, .el-icon-lock{
		color: #ff0000;
	}
	.el-icon-check, .el-icon-unlock{
		color: #42B983
	}
  .el-icon-lock{
    cursor: pointer;
  }
</style>
