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
                page:{align:'right'},
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
        this.tableData.tableData = this.tableDataInit();
        this.handlePage();
    },
    methods: {
        // 列初始化
        labelInit(){
            return [{prop:'id',title:'ID',type:'index',fixed:true,align:'center'},
                    {prop:'name',title:'名称'},
                    {prop:'date',title:'日期',formatter:(row, column, value, index) => {
                        // console.log(row);
                        // console.log(column);
                        // console.log(cellValue);
                        // console.log(index);
                        return value + '--' + index;
                    }},
                    {prop:'province',title:'省份'},
                    {prop:'city',title:'城市'},
                    {prop:'address',title:'地址',tooltip:true},
                    {prop:'zip',title:'邮编',sort:'custom'},
                    {prop:'status',title:'状态',isSwitch:{change:(currentData) => {console.log('switch开关',currentData)}}}];
        },
        // 数据初始化
        tableDataInit(){
            return [{id:1, date: '2016-05-02', name: '王小虎', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1518 弄', zip: 200333, status:0,},
                {id:2, date: '2016-05-04', name: '王小虎', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1517 弄', zip: 200333, status:1,},
                {id:3, date: '2016-05-01', name: '王小虎', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1519 弄', zip: 200333, status:0,},
                {id:3, date: '2016-05-01', name: '王小虎', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1519 弄', zip: 200333, status:0,},
                {id:4, date: '2016-05-03', name: '王小虎', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1516 弄', zip: 200333, status:0,}]
        },
        // 分页初始化处理
        handlePage(){
            // this.tableData.page.total = 60;
            this.tableData.page.total = this.tableData.tableData.length;
            this.tableData.page.currentChange = (currentPage) => {
                console.log('当前页',currentPage);
                this.tableData.loading = true;
                setTimeout(() => {
                    this.tableData.loading = false;
                },1500)
            }
        }
    },
}
</script>

<style lang="css" scoped>
.el-pagination.is-background >>> .btn-next, .el-pagination.is-background >>> .btn-prev, .el-pagination.is-background >>> .el-pager li{
    background-color: #fff;
}
</style>