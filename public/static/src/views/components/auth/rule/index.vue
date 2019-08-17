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
<!--        <table-add ref='tabelAdd' :parentRlue="parentRlue" :show="showTableAdd" @showTableAddEvent="showTableAddEvent"></table-add>-->
        <!-- 抽屉（编辑） -->
<!--        <table-edit ref='tabelEdit' :parentRlue="parentRlue" :show='showTableEdit'@showTableEditEvent='showTableEditEvent'></table-edit>-->

        <!-- 添加 -->
        <Modal v-model="showTableAdd" title="添加权限" :mask-closable="false" :width="'35%'" :footer-hide="true">
            <table-add ref='tabelAdd' v-if="showTableAdd" :parentRlue="parentRlue" @showTableAddEvent="showTableAddEvent"></table-add>
        </Modal>
        <!-- 编辑 -->
        <Modal v-model="showTableEdit" title="编辑权限" :mask-closable="false" :width="'35%'" :footer-hide="true">
            <table-edit ref='tabelEdit' v-show='showTableEdit' :parentRlue="parentRlue" @showTableEditEvent='showTableEditEvent'></table-edit>
        </Modal>
        <!-- 分页 -->
        <Page align='right' :current="currentPage" :styles='{marginTop:"10px"}' :total="total" @on-change='getPageData' @on-page-size-change="changeOffset" show-sizer />
    </div>
</template>
<script>
import TableAdd from './components/add.vue'
import TableEdit from './components/edit.vue'
import Loading from '@/components/admin/loading/load.vue'
import { getTableData,del } from '@/api/admin/rule.js'
import { pageOffset,jumpUrl } from "@/env.js"


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
                    key: 'title',
                    render:(h,params) => {
                        var txt = '└―';
                        var title = params.row.title;
                        var level = params.row.level;
                        if (level){
                            txt = txt.repeat(level) + ' ' + title;
                        }else {
                            txt = title;
                        }
                        return h('span',txt)
                    }
                },
                {
                    title: '规则',
                    key: 'name'
                },
                {
                    title: '状态',
                    key: 'status',
                    render:(h,params) => {
                        var text = '';
                        var color = '';
                        var status = params.row.status;
                        if (status == 1){
                            color = '#19be6b';
                            text = '正常';
                        }else{
                            color = 'red';
                            text = '禁用';
                        }
                        return h('span',{style:{color}},text)
                    }
                },
                {
                    title: '操作',
                    width:180,
                    align: 'center',
                    render: (h, params) => {
                        return h('div', [
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
            parentRlue:null// 伏击权限
        }
    },
    created () {
        this.getTableData();
    },
    methods:{
        // 获取分页数据
        getTableData () {
            this.isShowLoading = true;
            getTableData().then(res => {
                if (res.data.code == 1){
                    this.tableData = res.data.data.data;
                    this.total = res.data.data.total;
                    // this.parentRlue = [{id:0,title:'无',pid:0,level:0},...res.data.data.parent];
                    this.parentRlue = res.data.data.parent;
                    this.pageData = this.handleTableData(this.tableData,0,this.offset);
                    // this.pageData = this.tableData;
                    this.isShowLoading = false;
                }else {
                    jumpUrl(res.data,this);
                }
            });
        },
        // 点击分页切换数据
        getPageData (currentPage) {
            this.currentPage = currentPage;
            const page = (currentPage - 1) * this.offset;
            this.pageData = this.handleTableData(this.tableData,page,this.offset);
        },
        // 每页显示条数
        changeOffset (offet) {
            this.offset = offet;
            // this.getTableData(this.page,this.offset,handleSearchData({...this.search}));
            this.pageData = this.handleTableData(this.tableData,this.currentPage-1,this.offset);
        },
        showTableAddEvent () {
            this.showTableAdd = false;
            this.currentPage = 1;
            this.getTableData();
        },
        // 抽屉显示隐藏
        showTableEditEvent () {
            this.showTableEdit = false;
            this.currentPage = 1;
            this.getTableData();
        },
        del(id){
            del(id).then(res => {
                if (res.data.code == 1){
                    this.$Message.success(res.data.msg);
                    this.getTableData();
                    // this.getPageData(this.currentPage);
                }else {
                    this.$Message.error(res.data.msg);
                }
            })
        },
        // 截取数组中指定的项
        handleTableData (tableData,start,end) {
            return Array.isArray(tableData) &&　tableData.slice(start,start+end);
        },
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