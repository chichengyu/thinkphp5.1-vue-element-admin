<template>
    <div class="table">
        <el-table style="width: 100%"
            border
            v-loading="data.loading"
            :empty-text="data.table_msg_empty"
            :data="data.tableData"
            :default-sort="data.defaultSort"
            @sort-change="data.sortChange">
            <!-- 列 -->
            <template v-for="(col,key) in data.tableLabel">
                <!-- 有 swicth 开关 -->
                <template v-if="col.isSwitch">
                    <el-table-column
                        :key="key"
                        :type="col.type"
                        :fixed="col.fixed"
                        :prop="col.prop"
                        :label="col.title"
                        :width="col.width"
                        :min-width="col.width"
                        :sortable="col.sort"
                        :formatter="col.formatter"
                        :show-overflow-tooltip="col.tooltip"
                        :align="col.align">
                        <template slot-scope="scope">
                           <el-switch
                               :disabled="col.isSwitch.disabled"
                                v-model="scope.row.status"
                                active-color="#52BEA6"
                                inactive-color="#CACDD0"
                                :active-value="1"
                                :inactive-value="0"
                                @change="col.isSwitch.change(scope.row)">
                            </el-switch>
                        </template>
                    </el-table-column>
                </template>
                <template v-else>
                    <el-table-column
                        :key="key"
                        :type="col.type"
                        :fixed="col.fixed"
                        :prop="col.prop"
                        :label="col.title"
                        :width="col.width"
                        :min-width="col.minWidth"
                        :sortable="col.sort"
                        :formatter="col.formatter"
                        :show-overflow-tooltip="col.tooltip"
                        :align="col.align">
                    </el-table-column>
                </template>
            </template>
            <!-- 操作 -->
            <el-table-column v-if="data.tableOption"
                align="center"
                fixed="right"
                :label="data.tableOption.label"
                :width="data.tableOption.width">
                <template slot-scope="scope">
                    <template v-if="data.tableOption.buttons">
                        <el-button
                            v-for="(item,key) in data.tableOption.buttons"
                            :key="key"
                            v-if="item.prop != 'del'"
                            :type="item.type"
                            @click="item.methods(scope.row)"
                            size="mini">
                            {{ item.title }}
                        </el-button>
                        <el-popover v-else
                            :ref="`popover${scope.$index}`"
                            placement="top-end"
                            width="120" style="margin-left: 10px">
                            <div style="text-align: center; margin: 0">
                                <h4><i class="el-icon-warning" style="margin-right:6px;color:#ff9900;"></i>你确定删除吗？</h4>
                                <el-button type="text" size="mini" style="padding:4px 7px" @click="handleCancel(item,scope)">取消</el-button>
                                <el-button type="primary" size="mini" style="padding:4px 7px" @click="handleOk(item,scope)">确定</el-button>
                            </div>
                            <el-button :type="item.type" size="mini" slot="reference">删除</el-button>
                        </el-popover>
                    </template>
                </template>
            </el-table-column>
        </el-table>

        <!-- 分页 -->
        <el-pagination :align="data.page.align" :total="data.page.total" :current-page="data.page.currentPage" @current-change="data.page.currentChange" background layout="prev, pager, next"></el-pagination>
    </div>
</template>

<script>
export default {
    name:'Table',
    props:{
        data:{
            type:Object,
            default:() => {}
        }
    },
    data() {
        return {
            totalPage:100,
            currentPage:1,
        }
    },
    created(){
        !this.data.hasOwnProperty('sortChange') && (this.data.sortChange=(params)=>{});
        // this.data.page.currentPage?this.data.page.currentPage:1;
    },
    methods: {
        // popover 框  / 确定删除
        handleOk(currentBtn,scope){
            currentBtn.methods.ok(scope);
            scope._self.$refs[`popover${scope.$index}`].$refs.popper.click();
        },
        // popover 框  / 取消删除
        handleCancel(currentBtn,scope){
            scope._self.$refs[`popover${scope.$index}`].$refs.popper.click();
            currentBtn.methods.cancel(scope);
        }
    },
}
</script>

<style lang="css" scoped>
.el-pagination.is-background >>> .btn-next, .el-pagination.is-background >>> .btn-prev, .el-pagination.is-background >>> .el-pager li{
    background-color: #fff;
    font-weight: 400;
}
</style>