// 入口文件


// 引入包
// import Vue from 'Vue'
import Vue from 'vue'
import store from './store/index.js'
import App from './App.vue'
import router from './router/router.js'
import directives from './permission.js'
import utils from './lib/utils'

// import 'font-awesome/css/font-awesome.min.css' // https://www.thinkcmf.com/font/font_awesome/icons.html
import 'normalize.css'
import './assets/style/globalstyle.css'


Vue.use(directives);
Vue.use(utils);


// NProgress.inc(0.2)
// NProgress.configure({ easing: 'ease', speed: 500, showSpinner: false })

Vue.config.productionTip = false;
const vm = new Vue({
	el:'#app',
    store,
	router,
	render:c => c(App),
});
