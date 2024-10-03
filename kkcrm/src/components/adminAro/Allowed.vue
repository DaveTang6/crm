<template>
	<MainTitle :data-sub-title="alias + '可用权限'" :data-back="true">管理组</MainTitle>
  <MainRow>
    <el-button @click="handleAppend()" type="primary">保存设置</el-button>
  </MainRow>
	<MainBody>
      <div class="area">
        <el-row :gutter="10" class="first-row">
          <el-col :span="6">
            主权限
          </el-col>
          <el-col :span="18">
            子权限
          </el-col>
        </el-row>
        <el-row :gutter="10" v-for="(v,k) in tableData" :key="k">
          <el-col :span="6" >
            <el-checkbox v-model="checkAll[v.id]" @change="handleCheckAllChange(v.id)" class="head-col">
              {{ v.title }}
            </el-checkbox>
          </el-col>
          <el-col :span="18" class="check-col">
            <el-checkbox-group
                v-model="cList[v.id]"
                @change="handleCheckedAcosChange(v.id)"
            >
            <ul>
                <li v-for="(v1, k1) in v.children" :key="'c'+ k1">
                  <el-checkbox :label="v1.id" >{{ v1.title }}</el-checkbox>
                </li>
            </ul>
            </el-checkbox-group>
          </el-col>
        </el-row>
      </div>
	</MainBody>
  <MainRow>
    <el-button @click="handleAppend()" type="primary">保存设置</el-button>
  </MainRow>
</template>
<script>
	import {getCategories} from '@/api/adminAcos'
	import {getView, updateAdminAro} from '@/api/adminAros'
export default {
	data () {
		return {
			tableData: [],
      checkAll: {},
      checkedAcos: [],
      pList: {},
      cList: {},
      alias: this.$route.params.alias,
		}
	},
	mounted() {
		this.getList()
	},
	methods: {
    handleAppend() {
      let a = []
      for(let i in this.cList) {
        if(this.cList[i].length > 0) {
          a = a.concat(this.cList[i])
          a.push(parseInt(i))
        }
      }
      let data = {id: this.$route.params.id, allowed: JSON.stringify(a)}
      updateAdminAro(data).then((res)=>{
        this.$message.success({
          message: '可用权限设置成功',
          type: 'success',
          showClose: true,
        })
      })
    },
    handleCheckAllChange(pid) {
      if(!!this.checkAll[pid]){
        this.cList[pid] = this.pList[pid]
      } else {
        this.cList[pid] = []
      }

    },
    handleCheckedAcosChange(pid) {
      if(this.cList[pid].length === this.pList[pid].length) {
        this.checkAll[pid] = true
      } else {
        this.checkAll[pid] = false
      }
    },
		getList () {
      getView({id : this.$route.params.id}).then(data => {
        this.checkedAcos = data.memo.allowed
        console.log(data)
        getCategories()
            .then(res => {
              this.tableData = res.memo.list
              for(let i in this.tableData) {
                let pid = this.tableData[i].id,
                    child = this.tableData[i].children
                this.pList[pid] = []
                this.cList[pid] = []
                for(let j in child) {
                  let cid = child[j].id
                  this.pList[pid].push(cid)
                  if(this.checkedAcos.indexOf(cid) >= 0 ) {
                    this.cList[pid].push(cid)
                  }
                }
                if(this.cList[pid].length === this.pList[pid].length) {
                  this.checkAll[pid] = true
                }
              }
            })
      })

		},
	},
}

</script>
<style lang="scss" scoped>
.area{
  padding: 10px;
}
.first-row{
  background-color: #409EFF;
  padding: 8px;
  color: #f2f2f2;
  font-weight: bold;
}
.el-row {
  margin-bottom: 20px;
}
.el-row:last-child {
  margin-bottom: 0;
}
.head-col{
  font-weight: bold;
}
.check-col{
  ul {
    display: flex;
    flex-wrap:wrap;
    li {
      padding: 0 5px;
    }
  }
}
</style>
