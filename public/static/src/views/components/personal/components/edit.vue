<template>
    <div class="add">
        <component-form :data="form" :width="80"></component-form>
    </div>
</template>
<script>
import md5 from 'md5'
import { editPassword } from '@/api/personal'

export default {
    data () {
        return {
            formData:null,
            form:{
                labelWidth:'200px',
                formFields:{
                    password: '',
                    new_password: '',
                },
                formLabel:[
                    {prop: 'password', title: '旧密码', type: 'input',password:true,placeholder:'请输入旧密码（ 8 ~ 16 位）'},
                    {prop: 'new_password', title: '新密码', type: 'input',password:true,placeholder:'请输入新密码（ 8 ~ 16 位）',msg:'密码8到16位（字母，数字，下划线，减号）任意组合',msgStyle:{fontSize:'12px',color:'#606266'}}],
                buttons:{
                    align:'center',
                    options:[
                        {title:'提交',type:'primary',loading:false,click:(form,item) => {
                            form.validate(valid => {
                                if (valid){
                                    item.loading = true;
                                    editPassword({
                                        password:md5(this.form.formFields.password),
                                        new_password:md5(this.form.formFields.new_password),
                                    }).then(res => {
                                        if (res.data.code == 1){
                                            this.success(res.data.msg,()=>{
                                                this.alert('请重新登陆',()=>{
                                                    this.$parent.$parent.$parent.logout();
                                                },false,{showClose:false});
                                            });
                                            this.$emit('handleChildrenCloseDialog');
                                        }else{
                                            this.error(res.data.msg);
                                        }
                                        setTimeout(()=>{
                                            item.loading = false;
                                        },2000);
                                    });
                                }
                            })
                        }}
                    ]
                },
                rules: {
                    password: [
                        { required: true, message: '请输入旧密码', trigger: 'blur' },
                        { pattern:this.validator.regExpPassword, message: '密码格式不正确', trigger: 'blur' }
                    ],
                    new_password: [
                        { required: true, message: '请输入新密码', trigger: 'blur' },
                        { validator:(rule,val,callback) => {
                            if (this.validator.isEmpty(val)){
                                return callback(new Error('新密码不能为空'));
                            }else if (!this.validator.regExpPassword.test(val)){
                                return callback(new Error('新密码格式不正确'));
                            }else if (this.form.formFields.password == val){
                                return callback(new Error('新密码不能与旧密码一致'));
                            }
                            return callback();
                        }, trigger: 'blur' }
                    ],
                }
            },
        }
    },
    methods:{
        currentData(currentData){
            this.$nextTick(() => {
                currentData.password = '默认密码 123456789';
                currentData&&(this.form.formFields = currentData);
            })
        }
    }
}
</script>
<style lang="css" scoped>

</style>