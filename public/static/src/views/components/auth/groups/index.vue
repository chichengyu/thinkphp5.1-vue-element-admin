<template>
    <div class="groups">
        dsdsad
    </div>
</template>

<script>
import { getTableData } from '@/api/groups'

export default {
    name: "index",
    data () {
        return {
            loading:false,
            tableData: [],// 所有数据
            pageData:[],// 分页数据
            total:0,// 总共多少条数据,
            page:1,// 第一页
            currentPage:1,// 当前页,
            offset:pageOffset,// 每页显示条数
            groups:null,// 角色组
            rules:null// 分配权限结构数据
        }
    },
    created() {
        this.getTableData(this.page);
    },
    methods:{
        // 获取分页数据
        getTableData (page,pageOffset=10) {
            this.loading = true;
            getTableData(page,pageOffset).then(res => {
                if (res.data.code == 1){
                    this.tableData = res.data.data.data.data;
                    this.total = res.data.data.total;
                    this.groups = res.data.data.groups;
                    this.rules = res.data.data.rules;
                    this.pageData = this.tableData;
                    this.loading = false;
                    console.log(this.pageData);
                }else {
                    jumpUrl(res.data,this);
                }
            });
        },
    }
}
</script>

<style lang="css" scoped>

</style>