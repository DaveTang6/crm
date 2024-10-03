import { createApp } from 'vue'
import router from '@/router/index.js'
import store from '@/store/index.js'
import App from './App.vue'

import ElementPlus from 'element-plus'
import 'element-plus/lib/theme-chalk/index.css'

import MainTitle from '@/components/common/MainTitle.vue'
import MainBody from '@/components/common/MainBody.vue'
import MainRow from '@/components/common/MainRow.vue'
import MainContainer from '@/components/common/MainContainer.vue'
import OperationContainer from '@/components/common/OperationContainer.vue'
import SearcherContainer from '@/components/common/SearcherContainer.vue'

const app = createApp(App)
app.component('MainTitle', MainTitle)
app.component('MainBody', MainBody)
app.component('MainRow', MainRow)
app.component('MainContainer', MainContainer)
app.component('OperationContainer', OperationContainer)
app.component('SearcherContainer', SearcherContainer)

store.dispatch('getAuth')

app.use(router)
app.use(store)
app.use(ElementPlus)

app.mount('#app')
