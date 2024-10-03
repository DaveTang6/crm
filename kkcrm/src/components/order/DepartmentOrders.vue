<template>
	<MainTitle  data-sub-title="部门订单">订单管理</MainTitle>
  <OperationContainer
      :recordNum="pager.total"
      @onShowSearcher="handleShowSearcher"
  >
    <el-button type="primary"  size="mini"
               @click="exportExcel()"
    >
      导出订单
    </el-button>
  </OperationContainer>
	<MainBody>
    <SearcherContainer v-show="showSearcher">
      <el-form :inline="true" :model="searcher" class="form-searcher" size="mini">
        <el-form-item label="订单号">
          <el-input v-model="searcher.order_no" placeholder="请输入订单号"></el-input>
        </el-form-item>
        <el-form-item label="微信号">
          <el-input v-model="searcher.wechat" placeholder="请输入微信号"></el-input>
        </el-form-item>
        <el-form-item label="客户姓名">
          <el-input v-model="searcher.customer_name" placeholder="请输入姓名"></el-input>
        </el-form-item>
        <el-form-item label="销售姓名">
          <el-input v-model="searcher.saler_name" placeholder="请输入姓名"></el-input>
        </el-form-item>
        <el-form-item label="订单日期" prop="order_time">
          <el-date-picker
              v-model="searcher.order_time"
              type="daterange"
              range-separator="-"
              start-placeholder="Start date"
              end-placeholder="End date"
              value-format="YYYY-MM-DD"
          />
        </el-form-item>
        <el-form-item label="审核状态" prop="check_status">
          <el-select v-model="searcher.check_status" value-key="id" placeholder="全部" >
            <el-option label="全部" value="" :key="0"></el-option>
            <el-option :label="item.title" :value="item.id" :key="item.id" v-for="item in checkStatusArr"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="产品分类" prop="product_category_id">
          <el-select v-model="searcher.product_category_id"  placeholder="全部" @change="handleGetProducts">
            <el-option label="全部" value="" :key="0"></el-option>
            <el-option :label="item.title" :value="item.id" :key="index" v-for="(item,index) in productCategories"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="产品名称" prop="product_id">
          <el-select v-model="searcher.product_id" value-key="id" placeholder="全部" >
            <el-option label="全部" value="" :key="0"></el-option>
            <el-option :label="item.title" :value="item.id" :key="item.id" v-for="item in products"></el-option>
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
            prop="order_no"
            width="50"
            label="类型"
        >
          <template #default="scope">
            <span >{{orderTypeArr[scope.row.order_type]}}</span>
          </template>
        </el-table-column>
        <el-table-column
            prop="order_no"
            label="订单号"
            width="150"
        >
          <template #default="scope">
            <span @click="handleCopyNo(scope.row.order_no)" class="kk-blue">{{scope.row.order_no}}</span>
          </template>
        </el-table-column>
        <el-table-column
            prop="customer_wechat"
            label="微信号"
            width="120"
        >
        </el-table-column>
        <el-table-column
            prop="customer_name"
            label="客户名"
            width="100"
        >
        </el-table-column>
        <el-table-column
            prop="product_name"
            label="订单产品"
        >
          <template #default="scope">
            <span @click="handleProductShow(scope.row)" class="kk-blue">[{{scope.row.product_category}}]{{scope.row.product_name}}</span>
          </template>
        </el-table-column>
        <el-table-column
            prop="price"
            label="价格"
            width="80"
        >
        </el-table-column>

        <el-table-column
            prop="num"
            label="数量"
            width="80"
        >
        </el-table-column>
        <el-table-column
            prop="order_time"
            label="下单时间"
            width="100"
        >
        </el-table-column>
        <el-table-column
            prop="saler_name"
            label="当前销售"
            width="100"
        >
        </el-table-column>
        <el-table-column
            prop="check_status"
            label="审核状态"
            width="80"
        >
          <template #default="scope">
            <span @click="handleShowCheckStatus(scope.row)" class="kk-pointer">
              <i class="kk-red" v-if="scope.row.check_status==-1">未通过</i>
              <i class="kk-green" v-else-if="scope.row.check_status==1">初审通过</i>
              <i class="kk-blue" v-else-if="scope.row.check_status==2">复审通过</i>
              <i  v-else>待审核</i>
            </span>
          </template>
        </el-table-column>
        <el-table-column
            prop="pay_status"
            label="财务"
            width="50"
        >
          <template #default="scope">
            <i class="el-icon-success kk-green" v-if="scope.row.pay_status==1"></i>
            <i class="el-icon-info kk-red" v-else></i>
          </template>
        </el-table-column>
        <el-table-column
            prop="order_no"
            width="80"
            label="是否结算"
        >
          <template #default="scope">
            <span >{{settleStatusArr[scope.row.settle_status]}}</span>
          </template>
        </el-table-column>
        <el-table-column
            prop="settle_time"
            width="100"
            label="结算时间"
        >
        </el-table-column>
				<el-table-column
					label="操作"
          width="100"
        >
          <template #default="scope">
            <el-button
                size="mini"
                type="default"
                @click="handleView(scope.row.id)"
            >查看</el-button>
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
    <input type="text" style="opacity: 0;position: absolute;" v-model="copyText" ref="copyInput">
	</MainBody>
  <el-dialog
      v-model="checkedDialogVisible"
      title="订单审核"
      width="70%"
  >

    <div>
      <el-table
          :data="logData"
          stripe
          width="100%"
      >

        <el-table-column
            type="index"
            width="55"
            label="序号"
        >
        </el-table-column>
        <el-table-column
            prop="act_user"
            label="审核人"
            width="100"
        >
        </el-table-column>
        <el-table-column
            prop="add_time"
            label="审核时间"
            width="120"
        >
        </el-table-column>
        <el-table-column
            prop="memo"
            label="审核说明"
        >
        </el-table-column>
      </el-table>
    </div>
  </el-dialog>
  <order-products :show="productDialogShow"
                  :order-id="selectedOrderId"
                  :order-no="selectedOrderNo"
                  role="department"
                  @closeDialog="productDialogShow = false"
  ></order-products>
</template>
<script>
	import {orderDepartments, orderCheckLogs} from '@/api/orders'
  import {getKeyPath, getProducts} from "@/api/products"
  import OrderProducts from "./OrderProducts.vue"
  import siteConfig from '@/config/config.js'
export default {
  components:{
    OrderProducts
  },
	data () {
		return {
      productCategories: [],
      products: [],
			tableData: [],
			pager: {
				page: 1,
				total: 0,
				pageSize: 30
			},
      checkStatusArr: [
        {id: -1, title: '未通过'},
        {id: 0, title: '待审核'},
        {id: 1, title: '初审通过'},
        {id: 2, title: '复审通过'},

      ],
      payStatusArr: siteConfig('payStatus'),
      settleStatusArr: siteConfig('settleStatus'),
      orderTypeArr: siteConfig('orderType'),
      checkedDialogVisible: false,
      logData: [],
      searcher: {},
      copyText:'',
      productDialogShow: false,
      selectedOrderId: 0,
      selectedOrderNo: '',
      showSearcher: false,

		}
	},
	mounted() {
		this.getList()
    this.handleGetProductCategories()
	},
	methods: {
    exportExcel() {
      let query = 'JWT=' + this.$store.getters.jwt;
      for(let i in this.searcher) {
        query += '&' + i + '=' + this.searcher[i]
      }
      window.open(siteConfig('apiURL') + 'orders/getExcel/director/?' + query)
    },
    handleProductShow(r) {
      this.selectedOrderId = r.id
      this.selectedOrderNo = r.order_no
      this.productDialogShow = true
    },
    handleShowSearcher(v){
      this.showSearcher = v
    },
    handleShowCheckStatus(r) {
      orderCheckLogs({'order_id' : r.id})
          .then(res => {
            this.logData = res.memo.list
          })
      this.checkedDialogVisible = true
    },
    handleCopyNo(order_no) {
      this.copyText = order_no
      this.$refs.copyInput.select()
      document.execCommand("copy")
      this.$message({
        showClose: true,
        type: 'success',
        message: '订单号复制成功!'
      });
    },
    handleView(id) {
      let routeData = this.$router.resolve({
        path: '/order/view/' + id,
      })
      window.open(routeData.href, '_blank');
    },
    handleGetProducts(category_id) {

      let searcher = {'category_id': category_id}
      let pager = {'pageSize': 100}
      let data = {...searcher, ...pager}
      getProducts(data, 'limit')
          .then(res => {
            this.products = res.memo.list
          })

    },
    handleGetProductCategories () {
      getKeyPath()
          .then(res => {
            this.productCategories = res.memo.list
          })
    },
		getList () {
			let data = {...this.searcher, ...this.pager}
      orderDepartments(data)
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
  .kk-pointer{
    cursor: pointer;
  }
</style>
