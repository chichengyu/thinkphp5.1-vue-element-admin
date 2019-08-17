<template>
    <div>
        <Drawer
            title="查看车辆"
            v-model="show"
            width="45%"
            :mask-closable="false"
            :styles="styles"
        >
            <div slot='close'>
                <Icon type="ios-close" @click.stop='showDrawer'/>
            </div>
            <Form ref="form" :model="formData" :rules="ruleCustom" :label-width="110">
                <Row :gutter="32">
                    <Col>
                        <FormItem label="汽车名称" prop="name" label-position="top">
                            <Input v-model="formData.name" placeholder="请输入汽车名称" />
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="logo" prop="thumbnail" label-position="top">
                            <preview-image style="vertical-align: top" v-if="formData.thumbnail" :imageUrl="formData.thumbnail"></preview-image>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="汽车品牌" prop="brand_id" label-position="top">
                            <Select v-model="formData.brand_id">
                                <Option v-if="selectData.car_brands" v-for="item in selectData.car_brands" :value="item.id" :key="item.id">{{ item.name }}</Option>
                            </Select>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="汽车系列" prop="series" label-position="top">
                            <Input v-model="formData.series" placeholder="请输入汽车系列" />
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="款式年份" style="display: inline-block" prop="years" label-position="top">
                            <Input v-model="formData.years" placeholder="请输入生产年份"/>
<!--                            <DatePicker type="year" v-model="formData.years" @on-change="changeyear" placeholder="请选择生产年份" style="width: 200px"></DatePicker>-->
                        </FormItem>
                        <FormItem label="月份" style="display: inline-block" prop="month" label-position="top">
                            <Input v-model="formData.month" placeholder="请输入生产月份"/>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="车型" prop="cartype_id" label-position="top">
                            <Select v-model="formData.cartype_id">
                                <Option v-if="selectData.car_type" v-for="item in selectData.car_type" :value="item.id" :key="item.id">{{ item.name }}</Option>
                            </Select>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="类别" prop="classify" label-position="top">
                            <Select v-model="formData.classify">
                                <Option v-if="cartType" v-for="item in cartType" :value="item.value" :key="item.value">{{ item.name }}</Option>
                            </Select>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="变速箱" prop="bsx" label-position="top">
                            <RadioGroup v-model="formData.bsx">
                                <Radio :label="1">手动档</Radio>
                                <Radio :label="2">自动档</Radio>
                            </RadioGroup>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="汽车排量" prop="displacement" label-position="top">
                            <Input v-model="formData.displacement" placeholder="请输入汽车排量" />
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="发动机" prop="engine" label-position="top">
                            <Input v-model="formData.engine" placeholder="请输入发动机" />
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="国别" prop="country_id" label-position="top">
                            <Select v-model="formData.country_id">
                                <Option v-if="selectData.car_country" v-for="item in selectData.car_country" :value="item.id" :key="item.id">{{ item.name }}</Option>
                            </Select>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="车辆配置" prop="level" label-position="top">
                            <Input v-model="formData.level" placeholder="请输入车辆级别" />
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="车身结构" prop="carstyle" label-position="top">
                            <Input v-model="formData.carstyle" placeholder="请输入车身形式" />
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="燃油形式" prop="fuel" label-position="top">
                            <Select v-model="formData.fuel">
                                <Option v-if="fueles" v-for="item in fueles" :value="item.value" :key="item.value">{{ item.name }}</Option>
                            </Select>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="进气方式" prop="air" label-position="top">
                            <RadioGroup v-model="formData.air">
                                <Radio :label="1">自然吸气</Radio>
                                <Radio :label="2">涡轮增压</Radio>
                            </RadioGroup>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="指导价" prop="price_zdj" label-position="top">
                            <InputNumber v-model.number="formData.price_zdj" :max="100000000" :min="0" placeholder="请输入指导价" />（万元）
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="特价" prop="price_ptj" label-position="top">
                            <InputNumber v-model.number="formData.price_ptj" :max="100000000" :min="0" placeholder="请输入平台价" />（万元）
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="门店价" prop="price_mdj" label-position="top">
                            <InputNumber v-model.number="formData.price_mdj" :max="100000000" :min="0" placeholder="请输入门店价" />（万元）
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="经销商价" prop="price_jxs" label-position="top">
                            <InputNumber v-model.number="formData.price_jxs" :max="100000000" :min="0" placeholder="请输入经销商价" />（万元）
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="备注" prop="remarks" label-position="top">
                            <Input v-model="formData.remarks" placeholder="请输入备注" />
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="上传图片" prop="imagesId" label-position="top">
                            <div>
                                <div style="display: inline-block;margin-right: 10px;vertical-align: middle">外观</div>
                                <preview-image style="vertical-align: top" v-if="formData.imagesId.facade" v-for="(item,index) in formData.imagesId.facade" :key="index" :imageUrl="item"></preview-image>
                            </div>
                            <div>
                                <div style="display: inline-block;margin-right: 10px;vertical-align: middle">中控</div>
                                <preview-image style="vertical-align: top" v-if="formData.imagesId.center" v-for="(item,index) in formData.imagesId.center" :key="index" :imageUrl="item"></preview-image>
                            </div>
                            <div>
                                <div style="display: inline-block;margin-right: 10px;vertical-align: middle">座椅</div>
                                <preview-image style="vertical-align: top" v-if="formData.imagesId.seat" v-for="(item,index) in formData.imagesId.seat" :key="index" :imageUrl="item"></preview-image>
                            </div>
                            <div>
                                <div style="display: inline-block;margin-right: 10px;vertical-align: middle">细节</div>
                                <preview-image style="vertical-align: top" v-if="formData.imagesId.detail" v-for="(item,index) in formData.imagesId.detail" :key="index" :imageUrl="item"></preview-image>
                            </div>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="状态" prop="status" label-position="top">
                            <RadioGroup v-model="formData.status">
                                <Radio :label="1">待上架</Radio>
                                <Radio :label="2">上架</Radio>
                                <Radio :label="3">下架</Radio>
                            </RadioGroup>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="上架时间" prop="stime" label-position="top">
                            <DatePicker v-model="formData.stime" type="datetime" placeholder="请选择上架时间" style="width: 200px"></DatePicker>
                        </FormItem>
                    </Col>
                </Row>
                <Row :gutter="32">
                    <Col>
                        <FormItem label="车辆图文详情" prop="lightspot" label-position="top">
                            <wang-editor :isdisable="false" :value="formData.lightspot"></wang-editor>
                        </FormItem>
                    </Col>
                </Row>
            </Form>
            <div class="demo-drawer-footer">
                <Button style="margin-right: 8px" @click="showDrawer">返回</Button>
            </div>
        </Drawer>
    </div>
</template>
<script>
    import previewImage from '@/components/admin/preview/previewImage.vue'
    import wangEditor from '@/components/admin/wangEditor/wangEditor.vue'

    export default {
        props:['show','selectData'],
        data () {
            return {
                loading: false,
                styles: {
                    height: 'calc(100% - 55px)',
                    overflow: 'auto',
                    paddingBottom: '53px',
                    position: 'static'
                },
                cartType:[
                    {name:'商品车',value:1},
                    {name:'运损车',value:2},
                    {name:'换代新车',value:3},
                    {name:'库存车',value:4},
                    {name:'采集车',value:5},
                    {name:'活动车',value:6},
                ],
                fueles:[
                    {name:'汽油',value:1},
                    {name:'柴油',value:2},
                    {name:'油电混动',value:3},
                    {name:'纯电动',value:4},
                    {name:'插电式混合动力',value:5},
                ],
                formData: {
                    name:'',
                    brand_id:'',
                    years:'',
                    month:'',
                    cartype_id: '',
                    classify:'',
                    bsx: 1,
                    displacement: '',
                    engine: '',
                    country_id: '',
                    level: '',
                    carstyle: '',
                    fuel: '',
                    air:1,
                    series: '',
                    price_zdj: 0,
                    price_ptj: 0,
                    price_mdj: 0,
                    price_jxs: 0,
                    remarks: '',
                    thumbnail:'',
                    imagesId: {
                        facade:[],
                        center:[],
                        seat:[],
                        detail:[]
                    },
                    status: 1,
                    stime: '',
                    lightspot:''
                },
                ruleCustom: {
                    name: [
                        { required: true, message: '品牌名称必须填写', trigger: 'blur' }
                    ],
                    thumbnail: [
                        { required: true, message: '必须上传logo'}
                    ],
                    brand_id: [
                        { type:'number',required: true, message: '必须选择品牌',trigger: 'blur'}
                    ],
                    years: [
                        { required: true, message: '必须选择生产年份',trigger: 'blur'}
                    ],
                    cartype_id: [
                        { type:'number',required: true, message: '必须选择车型', trigger: 'blur' }
                    ],
                    classify: [
                        { type:'number',required: true, message: '必须选择类别', trigger: 'blur' }
                    ],
                    bsx: [
                        { type:'number',required: true, message: '必须填写变速箱', trigger: 'blur' }
                    ],
                    displacement: [
                        { required: true, message: '必须汽车排量', trigger: 'blur' }
                    ],
                    // engine: [
                    //     { required: true, message: '必须填写发动机', trigger: 'blur' }
                    // ],
                    country_id: [
                        { type:'number',required: true, message: '必须选择国别', trigger: 'blur' }
                    ],
                    level: [
                        { required: true, message: '必须填寫车辆级别', trigger: 'blur' }
                    ],
                    carstyle: [
                        { required: true, message: '必须填写车身形式', trigger: 'blur' }
                    ],
                    fuel: [
                        { type:'number',required: true, message: '必须选择燃油形式', trigger: 'blur' }
                    ],
                    air: [
                        { type:'number',required: true, message: '必须选择进气方式', trigger: 'blur' }
                    ],
                    series: [
                        { required: true, message: '必须填写汽车系列', trigger: 'blur' }
                    ],
                    price_zdj: [
                        { type:'number',required: true, message: '必须填写指导价', trigger: 'blur' },
                    ],
                    // price_ptj: [
                    //     { required: true, message: '必须填写特价', trigger: 'blur' }
                    // ],
                    price_mdj: [
                        { type:'number',required: true,max:100000000, message: '必须填写门店价', trigger: 'blur' },
                    ],
                    price_jxs: [
                        { type:'number',required: true,max:100000000, message: '必须填写经销商价', trigger: 'blur' },
                    ],
                    imagesId: [
                        { required: true, message: '必须上传车辆图片'}
                    ],
                    lightspot: [
                        { required: true, message: '必须填写车辆图文详情'}
                    ]
                },
            }
        },
        computed:{
            activeBrand(){
                return this.formData.status == 1;
            }
        },
        components:{
            previewImage,
            wangEditor,
        },
        methods:{
            // 显示隐藏模态表单
            showDrawer () {
                this.$emit('showTableDetailsEvent');
            },
            // 接收修改时传递的函数
            currentDetailsData (data) {
                this.$nextTick(() => {
                    data.price_jxs = parseFloat(data.price_jxs);
                    data.price_mdj = parseFloat(data.price_mdj);
                    data.price_ptj = parseFloat(data.price_ptj);
                    data.price_zdj = parseFloat(data.price_zdj);
                    var imagesId = {
                        facade:[],
                        center:[],
                        seat:[],
                        detail:[],
                    };
                    var images = data.car_images;
                    for (let key in images){
                        let item = images[key];
                        switch (item.type) {
                            case 1:
                                imagesId.facade.push(item.image);
                                break;
                            case 2:
                                imagesId.center.push(item.image);
                                break;
                            case 3:
                                imagesId.seat.push(item.image);
                                break;
                            case 4:
                                imagesId.detail.push(item.image);
                                break;
                        }
                    }
                    data.imagesId = imagesId;
                    this.formData = data;
                });
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
