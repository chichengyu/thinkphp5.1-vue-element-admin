<template>
    <div class="table">
        <table-data :data="tableData"></table-data>
    </div>
</template>

<script>
import TableData from '@/components/table'

export default {
    name:'Table',
    components:{
        TableData
    },
    data() {
        return {
            visible:false,
            totalPage:100,
            currentPage:1,
            tableData: {
                loading:false,
                // 请求回来的数据
                tableData:[],
                // 列
                tableLabel:[],
                // 操作
                tableOption:{
                    label:'操作',
                    width:230,
                    buttons:[
                        {prop:'detail',title:'查看',methods:(params) => {console.log(params)}},
                        {prop:'edit',title:'编辑',type:'primary',methods:(params) => {console.log(params)}},
                        {prop:'del',title:'删除',type:'danger',methods:{
                                ok:(params) => {console.log('确认删除',params)},
                                cancel:(params) => {console.log('取消删除',params)}
                        }},
                    ]
                },
                // 分页
                page:{align:'right',total:1,currentPage:1,currentChange:(currentPage) => {
                    console.log('当前页',currentPage);
                    this.tableData.loading = true;
                    setTimeout(() => {
                        this.tableData.loading = false;
                    },1500)
                }},
                // 排序
                sortChange(obj) {
                    console.log(obj);
                },
            }
        }
    },
    created(){
        // this.tableData.map(item => item.visible=false);
        this.tableData.tableLabel = this.labelInit();
        this.tableData.tableData = this.tableDataInit(this.tableData.page.currentPage,pageOffset);
        this.tableData.page.total = this.tableData.tableData.length;
    },
    methods: {
        // 列初始化
        labelInit(){
            return [{prop:'id',title:'ID',type:'index',fixed:true,width:80},
                {prop:'name',title:'名称',width:100,hasChildren:true,align:'center'},
                {prop:'date',title:'日期',render:(params) => {
                        // console.log(params);
                        return '--'+params.row.date;
                    }},
                {prop:'province',title:'省份'},
                {prop:'city',title:'城市'},
                {prop:'address',title:'地址',tooltip:true},
                {prop:'zip',title:'邮编',sort:'custom'},
                {prop:'status',title:'状态',isSwitch:{change:(currentData) => {console.log('switch开关',currentData)}}}];
        },
        // 数据初始化
        tableDataInit(page,pageOffset,keywords=''){
            /*this.tableData.loading = true;
            getTableData(page,pageOffset,keywords).then(res => {
                if (res.data.code == 1){
                    this.tableData.tableData = res.data.data.data.data;
                    this.tableData.page.total = res.data.data.total;
                }else {
                    jumpUrl(res.data,this);
                }
                this.tableData.loading = false;
            });*/
            return [{id:1, date: '2016-05-02', name: '王小虎', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1518 弄', zip: 200333, status:0,},
                {id:2, date: '2016-05-04', name: '王小虎', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1517 弄', zip: 200333, status:1,},
                {id:3, date: '2016-05-01', name: '王小虎', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1519 弄', zip: 200333, status:0,},
                {
                    id:4,
                    date: '2016-05-03',
                    name: '王小虎',
                    province: '上海',
                    city: '普陀区',
                    address: '上海市普陀区金沙江路 1516 弄',
                    zip: 200333,
                    status:0,
                    children: [{
                        id: 31,
                        date: '2016-05-01',
                        level: '2',
                        name: '1111',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1519 弄',
                        status:0,
                        zip: 200333,
                    }, {
                        id: 32,
                        date: '2016-05-01',
                        level: '2',
                        name: '2222',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1519 弄',
                        status:0,
                        zip: 200333,
                    }]},
                {id:5, date: '2016-05-01', name: '王小5', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1519 弄', zip: 200333, status:0,},
                {
                    id:6,
                    date: '2016-05-03',
                    name: '王小虎',
                    province: '上海',
                    city: '普陀区',
                    address: '上海市普陀区金沙江路 1516 弄',
                    zip: 200333,
                    status:0,
                    children: [{
                        id: 41,
                        date: '2016-05-01',
                        level: '2',
                        name: '3333',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1519 弄',
                        status:0,
                        zip: 200333,
                    }, {
                        id: 42,
                        date: '2016-05-01',
                        level: '2',
                        name: '4444',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1519 弄',
                        status:0,
                        zip: 200333,
                    },{
                        id: 43,
                        date: '2016-05-01',
                        level: '2',
                        name: '5555',
                        province: '上海',
                        city: '普陀区',
                        address: '上海市普陀区金沙江路 1519 弄',
                        status:0,
                        zip: 200333,}]}]
        },
    },
}
</script>

<style lang="css" scoped>

</style>