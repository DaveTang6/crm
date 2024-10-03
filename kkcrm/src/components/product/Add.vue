<template>
	<MainTitle :data-menu="menu" data-active="1" data-sub-title="新增" >产品管理</MainTitle>
	<MainBody>
			  <el-form ref="form" :model="form" :rules="rules" label-width="120px" class="content-form">
          <el-form-item label="类别" prop="category_id">
            <el-select v-model="form.category_id" value-key="id" placeholder="请选择类别">
              <el-option :label="item.title" :value="item.id" :key="item.id" v-for="item in categoryList"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="名称" prop="title">
            <el-input v-model="form.title"></el-input>
          </el-form-item>

          <el-form-item label="指导价格" prop="price">
            <el-input-number v-model="form.price" :precision="2" :step="1" placeholder="单位：元"></el-input-number>
          </el-form-item>

          <el-form-item label="成本计算方式" prop="cost_type">
            <el-radio-group v-model="form.cost_type">
              <el-radio :label="0">按成本</el-radio>
              <el-radio :label="1">按比例</el-radio>
            </el-radio-group>
          </el-form-item>

          <el-form-item label="成本价" prop="cost" v-if="form.cost_type===0">
            <el-input-number v-model="form.cost" :precision="2" :step="1" placeholder="单位：元" ></el-input-number>
          </el-form-item>
          <el-form-item label="成本比例(%)" prop="cost" v-else>
            <el-input-number v-model="form.cost"  :step="1" placeholder="单位：百分比"></el-input-number>
          </el-form-item>

          <el-form-item label="是否可用">
            <el-switch v-model="form.status" :active-value="1" :inactive-value="0"></el-switch>
          </el-form-item>

          <el-form-item label="产品介绍">
            <gceditor v-model="form.memo"></gceditor>
          </el-form-item>

				  <el-form-item>
            <el-button type="primary" @click="append()">新增</el-button>
            <el-button @click="goBack">取消</el-button>
				  </el-form-item>
			</el-form>
	</MainBody>
</template>
<script>
import {addProduct, getKeyPath} from "@/api/products"
import gceditor from "@/components/common/gceditor/index.vue"

export default {
  components: {gceditor},
	data () {
		return {
      menu: [{
        title: '列表',
        path: '/product/index',
      },{
        title: '新增',
        path: '/product/add',
      }],
      categoryList: [],
			form: {
        category_id: '',
        title: '',
        price: 0,
        cost: 0,
        cost_type: 0,
        status: 1,
        memo: ''
			},
			rules: {
        title: [
          {required: true, message: '请输入产品名称'}
        ],
        price: [
          {required: true, message: '请输入指导价格'},
          {type: 'number', message: '价格输入错误'}
        ],
        cost: [
          {required: true, message: '请输入成本/比例'},
          {type: 'number', message: '成本/比例输入错误'}
        ],
        cost_type: [
          {required: true, message: '请输入成本比例'},
        ],
        category_id: [
          {required: true, message: '请输入类别'},
          {type: 'number', message: '类别输入错误'}
        ],
			}
		}
	},
  mounted() {
    this.getList()
  },
	methods: {
    goBack(){
      this.$router.back()
    },
    getList () {
      getKeyPath()
          .then(res => {
            this.categoryList = res.memo.list
          })
    }, // 1
    append() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          addProduct(this.form).then((res)=>{
            this.$refs['form'].resetFields()
            this.form.memo = ''
            this.$message.success({
              message: '产品添加成功',
              type: 'success',
              showClose: true,
            })
          })

        } else {
          return false;
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
	.content-form {
		width: 100%;
		padding: 10px;
    box-sizing: border-box;
	}
  .form-knowledge{
    margin-top: 5px;
  }
  .el-icon-hot-water{
    color: #409eff;
    font-size: 16px;
    padding-right: 10px;
  }
</style>
