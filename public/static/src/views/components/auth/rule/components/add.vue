<template>
    <div class="add" style="padding-right: 35px">
        <Form ref="form" :model="formData" :rules="ruleCustom" :label-width="80">
            <Row :gutter="32">
                <Col>
                    <FormItem label="规则名称" prop="title" label-position="top">
                        <Input v-model="formData.title" placeholder="请输入规则名称" />
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="规则" prop="name" label-position="top">
                        <Input v-model="formData.name" placeholder="请输入规则" />
                        <div style="color: red">规则为模块名称 + 控制器名称 + 方法名称，如：index / index / action</div>
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="上级" prop="status" label-position="top">
                        <Select v-model="formData.pid">
                            <Option :value="0">无</Option>
                            <Option v-if="parentRlue" v-for="item in parentRlue" :value="item.id" :key="item.id">{{ ruleTitle(item) }}</Option>
                        </Select>
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
    import { add } from '@/api/admin/rule.js'
    export default {
        props:['show','parentRlue'],
        data () {
            return {
                loading: false,
                formData: {
                    title:'',
                    name:'',
                    status:1,
                    pid:0
                },
                ruleCustom: {
                    title: [
                        { required: true, message: '名称必须填写', trigger: 'blur' }
                    ],
                    // name: [
                    //     { required: true, message: '规则必须填写', trigger: 'blur' }
                    // ],
                },
            }
        },
        methods:{
            ruleTitle (val){
                var txt = '└―';
                var title = val.title;
                var level = val.level;
                if (val.pid != 0){
                    txt = txt.repeat(level) + title;
                } else {
                    txt = title;
                }
                return txt;
            },
            // 显示隐藏模态表单
            showDrawer () {
                this.$emit('showTableAddEvent');
            },
            // 修改后提交
            handleSubmit () {
                this.$refs.form.validate(valid => {
                    if (valid) {
                        this.loading = true;
                        setTimeout(() => {
                            add(this.formData).then(res => {
                                if (res.data.code == 1){
                                    this.$Message.success('添加成功');
                                    this.showDrawer();
                                    this.formData.pid = 0;
                                    this.$refs.form.resetFields();
                                }else {
                                    this.$Message.error('添加失败：'+res.data.msg);
                                }
                                this.loading = false;
                            })
                        })
                    }
                });
            },
            changeStatus (status){
                if (status){
                    this.formData.status = 1;
                } else{
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