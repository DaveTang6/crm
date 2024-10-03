<template>
	<MainTitle data-sub-title="客户信息" >客户管理</MainTitle>
  <el-alert title="未找到对应的客户信息" type="warning" :closable="false" v-if="errorData" />
  <MainBody v-else>
      <el-descriptions
          class="descriptions"
          title="基本信息"
          :column="2"
          border
      >
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">微信</div>
          </template>
          <span>{{ form.wechat }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">姓名</div>
          </template>
          <span>{{ form.truename }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">电话</div>
          </template>
          <span>{{ form.mobile }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">性别</div>
          </template>
          <span>{{ genders[form.gender] }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">所在地区</div>
          </template>
          <span>{{ form.area }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">公司名</div>
          </template>
          <span>{{ form.company }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">部门</div>
          </template>
          <span>{{ form.department }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">品牌</div>
          </template>
          <span>{{ form.brand }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">备注</div>
          </template>
          <span>{{ form.memo }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">白鹿会员</div>
          </template>
          <span>{{ blclubs[form.is_bl_member] }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">当前销售</div>
          </template>
          <span>{{ form.locked_by_user ?? '无'}}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">锁定时间</div>
          </template>
          <span>{{ form.locked_time }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">锁定过期时间</div>
          </template>
          <span>{{ form.expired_time }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">最后购买时间</div>
          </template>
          <span>{{ form.last_buy ?? '无'}}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">购买次数</div>
          </template>
          <span>{{ form.order_count}}</span>
        </el-descriptions-item>

        <el-descriptions-item>
          <template #label>
            <div class="cell-item">加入日期</div>
          </template>
          <span>{{ form.add_time}}</span>
        </el-descriptions-item>
      </el-descriptions>

      <div class="order-table">
        <h3>相关订单</h3>
        <el-table
            :data="tableData"
            stripe
            height="500"
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
              <span @click="handleCopyNo(scope.row.order_no)" class="kk-blue">{{scope.row.order_no}}</span>
            </template>
          </el-table-column>
          <el-table-column
              prop="product_name"
              label="产品名"
          >
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
              prop="pay_status"
              label="财务"
              width="100"
          >
            <template #default="scope">
              <i class="el-icon-success kk-green" v-if="scope.row.pay_status==1"></i>
              <i class="el-icon-info kk-red" v-else></i>
            </template>
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
      </div>
  </MainBody>
</template>
<script>
import { viewPlusCustomer} from "@/api/customers"
import { orders} from "@/api/orders"
export default {
	data () {
		return {
      genders: {2:'未知', 0: '女', 1: '男'},
      blclubs: ['否', '是'],
			form: {},
      tableData: [],
      pager: {
        page: 1,
        total: 0,
        pageSize: 30
      },
      searcher: {
        wechat: ''
      },
      copyText:'',
      errorData: false
		}
	},
  mounted() {
    this.getView()
  },
	methods: {
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
    getView () {
      viewPlusCustomer({id: this.$route.params.id})
          .then(res => {
            this.form = res.memo
            this.searcher.wechat = this.form.wechat
            this.getList()
          }, error=> {
            this.errorData = true
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
	.category-form {
		width: 500px;
		padding: 10px;
	}
  .form-knowledge{
    margin-top: 5px;
  }
  .el-icon-hot-water{
    color: #409eff;
    font-size: 16px;
    padding-right: 10px;
  }

  .el-descriptions {
    margin-top: 20px;
  }
  .cell-item {
    display: flex;
    align-items: center;
  }
  .descriptions {
    padding: 20px;
  }
  .order-table{
    padding: 20px;
    h3{
      margin-bottom: 20px;
    }
  }
  .locked_by_user, .kk-red{
    color: #ff0000;
  }
  .kk-blue{
    color: #409eff;
    cursor: pointer;
  }
  .kk-green{
    color: #42B983
  }
</style>
