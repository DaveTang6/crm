<template>
	<MainTitle :data-menu="menu" data-active="1" data-sub-title="Add" >Admin</MainTitle>
	<MainBody>
    <el-form ref="form" :model="form" :rules="rules" label-width="100px" class="category-form">
      <el-form-item label="Username" prop="username">
        <el-input v-model="form.username"></el-input>
      </el-form-item>

        <el-form-item label="Password" prop="password">
          <el-input type="password" v-model="form.password"></el-input>
        </el-form-item>

        <el-form-item label="Group" prop="group_id">
          <el-checkbox-group v-model="form.group_id">
            <el-checkbox :label="item.id"  :key="item.id" v-for="item in groupList">{{item.alias}}</el-checkbox>
          </el-checkbox-group>
        </el-form-item>

        <el-form-item label="Truename" prop="truename">
          <el-input  v-model="form.truename"></el-input>
        </el-form-item>

        <el-form-item label="Mobile" prop="mobile">
          <el-input v-model="form.mobile"></el-input>
        </el-form-item>

        <el-form-item label="Email" prop="email">
          <el-input v-model="form.email"></el-input>
        </el-form-item>

        <el-form-item label="Enabled">
        <el-switch v-model="form.enabled" :active-value="1" :inactive-value="0"></el-switch>
        </el-form-item>

        <el-form-item>
        <el-button type="primary" @click="append()">Submit</el-button>
        <el-button @click="goBack">Back</el-button>
        </el-form-item>
    </el-form>
	</MainBody>
</template>
<script>
import validation from "@/utils/validation"
import hash from "@/utils/hash"
import {addAdmin} from "@/api/admins"
import {getAdminAro} from "@/api/adminAros"
export default {
	data () {
		return {
      menu: [{
        title: 'List',
        path: '/admin/index'
      },{
        title: 'Add',
        path: '/admin/add/'
      }],
      groupList: [],
			form: {
			  username: '',
			  group_id: [],
			  password: '',
			  email: '',
        truename: '',
        mobile: '',
        enabled: 1,
			},
			rules: {
				username: [
					{required: true, message: 'Please Enter a username'}
				],
        password: [
          {required: true, message: '请输入密码'},
          {min:5, max: 20, message: '密码长度为5-20个字符'}
        ],
        truename: [
          {required: true, message: '请输入真实姓名'}
        ],
        mobile: [
          {validator: validation.mobile},
        ],
        email: [
          {type: 'email', message: 'Email格式错误'}
        ],
				group_id: [
					{required: true, message: '请选择管理组'},
					{type: 'array', message: '管理组不能为空'}
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
      getAdminAro({type: 'simple'}).then((res)=>{
        this.groupList = res.memo.list
      })
		},
    append() {

      this.$refs['form'].validate((valid) => {
        if (valid) {
          let data = {...this.form}
          data.password = hash.md5hex(data.password)
          addAdmin(data).then((res)=>{
            this.$refs['form'].resetFields()
            this.$message.success({
              message: '管理员添加成功',
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
</style>
