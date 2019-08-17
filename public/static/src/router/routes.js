import Layout from '@/components/layout/layout.vue'
/**
 * 	注意：
 * 		权限是通过 rules.js 权限id映射文件来进行控制的，筛选时通过路由中的 name 属性来进行定位，所以 name 必须填写，且唯一
 *
 * 		isMenu  表示该路由仅仅是路由，而不生成菜单
 * 				默认所有路由都生成菜单，不想生成菜单直接添加属性 isMenu 为 false 即可
 * 			注意：1.不需要每个路由都添加 isMenu 属性，只需要给不需要生成菜单的路由添加该属性，需要生成菜单的路由不需要加该属性
 * 		         2.二级菜单 isMenu 必须写在 children 里的每条路由上，一级菜单写在 children 外面（当前路由即可）
 *
 */

// 所有人都可以访问
const routes = [
	{
		path:'/login',
		// name:'login',
		isMenu:false,
		component: () => import(/* webpackChunkName: "login" */ '@/views/login.vue')
	},
	{
		path:'/401',
		component: Layout,
		redirect:'/401/index',
		children:[
			{
				path:'index',
				component: () => import(/* webpackChunkName: "401" */ '@/views/error/401.vue')
			}
		]
	},
	{
		path:'/404',
		component: Layout,
		redirect:'/404/index',
		children:[
			{
				path:'index',
				component: () => import(/* webpackChunkName: "404" */ '@/views/error/404.vue')
			}
		]
	}
];

// 需要权限验证
const routesMap = [
	{
		path:'/home',
		component: Layout,
		redirect:'/home/index',
		children:[
			{
				path:'index',
				name:'home',
				meta:{
					title:'首页',
					icon:'el-icon-s-data',
				},
				component: () => import(/* webpackChunkName: "home" */ '@/views/components/index.vue')
			}
		]
	},
	{
		path:'/a',
		component: Layout,
		redirect:'/a/index',
		children:[
			{
				path:'index',
				name:'a',
				meta:{
					title:'aaaa',
					icon:'el-icon-s-data',
				},
				component: () => import(/* webpackChunkName: "a" */ '@/views/test/a.vue')
			}
		]
	},
	{
		path:'/b',
		component: Layout,
		redirect:'/b/index',
		children:[
			{
				path:'index',
				name:'b',
				meta:{
					title:'bbbbb',
					icon:'el-icon-s-data',
				},
				component: () => import(/* webpackChunkName: "b" */ '@/views/test/b.vue')
			}
		]
	},
	{
		path:'/c',
		component: Layout,
		redirect:'/c/index',
		children:[
			{
				path:'index',
				name:'c',
				meta:{
					title:'ccccc',
					icon:'el-icon-s-data',
				},
				component: () => import(/* webpackChunkName: "c" */ '@/views/test/c.vue')
			}
		]
	},
	{
		path:'/abc',
		component: Layout,
		meta:{
			title:'权限管理',
			icon:'el-icon-s-data',
		},
		children:[
			{
				path:'a',
				name:'abc-a',
				meta:{
					title:'用户组',
					icon:true,
				},
				component: () => import(/* webpackChunkName: "test-abc-a" */ '@/views/test/a.vue')
			},
			{
				path:'b',
				name:'abc-b',
				meta:{
					title:'权限列表',
					icon:true,
				},
				component: () => import(/* webpackChunkName: "test-abc-b" */ '@/views/test/b.vue')
			},
			{
				path:'c',
				name:'abc-c',
				meta:{
					title:'测试',
					icon:true,
				},
				component: () => import(/* webpackChunkName: "test-abc-c" */ '@/views/test/c.vue')
			}
		],
	},
	{path:'*',redirect:'/404',isMenu:false}
];

export {routesMap,routes}