import {createRouter, createWebHashHistory} from 'vue-router'


import AdminLogin from '@/components/admin/Login.vue'
import AdminAdd from '@/components/admin/Add.vue'
import AdminIndex from '@/components/admin/Index.vue'
import AdminUpdate from '@/components/admin/Update.vue'
import AdminSelfUpdate from '@/components/admin/SelfUpdate.vue'
import LoginControl from '@/components/hardConfig/LoginControl.vue'
import AdminAcoAdd from '@/components/adminAco/AddCategory.vue'
import AdminAcoIndex from '@/components/adminAco/Category.vue'
import AdminAcoUpdate from '@/components/adminAco/UpdateCategory.vue'
import AdminAroIndex from '@/components/adminAro/Index.vue'
import AdminAroAdd from '@/components/adminAro/Add.vue'
import AdminAroUpdate from '@/components/adminAro/Update.vue'
import AdminAroAllowed from '@/components/adminAro/Allowed.vue'
import AdminAroDenied from '@/components/adminAro/Denied.vue'
import Warning from '@/components/common/Warning.vue'
import NotFound from '@/components/common/NotFound.vue'

import ContractCategories from '@/components/contract/Category.vue'
import ContractCategoryAdd from '@/components/contract/AddCategory.vue'
import ContractCategoryUpdate from '@/components/contract/UpdateCategory.vue'
import ContractAddContent from '@/components/contract/AddContent.vue'
import ContractUpdateContent from '@/components/contract/UpdateContent.vue'
import ContractIndex from '@/components/contract/Index.vue'

import ProductCategories from '@/components/product/Category.vue'
import ProductCategoryAdd from '@/components/product/AddCategory.vue'
import ProductCategoryUpdate from '@/components/product/UpdateCategory.vue'
import ProductAdd from '@/components/product/Add.vue'
import ProductUpdate from '@/components/product/Update.vue'
import ProductIndex from '@/components/product/Index.vue'
import ProductList from '@/components/product/List.vue'
import ProductView from '@/components/product/View.vue'

import DepartmentIndex from '@/components/department/Index.vue'
import DepartmentStaffs from '@/components/department/Staffs.vue'
import DepartmentMyStaffs from '@/components/department/MyStaffs.vue'

import CustomerMine from '@/components/customer/myCustomers.vue'
import CustomerDepartment from '@/components/customer/departmentCustomers.vue'
import Customers from '@/components/customer/Customers.vue'
import CustomerAdd from '@/components/customer/Add.vue'
import CustomerUpdate from '@/components/customer/Update.vue'
import CustomerView from '@/components/customer/View.vue'
import CustomerViewPlus from '@/components/customer/ViewPlus.vue'
import CustomerTimeline from '@/components/customer/Timeline.vue'
import CustomerLogs from '@/components/customer/Logs.vue'
import CustomerFinder from '@/components/customer/CustomerFinder.vue'

import OrderAdd from '@/components/order/Add.vue'
import OrderMines from '@/components/order/MyOrders.vue'
import OrderDepartments from '@/components/order/DepartmentOrders.vue'
import Orders from '@/components/order/Orders.vue'
import OrderUpdate from '@/components/order/Update.vue'
import OrderView from '@/components/order/View.vue'
import OrderViewPlus from '@/components/order/ViewPlus.vue'
import OrderLogs from '@/components/order/Logs.vue'

import StatisticAll from '@/components/statistic/All.vue'
import StatisticDepartment from '@/components/statistic/Department.vue'
import StatisticMine from '@/components/statistic/Mine.vue'

import SysMenuAdd from '@/components/sysMenu/AddCategory.vue'
import SysMenuIndex from '@/components/sysMenu/Category.vue'
import SysMenuUpdate from '@/components/sysMenu/UpdateCategory.vue'

const routes = [
	{path: '/', redirect: '/admin/login'},
	{path: '/:pathMatch(.*)', redirect: '/404'},
	{path: '/404', name: '/404', component: NotFound , meta: {template: 'empty'}},
	{path: '/admin/login/:p(\\d+)?', name: 'adminLogin', component: AdminLogin , meta: {template: 'empty'}},
	{path: '/admin/add', name: 'adminAdd', component: AdminAdd , meta: {template: 'default'}},
	{path: '/admin/index', name: 'adminIndex', component: AdminIndex, meta: {template: 'default'}},
	{path: '/admin/selfUpdate', name: 'adminSelfUpdate', component: AdminSelfUpdate, meta: {template: 'default'} },
	{path: '/admin/update/:id', name: 'adminUpdate', component: AdminUpdate, meta: {template: 'default'} },

	{path: '/adminAco/add/:pid(\\d+)?', name: 'aminAcoAdd', component: AdminAcoAdd, meta: {template: 'default'} },
	{path: '/adminAco/index', name: 'adminAcoIndex', component: AdminAcoIndex, meta: {template: 'default'} },
	{path: '/adminAco/update/:id', name: 'adminAcoUpdate', component: AdminAcoUpdate, meta: {template: 'default'} },

	{path: '/adminAro/index', name: 'adminAroIndex', component: AdminAroIndex, meta: {template: 'default'} },
	{path: '/adminAro/add', name: 'adminAroAdd', component: AdminAroAdd, meta: {template: 'default'} },
	{path: '/adminAro/update/:id/:alias', name: 'adminAroUpdate', component: AdminAroUpdate, meta: {template: 'default'} },
	{path: '/adminAro/allowed/:id/:alias', name: 'adminAroAllowed', component: AdminAroAllowed , meta: {template: 'default'}},
	{path: '/adminAro/denied/:id/:alias', name: 'adminAroDenied', component: AdminAroDenied, meta: {template: 'default'} },

	{path: '/hardConfig/loginControl', name: 'loginControl', component: LoginControl, meta: {template: 'default'} },
	{path: '/common/warning/:msg', name: 'warning', component: Warning , meta: {template: 'empty'} },

	{path: '/contract/category', name: 'ContractCategories', component: ContractCategories, meta: {template: 'default'} },
	{path: '/contract/addCategory/:pid(\\d+)?', name: 'ContractCategoryAdd', component: ContractCategoryAdd, meta: {template: 'default'} },
	{path: '/contract/updateCategory/:id', name: 'ContractCategoryUpdate', component: ContractCategoryUpdate, meta: {template: 'default'} },
	{path: '/contract/add', name: 'ContractAddContent', component: ContractAddContent, meta: {template: 'default'} },
	{path: '/contract/update/:id(\\d+)?', name: 'ContractUpdateContent', component: ContractUpdateContent, meta: {template: 'default'} },
	{path: '/contract/index', name: 'ContractIndex', component: ContractIndex, meta: {template: 'default'} },

	{path: '/product/category', name: 'ProductCategories', component: ProductCategories, meta: {template: 'default'} },
	{path: '/product/addCategory/:pid(\\d+)?', name: 'ProductCategoryAdd', component: ProductCategoryAdd, meta: {template: 'default'} },
	{path: '/product/updateCategory/:id', name: 'ProductCategoryUpdate', component: ProductCategoryUpdate, meta: {template: 'default'} },
	{path: '/product/add', name: 'ProductAdd', component: ProductAdd, meta: {template: 'default'} },
	{path: '/product/update/:id(\\d+)?', name: 'ProductUpdate', component: ProductUpdate, meta: {template: 'default'} },
	{path: '/product/view/:id(\\d+)?', name: 'ProductView', component: ProductView, meta: {template: 'default'} },
	{path: '/product/index', name: 'ProductIndex', component: ProductIndex, meta: {template: 'default'} },
	{path: '/product/list', name: 'ProductList', component: ProductList, meta: {template: 'default'} },

	{path: '/department/index', name: 'DepartmentIndex', component: DepartmentIndex, meta: {template: 'default'} },
	{path: '/department/staffs/:id(\\d+)?/:manager', name: 'DepartmentStaffs', component: DepartmentStaffs, meta: {template: 'default'} },
	{path: '/department/myStaffs', name: 'DepartmentMyStaffs', component: DepartmentMyStaffs, meta: {template: 'default'} },

	{path: '/customer/add', name: 'CustomerAdd', component: CustomerAdd, meta: {template: 'default'} },
	{path: '/customer/update/:id(\\d+)?', name: 'CustomerUpdate', component: CustomerUpdate, meta: {template: 'default'} },
	{path: '/customer/view/:id(\\d+)?', name: 'CustomerView', component: CustomerView, meta: {template: 'default'} },
	{path: '/customer/viewPlus/:id(\\d+)?', name: 'CustomerViewPlus', component: CustomerViewPlus, meta: {template: 'default'} },
	{path: '/customer/timeline/:id(\\d+)?/:wechat', name: 'CustomerTimeline', component: CustomerTimeline, meta: {template: 'default'} },
	{path: '/customer/myCustomers', name: 'CustomerMine', component: CustomerMine, meta: {template: 'default'} },
	{path: '/customer/departmentCustomers', name: 'CustomerDepartment', component: CustomerDepartment, meta: {template: 'default'} },
	{path: '/customer/customers', name: 'Customers', component: Customers, meta: {template: 'default'} },
	{path: '/customer/logs', name: 'CustomerLogs', component: CustomerLogs, meta: {template: 'default'} },
	{path: '/customer/finder', name: 'CustomerFinder', component: CustomerFinder, meta: {template: 'default'} },

	{path: '/order/add/:wechat?', name: 'OrderAdd', component: OrderAdd, meta: {template: 'default'} },
	{path: '/order/myOrders', name: 'OrderMines', component: OrderMines, meta: {template: 'default'} },
	{path: '/order/departmentOrders', name: 'OrderDepartments', component: OrderDepartments, meta: {template: 'default'} },
	{path: '/order/orders', name: 'Orders', component: Orders, meta: {template: 'default'} },
	{path: '/order/update/:id(\\d+)?', name: 'OrderUpdate', component: OrderUpdate, meta: {template: 'default'} },
	{path: '/order/view/:id(\\d+)?', name: 'OrderView', component: OrderView, meta: {template: 'default'} },
	{path: '/order/viewPlus/:id(\\d+)?', name: 'OrderViewPlus', component: OrderViewPlus , meta: {template: 'default'}},
	{path: '/order/logs', name: 'OrderLogs', component: OrderLogs , meta: {template: 'default'}},

	{path: '/statistic/all', name: 'StatisticAll', component: StatisticAll, meta: {template: 'default'} },
	{path: '/statistic/department', name: 'StatisticDepartment', component: StatisticDepartment, meta: {template: 'default'} },
	{path: '/statistic/mine', name: 'StatisticMine', component: StatisticMine, meta: {template: 'default'}},

	{path: '/sysMenu/add/:pid(\\d+)?', name: 'SysMenuAdd', component: SysMenuAdd, meta: {template: 'default'} },
	{path: '/sysMenu/index', name: 'SysMenuIndex', component: SysMenuIndex, meta: {template: 'default'} },
	{path: '/sysMenu/update/:id', name: 'SysMenuUpdate', component: SysMenuUpdate, meta: {template: 'default'} },

]

const router = createRouter({
    history: createWebHashHistory(),
    routes
})

export default router
