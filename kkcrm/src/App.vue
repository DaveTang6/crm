<template>
  <template v-if="$route.meta.template === 'default'">
    <el-container class="app-container">
      <el-header height="48px" style="background-color: #262f3e">
        <MainTopBar></MainTopBar>
      </el-header>
      <el-container class="app-container-menu-box">
        <transition name="slide-fade">
          <el-aside width="200px"  v-show="menuShow" class="app-container-menu" >
            <el-scrollbar>
              <MainMenu></MainMenu>
            </el-scrollbar>
          </el-aside>
        </transition>
        <el-main class="app-contain-body">
          <router-view></router-view>
        </el-main>
        <div class="app-container-menu-bar" :class="{'app-container-menu-bar-active': !menuShow}" @click="showMenu">
          <i class="el-icon-caret-left"></i>
        </div>
      </el-container>
    </el-container>
  </template>
    <template v-if="$route.meta.template === 'empty'">
        <router-view></router-view>
    </template>

</template>

<script>
import MainTopBar from '@/components/common/MainTopBar.vue'
import MainMenu from '@/components/common/MainMenu.vue'
import checkMenu from '@/utils/checkMenu.js'

export default {
  components:{
    MainTopBar,
    MainMenu
  },
  provide: {
    checkMenu: checkMenu
  },
  data () {
    return {
      menuShow: true
    }
  },
  methods: {
    showMenu () {
      this.menuShow = !this.menuShow
    },
  },
}
</script>

<style>
.app-container{
  height: 100%;
}
.app-container-menu-box{
  height: 100%;
  position: relative;
  overflow-y: auto;
}
.app-container-menu{
  background-color: #1e222d;
  color:#c1c6c8;
}
.app-contain-body{
  position: relative;
  display: flex!important;
  flex-direction: column;
}
.app-container-menu-bar{
  position: absolute;
  left:200px;
  top:45%;
  height: 60px;
  border-bottom: 8px solid transparent;
  border-right: none;
  border-left: 12px solid #262f3e;
  border-top: 8px solid transparent;
  color:#c1c6c8;
  cursor: pointer;
}
.app-container-menu-bar .el-icon-caret-left{
  position: absolute;
  left: -12px;
  top: 15px;
}
.app-container-menu-bar:hover{
  opacity: 0.9;
  transform: scale(1.05);
}

.app-container-menu-bar-active{
  left:0;
}
.app-container-menu-bar-active .el-icon-caret-left{
  transform: rotate(180deg);
}
</style>
<style scoped lang="scss">
.slide-fade-enter-active, {
  transition: width 0.12s ease;
}

.slide-fade-leave-active {
  transition: width 0.12s ease;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  width:0;
}

</style>
