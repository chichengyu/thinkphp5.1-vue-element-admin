<template>
    <div class="add" style="padding-right: 120px;">
        <Form ref="form" :model="formData" :rules="ruleCustom" :label-width="120">
            <Row :gutter="32">
                <Col>
                    <FormItem label="收货人姓名" prop="takeover" label-position="top">
                        <Input v-model="formData.takeover" placeholder="请输入收货人姓名" />
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="收货人手机" prop="takeover_tel" label-position="top">
                        <Input v-model="formData.takeover_tel" placeholder="请输入收货人手机" />
                    </FormItem>
                </Col>
            </Row>
        </Form>
        <div class="footer">
            <Button type="primary" :loading="loading" @click="handleSubmit">提交</Button>
        </div>
    </div>
</template>
<script>
    import { edit } from '@/api/admin/owner.js'
    export default {
        props:{
            currentData:{
                type:Object,
                required: true
            }
        },
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
                    takeover:'',
                    takeover_tel:'',
                },
                ruleCustom:{
                    takeover: [
                        { required: true, message: '收货人必须填写', trigger: 'blur' },
                    ],
                    takeover_tel: [
                        { type:'number', required: true, message: '收货人电话必须填写', trigger: 'blur' },
                    ],
                    takeover_tel: [
                        { required: true, message: '收货人手机号码必须填写', trigger: 'blur' },
                        { pattern:/^1(3|4|5|7|8|9)[0-9]\d{8}$/, message: '手机号码必须填写真实有效号码', trigger: 'blur' }
                    ],
                },
                cares:null
            }
        },
        methods:{
            // 显示隐藏模态表单
            // showDrawer () {
            //     this.$emit('showTableAddEvent');
            // },
            // 修改后提交
            handleSubmit () {
                this.$nextTick(() => {
                    this.$refs.form.validate(valid => {
                        if (valid && this.currentData.id){
                            this.loading = true;
                            edit(this.currentData.id,this.formData).then(res => {
                                if (res.data.code == 1){
                                    this.$Message.success(res.data.msg);
                                    this.$refs.form.resetFields();
                                    // this.showDrawer();
                                    this.$emit('update:showTakeoverModal',false);
                                }else {
                                    this.$Message.error(res.data.msg);
                                }
                                this.loading = false;
                            })
                        }
                    });
                })
            }
        }
    }
</script>
<style lang="less" scoped>
.footer{
    width: 100%;
    margin-left: 10%;
    text-align: center;
}
</style>
