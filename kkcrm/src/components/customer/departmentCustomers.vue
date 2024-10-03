<template>
	<MainTitle  data-sub-title="部门客户">客户管理</MainTitle>
  <OperationContainer
      :recordNum="pager.total"
      @onShowSearcher="handleShowSearcher"
  ></OperationContainer>
	<MainBody>
    <SearcherContainer v-show="showSearcher">
      <el-form :inline="true" :model="searcher" class="form-searcher" size="mini">
        <el-form-item label="微信号">
          <el-input v-model="searcher.wechat" placeholder="请输入微信号"></el-input>
        </el-form-item>
        <el-form-item label="姓名">
          <el-input v-model="searcher.truename" placeholder="请输入姓名"></el-input>
        </el-form-item>
        <el-form-item label="销售">
          <el-input v-model="searcher.saler" placeholder="请输入销售姓名"></el-input>
        </el-form-item>
        <el-form-item label="等级" prop="level">
          <el-select v-model="searcher.level"  placeholder="全部等级">
            <el-option :label="item" :value="item" :key="index" v-for="(item,index) in levels"></el-option>
          </el-select>
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
      >

				<el-table-column
				  type="index"
				  width="55"
          label="序号"
        >
				</el-table-column>
        <el-table-column
            prop="saler"
            label="销售"
            width="120"
        >
        </el-table-column>
        <el-table-column
            label="锁定"
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
            prop="level"
            label="评级"
            width="80"
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
                  <el-dropdown-item @click="handleTimeline(scope.row.id, scope.row.wechat)">拜访记录</el-dropdown-item>
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
</template>
<script>
	import {getDepartmentCustomers} from '@/api/customers'


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
    handleTimeline(id, wechat) {
      let routeData = this.$router.resolve({
        path: '/customer/timeline/' + id + '/' + wechat,
      })
      window.open(routeData.href, '_blank');
    },
    handleView(id) {
      let routeData = this.$router.resolve({
        path: '/customer/view/' + id,
      })
      window.open(routeData.href, '_blank');
    },
		getList () {
			let data = {...this.searcher, ...this.pager}
      getDepartmentCustomers(data)
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
