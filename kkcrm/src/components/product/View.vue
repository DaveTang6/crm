<template>
  <MainTitle data-sub-title="产品信息" >产品</MainTitle>
  <el-alert title="产品不存在或已被删除！" type="warning" :closable="false" v-if="form == false" />
  <MainBody v-else-if="!loading">

      <el-descriptions
          class="descriptions"
          :title="form.title"
          :column="2"
          border
      >
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">产品分类</div>
          </template>
          <span>{{  categoryList[form.category_id]?? '未分类' }}</span>
        </el-descriptions-item>
        <el-descriptions-item>
          <template #label>
            <div class="cell-item">价格</div>
          </template>
          <span>{{ form.price }}</span>
        </el-descriptions-item>
      </el-descriptions>

      <div class="view-container">
        <h3>产品详情</h3>
        <div class="view-content">
          <gc-editor-view :content="form.memo"></gc-editor-view>
        </div>
      </div>
  </MainBody>
</template>
<script>
	import {  viewProductLimit, getKeyPath} from '@/api/products'
  import gcEditorView from '@/components/common/gceditor/view.vue'

export default {
	  components:{gcEditorView},
	data () {
		return {
      loading: true,
			form: {},
      categoryList: [],
		}
	},
  mounted() {
    this.getView()
  },
	methods: {
    getView () {
      getKeyPath()
          .then(res => {
            this.categoryList = res.memo.list
            for(let i in res.memo.list) {
              this.categoryList[res.memo.list[i]['id']] = res.memo.list[i]['title'].replace('—', '')
            }
          })
      viewProductLimit({id: this.$route.params.id})
          .then(res => {
              this.loading = false
              this.form = res.memo
          }, err => {
            this.form = false
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
  .url-link{
    color: #409eff;
    cursor: pointer;
  }
  .descriptions {
    padding: 20px;
  }
  .view-container{
    padding: 20px;
    h3{
      margin-bottom: 20px;
    }
    .view-content{
      margin: 0 auto;
      max-width: 900px;
    }
  }
</style>
