<template>
  <el-dialog
      v-model="show"
      :title="orderNo + ' 订单产品'"
      width="70%"
  >
    <el-table
        :data="tableData"
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
          prop="category_name"
          label="产品类别"
          width="120"
      ></el-table-column>
      <el-table-column
          prop="product_name"
          label="产品名"
      ></el-table-column>
      <el-table-column
          prop="num"
          label="数量"
          width="100"
      ></el-table-column>
      <el-table-column
          prop="total"
          label="总价(元)"
          width="120"
      ></el-table-column>
      <el-table-column
          v-if="role==='admin'"
          prop="cost"
          label="成本(元)"
          width="120"
      ></el-table-column>
    </el-table>
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

    <template #footer>
      <span class="dialog-footer">
        <el-button @click="handleCloseDialog">取消</el-button>
        <el-button type="primary" @click="handleCloseDialog"
        >确认</el-button>
      </span>
    </template>
  </el-dialog>

</template>
<script>
	import {orderProducts} from '@/api/orders'

export default {
	  props:{
      show: {
        type: Boolean
      },
	    orderId: {
	      type: Number
      },
      orderNo: {
	      type: [String, Number]
      },
      role: {
        type: String
      }
    },
  emits:['closeDialog'],
	data () {
		return {
			tableData: [],
			pager: {
				page: 1,
				total: 0,
				pageSize: 30
			},
		}
	},
	mounted() {
		this.getList()
	},
  watch:{
	    orderId(){
	      this.getList()
      }
  },
	methods: {
    handleCloseDialog() {
      this.$emit('closeDialog')
    },
		getList () {
      if(!this.orderId) {
        return false
      }
			let data = {orderId: this.orderId, ...this.pager}
      orderProducts(data, this.role)
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
