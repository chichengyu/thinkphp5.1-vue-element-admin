<template>
    <div class="groups">
        <div class="header">
            <el-button v-has="'add'" type="primary" @click="handleTable(true,'showTableAdd')" >添加</el-button>
        </div>
        <component-table :data="tableData">
            <template v-slot:button="{scope}">
                <el-button v-has="'edit'" type="primary" size="mini" @click="handleEdit(scope)">编辑</el-button>
                <component-popover v-has="'del'" :params="scope.row" @ok="handleOk" @cancel="handleCancel"></component-popover>
            </template>
        </component-table>

        <component-dialog v-if="show" :width="50" :title="optionText" :visible.sync="show" :footer="false">
            <table-add slot="dialog" v-if="showTable.showTableAdd" :groups="parentData" @handleGetTableData="handleGetTableData" style="overflow: hidden"></table-add>
            <table-edit slot="dialog" ref="tableEdit" v-if="showTable.showTableEdit" :groups="parentData" @handleGetTableData="handleGetTableData" style="overflow: hidden"></table-edit>
        </component-dialog>
    </div>
</template>

<script>
import tableAdd from './components/add.vue'
import tableEdit from './components/edit.vue'
import { getTableData,edit,del } from '@/api/rule'

export default {
    name: "index",
    components:{
        tableAdd,
        tableEdit
    },
    data () {
        return {
            show:false,
            showTable:{
                showTableAdd:false,
                showTableEdit:false,
            },
            tableDataAll:null,
            currentPage:1,
            optionText:'',
            search:{
                keywords:''
            },
            parentData:null,
            visible:false,
            tableData: {
                loading:false,
                // 请求回来的数据
                tableData:[],
                // 列
                tableLabel:[],
                // 操作
                tableOption:{
                    label:'操作',
                    width:150,
                    slot:true
                    // buttons:[
                    //     {title:'编辑',directives:[{name:'has',value:'edit'}],type:'primary',click: (params) => {
                    //         this.handleTable(true, 'showTableEdit', '编辑权限');
                    //         this.$nextTick(() => {
                    //             this.$refs.tableEdit && this.$refs.tableEdit.currentData(params.row)
                    //         })
                    //     }},
                    //     {title:'删除',tooltip:true,directives:[{name:'has',value:'del'}],type:'danger',click:{
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
                    // this.tableData.loading = true;
                    this.tableData.tableData = this.handleTableData(this.tableDataAll,(this.currentPage-1)*pageOffset,pageOffset);
                    // this.tableDataInit(this.currentPage,pageOffset,handleSearchData({...this.search}));
                }},
            },
        }
    },
    created(){
        // this.labelInit();
        // this.tableDataInit(this.tableData.page.currentPage,pageOffset);
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
                {prop:'title',title:'名称',hasChildren:true},
                {prop:'name',title:'规则'},
                {prop:'status',title:'状态',isSwitch:true,width:80,change:(currentData) => {
                    let txt = currentData.row.status==1?'启用权限':'禁用权限';
                    edit(currentData.row,currentData.row.id).then(res => {
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
                    this.tableDataAll = res.data.data.data;
                    this.tableData.tableData = this.handleTableData(this.tableDataAll,0,pageOffset);
                    this.parentData = res.data.data.parent;
                    this.tableData.page.total = this.tableDataAll.length;
                } else {
                    this.jumpUrl(res.data,this);
                }
                this.tableData.loading = false;
            });
        },
        handleTable(flag,type='',text='添加权限'){
            ObjectforIn(this.showTable,false);
            this.optionText = text;
            this.show = flag;
            this.showTable[type] = flag;
        },
        // 截取数组中指定的项
        handleTableData (tableData,start,end) {
            return Array.isArray(tableData) &&　tableData.slice(start,start+end);
        },
        /****************************** 操作 ******************************/
        handleEdit(params){
            this.handleTable(true, 'showTableEdit', '编辑权限');
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