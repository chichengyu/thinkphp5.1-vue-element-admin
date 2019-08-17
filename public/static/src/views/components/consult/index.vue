<template>
    <div class="table">
        <!-- header -->
        <div class="table-header" style="margin: 10px">
            <Button v-has="'add'" type="primary" size="large" @click.stop="showTableAdd=true"><Icon type="md-add"></Icon>添加</Button>
<!--            <Button v-has="'export'" type="primary" size="large" @click.stop="handleExportData"><Icon type="ios-download-outline"></Icon>导出</Button>-->
            <div class="header-dis-inline">
                <Input size="large" v-model="search.keywords" prefix="ios-search" placeholder="手机/用户/门店/车辆/员工" style="width: auto" />
            </div>
            <div class="header-dis-inline">
                <DatePicker type="daterange" v-model="search.date" @on-change="changeDate" split-panels size="large" placeholder="请选择时间区间"></DatePicker>
            </div>
            <div class="header-dis-inline">
                状态
                <Select v-model="search.status" @on-change="changeStatus" style="width:150px" size="large">
                    <Option value="">请选择</Option>
                    <Option value="1">已沟通</Option>
                    <Option value="0">待沟通</Option>
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

        <!-- 添加 -->
        <Modal v-model="showTableAdd" title="添加咨询" :mask-closable="false" :width="'35%'" :footer-hide="true">
            <table-add ref='tabelAdd' v-if="showTableAdd" :carBrandsTypes="carBrandsTypes" @showTableAddEvent="showTableAddEvent"></table-add>
        </Modal>
        <!-- 编辑 -->
        <Modal v-model="showTableEdit" title="编辑咨询" :mask-closable="false" :width="'35%'" :footer-hide="true">
            <table-edit ref='tabelEdit' :show='showTableEdit' :carBrandsTypes="carBrandsTypes" @showTableEditEvent='showTableEditEvent'></table-edit>
        </Modal>

        <!-- 转发 -->
        <Modal v-model="showTableRelay" title="指派" :mask-closable="false" :footer-hide="true">
            <table-relay ref="tableRelay" :show='showTableRelay' :personnel="personnel" @showTableRelayEvent='showTableRelayEvent'></table-relay>
        </Modal>


        <!-- 分页 -->
        <Page align='right' :current="currentPage" :styles='{marginTop:"10px"}' :total="total" @on-change='getPageData' @on-page-size-change="changeOffset" show-sizer />
    </div>
</template>
<script>
import TableAdd from './components/add.vue'
import TableEdit from './components/edit.vue'
import TableRelay from './components/relay.vue'
import Loading from '@/components/admin/loading/load.vue'
import { getTableData,del,edit,excel } from '@/api/admin/consult.js'
import { pageOffset,jumpUrl,handleSearchData } from "@/env.js"

export default {
    components:{
        TableAdd,
        TableEdit,
        TableRelay,
        Loading
    },
    data () {
        return {
            showModal:false,// 模态框
            // exportShow:false,// 导出excel模态确认
            showTableAdd:false, // 添加
            showTableEdit:false, // 编辑
            showTableRelay:false, // 转发
            delOkOrCancel:false,// 删除确认框
            currentTableData:null,
            offset:pageOffset,// 每页显示的条数
            columns: [
                {
                    title:'ID',
                    key:'id',
                    width: 60,
                    fixed:'left',
                    align: 'center',
                },
                {
                    title:'用户',
                    key:'username',
                    minWidth: 100,
                },
                {
                    title: '手机号码',
                    key: 'tel',
                    width: 115,
                },
                {
                    title: '门店',
                    key: 'name',
                    width: 250,
                    align: 'center',
                    tooltip:true,
                },
                {
                    title: '车辆名称',
                    key: 'cart_name',
                    minWidth: 200,
                    tooltip:true
                },
                {
                    title:'状态',
                    key:'status',
                    minWidth: 100,
                    render:(h,params)=>{
                        var status = params.row.status == 0;
                        var color = status ?'#ff9900':'#19be6b';
                        var text = status ?'待沟通':'已沟通';
                        return h('span',{
                            props: {
                                size: 'small',
                            },
                            style:{
                                color
                            }
                        },text)
                    }
                },
                {
                    title:'预约时间',
                    key:'appointment_time',
                    width: 150,
                },
                {
                    title:'來源',
                    key:'source',
                    width: 100,
                },
                {
                    title:'对接人',
                    key:'personnel_name',
                    width: 100,
                },
                {
                    title:'联系方式',
                    key:'personnel_tel',
                    minWidth: 120,
                },
                {
                    title:'备注',
                    key:'remark',
                    width: 150,
                    tooltip:true
                },
                {
                    title:'沟通时间',
                    key:'update_time',
                    width: 150,
                    render:(h,params) => {
                        var time = params.row.update_time ? params.row.update_time:'(空)';
                        return h('span',time);
                    }
                },
                {
                    title: '操作',
                    key: 'address',
                    width:220,
                    fixed:'right',
                    align: 'center',
                    render: (h, params) => {
                        return h('div', [
                            // h('Button', {
                            //     props: {
                            //         type: 'warning',
                            //         size: 'small'
                            //     },
                            //     style: {
                            //         marginRight: '5px'
                            //     },
                            //     on: {
                            //         click: () => {
                            //             this.$refs.tabelEdit.currentEditData(params.row);
                            //             this.showTableEdit = true;
                            //         }
                            //     }
                            // }, '查看'),
                            h('Button', {
                                props: {
                                    type: 'warning',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                domProps:{
                                    disabled:params.row.personnel_id!=0?true:false
                                },
                                on: {
                                    click: () => {
                                        this.$refs.tableRelay.currentRelayData(params.row);
                                        this.showTableRelay = true;
                                    }
                                }
                            }, '转发'),
                            h('Button', {
                                props: {
                                    type: 'primary',
                                    size: 'small'
                                },
                                directives:[{name:'has',value:'edit'}],
                                style: {
                                    marginRight: '5px'
                                },
                                domProps:{
                                    disabled:(params.row.status == 1)?true:false
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
                            }, [h('Button',{
                                    props: { 
                                        type: 'error' ,
                                        size: 'small',
                                    },
                                    // directives:[{name:'has',value:'del'}],
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
            // 搜索条件
            search:{
                keywords:'',
                status:'',
                date:[]
            },
            // 门店员工
            personnel:null,
            carBrandsTypes:{
                brands:null,
                types:null
            }
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
                    res = res.data.data;
                    this.tableData = res.data.data;
                    this.total = res.total;
                    this.carBrandsTypes.brands = res.carBrands;
                    this.carBrandsTypes.types = res.types;
                    this.personnel = res.personnel;
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
        showTableAddEvent () {
            this.showTableAdd = false;
            this.getTableData(this.page,this.offset,handleSearchData({...this.search}));
        },
        // 抽屉显示隐藏
        showTableEditEvent () {
            this.showTableEdit = false;
            this.getTableData(this.currentPage,this.offset,handleSearchData({...this.search}));
        },
        // 转发
        showTableRelayEvent () {
            this.showTableRelay = false;
            this.getTableData(this.currentPage,this.offset,handleSearchData({...this.search}));
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
        },
        // 导出数据
        handleExportData (e) {
            if (this.search.date.every(item => item != '')){
                let params = '?start=' + this.search.date[0] + '&end=' + this.search.date[1];
                excel(params).then(res => {
                    if (res.data.code == 1){
                        // iframe 无刷新下载
                        let oi = document.createElement('iframe');
                            oi.style.display = 'none';
                            oi.src = res.data.data.download_url;
                            document.body.appendChild(oi);
                        this.$Message.success(res.data.msg);
                    } else {
                        this.$Message.error(res.data.msg);
                    }
                }).catch(err => {
                    this.$Message.error('导出失败！');
                })
            } else {
                this.$Message.error('请先选择时间区间');
            }
        },
        changeDate(date){
            (date.length) && (this.search.date = date);
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
td.ivu-table-expanded-cell{padding:0;}
</style>