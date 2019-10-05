<template>
    <div class="table" style="width:100%;margin-top:5px">
        <el-table style="width:100%"
            border
            v-loading="data.loading"
            :element-loading-text="data.loadingText||'Loading'"
            :element-loading-spinner="data.loadingIcon||'el-icon-loading'"
            :empty-text="data.table_msg_empty"
            :data="data.tableData"
            :default-sort="data.defaultSort"
            @sort-change="handleSort">
            <!-- 列 -->
            <el-table-column v-for="(col,key) in data.tableLabel"
                :key="key"
                :type="col.type"
                :fixed="col.fixed"
                :prop="col.prop"
                :label="col.title"
                :width="col.width"
                :min-width="col.minWidth"
                :sortable="col.sort"
                :formatter="col.formatter"
                :align="col.align||'left'">
                <template slot-scope="scope">
                    <!-- 有 swicth 开关 -->
                    <el-switch v-if="col.isSwitch"
                        :style="col.style&&col.style(scope,col)"
                        :disabled="col.isSwitch.disabled"
                        v-model="scope.row.status"
                        active-color="#52BEA6"
                        inactive-color="#ff4949"
                        :active-value="1"
                        :inactive-value="0"
                        @change="col.isSwitch.change && col.isSwitch.change(scope)">
                    </el-switch>
                    <!-- tree树形 -->
                    <div v-else-if="col.hasChildren && scope.row.children && scope.row.children.length > 0" @click="treeClick(scope.row,scope.$index)" :style="Object.assign({marginLeft:(scope.row.xgrade-0.3)+'em',cursor:'pointer'},col.style&&col.style(scope,col)||{})">
                        <i class="el-icon-arrow-down" v-if="scope.row.open"></i>
                        <i class="el-icon-arrow-right" v-else></i>
                        <span>{{ col.render?col.render(scope):scope.row[col.prop] }}</span>
                    </div>
                    <div v-else-if="col.tooltip">
                        <el-tooltip placement="top">
                            <div slot="content" :style="col.style&&col.style(scope,col)">{{ col.render?col.render(scope):scope.row[col.prop] }}</div>
                            <div :style="Object.assign({width: '100%',overflow: 'hidden','white-space':'nowrap','text-overflow':'ellipsis'},col.style&&col.style(scope,col)||{})">{{ col.render?col.render(scope):scope.row[col.prop] }}</div>
                        </el-tooltip>
                    </div>
<!--                    <div v-else>{{ col.render?col.render(scope):scope.row[col.prop] }}</div>-->
                    <div v-else>
                        <div v-if="scope.row.xgrade>0&&col.hasChildren" :style="Object.assign({marginLeft:(scope.row.xgrade+0.6)+'em'},col.style&&col.style(scope,col)||{})">{{ col.render?col.render(scope):scope.row[col.prop] }}</div>
<!--                        <div v-else-if="scope.row.xgrade==0&&col.hasChildren" style="margin-left: 0.9em">{{ col.render?col.render(scope):scope.row[col.prop] }}</div>-->
                        <div v-else :style="col.style&&col.style(scope,col)">{{ col.render?col.render(scope):scope.row[col.prop] }}</div>
                    </div>
                </template>
            </el-table-column>
            <!-- 操作 -->
            <el-table-column v-if="data.tableOption"
                fixed="right"
                :label="data.tableOption.label"
                :width="data.tableOption.width"
                :align="data.tableOption.align||'center'">
                <template slot-scope="scope">
                    <template v-if="data.tableOption.buttons" v-for="(item,key) in data.tableOption.buttons">
<!--                        <div v-if="item.render" v-html="item.render(scope,item)" style="display: inline-block"></div>-->
                        <el-button :key="key" v-if="!item.tooltip"
                            :type="item.type"
                            :style="item.style&&item.style(scope,item)"
                            @click="item.click&&item.click(scope,item)"
                            :size="item.size||'mini'"
                            v-has="item.directives && item.directives.length && item.directives[0].value">
                            {{ item.title }}
                        </el-button>
                        <el-popover v-else
                            :ref="`popover${scope.$index}`"
                            placement="top-end"
                            width="120" style="margin-left: 10px">
                            <div style="text-align: center; margin: 0">
                                <h4 style="margin-top:.6rem;"><i class="el-icon-warning" style="margin-right:6px;color:#ff9900;"></i>{{ item.header||'你确定删除吗？'}}</h4>
                                <el-button type="text" size="mini" style="padding:4px 7px" @click="handleCancel(item,scope)">取消</el-button>
                                <el-button type="primary" size="mini" style="padding:4px 7px" @click="handleOk(item,scope)">确定</el-button>
                            </div>
                            <el-button v-has="item.directives && item.directives.length && item.directives[0].value" :type="item.type" :style="item.style&&item.style(scope,item)" size="mini" slot="reference">{{ item.title||'删除' }}</el-button>
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
        },
    },
    data() {return {count:0}},
    watch:{
        data:{
            immediate:true,
            deep:true,
            handler(val){(this.count === 1) && util.treeTableXcode(val.tableData);}
        }
    },
    created(){
        util.treeTableXcode(this.data.tableData);
        this.count = 1;
    },
    methods: {
        // popover 框  / 确定删除
        handleOk(currentBtn,scope){
            // scope._self.$refs[`popover${scope.$index}`].$refs.popper.click();
            scope._self.$el.click();
            currentBtn.click.ok && currentBtn.click.ok(scope,currentBtn);
        },
        // popover 框  / 取消删除
        handleCancel(currentBtn,scope){
            // scope._self.$refs[`popover${scope.$index}`].$refs.popper.click();
            scope._self.$el.click();
            currentBtn.click.cancel && currentBtn.click.cancel(scope,currentBtn);
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
            /*!item.xgrade && this.data.tableData.some((item,index) => {
                if (item.xcode.includes('-')) {
                    index = item.xcode.substr(0,1);
                    this.collapse(this.data.tableData[index],index);
                    return true;
                }
            });*/
            //展开
            for(var i=0;item.children && i<item.children.length;i++){
                var child = item.children[i];
                // this.data.tableData.splice(++index,0,child);
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
            // this.data.tableData.splice(Number(index)+1,item.children.length);
            this.data.tableData.splice(Number(index)+1,util.size(item.children));
        },
        // 排序
        handleSort(params){this.data.sortChange && this.data.sortChange(params);}
    },
}
var util = {};
util.treeTableXcode = function(data,xcode,xgrade){
    xcode = xcode || "";
    xgrade = xgrade || 0;
    for(var i=0;i<data.length;i++){
        var item = data[i];
        if (item.xcode && !item.xcode.includes('-')){
            break;
        }else{
            item.xcode = xcode + i;
            item.xgrade = xgrade;
            if(item.children && item.children.length > 0){
                util.treeTableXcode(item.children,item.xcode+"-",xgrade+1);
            }
        }
    }
};
util.size = function (data) {
    let len = data.length || 0;
    for(var i=0;i<data.length;i++){
        if (data[i].open && data[i].children){
            len += util.size(data[i].children)
        }
    }
    return len;
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