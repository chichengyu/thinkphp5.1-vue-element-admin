<template>
    <div class="form" style="background:#fff;">
        <component-form :width="80" :data="form">
            <template slot="form">
                <el-form-item label="扩展表单" prop="extend">
                    <el-input v-model="form.formFields.extend"></el-input>
                </el-form-item>
            </template>
        </component-form>
    </div>
</template>

<script>
export default {
    name: "Form",
    data () {
        return {
            form:{
                labelWidth:'200px',
                inline:true,
                labelPosition:'right',
                formFields:{
                    name: '',
                    nameLine:'',
                    number:1,
                    password: '',
                    text: '',
                    select: '',
                    selectGroup:2,
                    cascader:'xiaolv',
                    radio: '1',
                    radiosButton: '1',
                    checkbox: ['1','2'],
                    date: '2019-09-07',
                    switch:true,
                    extend:'扩展表单',
                },
                formLabel:[
                    {prop: 'name', title: '名称', type: 'input',disabled:false,prefixIcon:'el-icon-user',placeholder:'请输入姓名'},
                    {prop: 'nameLine', title: '同行显示', type: 'input',formItemStyle:{width:'500px'},style:{display:'inline-block',width:'100px',transform:'translateX(27px)'}},
                    {prop: 'number', title: '数字', type: 'inputNumber',change:(val)=>{console.log(val)}},
                    {prop: 'password', title: '密码', type: 'input',password:true},
                    {prop: 'text', title: '文本域', type: 'textarea',placeholder: '我是自定义内容'},
                    {prop: 'select', title: '下拉选择',type: 'select',key:'label',value:'value',options:[
                        {label:'北京',value:1,level:0},
                        {label:'上海',value:2,level: 1},
                        {label:'重庆',value:3}],
                        change:(val) => {console.log(val);}
                    },
                    {prop: 'selectGroup', title: '分组下拉选择',type: 'selectGroup',key:'label',value:'value',right:true,header:'label',children:'children',options:[
                            {
                                label:'分组一',
                                children:[
                                    {label:'北京',value:1},
                                    {label:'上海',value:2},
                                    {label:'重庆',value:3}
                                ]
                            }, {
                                label:'分组二',
                                children:[
                                    {label:'成都',value:4},
                                    {label:'天津',value:5},
                                    {label:'绵阳',value:6}
                                ]
                            }],
                        change:(val) => {console.log(val);}
                    },
                    {prop: 'cascader', title: 'Cascader级选择器',type: 'cascader',props:{label:'name',value:'value',children:'children'},right:true,header:'label', options:[{
                            value: 'zhinan',
                            name: '指南',
                            children: [{
                                value: 'shejiyuanze',
                                name: '设计原则',
                                children: [{
                                    value: 'yizhi',
                                    name: '一致'
                                }, {
                                    value: 'fankui',
                                    name: '反馈'
                                }, {
                                    value: 'xiaolv',
                                    name: '效率'
                                }, {
                                    value: 'kekong',
                                    name: '可控'
                                }]
                            }, {
                                value: 'daohang',
                                name: '导航',
                                children: [{
                                    value: 'cexiangdaohang',
                                    name: '侧向导航'
                                }, {
                                    value: 'dingbudaohang',
                                    name: '顶部导航'
                                }]
                            }]
                        }],
                        change:(val) => {console.log(val);}
                    },
                    {prop: 'radio', title: '单选',type: 'radio',options:[
                        {label:'男',value:'1'},
                        {label:'女',value:'2'}],
                        change:(val) => {console.log(val);}
                    },
                    {prop: 'radiosButton', title: '单选按钮',type: 'radioButton',options:[
                        {label:'男',value:'1'},
                        {label:'女',value:'2'}],
                        change:(val) => {console.log(val);}
                    },
                    {prop: 'checkbox', title: '复选框', type: 'checkbox',options:[
                        {label:'上海',value:'1'},
                        {label:'重庆',value:'2'},
                        {label:'北京',value:'3'}],
                        change:(val) => {console.log(val);}
                    },
                    {prop: 'date', title: '日期', type: 'date',format:'yyyy-MM-dd', change:(val) => {console.log(val)}},
                    {prop: 'dater', title: '日期范围', type: 'daterange',format:'yyyy-MM-dd', change:(val) => {console.log(val)}},
                    {prop: 'datetime', title: '日期时间', type: 'datetime',format:'yyyy-MM-dd HH:mm:ss', change:(val) => {console.log(val)}},
                    {prop: 'dateti', title: '日期时间', type: 'datetimerange',width:'100px',size:'small', change:(val) => {console.log(val)}},
                    {prop: 'switch', title: 'switch开关', type: 'switch',change:(val) => {console.log(val)}},
                ],
                buttons:{
                    align:'left',
                    style:{display:'block',width:'600px'},
                    options:[
                        {title:'提交',type:'primary',style:{width:'100px'},loading:false,click:(form,item) => {
                                item.loading = true;
                                console.log(form);
                                console.log(form.validate);
                                console.log(item);
                                form.validate(valid => {
                                if (valid){
                                    console.log('提交');
                                }else{
                                    console.log('验证不通过');
                                }
                            });
                        }},
                        {title:'重置',click:(form) => {
                            console.log('重置');
                            form.resetFields();
                        }},
                    ]
                },
                rules: {
                    name: [
                        { required: true, message: '请输入活动名称', trigger: 'blur' },
                        { min: 3, max: 5, message: '长度在 3 到 5 个字符', trigger: 'blur' }
                    ],
                    number: [
                        { required: true, message: '请选择活动区域', trigger: 'change' }
                    ],
                    password: [
                        { type: 'string', required: true, message: '请选择日期', trigger: 'change' }
                    ],
                    text: [
                        { type: 'string', required: true, message: '请选择时间', trigger: 'change' }
                    ],
                    select: [
                        { type: 'array', required: true, message: '请至少选择一个活动性质', trigger: 'change' }
                    ],
                    radio: [
                        { required: true, message: '请选择活动资源', trigger: 'change' }
                    ],
                    radiosButton: [
                        { required: true, message: '请填写活动形式', trigger: 'blur' }
                    ],
                    extend: [
                        { required: true, message: '请填写表单扩展', trigger: 'blur' },
                        {pattern:this.$validator.regExpChinese,message: '表单扩展输入不正确', trigger: 'blur'}
                    ]
                }
            },
        }
    }
}
</script>

<style lang="css" scoped>

</style>