<template>
    <div class="table">
        <el-table style="width: 100%"
            border
            v-loading="data.loading"
            :empty-text="data.table_msg_empty"
            :data="data.tableData"
            :default-sort="data.defaultSort"
            @sort-change="data.sortChange">

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
                        :align="col.align||'left'">
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
                        :min-width="col.width"
                        :sortable="col.sort"
                        :formatter="col.formatter"
                        :align="col.align||'left'">
                        <template slot-scope="scope">
                            <div v-if="col.hasChildren && scope.row.children && scope.row.children.length > 0" @click="treeClick(scope.row,scope.$index)" style="margin-left:-1.3em;cursor: pointer;">
                                <i class="el-icon-arrow-down" v-if="scope.row.open"></i>
                                <i class="el-icon-arrow-right" v-else></i>
                                <span>{{ scope.row[col.prop] }}</span>
                            </div>
                            <div v-else-if="col.tooltip">
                                <el-tooltip placement="top">
                                    <div slot="content">{{ scope.row[col.prop] }}</div>
                                    <div style="width: 100%;overflow: hidden;white-space: nowrap;text-overflow: ellipsis">{{ scope.row[col.prop] }}</div>
                                </el-tooltip>
                            </div>
                            <div v-else>
                                <div v-if="col.render">{{ col.render(scope) }}</div>
                                <div v-else>{{ scope.row[col.prop] }}</div>
                            </div>
                        </template>
                    </el-table-column>
                </template>
            </template>

            <el-table-column v-if="data.tableOption"
                fixed="right"
                :label="data.tableOption.label"
                :width="data.tableOption.width"
                :align="data.tableOption.align||'center'">
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
                                <h4 style="margin-top:.6rem;"><i class="el-icon-warning" style="margin-right:6px;color:#ff9900;"></i>你确定删除吗？</h4>
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
        return {}
    },
    created(){
        util.treeTableXcode(this.data.tableData);
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
        },
        treeClick:function(item,index){
            if(item.open){
                this.collapse(item,index);
            }else{
                this.expand(item,index);
            }
        },
        // 树形表格点击展开
        expand:function(item,index){
            if(!item.children){
                return index;
            }
            this.data.tableData.some((item,index) => {
                if (item.xcode.includes('-')) {
                    index = item.xcode.substr(0,1);
                    this.collapse(this.data.tableData[index],index);
                }
            });
            //展开
            for(var i=0;item.children && i<item.children.length;i++){
                var child = item.children[i];
                this.data.tableData.splice(++index,0,child);
                if(child.children && child.children.length > 0 && child.open){
                    index = this.expand(child,index);
                }
            }
            item.open = true;
            return index;
        },
        // 树形表格点击收缩
        collapse:function(item,index){
            if(!item.children)return index;
            //收缩
            item.open = false;
            this.data.tableData.splice(Number(index)+1,item.children.length);
        },
    },
}
var util = {};
util.treeTableXcode = function(data,xcode){
    xcode = xcode || "";
    for(var i=0;i<data.length;i++){
        var item = data[i];
        item.xcode = xcode + i;
        if(item.children && item.children.length > 0){
            util.treeTableXcode(item.children,item.xcode+"-");
        }
    }
}
</script>

<style lang="css" scoped>
.el-pagination.is-background >>> .btn-next, .el-pagination.is-background >>> .btn-prev, .el-pagination.is-background >>> .el-pager li{
    background-color: #fff;
    font-weight: 400;
}
.el-pagination{
    margin-top: 10px;
    margin-bottom: 10px;
}
</style>