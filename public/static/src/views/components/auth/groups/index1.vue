<template>
    <div class="table">
        <!-- header -->
        <div class="table-header" style="margin: 10px">
            <Button v-has="'add'" type="primary" size="large" @click.stop="showTableAdd=true"><Icon type="md-add"></Icon>添加</Button>
        </div>
        <!-- 表格 -->
        <div class="tableData">
            <Table ref='table' border v-if='pageData' :columns="columns" :data="pageData"></Table>
            <!-- loading -->
            <loading :isSshow="isShowLoading"></loading>
        </div>

        <!-- 添加 -->
<!--        <table-add ref='tabelAdd' :show="showTableAdd" @showTableAddEvent="showTableAddEvent"></table-add>-->
        <!-- 抽屉（编辑） -->
<!--        <table-edit ref='tabelEdit' :show='showTableEdit' @showTableEditEvent='showTableEditEvent'></table-edit>-->
        <!-- 分配权限 -->
<!--        <table-auth ref='tabelAuth' :rules="rules" :show="showTableAuth" @showTableAuthEvent="showTableAuthEvent"></table-auth>-->

        <!-- 添加 -->
        <Modal v-model="showTableAdd" title="添加用户组" :mask-closable="false" :width="'35%'" :footer-hide="true">
            <table-add ref='tabelAdd' v-if="showTableAdd" @showTableAddEvent="showTableAddEvent"></table-add>
        </Modal>
        <!-- 编辑 -->
        <Modal v-model="showTableEdit" title="编辑用户组" :mask-closable="false" :width="'35%'" :footer-hide="true">
            <table-edit ref='tabelEdit' :show='showTableEdit' @showTableEditEvent='showTableEditEvent'></table-edit>
        </Modal>
        <!-- 编辑 -->
        <Modal v-model="showTableAuth" title="分配权限" :mask-closable="false" :width="'35%'" :footer-hide="true">
            <table-auth ref='tabelAuth' :show="showTableAuth" :rules="rules" @showTableAuthEvent="showTableAuthEvent"></table-auth>
        </Modal>

        <!-- 分页 -->
        <Page align='right' :styles='{marginTop:"10px"}' :total="total" :current="currentPage" @on-change='getPageData' @on-page-size-change="changeOffset" show-sizer />
    </div>
</template>
<script>
import TableAdd from './components/add.vue'
import TableEdit from './components/edit.vue'
import TableAuth from './components/auth.vue'
import Loading from '@/components/admin/loading/load.vue'
import { getTableData,del } from '@/api/admin/groups.js'
import { pageOffset,jumpUrl } from "@/env.js"

export default {
    components:{
        TableAdd,
        TableEdit,
        TableAuth,
        Loading
    },
    data () {
        return {
            showModal:false,// 模态框
            // exportShow:false,// 导出excel模态确认
            showTableAdd:false, // 抽屉添加
            showTableEdit:false, // 抽屉编辑
            showTableAuth:false, // 抽屉编辑
            delOkOrCancel:false,// 删除确认框
            offset:pageOffset,// 每页显示的条数
            columns: [
                {
                    title:'ID',
                    key:'id',
                    width: 60,
                    align: 'center'
                },
                {
                    title: '名称',
                    key: 'title'
                },
                {
                    title: '状态',
                    key: 'status',
                    render:(h,params) => {
                        var color = '';
                        var text = '';
                        var status = params.row.status;
                        if (status == 1){
                            color = '#19be6b';
                            text = '正常';
                        }else{
                            color = 'red';
                            text = '禁用';
                        }
                        return h('span',{style:{color}},text);
                    }
                },
                {
                    title: '操作',
                    key: 'address',
                    width:210,
                    align: 'center',
                    render: (h, params) => {
                        return h('div', [
                            h('Button', {
                                props: {
                                    type: 'success',
                                    size: 'small'
                                },
                                directives:[{name:'has',value:'auth'}],
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.$refs.tabelAuth.currentEditData(params.row);
                                        this.showTableAuth = true;
                                    }
                                }
                            }, '分配权限'),
                            h('Button', {
                                props: {
                                    type: 'primary',
                                    size: 'small'
                                },
                                directives:[{name:'has',value:'edit'}],
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.$refs.tabelEdit.currentEditData(params.row);
                                        this.showTableEdit = true;
                                    }
                                }
                            }, '编辑'),
                            h('Poptip',{
                                props: {
                                    confirm: true,
                                    title: '您确认删除这条内容吗？',
                                    placement: 'top',
                                    transfer:true,
                                },
                                directives:[{name:'has',value:'del'}],
                                on: {
                                    'on-ok': () => {
                                        if(this.pageData.length == 1){
                                            if (this.currentPage > 1){
                                                this.currentPage = this.currentPage - 1;
                                            }else {
                                                this.currentPage = 1;
                                            }
                                        }
                                        this.del(params.row.id);
                                    },
                                    'on-cancel': () => {
                                        this.$Message.error('取消删除');
                                    }
                                }
                            }, [
                                h('Button',{ 
                                    props: { 
                                        type: 'error' ,
                                        size: 'small',
                                    }
                                },'删除')
                            ])
                        ]);
                    }
                }
            ],
            tableData: [],// 所有数据
            pageData:[],// 分页数据
            total:0,// 总共多少条数据,
            page:1,// 第一页
            currentPage:1,// 当前页,
            isShowLoading:false,
            groups:null,// 角色组
            rules:null// 分配权限结构数据
        }
    },
    created () {
        this.getTableData(this.page,pageOffset);
    },
    methods:{
        // 获取分页数据
        getTableData (page,pageOffset) {
            this.isShowLoading = true;
            getTableData(page,pageOffset).then(res => {
                if (res.data.code == 1){
                    this.tableData = res.data.data.data.data;
                    this.total = res.data.data.total;
                    this.groups = res.data.data.groups;
                    this.rules = res.data.data.rules;
                    this.pageData = this.tableData;
                    this.isShowLoading = false;
                }else {
                    jumpUrl(res.data,this);
                }
            });
        },
        // 点击分页切换数据
        getPageData (currentPage) {
            this.currentPage = currentPage;
            this.getTableData(currentPage,this.offset);
        },
        // 每页显示条数
        changeOffset (offet) {
            this.offset = offet;
            this.getTableData(this.page,this.offset);
        },
        showTableAddEvent () {
            this.showTableAdd = false;
            this.getTableData(this.currentPage,this.offset);
        },
        // 抽屉显示隐藏
        showTableEditEvent () {
            this.showTableEdit = false;
            this.getTableData(this.currentPage,this.offset);
        },
        // 分配权限抽屉显示隐藏
        showTableAuthEvent () {
            this.showTableAuth = false;
            this.getTableData(this.currentPage,this.offset);
        },
        del(id){
            del(id).then(res => {
                if (res.data.code == 1){
                    this.$Message.success(res.data.msg);
                    this.getTableData(this.currentPage,this.offset);
                }else {
                    this.$Message.error(res.data.msg);
                }
            })
        }
    }
}
</script>

<style lang="stylus" scoped>
.table{
    height:80%;
}
.tableData{
    position: relative;
    /*height:100%;*/
}
</style>