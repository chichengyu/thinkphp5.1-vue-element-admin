<template>
    <div class="add">
        <component-form :data="form" :width="80"></component-form>
    </div>
</template>
<script>
import { getPersonal } from '@/api/personal'
export default {
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
                    {prop: 'tel', title: '用户', type: 'input',disabled:true,placeholder:'请输入用户'},
                    {prop: 'password', title: '密码', type: 'input',disabled:true,placeholder:'请输入密码'},
                    {prop: 'name', title: '姓名', type: 'input',disabled:true,placeholder:'请输入姓名'},
                    {prop: 'address_id', title: '地区', type: 'input',disabled:true,placeholder:'请输入地区'},
                    {prop: 'store_name', title: '店面名称', type: 'input',disabled:true,placeholder:'请输入店面名称'},
                    {prop: 'group_title', title: '用户组',type: 'select',disabled:true,key:'title',value:'id',},
                ],
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
                    group_title: [
                        { required: true, message: '请选择用户组', trigger: 'blur' },
                    ],
                }
            },
        }
    },
    created() {
        getPersonal().then(res => {
            if (res.data.code == 1) {
                this.form.formFields = res.data.data;
            } else {
                jumpUrl(res.data, this);
            }
        });
    }
}
</script>
<style lang="css" scoped>

</style>