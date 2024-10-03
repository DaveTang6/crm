<template>
    <div class="main-menu">
        <ul class="main-menu-parent">
            <li class="main-menu-item" v-for="(item,index) in menus" :key="'p' + index">
                <div class="main-menu-title"
                     :class="{'active': selectedMenu['p'+index] === xmenu}"
                     @click="doParent(item.path, index)">
                    <i class="el-icon-menu"></i>
                    <span>{{ item.title }}</span>
                    <i class="el-icon-arrow-down" :class="{'reverse' : childrenShow.indexOf(index)>=0}"
                       v-if="!item.path"></i>
                </div>
                <ul class="main-menu-children" v-if="!!item.children" v-show="childrenShow.indexOf(index) >= 0">
                    <li v-for="(child,cIndex) in item.children"
                        :key="'c' + cIndex"
                        :class="{'active': selectedMenu['p'+index+'c'+cIndex] === xmenu}"
                        @click="doChildren(child.path, index, cIndex)"
                    >
                        <span class="main-menu-dot">{{ child.title }}</span>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

</template>
<script>
import {getSelfAros} from '@/api/adminAros.js'
import {getMenu} from '@/api/sysMenus.js'

export default {
//通过用户ID获取对应管理组，再通过管理组获取权限ID，通过权限ID判断是否显示对应按钮
    data() {
        return {
            menus: [],
            childrenShow: [],
            selectedMenu: {},
            sysMenu: [],
            xmenu: ''
        }
    },
    created() {
        this.getMenu()
        this.$router.afterEach((to, from) => {
            this.xmenu = to.fullPath

        })
    },
    mounted() {

    },
    methods: {
        getMenu() {
            getMenu().then((res) => {
                this.sysMenu = res.memo.list
                this.getAros()
            })
        },
        getAros() {
            getSelfAros().then((res) => {
                let aros = res.memo,
                    allowed = aros['allowed'],
                    denied = aros['denied']

                if (!allowed) {
                    return
                }
                this.$store.dispatch('setRole', {allowed: aros['allowedAlias'], denied: aros['deniedAlias']})
                let sysMenu = this.sysMenu
                for (let i in sysMenu) {
                    let p = sysMenu[i]

                    if (allowed.indexOf(p.role) < 0) {
                        continue
                    }

                    if (!!p.children) {

                        let tmp = []
                        for (let j in p.children) {
                            if ((allowed.indexOf(p.children[j].role) >= 0) && (denied.indexOf(p.children[j].role) < 0)) {
                                //p.children.splice(j, 1)
                                tmp.push(p.children[j])
                            }
                        }
                        p.children = tmp
                    }

                    if (!p.children || (Array.isArray(p.children) && p.children.length <= 0)) {
                        continue
                    }
                    this.menus.push(p)
                }

            })
        },
        doChildren(path, index, cIndex) {
            this.$router.push(path)
            this.selectedMenu['p' + index + 'c' + cIndex] = path
        },
        doParent(path, index) {
            if (!!path) {
                this.$router.push(path)
                this.selectedMenu['p' + index] = path
                console.log(this.selectedMenu, 'doparent')
            } else {
                let i = this.childrenShow.indexOf(index)
                if (i >= 0) {
                    this.childrenShow.splice(i, 1)
                } else {
                    this.childrenShow.push(index)
                }

            }
        }, // 1
    },
}

</script>
<style lang="scss" scoped>
.main-menu {
  display: flex;
  flex-direction: column;

  .main-menu-parent {
    font-size: 14px;
    font-weight: bold;
    padding-top: 20px;

    .main-menu-item {
      .main-menu-title {
        position: relative;
        padding: 0 30px 0 20px;
        height: 38px;
        line-height: 38px;
        cursor: pointer;

        .el-icon-menu {
          padding-right: 10px;
        }

        .el-icon-arrow-down {
          position: absolute;
          right: 18px;
          top: 13px;
          font-size: 12px;
        }

        .el-icon-arrow-down.reverse {
          transform: rotate(180deg);
        }

      }

      .main-menu-title:hover {
        background-color: rgba(52, 66, 88, .6);
      }

      .main-menu-title.active {
        color: #FFFFFF;
        background-color: #409eff !important;
      }

      .main-menu-children {
        font-size: 14px;
        font-weight: 300;
        background-color: #222;
        padding: 10px 0;

        li {
          line-height: 38px;
          height: 38px;
          padding: 0 20px;
          cursor: pointer;
        }

        li:hover {
          background-color: rgba(52, 66, 88, .6);
        }

        li.active {
          background-color: #409eff !important;
          color: #FFFFFF;
        }
      }

    }


  }

  .main-menu-dot::before {
    content: "";
    background-color: #7c878e;
    width: 3px;
    height: 3px;
    border-radius: 10px;
    margin-right: 14px;
    display: inline-block;
    vertical-align: text-top;
    margin-top: 11px;
    margin-left: 6px;
  }
}

.main-menu-dot::before {
  content: "";
  background-color: #7c878e;
  width: 3px;
  height: 3px;
  border-radius: 10px;
  margin-right: 14px;
  display: inline-block;
  vertical-align: text-top;
  margin-top: 11px;
  margin-left: 6px;
}
</style>
