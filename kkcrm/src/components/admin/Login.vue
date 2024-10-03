<template>
  <div class="login-page">
    <div class="login-form">
      <el-row>
        <el-col class="login-title">Customer Sales Management System</el-col>
      </el-row>
      <el-row>
        <el-col>
          <el-form ref="form" :model="form" :rules="rules" >
            <el-form-item prop="username">
              <el-input
                  v-model="form.username"
                  placeholder="Username"
              ></el-input>
            </el-form-item>
            <el-form-item prop="password">
              <el-input
                  v-model="form.password"
                  type="password"
                  placeholder="Password"
                  show-password
                  @keyup="goLogin"
              ></el-input>
            </el-form-item>

            <el-form-item>
              <el-button type="primary" @click="loginIn()" size="large" class="login-btn">Sign in</el-button>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
import hash from "@/utils/hash"
import {login} from '@/api/admins'


export default {
	data () {
		return {
		  pageFrom: null,
      form: {
        username: '',
        password: ''
      },
      rules: {
        username: [
          {required: true, message: 'Please enter a username'},
          {min:2, max: 20, message: 'Invalid username format'}
        ],
        password: [
          {required: true, message: 'Please enter a password'},
          {min:5, max: 20, message: 'Password length should be 5-20 characters.'}
        ],
      }
		}
	},
    created(){
    },
  beforeRouteUpdate(to, from) {
	  this.pageFrom = from
	  // console.log(to, from)
    // if(from.name!== undefined && from.name!==to.name) {
    //   this.p = this.$route.params.p
    // }
    // console.log(this.p)
  },
	methods: {
    goLogin(e) {
      if(e.keyCode === 13) {
        this.loginIn()
      }
    },
    loginIn() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          let data = {...this.form}
          data.password = hash.md5hex(data.password)
          login(data).then((res)=>{
            this.$store.dispatch('loginIn', res.memo)
            this.$message.success({
              message: 'Login successful.',
              type: 'success',
              showClose: true,
            })
            console.log(this.pageFrom)
              this.$router.push('/statistic/mine')
            //let p = parseInt(this.$route.params.p)
            // if(!this.pageFrom || this.pageFrom === 'adminLogin' || !p){
            //   this.$router.push('/statistic/mine')
            // } else {
            //   this.$router.go(-p)
            // }
          })
        } else {
          return false
        }
      })
    }
	},
}

</script>
<style scoped>
  body {
    background-color: #1e222d !important;
  }
	.login-page{
    background-color: #1e222d;
    width: 100%;
    height: 100%;
    padding-top: 50px;
	}
  .login-form{
    margin: 0 auto;
    padding: 30px 90px 50px 90px;
    width: 500px;
    background-color: rgb(50, 50, 50);
    border-radius: 5px;
  }
  .login-title{
    font-size: 24px;
    font-weight: 700;
    color: #f2f2f2;
    text-align: center;
    margin-bottom: 30px;
  }
  .el-row{
    margin-top: 20px;
  }
  .login-btn{
    margin-top: 20px;
    width: 100%;
    height: 50px;
    font-size: 18px;
  }
</style>
