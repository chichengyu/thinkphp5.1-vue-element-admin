(window.webpackJsonp=window.webpackJsonp||[]).push([[2],{"4jbn":function(t,e,i){"use strict";i.r(e);var o=i("cKSZ"),n=i("IWVv");for(var r in n)"default"!==r&&function(t){i.d(e,t,function(){return n[t]})}(r);i("LHhA");var u=i("KHd+"),a=Object(u.a)(n.default,o.a,o.b,!1,null,"68d0bd9e",null);e.default=a.exports},IWVv:function(t,e,i){"use strict";i.r(e);var o=i("JZCs"),n=i.n(o);for(var r in o)"default"!==r&&function(t){i.d(e,t,function(){return o[t]})}(r);e.default=n.a},Iq0j:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o,n=i("4jbn"),r=(o=n)&&o.__esModule?o:{default:o};e.default={name:"editor",components:{WangEditor:r.default}}},JZCs:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var o,n=i("GguQ"),r=(o=n)&&o.__esModule?o:{default:o};e.default={name:"wangEditor",data:function(){return{editor:null,content:"",style:{height:this.height+"px"}}},props:{uploadUrl:{type:String,default:""},value:{type:String,default:""},height:{type:Number,default:200},isClear:{type:Boolean,default:!1},isdisable:{type:Boolean,default:!0},debug:{type:Boolean,default:!1}},watch:{isClear:function(t){t&&(this.editor.txt.clear(),this.content=null)},value:function(t){t!==this.editor.txt.html()&&this.editor.txt.html(this.value)}},mounted:function(){this.setEditor(),this.editor.txt.html(this.value)},methods:{setEditor:function(){var e=this;this.editor=new r.default(this.$refs.toolbar,this.$refs.editor),this.editor.customConfig.zIndex=0,this.editor.customConfig.uploadFileName="file",this.editor.customConfig.onchangeTimeout=1,this.editor.customConfig.uploadImgMaxLength=5,this.editor.customConfig.uploadImgMaxSize=2097152,this.editor.customConfig.uploadImgTimeout=18e4,this.editor.customConfig.uploadImgHeaders={token:this.$store.getters.userInfo.token},this.editor.customConfig.menus=["head","bold","fontSize","fontName","italic","underline","strikeThrough","foreColor","backColor","link","list","justify","quote","emoticon","image","table","video","code","undo","redo","fullscreen"],""!==this.uploadUrl?this.editor.customConfig.uploadImgServer=this.uploadUrl:this.editor.customConfig.uploadImgShowBase64=!0,this.editor.customConfig.uploadImgHooks={fail:function(t,e,i){},success:function(t,e,i){},timeout:function(t,e){},error:function(t,e){},customInsert:function(t,e,i){if(e.data&&e.data.length)for(var o=0;o<e.data.length;o++){t(e.data[o].ivew_path)}else t(e.ivew_path)}},this.editor.customConfig.onchange=function(t){e.content=t,e.$emit("change",e.content)},this.debug&&(this.editor.customConfig.debug=location.href.indexOf("wangeditor_debug_mode=1")),this.editor.create(),this.editor.$textElem.attr("contenteditable",this.isdisable)}}}},LHhA:function(t,e,i){"use strict";var o=i("OXrm");i.n(o).a},OXrm:function(t,e,i){},UtNH:function(t,e,i){"use strict";function o(){var t=this.$createElement,e=this._self._c||t;return e("div",{attrs:{id:"editor"}},[e("wang-editor",{attrs:{height:350}})],1)}var n=[];i.d(e,"a",function(){return o}),i.d(e,"b",function(){return n})},X6zU:function(t,e,i){"use strict";i.r(e);var o=i("Iq0j"),n=i.n(o);for(var r in o)"default"!==r&&function(t){i.d(e,t,function(){return o[t]})}(r);e.default=n.a},cKSZ:function(t,e,i){"use strict";function o(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"editor"},[e("div",{ref:"toolbar",staticClass:"toolbar"}),this._v(" "),e("div",{ref:"editor",staticClass:"text",style:this.style})])}var n=[];i.d(e,"a",function(){return o}),i.d(e,"b",function(){return n})},qquq:function(t,e,i){"use strict";i.r(e);var o=i("UtNH"),n=i("X6zU");for(var r in n)"default"!==r&&function(t){i.d(e,t,function(){return n[t]})}(r);var u=i("KHd+"),a=Object(u.a)(n.default,o.a,o.b,!1,null,"75865a98",null);e.default=a.exports}}]);