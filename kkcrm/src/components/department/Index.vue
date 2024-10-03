<template>
	<MainTitle :data-menu="menu" data-active="0"  data-sub-title="列表">部门人员</MainTitle>
  <OperationContainer
      :recordNum="pager.total"
      @onShowSearcher="handleShowSearcher"
  >
    <el-button type="primary" size="mini" @click="handleShow()">分配选中的人员</el-button>
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
            type="selection"
            width="55">
        </el-table-column>

				<el-table-column
				  type="index"
				  width="55"
          label="序号"
        >
				</el-table-column>
        <el-table-column
            prop="truename"
            label="姓名"
            width="120"
        >
        </el-table-column>
        <el-table-column
            prop="group_id"
            label="用户组"
        >
        </el-table-column>
        <el-table-column
            prop="staff_num"
            label="管理人数"
            width="120"
        >
        </el-table-column>
				<el-table-column
					label="操作"
          width="100"
        >
          <template #default="scope">
            <el-button
                size="mini"
                type="warning"
                @click="handleStaffs(scope.row.id, scope.row.truename)"
            >查看下属</el-button>
          </template>
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
  <el-dialog
      v-model="dialogVisible"
      title="人员分配"
      width="50%"
  >
    <span>选中的人员分配给：</span>
    <el-autocomplete
        v-model="manager"
        :fetch-suggestions="handleGetAdmins"
        :trigger-on-focus="false"
        clearable
        placeholder="请输入负责人姓名"
        @select="handleSelect"
    />

    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="handleRange"
        >确认</el-button>
      </span>
    </template>
  </el-dialog>
</template>
<script>
	import {getDepartments, addDepartmentStaffs} from '@/api/departments'
	import {getAdmins} from '@/api/admins'

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
      categoryList: [],
			tableData: [],
			pager: {
				page: 1,
				total: 0,
				pageSize: 30
			},
      searcher: {
        truename: ''
      },
      selectedItems: [],
      manager: '',
      managerFull: '',
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
    handleStaffs(id, truename) {
      this.$router.push('/department/staffs/' + id + '/' + truename)
    },
	  handleGetAdmins(v, cb) {
      getAdmins({truename: v})
          .then(res => {
            cb(res.memo.list)
          })
    },
    handleSelect(v) {
      this.managerFull = v;
    },
    handleRange() {
      //确认加入
      if(this.managerFull['value'] != this.manager) {
        this.$message.warning('请选择负责人而非填写负责人名称')
        return false
      }
      addDepartmentStaffs({'manager_id': this.managerFull['key'], 'staff_ids' : this.selectedItems}).then((res)=>{
        this.getList()
        this.dialogVisible = false
        this.$message.success({
          message: '员工添加成功',
          type: 'success',
          showClose: true,
        })
      })
    },
    handleShow() {
      if(this.selectedItems.length<=0){
        this.$message.warning('请先选择相关人员')
        return false
      }
      this.dialogVisible = true
    },
    handleSelectionChange(val) {
      this.selectedItems=[]
      for(let i in val){
        this.selectedItems.push(val[i].id)
      }

    },
		getList () {
			let data = {...this.searcher, ...this.pager}
      getDepartments(data)
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
