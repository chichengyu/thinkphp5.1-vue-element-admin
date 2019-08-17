<template>
    <div class="table">
        <!-- header -->
        <div class="table-header" style="margin: 10px">
<!--            <Button v-has="'account.vue'" type="primary" size="large" @click.stop="showTableAdd=true"><Icon type="md-add"></Icon>添加</Button>-->
<!--            <Button v-has="'export'" type="primary" size="large" @click.stop="handleExportData"><Icon type="ios-download-outline"></Icon>导出</Button>-->
<!--            <div class="header-dis-inline">-->
<!--                <DatePicker type="daterange" v-model="search.date" @on-change="changeDate" split-panels size="large" placeholder="请选择时间区间"></DatePicker>-->
<!--            </div>-->
            <div class="header-dis-inline">
                <Input size="large" v-model="search.keywords" prefix="ios-search" placeholder="车辆名称" style="width: auto" />
            </div>
            <div class="header-dis-inline">
                品牌
                <Select v-model="search.brand" @on-change="changeStatus" style="width:150px" size="large">
                    <Option value="">请选择</Option>
                    <Option v-for="(item,key) in selectData.car_brands" :key="key" :value="item.id">{{item.name}}</Option>
                </Select>
            </div>
            <div class="header-dis-inline">
                类型
                <Select v-model="search.classify" @on-change="changeStatus" style="width:150px" size="large">
                    <Option value="">请选择</Option>
                    <Option v-if="carType" v-for="(item,key) in carType" :key="key" :value="item.id">{{item.name}}</Option>
                </Select>
            </div>
            <div class="header-dis-inline">
                指导价
                <Select v-model="search.price" @on-change="changeStatus" style="width:180px" size="large">
                    <Option value="">请选择</Option>
                    <Option v-if="price" v-for="(item,key) in price" :key="key" :value="item.value">{{item.name}}</Option>
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
        <table-details ref='tabelDetails' :selectData="selectData" :show="showTableDetails" @showTableDetailsEvent='showTableDetailsEvent'></table-details>


        <!-- 分页 -->
        <Page align='right' :current="currentPage" :styles='{marginTop:"10px"}' :total="total" @on-change='getPageData' @on-page-size-change="changeOffset" show-sizer />
    </div>
</template>
<script>
import TableDetails from './components/details.vue'
import TableOrder from './components/order.vue'
import Loading from '@/components/admin/loading/load.vue'
import { getTableData,del,editRec,excel } from '@/api/admin/car.js'
import { pageOffset,jumpUrl,handleSearchData } from "@/env.js"

export default {
    components:{
        TableDetails,
        TableOrder,
        Loading
    },
    data () {
        return {
            showModal:false,// 模态框
            // exportShow:false,// 导出excel模态确认
            // showTableAdd:false, // 抽屉添加
            // showTableEdit:false, // 抽屉编辑
            showTableDetails:false, // 查看
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
                    title: 'logo',
                    key: 'thumbnail',
                    width:100,
                    align:'center',
                    render:(h,params) => {
                        var url = params.row.thumbnail;
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
                            on:{
                                click:() => {
                                    this.currentTableData = params.row;
                                    // this.currentTableData.logo_url = url;
                                    // this.currentTableData.thumbnail = url;
                                    this.showModal = true;
                                }
                            }
                        });
                    }
                },
                {
                    title: '名称',
                    key: 'name',
                    minWidth:250,
                    tooltip:true,
                },
                {
                    title: '品牌',
                    key: 'brand_name',
                    minWidth:100,
                    tooltip:true,
                },
                {
                    title: '车型',
                    key: 'type_name',
                    minWidth:100,
                    tooltip:true,
                },
                {
                    title: '类别',
                    key: 'classify',
                    minWidth:100,
                    render:(h,params) => {
                        var index = params.row.classify;
                        // var name = ['商品车','运损车','换代新车','库存车','采集车','活动车'];
                        return h('span',this.carType[index-1].name)
                    }
                },
                {
                    title: '生产年份',
                    key: 'years',
                    minWidth:100,
                    render:(h,params) => {
                        let years = params.row.years;
                        let month = params.row.month;
                        if (month){
                            years += ' / ' + (month < 10 ? ('0'+month):month);
                        }
                        return h('span',years);
                    }
                },
                {
                    title: '库存',
                    key: 'car_number',
                    align: 'center',
                    minWidth:100,
                    render:(h,params) => {
                        let number = params.row.car_number ? params.row.car_number:0;
                        return h('span',number);
                    }
                },
                {
                    title: '颜色',
                    key: 'car_color',
                    align: 'center',
                    minWidth:150,
                    tooltip:true,
                    render:(h,params) => {
                        var color = params.row.car_color && params.row.car_color.split(',');
                        var arr = [];
                        if (color){
                            for(let key in color){
                                let str = color[key];
                                if (str){
                                    let colorArr = str.split('-');
                                    arr.push(colorArr[0]);
                                }
                            }
                        }
                        return h('div',arr.join(','));
                    },
                },
                {
                    title: '指导价(万元)',
                    key: 'price_zdj',
                    minWidth:110
                },
                {
                    title: '特价(万元)',
                    key: 'price_ptj',
                    align: 'center',
                    minWidth:110
                },
                {
                    title: '门店价(万元)',
                    key: 'price_mdj',
                    align: 'center',
                    minWidth:110
                },
                {
                    title: '经销商价(万元)',
                    key: 'price_jxs',
                    align: 'center',
                    minWidth:120
                },
                {
                    title: '备注',
                    key: 'remarks',
                    minWidth:80,
                    tooltip:true
                },
                {
                    title: '状态',
                    key: 'status',
                    width:80,
                    render:(h,params)=>{
                        var color = '';
                        var text = '';
                        var status = params.row.status;
                        if (params.row.car_number){
                            if (status == 1){
                                color = '#f90';
                                text = '待上架';
                            }else if (status == 2){
                                color = '#19be6b';
                                text = '上架';
                            }else {
                                color = 'red';
                                text = '下架';
                            }
                        }else {
                            color = 'red';
                            text = '已售完';
                        }
                        return h('span',{style: {color}},text);
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
                    width:120,
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
                                    marginRight: '5px'
                                },
                                on: {
                                    click: () => {
                                        this.$router.push({name:'addshopcar',params:params.row});
                                    }
                                }
                            }, '详情'),
                            // h('Button', {
                            //     props: {
                            //         type: 'info',
                            //         size: 'small'
                            //     },
                            //     style: {
                            //         marginRight: '5px'
                            //     },
                            //     on: {
                            //         click: () => {
                            //             this.$refs.tabelDetails.currentDetailsData(params.row);
                            //             this.showTableDetails = true;
                            //         }
                            //     }
                            // }, '查看'),
                            // h('Poptip',{
                            //     props: {
                            //         confirm: true,
                            //         title: '您确认删除这条内容吗？',
                            //         placement: 'top',
                            //         transfer:true,
                            //     },
                            //     directives:[{name:'has',value:'del'}],
                            //     on: {
                            //         'on-ok': () => {
                            //             if(this.pageData.length == 1){
                            //                 if (this.currentPage > 1){
                            //                     this.currentPage = this.currentPage - 1;
                            //                 }else {
                            //                     this.currentPage = 1;
                            //                 }
                            //             }
                            //             this.del(params.row.id);
                            //         },
                            //         'on-cancel': () => {
                            //             this.$Message.error('取消删除');
                            //         }
                            //     }
                            // }, [h('Button',{
                            //         props: {
                            //             type: 'error' ,
                            //             size: 'small',
                            //         },
                            //     },'删除')
                            // ])
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
            selectData:{// 下拉选框数据
                car_brands:null,
                car_type:null,
                // car_level:null,
                car_country:null
            },
            carType:[// 商品车 运损车 换代新车 库存车 采集车 活动车
                {id:1,name:'商品车'},
                {id:2,name:'运损车'},
                {id:3,name:'换代新车'},
                {id:4,name:'库存车'},
                {id:5,name:'集采车'},
                {id:6,name:'活动车'},
            ],
            price:[
                {name:'10万以下',value:'0,10'},
                {name:'10万 ~ 15万',value:'10,15'},
                {name:'15万 ~ 20万',value:'15,20'},
                {name:'20万 ~ 40万',value:'20,40'},
                {name:'40万以上',value:'40'},
            ],
            // 搜索条件
            search:{
                keywords:'',
                brand:'',
                classify:'',
                date:[],
                price:''
            },
            // 库存
            showCarStockModal:false,
        }
    },
    created () {
        this.getTableData(this.page,pageOffset);
    },
    watch:{
        showCarStockModal (old,now) {
            now && this.getTableData(this.currentPage,pageOffset,handleSearchData({...this.search}));
        }
    },
    methods:{
        // 获取分页数据
        getTableData (page,pageOffset,keywords='') {
            this.isShowLoading = true;
            getTableData(page,pageOffset,keywords).then(res => {
                if (res.data.code == 1){
                    res = res.data.data;
                    this.tableData = res.data;
                    this.total = res.total;
                    this.selectData.car_brands = res.car_brands;
                    this.selectData.car_type = res.car_type;
                    this.selectData.car_country = res.car_country;
                    // this.selectData.car_level = res.car_level;
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
        // 查看
        showTableDetailsEvent () {
            this.showTableDetails = false;
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
            this.getTableData(this.page,pageOffset,handleSearchData({...this.search}));
        },
        changeStatus (status) {
            if (typeof this.search.brand == 'undefined') {
                this.search.brand = '';
            }
            if (typeof this.search.classify == 'undefined') {
                this.search.classify = '';
            }
            if (typeof this.search.price == 'undefined') {
                this.search.price = '';
            }
        },
        // 导出数据
        // handleExportData (e) {
        //     if (this.search.date.every(item => item != '')){
        //         let params = '?start=' + this.search.date[0] + '&end=' + this.search.date[1];
        //         excel(params).then(res => {
        //             if (res.data.code == 1){
        //                 // iframe 无刷新下载
        //                 let oi = document.createElement('iframe');
        //                     oi.style.display = 'none';
        //                     oi.src = res.data.data.download_url;
        //                     document.body.appendChild(oi);
        //                 this.$Message.success(res.data.msg);
        //             }else {
        //                 this.$Message.error(res.data.msg);
        //             }
        //         }).catch(err => {
        //             this.$Message.error('导出失败！');
        //         });
        //     } else {
        //         this.$Message.error('请先选择时间区间');
        //     }
        // },
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
</style>