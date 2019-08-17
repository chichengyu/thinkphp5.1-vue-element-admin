import types from './types.js'

// 生成左侧菜单数据
const getMenuList = (routesList,nowRole) => {
	let siderList = [];
	for (let key in routesList) {
		let item = routesList[key];
		// if (item.isShowMenu === false) {
		// 	continue;
		// }
		if ((item.isMenu === false) || (item.meta && item.meta.isShowMenu === false)) {
			continue;
		}
		const sider = {
			name:'',
			icon:'',
			path:'',
		};
		if (item.meta && item.meta.icon && item.meta.title && item.path){
			sider.name = item.meta.title;
			sider.icon = item.meta.icon;
			sider.path = item.path;
			// if (nowRole != 'admin' && !item.children && !item.isShowMenu){
			if (nowRole != 1 && !item.children && item.meta && item.meta.isShowMenu === false){
				sider.name = '';
				sider.icon = '';
				sider.path = '';
			}
		}
		// 判断是否有子级
		let childItem = item.children;
		if (childItem) {
			if (childItem.length == 1 && childItem[0].path === 'index'){
				childItem = childItem[0];
				// isShowMenu 为 undefined 表示是公开路由生成菜单，为 true 表示有权限生成菜单，为 false 表示没有权限不生成菜单
				if (childItem.meta && childItem.meta.icon && childItem.meta.title && childItem.path) {
					// if (nowRole == 'admin' || (childItem.isShowMenu!==false)||(childItem.isShowMenu===true)){
					if (nowRole == 1 || (childItem.meta && (childItem.meta.isShowMenu!==false)||(childItem.meta.isShowMenu===true))){
						sider.name = childItem.meta.title;
						sider.icon = childItem.meta.icon;
						sider.path = item.redirect;
					}
				}
			} else {
				let childrenSider = getMenuList(item.children,nowRole);
				if (childrenSider.length > 0){
					for(let key in childrenSider){
						childrenSider[key].path = item.path + '/' + childrenSider[key].path;
					}
					sider.children = childrenSider;
				}else {
					sider.name = '';
					sider.icon = '';
					sider.path = '';
				}
			}
		}
		sider.name && sider.icon && sider.path && siderList.push(sider);
	}
	return siderList;
};

const mutations = {
	[types.SET_TOKEN] (state,isAuthToken) {
		if (isAuthToken){
			state.isAuthToken = isAuthToken;
		}else{
			state.isAuthToken = false;
		}
	},
	[types.SET_USER] (state,userInfo) {
		if (userInfo){
			state.userInfo = userInfo;
		}else{
			state.userInfo = {};
		}
	},
	// // 合并过滤后的路由新数组，并更新获取权限状态
	[types.SET_NEWROUTES] (state,newRoutes) {
		// 合并过滤后的路由新数组
		state.routes = state.routes.concat(newRoutes);
		// state.routes = newRoutes.concat(state.routes);
		// 更新获取状态
		state.isAuthToken = true;
	},
	// 左侧菜单列表
	[types.SET_SIDERLIST] (state,siderList) {
		if(!siderList){
			return state.siderList = siderList;
		}
		// siderList = getMenuList(siderList);
		siderList = getMenuList(siderList,state.userInfo.roles);
		if (Array.isArray(siderList) && siderList.length > 0) {
			state.siderList = siderList;
		}else {
			state.siderList = [];
		}
	},
	// 存放用户组 如：['admin','editor']
	// [types.SET_RULES] (state,rules) {
	// 	if (rules) {
	// 		state.rules = rules;
	// 	}else {
	// 		state.rules = [];
	// 	}
	// }
}

export default mutations
