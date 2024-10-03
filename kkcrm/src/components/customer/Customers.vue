<template>
	<MainTitle  data-sub-title="客户列表">客户管理</MainTitle>
  <OperationContainer
      :recordNum="pager.total"
      @onShowSearcher="handleShowSearcher"
  >
    <el-button type="warning" @click="handleAssignmentShow()" size="mini">分配选中的人员</el-button>
  </OperationContainer>
	<MainBody>
    <SearcherContainer v-show="showSearcher">
      <el-form :inline="true" :model="searcher" size="mini">
        <el-form-item label="微信号">
          <el-input v-model="searcher.wechat" placeholder="请输入微信号"></el-input>
        </el-form-item>
        <el-form-item label="姓名">
          <el-input v-model="searcher.truename" placeholder="请输入姓名"></el-input>
        </el-form-item>
        <el-form-item label="销售">
          <el-input v-model="searcher.saler" placeholder="请输入销售姓名"></el-input>
        </el-form-item>

        <el-form-item label="锁定情况" prop="locked_by_user">
          <el-select v-model="searcher.locked_by_user"  placeholder="全部">
            <el-option :label="item" :value="index" :key="index" v-for="(item,index) in locked_status"></el-option>
          </el-select>
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
            prop="locked_time"
            label="锁定时间"
            width="120"
        >
        </el-table-column>
        <el-table-column
            prop="expired_time"
            label="过期时间"
            width="120"
        >
        </el-table-column>
        <el-table-column
            label="锁定人"
            width="100"
        >
          <template #default="scope">
            <span v-if="!scope.row.locked_by_user">无</span>
            <span class="locked_by_user" v-else>{{scope.row.locked_by_user}}</span>
          </template>
        </el-table-column>

        <el-table-column
            prop="wechat"
            label="微信号"
            width="120"
        >
        </el-table-column>
        <el-table-column
            prop="truename"
            label="姓名"
            width="120"
        >
        </el-table-column>

        <el-table-column
            prop="area"
            label="地区"
            width="100"
        >
        </el-table-column>
        <el-table-column
            prop="last_buy"
            label="最后购买"
            width="100"
        >
        </el-table-column>
        <el-table-column
            prop="order_count"
            label="下单次数"
            width="100"
        >
        </el-table-column>
        <el-table-column
            prop="memo"
            label="备注"
        >
        </el-table-column>
				<el-table-column
					label="操作"
          width="100"
        >
          <template #default="scope">
            <el-dropdown split-button type="primary" size="small">
              设置
              <template #dropdown>
                <el-dropdown-menu>
                  <el-dropdown-item @click="handleView(scope.row.id)">查看</el-dropdown-item>
                  <el-dropdown-item @click="handleDelete(scope.row.id)">删除</el-dropdown-item>
                  <el-dropdown-item @click="handleMemo(scope.row.id, scope.row.memo)">备注</el-dropdown-item>
                </el-dropdown-menu>
              </template>
            </el-dropdown>
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
      v-model="dialogVisible1"
      title="设置备注"
      width="50%"
  >
    <el-form ref="form1" :model="form1" label-width="100px" class="category-form">
      <el-form-item label="备注" prop="memo" >
        <el-input v-model="form1.memo" type="textarea"></el-input>
      </el-form-item>
    </el-form>

    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogVisible1 = false">取消</el-button>
        <el-button type="primary" @click="handleMemoUpdate"
        >确认</el-button>
      </span>
    </template>
  </el-dialog>

  <el-dialog
      v-model="dialogVisible2"
      title="客户分配"
      width="50%"
  >
    <el-form ref="form2" :model="form2" label-width="100px" class="category-form" width="100%">
      <el-form-item label="分配给" prop="title">
        <el-autocomplete
            v-model="manager"
            :fetch-suggestions="handleGetAdmins"
            :trigger-on-focus="false"
            clearable
            placeholder="请输入销售姓名"
            @select="handleSelect"
        />
      </el-form-item>
    </el-form>
    <template #footer>
      <span class="dialog-footer">
        <el-button type="primary" @click="handleAssignment()">立即分配</el-button>
        <el-button type="danger" @click="handleRelease()">释放到公海</el-button>
        <el-button @click="dialogVisible2 = false">取消</el-button>
      </span>
    </template>
  </el-dialog>
</template>
<script>
	import {customers, updateCustomerMemo, releaseCustomers, assignCustomers, deletePlusCustomer} from '@/api/customers'
  import {getAdmins} from '@/api/admins'

export default {
	data () {
		return {
      levels: ['', 'A', 'B', 'C'],
      locked_status: ['全部', '未锁定', '已锁定'],
			tableData: [],
			pager: {
				page: 1,
				total: 0,
				pageSize: 30
			},
      searcher: {
        locked_by_user: 0
      },
      form1:{},
      form2:{},
      dialogVisible1: false,
      dialogVisible2: false,
      managerFull: '',
      manager: '',
      selectedItems: [],
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
    handleSelectionChange(val) {
      this.selectedItems=[]
      for(let i in val){
        this.selectedItems.push(val[i].id)
      }

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
    handleAssignmentShow() {
      if(this.selectedItems.length<=0){
        this.$message.warning('请先选择需要分配的客户')
        return false
      }
      this.dialogVisible2 = true
    },
    handleAssignment() {
      if(this.managerFull['value'] != this.manager) {
        this.$message.warning('请选择负责人而非填写负责人名称')
        return false
      }
      assignCustomers({ids: this.selectedItems, salerId: this.managerFull['key']})
          .then(res=>{
            this.getList()
            this.dialogVisible2 = false
            this.$message({
              showClose: true,
              type: 'success',
              message: '释放成功!'
            })
          })
    },
    handleRelease() {
      this.$confirm('此操作将释放选中的客户到公海，是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        releaseCustomers({ids: this.selectedItems})
            .then(res=>{
              this.getList()
              this.dialogVisible2 = false
              this.$message({
                showClose: true,
                type: 'success',
                message: '释放成功!'
              })
            })

      })
    },
    handleDelete(id) {
      this.$confirm('此操作将删除选中的客户，是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deletePlusCustomer({id: id})
            .then(res=>{
              this.getList()
              this.$message({
                showClose: true,
                type: 'success',
                message: '删除成功!'
              })
            })

      })
    },
    handleMemoUpdate(){
      updateCustomerMemo(this.form1).then((res)=>{
        for(let i in this.tableData) {
          if(this.tableData[i].id == this.form1.id){
            this.tableData[i].memo = this.form1.memo
            break;
          }
        }
        this.dialogVisible1 = false
        this.$message.success({
          message: '备注设置成功',
          type: 'success',
          showClose: true,
        })
      })
    },
    handleMemo(id, memo) {
      this.form1.memo = memo
      this.form1.id = id
      this.dialogVisible1 = true
    },
    handleView(id) {
      let routeData = this.$router.resolve({
        path: '/customer/viewPlus/' + id,
      })
      window.open(routeData.href, '_blank');
    },
		getList () {
			let data = {...this.searcher, ...this.pager}
      customers(data)
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
  .locked_by_user{
    color: #ff0000;
  }
</style>
