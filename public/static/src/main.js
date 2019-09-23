// 入口文件


// 引入包
// import Vue from 'Vue'
import Vue from 'vue'
import store from './store/index.js'
import App from './App.vue'
import router from './router/router.js'
import directives from './directtives'
import utils from './lib/utils'

// https://www.thinkcmf.com/font/font_awesome/icons.html
import 'normalize.css'
import './assets/style/globalstyle.css'

Vue.use(directives);
Vue.use(utils);

Vue.config.productionTip = false;
const vm = new Vue({
	el:'#app',
    store,
	router,
	render:c => c(App),
});
