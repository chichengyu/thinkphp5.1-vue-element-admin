<template>
    <div class="table">
        <el-table
            v-loading="loading"
            :data="tableData"
            tooltip-effect="dark"
            border
            style="width: 100%">
            <el-table-column
                fixed
                type="index"
                prop="id"
                label="ID"
                align="center">
            </el-table-column>
            <el-table-column
                prop="date"
                label="日期"
                width="150">
            </el-table-column>
            <el-table-column
                prop="name"
                label="姓名">
            </el-table-column>
            <el-table-column
                prop="province"
                label="省份">
            </el-table-column>
            <el-table-column
                prop="city"
                label="市区">
            </el-table-column>
            <el-table-column
                prop="address"
                label="地址"
                show-overflow-tooltip>
    <!--            <template slot-scope="scope">-->
    <!--                <el-popover placement="top" trigger="hover">-->
    <!--                    <div>{{scope.row.address}}</div>-->
    <!--                    <span slot="reference">{{ scope.row.address.substr(0,50)+'...' }}</span>-->
    <!--                </el-popover>-->
    <!--            </template>-->
            </el-table-column>
            <el-table-column
                prop="zip"
                label="邮编">
            </el-table-column>
            <el-table-column
                align="center"
                fixed="right"
                label="操作"
                width="250">
                <template slot-scope="scope">
                    <el-button @click="handleClick(scope.row)" size="mini" plain>查看</el-button>
                    <el-button type="primary" size="mini" plain>编辑</el-button>
                    <el-button type="danger" size="mini" plain>删除</el-button>
                </template>
            </el-table-column>
        </el-table>

        <!-- 分页 -->
        <el-pagination align="right" background layout="prev, pager, next" :total="totalPage" :current-page="currentPage" @current-change="changeCurrentPage"></el-pagination>
    </div>
</template>

<script>
export default {
    name:'treeTable',
    methods: {
        handleClick(row) {
            console.log(row);
        },
        changeCurrentPage(currentPage){
            this.loading = false;
            console.log(currentPage);
        }
    },
    data() {
        return {
            loading:true,
            totalPage:100,
            currentPage:1,
            tableData: [{
                id:1,
                date: '2016-05-02',
                name: '王小虎',
                province: '上海',
                city: '普陀区',
                address: '上海市普陀区金沙江路 1518 弄',
                zip: 200333
            }, {
                id:2,
                date: '2016-05-04',
                name: '王小虎',
                province: '上海',
                city: '普陀区',
                address: '上海市普陀区金沙江路 1517 弄',
                zip: 200333
            }, {
                id:3,
                date: '2016-05-01',
                name: '王小虎',
                province: '上海',
                city: '普陀区',
                address: '上海市普陀区金沙江路 1519 弄',
                zip: 200333
            }, {
                id:4,
                date: '2016-05-03',
                name: '王小虎',
                province: '上海',
                city: '普陀区',
                address: '上海市普陀区金沙江路 1516 弄',
                zip: 200333
            }]
        }
    }
}
</script>

<style lang="css" scoped>
.el-pagination.is-background >>> .btn-next, .el-pagination.is-background >>> .btn-prev, .el-pagination.is-background >>> .el-pager li{
    background-color: #fff;
}
</style>