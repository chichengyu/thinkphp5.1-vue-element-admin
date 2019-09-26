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
                        {title:'查看',methods:(params) => {console.log(params)}},
                        {title:'编辑',directives:[{name:'has',value:'edit'}],type:'primary',methods:(params) => {console.log(params)}},
                        {title:'删除',directives:[{name:'has',value:'del'}],type:'danger',methods:{
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
                    console.log(10);
                    console.log(obj);
                },
            }
        }
    },
    created(){
        // this.tableData.map(item => item.visible=false);
        this.labelInit();
        this.tableDataInit(this.tableData.page.currentPage,pageOffset);
    },
    methods: {
        // 列初始化
        labelInit(){
            this.tableData.tableLabel = [
                {prop:'id',title:'ID',type:'index',fixed:true,width:80,align:'center'},
                {prop:'name',title:'名称',width:100},
                {prop:'date',title:'日期',minWidth:150,render:(params) => {
                    // console.log(params);
                    return '日期：' + params.row.date;
                }},
                {prop:'province',title:'省份'},
                {prop:'city',title:'城市'},
                {prop:'address',title:'地址',tooltip:true,width:150},
                {prop:'zip',title:'邮编',sort:'custom'},
                {prop:'status',title:'状态',isSwitch:{change:(currentData) => {console.log('switch开关',currentData)}}}
            ];
        },
        // 数据初始化
        tableDataInit(currentPage,pageOffset,keywords=''){
            /*this.tableData.loading = true;
            getTableData(currentPage,pageOffset,keywords).then(res => {
                if (res.data.code == 1){
                    this.tableData.tableData = res.data.data.data.data;
                    this.tableData.page.total = res.data.data.total;
                }else {
                    jumpUrl(res.data,this);
                }
                this.tableData.loading = false;
            });*/
            let data = [{id:1, date: '2016-05-02', name: '王小虎', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1518 弄', zip: 200333, status:0,},
                {id:2, date: '2016-05-04', name: '王小虎', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1517 弄', zip: 200333, status:1,},
                {id:3, date: '2016-05-01', name: '王小虎', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1519 弄', zip: 200333, status:0,},
                {id:4, date: '2016-05-03', name: '王小虎', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1516 弄', zip: 200333, status:0},
                {id:5, date: '2016-05-01', name: '王小5', province: '上海', city: '普陀区', address: '上海市普陀区金沙江路 1519 弄', zip: 200333, status:0,}];
            this.tableData.tableData = data;
        },
    },
}
</script>

<style lang="css" scoped>

</style>