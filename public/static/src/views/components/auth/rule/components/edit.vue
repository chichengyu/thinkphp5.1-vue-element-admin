<template>
    <div class="edit" style="padding-right: 35px">
        <Form ref="form" :model="formData" :rules="ruleCustom" :label-width="80">
            <Row :gutter="32">
                <Col>
                    <FormItem label="规则名称" prop="name" label-position="top">
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
                            <Option v-if="parentRlue && formData.id!=item.id" v-for="item in parentRlue" :value="item.id" :key="item.id">{{ ruleTitle(item) }}</Option>
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
    import upload from '@/components/admin/upload/upload.vue'
    import { edit } from '@/api/admin/rule.js'
    export default {
        props:['show','parentRlue'],
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
                    name:'',
                    title:'',
                    status:'',
                    pid:''
                },
                ruleCustom: {
                    title: [
                        { required: true, message: '规则必须填写', trigger: 'blur' }
                    ],
                    // name: [
                    //     { required: true, message: '规则名称必须填写', trigger: 'blur' }
                    // ],
                }
            }
        },
        computed:{
            activeStatus(){
                return this.formData.status == 1;
            },
        },
        components:{
            upload
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
                this.formData = data;
            },
            changeStatus (status){
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
