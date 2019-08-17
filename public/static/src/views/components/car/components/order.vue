<template>
    <div class="order" v-if="currentData">
        <h2 style="margin: 0 0 10px 20px;font-weight: 900;font-size: 17px">下单</h2>
        <div class="content">
            <div class="basc">
                <div class="left">
                    <div class="f-t">
                        <img v-if="thumbnailImage" :src="thumbnailImage" width="100%" height="100%">
                    </div>
                    <div class="f-b">
                        <swiper v-if="currentData.car_images" :banner="currentData.car_images" :thumbnailImage.sync="thumbnailImage"></swiper>
<!--                        <div class="img-item"-->
<!--                             v-if="currentData.car_images.length"-->
<!--                             v-for="(item,key) in currentData.car_images"-->
<!--                             :key="key">-->
<!--                            <img :src="item.image" width="100%" height="100%">-->
<!--                        </div>-->
                    </div>
                </div>
                <div class="right" v-if="currentData.car_attr">
                    <h2>{{ currentData.name }} <Tag color="success">{{carType}}</Tag></h2>
                    <div class="content">
                        <div class="title">车辆基本信息</div>
                        <div class="c-c">
                            <div class="car-basc">
                                <p>车辆品牌</p>
                                <p>车辆颜色</p>
                                <p>发动机</p>
                                <p>车身形式</p>
                                <p>排量</p>
                                <p>生产时间</p>
                            </div>
                            <div class="car-basc">
                                <p>{{currentData.brand_name}}</p>
                                <p>{{currentData.car_attr.basic.waicolor}}</p>
                                <p>{{currentData.car_attr.basic.fadongji}}</p>
                                <p>{{currentData.car_attr.basic.cheshen}}</p>
                                <p>{{currentData.displacement}}L</p>
                                <p>{{currentData.years}}款</p>
                            </div>
                            <div class="car-basc">
                                <p>车型</p>
                                <p>库存</p>
                                <p>变速箱</p>
                                <p>长*宽*高</p>
                                <p>燃油形式</p>
                                <p>驱动方式</p>
                            </div>
                            <div class="car-basc">
                                <p>{{currentData.car_attr.basic.jibie}}</p>
                                <p>{{stock}}</p>
                                <p>{{currentData.car_attr.basic.biansu}}</p>
                                <p>{{currentData.car_attr.cheshen.changkuangao}}</p>
                                <p>{{currentData.car_attr.basic.ranliao}}</p>
                                <p></p>
                            </div>
                        </div>
                        <div class="c-b">
                            <p style="display: inline-block;margin-top:12px;padding-left: 12px">
                                <span>指导价：<b>{{currentData.price_zdj}}</b>万</span>
                                <span>拿车价：<b class="color-red">{{currentData.price_jxs}}</b>万</span>
                                <span>门店价：<b class="color-red">{{currentData.price_mdj}}</b>万</span>
                            </p>
                            <div class="c" >
                                <div class="c-colors">
                                    <p style="font-size: 16px;display: inline-block;">颜色：</p>
                                    <RadioGroup v-model="formData.car_attr_id" type="button" @on-change="changeColor(formData.car_attr_id)">
                                        <Radio v-for="(item,key) in currentData.car_color" :key="key" :label="item.id">{{item.name}}</Radio>
                                    </RadioGroup>
                                </div>
                                <div style="display: flex;margin-left: 50px;">
                                    <p class="c-p" style="float: left;align-self: center;">
                                        <span style="margin-right: 0;cursor: pointer;user-select: none" @click="countNum(false)">-</span>
                                        <input type="text" style="width: 50px;" v-model="formData.car_number">
                                        <span style="cursor: pointer;user-select: none" @click="countNum(true)">+</span>
                                    </p>
                                    <div style="float: left;align-self: center;margin-left: 50px;">
                                        <Button type="error" @click="handleSubmit">加入购物车</Button>
                                        <Button type="warning">联系总部</Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="car-remark">
                <h2>备注说明</h2>
                <p>大师的撒大师大师大师的撒</p>
            </div>
            <div v-if="currentData.car_attr" class="car-attr">
                <div class="car-a">
                    <h2>详情配置</h2>
<!--                    <div @click="showAttr">展开 <i></i></div>-->
                </div>
                <div class="car-b">
                    <div class="car-b-a">
                        <h3>基本参数</h3>
                        <div class="">
                            <ul>
                                <li>厂商</li>
                                <li>{{currentData.car_attr.basic.changshang}}</li>
                                <li>级别</li>
                                <li>{{currentData.car_attr.basic.jibie}}</li>
                                <li>发动机</li>
                                <li>{{currentData.car_attr.basic.fadongji}}</li>
                                <li>变速箱</li>
                                <li>{{currentData.car_attr.basic.biansu}}</li>
                                <li>长*宽*高(mm)</li>
                                <li>{{currentData.car_attr.basic.changkuangao}}</li>
                                <li>车身型式</li>
                                <li>{{currentData.car_attr.basic.cheshen}}</li>
                                <li>能源类型</li>
                                <li>{{currentData.car_attr.basic.ranliao}}</li>
                                <li>工信部综合油耗(L/100km)</li>
                                <li>{{currentData.car_attr.basic.youhao}}</li>
                                <li>外部颜色</li>
                                <li>{{currentData.car_attr.basic.waicolor}}</li>
                                <li>内饰颜色</li>
                                <li>{{currentData.car_attr.basic.neicolor}}</li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>车身配置</h3>
                        <div>
                            <ul>
                                <li>长*宽*高(mm)</li>
                                <li>{{currentData.car_attr.cheshen.changkuangao}}</li>
                                <li>轴距(mm)</li>
                                <li>{{currentData.car_attr.cheshen.zhouju}}</li>
                                <li>整车质量(kg)</li>
                                <li>{{currentData.car_attr.cheshen.zhengche}}</li>
                                <li>车身结构</li>
                                <li>{{currentData.car_attr.cheshen.cheshen}}</li>
                                <li>油箱容积(L)</li>
                                <li>{{currentData.car_attr.cheshen.youxiang}}</li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>发动机配置</h3>
                        <div>
                            <ul>
                                <li>发动机型号</li>
                                <li>{{currentData.car_attr.fadongji.fdjxh}}</li>
                                <li>排量(ml)</li>
                                <li>{{currentData.car_attr.fadongji.pailiang}}</li>
                                <li>进气形式</li>
                                <li>{{currentData.car_attr.fadongji.jinqixingshi}}</li>
                                <li>气缸排列形式</li>
                                <li>{{currentData.car_attr.fadongji.qigangpailie}}</li>
                                <li>气缸数(个)</li>
                                <li>{{currentData.car_attr.fadongji.qigangshu}}</li>
                                <li>每缸气门数(个)</li>
                                <li>{{currentData.car_attr.fadongji.meigangqimenshu}}</li>
                                <li>配气机构</li>
                                <li>{{currentData.car_attr.fadongji.peiqijigou}}</li>
                                <li>最大马力(Ps)</li>
                                <li>{{currentData.car_attr.fadongji.zuidamali}}</li>
                                <li>最大功率(kW)</li>
                                <li>{{currentData.car_attr.fadongji.zuidagonglv}}</li>
                                <li>最大扭矩(N·m)</li>
                                <li>{{currentData.car_attr.fadongji.zuidaniuzhuan}}</li>
                                <li>燃料形式</li>
                                <li>{{currentData.car_attr.fadongji.ranliaoxingshi}}</li>
                                <li>燃料标号</li>
                                <li>{{currentData.car_attr.fadongji.ranyoubiaohao}}</li>
                                <li>供油方式</li>
                                <li>{{currentData.car_attr.fadongji.gongyoufangshi}}</li>
                                <li>缸盖材料</li>
                                <li>{{currentData.car_attr.fadongji.ganggaicailiao}}</li>
                                <li>缸体材料</li>
                                <li>{{currentData.car_attr.fadongji.gangticailiao}}</li>
                                <li>环保标准</li>
                                <li>{{currentData.car_attr.fadongji.huanbaobiaozhun}}</li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>变速箱配置</h3>
                        <div>
                            <ul>
                                <li>档位个数</li>
                                <li>{{currentData.car_attr.biansuxiang.dangwei}}</li>
                                <li>变速箱类型</li>
                                <li>{{currentData.car_attr.biansuxiang.leixing}}</li>
                                <li>变速箱描述</li>
                                <li>{{currentData.car_attr.biansuxiang.miaoshu}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>底盘转向</h3>
                        <div>
                            <ul>
                                <li>前悬架类型</li>
                                <li>{{currentData.car_attr.dipan.qianxuanjia}}</li>
                                <li>后悬架类型</li>
                                <li>{{currentData.car_attr.dipan.houxuanjia}}</li>
                                <li>助力类型</li>
                                <li>{{currentData.car_attr.dipan.zhuli}}</li>
                                <li>车体结构</li>
                                <li>{{currentData.car_attr.dipan.cheti}}</li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>车轮制动</h3>
                        <div>
                            <ul>
                                <li>前制动器类型</li>
                                <li>{{currentData.car_attr.chelun.qianzhidong}}</li>
                                <li>后制动器类型</li>
                                <li>{{currentData.car_attr.chelun.houzhidong}}</li>
                                <li>驻车制动类型</li>
                                <li>{{currentData.car_attr.chelun.zhuchezhidong}}</li>
                                <li>前轮胎规格</li>
                                <li>{{currentData.car_attr.chelun.qianluntai}}</li>
                                <li>后轮胎规格</li>
                                <li>{{currentData.car_attr.chelun.houluntai}}</li>
                                <li>备胎规格</li>
                                <li>{{currentData.car_attr.chelun.beitai}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>安全装备</h3>
                        <div>
                            <ul>
                                <li>主/副驾驶座安全气囊</li>
                                <li>{{currentData.car_attr.anquan.jiashizuo}}</li>
                                <li>前/后排侧气囊</li>
                                <li>{{currentData.car_attr.anquan.ceqinang}}</li>
                                <li>胎压监测装置</li>
                                <li>{{currentData.car_attr.anquan.taiyajianche}}</li>
                                <li>安全带未系提示</li>
                                <li>{{currentData.car_attr.anquan.anquandai}}</li>
                                <li>ISOFIX儿童座椅接口</li>
                                <li>{{currentData.car_attr.anquan.ISOFIXzuoyi}}</li>
                                <li>ABS防抱死</li>
                                <li>{{currentData.car_attr.anquan.ABS}}</li>
                                <li>制动力分配(EBD/CBC等)</li>
                                <li>{{currentData.car_attr.anquan.zhidongli}}</li>
                                <li>刹车辅助(EBA/BAS/BA等)</li>
                                <li>{{currentData.car_attr.anquan.shache}}</li>
                                <li>牵引力控制(ASR/TCS/TRC等)</li>
                                <li>{{currentData.car_attr.anquan.qianyingli}}</li>
                                <li>车身稳定控制(ESC/ESP/DSC等)</li>
                                <li>{{currentData.car_attr.anquan.cheshen}}</li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>操作配置</h3>
                        <div>
                            <ul>
                                <li>前/后驻车雷达</li>
                                <li>{{currentData.car_attr.caozuo.zhuche}}</li>
                                <li>倒车视频影像</li>
                                <li>{{currentData.car_attr.caozuo.daoche}}</li>
                                <li>定速巡航</li>
                                <li>{{currentData.car_attr.caozuo.dingsu}}</li>
                                <li>上坡辅助</li>
                                <li>{{currentData.car_attr.caozuo.shangpo}}</li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>外部配置</h3>
                        <div>
                            <ul>
                                <li>电动天窗</li>
                                <li>{{currentData.car_attr.waibu.diandong}}</li>
                                <li>全景天窗</li>
                                <li>{{currentData.car_attr.waibu.quanjing}}</li>
                                <li>铝合金轮圈</li>
                                <li>{{currentData.car_attr.waibu.lvhejinlun}}</li>
                                <li>车顶行李架</li>
                                <li>{{currentData.car_attr.waibu.cheding}}</li>
                                <li>发动机电子防盗</li>
                                <li>{{currentData.car_attr.waibu.fadongji}}</li>
                                <li>车内中控锁</li>
                                <li>{{currentData.car_attr.waibu.chenei}}</li>
                                <li>遥控钥匙</li>
                                <li>{{currentData.car_attr.waibu.yaokong}}</li>
                                <li>无钥匙启动系统</li>
                                <li>{{currentData.car_attr.waibu.qidong}}</li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>内部配置</h3>
                        <div>
                            <ul>
                                <li>真皮方向盘</li>
                                <li>{{currentData.car_attr.neibu.zhenpi}}</li>
                                <li>方向盘调节</li>
                                <li>{{currentData.car_attr.neibu.fangxiangpan}}</li>
                                <li>多功能方向盘</li>
                                <li>{{currentData.car_attr.neibu.duogongneng}}</li>
                                <li>行车电脑显示屏</li>
                                <li>{{currentData.car_attr.neibu.xingche}}</li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>座椅配置</h3>
                        <div>
                            <ul>
                                <li>座椅材质</li>
                                <li>{{currentData.car_attr.zuoyi.zuoyi}}</li>
                                <li>座椅高低调节</li>
                                <li>{{currentData.car_attr.zuoyi.zuoyigaodi}}</li>
                                <li>主驾驶座椅电动调节</li>
                                <li>{{currentData.car_attr.zuoyi.jiashizuo}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>多媒体配置</h3>
                        <div>
                            <ul>
                                <li>中控彩色大屏</li>
                                <li>{{currentData.car_attr.duomeiti.zhongkong}}</li>
                                <li>蓝牙/车载电话</li>
                                <li>{{currentData.car_attr.duomeiti.lanya}}</li>
                                <li>外接音源接口</li>
                                <li>{{currentData.car_attr.duomeiti.waijie}}</li>
                                <li>扬声器数量</li>
                                <li>{{currentData.car_attr.duomeiti.yangshenqi}}</li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>灯光配置</h3>
                        <div>
                            <ul>
                                <li>远光灯</li>
                                <li>{{currentData.car_attr.dengguang.yuanguangdeng}}</li>
                                <li>近光灯</li>
                                <li>{{currentData.car_attr.dengguang.jingguangdeng}}</li>
                                <li>LED日间行车灯</li>
                                <li>{{currentData.car_attr.dengguang.LED}}</li>
                                <li>前雾灯</li>
                                <li>{{currentData.car_attr.dengguang.qianwudeng}}</li>
                                <li>大灯高度可调</li>
                                <li>{{currentData.car_attr.dengguang.dadeng}}</li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>玻璃/后视镜</h3>
                        <div>
                            <ul>
                                <li>前/后电动车窗</li>
                                <li>{{currentData.car_attr.boli.chechuang}}</li>
                                <li>车窗防夹手功能</li>
                                <li>{{currentData.car_attr.boli.jiashou}}</li>
                                <li>后视镜电动调节</li>
                                <li>{{currentData.car_attr.boli.houshijing}}</li>
                                <li>后视镜加热</li>
                                <li>{{currentData.car_attr.boli.houshijingjiare}}</li>
                                <li>遮阳板化妆镜</li>
                                <li>{{currentData.car_attr.boli.zheyangban}}</li>
                                <li>后雨刷</li>
                                <li>{{currentData.car_attr.boli.houyushua}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="car-b-a">
                        <h3>空调/冰箱</h3>
                        <div>
                            <ul>
                                <li>空调控制方式</li>
                                <li>{{currentData.car_attr.kongtiao.kongtiao}}</li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { add } from '@/api/admin/car_shop.js'
    import swiper from './sweiper.vue'

    export default {
        props:['show','selectData'],
        components:{
            swiper
        },
        data () {
            return {
                styles: {
                    height: 'calc(100% - 55px)',
                    overflow: 'auto',
                    paddingBottom: '53px',
                    position: 'static'
                },
                formData: {
                    car_id:'',
                    car_attr_id:null,
                    car_number:1,
                },
                currentData:null,
                color:null,
                thumbnailImage:'',
                stock:0,// 库存
            }
        },
        mounted() {
            // this.$nextTick(() => {
                var obj = this.$route.params;
                if (obj.car_color){
                    let arres = [];
                    let color = obj.car_color.split(',');
                    for (let key in color){
                        var str = color[key];
                        if (str){
                            let arr = str.split('-');
                            arres.push({id:arr[1],name:arr[0],stock:arr[2]});
                        }
                    }
                    obj.car_color = arres;
                }
                this.currentData = obj;
                this.stock = this.currentData.car_number;
                this.thumbnailImage = this.currentData.thumbnail;
            // })
        },
        computed:{
            carType (){
                var name = ['商品车','运损车','换代新车','库存车','采集车','活动车'];
                return name[this.currentData.cartype_id-1];
            }
        },
        methods:{
            // 显示隐藏模态表单
            showDrawer () {
                // this.$emit('showTableDownOrderEvent');
                this.$router.back();
            },
            // 提交
            handleSubmit () {
                setTimeout(() => {
                    if (this.formData.car_id && this.formData.car_attr_id && this.formData.car_number){
                        add(this.formData).then(res => {
                            if (res.data.code == 1) {
                                this.$Message.success(res.data.msg);
                            }else {
                                this.$Message.error(res.data.msg);
                            }
                        })
                    }else {
                        this.$Message.error('请先选择车辆颜色！');
                    }
                })
            },
            countNum(flag){
                if (flag) {
                    this.formData.car_number++;
                }else {
                    if (this.formData.car_number > 0){
                        this.formData.car_number--;
                    }
                }
            },
            changeColor(colorID) {
                this.formData.car_id = this.currentData.id;
                this.formData.car_attr_id = colorID;
                this.currentData.car_color.find(item => {
                    if (item.id == colorID){
                        this.stock = item.stock;
                    }
                });
            }
        }
    }
</script>
<style lang="less" scoped>
.order{
    width: 100%;
    height: 100%;
    /*background:#fff;*/
}
.order .content{
    .basc{
        margin: 0 16px;
        height:360px;
        overflow: hidden;
        background:#f5f7f9;
        .left{
            position: relative;
            float: left;
            width: 25%;
            height: 100%;
            /*background: #f3f3f3;*/
            /*background: red;*/
            .f-t{
                width: 100%;;
                height: 250px;
                /*height: 50%;*/
                /*background:rgba(255,25,255,1);*/
            }
            .f-b{
                position: absolute;
                /*left:0;*/
                bottom: 0;
                width: 100%;
                height: 80px;
                margin-top: 20px;
                overflow: hidden;
            }
        }
        .right{
            position: relative;
            /*padding: 0 12px;*/
            float: right;
            width: calc(100% - 27%);
            /*height: 350px;*/
            /*background: #fff;*/
            h2{
                padding:12px 0 12px 12px ;
                /*padding-bottom: 12px;*/
                background: #fff;
            }
            .content{
                .title{
                    margin-left: -12px;
                    margin-right: -12px;
                    padding-top: 12px;
                    padding-bottom: 12px;
                    /*background:rgba(247,248,250,1);*/
                    text-indent: 20px;
                    /*font-weight:bold;*/
                }
                .c-c{
                    overflow: hidden;
                    background: #fff;
                    padding-bottom: 12px;
                    .car-basc{
                        float: left;
                        padding-top: 5px;
                        width: 23%;
                        text-align: center;
                        & > p{
                            height: 26px;
                            line-height: 26px;
                        }
                        & + .car-basc{
                            border-left: 1px solid #eee;
                        }
                    }
                }
                .c-b{
                    width: 100%;
                    /*position: absolute;*/
                    /*bottom: 0;*/
                    margin: 12px 0;
                    padding-bottom: 12px;
                    background: #fff;
                    overflow: hidden;
                    & > p{
                        span{
                            margin-right: 12px;
                            font-weight: 900;
                            font-size: 14px
                        }
                    }
                    .c{
                        display: flex;
                        margin-top: 12px;
                        padding-left: 12px;
                        & > p{
                            align-self: center;
                        }
                        .c-p{
                            display: flex;
                            align-self: center;
                            span{
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                height: 31px;
                                width: 30px;
                                border:1px solid #a9a9a9;
                            }
                            input{
                                height: 31px;
                                text-align: center;
                            }
                        }
                    }
                }
            }
        }
    }
    .car-remark{
        margin: 20px 16px 10px 16px;
        height: 100px;
        background: #f5f7f9;
        h2{
            margin: 20px 0 10px 0;
        }
        p{
            display: inline-block;
            padding: 15px 15px;
            width: 100%;
            background: #fff;
        }
    }
    .car-attr{
        margin: 0 16px 0 16px;
        .car-a{
            height: 40px;
            background: #fff;
            margin-bottom: 20px;
            & > h2{
                width: 100px;
                margin: 9px 0 0 10px ;
                float: left;
            }
            & > div{
                height: 40px;
                font-size: 14px;
                line-height:40px;
                color: #6D87FA;
                display: flex;
                align-items: center;
                margin-right: 20px;
                float: right;
                cursor: pointer;
                user-select: none;
                & > i{
                    display: inline-block;
                    width: 14px;
                    height: 14px;
                    background: red;
                }
            }
        }
        .car-b{
            background: #fff;
            width: 100%;
            padding: 10px 10px 20px 10px;
            /*float: left;*/
            .car-b-a{
                overflow: hidden;
                h3{
                    margin: 15px 0 5px 10px;
                }
                li{
                    width: 16.6%;
                    height: 40px;
                    float: left;
                    text-align:center;
                    line-height:40px;
                    list-style: none;
                    background: #f5f7f9;
                    border-right: 1px solid #eeeeee;
                    border-left: 1px solid #eeeeee;
                }
            }
        }
    }
}
.color-red{
    color: red;
}
</style>