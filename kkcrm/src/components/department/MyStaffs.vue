<template>
  <MainTitle :data-menu="menu" data-active="1"  data-sub-title="我的下属">部门人员</MainTitle>
  <OperationContainer
      :recordNum="pager.total"
      @onShowSearcher="handleShowSearcher"
  >
  </OperationContainer>
	<MainBody>
    <SearcherContainer v-show="showSearcher">
      <el-form :inline="true" :model="searcher" class="form-searcher" size="mini">
        <el-form-item label="姓名">
          <el-input v-model="searcher.truename" placeholder="请输入姓名"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="getList()">查询</el-button>
        </el-form-item>
      </el-form>
    </SearcherContainer>
		<MainContainer>

			<el-table
				:data="tableData"
				stripe
				height="100%"
        width="100%"
        @selection-change="handleSelectionChange"
      >

				<el-table-column
				  type="index"
				  width="80"
          label="序号"
        >
				</el-table-column>
        <el-table-column
            prop="truename"
            label="姓名"
        >
        </el-table-column>
			</el-table>
		</MainContainer>
    <el-pagination
        style="padding: 10px 0;"
        background
        layout="prev, pager, next"
        :total="pager.total"
        v-model:currentPage= "pager.page"
        :page-size="pager.pageSize"
        @current-change="getList"
    >
    </el-pagination>
	</MainBody>
</template>
<script>
	import {getDepartmentMyStaffs} from '@/api/departments'

export default {
	data () {
		return {
      menu: [{
        title: '列表',
        path: '/department/index',
      },{
        title: '我的下属',
        path: '/department/myStaffs',
      },],
			tableData: [],
			pager: {
				page: 1,
				total: 0,
				pageSize: 30
			},
      searcher: {
        truename: '',
        manager_id: this.$route.params.id
      },
      selectedItems: [],
      manager: this.$route.params.manager,
      dialogVisible: false,
      showSearcher: false,
		}
	},
	mounted() {
		this.getList()
	},
	methods: {
    handleShowSearcher(v){
      this.showSearcher = v
    },
    handleDelete() {
      if(this.selectedItems.length<=0){
        this.$message.warning('请先选择需要删除的项')
        return false
      }
      this.$confirm('此操作将删除选中的项，是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteDepartmentStaffs({ids: this.selectedItems})
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
    handleSelectionChange(val) {
      this.selectedItems=[]
      for(let i in val){
        this.selectedItems.push(val[i].id)
      }

    },
		getList () {
			let data = {...this.searcher, ...this.pager}
      getDepartmentMyStaffs(data)
				.then(res => {
					this.tableData = res.memo.list
					this.pager.page = res.memo.page
					this.pager.pageSize = res.memo.pageSize
					this.pager.total = res.memo.total
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
  .el-icon-hot-water, .el-icon-money{
    color: #409eff;
    font-size: 16px;
    padding-right: 10px;
  }
</style>
