<template>
	<MainTitle data-sub-title="跟进记录" >客户管理</MainTitle>
  <MainRow>
    <el-form :inline="true" :model="searcher" class="form-searcher">
      <el-form-item>
        微信号：{{wechat}}
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="handleShow(-1)">新增跟进</el-button>
      </el-form-item>
    </el-form>
  </MainRow>
  <MainBody>
      <div class="timeline">
        <span v-if="tableData.length <= 0">暂无记录</span>
        <el-timeline v-else>
          <el-timeline-item
              v-for="(item, index) in tableData"
              :key="index"
              :timestamp="item.timestamp"
          >
            <span class="timeline-content" @click="handleShow(index)">{{ item.content }}</span>
          </el-timeline-item>
        </el-timeline>
      </div>
  </MainBody>
  <el-dialog
      v-model="dialogVisible"
      title="跟进日志操作"
      width="50%"
  >

    <el-form ref="form" :model="form" :rules="rules" label-width="60px" class="category-form">
      <el-form-item label="日志" prop="content">
        <el-input v-model="form.content" type="textarea"></el-input>
      </el-form-item>

      <el-form-item label="时间" prop="timestamp">
        <el-date-picker
            v-model="form.timestamp"
            value-format="YYYY-MM-DD"
            type="date"
            placeholder="Pick a day"
        />
      </el-form-item>

    </el-form>

    <template #footer>
      <span class="dialog-footer">
        <el-button @click="dialogVisible = false">取消</el-button>
        <el-button type="primary" @click="handleAdd" v-if="updateIndex == -1">新增</el-button>
        <template v-else>
          <el-button type="warning" @click="handleUpdate">修改</el-button>
          <el-button type="danger" @click="handleDelete(form.id)">删除</el-button>
        </template>
      </span>
    </template>
  </el-dialog>
</template>
<script>
import { addCustomerSaleLog, updateCustomerSaleLog, getCustomerSaleLogs, deleteCustomerSaleLogs} from "@/api/customers"
import formatDateTime from "@/utils/formatDateTime"
export default {
	data () {
		return {
      wechat: this.$route.params.wechat,
      customerId: this.$route.params.id,
      searcher: {},
      dialogVisible: false,
      updateIndex: -1,
      form: {},
      rules: {
        content: [
          {required: true, message: '请输入日志内容'}
        ],
        timestamp: [
          {required: true, message: '请输入日期'},
          {type: 'date', message: '请输入正确的日期'}
        ],
      },
      tableData: []
		}
	},
  mounted() {
    this.getList()
  },
	methods: {
    handleDelete(id) {
      this.$confirm('此操作将删除该跟进日志，是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        deleteCustomerSaleLogs({ids: [id]})
            .then(res=>{
              this.dialogVisible = false
              this.getList()
              this.$message({
                showClose: true,
                type: 'success',
                message: '删除成功!'
              });
            })

      })
      return false
    },
    handleUpdate() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          updateCustomerSaleLog(this.form).then((res)=>{
            this.getList()
            this.$message.success({
              message: '修改成功',
              type: 'success',
              showClose: true,
            })
          })

        } else {
          return false;
        }
      })
    },
    handleAdd() {
      this.$refs['form'].validate((valid) => {
        if (valid) {
          addCustomerSaleLog(this.form).then((res)=>{
            this.$refs['form'].resetFields()
            this.getList()
            this.$message.success({
              message: '添加成功',
              type: 'success',
              showClose: true,
            })
          })

        } else {
          return false;
        }
      })
    },
    handleShow(i) {
      this.updateIndex = i
      if(i>=0) {
        this.form = this.tableData[i]
      } else {
        this.form = {
          customer_id: this.customerId,
          content: '',
          timestamp: formatDateTime(new Date(), 'yyyy-mm-dd')
        }
      }
      this.dialogVisible = true
    },
    getList () {
      getCustomerSaleLogs({customer_id: this.customerId})
          .then(res => {
            this.tableData = res.memo.list

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

  .el-descriptions {
    margin-top: 20px;
  }
  .cell-item {
    display: flex;
    align-items: center;
  }
  .descriptions {
    padding: 20px;
  }
  .order-table{
    padding: 20px;
    h3{
      margin-bottom: 20px;
    }
  }
  .timeline{
    padding: 20px;
    height: 100%;
    overflow-y: auto;
  }
  .timeline-content{
    cursor: pointer;
  }
</style>
