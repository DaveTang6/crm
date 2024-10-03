<template>
	<MainTitle data-sub-title="部门" >统计</MainTitle>
    <el-card class="box-card">
      <template #header>
        <div class="card-header">

            <span>
              <i class="el-icon el-icon-user"></i>
              客户情况
            </span>
          <span>
              <el-date-picker
                  v-model="customerTime"
                  value-format="YYYY-MM-DD"
                  type="daterange"
                  range-separator="-"
                  start-placeholder="Start date"
                  end-placeholder="End date"
                  @change="getCustomerStatistic"
              />
            </span>
          <span>
              <el-button-group class="ml-4">
                <el-button type="primary" @click="getPeriodCustomerStatistic('y')">本年</el-button>
                <el-button type="primary" @click="getPeriodCustomerStatistic('6m')">半年</el-button>
                <el-button type="primary" @click="getPeriodCustomerStatistic('3m')">三月</el-button>
                <el-button type="primary" @click="getPeriodCustomerStatistic('m')">本月</el-button>
                <el-button type="primary" @click="getPeriodCustomerStatistic('w')">本周</el-button>
              </el-button-group>
            </span>
        </div>
      </template>
      <el-row class="statistic-content">
        <el-col :span="8" class="statistic-item">
          <h3>客户总数</h3>
          <h1>{{ customerStatistic['all'] }} <span>个</span></h1>

        </el-col>

        <el-col :span="8"  class="statistic-item">
          <h3>新增客户</h3>
          <h1>{{ customerStatistic['add'] }} <span>个</span></h1>
        </el-col>
        <el-col :span="8"  class="statistic-item">
          <h3>客户维护</h3>
          <h1>{{ customerStatistic['saleLog'] }} <span>次</span></h1>
        </el-col>
      </el-row>
    </el-card>

    <el-card class="box-card">
      <template #header>
        <div class="card-header">

            <span>
              <i class="el-icon el-icon-document"></i>
              销售情况
            </span>
          <span>
          <el-date-picker
              v-model="orderTime"
              value-format="YYYY-MM-DD"
              type="daterange"
              range-separator="-"
              start-placeholder="Start date"
              end-placeholder="End date"
              @change="getOrderStatistic"
          />
          </span>
          <span>
              <el-button-group class="ml-4">
                <el-button type="primary" @click="getPeriodOrderStatistic('y')">本年</el-button>
                <el-button type="primary" @click="getPeriodOrderStatistic('6m')">半年</el-button>
                <el-button type="primary" @click="getPeriodOrderStatistic('3m')">三月</el-button>
                <el-button type="primary" @click="getPeriodOrderStatistic('m')">本月</el-button>
                <el-button type="primary" @click="getPeriodOrderStatistic('w')">本周</el-button>
              </el-button-group>
            </span>
        </div>
      </template>
      <el-row class="statistic-content">
        <el-col :span="8" class="statistic-item">
          <h3>成交次数</h3>
          <h1>{{ orderStatistic['orders'] }} <span>次</span></h1>

        </el-col>

        <el-col :span="8"  class="statistic-item">
          <h3>销售额</h3>
          <h1>{{ orderStatistic['turnover'] }} <span>元</span></h1>
        </el-col>
        <el-col :span="8"  class="statistic-item">
          <h3>利润</h3>
          <h1>{{ orderStatistic['profit'] }} <span>元</span></h1>
        </el-col>
      </el-row>
    </el-card>
</template>
<script>
import {customerDepartment, orderDepartment} from "@/api/statistic"
import formatDateTime from "@/utils/formatDateTime"

export default {
	data () {
		return {
      customerTime: [],
      orderTime: [],
      customerStatistic: [],
      orderStatistic: [],
		}
	},
  mounted() {
    let period = this.getPeriod('6m')
    this.customerTime[0] = formatDateTime(period[0], 'yyyy-mm-dd')
    this.customerTime[1] = formatDateTime(period[1], 'yyyy-mm-dd')
    this.orderTime[0] = formatDateTime(period[0], 'yyyy-mm-dd')
    this.orderTime[1] = formatDateTime(period[1], 'yyyy-mm-dd')
    this.getCustomerStatistic()
    this.getOrderStatistic()
  },
  methods: {
    getPeriodCustomerStatistic(type) {
      let period = this.getPeriod(type)
      this.customerTime[0] = formatDateTime(period[0], 'yyyy-mm-dd')
      this.customerTime[1] = formatDateTime(period[1], 'yyyy-mm-dd')
      this.getCustomerStatistic()
    },
    getPeriodOrderStatistic(type) {
      let period = this.getPeriod(type)
      this.orderTime[0] = formatDateTime(period[0], 'yyyy-mm-dd')
      this.orderTime[1] = formatDateTime(period[1], 'yyyy-mm-dd')
      this.getOrderStatistic()
    },
    getCustomerStatistic () {
      if(!this.customerTime) {
        this.$message({
          showClose: true,
          type: 'warning',
          message: '请选择日期后再进行客户信息统计!'
        })
        return false
      }

      customerDepartment({'customerTime': this.customerTime})
          .then(res => {
            this.customerStatistic = res.memo
          })
    },
    getOrderStatistic () {
      if(!this.orderTime) {
        this.$message({
          showClose: true,
          type: 'warning',
          message: '请选择日期后再进行销售统计!'
        })
        return false
      }

      orderDepartment({'orderTime': this.orderTime})
          .then(res => {
            this.orderStatistic = res.memo
          })
    },
    getPeriod(type = 'm'){
      let currentDate = new Date(),
          year = currentDate.getFullYear(),
          month = currentDate.getMonth(),
          date = currentDate.getDate(),
          day = currentDate.getDay(),
          monthDays = new Date(year, month, 0).getDate(),
          period = []
      if(type === 'y'){
        period = [
          new Date(year, 0, 1),
          currentDate
        ]
      } else if(type === 'm') {
        period = [
          new Date(year, month, 1),
          currentDate
        ]
      }else if(type === 'w') {
        period = [
          new Date(year, month, date-day),
          currentDate
        ]
      } else if(type === '3m') {
        period = [
          new Date(year, month-3, date),
          new Date(year, month, date)
        ]
      } else if(type === '6m') {
        period = [
          new Date(year, month-6, date),
          new Date(year, month, date)
        ]
      } else { // return current month
        period = [
          new Date(year, month, 1),
          new Date(year, month, monthDays)
        ]
      }
      return period
    }
	},
}

</script>
<style lang="scss" scoped>
 .box-card{
   margin-top: 20px;
   .card-header{
     span{
       font-weight: bold;
       font-size: 16px;
       padding-right:20px;
       line-height: 45px;
     }
   }
 }
 .statistic-content{
   padding-bottom: 10px;
   .statistic-item{
     padding: 0 10px;
     border-right: 1px solid #cccccc;
     h3 {
       font-weight: normal;
       font-size: 14px;
     }
     h1{
       font-weight: bold;;
       font-size: 28px;
       padding: 20px 0;
       span{
         font-size: 12px;
         padding: 0 10px;
         font-weight: normal;
       }
     }
   };
   .statistic-item:last-child{
     border: 0;
   }
 }
</style>
