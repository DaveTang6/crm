<template>
	<MainTitle  data-back="1" data-sub-title="Edit" >Administrator</MainTitle>
	<MainBody>
			  <el-form ref="form" :model="form" :rules="rules" label-width="100px" class="category-form">
				<el-form-item label="Username" prop="username">
					{{username}}
				</el-form-item>

          <el-form-item label="Password" prop="password">
            <el-input type="password" v-model="form.password"></el-input>
          </el-form-item>

				  <el-form-item label="Group" prop="group_id">
            <el-checkbox-group v-model="form.group_id">
              <el-checkbox :label="item.id"  :key="item.id" v-for="item in groupList">{{item.alias}}</el-checkbox>
            </el-checkbox-group>
				  </el-form-item>

          <el-form-item label="Real Name" prop="truename">
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
					<el-button type="primary" @click="append()">Confirm</el-button>
					<el-button @click="goBack">Back</el-button>
				  </el-form-item>
			</el-form>
	</MainBody>
</template>
<script>
import validation from "@/utils/validation"
import hash from "@/utils/hash"
import {viewAdmin, updateAdmin} from "@/api/admins";
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
      username: '',
			form: {
			  id: '',
			  group_id: [],
			  password: '',
			  email: '',
        truename: '',
        mobile: '',
        enabled: 1,
			},
			rules: {
        password: [
          {min:5, max: 20, message: 'Password must be between 5 and 20 characters'}
        ],
        truename: [
          {required: true, message: 'Please enter your real name'}
        ],
        mobile: [
          {validator: validation.mobile},
        ],
        email: [
          {type: 'email', message: 'Invalid Email'}
        ],
				group_id: [
					{required: true, message: 'Please select a group'},
					{type: 'array', message: 'Invalid Groups'}
				],
			}
		}
	},
	mounted() {
    this.getView()
	},
	methods: {
    goBack(){
      this.$router.back()
    },
    getView() {
      viewAdmin({id: this.$route.params.id}).then((res)=>{
        this.username = res.memo.username
        this.groupList = res.memo.groups

        this.form = {
          id: res.memo.id,
          group_id: res.memo.group_id,
          password: '',
          email: res.memo.email,
          truename: res.memo.truename,
          mobile: res.memo.mobile,
          enabled: res.memo.enabled,
        }

      })
    },
    append() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          let data = {...this.form}
          if(data.password !== '') {
            data.password = hash.md5hex(data.password)
          }

          updateAdmin(data).then((res)=>{
            this.$message.success({
              message: 'Successfully updated',
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
