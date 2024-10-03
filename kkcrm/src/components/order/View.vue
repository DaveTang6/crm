<template>
  <MainTitle data-sub-title="订单信息" >订单管理</MainTitle>
  <el-alert title="订单不存在或已被删除！" type="warning" :closable="false" v-if="form == false" />
  <MainBody  v-else-if="!loading">
      <el-descriptions
          class="descriptions"
          :title="'订单 ' + form.order_no"
          :column="2"
          border
      >
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">订单类型</div>
          </template>
          <span class="kk-red">{{ orderType[form.order_type] }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">客户微信</div>
          </template>
          <span>{{ form.customer_wechat }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">客户名</div>
          </template>
          <span>{{ form.customer_name }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">商品分类</div>
          </template>
          <span>{{ form.product_category }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">商品</div>
          </template>
          <span @click="handleProductShow(form)" class="kk-blue kk-pointer">{{ form.product_name }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">销售价格(元)</div>
          </template>
          <span>{{ form.price }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">销售数量</div>
          </template>
          <span>{{ form.num }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">订单时间</div>
          </template>
          <span>{{ form.order_time }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">付款时间</div>
          </template>
          <span>{{ form.pay_time?? '无' }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">合同</div>
          </template>
          <span v-if="!form.contract_url">无</span>
          <span v-else class="url-link" @click="gotoURL(form.contract_url)">查看</span>

        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">支付凭证</div>
          </template>
          <span v-if="!form.pay_proof_url">无</span>
          <el-image
              v-else
              style="width: 80px; height: 80px"
              :src="form.pay_proof_url[0]"
              :preview-src-list="form.pay_proof_url"
              fit="cover"
          />
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">订单备注</div>
          </template>
          <span>{{ form.memo }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">销售人</div>
          </template>
          <span>{{ form.saler_name}}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">是否退款</div>
          </template>
          <span>{{ form.refund_status?? '无'}}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">退款金额(元)</div>
          </template>
          <span>{{ form.refund ?? '无'}}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">支付确认</div>
          </template>
          <span>{{  form.pay_status == 0? '未确认' : '已确认'}}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">订单添加时间</div>
          </template>
          <span>{{  form.add_time}}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">是否结算</div>
          </template>
          <span>{{  form.settle_status == 0? '未结算' : '已结算'}}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">结算时间</div>
          </template>
          <span>{{  form.settle_time}}</span>
        </el-descriptions-item>
      </el-descriptions>
  </MainBody>
  <order-products :show="productDialogShow"
                  :order-id="selectedOrderId"
                  :order-no="selectedOrderNo"
                  role="department"
                  @closeDialog="productDialogShow = false"
  ></order-products>
</template>
<script>
	import {  orderView} from '@/api/orders'
  import OrderProducts from "./OrderProducts.vue"
	import siteConfig from '@/config/config.js'
export default {
  components:{
    OrderProducts
  },
	data () {
		return {
      loading: true,
			form: {},
      selectedOrderId: 0,
      selectedOrderNo: '',
      showSearcher: false,
      productDialogShow: false,
      orderType: siteConfig('orderType')
		}
	},
  mounted() {
    this.getView()
  },
	methods: {
    handleProductShow(r) {
      this.selectedOrderId = r.id
      this.selectedOrderNo = r.order_no
      this.productDialogShow = true
    },
    gotoURL(url) {
      window.open(url)
    },
		getUpUrl() {
			return siteConfig('updateURL')
		},
		goBack(){
			this.$router.back()
		},
    getView () {
      orderView({id: this.$route.params.id})
          .then(res => {
              this.loading = false
              this.form = res.memo
              this.form.contract_url = !this.form.contract_url? null : siteConfig('filePath')  + this.form.contract_url
              this.form.pay_proof_url = !this.form.pay_proof_url? null : this.form.pay_proof_url.map((currentValue)=>{
                  return  siteConfig('filePath') + currentValue
              })
          }, err => {
            this.form = false
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
  .url-link{
    color: #409eff;
    cursor: pointer;
  }
  .descriptions {
    padding: 20px;
  }
</style>
