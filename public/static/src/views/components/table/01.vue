<template>
    <div>
        <el-table
                :data="table.tableData"
                @sort-change="sortChange"
                :empty-text="$t('MSG_TABLE_EMPTY')"
                :default-sort="table.defaultSort"
                style="width: 100%">
            <el-table-column
                    v-for="col in table.tableLabel"
                    :type="col.type"
                    :fixed="col.fixed"
                    :prop="col.prop"
                    :label="$t(col.title)"
                    :min-width="col.width"
                    :sortable="col.sort"
                    :formatter="col.formatter"
                    :show-overflow-tooltip="col.ellipsis">
            </el-table-column>
            <el-table-column
                    v-if="table.status"
                    width="100"
                    :label="$t('STATUS')">
                <template slot-scope="scope">
                    <el-switch
                            :disabled="table.status.disabled"
                            v-model="scope.row.active"
                            active-color="#52BEA6"
                            inactive-color="#CACDD0"
                            :active-value="1"
                            :inactive-value="0">
                    </el-switch>
                </template>
            </el-table-column>
            <el-table-column
                    v-if="table.tableOption"
                    fixed="right"
                    align="center"
                    header-align="center"
                    :label="$t('ACTION')"
                    :width="table.tableOption.width">
                <template slot-scope="scope">
                    <el-button v-for="btn in table.tableOption.buttons"
                               @click="handleButton(scope.row,btn.methods)" type="text" size="small">
                        {{ $t(btn.label) }}
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <div class="block">
            <el-pagination
                    @size-change="handleSizeChange"
                    @current-change="handleCurrentChange"
                    :page-sizes="[10,25,50]"
                    layout="total, sizes, prev, pager, next"
                    :total="table.total">
            </el-pagination>
        </div>
    </div>
</template>

<script>
    export default {
        name: "voTable",
        props: {table: Object, pageParam: Object},
        methods: {
            sortChange(obj) {
                let orderBy = {};
                if (obj.order) {
                    if (obj.order.substr(0, 1) === 'a') {
                        orderBy = obj.prop + " asc"
                    }
                    if (obj.order.substr(0, 1) === 'd') {
                        orderBy = obj.prop + " desc"
                    }
                }
                this.$emit('search', {
                    pageNo: this.pageParam.pageNo,
                    pageSize: this.pageParam.pageSize,
                    orderBy: orderBy
                });
            },
            handleButton(row, methods) {
                this.$emit('action', {'row': row, 'methods': methods});
            },
            handleSizeChange(val) {
                this.$emit('search', {pageNo: this.pageParam.pageNo, pageSize: val, orderBy: this.pageParam.orderBy});
            },
            handleCurrentChange(val) {
                this.$emit('search', {pageNo: val, pageSize: this.pageParam.pageSize, orderBy: this.pageParam.orderBy});
            }
        }
    }
</script>
<style scoped>

</style>