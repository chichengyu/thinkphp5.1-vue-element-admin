export default [
    /************************************************************************************
     ************************ 权限映射文件，生成左侧菜单与权限按钮 **************************
     ******************* (与数据库权限id进行映射，数字都是数据库权限id) *********************
     ************************** (且name值必须与路由name值一致) ****************************
     ************************************************************************************/
    {id:2,title:'车辆列表',name:'car',rulesBtn:{add:40,edit:42,del:43,attr:44,export:41}},
    {id:4,title:'购物车列表',name:'shopcar',rulesBtn:{}},
    {id:8,title:'订单列表',name:'order',rulesBtn:{add:9,del:16}},
    {id:12,title:'咨询列表',name:'consult',rulesBtn:{add:13,edit:14,relay:15,del:16}},
    {id:18,title:'用户组列表',name:'groups',rulesBtn:{add:19,edit:20,del:21,auth:22}},
    {id:24,title:'权限列表',name:'rule',rulesBtn:{add:25,edit:26,del:27}},
    {id:29,title:'资讯列表',name: 'message',rulesBtn:{add:30,edit:31,del:32}},
    {id:34,title:'员工列表',name: 'personnel',rulesBtn:{add:35,edit:36,del:37}},
]