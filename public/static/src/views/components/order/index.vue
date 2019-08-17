<template>
    <div class="order">
        <!-- header -->
        <div class="search">
            <div class="search-a">
                <Input size="large" v-model="search.keywords" prefix="ios-search" placeholder="请输入订单号" />
            </div>
            <div class="header-dis-inline">
                <DatePicker type="daterange" v-model="search.date" @on-change="changeDate" split-panels size="large" placeholder="请选择时间"></DatePicker>
            </div>
            <div class="search-b">
                <Select v-model="search.status" @on-change="changeStatus" style="width:150px" size="large" placeholder="付款状态">
                    <Option value="">请选择</Option>
                    <Option value="0">未付款</Option>
                    <Option value="1">已付款</Option>
                    <Option value="2">已发货</Option>
                    <Option value="3">已签收</Option>
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
        <Modal v-model='showModal' title="Logo查看" :footer-hide="true" >
            <div v-if='currentTableData'>
                <img :src="currentTableData.thumbnail" width="100%" height="100%" alt="">
            </div>
        </Modal>

        <!-- 查看 -->
<!--        <Modal v-model="showTableDetails" title="查看详情" :mask-closable="false" :footer-hide="true">-->
<!--            <table-details ref='tabelDetails' :show="showTableDetails" @showTableDetailsEvent='showTableDetailsEvent'></table-details>-->
<!--        </Modal>-->


        <!-- 分页 -->
        <Page align='right' :current="currentPage" :styles='{marginTop:"10px"}' :total="total" @on-change='getPageData' @on-page-size-change="changeOffset" show-sizer />
    </div>
</template>

<script>
// import TableDetails from './components/details.vue'
import Loading from '@/components/admin/loading/load.vue'
import { getTableData,del } from '@/api/admin/order.js'
import { pageOffset,jumpUrl,handleSearchData } from "@/env.js"

export default {
    components:{
        // TableDetails,
        Loading
    },
    data () {
        return {
            showModal:false,// 模态框
            // exportShow:false,// 导出excel模态确认
            // showTableAdd:false, // 抽屉添加
            // showTableEdit:false, // 抽屉编辑
            // showTableDetails:false, // 查看
            // showTableAttr:false, // 添加配置
            showTableDownOrder:false, // 下单
            delOkOrCancel:false,// 删除确认框
            currentTableData:null,
            offset:pageOffset,// 每页显示的条数
            columns: [
                {
                    title:'ID',
                    key:'id',
                    width: 70,
                    align: 'center',
                    fixed:'left',
                    sortable: true
                },
                {
                    title: '订单编号',
                    key: 'order_no',
                    minWidth:150,
                    tooltip:true
                },
                {
                    title: '订单信息',
                    key: 'order_no',
                    minWidth:320,
                    tooltip:true,
                    render:(h,params) => {
                        var html = '<div>';
                        var owner = params.row.store_owner;
                        var border = 'none';
                        var index = 1;
                        (owner.length > 1)&&(border = '1px solid #e2e2e2');
                        for (var key in owner){
                            if (index == 1){
                                border = 'none';
                            } else {
                                border = '1px solid #e2e2e2';
                            }
                            index++;
                            html += '<div style="margin: 5px -6%;border-top: '+border+';">';
                            html += '<img src="'+owner[key].car_details.thumbnail+'" width="60" height="60">';
                            html += '<div style="display: inline-block;vertical-align: top"><p>' + owner[key].car_details.name;
                            html += '<p><span style="margin-right: 5px">指导价：'+ this.price(owner[key].car_details.price_zdj) +' 万</span>';
                            html += '<span style="margin-right: 5px"> 拿车价：<b style="color: red">'+ this.price(owner[key].car_details.price_jxs) +' 万</b></span>';
                            html += '<span style="margin-right: 5px"> 门店价：<b style="color: red">'+ this.price(owner[key].car_details.price_mdj) +' 万</b></span></p>';
                            html += '<p>'+ owner[key].car_number.car_color +' x '+ owner[key].count +'</p>';
                            html += '<p>共计：<b style="color: red">'+ this.price(owner[key].car_details.price_jxs)*owner[key].count +'</b> 万</p>';
                            html += '</p></div>';
                            html += '</div>';
                        }
                        html += '</div>';
                        return h('div',{domProps:{innerHTML:html}});
                    }
                },
                {
                    title: '数量',
                    key: 'order_no',
                    width:62,
                    render:(h,params) => {
                        var html = '<div>';
                        var owner = params.row.store_owner;
                        var border = 'none';
                        var index = 1;
                        (owner.length > 1)&&(border = '1px solid #e2e2e2');
                        for (var key in owner){
                            if(index == 1){
                                border = 'none';
                            }else {
                                border = '1px solid #e2e2e2';
                            }
                            index++;
                            html += '<div style="margin: 5px -70%;padding:27px 0;text-align: center;border-top: '+border+';">'+ owner[key].count +'</div>';
                        }
                        html += '</div>';
                        return h('div',{domProps:{innerHTML:html}});
                    }
                },
                {
                    title: '用户联系方式',
                    key: 'order_no',
                    minWidth:110,
                    render:(h,params) => {
                        var html = '<div>';
                        var owner = params.row.store_owner;
                        var border = 'none';
                        var index = 1;
                        (owner.length > 1)&&(border = '1px solid #e2e2e2');
                        for (var key in owner){
                            if(index == 1){
                                border = 'none';
                            }else {
                                border = '1px solid #e2e2e2';
                            }
                            index++;
                            html += '<div style="margin: 5px -40%;padding:18px 0;text-align: center;border-top: '+border+';">';
                            html += '<p>'+ owner[key].owner_name +'</p>';
                            html += '<p>'+ owner[key].owner_tel +'</p>';
                            html += '</div>';
                        }
                        html += '</div>';
                        return h('div',{domProps:{innerHTML:html}});
                    }
                },
                {
                    title: '意向金',
                    key: 'order_no',
                    minWidth:100,
                    render:(h,params) => {
                        var html = '<div>';
                        var owner = params.row.store_owner;
                        var border = 'none';
                        var index = 1;
                        (owner.length > 1)&&(border = '1px solid #e2e2e2');
                        for (var key in owner){
                            if(index == 1){
                                border = 'none';
                            }else {
                                border = '1px solid #e2e2e2';
                            }
                            index++;
                            html += '<div style="margin: 5px -40%;padding:27px 0;text-align: center;border-top: '+border+';">无</div>';
                        }
                        html += '</div>';
                        return h('div',{domProps:{innerHTML:html}});
                    }
                },
                {
                    title: '子订单编号',
                    key: 'order_no',
                    minWidth:150,
                    render:(h,params) => {
                        var html = '<div>';
                        var owner = params.row.store_owner;
                        var border = 'none';
                        var index = 1;
                        (owner.length > 1)&&(border = '1px solid #e2e2e2');
                        for (var key in owner){
                            if(index == 1){
                                border = 'none';
                            }else {
                                border = '1px solid #e2e2e2';
                            }
                            index++;
                            html += '<div style="margin: 5px -40%;padding:27px 0;text-align: center;border-top: '+border+';">'+ owner[key].order_no_sub +'</div>';
                        }
                        html += '</div>';
                        return h('div',{domProps:{innerHTML:html}});
                    }
                },
                {
                    title: '未支付金额',
                    key: 'order_no',
                    minWidth:100,
                    render:(h,params) => {
                        var html = '<div>';
                        var owner = params.row.store_owner;
                        var border = 'none';
                        var index = 1;
                        (owner.length > 1)&&(border = '1px solid #e2e2e2');
                        for (var key in owner){
                            if(index == 1){
                                border = 'none';
                            }else {
                                border = '1px solid #e2e2e2';
                            }
                            index++;
                            // TODO:未支付金额没有计算
                            html += '<div style="margin: 5px -40%;padding:27px 0;text-align: center;border-top: '+border+';">'+ owner[key].id +'</div>';
                        }
                        html += '</div>';
                        return h('div',{domProps:{innerHTML:html}});
                    }
                },
                {
                    title: '支付金额',
                    key: 'order_no',
                    minWidth:150,
                    tooltip:true,
                    render:(h,params) => {
                        return h('div', '合计：'+this.price(params.row.order_amount_total)+' 万');
                    }
                },
                {
                    title: '支付方式',
                    key: 'order_no',
                    minWidth:100,
                    tooltip:true,
                    render:(h,params) => {
                        var type = '';
                        var paytype = params.row.pay_channel;
                        if (paytype == 1){
                            type = '微信支付';
                        } else if (paytype == 2){
                            type = '支付宝支付';
                        }else if (paytype == 3) {
                            type = '银联支付';
                        }
                        return h('div',type);
                    }
                },
                {
                    title: '状态',
                    key: 'status',
                    width:80,
                    render:(h,params)=>{
                        var arr = ['未支付','已支付','已发货','已签收','退货申请','退货中','已退货','取消交易'];
                        var color = ['#f90','#19be6b','#2db7f5','#2d8cf0','red','red','red','red'];
                        var index = Math.abs(params.row.order_status < 0 ? (params.row.order_status+4):params.row.order_status);
                        return h('span',{style: {color:color[index]}},arr[index]);
                    }
                },
                {
                    title:'创建日期',
                    key:'create_time',
                    width: 160,
                },
                {
                    title: '操作',
                    key: 'address',
                    width:180,
                    align: 'center',
                    fixed:'right',
                    render: (h, params) => {
                        return h('div', [
                            h('Button', {
                                props: {
                                    type: 'warning',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px',
                                },
                                domProps:{
                                    disabled:params.row.order_status != 0?true:false
                                },
                                on: {
                                    click: () => {
                                        this.$router.push({path:'/payment',query:{order:params.row}});
                                    }
                                }
                            }, '支付'),
                            h('Button', {
                                props: {
                                    type: 'info',
                                    size: 'small'
                                },
                                style: {
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        // this.$refs.tabelDetails.currentDetailsData(params.row);
                                        // this.showTableDetails = true;
                                        // console.log(1000);
                                        this.$router.push({path:'/order_details',query:{data:params.row}});
                                    }
                                }
                            }, '查看'),
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
                                        this.$Message.error('关闭取消！');
                                    }
                                }
                            }, [h('Button',{
                                props: {
                                    type: 'error' ,
                                    size: 'small',
                                },
                                domProps:{
                                    disabled:params.row.order_status == 0?false:true
                                }
                            },'取消')
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
                status:'',
                date:[]
            },
        }
    },
    created () {
        this.getTableData(this.page,pageOffset);
    },
    mounted() {
        // this.getTableData(this.page,pageOffset);
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
            // this.$router.push('/order_details');
            this.currentPage = this.page;
            this.getTableData(this.page,this.offset,handleSearchData({...this.search}));
        },
        changeStatus (status) {
            if (typeof this.search.status == 'undefined') {
                this.search.status = '';
            }
        },
        changeDate(date){
            (date.length) && (this.search.date = date);
        }
    }
}
</script>

<style lang="less" scoped>
.order{
    /*margin: 12px 12px 0 12px;*/
    height: 80%;
    .search{
        padding: 12px;
        background: #fff;
        /*position: relative;*/
        &>div{
            margin-right: 18px;
            float: left;
        }
    }

    .table{
        .header{
            display: flex;
            background: #f8f8f9;

            .h-item {
                flex: 1;
                padding: 10px 0;
                text-align: center;
                border: 1px solid #eee;
            }
            
            .content{
                background: #ffff;
            }
        }
    }
}
/*.tableData{*/
/*    position: relative;*/
/*    !*height:100%;*!*/
/*}*/
/*.header-dis-inline{*/
/*    display: inline-block;*/
/*    margin: 0 10px;*/
/*}*/
</style>