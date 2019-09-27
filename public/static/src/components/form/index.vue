<template>
    <div class="form" :style="'width:'+width+'%;padding: 32px 32px 32px 0;background:#fff;'">
        <el-form ref="form" :inline="form.inline||false" :model="form.formFields" :rules="form.rules" :label-width="form.labelWidth||'100px'" :label-position="form.labelPosition||'right'" class="demo-ruleForm">
            <el-form-item v-for="(item,index) in form.formLable" :key="index" :label="item.title" :prop="item.prop" :style="item.style||''">
                <!-- 输入框 -->
                <el-input v-if="item.type==='input'" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :show-password="item.password" :size="item.size" :disabled="item.disabled" :style="{width:item.width}" :prefix-icon="item.prefixIcon||''" :placeholder="item.placeholder"></el-input>
                <!-- 数字输入框 -->
                <el-input-number v-if="item.type==='inputNumber'" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :min="item.min" :max="item.max" :step="item.step" :size="item.size" :disabled="item.disabled" :style="{width:item.width}"></el-input-number>
                <!-- 文本域 -->
                <el-input v-if="item.type==='textarea'" type="textarea" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :rows="item.rows || 2" :disabled="item.disabled" :resize="item.resize||'none'" :style="{width:item.width}" :placeholder="item.placeholder||'请输入内容'"></el-input>
                <!-- 下拉框 -->
                <el-select v-if="item.type==='select'" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :size="item.size" :disabled="item.disabled" :style="{width:item.width}" placeholder="请选择">
                    <el-option label="请选择" value="0"></el-option>
                    <el-option v-for="(subItem,key) in item.options" :key="key" :label="subItem[item.key||'label']" :value="subItem[item.value||'value']" :disabled="form.formFields[item.value||'value']!=subItem[item.value||'value']?false:true">
                        <span style="float: left">{{ '└―'.repeat(subItem.level||0) + ' ' + subItem[item.key||'label']}}</span>
                    </el-option>
                </el-select>
                <!-- 分组下拉框 -->
                <el-select v-if="item.type==='selectGroup'" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :size="item.size" :disabled="item.disabled" :style="{width:item.width}" placeholder="请选择">
                    <el-option label="请选择" value="0" style="color:#909399;font-size: 13px"></el-option>
                    <div v-for="(subItem,key) in item.options" :key="key">
                        <div style="padding:.6em;font-size:12px;color:#909399;user-select:none;cursor:no-drop">{{ subItem[item.header||'label'] }}</div>
                        <el-option v-if="subItem[item.children||'children']" v-for="(subItemChild,k) in subItem[item.children||'children']" :key="k" :label="subItemChild[item.key||'label']" :value="subItemChild[item.value||'value']" :disabled="form.formFields[item.prop]!=subItemChild[item.value||'value']?false:true">
                            <span style="float: left">{{ subItemChild[item.key||'label'] }}</span>
                            <span v-if="item.right" style="float: right; color: #8492a6; font-size: 13px">{{ subItem[item.header||'label'] }}</span>
                        </el-option>
                    </div>
                </el-select>
                <!-- 单选 -->
                <el-radio-group v-if="item.type==='radio'" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :size="item.size" :disabled="item.disabled">
                    <el-radio v-for="(subItem,key) in item.options" :key="key" :label="subItem.value">{{ subItem.label }}</el-radio>
                </el-radio-group>
                <!-- 单选按钮 -->
                <el-radio-group v-if="item.type==='radioButton'" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :size="item.size" :disabled="item.disabled">
                    <el-radio-button v-for="(subItem,key) in item.options" :key="key" :label="subItem.value">{{ subItem.label }}</el-radio-button>
                </el-radio-group>
                <!-- 复选框 -->
                <el-checkbox-group v-if="item.type==='checkbox'" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :size="item.size" :disabled="item.disabled" :text-color="item.textColor">
                    <el-checkbox v-for="(subItem,key) in item.options" :key="key" :label="subItem.value">{{ subItem.label }}</el-checkbox>
                </el-checkbox-group>
                <!-- 日期 -->
                <el-date-picker v-if="item.type==='date'" type="date" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :value-format="item.format" :size="item.size" :disabled="item.disabled" :style="{width:item.width}" placeholder="选择日期"></el-date-picker>
                <!-- 日期范围 -->
                <el-date-picker v-if="item.type==='daterange'" type="daterange" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :value-format="item.format" :size="item.size" :disabled="item.disabled" :style="{width:item.width}" start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
                <!-- 日期时间 -->
                <el-date-picker v-if="item.type==='datetime'" type="datetime" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :value-format="item.format" :size="item.size" :disabled="item.disabled" :style="{width:item.width}" placeholder="选择日期"></el-date-picker>
                <!-- 日期时间范围 -->
                <el-date-picker v-if="item.type==='datetimerange'" type="datetimerange" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :value-format="item.format" :size="item.size" :disabled="item.disabled" :style="{width:item.width}" start-placeholder="开始时间" end-placeholder="结束时间"></el-date-picker>
                <!-- swicth开关 -->
                <el-switch v-if="item.type==='switch'" v-model="form.formFields[item.prop]" @change="item.change&&item.change(form.formFields[item.prop])" :disabled="item.disabled" :active-value="item.activeValue" :inactive-value="item.inactiveValue" active-color="#52BEA6" inactive-color="#ff4949" :active-text="item.activeText||''" :inactive-text="item.inactiveText||''"></el-switch>
            </el-form-item>
            <!-- 按钮操作 -->
            <el-form-item v-if="form.buttons&&form.buttons.options" :align="form.buttons.align||'left'">
                <el-button v-for="(item,key) in form.buttons.options" :type="item.type" :size="item.size" :key="key" :disabled="item.disabled" @click="item.method && item.method($refs['form'])">{{ item.title }}</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
export default {
    name: "Form",
    props:{
        width:{
            type:Number,
            default:100
        },
        form:{
            type:Object,
            required:true
        }
    },
}
</script>

<style lang="css" scoped>

</style>