<template>
	<MainTitle :data-menu="menu" data-active="1" data-sub-title="新增" >订单管理</MainTitle>
	<MainBody>
        <el-alert title="注意：请完善客户信息后再填写订单，否则可能导致订单创建失败！" type="warning" />
			  <el-form ref="form" :model="form" :rules="rules" label-width="100px" class="category-form">
				<el-form-item label="客户微信号" prop="customer_wechat">
					<el-input v-model="form.customer_wechat" class="input-size"></el-input>
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
                :multiple="true"
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
                  只能上传 jpg/png 文件，且不超过 10M，不超过10张
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
					<el-button type="primary" @click="append()">立即创建</el-button>
					<el-button @click="goBack">取消</el-button>
				  </el-form-item>
			</el-form>
	</MainBody>

  <el-dialog
      v-model="dialogVisible"
      title="更新客户资料"
      width="50%"
  >
    <el-form ref="customerForm" :model="customerForm" :rules="customerRules" label-width="100px" class="category-form">
      <el-form-item label="微信" prop="wechat">
        <el-input v-model="customerForm.wechat"></el-input>
      </el-form-item>
      <el-form-item label="姓名" prop="truename">
        <el-input v-model="customerForm.truename"></el-input>
      </el-form-item>
      <el-form-item label="电话" prop="mobile">
        <el-input v-model="customerForm.mobile"></el-input>
      </el-form-item>
      <el-form-item label="性别" prop="gender">
        <el-select v-model="customerForm.gender" value-key="key" placeholder="请选择性别">
          <el-option :label="item.title" :value="item.key" :key="item.key" v-for="item in genders"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="所在地区" prop="area">
        <el-input v-model="customerForm.area"></el-input>
      </el-form-item>
      <el-form-item label="公司名" prop="company">
        <el-input v-model="customerForm.company"></el-input>
      </el-form-item>
      <el-form-item label="部门名" prop="department">
        <el-input v-model="customerForm.department"></el-input>
      </el-form-item>
      <el-form-item label="品牌名" prop="brand">
        <el-input v-model="customerForm.brand"></el-input>
      </el-form-item>
      <el-form-item label="备注" prop="memo">
        <el-input v-model="customerForm.memo" type="textarea"></el-input>
      </el-form-item>
      <el-form-item label="等级" prop="level">
        <el-select v-model="customerForm.level"  placeholder="请选择客户等级">
          <el-option :label="item" :value="item" :key="index" v-for="(item,index) in levels"></el-option>
        </el-select>
      </el-form-item>

      <el-form-item label="白鹿会员">
        <el-switch v-model="customerForm.is_bl_member" :active-value="1" :inactive-value="0"></el-switch>
      </el-form-item>
    </el-form>

    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="handleCustomerUpdate"
        >确认</el-button>
      </span>
    </template>
  </el-dialog>
</template>
<script>
	import { orderAdd} from '@/api/orders'
  import {getKeyPath, getProducts} from "@/api/products"
  import {addCustomer, updateCustomer} from "@/api/customers"

  import validation from "@/utils/validation"

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
			menu: [{
				title: '列表',
				path: '/order/myOrders'
			},{
				title: '新增',
				path: '/order/add'
			}],
      orderType: siteConfig('orderType'),
      settleStatus: siteConfig('settleStatus'),
      customerAct: '',
      genders: [{key: 2, title: '未知'}, {key: 0, title: '女'}, {key: 1, title: '男'},],
      levels: ['A', 'B', 'C'],
      customerForm: {
        is_bl_member: 0,
        mobile: '',
        gender: 2,
        level: 'C'
      },
      customerRules: {
        wechat: [
          {required: true, message: '请输入微信名称'}
        ],
        mobile: [
          {required: true, message: '请输入手机号'},
          {validator: validation.mobile},
        ],
        truename: [
          {required: true, message: '请输入客户真实姓名'}
        ],
        area: [
          {required: true, message: '请输入客户所在地区'}
        ],

      },
			productCategories: [],
      products:{},
			dialogImageUrl: '',
			dialogVisible: false,
			form: {
			  customer_wechat: this.$route.params.wechat,
        order_type: 1,
        settle_status: 0,
        pay_proof_url: [],
        order_products: [{
          category_id: null,
          product_id: null,
          total: null,
          num: null,
        }]
			},
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
        pay_proof_url: [
          {required: true, message: '请选择上传支付凭证'},
        ],
			}
		}
	},
  mounted() {
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
    handleCustomerUpdate() {
      this.$refs['customerForm'].validate((valid) => {
        if (valid) {
          if (this.customerAct == 'add') {
            addCustomer(this.customerForm).then((res)=>{
              this.$message.success({
                message: '客户信息添加成功',
                type: 'success',
                showClose: true,
              })
            })
          } else {
            updateCustomer(this.customerForm).then((res)=>{
              this.$message.success({
                message: '客户信息更新成功',
                type: 'success',
                showClose: true,
              })
            })
          }
          this.dialogVisible = false
        } else {
          return false;
        }
      })
    },
    handleGetProducts(index) {
      let category_id = this.form.order_products[index].category_id
      let searcher = {'category_id': category_id, 'status': 1}
      let pager = {'pageSize': 100}
      let data = {...searcher, ...pager}
      getProducts(data, 'limit')
          .then(res => {
            this.form.order_products[index].product_id = null
            this.products[index] = res.memo.list
          })

    },
    handleRemoveContract(file, fileList) {
			this.form.contract_url=''
    },
    handleRemoveProof(file, fileList) {
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
      console.log(file)
			const maxSize = 10
			const allowedTypes = ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword', '.doc', '.docx', '.pdf', 'application/pdf'] //update
      const type = file.type !== '' ? file.type : file.name.substring(file.name.lastIndexOf('.')).toLowerCase()
      console.log(type)
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
        this.$message.error('上传文件只能是 jpeg 或 png 格式!')
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
          orderAdd(this.form).then((res)=>{
						this.$refs['form'].resetFields()
						this.$refs['fileUploader'].clearFiles()
						this.$refs['imgUploader'].clearFiles()
            this.form.order_products = [{
              category_id: null,
              product_id: null,
              total: null,
              num: null,
            }]
						this.$message.success({
							message: '订单添加成功',
							type: 'success',
							showClose: true,
						})
					},(err) => {
            if(!!err.memo){
              if(err.memo === 'add') {
                this.customerAct = 'add'
                this.customerForm = {
                  wechat : this.form.customer_wechat
                }
              } else {
                this.customerAct = 'update'
                this.customerForm = err.memo
              }
              this.dialogVisible = true
            }
          })

				} else {
					return false;
				}
			});

		},
		getList () {
			getKeyPath()
				.then(res => {
					this.productCategories = res.memo.list
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
