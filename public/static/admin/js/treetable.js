(window.webpackJsonp=window.webpackJsonp||[]).push([[11],{"/IXo":function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var n,i=a("m3Je"),r=(n=i)&&n.__esModule?n:{default:n};e.default={name:"Table",components:{TableData:r.default},data:function(){return{visible:!1,totalPage:100,currentPage:1,tableData:{loading:!1,tableData:[],tableLabel:[],tableOption:{label:"操作",width:230,buttons:[{prop:"detail",title:"查看",methods:function(t){console.log(t)}},{prop:"edit",title:"编辑",type:"primary",methods:function(t){console.log(t)}},{prop:"del",title:"删除",type:"danger",methods:{ok:function(t){console.log("确认删除",t)},cancel:function(t){console.log("取消删除",t)}}}]},page:{align:"right"},sortChange:function(t){console.log(t)}}}},created:function(){this.tableData.tableLabel=this.labelInit(),this.tableData.tableData=this.tableDataInit(),this.handlePage()},methods:{labelInit:function(){return[{prop:"id",title:"ID",type:"index",fixed:!0,width:80},{prop:"name",title:"名称",width:100,hasChildren:!0,align:"center"},{prop:"date",title:"日期",formatter:function(t,e,a,n){return a+"--"+n}},{prop:"province",title:"省份"},{prop:"city",title:"城市"},{prop:"address",title:"地址",tooltip:!0},{prop:"zip",title:"邮编",sort:"custom"},{prop:"status",title:"状态",isSwitch:{change:function(t){console.log("switch开关",t)}}}]},tableDataInit:function(){return[{id:1,date:"2016-05-02",name:"王小虎",province:"上海",city:"普陀区",address:"上海市普陀区金沙江路 1518 弄",zip:200333,status:0},{id:2,date:"2016-05-04",name:"王小虎",province:"上海",city:"普陀区",address:"上海市普陀区金沙江路 1517 弄",zip:200333,status:1},{id:3,date:"2016-05-01",name:"王小虎",province:"上海",city:"普陀区",address:"上海市普陀区金沙江路 1519 弄",zip:200333,status:0},{id:4,date:"2016-05-03",name:"王小虎",province:"上海",city:"普陀区",address:"上海市普陀区金沙江路 1516 弄",zip:200333,status:0,children:[{id:31,date:"2016-05-01",level:"2",name:"1111",province:"上海",city:"普陀区",address:"上海市普陀区金沙江路 1519 弄",status:0,zip:200333},{id:32,date:"2016-05-01",level:"2",name:"2222",province:"上海",city:"普陀区",address:"上海市普陀区金沙江路 1519 弄",status:0,zip:200333}]},{id:5,date:"2016-05-01",name:"王小5",province:"上海",city:"普陀区",address:"上海市普陀区金沙江路 1519 弄",zip:200333,status:0},{id:6,date:"2016-05-03",name:"王小虎",province:"上海",city:"普陀区",address:"上海市普陀区金沙江路 1516 弄",zip:200333,status:0,children:[{id:41,date:"2016-05-01",level:"2",name:"3333",province:"上海",city:"普陀区",address:"上海市普陀区金沙江路 1519 弄",status:0,zip:200333},{id:42,date:"2016-05-01",level:"2",name:"4444",province:"上海",city:"普陀区",address:"上海市普陀区金沙江路 1519 弄",status:0,zip:200333},{id:43,date:"2016-05-01",level:"2",name:"5555",province:"上海",city:"普陀区",address:"上海市普陀区金沙江路 1519 弄",status:0,zip:200333}]}]},handlePage:function(){var e=this;this.tableData.page.total=60,this.tableData.page.currentChange=function(t){console.log("当前页",t),e.tableData.loading=!0,setTimeout(function(){e.tableData.loading=!1},1500)}}}}},"8CjA":function(t,e,a){"use strict";function n(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"table"},[a("table-data",{attrs:{data:t.tableData}})],1)}var i=[];a.d(e,"a",function(){return n}),a.d(e,"b",function(){return i})},"9PBc":function(t,e,a){},L78P:function(t,e,a){"use strict";var n=a("9PBc");a.n(n).a},QBL0:function(t,e,a){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default={name:"Table",props:{data:{type:Object,default:function(){}}},data:function(){return{}},created:function(){i.treeTableXcode(this.data.tableData),this.data.hasOwnProperty("sortChange")||(this.data.sortChange=function(t){})},methods:{handleOk:function(t,e){t.methods.ok(e),e._self.$refs["popover"+e.$index].$refs.popper.click()},handleCancel:function(t,e){e._self.$refs["popover"+e.$index].$refs.popper.click(),t.methods.cancel(e)},treeClick:function(t,e){t.open?this.collapse(t,e):this.expand(t,e)},expand:function(t,e){var a=this;if(!t.children)return e;this.data.tableData.some(function(t,e){t.xcode.includes("-")&&(e=t.xcode.substr(0,1),a.collapse(a.data.tableData[e],e))});for(var n=0;t.children&&n<t.children.length;n++){var i=t.children[n];this.data.tableData.splice(++e,0,i),i.children&&0<i.children.length&&i.open&&(e=this.expand(i,e))}return t.open=!0,e},collapse:function(t,e){if(!t.children)return e;t.open=!1,this.data.tableData.splice(Number(e)+1,t.children.length)}}};var i={treeTableXcode:function(t,e){e=e||"";for(var a=0;a<t.length;a++){var n=t[a];n.xcode=e+a,n.children&&0<n.children.length&&i.treeTableXcode(n.children,n.xcode+"-")}}}},"W7/C":function(t,e,a){"use strict";a.r(e);var n=a("/IXo"),i=a.n(n);for(var r in n)"default"!==r&&function(t){a.d(e,t,function(){return n[t]})}(r);e.default=i.a},m3Je:function(t,e,a){"use strict";a.r(e);var n=a("o2Lu"),i=a("v47T");for(var r in i)"default"!==r&&function(t){a.d(e,t,function(){return i[t]})}(r);a("L78P");var o=a("KHd+"),l=Object(o.a)(i.default,n.a,n.b,!1,null,"599f9b5a",null);e.default=l.exports},o2Lu:function(t,e,a){"use strict";function n(){var n=this,t=n.$createElement,i=n._self._c||t;return i("div",{staticClass:"table"},[i("el-table",{directives:[{name:"loading",rawName:"v-loading",value:n.data.loading,expression:"data.loading"}],staticStyle:{width:"100%"},attrs:{border:"","empty-text":n.data.table_msg_empty,data:n.data.tableData,"default-sort":n.data.defaultSort},on:{"sort-change":n.data.sortChange}},[n._l(n.data.tableLabel,function(a,t){return[a.isSwitch?[i("el-table-column",{key:t,attrs:{type:a.type,fixed:a.fixed,prop:a.prop,label:a.title,width:a.width,"min-width":a.width,sortable:a.sort,formatter:a.formatter,"show-overflow-tooltip":a.tooltip,align:a.align||"left"},scopedSlots:n._u([{key:"default",fn:function(e){return[i("el-switch",{attrs:{disabled:a.isSwitch.disabled,"active-color":"#52BEA6","inactive-color":"#CACDD0","active-value":1,"inactive-value":0},on:{change:function(t){return a.isSwitch.change(e.row)}},model:{value:e.row.status,callback:function(t){n.$set(e.row,"status",t)},expression:"scope.row.status"}})]}}],null,!0)})]:[i("el-table-column",{key:t,attrs:{type:a.type,fixed:a.fixed,prop:a.prop,label:a.title,width:a.width,"min-width":a.width,sortable:a.sort,formatter:a.formatter,align:a.align||"left"},scopedSlots:n._u([{key:"default",fn:function(e){return[a.hasChildren&&e.row.children&&0<e.row.children.length?i("div",{staticStyle:{"margin-left":"-1.3em",cursor:"pointer"},on:{click:function(t){return n.treeClick(e.row,e.$index)}}},[e.row.open?i("i",{staticClass:"el-icon-arrow-down"}):i("i",{staticClass:"el-icon-arrow-right"}),n._v(" "),i("span",[n._v(n._s(e.row[a.prop]))])]):a.tooltip?i("div",[i("el-tooltip",{attrs:{placement:"top"}},[i("div",{attrs:{slot:"content"},slot:"content"},[n._v(n._s(e.row[a.prop]))]),n._v(" "),i("div",{staticStyle:{width:"100%",overflow:"hidden","white-space":"nowrap","text-overflow":"ellipsis"}},[n._v(n._s(e.row[a.prop]))])])],1):i("div",[n._v(n._s(e.row[a.prop]))])]}}],null,!0)})]]}),n._v(" "),n.data.tableOption?i("el-table-column",{attrs:{fixed:"right",label:n.data.tableOption.label,width:n.data.tableOption.width,align:n.data.tableOption.align||"center"},scopedSlots:n._u([{key:"default",fn:function(a){return[n.data.tableOption.buttons?n._l(n.data.tableOption.buttons,function(e,t){return"del"!=e.prop?i("el-button",{key:t,attrs:{type:e.type,size:"mini"},on:{click:function(t){return e.methods(a.row)}}},[n._v("\n                        "+n._s(e.title)+"\n                    ")]):i("el-popover",{ref:"popover"+a.$index,staticStyle:{"margin-left":"10px"},attrs:{placement:"top-end",width:"120"}},[i("div",{staticStyle:{"text-align":"center",margin:"0"}},[i("h4",{staticStyle:{"margin-top":".6rem"}},[i("i",{staticClass:"el-icon-warning",staticStyle:{"margin-right":"6px",color:"#ff9900"}}),n._v("你确定删除吗？")]),n._v(" "),i("el-button",{staticStyle:{padding:"4px 7px"},attrs:{type:"text",size:"mini"},on:{click:function(t){return n.handleCancel(e,a)}}},[n._v("取消")]),n._v(" "),i("el-button",{staticStyle:{padding:"4px 7px"},attrs:{type:"primary",size:"mini"},on:{click:function(t){return n.handleOk(e,a)}}},[n._v("确定")])],1),n._v(" "),i("el-button",{attrs:{slot:"reference",type:e.type,size:"mini"},slot:"reference"},[n._v("删除")])],1)}):n._e()]}}],null,!1,1966742643)}):n._e()],2),n._v(" "),i("el-pagination",{attrs:{align:n.data.page.align,total:n.data.page.total,"current-page":n.data.page.currentPage,background:"",layout:"prev, pager, next"},on:{"current-change":n.data.page.currentChange}})],1)}var i=[];a.d(e,"a",function(){return n}),a.d(e,"b",function(){return i})},v47T:function(t,e,a){"use strict";a.r(e);var n=a("QBL0"),i=a.n(n);for(var r in n)"default"!==r&&function(t){a.d(e,t,function(){return n[t]})}(r);e.default=i.a},zphg:function(t,e,a){"use strict";a.r(e);var n=a("8CjA"),i=a("W7/C");for(var r in i)"default"!==r&&function(t){a.d(e,t,function(){return i[t]})}(r);var o=a("KHd+"),l=Object(o.a)(i.default,n.a,n.b,!1,null,"2b084242",null);e.default=l.exports}}]);