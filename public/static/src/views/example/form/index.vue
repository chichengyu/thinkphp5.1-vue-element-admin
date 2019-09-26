<template>
    <div class="form" style="background:#fff;">
        <form-data :width="50" :form="form"></form-data>
    </div>
</template>

<script>
import formData from '@/components/form'
export default {
    name: "Form",
    components:{
        formData
    },
    data () {
        return {
            form:{
                formLabelWidth:'300px',
                formItemLabelWidth:'200px',
                formFields:{
                    name: '',
                    number:1,
                    password: '',
                    text: '',
                    select: '',
                    selectGroup:2,
                    radio: '1',
                    radiosButton: '1',
                    checkbox: ['1','2'],
                    date: '2019-09-07',
                    switch:true
                },
                formLable:[
                    {prop: 'name', title: '名称', type: 'input',disabled:false,placeholder:'请输入姓名'},
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
                    {prop: 'dateti', title: '日期时间', type: 'datetimerange', change:(val) => {console.log(val)}},
                    {prop: 'switch', title: 'switch开关', type: 'switch',change:(val) => {console.log(val)}},
                ],
                buttons:{
                    align:'left',
                    options:[
                        {title:'提交',type:'primary',method:(form) => {
                            form.validate(valid => {
                                if (valid){
                                    console.log('提交');
                                }else{
                                    console.log('验证不通过');
                                }
                            });
                        }},
                        {title:'重置',method:(form) => {
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
                        { type: 'date', required: true, message: '请选择日期', trigger: 'change' }
                    ],
                    text: [
                        { type: 'date', required: true, message: '请选择时间', trigger: 'change' }
                    ],
                    select: [
                        { type: 'array', required: true, message: '请至少选择一个活动性质', trigger: 'change' }
                    ],
                    radio: [
                        { required: true, message: '请选择活动资源', trigger: 'change' }
                    ],
                    radiosButton: [
                        { required: true, message: '请填写活动形式', trigger: 'blur' }
                    ]
                }
            },
        }
    }
}
</script>

<style lang="css" scoped>

</style>