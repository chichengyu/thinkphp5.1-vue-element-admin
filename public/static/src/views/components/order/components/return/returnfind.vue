<template>
    <div class="add" style="padding-right: 120px;">
        <Form ref="form" :model="formData" :rules="ruleCustom" :label-width="120">
            <Row :gutter="32">
                <Col>
                    <FormItem label="退款原因" prop="reason" label-position="top">
                        <Input type="textarea" v-model="formData.reason" :rows="5" :autosize="{minRows: 5,maxRows: 5}" placeholder="请输入备注" />
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
                    reason:'',
                },
                ruleCustom:{
                    reason: [
                        { required: true, message: '退款原因必须填写', trigger: 'blur' },
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
                                    this.$emit('update:showReturnModal',false);
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
