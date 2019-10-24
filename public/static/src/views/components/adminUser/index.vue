<template>
    <div class="adminUser">
        <div class="header">
            <el-button v-has="'add'" type="primary" @click="handleTable(true,'showTableAdd')" >添加</el-button>
            <div class="search" style="display: inline-block">
                <el-input v-model="search.keywords" placeholder="请输入手机"></el-input>
            </div>
            <el-button type="primary" @click="handleSearch" >搜索</el-button>
        </div>
        <component-table :data="tableData">
            <template v-slot:button="{scope}">
                <el-button v-has="'passwrod'" v-if="scope.row.id>0" type="success" size="mini" @click="handleResetPassword(scope)">重置密码</el-button>
                <el-button v-has="'edit'" v-if="scope.row.id>0" type="primary" size="mini" @click="handleEdit(scope)">编辑</el-button>
                <component-popover v-has="'del'" :params="scope.row" @ok="handleOk" @cancel="handleCancel"></component-popover>
            </template>
        </component-table>

        <component-dialog v-if="show" :width="50" :title="optionText" :visible.sync="show" :footer="false">
            <table-add slot="dialog" v-if="showTable.showTableAdd" :groups="groups" @handleGetTableData="handleGetTableData"></table-add>
            <table-edit slot="dialog" ref="tableEdit" v-if="showTable.showTableEdit" :groups="groups" @handleGetTableData="handleGetTableData"></table-edit>
        </component-dialog>
    </div>
</template>

<script>
import tableAdd from './components/add.vue'
import tableEdit from './components/edit.vue'
import { getTableData,edit,password,del } from '@/api/adminUser'

export default {
    name: "adminUser",
    components:{
        tableAdd,
        tableEdit,
    },
    data () {
        return {
            show:false,
            showTable:{
                showTableAdd:false,
                showTableEdit:false,
            },
            currentPage:1,
            optionText:'',
            rules:null,
            groups:null,
            search:{
                keywords:''
            },
            // visible:false,
            tableData: {
                loading:false,
                // 请求回来的数据
                tableData:[],
                // 列
                tableLabel:[],
                // 操作
                tableOption:{
                    label:'操作',
                    width:250,
                    slot:true,
                    // buttons:[
                    //     {title:'重置密码',type:'success',style:(params,item) => params.row.id==1?{display:'none'}:'',click:(params,item) => {
                    //         this.confirm('你确定要重置密码?',()=>{
                    //             password(params.row.id).then(res => {
                    //                 if (res.data.code == 1){
                    //                     return this.success(res.data.msg);
                    //                 }
                    //                 return this.success(res.data.msg);
                    //             });
                    //         },()=>{
                    //             this.warning('取消重置密码');
                    //         });
                    //     }},
                    //     {title:'编辑',directives:[{name:'has',value:'edit'}],type:'primary',style:(params,item) => params.row.id==1?{display:'none'}:'',click: (params) => {
                    //         this.handleTable(true, 'showTableEdit', '编辑用户');
                    //         this.$nextTick(() => {
                    //             this.$refs.tableEdit && this.$refs.tableEdit.currentData(params.row)
                    //         })
                    //     }},
                    //     {title:'删除',tooltip:true,directives:[{name:'has',value:'del'}],type:'danger',style:(params,item) => params.row.id==1?{display:'none'}:'',click:{
                    //         ok:(params) => {
                    //             del(params.row.id).then(res => {
                    //                if (res.data.code == 1){
                    //                    (this.tableData.tableData.length == 1 && this.currentPage > 1)&&(--this.currentPage);
                    //                    this.handleGetTableData();
                    //                    return this.success(res.data.msg);
                    //                }
                    //                return this.error(res.data.msg);
                    //             });
                    //         },
                    //         cancel:(params) => {this.warning('取消删除')}
                    //     }},
                    // ]
                },
                // 分页
                page:{align:'right',total:1,currentPage:1,currentChange:(currentPage) => {
                    this.currentPage = currentPage;
                    this.tableData.loading = true;
                    this.tableDataInit(this.currentPage,pageOffset,handleSearchData({...this.search}));
                }},
            },
        }
    },
    created(){
        this.handleGetTableData();
    },
    methods: {
        handleGetTableData(flag=false){
            this.handleTable(flag);
            this.labelInit();
            this.tableDataInit(this.currentPage,pageOffset);
        },
        // 列初始化
        labelInit(){
            this.tableData.tableLabel = [
                {prop:'id',title:'ID',type:'index',fixed:true,width:60,align:'center'},
                {prop:'tel',title:'手机'},
                {prop:'name',title:'姓名'},
                {prop:'address_id',title:'地区'},
                {prop:'store_name',title:'店面名称'},
                {prop:'group_id',title:'用户组',render:(params) => [params.row.title]},
                {prop:'status',title:'状态',isSwitch:true,width:80,style:(params,item)=>{return params.row.id==1?{display:'none'}:''},change:(params) => {
                    let txt = params.row.status==1?'启用账号':'禁用账号';
                    edit(params.row,params.row.id).then(res => {
                        if (res.data.code == 1){
                            return this.success(txt);
                        }
                        return this.error(res.data.msg);
                    });
                }}
            ];
        },
        // 数据初始化
        tableDataInit(currentPage,pageOffset,keywords='') {
            this.tableData.loading = true;
            getTableData(currentPage, pageOffset, keywords).then(res => {
                if (res.data.code == 1) {
                    this.tableData.tableData = res.data.data.data.data;
                    this.tableData.page.total = res.data.data.total;
                    this.groups = res.data.data.groups;
                } else {
                    this.jumpUrl(res.data,this);
                }
                this.tableData.loading = false;
            });
        },
        handleTable(flag,type='',text='添加用户'){
            ObjectforIn(this.showTable,false);
            this.optionText = text;
            this.show = flag;
            this.showTable[type] = flag;
        },
        // 搜索
        handleSearch(){
            this.tableDataInit(this.tableData.page.currentPage,pageOffset,handleSearchData({...this.search}));
        },
        /****************************** 操作 ******************************/
        handleResetPassword(params){
            this.confirm('你确定要重置密码?',()=>{
                password(params.row.id).then(res => {
                    if (res.data.code == 1){
                        return this.success(res.data.msg);
                    }
                    return this.success(res.data.msg);
                });
            },()=>{
                this.warning('取消重置密码');
            });
        },
        handleEdit(params){
            this.handleTable(true, 'showTableEdit', '编辑用户');
            this.$nextTick(() => {
                this.$refs.tableEdit && this.$refs.tableEdit.currentData(params.row)
            })
        },
        handleOk(params){
            del(params.id).then(res => {
                if (res.data.code == 1){
                    (this.tableData.tableData.length == 1 && this.currentPage > 1)&&(--this.currentPage);
                    this.handleGetTableData();
                    return this.success(res.data.msg);
                }
                return this.error(res.data.msg);
            });
        },
        handleCancel(params){
            return this.warning('取消删除');
        }
    },
}
</script>

<style lang="css" scoped>

</style>