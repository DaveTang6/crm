<template>
	<MainTitle  data-sub-title="客户独占查询">客户管理</MainTitle>
  <MainRow>
    <el-form :inline="true" :model="searcher" class="form-searcher">
      <el-form-item>
        <el-input v-model="searcher.keyword" placeholder="请输入客户微信号/姓名" style="width: 300px"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="getList()">查询</el-button>
      </el-form-item>
    </el-form>
  </MainRow>
	<MainBody v-if="tableData !== false">

		<MainContainer >
			<el-table
				:data="tableData"
				stripe
        width="100%"
      >
				<el-table-column
				  type="index"
				  width="55"
          label="序号"
        >
				</el-table-column>
        <el-table-column
            prop="wechat"
            label="客户微信"
            width="120"
        >
        </el-table-column>
        <el-table-column
            prop="truename"
            label="客户姓名"
            width="120"
        >
        </el-table-column>
        <el-table-column
            prop="locked_time"
            label="锁定时间"
            width="120"
        >
        </el-table-column>
        <el-table-column
            prop="expired_time"
            label="过期时间"
            width="120"
        >
        </el-table-column>
        <el-table-column
            label="锁定人"
            width="100"
        >
          <template #default="scope">
            <span v-if="!scope.row.locked_by_user">无</span>
            <span class="locked_by_user" v-else>{{scope.row.locked_by_user}}</span>
          </template>
        </el-table-column>
			</el-table>

		</MainContainer>
    <el-pagination
        style="padding: 10px 0;"
        background
        layout="prev, pager, next"
        :total="pager.total"
        v-model:currentPage= "pager.page"
        :page-size="pager.pageSize"
        @current-change="getList"
    >
    </el-pagination>
	</MainBody>
</template>
<script>
	import {findCustomers} from '@/api/customers'

export default {
	data () {
		return {
			tableData: false,
			pager: {
				page: 1,
				total: 0,
				pageSize: 30
			},
      searcher: {
      },
		}
	},
	methods: {
		getList () {
		  if(!this.searcher['keyword']) {
        this.$message({
          showClose: true,
          type: 'warning',
          message: '请输入客户微信号/姓名!'
        })
        return false
      }
			let data = {...this.searcher, ...this.pager}
      this.tableData = false
      findCustomers(data)
				.then(res => {
					this.tableData = res.memo.list
					this.pager.page = res.memo.page
					this.pager.pageSize = res.memo.pageSize
					this.pager.total = res.memo.total
        })

		},

	},
}

</script>
<style lang="scss" scoped>
	.el-icon-close, .el-icon-lock{
		color: #ff0000;
	}
	.el-icon-check, .el-icon-unlock{
		color: #42B983
	}
  .el-icon-lock{
    cursor: pointer;
  }
  .el-icon-hot-water, .el-icon-money{
    color: #409eff;
    font-size: 16px;
    padding-right: 10px;
  }
  .locked_by_user{
    color: #ff0000;
  }
  .form-searcher{
    text-align: center;
    margin: 0 auto;
  }
</style>
