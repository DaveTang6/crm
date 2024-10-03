<template>
	<MainTitle data-sub-title="全部订单">订单管理</MainTitle>
  <OperationContainer
      :recordNum="pager.total"
      @onShowSearcher="handleShowSearcher"
  >
    <el-button type="primary"  size="mini"
               @click="handleShowCheckStatus()"
               v-if="returnCheckMenu('orderCheckStatus1') || returnCheckMenu('orderCheckStatus2')"
    >
      批量审批
    </el-button>
    <el-dropdown style="margin-left: 10px">
      <el-button type="info"  size="mini">
        更多操作 <i class="el-icon el-icon-arrow-down"></i>
      </el-button>
      <template #dropdown>
        <el-dropdown-menu>
          <el-dropdown-item @click="exportExcel()" >导出查询结果</el-dropdown-item>
        </el-dropdown-menu>
      </template>
    </el-dropdown>
  </OperationContainer>
	<MainBody>
    <SearcherContainer v-show="showSearcher">
      <el-form :inline="true" :model="searcher" class="form-searcher" size="mini">
        <el-form-item label="订单号">
          <el-input v-model="searcher.order_no" placeholder="请输入订单号" >
          </el-input>
        </el-form-item>
        <el-form-item label="微信号">
          <el-input v-model="searcher.wechat" placeholder="请输入微信号" ></el-input>
        </el-form-item>
        <el-form-item label="客户姓名">
          <el-input v-model="searcher.customer_name" placeholder="请输入姓名" ></el-input>
        </el-form-item>
        <el-form-item label="销售姓名">
          <el-input v-model="searcher.saler_name" placeholder="请输入姓名" ></el-input>
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

        <el-form-item label="产品分类" prop="product_category_id">
          <el-select v-model="searcher.product_category_id"  placeholder="全部" @change="handleGetProducts" >
            <el-option label="全部" value="" :key="0"></el-option>
            <el-option :label="item.title" :value="item.id" :key="index" v-for="(item,index) in productCategories"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="审核状态" prop="check_status">
          <el-select v-model="searcher.check_status" value-key="id" placeholder="全部" >
            <el-option label="全部" value="" :key="0"></el-option>
            <el-option :label="item.title" :value="item.id" :key="item.id" v-for="item in checkStatusArr"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="支付确认" prop="check_status">
          <el-select v-model="searcher.pay_status" value-key="id" placeholder="全部" >
            <el-option label="全部" value="" :key="0"></el-option>
            <el-option :label="item" :value="index" :key="item.id" v-for="(item,index) in payStatusArr"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="产品名称" prop="product_id">
          <el-select v-model="searcher.product_id" value-key="id" placeholder="全部" >
            <el-option label="全部" value="" :key="0"></el-option>
            <el-option :label="item.title" :value="item.id" :key="item.id" v-for="item in products"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="订单类型" prop="order_type">
          <el-select v-model="searcher.order_type" value-key="id" placeholder="全部" >
            <el-option label="全部" value="" ></el-option>
            <el-option :label="item" :value="index" :key="index" v-for="(item,index) in orderTypeArr"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="是否结算" prop="settle_status">
          <el-select v-model="searcher.settle_status" value-key="id" placeholder="全部" >
            <el-option label="全部" value="" ></el-option>
            <el-option :label="item" :value="index" :key="index" v-for="(item,index) in settleStatusArr"></el-option>
          </el-select>
        </el-form-item>

        <el-form-item label="结算时间" prop="settle_time">
          <el-date-picker
              v-model="searcher.settle_time"
              type="daterange"
              range-separator="-"
              start-placeholder="Start date"
              end-placeholder="End date"
              value-format="YYYY-MM-DD"
          />
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="getList()" style="margin-right: 10px">查询</el-button>
        </el-form-item>
      </el-form>
    </SearcherContainer>
		<MainContainer>
			<el-table
				:data="tableData"
				stripe
        height="100%"
        width="100%"
        :default-sort="{ prop: 'order_time', order: 'descending' }"
        @selection-change="handleSelectionChange"
        @sort-change="handleSortChange"
      >
        <el-table-column
            type="selection"
            width="55">
        </el-table-column>
				<el-table-column
				  type="index"
				  width="50"
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
            prop="cost"
            label="成本"
            width="80"
        >
          <template #default="scope">
            <span @click="handleCostShow(scope.row)" class="kk-blue">{{scope.row.cost}}</span>
          </template>
        </el-table-column>
        <el-table-column
            prop="order_time"
            label="下单时间"
            width="100"
            sortable="custom"
        >
        </el-table-column>
        <el-table-column
            prop="saler_name"
            label="销售"
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
            <span @click="handleShowFinance(scope.row)" class="kk-pointer">
              <i class="el-icon-success kk-green" v-if="scope.row.pay_status==1"></i>
              <i class="el-icon-info kk-red" v-else></i>
            </span>
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
      v-model="dialogVisible"
      title="财务设置"
      width="50%"
  >

    <el-form ref="form" :model="form" :rules="rules" label-width="100px" class="category-form">
      <el-form-item label="支付凭证">
        <span v-if="!url">无</span>
        <el-image
            v-else
            style="width: 100px; height: 100px"
            :src="url"
            :preview-src-list="srcList"
            :initial-index="4"
            fit="cover"
        />
      </el-form-item>
      <el-form-item label="是否付款" prop="pay_status">
        <el-switch v-model="form.pay_status" :active-value="1" :inactive-value="0"></el-switch>
      </el-form-item>
      <el-form-item label="付款时间" prop="pay_time" v-show="form.pay_status == 1">
        <el-date-picker
            v-model="form.pay_time"
            value-format="YYYY-MM-DD"
            type="date"
            placeholder="Pick a day"
        />
      </el-form-item>

      <el-form-item label="有无退款" prop="refund_status">
        <el-switch v-model="form.refund_status" :active-value="1" :inactive-value="0"></el-switch>
      </el-form-item>

      <el-form-item label="退款金额" prop="refund" v-show="form.refund_status == 1">
        <el-input-number v-model="form.refund" :precision="2" :step="1" placeholder="单位：元"></el-input-number>
      </el-form-item>

      <el-form-item label="是否结算" prop="settle_status">
        <el-switch v-model="form.settle_status" :active-value="1" :inactive-value="0"></el-switch>
      </el-form-item>

      <el-form-item label="结算时间" prop="settle_time" v-show="form.settle_status == 1">
        <el-date-picker
            v-model="form.settle_time"
            value-format="YYYY-MM-DD"
            type="date"
            placeholder="Pick a day"
        />
      </el-form-item>

    </el-form>

    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="handleFinanceUpdate"
        >确认</el-button>
      </span>
    </template>
  </el-dialog>

  <el-dialog
      v-model="costDialogShow"
      title="成本设置"
      width="50%"
  >
    <el-form ref="costForm" :model="costForm" :rules="costRules" label-width="100px" class="category-form">
      <el-form-item label="成本" prop="cost">
        <el-input-number v-model="costForm.cost" :precision="2" :step="1" placeholder="单位：元"></el-input-number>
      </el-form-item>
    </el-form>

    <template #footer>
      <span class="dialog-footer">
        <el-button @click="costDialogShow = false">取消</el-button>
        <el-button type="primary" @click="handleCostUpdate"
        >确认</el-button>
      </span>
    </template>
  </el-dialog>

  <el-dialog
      v-model="checkedDialogVisible"
      title="订单审核"
      width="70%"
  >

    <div v-if="logData.length>0">
      <h3>审核结果</h3>
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
    <h3 style="padding: 10px 0">审核说明</h3>
    <el-form ref="checkedForm" :model="checkedForm"  label-width="100px" class="category-form">
        <el-input v-model="checkedForm.memo" type="textarea"></el-input>
    </el-form>

    <template #footer>
      <span class="dialog-footer">
        <el-button @click="checkedDialogVisible = false">关闭</el-button>
        <el-button type="danger" @click="handleCheckedUpdate(-1)"
                   v-if="(checkStatus<=1 && returnCheckMenu('orderCheckStatus1')) || (checkStatus<=2 && returnCheckMenu('orderCheckStatus2'))">
          审核不通过</el-button>
        <el-button type="warning" @click="handleCheckedUpdate(1)" v-if="checkStatus<=1 && returnCheckMenu('orderCheckStatus1')">通过初审</el-button>
        <el-button type="primary" @click="handleCheckedUpdate(2)" v-if="returnCheckMenu('orderCheckStatus2')">通过复审</el-button>
      </span>
    </template>
  </el-dialog>
  <order-products :show="productDialogShow"
                  :order-id="selectedOrderId"
                  :order-no="selectedOrderNo"
                  role="admin"
                  @closeDialog="productDialogShow = false"
  ></order-products>
</template>
<script>
	import {orders, orderFinance, orderCost, orderCheck, orderCheckLogs} from '@/api/orders'
  import {getKeyPath, getProducts} from "@/api/products"
  import OrderProducts from "./OrderProducts.vue"
  import siteConfig from '@/config/config.js'
  import checkMenu from "@/utils/checkMenu"

export default {
	  components:{
	    OrderProducts
    },
	data () {
		return {
      menu: [{
        title: '列表',
        path: '/admin/index'
      },{
        title: '新增',
        path: '/admin/add/'
      }],
		  url: '',
      srcList: [],
      cnActs: {
        'update': '修改', 'create': '新增', 'delete': '删除', 'pay': '支付确认', 'refund': '退款', 'cost': '成本修改', 'check': '审核'
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
      logData: [],
      productCategories: [],
      products: [],
			tableData: [],
			pager: {
				page: 1,
				total: 0,
				pageSize: 30
			},
      searcher: {},
      copyText:'',
      dialogVisible: false,
      checkedDialogVisible: false,
      checkStatus: 0,
      checkedForm: {},

      form: {},
      rules: {
        refund: [
          {type: 'number', message: '退款金额必须为整数'}
        ],
      },
      costForm: {},
      costRules: {
        cost: [
          {type: 'number', message: '成本必须为整数'}
        ],
      },
      costDialogShow: false,
      productDialogShow: false,
      selectedOrderId: 0,
      selectedOrderNo: '',
      showSearcher: false,
      selectedItems: [],
		}
	},
	mounted() {
		this.getList()
    this.handleGetProductCategories()
	},
	methods: {
    handleSortChange(val){

      this.searcher['sortSetting'] = {
        'field': val.prop,
        'order': val.order
      }
      this.getList();
      console.log( this.searcher['sortSetting'])
    },
    handleSelectionChange(val) {
      this.selectedItems=[]
      for(let i in val){
        this.selectedItems.push(val[i].id)
      }

    },
    handleShowSearcher(v){
      this.showSearcher = v
    },
    handleProductShow(r) {
      this.selectedOrderId = r.id
      this.selectedOrderNo = r.order_no
      this.productDialogShow = true
    },
    exportExcel() {
      let query = 'JWT=' + this.$store.getters.jwt;
      for(let i in this.searcher) {
        query += '&' + i + '=' + this.searcher[i]
      }
      window.open(siteConfig('apiURL') + 'orders/getExcel?' + query)
    },
    handleCheckedUpdate(status) {

      orderCheck(status, this.checkedForm).then((res)=>{
        for(let i in this.tableData) {
          if(this.checkedForm.id .indexOf(this.tableData[i].id) >= 0 ){
            this.tableData[i].check_status = status
          }
        }
        this.$message.success({
          message: '订单状态已更新',
          type: 'success',
          showClose: true,
        })
      })
      this.checkedDialogVisible = false
    },
	  returnCheckMenu(m) {
	    return checkMenu(m)
    },
    handleShowCheckStatus(r = 'multi') {
      if(r === 'multi'){
        let ids = this.selectedItems
        if(ids.length<=0){
          this.$message.warning('请先选择需要设置的项')
          return false
        }
        this.logData = []
        this.checkedForm = {
          id: ids,
          memo: ''
        }
        this.checkStatus = 0
      } else {
        orderCheckLogs({'order_id' : r.id})
            .then(res => {
              this.logData = res.memo.list
            })

        this.checkedForm = {
          id: [r.id],
          memo: ''
        }
        this.checkStatus = r.check_status
      }


      this.checkedDialogVisible = true
    },
    handleCostUpdate() {
      this.$refs['costForm'].validate((valid) => {
        if (valid) {
          orderCost(this.costForm).then((res)=>{
            for(let i in this.tableData) {
              if(this.tableData[i].id == this.costForm.id){
                this.tableData[i].cost = this.costForm.cost
                break;
              }

            }
            this.costDialogShow = false
            this.$message.success({
              message: '成本已更新',
              type: 'success',
              showClose: true,
            })
          })

        } else {
          return false;
        }
      });
    },
	  handleCostShow(r) {
      if(!checkMenu('orderUpdateCost')) {
        return false
      }
      if(r.pay_status === 1) {
        this.$message.success({
          message: '该订单已被财务确认，无法修改',
          type: 'warning',
          showClose: true,
        })
        return false
      }
      this.costForm = {
        id: r.id,
        cost: r.cost
      }
      this.costDialogShow = true
    },
    handleFinanceUpdate() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          orderFinance(this.form).then((res)=>{
            for(let i in this.tableData) {
              if(this.tableData[i].id == this.form.id){
                this.tableData[i].pay_status = this.form.pay_status
                this.tableData[i].refund_status = this.form.refund_status
                this.tableData[i].pay_time = this.form.pay_time
                this.tableData[i].refund = this.form.refund
                this.tableData[i].settle_status = this.form.settle_status
                this.tableData[i].settle_time = this.form.settle_time
                break;
              }

            }
            this.$message.success({
              message: '财务状态已更新',
              type: 'success',
              showClose: true,
            })
          })

        } else {
          return false;
        }
      });
    },
    handleShowFinance(r) {
      if(!checkMenu('orderUpdateFinance')) {
        return false
      }
      this.form = {
        id: r.id,
        pay_status: r.pay_status,
        refund_status: r.refund_status,
        pay_time: r.pay_time,
        refund: r.refund,
        settle_status: r.settle_status,
        settle_time: r.settle_time
      }
      if(!!r.pay_proof_url){
        this.url= siteConfig('filePath') + r.pay_proof_url[0]
        this.srcList= r.pay_proof_url.map((currentValue)=>{
          return  siteConfig('filePath') + currentValue
        })
      } else {
        this.url = null
      }
      this.dialogVisible = true
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
        path: '/order/viewPlus/' + id,
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
      orders(data)
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
  .el-form-item{margin-bottom: 0}
</style>
