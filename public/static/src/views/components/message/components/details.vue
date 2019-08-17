<template>
    <div class="details" style="padding-left: 20px;padding-right: 35px;">
        <Form ref="form" :model="formData" :rules="ruleCustom" :label-width="80">
            <Row :gutter="32">
                <Col>
                    <FormItem label="车主" prop="owner" label-position="top">
                        <Input v-model="formData.owner" placeholder="请输入车主姓名" />
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="咨讯标题" prop="title" label-position="top">
                        <Input v-model="formData.title" placeholder="请输入咨讯标题" />
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="提车日期" prop="year" label-position="top">
                        <DatePicker type="date" v-model="formData.year" :options="options" format="yyyy-MM-dd" placeholder="请选择提车日期" style="width: 200px"></DatePicker>
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="提车门店" prop="store_id" label-position="top">
                        <Select v-model="formData.store_id" style="width:200px">
                            <Option v-for="(item,key) in stores" :value="item.id" :key="item.key">{{ item.name }}</Option>
                        </Select>
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="车辆ID" prop="car_id" label-position="top">
                        <InputNumber v-model="formData.car_id" :max="100000" :min="1"></InputNumber>
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="缩略图" prop="image" label-position="top">
                        <preview-image style="vertical-align: top;" v-if="formData.image" :imageUrl="formData.image"></preview-image>
                        <span style="margin-left: 5%;vertical-align: middle;color: red;"> * 上传的图片不得超过 2M</span>
                    </FormItem>
                </Col>
            </Row>
            <Row :gutter="32">
                <Col>
                    <FormItem label="内容正文" prop="main" label-position="top">
                        <wang-editor :isdisable="false" :value="formData.main"></wang-editor>
                    </FormItem>
                </Col>
            </Row>
        </Form>
<!--        <div class="demo-drawer-footer">-->
<!--            <Button style="margin-right: 8px" @click="showDrawer">返回</Button>-->
<!--        </div>-->
    </div>
</template>
<script>
    import previewImage from '@/components/admin/preview/previewImage.vue'
    import wangEditor from '@/components/admin/wangEditor/wangEditor.vue'

    export default {
        props:['show','stores'],
        data () {
            return {
                loading: false,
                options:{
                    disabledDate (date) {
                        return true;
                    }
                },
                formData: {
                    owner:'',
                    title:'',
                    year:null,
                    store_id: '',
                    car_id:0,
                    image: '',
                    main:'',
                },
                ruleCustom: {
                    owner: [
                        { required: true, message: '车主必须填写', trigger: 'blur' }
                    ],
                    title: [
                        { required: true, message: '标题必须填写', trigger: 'blur' }
                    ],
                    year: [
                        { type: 'date',required: true, message: '提车日期必须选择', trigger: 'blur' }
                    ],
                    store_id: [
                        { type:'number',required: true, message: '提车门店必须选择', trigger: 'blur' }
                    ],
                    car_id: [
                        { type:'number',required: true, message: '车辆ID必须填写', trigger: 'blur' }
                    ],
                    main: [
                        { required: true, message: '内容正文必须填写', trigger: 'blur' }
                    ],
                },
            }
        },
        computed:{
            activeStatus(){
                return this.formData.status == 1;
            }
        },
        components:{
            previewImage,
            wangEditor
        },
        methods:{
            // 显示隐藏模态表单
            showDrawer () {
                this.$emit('showTableDetailsEvent');
            },
            // 接收修改时传递的函数
            currentDetailsData (data) {
                data.status && delete data.status;
                data.year = new Date(data.year);
                this.formData = data;
            },
            changeStatus(status){
                if (status){
                    this.formData.status = 1;
                } else {
                    this.formData.status = 0;
                }
            },
        }
    }
</script>
<style lang="css" scoped>

</style>
