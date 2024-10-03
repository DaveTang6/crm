<template>

	<div class="main-title">
		<div class="title" v-if="!dataBack && !dataPath">
			<slot></slot>
			<span class="sub-title" v-if="!!dataSubTitle"> - {{dataSubTitle}}</span>
		</div>
		<el-page-header icon="el-icon-arrow-left" title="Back" @back="goBack" v-else>
			<template v-slot:content>
				<span class="title">
					<slot></slot>
					<span class="sub-title" v-if="!!dataSubTitle"> - {{dataSubTitle}}</span>
				</span>
			</template>
		</el-page-header>
		<el-menu :default-active="activeIndex" mode="horizontal" @select="handleSelect" v-if="!!dataMenu">
			<el-menu-item :index="item.path" v-for="(item,index) in dataMenu">{{item.title}}</el-menu-item>
		</el-menu>
		<div class="line" v-else></div>
	</div>

</template>
<script>
export default {
	props:{
		dataBack: {
			type: Boolean,
			default: false,
		},
		dataPath: {
			type: String,
			default: null
		},
		dataMenu: {
			type: Array,
			default() {
				return null
			}
		},
		dataActive: {
			type: [String, Number],
			default: null
		},
		dataSubTitle: {
			type: String,
			default: null
		}
	},
	mounted(){
		if(this.dataActive !== null){
			this.activeIndex = this.dataMenu[this.dataActive].path
		}
	},
  data () {
    return {
		activeIndex: ''
    }
  },
  methods: {
    goBack () {
		if (!!this.dataBack){
			this.$router.back()
		} else {
			this.$router.push(this.dataPath)
		}

    },
	handleSelect(v) {
		this.$router.push(v)
	}
  },
}

</script>
<style lang="scss" scoped>
.main-title{
	width: 100%;
  box-sizing: border-box;
	padding: 10px 20px 0 20px;
	margin-bottom: 10px;
	border-bottom: 1px solid #fff;
	color: #000;
	background-color: #fff;
  flex-shrink: 0;
	.title{
		font-size: 18px;
		color: #303133;
		font-weight: 700;
		.sub-title{
			color: #666;
			font-size: 14px;
			font-weight: normal;
		}
	}
	.line {
		padding: 10px 0 0 0;
	}
}
</style>
