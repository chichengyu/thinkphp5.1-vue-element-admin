<template>
    <div class="edit" style="padding-right: 60px">
        <Form ref="form" :model="formData" :rules="ruleCustom" :label-width="110">
            <Row :gutter="32">
                <Col>
                    <FormItem label="用户组名称" prop="title" label-position="top">
                        <Input v-model="formData.title" placeholder="请输入用户组名称" />
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="状态" prop="status" label-position="top">
                        <i-Switch size="large" :value="formData.status == 1" @on-change="changeStatus">
                            <span slot="open">正常</span>
                            <span slot="close">禁用</span>
                        </i-Switch>
                    </FormItem>
                </Col>
            </Row>
        </Form>
        <div class="footer">
<!--            <Button style="margin-right: 8px" @click="showDrawer">返回</Button>-->
            <Button type="primary" :loading="loading" @click="handleSubmit">提交</Button>
        </div>
    </div>
</template>
<script>
    import { edit } from '@/api/admin/groups.js'
    export default {
        props:['show'],
        data () {
            return {
                loading: false,
                formData: {
                    title:'',
                    status:'',
                },
                ruleCustom: {
                    title: [
                        { required: true, message: '名称必须填写', trigger: 'blur' }
                    ]
                }
            }
        },
        computed:{
            activeStatus(){
                return this.formData.status == 1;
            }
        },
        methods:{
            // 显示隐藏模态表单
            showDrawer () {
                this.$emit('showTableEditEvent');
            },
            // 修改后提交
            handleSubmit () {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        this.loading = true;
                        setTimeout(() => {
                            edit(this.formData,this.formData.id).then(res =>{
                                if (res.data.code == 1){
                                    this.$Message.success('修改成功');
                                    this.showDrawer();
                                }else {
                                    this.$Message.error(res.data.msg);
                                }
                                this.loading = false;
                            })
                        })
                    }
                })
            },
            // 接收修改时传递的函数
            currentEditData (data) {
                this.formData = data
            },
            changeStatus(status){
                if (status){
                    this.formData.status = 1;
                } else {
                    this.formData.status = 0;
                }
            }
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
