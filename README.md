# thinkphp5.1-vue-element-admin

#### 介绍

基于thinkphp5.1、vue、element做的一个后台管理系统，默认打包出的是异步加载


还有一点需要注意的是，如果使用本项目的ThinkPHP5.1进行接口开发，值得注意的一点是，我已经封装好接管异常的处理了，只需要创建异常类文件继承封装的异常就可以了。异常目录 app/lib/excption 目录，这个异常类分两种，一种是客户端异常(BaseException),一种服务器异常（不需要管，接管的异常会自动处理），当需要在不同功能接口返回异常时，只需要创建对应的异常类来继承客户端异常(即 BaseException)即可。


#### 架构
架构说明

   + [Preview预览](http://xiaochiwz.gitee.io/thinkphp5.1-vue-ivew-admin)
   + [Element官网](https://element.eleme.cn/2.11/#/zh-CN/component/installation)
   + [ThinkPHP官网](http://www.thinkphp.cn/)
   + [ThinkPHP5.1手册](https://www.kancloud.cn/manual/thinkphp5_1/353946)
   + [Vue官网](https://cn.vuejs.org/v2/guide/)
   + [Webpack官网](https://www.webpackjs.com/)
   + [Easy Mock接口API](https://www.easy-mock.com/project/5bf4b1a323557c43607406bc)

#### 目录结构
   ThinkPHP的目录结构就不用多说了，要说的是结合Vue后的目录  
```  
     public目录  
     ├─preview          预览目录(安装之后直接删除)  
     ├─static           应用目录  
     │  ├─admin             后台目录（本后台管理系统主目录）  
     │  ├─home              前台目录  
     │  ├─src               模块目录  
     │  │  ├─api               接口请求目录  
     │  │  ├─assets            静态资源目录
     │  │  ├─common            公共目录(如：公共函数,可直接修改，不影响打包后的文件)    
     │  │  ├─components        组件目录  
     │  │  ├─config            配置目录  
     │  │  ├─lib               核心库目录  
     │  │  ├─router            路由目录  
     │  │  ├─store             vuex目录  
     │  │  ├─views             视图目录  
     │  │  ├─App.vue           根组件  
     │  │  ├─index.html        主页    
     │  │  ├─main.js           入口文件    
     │  │  └─ ...              
     │
```

#### Getting started
```
# clone the project
git clone https://github.com/chichengyu/thinkphp5.1-vue-element-admin.git

# install dependency
composer install  

# install dependency
npm install

# test development build
npm run build:dev

# build
npm run build

# watch
npm run watch
```
   

#### Build
```
# Dev server
npm run dev  

# test development build
npm run build:dev

# build  
npm run build

# watch
npm run watch
```

#### 安装异常
可能会出现的异常情况  
######路由报错  
```Uncaught (in promise) NavigationDuplicated {_name: "NavigationDuplicated", name: "NavigationDuplicated"}```，这是可能路由安装的是报错的版本，解决方法：
```
npm uninstall -S vue-router@3.0
```
重新安装一个稳定的版本

######该异常可能会出现
    ```
    npm ERR! path C:\Users\admin\Desktop\abc\node_modules\less
    npm ERR! code EISGIT
    npm ERR! git C:\Users\admin\Desktop\abc\node_modules\less: Appears to be a git repo or submodule.
    npm ERR! git     C:\Users\admin\Desktop\abc\node_modules\less
    npm ERR! git Refusing to remove it. Update manually,
    npm ERR! git or move it out of the way first.
    
    npm ERR! A complete log of this run can be found in:
    npm ERR!     C:\Users\admin\AppData\Roaming\npm-cache\_logs\2019-08-19T01_12_12_963Z-debug.log
    ```

#### 使用说明
ThinkPHP5.1、Vue、element的使用就不在此多说了，这里要说的是前后分离时的 **权限控制**，这里介绍两种方法，两种都需要前后配合  
   + [Vue-Router的meta元信息](https://router.vuejs.org/zh/guide/advanced/meta.html)  
        在需要验证的路由中加入 meta 属性 ``` meta:{rules:['admin','editor']} ```，如下：
      ```
        {  
	        path:'index',  
	        name:'adminIndex',  
	        meta:{rules:['admin','editor']},// 如此这样  
	        component: () => import(/* webpackChunkName: "login" */ '@/views/admin/components/index.vue')  
        }  
      ```
      admin、editor表示只有 admin/editor 两个用户组的用户能访问此条路由。  
         当用户登陆时返回的用户信息中需要有当前用户所属的用户组（键名：roles）  
         如：
      ```
       {
	        username: 'admin',
	        token: ' 5bf4b1a323557c43607406bc',
	        roles: ['admin']
       } 
      ```
   + [Vue-Router的name属性](https://router.vuejs.org/zh/guide/essentials/named-routes.html)  
        在需要验证的路由中加入 name 属性，且每个路由的 name 值必须是独一无二的，不能有相同值的，后台需要返回一个以所有路由中 **name** 值的对象规则，
      ```
        {
	        "rules": {
	            'index': true,
	            'adminIndex': true,
	            'table': true,
	            'form': true,
	            'keyboard': true,
	            'line': true,
	            'icons': true,
	            'mix': true,
	            'notAuth': false,
	            'found': false,
	        }
        }
      ```  
        然后在路由守卫里进行判断筛选出当前登陆用户的菜单动态挂载到路由上。  
        这里使用的是 meta 属性进行权限控制的，当然两种各有优劣势 

		如果需要粒度更细的权限，如：按钮权限，这里我在 ```src\router\rules.js```这个文件算是一个权限映射文件，只需要填写正确的权限id即可使用


#### License
[MIT](https://opensource.org/licenses/MIT)
