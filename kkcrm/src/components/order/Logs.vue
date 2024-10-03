<template>
	<MainTitle  data-sub-title="订单日志">订单管理</MainTitle>
  <OperationContainer
      :recordNum="pager.total"
      @onShowSearcher="handleShowSearcher"
  >
  </OperationContainer>
	<MainBody>
    <SearcherContainer v-show="showSearcher">
      <el-form :inline="true" :model="searcher" class="form-searcher" size="mini">
        <el-form-item label="订单号">
          <el-input v-model="searcher.order_no" placeholder="请输入订单号"></el-input>
        </el-form-item>
        <el-form-item label="操作人">
          <el-input v-model="searcher.act_user" placeholder="请输入姓名"></el-input>
        </el-form-item>
        <el-form-item label="操作行为" prop="act">
          <el-select v-model="searcher.act"  placeholder="全部">
            <el-option label="全部" value="" :key="0"></el-option>
            <el-option :label="cnActs[item]" :value="item" :key="index" v-for="(item,index) in acts"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="操作时间" prop="add_time">
          <el-date-picker
              v-model="searcher.add_time"
              type="daterange"
              range-separator="-"
              start-placeholder="Start date"
              end-placeholder="End date"
              value-format="YYYY-MM-DD"
          />
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
            prop="order_no"
            label="订单号"
            width="150"
        >
          <template #default="scope">
            <span @click="handleView(scope.row.order_id)" class="kk-blue">{{scope.row.order_no}}</span>
          </template>
        </el-table-column>
        <el-table-column
            prop="act"
            label="操作行为"
            width="120"
        >
          <template #default="scope">
            <span >{{cnActs[scope.row.act]}}</span>
          </template>
        </el-table-column>
        <el-table-column
            prop="act_user"
            label="操作人"
            width="120"
        >
        </el-table-column>
        <el-table-column
            prop="add_time"
            label="操作时间"
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
	import {orderLogs} from '@/api/orders'

export default {
	data () {
		return {
      acts: ['update', 'create', 'delete', 'pay', 'refund', 'cost', 'check'],
      cnActs: {
        'update': '修改', 'create': '新增', 'delete': '删除', 'pay': '支付确认', 'refund': '退款', 'cost': '成本修改', 'check': '审核'
      },
			tableData: [],
			pager: {
				page: 1,
				total: 0,
				pageSize: 30
			},
      searcher: {},
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
    handleView(id) {
      let routeData = this.$router.resolve({
        path: '/order/viewPlus/' + id,
      })
      window.open(routeData.href, '_blank');
    },
		getList () {
			let data = {...this.searcher, ...this.pager}
      orderLogs(data)
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
	.kk-red, .el-icon-lock{
		color: #ff0000;
	}

	.kk-green, .el-icon-unlock{
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
  .kk-blue{
    color: #409eff;
    cursor: pointer;
  }
</style>
