<template>
    <div class="edit" style="padding-right: 120px;">
        <Form ref="form" :model="formData" :rules="ruleCustom" :label-width="120">
            <Row :gutter="32">
                <Col>
                    <FormItem label="手机" prop="tel" label-position="top">
                        <Input v-model="formData.tel" placeholder="请输入手机号码" />
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="密码" prop="password" label-position="top">
                        <Input disabled type="password" v-model="formData.password" placeholder="请输入密码" />
                        <i>密碼8到16位（字母，数字，下划线，减号）任意组合</i>
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="确认密码" prop="password_comfirm" label-position="top">
                        <Input disabled type="password" v-model="formData.password_comfirm" placeholder="请再次输入密码" />
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="姓名" prop="name" label-position="top">
                        <Input v-model="formData.name" placeholder="请输入姓名" />
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="职位" prop="position" label-position="top">
                        <Input v-model="formData.position" placeholder="请输入职位" />
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="角色" prop="group_id" label-position="top">
                        <Select v-model="formData.group_id">
                            <Option v-for="item in groups" :value="item.id" :key="item.id">{{ item.title }}</Option>
                        </Select>
                    </FormItem>
                </Col>
            </Row>
        </Form>
        <div class="footer">
            <Button style="margin-right: 8px" @click="showDrawer">返回</Button>
            <Button type="primary" :loading="loading" @click="handleSubmit">提交</Button>
        </div>
    </div>
</template>
<script>
    import { edit } from '@/api/admin/personnel.js'
    export default {
        props:['show','groups'],
        data () {
            return {
                loading: false,
                styles: {
                    height: 'calc(100% - 55px)',
                    overflow: 'auto',
                    paddingBottom: '53px',
                    position: 'static'
                },
                formData: {
                    tel:'',
                    password:'',
                    password_comfirm:'',
                    name: '',
                    position:'',
                    group_id:''
                },
                ruleCustom: {
                    tel: [
                        { required: true, message: '手机号码必须填写', trigger: 'blur' },
                        { pattern:/^1(3|4|5|7|8|9)[0-9]\d{8}$/, message: '手机号码必须填写真实有效号码', trigger: 'blur' }
                    ],
                    // password: [
                    //     { required: true, message: '密码必须填写', trigger: 'blur' },
                    //     { pattern:/^[a-zA-Z0-9_-]{8,16}$/,message: '密码填写不正确', trigger: 'blur' },
                    // ],
                    // password_comfirm:[
                    //     { required: true, message: '请再次输入密码', trigger: 'blur' },
                    //     { validator:(rule,val,callback,source,options) => {
                    //             // 用户名正则，8到16位（字母，数字，下划线，减号）
                    //             var reg = /^[a-zA-Z0-9_-]{8,16}$/;
                    //             if (!reg.test(val)){
                    //                 return callback(new Error('密码必须是8到16位（字母，数字，下划线，减号）任意组合'));
                    //             }else if (val !== this.formData.password){
                    //                 return callback(new Error('两次输入密码不一致'));
                    //             }
                    //         }, trigger: 'blur'
                    //     }
                    // ],
                    name: [
                        { required: true, message: '名称必须填写', trigger: 'blur' }
                    ],
                    position: [
                        { required: true, message: '职位必须填写', trigger: 'blur' }
                    ],
                    group_id: [
                        { type:'number', required: true, message: '角色必须选择', trigger: 'blur' }
                    ],
                },
            }
        },
        methods:{
            // 显示隐藏模态表单
            showDrawer () {
                this.$emit('showTableEditEvent');
            },
            // 修改后提交
            handleSubmit () {
                this.$nextTick(() => {
                    this.$refs.form.validate(valid => {
                        if (valid){
                            this.loading = true;
                            edit(this.formData,this.formData.id).then(res =>{
                                if (res.data.code == 1){
                                    this.$Message.success(res.data.msg);
                                    this.showDrawer();
                                }else {
                                    this.$Message.error(res.data.msg);
                                }
                                this.loading = false;
                            })
                        }
                    });
                })
            },
            // 接收修改时传递的函数
            currentEditData (data) {
                this.$nextTick(() => {
                    this.formData = data;
                });
            },
        }
    }
</script>
<style lang="css" scoped>
.footer{
    width: 100%;
    margin-left: 10%;
    text-align: center;
}
</style>
