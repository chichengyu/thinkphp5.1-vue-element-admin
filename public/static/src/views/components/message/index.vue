<template>
    <div class="table">
        <!-- header -->
        <div class="table-header" style="margin: 10px">
            <Button v-has="'add'" type="primary" size="large" @click.stop="showTableAdd=true"><Icon type="md-add"></Icon>添加</Button>
            <div class="header-dis-inline">
                <Input size="large" v-model="search.keywords" prefix="ios-search" placeholder="车主/标题" style="width: auto" />
            </div>
            <div class="header-dis-inline">
                显示
                <Select v-model="search.status" @on-change="changeStatus" style="width:150px" size="large">
                    <Option value="">请选择</Option>
                    <Option value="1">是</Option>
                    <Option value="0">否</Option>
                </Select>
            </div>
            <Button @click.stop="handleSearchEvent" type="primary" size="large" >搜索</Button>
        </div>

        <!-- 表格 -->
        <div class="tableData">
            <Table ref='table' border v-if='pageData' :columns="columns" :data="pageData"></Table>
            <!-- loading -->
            <loading :isSshow="isShowLoading"></loading>
        </div>

        <!-- 模态框（图片查看） -->
        <Modal v-model='showModal' title="Logo查看" :footer-hide="true" :close="'关闭'">
            <div v-if='currentTableData'>
                <img :src="currentTableData.image" width="100%" height="100%" alt="">
            </div>
        </Modal>

        <!-- 添加 -->
        <Modal v-model="showTableAdd" title="添加咨讯" :mask-closable="false" :width="'35%'" :footer-hide="true">
            <table-add ref='tabelAdd' v-if="showTableAdd" :stores="stores" @showTableAddEvent="showTableAddEvent"></table-add>
        </Modal>
        <!-- 编辑 -->
        <Modal v-model="showTableEdit" title="编辑咨讯" :mask-closable="false" :width="'35%'" :footer-hide="true">
            <table-edit ref='tabelEdit' :show='showTableEdit' :stores="stores" @showTableEditEvent='showTableEditEvent'></table-edit>
        </Modal>

        <!-- 查看 -->
        <Modal v-model="showTableDetails" title="查看咨讯" :mask-closable="false" :width="'35%'" :footer-hide="true">
            <table-details ref='tabelDetails' :show='showTableDetails' :stores="stores" @showTableDetailsEvent='showTableDetailsEvent'></table-details>
        </Modal>

        <!-- 添加 -->
<!--        <table-add ref='tabelAdd' :show="showTableAdd" :stores="stores" @showTableAddEvent="showTableAddEvent"></table-add>-->
        <!-- 抽屉（编辑） -->
<!--        <table-edit ref='tabelEdit' :show='showTableEdit' :stores="stores" @showTableEditEvent='showTableEditEvent'></table-edit>-->
        <!-- 查看 -->
<!--        <table-details ref='tabelDetails' :show='showTableDetails' :stores="stores" @showTableDetailsEvent='showTableDetailsEvent'></table-details>-->

        <!-- 分页 -->
        <Page align='right' :current="currentPage" :styles='{marginTop:"10px"}' :total="total" @on-change='getPageData' @on-page-size-change="changeOffset" show-sizer />
    </div>
</template>
<script>
import TableAdd from './components/add.vue'
import TableEdit from './components/edit.vue'
import TableDetails from './components/details.vue'
import Loading from '@/components/admin/loading/load.vue'
import { getTableData,del,edit } from '@/api/admin/message.js'
import { pageOffset,jumpUrl,handleSearchData } from "@/env.js"

export default {
    components:{
        TableAdd,
        TableEdit,
        TableDetails,
        Loading
    },
    data () {
        return {
            showModal:false,// 模态框
            // exportShow:false,// 导出excel模态确认
            showTableAdd:false, // 抽屉添加
            showTableEdit:false, // 抽屉编辑
            showTableDetails:false, // 查看
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
                    title: '车主',
                    key: 'owner',
                    align: 'center'
                },
                {
                    title: '标题',
                    key: 'title',
                    align: 'center',
                    tooltip:true,
                },
                {
                    title:'提车日期',
                    key:'year',
                    width: 150,
                    align: 'center'
                },
                {
                    title: '提车门店',
                    key: 'store_name',
                    minWidth:100,
                    align: 'center',
                    tooltip:true,
                },
                {
                    title: '提取车辆',
                    key: 'car_name',
                    align: 'center'
                },
                {
                    title: '缩略图',
                    key: 'image',
                    align: 'center',
                    render:(h,params) => {
                        var url = params.row.image;
                        if (url){
                            return h('img',{
                                    style:{
                                        width:'100%',
                                        height:'40px',
                                        verticalAlign:'middle',
                                        cursor:'pointer'
                                    },
                                    domProps:{
                                        src:url
                                    },
                                    on: {
                                        click: () => {
                                            this.currentTableData = this.pageData[params.index];
                                            this.showModal = true;
                                        }
                                    }
                            })
                        }
                        return h('span','（ 无 ）');
                    }
                },
                {
                    title:'是否显示',
                    key:'status',
                    align: 'center',
                    render:(h,params)=>{
                        var val = params.row.status == 1? true:false;
                        return h('i-Switch',{
                            props: {
                                size:'large',
                                value:val
                            },
                            on:{
                                'on-change':(status) => {
                                    var value = 0;
                                    if (status){
                                        value = 1;
                                    }
                                    edit({status:value},params.row.id).then(res => {
                                        if (res.data.code == 1){
                                            return this.$Message.success(res.data.msg);
                                        }
                                        return this.$Message.error(res.data.msg);
                                    })
                                }
                            }
                        },[h('span',{
                            slot: 'open',
                            domProps:{
                                innerHTML:'是'
                            }
                        }),h('span',{
                            slot: 'close',
                            domProps:{
                                innerHTML:'否'
                            }
                        })])
                    }
                },
                {
                    title:'创建日期',
                    key:'create_time',
                    width: 150,
                    align: 'center'
                },
                {
                    title: '操作',
                    key: 'address',
                    width:180,
                    align: 'center',
                    render: (h, params) => {
                        return h('div', [
                            h('Button', {
                                props: {
                                    type: 'warning',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.$refs.tabelDetails.currentDetailsData(params.row);
                                        this.showTableDetails = true;
                                    }
                                }
                            }, '查看'),
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
            stores:null,// 门店
            // 搜索条件
            search:{
                keywords:'',
                status:''
            },
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
                    this.stores = res.data.data.stores;
                    this.pageData = this.tableData;
                }else {
                    // this.$Message.error(res.data.msg);
                    // this.$router.push('/401');
                    jumpUrl(res.data,this);
                }
                this.isShowLoading = false;
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
        showTableAddEvent () {
            this.showTableAdd = false;
            this.getTableData(this.currentPage,this.offset,handleSearchData({...this.search}));
        },
        // 抽屉显示隐藏
        showTableEditEvent () {
            this.showTableEdit = false;
            this.getTableData(this.currentPage,this.offset,handleSearchData({...this.search}));
        },
        // 查看
        showTableDetailsEvent () {
            this.showTableDetails = false;
            this.getTableData(this.currentPage,this.offset,handleSearchData({...this.search}));
        },
        del(id){
            del(id).then(res => {
                if (res.data.code == 1){
                    this.getTableData(this.currentPage,this.offset,handleSearchData({...this.search}));
                    this.$Message.success(res.data.msg);
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

<style lang="stylus" scoped>
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
</style>