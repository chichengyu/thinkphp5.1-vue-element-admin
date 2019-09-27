<template>
    <el-container style="height: 100%; border: 1px solid #eee">
        <el-aside class="sider" width="auto" style="background-color: #515a6e">
            <el-menu v-if="siderBarList.length"
                    :collapse="isCollapse"
                    :default-active="menuIndex"
                    class="el-menu-vertical-demo"
                    @select="handleSelectMenu"
                    background-color="#515a6e"
                    text-color="rgb(203,199,206)"
                    active-text-color="#409eff">
                <template v-for="(item,key) in siderBarList">
                    <el-submenu :index="String(key)" v-if="item.children">
                        <template slot="title">
                            <i :class="item.icon"></i>
                            <span>{{ item.name }}</span>
                        </template>
                        <router-link tag="div" v-for="(subItem,index) in item.children" :to="subItem.path" :key="index">
                            <el-menu-item :index="String(key+'-'+index)">{{ subItem.name }}</el-menu-item>
                        </router-link>
                    </el-submenu>
                    <router-link tag="div" v-else :to="item.path">
                        <el-menu-item :index="String(key)" :key="key">
                            <i :class="item.icon"></i>
                            <span slot="title">{{ item.name }}</span>
                        </el-menu-item>
                    </router-link>
                </template>
            </el-menu>
        </el-aside>
        <el-container>
            <el-header style="color: #515a6e;text-align: right">
                <div class="left" style="float: left;display: flex;">
                    <div @click.stop="isCollapse=!isCollapse"><i :class="iconClassName" style="font-size: 22px;"></i></div>
                    <div style="margin-left: 22px">
                        <h4 style="margin: -4px 0 0;">{{ title }}</h4>
                    </div>
                </div>
                <personal></personal>
            </el-header>
            <div class="tabBtn">
                <template v-if="tabBtnList.length" v-for="(item,key) in tabBtnList" >
<!--                <router-link tag='span' class="txt" :to="item.path">-->
                    <el-tag size="small" :key="key" :class="item.tabBtnActive?'tabBtnActive':''" @click.native="handleBtnClickEvent(item,key)" @close="handleBtnCloseEvent(key)" closable>
                        {{ item.name }}
                    </el-tag>
<!--                </router-link>-->
                </template>
            </div>
            <el-main>
                <fade-in>
                    <router-view slot='fadeIn'/>
                </fade-in>
            </el-main>
            <el-footer align="center" style="background:#f5f7f9;line-height: 60px;font-size: 14px;">
                <span>{{ title }}</span>
            </el-footer>
        </el-container>
    </el-container>
</template>

<script>
import FadeIn from '@/components/fadeIn/FadeIn.vue'
import Personal from '@/views/components/personal'
export default {
    name:'Layout',
    components:{
        FadeIn,
        Personal,// 个人中心
    },
    data() {
        return {
            title:'xxxxxxxxxxx系统',
            isCollapse:false,
            menuIndex:'0',
            siderBarList:[],
            tabBtnList:[]
        }
    },
    beforeCreate () {
        let first = this.$store.getters.siderList[0];
        let url  = '';
        if(first.children){
            url = first.children[0].path;
        }else {
            url = first.path;
        }
        // 登陆成功默认跳转地址
        this.$router.push(url);
    },
    created() {
        this.siderBarList = this.$store.getters.siderList;
        setTimeout(() => {
            let first = this.siderBarList[0];
            if (first.children){
                first = first.children[0];
                this.menuIndex = '0-0';
            }else {
                first = this.siderBarList[0];
                this.menuIndex = '0';
            }
            first.tabBtnActive = true;
            this.tabBtnList.push(first);
        });
    },
    computed:{
        iconClassName(){
            return this.isCollapse?'el-icon-s-unfold':'el-icon-s-fold';
        }
    },
    methods: {
        handleSelectMenu (index,indexPath) {
            this.menuIndex = index;
            // 二级菜单
            let currentNav = null;
            if (String(index).includes('-')){
                index = index.split('-');
                if (Array.isArray(index)){
                    currentNav = this.siderBarList[index[0]].children[index[1]];
                }
            }else {
                currentNav = this.siderBarList[index];
            }
            if (currentNav){
                let key = this.tabBtnList.findIndex(item => (item.name==currentNav.name)&&(item.path==currentNav.path));
                this.tabBtnList.map(item => item.tabBtnActive=false);
                if (key === -1) {
                    let tabBtnBar = {
                        name:'',
                        path:'',
                        tabBtnActive:false,
                    };
                    tabBtnBar.name = currentNav.name;
                    tabBtnBar.path = currentNav.path;
                    tabBtnBar.tabBtnActive = true;
                    this.tabBtnList.push(tabBtnBar);
                }else {
                    this.tabBtnList[key].tabBtnActive = true;
                }
            }
        },
        // 找到对应选中的左侧菜单高亮
        siderBarListFindSelected(currentItem){
            this.siderBarList.find((item,key) => {
                if (item.children){
                    return item.children.find((subItem,index) => {
                        if((subItem.name == currentItem.name)&&(subItem.path == currentItem.path)){
                            this.menuIndex = key + '-' + index;
                            return true;
                        }
                    }) && true;
                }else {
                    if((item.name == currentItem.name)&&(item.path == currentItem.path)){
                        this.menuIndex = String(key);
                        return true;
                    }
                }
            });
        },
        handleBtnClickEvent(currentBtnItem,index){
            this.tabBtnList.map(item => item.tabBtnActive=false);
            this.tabBtnList[index].tabBtnActive = true;
            this.siderBarListFindSelected(currentBtnItem);
            this.$router.push(currentBtnItem.path);
        },
        handleBtnCloseEvent (index){
            if (this.tabBtnList.length <= 1)return this.warning('已达上限，不能关闭！');
            this.tabBtnList.splice(index,1);
            this.siderBarListFindSelected(this.tabBtnList[this.tabBtnList.length-1]);
            this.tabBtnList.map(item => item.tabBtnActive=false);
            this.tabBtnList[this.tabBtnList.length-1].tabBtnActive=true;
            this.$router.push(this.tabBtnList[this.tabBtnList.length-1].path);
        }
    }
};
</script>

<style lang="css" scoped>
.el-menu-vertical-demo:not(.el-menu--collapse) {
    width: 200px;
    min-height: 400px;
}
.sider{
    overflow: hidden;
}
.el-header {
    /*background-color: #B3C0D1;*/
    color: #333;
    line-height: 60px;
    border-bottom: 1px solid #f5f6f6;
}
.tabBtn{
    padding-top: 2px;
    padding-bottom: 2px;
    border-bottom: 1px solid #f5f6f6;
    box-shadow: 0 0 0 0 rgba(0,0,0,.5);
    user-select: none;
    /*box-shadow: 0 1px 3px 0 rgba(0,0,0,.12), 0 0 3px 0 rgba(0,0,0,.04);*/
}
.tabBtn .tabBtnActive,.el-tag:hover{
    background-color: #42b983 !important;
    color: #fff !important;
    cursor: pointer;
}
.el-tag{
    margin-left: 2px;
    margin-right: 2px;
    background-color: #fff;
    color: #515a6e !important;
    border-color: #eee !important;
    transition: all .5s;
}
.el-tag--small >>> .el-icon-close{
    right: -1px;
    color: #cacaca !important;
    transition: all .5s;
}
.el-tag >>> .el-tag__close:hover{
    background-color: #6b5b5b;
    color: #fff !important;
}
.el-main{
    padding: 5px 2px;
    background: #f5f7f9;
}
</style>