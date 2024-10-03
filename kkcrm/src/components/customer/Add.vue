<template>
	<MainTitle :data-menu="menu" data-active="1" data-sub-title="新增" >客户管理</MainTitle>
	<MainBody>
			  <el-form ref="form" :model="form" :rules="rules" label-width="100px" class="category-form">
          <el-form-item label="微信" prop="wechat">
            <el-input v-model="form.wechat"></el-input>
          </el-form-item>
          <el-form-item label="姓名" prop="truename">
            <el-input v-model="form.truename"></el-input>
          </el-form-item>
          <el-form-item label="电话" prop="mobile">
            <el-input v-model="form.mobile"></el-input>
          </el-form-item>
          <el-form-item label="性别" prop="gender">
            <el-select v-model="form.gender" value-key="key" placeholder="请选择性别">
              <el-option :label="item.title" :value="item.key" :key="item.key" v-for="item in genders"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="所在地区" prop="area">
            <el-input v-model="form.area"></el-input>
          </el-form-item>
          <el-form-item label="公司名" prop="company">
            <el-input v-model="form.company"></el-input>
          </el-form-item>
          <el-form-item label="部门名" prop="department">
            <el-input v-model="form.department"></el-input>
          </el-form-item>
          <el-form-item label="品牌名" prop="brand">
            <el-input v-model="form.brand"></el-input>
          </el-form-item>
          <el-form-item label="备注" prop="memo">
            <el-input v-model="form.memo" type="textarea"></el-input>
          </el-form-item>
          <el-form-item label="等级" prop="level">
            <el-select v-model="form.level"  placeholder="请选择客户等级">
              <el-option :label="item" :value="item" :key="index" v-for="(item,index) in levels"></el-option>
            </el-select>
          </el-form-item>

          <el-form-item label="白鹿会员">
            <el-switch v-model="form.is_bl_member" :active-value="1" :inactive-value="0"></el-switch>
          </el-form-item>

				  <el-form-item>
            <el-button type="primary" @click="append()">新增</el-button>
            <el-button @click="goBack">取消</el-button>
				  </el-form-item>
			</el-form>
	</MainBody>
</template>
<script>
import {addCustomer} from "@/api/customers"
import validation from "@/utils/validation"

export default {
	data () {
		return {
      menu: [{
        title: '我的客户',
        path: '/customer/myCustomers',
      },{
        title: '新增',
        path: '/customer/add',
      }],
      genders: [{key: 2, title: '未知'}, {key: 0, title: '女'}, {key: 1, title: '男'},],
      levels: ['A', 'B', 'C'],
			form: {
        is_bl_member: 0,
        mobile: '',
        gender: 2,
        level: 'C'
			},
			rules: {
        wechat: [
          {required: true, message: '请输入微信名称'}
        ],
        mobile: [
          {validator: validation.mobile},
        ],
			}
		}
	},
	methods: {
    append() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          addCustomer(this.form).then((res)=>{
            this.$refs['form'].resetFields()
            this.$message.success({
              message: '客户添加成功',
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
</style>
