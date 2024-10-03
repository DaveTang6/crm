<template>
	<MainTitle data-sub-title="List">Administrator</MainTitle>
  <OperationContainer
      :recordNum="pager.total"
      @onShowSearcher="handleShowSearcher"
  >
    <el-button type="primary" @click="$router.push(addPath)" size="mini">Add an administrator</el-button>
    <el-dropdown  style="margin-left: 10px">
      <el-button type="info"  size="mini">
        Setting <i class="el-icon el-icon-arrow-down"></i>
      </el-button>
      <template #dropdown>
        <el-dropdown-menu>
          <el-dropdown-item @click="handleDelete()" >Delete</el-dropdown-item>
          <el-dropdown-item @click="handleEnabled(0)" >Disable</el-dropdown-item>
          <el-dropdown-item @click="handleEnabled(1)" >Enable</el-dropdown-item>
        </el-dropdown-menu>
      </template>
    </el-dropdown>
  </OperationContainer>
	<MainBody>
    <SearcherContainer v-show="showSearcher">
      <el-form :inline="true" :model="searcher" class="form-searcher" size="mini">
        <el-form-item label="Username">
          <el-input v-model="searcher.username"></el-input>
        </el-form-item>
        <el-form-item label="Real Name">
          <el-input v-model="searcher.truename"></el-input>
        </el-form-item>
        <el-form-item label="Group">
          <el-select v-model="searcher.group_id" value-key="i" >
            <el-option label="All" value=""></el-option>
            <el-option :label="item" :value="id" :key="id" v-for="(item,id) in groups"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="Status">
          <el-select v-model="searcher.enabled" placeholder="All">
            <el-option label="All" value=""></el-option>
            <el-option label="Enabled" value="1"></el-option>
            <el-option label="Disabled" value="0"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="getList()">Search</el-button>
        </el-form-item>
      </el-form>
    </SearcherContainer>
		<MainContainer>
			<el-table
				:data="tableData"
				stripe
				@selection-change="handleSelectionChange"
        height="100%"
        width="100%"
      >
				<el-table-column
				  type="selection"
				  width="55">
				</el-table-column>
				<el-table-column
					prop="username"
					label="Username"
          width="180"
        >
				</el-table-column>
				<el-table-column
					prop="truename"
					label="Real Name"
					width="180">
				</el-table-column>
        <el-table-column
            prop="group_id"
            label="Group"
        >
        </el-table-column>
        <el-table-column
            label="Locked"
            width="80">
          <template #default="scope">
            <i class="el-icon-lock" v-if="scope.row.locked>0" @click="handleUnlock(scope.row.id)" :title="'锁定时间:' + scope.row.locked_time " ></i>
            <i class="el-icon-unlock" v-else ></i>
          </template>
        </el-table-column>
				<el-table-column
					label="Status"
					width="80">
					<template #default="scope">
						<i class="el-icon-check" v-if="scope.row.enabled==1"></i>
						<i class="el-icon-close" v-else></i>
					</template>
				</el-table-column>
				<el-table-column
					width="180"
					prop="last_login"
					label="Last Login Time">
				</el-table-column>
				<el-table-column
					width="80"
					label="Action">
				  <template #default="scope">
					<el-button
					  size="mini"
					  type="danger"
					  @click="handleEdit(scope.row.id)">Edit</el-button>
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
	import {indexAdmin, setAdminEnabled, setAdminUnlocked, deleteAdmin} from '@/api/admins'
export default {
	data () {
		return {
      addPath: '/admin/add/',
			tableData: [],
			groups: [],
			selectedItems: [],
			pager: {
				page: 1,
				total: 0,
				pageSize: 30
			},
			searcher: {
				username: '',
				truename: '',
				group_id: '',
        enabled: ''
			},
      showSearcher: false,
		}
	},
	mounted() {
		this.getList()
	},
	methods: {
    handleShowSearcher(v){
      this.showSearcher = v
    },
    handleUnlock(id) {
      this.$confirm('Unlock the user?', 'Prompt', {
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        type: 'warning'
      }).then(() => {
        setAdminUnlocked({id: id})
            .then(res => {
              this.getList()
            })
      })

    },
		handleSelectionChange(val) {
			this.selectedItems=[]
			for(let i in val){
				this.selectedItems.push(val[i].id)
			}

		},
		handleEnabled(act) {
			if(this.selectedItems.length<=0){
				this.$message.warning('Please select an item to operate.')
				return false
			}
      setAdminEnabled({ids: this.selectedItems, enabled: act})
				.then(res => {
					this.getList()
        })
		},
		handleDelete(id) {
			if(this.selectedItems.length<=0){
				this.$message.warning('Please select an item to operate.')
				return false
			}
			this.$confirm('This action will delete the items you\'ve selected. Continue?', 'Prompt', {
			  confirmButtonText: 'Confirm',
			  cancelButtonText: 'Cancel',
			  type: 'warning'
			}).then(() => {
        deleteAdmin({ids: this.selectedItems})
				.then(res=>{
					this.getList()
					this.$message({
						showClose: true,
						type: 'success',
						message: 'Successfully Deleted!'
					});
				})

			})
			return false
		},
		handleEdit(id) {
			this.$router.push('/admin/update/' + id)
		},
		getList () {
			let data = {...this.searcher, ...this.pager}
      indexAdmin(data)
				.then(res => {
					this.tableData = res.memo.list
          this.groups = res.memo.groups
					this.pager.page = res.memo.page
					this.pager.pageSize = res.memo.pageSize
					this.pager.total = res.memo.total
			     })

		}, // 1
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
</style>
