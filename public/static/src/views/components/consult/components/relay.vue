<template>
    <div class="update">
        <Form ref="form" :model="formData" :rules="ruleCustom" :label-width="120">
            <Row :gutter="32">
                <Col>
                    <FormItem label="选择员工" prop="personnel_id" label-position="top">
                        <Select v-model.number="formData.personnel_id" style="width: 80%;">
                            <Option v-if="personnel" v-for="item in personnel" :value="item.id" :key="item.id">{{ item.name }}</Option>
                        </Select>
                    </FormItem>
                </Col>
            </Row>
        </Form>
        <div align="center" slot="footer">
            <Button @click.stop="showDrawer" size="default">返回</Button>
            <Button @click.stop="handleSubmit" type="primary" size="default">确定</Button>
        </div>
    </div>
</template>
<script>
    import { relay } from '@/api/admin/consult.js'

    export default {
        props:['show','personnel'],
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
                    personnel_id:null,
                },
                ruleCustom:{
                    personnel_id:[
                        { type:'number',required: true, message: '必须选择员工', trigger: 'blur' }
                    ]
                },
            }
        },
        methods:{
            // 显示隐藏模态表单
            showDrawer () {
                this.$emit('showTableRelayEvent');
            },
            // 修改后提交
            handleSubmit () {
                this.loading = true;
                this.$refs.form.validate(valid => {
                    if (valid){
                        relay({personnelId:this.formData.personnel_id},this.formData.id).then(res =>{
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
            },
            // 接收修改时传递的函数
            currentRelayData (data) {
                this.formData = data;
            },
        }
    }
</script>
<style>
    .demo-drawer-footer{
        width: 100%;
        position: absolute;
        bottom: 0;
        left: 0;
        border-top: 1px solid #e8e8e8;
        padding: 10px 16px;
        text-align: right;
        background: #fff;
    }
</style>

