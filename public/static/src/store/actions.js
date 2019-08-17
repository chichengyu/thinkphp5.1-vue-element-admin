import types from './types.js'
// import {getSiderAuth} from '@/api/admin/sider.js'
// import {getUserInfo} from '@/api/admin/user.js'
import {routesMap} from '@/router/routes.js'
import rules from '@/router/rules.js'

/*
	通过meta.role判断是否与当前用户权限匹配
	@param rules routesMap中每一个路由对象
	@param roles 当前用户所属于的用户组
*/
const hasAccess = (route,rulesIds) => {
	return rules.some(el => {
		if (route.name == el.name && rulesIds.some(item=> el.id==item)){
			return true;
		}
		return false;
	});

	// if (route.meta && route.meta.rules) {
	// 	// 验证是否有权限
	// 	// return roles.some(role => route.meta.rules.includes(roles));
	// 	return route.meta.rules.includes(roles);
	// } else {
	// 	// 没有 meta 属性  说明是开放路由
	// 	return true;	
	// }
}
// 方法二 递归筛选路由
const getAccessRoutes = (routesMap,rulesIds) => {
	return routesMap.filter(item => {
		if (item.children && Array.isArray(item.children)) {
			 item.children = item.children.map(item => {
				(!item.meta) && (item.meta = {isShowMenu:false});
				if (hasAccess({...item},rulesIds)) {
					item.meta.isShowMenu = true;
				}else {
					item.meta.isShowMenu = false;
				}
				 return item;
			 });
			(item.children.every(item => item.meta.isShowMenu === false)) && (!(item.meta) && (item.meta = {isShowMenu:false}));
			return item.children.length;
		}
	});
};

const actions = {
	setAuthToken ({commit},isAuthToken) {
		commit(types.SET_TOKEN,isAuthToken);
	},
	setUserInfo ({commit},userInfo){
		commit(types.SET_USER,userInfo);
	},
	clearLoginOut ({commit}) {
		commit(types.SET_TOKEN,null);
		commit(types.SET_USER,null);
		// commit(types.SET_RULES,null);
		commit(types.SET_SIDERLIST,null);
	},
	// 左侧菜单列表
	setSiderList ({commit},siderList) {
		commit(types.SET_SIDERLIST,siderList);
	},
	// 获取左侧菜单权限
	// authorization ({commit}){
	// 	return new Promise((resolve,reject) => {
	// 		getSiderAuth().then(res => {
	// 			if (res.code == 401) {
	// 				reject(new Error('token valied')); 
	// 			} else {
	// 				// store.dispatch('setAuthToken');
	// 				commit(types.SET_RULES,res.data.rules);
	// 				resolve(res);
	// 			}
	// 		}).catch(error => {
	// 			reject(error);
	// 		});
	// 	})
	// },
	// 过滤左侧菜单
	// concatRlues ({commit},rules) {
	// 	rules = rules.data.rules;
	// 	return new Promise((resolve,reject) => {
	// 		try {
	// 			let routerList = [];
	// 			// 如果全部为 true,表示可访问所有
	// 			if (Object.entries(rules).every(item => item[1])) {
	// 				routerList = routesMap;
	// 			} else {
	// 				// 进行路由筛选
	// 				routerList = getAccessRouterList(routesMap,rules);
	// 			}
	// 			commit(types.SET_NEWROUTES,routerList);
	// 			resolve(this.getters.routes);
	// 		} catch(e) {
	// 			console.log('err')
	// 			console.log(e);
	// 			reject(e);
	// 		}
	// 	});
	// }
	// 方法二 获取用户信息
	getUserInfo ({commit},userInfo) {
		return new Promise((resolve,reject) => {
			// getUserInfo().then(userInfo => {
			// 	if (userInfo.code == 401) {
			// 		reject(new Error('token valied')); 
			// 	} else {
			// 		const data = userInfo.data;
			// 		if (data.roles && data.roles.length > 0) {
			// 			commit(types.SET_RULES,userInfo.data.roles);
			// 		} else {
			// 			reject('getInfo: roles must be a non-null array !')
			// 		}
			// 		resolve(userInfo);
			// 	}
			// }).catch(error => {
			// 	console.log('getUserInfo',error);
			// 	reject(error);
			// });
			try {
				const roles = userInfo.roles;
				if (roles && (roles == 1 || roles.length > 0)) {
					// Array.isArray(roles)||(userInfo.roles = [roles]);
					userInfo.roles = [roles];
					// commit(types.SET_RULES,userInfo.roles);
					commit(types.SET_USER,userInfo);
				} else {
					reject('getInfo: roles must be a non-null array !')
				}
				resolve(userInfo);
			} catch(e) {
				console.log('getUserInfo',error);
				reject(error);
			}
		});
	},
	// 方法二过滤 左侧菜单路由
	concatRlues ({commit},userInfo) {
		return new Promise((resolve,reject) => {
			try {
				let routerList = [];
				if (userInfo.roles.includes(1)) {
			 		routerList = routesMap;
			 	} else {
			 		routerList = getAccessRoutes(routesMap,userInfo.rules);
				}
			 	commit(types.SET_NEWROUTES,routerList);
			 	// 生成左侧菜单
				commit(types.SET_SIDERLIST,this.getters.routes);
				resolve(this.getters.routes);
			 } catch(error) {
			 	console.log('concatRlues',error);
			 	reject(error);
			 }
		});
	}
};

export default actions