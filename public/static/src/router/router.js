import Vue from 'Vue'
import VueRouter from 'vue-router'
import ElementUI from 'element-ui'
import Storage from 'vue-ls'
import NProgress from 'nprogress'
import store from '@/store/index.js'
import {routes} from './routes.js'
// import { userInfo } from '@/env.js'
import 'element-ui/lib/theme-chalk/index.css'
import 'nprogress/nprogress.css'

const options = {
    namespace: '',
    name: 'ls',
    storage: 'session',
};
NProgress.inc(0.2);
NProgress.configure({ easing: 'ease', speed: 500, showSpinner: false });
Vue.use(VueRouter);
Vue.use(ElementUI);
Vue.use(Storage,options);
Vue.prototype.$progress = NProgress;

const router = new VueRouter({
	scrollBehavior: () => ({ y: 0 }),
	routes
});

let hasAccess = (userInfo,meta) => {
	let roles = Array.isArray(userInfo.roles) ? userInfo.roles[0]:userInfo.roles;
	if (roles == 1) return true;// 超级管理员
	// isShowMenu不存在为 undefined 表示公开路由  为 true 表示有权限访问
	if (meta.isShowMenu !== false) return true;
};

router.beforeEach((to,from,next) => {
	NProgress.start();
	var isLogin = Vue.ls.get(userInfo);
	if (to.path == '/login' || to.path == '/register') {
		next();
	}else{
		if (isLogin) {
			if (!store.getters.isAuthToken) {
				store.dispatch('getUserInfo',isLogin).then(rlues => {
					store.dispatch('concatRlues',rlues).then(routes => {
						// 解决：动态挂载提示Duplicate named routes definition: { name: "home", path: "/home/index" }
						router.matcher = new VueRouter({mode: 'history'}).matcher;
						router.addRoutes(routes);
						next({ ...to, replace: true });
					}).catch(err => {
						next('/login');
					});
				}).catch(err => {
					Vue.ls.remove('userInfo');
					next('/login');
				});
			} else {
				if (hasAccess(store.getters.userInfo,to)) {
					next();
				} else {
					ElementUI.Message.error({message:'你没有权限访问',center: true});
					NProgress.done();
					next({path:'/401',replace:true});
				}
			}
		}else {
			ElementUI.Message.error({message:'请先登录',center: true});
			NProgress.done();
			next('/login');
		}
	}
});
router.afterEach((to,from) => {
	if (to.path === '*') {
		ElementUI.Message.error({message:'页面不存在',center: true});
	}
	NProgress.done();
});

export default router