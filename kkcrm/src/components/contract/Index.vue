<template>
	<MainTitle :data-menu="menu" data-active="0" >合同管理</MainTitle>
  <OperationContainer
      :recordNum="pager.total"
      @onShowSearcher="handleShowSearcher"
  >
    <el-button type="primary" @click="handleDelete()" size="mini">删除</el-button>
    <el-dropdown  size="mini" style="margin-left: 10px">
      <el-button type="info"  size="mini">
        状态设置 <i class="el-icon el-icon-arrow-down"></i>
      </el-button>
      <template #dropdown>
        <el-dropdown-menu>
          <el-dropdown-item @click="handleStatus(0)" >不可用</el-dropdown-item>
          <el-dropdown-item @click="handleStatus(1)" >可用</el-dropdown-item>
        </el-dropdown-menu>
      </template>
    </el-dropdown>
  </OperationContainer>
	<MainBody>
    <SearcherContainer v-show="showSearcher">
      <el-form :inline="true" :model="searcher" class="form-searcher" size="mini">
        <el-form-item label="标题">
          <el-input v-model="searcher.title" placeholder="标题" ></el-input>
        </el-form-item>
        <el-form-item label="分类">
          <el-select v-model="searcher.category_id" value-key="i" placeholder="所有分类">
            <el-option label="所有分类" value=""></el-option>
            <el-option :label="item.title" :value="item.id" :key="item.id" v-for="item in categoryList"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="状态">
          <el-select v-model="searcher.status" placeholder="全部状态">
            <el-option label="全部" value=""></el-option>
            <el-option label="可用" value="1"></el-option>
            <el-option label="不可用" value="0"></el-option>
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
				@selection-change="handleSelectionChange"
				height="100%"
        width = "100%"
      >
				<el-table-column
				  type="selection"
				  width="55">
				</el-table-column>
				<el-table-column
					prop="title"
					label="标题"
          width="200">
				</el-table-column>
				<el-table-column
					prop="category"
					label="分类"
					width="180">
				</el-table-column>
        <el-table-column
            prop="content"
            label="说明"
            >
        </el-table-column>
				<el-table-column
					label="状态"
					width="80">
					<template #default="scope">
						<i class="el-icon-check" v-if="scope.row.status==1"></i>
						<i class="el-icon-close" v-else></i>
					</template>
				</el-table-column>
				<el-table-column
					width="150"
					label="操作">
				  <template #default="scope">
					<el-button
					  size="mini"
					  type="danger"
					  @click="handleEdit(scope.row.id)">编辑</el-button>
            <el-button
                size="mini"
                type="warning"
                @click="handleDownload(scope.row.file_url)"
                v-if="scope.row.status==1">下载</el-button>
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
	import {getContracts, setStatus, deleteContracts} from '@/api/contracts'
	import {getKeyPath} from '@/api/contracts'
  import siteConfig from '@/config/config.js'
export default {
	data () {
		return {
			menu: [{
				title: '列表',
				path: '/contract/Index'
			},{
				title: '新增',
				path: '/contract/add'
			}],
			tableData: [],
			categoryList: [],
			selectedItems: [],
			pager: {
				page: 1,
				total: 0,
				pageSize: 30
			},
			searcher: {
				title: '',
				category_id: '',
				status: ''
			},
      showSearcher: false,

		}
	},
	mounted() {
		this.getCategories()
		this.getList()
	},
	methods: {
    handleShowSearcher(v){
      this.showSearcher = v
    },
    handleDownload(fileUrl) {
      window.open(siteConfig('apiURL') + 'files/' + fileUrl)
    },
		handleSelectionChange(val) {
			this.selectedItems=[]
			for(let i in val){
				this.selectedItems.push(val[i].id)
			}

		},
		getCategories(){
			getKeyPath()
				.then(res => {
					this.categoryList = res.memo.list
			     })
		},
		handleStatus(act) {
			if(this.selectedItems.length<=0){
				this.$message.warning('请先选择需要设置的项')
				return false
			}
			setStatus({ids: this.selectedItems, status: act})
				.then(res => {
					this.getList()
			     })
		},
		handleDelete(id) {
			if(this.selectedItems.length<=0){
				this.$message.warning('请先选择需要删除的项')
				return false
			}
			this.$confirm('此操作将删除选中的文档，是否继续?', '提示', {
			  confirmButtonText: '确定',
			  cancelButtonText: '取消',
			  type: 'warning'
			}).then(() => {
        deleteContracts({ids: this.selectedItems})
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
		handleEdit(id) {
			this.$router.push('/contract/update/' + id)
		},
		getList () {
			let data = {...this.searcher, ...this.pager}
      getContracts(data)
				.then(res => {
					this.tableData = res.memo.list
					this.pager.page = res.memo.page
					this.pager.pageSize = res.memo.pageSize
					this.pager.total = res.memo.total
			     })
		}, // 1
	},
}

</script>
<style lang="scss" scoped>
	.el-icon-check{
		color: #ff0000;
	}
	.el-icon-close{
		color: #42B983
	}
</style>
