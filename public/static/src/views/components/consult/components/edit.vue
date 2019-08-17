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
                    <FormItem label="客户名称" prop="username" label-position="top">
                        <Input v-model="formData.username" placeholder="请输入客户名称 " />
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="选择品牌" prop="brand_id" label-position="top">
                        <Select v-if="carBrandsTypes.brands" :value="formData.brand_id" @on-change="changeBrand">
                            <Option v-for="item in carBrandsTypes.brands" :value="item.id" :key="item.id">{{ item.name }}</Option>
                        </Select>
                    </FormItem>
                    <FormItem label="选择类型" prop="type_id" label-position="top">
                        <Select v-if="carBrandsTypes.types" :value="formData.type_id" @on-change="changeType">
                            <Option v-for="item in carBrandsTypes.types" :value="item.id" :key="item.id">{{ item.name }}</Option>
                        </Select>
                    </FormItem>
                    <FormItem label="选择车辆" prop="carid" label-position="top">
                        <Select v-model="formData.carid">
                            <Option v-for="item in cares" :value="item.id" :key="item.id">{{ item.name }}</Option>
                        </Select>
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="状态" prop="status" label-position="top">
                        <RadioGroup v-model="formData.status">
                            <Radio :label="0">待沟通</Radio>
                            <Radio :label="1">已沟通</Radio>
                        </RadioGroup>
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="备注" prop="remark" label-position="top">
                        <Input v-model="formData.remark" placeholder="请输入备注" />
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
    import { getByBrandOrTypeCar,edit } from '@/api/admin/consult.js'

    export default {
        props:['carBrandsTypes'],
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
                    username:'',
                    carid:'',
                    tel: '',
                    brand_id:'',
                    type_id:'',
                    status:0,
                    remark:''
                },
                ruleCustom:{
                    username: [
                        { required: true, message: '手机号码必须填写', trigger: 'blur' },
                    ],
                    carid: [
                        { type:'number',required: true, message: '必须选择车辆', trigger: 'blur' },
                    ],
                    tel: [
                        { required: true, message: '手机号码必须填写', trigger: 'blur' },
                        { pattern:/^1(3|4|5|7|8|9)[0-9]\d{8}$/, message: '手机号码必须填写真实有效号码', trigger: 'blur' }
                    ],
                    dockertel:[
                        { pattern:/^1(3|4|5|7|8|9)[0-9]\d{8}$/, message: '手机号码必须填写真实有效号码', trigger: 'blur' }
                    ]
                },
                cares:null
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
                    })
                })
            },
            // 接收修改时传递的函数
            currentEditData (data) {
                this.$nextTick(() => {
                    this.formData = data;
                    this.getByBrandOrTypeCar();
                });
            },
            changeBrand(val){
                this.formData.brand_id = val;
                this.getByBrandOrTypeCar();
            },
            changeType(val){
                this.formData.type_id = val;
                this.getByBrandOrTypeCar();
            },
            getByBrandOrTypeCar () {
                let url = '?';
                if (this.formData.brand_id || this.formData.type_id) {
                    url += 'brand_id=' + this.formData.brand_id + '&type=' + this.formData.type_id;
                }
                getByBrandOrTypeCar(url).then(res => {
                    if (res.data.code == 1){
                        this.cares = res.data.data;
                    }
                });
            }
        }
    }
</script>
<style>
.footer{
    width: 100%;
    margin-left: 10%;
    text-align: center;
}
</style>

