<template>
	<MainTitle data-sub-title="修改" :data-back="true" >订单管理</MainTitle>
	<MainBody>
			  <el-form ref="form" :model="form" :rules="rules" label-width="100px" class="category-form">
				<el-form-item label="客户微信号" prop="customer_wechat">
					{{form.customer_wechat}}
				</el-form-item>
          <el-form-item label="订单类型" prop="order_type">
            <el-select v-model.number="form.order_type" placeholder="请选择分类" >
              <el-option :label="item" :value="parseInt(index)" :key="index" v-for="(item,index) in orderType"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="订单产品" prop="order_products">
            <div class="product-container" v-for="(item, index) in form.order_products">
              <el-select v-model="form.order_products[index].category_id" placeholder="请选择产品分类" @change="handleGetProducts(index)" size="mini" class="item">
                <el-option :label="item.title" :value="item.id" :key="item.id" v-for="item in productCategories"></el-option>
              </el-select>
              <el-select v-model="form.order_products[index].product_id" value-key="id" placeholder="请先选择分类后再选择产品" size="mini"  class="item">
                <el-option :label="item.title" :value="item.id" :key="item.id" v-for="item in products[index]"></el-option>
              </el-select>
              <el-input-number v-model="form.order_products[index].total" :precision="2" :step="1" placeholder="总价(元)" size="mini"  class="item"></el-input-number>
              <el-input-number v-model="form.order_products[index].num" :step="1"  size="mini" placeholder="数量"  class="item"></el-input-number >
              <el-button @click="handleDeleteProduct(index)" size="mini" type="danger" v-if="index>0">删除</el-button>
            </div>
            <div class="product-account">
              <span>总销售额：{{ total.price }} 元</span>
              <span>总数量：{{ total.num }} 件</span>
            </div>
            <el-button @click="handleAddProduct" size="mini" type="primary">新增</el-button>

          </el-form-item>

          <el-form-item label="下单时间" prop="order_time">
            <el-date-picker
                v-model="form.order_time"
                value-format="YYYY-MM-DD"
                type="date"
                placeholder="Pick a day"
            />
          </el-form-item>

				  <el-form-item label="合同文档" prop="contract_url">
					    <el-upload
						ref="fileUploader"
						:multiple="false"
						accept=".doc,.docx,.pdf"
            :file-list="contractList"
						:limit="1"
					    :action="getUpUrl()"
						:on-success="handleContractSuccess"
						:on-error="handleError"
						:before-upload="handleBeforeUploadContract"
						:on-exceed = "handleExceedContract"
						:on-remove="handleRemoveContract"
					    >

					    <el-button size="small" type="primary">点击上传</el-button>
						<template #tip>
							<div class="el-upload__tip">
								只能上传 doc/docx/pdf 文件，且不超过 10M
							</div>
						</template>
					  </el-upload>
				  </el-form-item>

          <el-form-item label="支付凭证" prop="pay_proof_url">
            <el-upload
                ref="imgUploader"
                accept=".jpg,.jpeg,.png"
                list-type="picture"
                :multiple="false"
                :file-list="proofList"
                :limit="10"
                :action="getUpUrl()"
                :on-success="handleProofSuccess"
                :on-error="handleError"
                :before-upload="handleBeforeUploadProof"
                :on-exceed = "handleExceedProof"
                :on-remove="handleRemoveProof"
            >

              <el-button size="small" type="primary">点击上传</el-button>
              <template #tip>
                <div class="el-upload__tip">
                  只能上传 jpg/png 文件，且不超过 10M, 不超过10张
                </div>
              </template>
            </el-upload>
          </el-form-item>
          <el-form-item label="结算时间" prop="settle_time">
            <el-date-picker
                v-model="form.settle_time"
                value-format="YYYY-MM-DD"
                type="date"
                placeholder="Pick a day"
            />
          </el-form-item>
          <el-form-item label="结算状态" prop="settle_status">
            <el-select v-model.number="form.settle_status" placeholder="请选择状态" >
              <el-option :label="item" :value="parseInt(index)" :key="index" v-for="(item,index) in settleStatus"></el-option>
            </el-select>
          </el-form-item>
				<el-form-item label="说明" prop="memo">
					 <el-input
					  type="textarea"
					  :rows="3"
					  placeholder="请输入说明"
					  v-model="form.memo">
					</el-input>
				</el-form-item>

				  <el-form-item>
					<el-button type="primary" @click="append()">确认修改</el-button>
					<el-button @click="goBack">取消</el-button>
				  </el-form-item>
			</el-form>
	</MainBody>
</template>
<script>
	import { orderUpdate, orderView, orderProducts} from '@/api/orders'
  import {getKeyPath, getProducts} from "@/api/products"
	import siteConfig from '@/config/config.js'
export default {
  computed:{
    total() {
      let products = this.form.order_products,
          price = 0,
          num = 0

      for(let i in this.form.order_products){
        price+=this.form.order_products[i].total ?? 0
        num+=this.form.order_products[i].num ?? 0
      }
      return {price,num}
    }
  },
	data () {
		return {
      orderType: siteConfig('orderType'),
      settleStatus: siteConfig('settleStatus'),
			productCategories: [],
      products:[],
			form: {
        order_products: []
      },
      contractList: [],
      proofList: [],
			rules: {
				customer_wechat: [
					{required: true, message: '请输入微信名'}
				],
        order_products: [
          {
            validator:(rule, value, callback)=>{
              if(!value){
                return callback(new Error('请填写订单产品'))
              }
              let pNum = /^[0-9]*(\.?[0-9]{0,2})$/
              for(let i in value) {
                for(let j in value[i]){
                  if(!pNum.test(value[i][j])){
                    return callback(new Error('请正确填写订单产品相关项'))
                  }
                }
              }
              return  callback()
            },
            trigger: 'submit',
          }
        ],
        order_time: [
          {required: true, message: '请选择下单日期'},
        ],
			}
		}
	},
  mounted() {
    this.getView()
    this.getList()
  },
	methods: {
    handleAddProduct() {
      this.form.order_products.push({
        category_id: null,
        product_id: null,
        total: null,
        num: null,
      })
    },
    handleDeleteProduct(index) {
      this.form['order_products'].splice(index, 1)
    },
    handleGetProducts(index) {
      let category_id = this.form.order_products[index].category_id
      let searcher = {'category_id': category_id}
      let pager = {'pageSize': 100}
      let data = {...searcher, ...pager}
      getProducts(data, 'limit')
          .then(res => {
            this.products[index] = res.memo.list
          })

    },
    handleRemoveContract(file, fileList) {
			this.form.contract_url=''
    },
    handleRemoveProof(file, fileList) {
      console.log(fileList)
      this.form.pay_proof_url=[]
      for(let i in fileList) {
        this.form.pay_proof_url.push(fileList[i].response.memo.fullName)
      }
    },
		handleExceedContract(files, fileList) {
			this.$message.warning(`只能上传 1 份合同文档`);
		},
    handleExceedProof(files, fileList) {
      this.$message.warning(`只能上传少于 10 份支付凭证`);
    },
		handleBeforeUploadContract(file) {
			const maxSize = 10
      const allowedTypes = ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword', '.doc', '.docx', 'application/pdf', '.pdf'] //update
      const type = file.type !== '' ? file.type : file.name.substring(file.name.lastIndexOf('.')).toLowerCase()
      const isRightType = allowedTypes.indexOf(type) >= 0
			const isLtSize = file.size / 1024 / 1024 < maxSize

			if (!isRightType) {
			  this.$message.error('上传文件只能是 docx 或 doc 格式!')
			}
			if (!isLtSize) {
			  this.$message.error('上传文件大小不能超过 ' + maxSize + 'MB!')
			}

			return isRightType && isLtSize
		},

    handleBeforeUploadProof(file) {
      const maxSize = 10
      const allowedTypes = ['image/jpeg', 'image/png'] //update
      const isRightType = allowedTypes.indexOf(file.type) >= 0
      const isLtSize = file.size / 1024 / 1024 < maxSize

      if (!isRightType) {
        this.$message.error('上传文件只能是 docx 或 doc 格式!')
      }
      if (!isLtSize) {
        this.$message.error('上传文件大小不能超过 ' + maxSize + 'MB!')
      }

      return isRightType && isLtSize
    },
    handleContractSuccess(res){
			if(res.status === 400){
				this.$message.error({
					message: res.msg,
					showClose: true
				})
			} else {
				this.form.contract_url = res.memo.fullName
			}
		},
    handleProofSuccess(res){
      if(res.status === 400){
        this.$message.error({
          message: res.msg,
          showClose: true
        })
      } else {
        this.form.pay_proof_url.push(res.memo.fullName)
      }
    },
		handleError(err){
			this.$message.error({
				message: err,
				showClose: true
			})
		},
    getUpUrl() {
      return siteConfig('updateURL') + '?JWT=' + this.$store.getters.jwt
    },
		goBack(){
			this.$router.back()
		},
		append() {
			this.$refs['form'].validate((valid) => {
				if (valid) {
          orderUpdate(this.form).then((res)=>{
						this.$message.success({
							message: '订单修改成功',
							type: 'success',
							showClose: true,
						})
					})

				} else {
					return false;
				}
			});

		},
    getOrderProducts (orderId) {
      let data = {orderId: orderId, pageSize: 100, page: 1}
      orderProducts(data, 'user')
          .then(res => {
            let list = res.memo.list
            this.form.order_products = []
            for(let i in list){
              this.form.order_products[i]= {
                category_id: list[i].category_id,
                product_id: list[i].product_id,
                total: list[i].total,
                num: list[i].num,
              }
              this.handleGetProducts(i)
            }
          })

    },
		getList () {
			getKeyPath()
				.then(res => {
					this.productCategories = res.memo.list
				})
		},
    getView () {
      orderView({id: this.$route.params.id})
          .then(res => {
            this.form = res.memo
            this.getOrderProducts(this.form.id)

            if(!!this.form.contract_url){
              this.contractList = [{
                name: '合同文档',
                url: siteConfig('filePath') + this.form.contract_url
              }]
            }
            if(!!this.form.pay_proof_url){
              this.proofList = this.form.pay_proof_url.map((currentValue)=>{
                return  {
                  name: currentValue.substr(currentValue.lastIndexOf('\\')+1),
                  url: siteConfig('filePath') + currentValue,
                  response:{memo: {fullName : currentValue}}
              }})
            }
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
    padding: 10px;
    .input-size{
      max-width: 300px;
    }
    .product-container{
      .item{
        margin-right: 10px;
      }
    }
    .product-account{
      font-size: 12px;
      color: #409eff;
      span{
        padding-right: 20px;
      }
    }
  }
</style>
