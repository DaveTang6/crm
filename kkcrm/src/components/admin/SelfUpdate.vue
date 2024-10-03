<template>
	<MainTitle  data-back="1" data-sub-title="User Setting">Administrator</MainTitle>
	<MainBody>
			  <el-form ref="form" :model="form" :rules="rules" label-width="100px" class="category-form">
          <el-form-item label="Password" prop="password" >
            <el-input type="password" show-password v-model="form.password" placeholder="Leave blank if not changing password"></el-input>
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

				  <el-form-item>
					<el-button type="primary" @click="append()">Update</el-button>
					<el-button @click="goBack">Back</el-button>
				  </el-form-item>
			</el-form>
	</MainBody>
</template>
<script>
import validation from "@/utils/validation"
import hash from "@/utils/hash"
import {selfUpdate, selfView} from "@/api/admins";
export default {
	data () {
		return {
      menu: [],
			form: {
			  password: '',
			  email: '',
        truename: '',
        mobile: '',
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
          {type: 'email', message: 'Invalid email'}
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
      selfView().then((res)=>{
        this.form = {
          password: '',
          email: res.memo.email,
          truename: res.memo.truename,
          mobile: res.memo.mobile,
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

          selfUpdate(data).then((res)=>{
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
