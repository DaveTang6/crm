<template>
	<MainTitle  data-sub-title="产品库">产品</MainTitle>
  <OperationContainer
      :recordNum="pager.total"
      @onShowSearcher="handleShowSearcher"
  >
  </OperationContainer>
	<MainBody>
    <SearcherContainer v-show="showSearcher">
      <el-form :inline="true" :model="searcher" size="mini">
        <el-form-item label="名称">
          <el-input v-model="searcher.title" placeholder="请输入产品名称"></el-input>
        </el-form-item>
        <el-form-item label="类别" prop="category_id">
          <el-select v-model="searcher.category_id" value-key="id" placeholder="请选择类别">
            <el-option value="">全部</el-option>
            <el-option :label="item.title" :value="item.id" :key="item.id" v-for="item in categoryList"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="getList()">查询</el-button>
        </el-form-item>
      </el-form>
    </SearcherContainer>
		<MainContainer>

			<el-table
				:data="tableData"
				stripe
				height="100%"
        width="100%"
      >
				<el-table-column
				  type="index"
				  width="55"
          label="序号"
        >
				</el-table-column>
        <el-table-column
            prop="title"
            label="名称"
        >
        </el-table-column>
        <el-table-column
            prop="category"
            label="分类"
            width="150"
        >
        </el-table-column>
        <el-table-column
            prop="price"
            label="指导价格(元)"
            width="130"
        >
        </el-table-column>

				<el-table-column
					label="操作"
          width="100"
        >
          <template #default="scope">
            <el-button
                size="mini"
                type="default"
                @click="handleView(scope.row.id)"
            >查看</el-button>
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
	import {getProductLimit, deleteProducts, getKeyPath} from '@/api/products'

export default {
	data () {
		return {
      categoryList: [],
			tableData: [],
			pager: {
				page: 1,
				total: 0,
				pageSize: 30
			},
      searcher: {
        category_id: '',
        title: '',
        status: ''
      },
      showSearcher: false,
		}
	},
	mounted() {
		this.getList()
    this.getCategories()
	},
	methods: {
    handleShowSearcher(v){
      this.showSearcher = v
    },
    handleView(id) {
      let routeData = this.$router.resolve({
        path: '/product/view/' + id,
      })
      window.open(routeData.href, '_blank');
    },
    handleEdit(id) {
      this.$router.push('/product/update/' + id)
    },
		handleDelete(id) {
			this.$confirm('此操作将删除选中的项，是否继续?', '提示', {
			  confirmButtonText: '确定',
			  cancelButtonText: '取消',
			  type: 'warning'
			}).then(() => {
        deleteProducts({ids: [id]})
				.then(res=>{
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
		getList () {
			let data = {...this.searcher, ...this.pager}
      getProductLimit(data)
				.then(res => {
					this.tableData = res.memo.list
					this.pager.page = res.memo.page
					this.pager.pageSize = res.memo.pageSize
					this.pager.total = res.memo.total
			     })

		},
    getCategories () {
      getKeyPath()
          .then(res => {
            this.categoryList = res.memo.list
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
  .kk-red{
    color: #ff0000;
  }

  .kk-green{
    color: #42B983
  }
</style>
