<template>
    <div class="add">
        <component-form :data="form" :width="80"></component-form>
    </div>
</template>
<script>
import { add } from '@/api/adminUser'

export default {
    props:['groups'],
    data () {
        return {
            form:{
                labelWidth:'200px',
                formFields:{
                    tel: '',
                    password: '默认密码 123456789',
                    name: '',
                    address_id: '',
                    store_name: '',
                    group_id: '',
                },
                formLabel:[
                    {prop: 'tel', title: '手机', type: 'input',placeholder:'请输入手机'},
                    {prop: 'password', title: '密码', type: 'input',disabled:true,placeholder:'请输入密码'},
                    {prop: 'name', title: '姓名', type: 'input',placeholder:'请输入姓名'},
                    {prop: 'address_id', title: '地区', type: 'input',placeholder:'请输入地区'},
                    {prop: 'store_name', title: '店面名称', type: 'input',placeholder:'请输入店面名称'},
                    {prop: 'group_id', title: '用户组',type: 'select',key:'title',value:'id',options:this.groups,change:(val) => {this.form.formFields.group_id = val;}},
                ],
                buttons:{
                    align:'center',
                    options:[
                        {title:'提交',type:'primary',loading:false,click:(form,item) => {
                            form.validate(valid => {
                                if (valid){
                                    item.loading = true;
                                    add(this.form.formFields).then(res => {
                                        if (res.data.code == 1){
                                            this.success(res.data.msg);
                                            this.$emit('handleGetTableData');
                                        }else{
                                            this.error(res.data.msg);
                                        }
                                        setTimeout(()=>{
                                            item.loading = false;
                                        },2000);
                                    })
                                }
                            });
                        }},
                        {title:'重置',click:(form) => form.resetFields()},
                    ]
                },
                rules: {
                    tel: [
                        { required: true, message: '请输入手机', trigger: 'blur' },
                        { pattern:this.validator.regExpPhone, message: '手机不正确', trigger: 'blur' }
                    ],
                    name: [
                        { required: true, message: '请输入姓名', trigger: 'blur' },
                    ],
                    address_id: [
                        { required: true, message: '请输入地区', trigger: 'blur' },
                    ],
                    store_name: [
                        { required: true, message: '请输入店面名称', trigger: 'blur' },
                    ],
                    group_id: [
                        { required: true, message: '请选择用户组', trigger: 'blur' },
                    ],
                }
            },
        }
    },
}
</script>
<style lang="css" scoped>

</style>