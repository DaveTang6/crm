<template>
	<MainTitle   data-sub-title="登录" >系统设置</MainTitle>
	<MainBody>
			  <el-form ref="form" :model="form" :rules="rules" label-width="130px" class="category-form">
          <el-form-item label="游客组ID" prop="guestGroup">
            <el-input  v-model.number="form.guestGroup" ></el-input>
          </el-form-item>

          <el-form-item label="同时登录数" prop="userNum">
            <el-input  v-model.number="form.userNum"></el-input>
          </el-form-item>

				  <el-form-item label="强制下线时间" prop="blockTime">
            <el-date-picker
                v-model="form.blockTime"
                type="datetime"
                placeholder="选择日期时间">
            </el-date-picker>
				  </el-form-item>

          <el-form-item label="登录错误次数" prop="loginErrorNum">
            <el-input  v-model.number="form.loginErrorNum"></el-input>
          </el-form-item>

          <el-form-item label="锁定登录分钟数" prop="loginLockTime">
            <el-input  v-model.number="form.loginLockTime"></el-input>
          </el-form-item>
          <el-form-item label="免登录小时数" prop="usingTime">
            <el-input  v-model.number="form.usingTime"></el-input>
          </el-form-item>
          <el-form-item label="禁止登录">
            <el-switch v-model="form.loginDeny" :active-value="1" :inactive-value="0"></el-switch>
          </el-form-item>
          <el-form-item label="独占客户天数" prop="lockedDays">
            <el-input  v-model.number="form.lockedDays"></el-input>
          </el-form-item>
				  <el-form-item>
					<el-button type="primary" @click="append()">确认</el-button>
					<el-button @click="goBack">取消</el-button>
				  </el-form-item>
			</el-form>
	</MainBody>
</template>
<script>
import formatDateTime from "@/utils/formatDateTime"
import {controlView, controlUpdate} from "@/api/hardConfigs";
export default {
	data () {
		return {
      groupList: [],
      username: '',
			form: {
			  guestGroup: 0,
			  blockTime: 0,
			  loginDeny: 0,
        userNum: 1,
        loginErrorNum: 5,
        loginLockTime: 60,
        usingTime: 5,
        lockedDays: 60
			},
			rules: {
        guestGroup: [
          {required: true, message: '请输入游客组ID'},
          {type: 'number', message: '游客组ID必须为数字'}
        ],
        blockTime: [
          {required: true, message: '请输入强制下线时间'},
          {type: 'date', message: '强制下线时间必须为日期时间格式'}
        ],
        userNum: [
          {required: true, message: '请输入同一用户可登录数量'},
          {type: 'number', message: '同时在线数量必须为数字'}
        ],
        loginErrorNum: [
          {required: true, message: '请输入登录错误次数'},
          {type: 'number', message: '登录错误次数必须为数字'}
        ],
        loginLockTime: [
          {required: true, message: '请输入登录错误后锁定时间'},
          {type: 'number', message: '锁定时间为分钟数'}
        ],
        usingTime: [
          {required: true, message: '请输入免登录小时数'},
          {type: 'number', message: '免登录时长为小时'}
        ],
        lockedDays: [
          {required: true, message: '请输入销售独占客户的天数'},
          {type: 'number', message: '独占天数必须为数字'}
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
      controlView({'alias': 'admin_control'}).then((res)=>{
        let data = JSON.parse(res.memo)
        this.form = {
          guestGroup: data.guestGroup,
          blockTime: formatDateTime(data.blockTime*1000),
          loginDeny: data.loginDeny,
          userNum: data.userNum,
          loginErrorNum: data.loginErrorNum,
          loginLockTime: data.loginLockTime,
          usingTime: data.usingTime,
          lockedDays: data.lockedDays,
        }

      })
    },
    append() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          let form = {...this.form}
          form['blockTime'] = Math.round(formatDateTime(form['blockTime'], 'stamp') / 1000)
          let data = {
            'alias' : 'admin_control',
            'value' : JSON.stringify(form)
          }

          controlUpdate(data).then((res)=>{
            this.$message.success({
              message: '配置修改成功',
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
