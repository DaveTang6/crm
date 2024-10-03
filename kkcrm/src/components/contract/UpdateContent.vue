<template>
	<MainTitle data-sub-title="编辑" data-back="1" >合同管理</MainTitle>
	<MainBody>
			  <el-form ref="form" :model="form" :rules="rules" label-width="100px" class="category-form">
				<el-form-item label="合同名" prop="title">
					<el-input v-model="form.title"></el-input>
				</el-form-item>

				  <el-form-item label="所属分类" prop="category_id">
					<el-select v-model="form.category_id" value-key="id" placeholder="请选择分类">
					  <el-option :label="item.title" :value="item.id" :key="item.id" v-for="item in categoryList"></el-option>
					</el-select>
				  </el-form-item>
				  <el-form-item label="合同文档" prop="file_url">
					    <el-upload
						ref="fileUploader"
						multiple="false"
						accept=".doc,.docx"
            :file-list="fileList"
						:limit="1"
					    :action="getUpUrl()"
						:on-success="handleSuccess"
						:on-error="handleError"
						:before-upload="handleBeforeUpload"
						:on-exceed = "handleExceed"
						:on-remove="handleRemove"
					    >

					    <el-button size="small" type="primary">点击上传</el-button>
						<template #tip>
							<div class="el-upload__tip">
								只能上传 doc/docx 文件，且不超过 10M
							</div>
						</template>
					  </el-upload>
				  </el-form-item>

				<el-form-item label="说明" prop="content">
					 <el-input
					  type="textarea"
					  :rows="3"
					  placeholder="请输入说明"
					  v-model="form.content">
					</el-input>
				</el-form-item>

				  <el-form-item label="是否可用">
					<el-switch v-model="form.status" :active-value="1" :inactive-value="0"></el-switch>
				  </el-form-item>

				  <el-form-item>
					<el-button type="primary" @click="append()">修改合同</el-button>
					<el-button @click="goBack">取消</el-button>
				  </el-form-item>
			</el-form>
	</MainBody>
</template>
<script>
	import { getKeyPath, updateContract, viewContract} from '@/api/contracts'
	import siteConfig from '@/config/config.js'
export default {
	data () {
		return {
			menu: [{
				title: '列表',
				path: '/contract/Index'
			},{
				title: '新增',
				path: '/contract/AddContent/'
			}],
			categoryList: [],
      fileList: [],
			dialogImageUrl: '',
			dialogVisible: false,
			form: {
			  title: '',
			  category_id: '',
			  file_url: '',
			  content: '',
			  status: 1,
			},
			rules: {
				title: [
					{required: true, message: '请输入名称'}
				],
				category_id: [
					{required: true, message: '请选择分类名称'},
					{type: 'number', message: '分类必须为数字'}
				],
        file_url: [
					{required: true, message: '请上传合同文档'}
				],
			}
		}
	},
	mounted() {
    this.getView()
		this.getList()
	},
	methods: {
    getView() {
      viewContract({id: this.$route.params.id}).then((res)=>{

        this.form = {
          id: res.memo.id,
          title: res.memo.title,
          category_id: parseInt(res.memo.category_id),
          file_url: res.memo.file_url,
          content: res.memo.content,
          status: res.memo.status,
        }
        this.fileList = [{
          name: this.form.title,
          url: siteConfig('apiURL') + 'files/' + this.form.file_url
        }]
      })
    },
	    handleRemove(file, fileList) {
			this.form.file_url=''
	    },
		handleExceed(files, fileList) {
			this.$message.warning(`只能上传 1 份合同文档`);
		},
		handleBeforeUpload(file) {
	      console.log(file)
			const maxSize = 10
			const allowedTypes = ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/msword'] //update
			const isRightType = allowedTypes.indexOf(file.type) >= 0
			const isLtSize = file.size / 1024 / 1024 < maxSize

			if (!isRightType) {
			  this.$message.error('上传文件只能是 docx 或 doc 格式!')
			}
			if (!isLtSize) {
			  this.$message.error('上传文件大小不能超过 ' + maxSize + 'MB!')
			}

			return isRightType && isLtSize
		},
		handleSuccess(res){
			if(res.status === 400){
				this.$message.error({
					message: res.msg,
					showClose: true
				})
			} else {
				this.form.file_url = res.memo.fullName
			}

		},
		handleError(err){
			this.$message.error({
				message: err,
				showClose: true
			})
		},
		getUpUrl() {
			return siteConfig('updateURL') + '?JWT=' + this.$store.getters.jwt
		},
		goBack(){
			this.$router.back()
		},
		append() {
			this.$refs['form'].validate((valid) => {
				if (valid) {
          updateContract(this.form).then((res)=>{
						this.$message.success({
							message: '修改合同成功',
							type: 'success',
							showClose: true,
						})
					})

				} else {
					return false;
				}
			});

		},
		getList () {
			getKeyPath()
				.then(res => {
					this.categoryList = res.memo.list
			     })
		}, // 1
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
