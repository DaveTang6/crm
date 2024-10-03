<template>

	<div class="operation-container">
      <div class="operation-btn">
        <slot></slot>
      </div>
      <div class="operation-searcher">
        <div class="searcher-btn" v-if="searcherBtn">
          <i class="el-icon el-icon-search" :class="{active: showSearcher}" @click="handleShowSearcher()"> Search</i>
          <div class="searcher-triangle" v-show="showSearcher"></div>
        </div>
        <div class="searcher-record" v-if="recordNum!==false">
          <el-divider direction="vertical" />
          Total of <span>{{ recordNum }}</span> records
        </div>
      </div>
	</div>
</template>
<script>
export default {
  props:{
    recordNum:{
      type: [Number, Boolean],
      default(){
        return false
      }
    },
    searcherBtn:{
      type: Boolean,
      default() {
        return true
      }
    }
  },
  emits:[
    'onShowSearcher'
  ],
  data(){
    return {
      showSearcher: false
    }
  },
  methods: {
    handleShowSearcher() {
      this.showSearcher = !this.showSearcher
      this.$emit('onShowSearcher', this.showSearcher)
    }
  }
}
</script>
<style scoped lang="scss">
.operation-container{
  width: 100%;
  display: flex;
  justify-content: space-between;
  padding-top: 10px;
  flex-shrink: 0;
  .operation-btn {
    display: flex;
    flex-grow: 1;
  }
  .operation-searcher{
    display: flex;
    text-align: right;
    padding-top: 5px;
    color:#999;
    .searcher-btn{
      position: relative;
      background-color: #409EFF;
      padding: 3px 15px;
      color: #f2f2f2;
      border-radius: 15px;
      i{cursor: pointer;}
      .searcher-triangle{
        width: 0px;
        height: 0px;
        border-left: 12px solid transparent;
        border-right: 12px solid transparent;
        border-bottom: 15px solid rgb(255, 255, 255);
        font-size: 0px;
        line-height: 0;
        position: absolute;
        bottom: -20px;
        z-index: 100;
        display: block;
      }
    }
    .searcher-btn.active{
      background-color: #67C23A;
    }
    .searcher-record{
      span{
        font-weight: bold;
        color:#000000;
      }
    }
  }
}
</style>
