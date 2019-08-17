<template>
    <div class="table">
        <!-- header -->
        <div class="table-header" style="margin: 10px">
            <Button v-has="'add'" type="primary" size="large" @click.stop="showTableAdd=true"><Icon type="md-add"></Icon>添加</Button>
            <div class="header-dis-inline">
                手机：
                <Input size="large" v-model="search.tel" prefix="md-phone-portrait" placeholder="请输入手机" style="width: auto" />
            </div>
            <div class="header-dis-inline">
                名称：
                <Input size="large" v-model="search.username" prefix="ios-contact" placeholder="请输入名称" style="width: auto" />
            </div>
            <Button @click.stop="handleSearchEvent" type="primary" size="large" >搜索</Button>
        </div>

        <!-- 表格 -->
        <div class="tableData">
            <Table ref='table' border v-if='pageData' :columns="columns" :data="pageData"></Table>
            <!-- loading -->
            <loading :isSshow="isShowLoading"></loading>
        </div>

        <!-- 添加 -->
        <Modal v-model="showTableAdd" title="添加员工" :mask-closable="false" :footer-hide="true" :width="'35%'">
            <table-add ref='tabelAdd' v-if="showTableAdd" :groups="groups" @showTableAddEvent="showTableAddEvent"></table-add>
        </Modal>
        <!-- 编辑 -->
        <Modal v-model="showTableEdit" title="编辑员工" :mask-closable="false" :footer-hide="true" :width="'35%'">
            <table-edit ref='tabelEdit' :show="showTableEdit" :groups="groups" @showTableEditEvent="showTableEditEvent"></table-edit>
        </Modal>


        <!-- 重置密码确认框 -->
        <Modal v-model="resetShow" title="重置密码" :mask-closable="false" :width="220">
            <h3 align="center">你确定要重置密码吗？</h3>
            <div align="center" slot="footer">
                <Button @click.stop="handleCancel" size="default">取消</Button>
                <Button @click.stop="handleOk(currentTableData.id)" type="primary" size="default">确定</Button>
            </div>
        </Modal>

        <!-- 分页 -->
        <Page align='right' :current="currentPage" :styles='{marginTop:"10px"}' :total="total" @on-change='getPageData' @on-page-size-change="changeOffset" show-sizer />
    </div>
</template>
<script>
import TableAdd from './components/add.vue'
import TableEdit from './components/edit.vue'
import Loading from '@/components/admin/loading/load.vue'
import { getTableData,del,edit } from '@/api/admin/personnel.js'
import { pageOffset,jumpUrl,handleSearchData } from "@/env.js"

export default {
    components:{
        TableAdd,
        TableEdit,
        Loading
    },
    data () {
        return {
            showModal:false,// 模态框
            // exportShow:false,// 导出excel模态确认
            showTableAdd:false, // 抽屉添加
            showTableEdit:false, // 抽屉编辑
            delOkOrCancel:false,// 删除确认框
            currentTableData:null,
            offset:pageOffset,// 每页显示的条数
            columns: [
                {
                    title:'ID',
                    key:'id',
                    width: 60,
                    align: 'center'
                },
                {
                    title: '手机',
                    key: 'tel'
                },
                {
                    title: '名称',
                    key: 'name'
                },
                {
                    title: '职位',
                    key: 'position'
                },
                {
                    title: '角色',
                    key: 'group_name'
                },
                {
                    title:'创建日期',
                    key:'create_time',
                    width: 150,
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
                                    type: 'default',
                                    size: 'small'
                                },
                                directives:[{name:'has',value:'edit'}],
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.currentTableData = params.row;
                                        this.resetShow = true;
                                    }
                                }
                            }, '重置密码'),
                            h('Button', {
                                props: {
                                    type: 'primary',
                                    size: 'small'
                                },
                                directives:[{name:'has',value:'edit'}],
                                style: {
                                    marginRight: '5px',
                                },
                                on: {
                                    click: () => {
                                        this.showTableEdit = true;
                                        this.$refs.tabelEdit.currentEditData(params.row);
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
            // 搜索条件
            search:{
                username:'',
                tel:'',
            },
            resetShow:false,
        }
    },
    created () {
        this.getTableData(this.page,pageOffset);
    },
    methods:{
        // 获取分页数据
        getTableData (page,pageOffset,keywords='') {
            this.isShowLoading = true;
            getTableData(page,pageOffset,keywords).then(res => {
                if (res.data.code == 1){
                    this.tableData = res.data.data.data.data;
                    this.total = res.data.data.total;
                    this.groups = res.data.data.groups;
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
            this.getTableData(currentPage,this.offset,handleSearchData({...this.search}));
        },
        // 每页显示条数
        changeOffset (offet) {
            this.offset = offet;
            this.getTableData(this.page,this.offset,handleSearchData({...this.search}));
        },
        handleOk (id) {
            if (id){
                edit({reset:true},id).then(res => {
                    if (res.data.code == 1){
                        return this.$Message.success(res.data.msg);
                    }
                    this.$Message.error(res.data.msg);
                });
            }else {
                this.$Message.error('重置密码失败！');
            }
            this.resetShow = false;
        },
        handleCancel () {
            this.resetShow = false;
            this.$Message.warning('取消重置密码');
        },
        showTableAddEvent () {
            this.showTableAdd = false;
            this.getTableData(this.page,this.offset,handleSearchData({...this.search}));
        },
        // 抽屉显示隐藏
        showTableEditEvent () {
            this.showTableEdit = false;
            this.getTableData(this.page,this.offset,handleSearchData({...this.search}));
        },
        del(id){
            del(id).then(res => {
                if (res.data.code == 1){
                    this.$Message.success(res.data.msg);
                    this.getTableData(this.currentPage,this.offset,handleSearchData({...this.search}));
                }else {
                    this.$Message.error(res.data.msg);
                }
            })
        },
        // 搜索
        handleSearchEvent (){
            this.currentPage = this.page;
            this.getTableData(this.page,this.offset,handleSearchData({...this.search}));
        },
        changeStatus (status) {
            if (typeof this.search.status == 'undefined') {
                this.search.status = '';
            }
        }
    }
}
</script>

<style lang="css" scoped>
.table{
    height:80%;
}
.tableData{
    position: relative;
    /*height:100%;*/
}
.header-dis-inline{
    display: inline-block;
    margin: 0 10px;
}
.ivu-modal-footer{
    text-align: center !important;
}
</style>