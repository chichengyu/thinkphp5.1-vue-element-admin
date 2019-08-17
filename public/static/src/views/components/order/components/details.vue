<template>
    <div class="order-details" v-if="order">
        <h3>订单详情</h3>
        <div class="process">
            <Steps :current="order.order_status">
                <Step title="未付款" content="这里是该步骤的描述信息"></Step>
                <Step title="已付款" content="这里是该步骤的描述信息"></Step>
                <Step title="已发货" content="这里是该步骤的描述信息"></Step>
                <Step title="已签收" content="这里是该步骤的描述信息"></Step>
            </Steps>
        </div>
        <div class="body" v-for="(item,key) in data" :key="key">
            <div class="details-item">
                <div class="item-car">
                    <div class="tupian"><img :src="item.car_details.thumbnail" width="100%" height="100%"></div>
                    <div class="message">
                        <div class="carName">
                            <h2>{{ item.car_details.name }}<Tag color="success">{{ item.car_details.classify|carType }}</Tag></h2>
                        </div>
                        <div class="price">
                            <span>指导价：<b>{{ price(item.car_details.price_zdj) }}万</b></span>
                            <span>拿车价：<b class="color-red">{{ price(item.car_details.price_jxs) }}万</b></span>
                            <span>门店价：<b class="color-red">{{ price(item.car_details.price_mdj) }}万</b></span>
                        </div>
                        <p class="car-color">{{ item.car_number.car_color }} X{{ item.count }}</p>
                        <div class="car-number">共计：<span style="color: red;">{{ price(item.car_details.price_jxs * item.count) }}万</span></div>
                    </div>
                </div>
                <div class="item-owner" v-for="(itemOwner,k) in item.owner">
                    <h3>用户{{k}}基本信息</h3>
                    <div class="owner-b">
                        <div>
                            <p>车主姓名：{{ itemOwner.owner_name }}</p>
                            <p>车架号：6541  3218</p>
                            <p>物流公司：车一百物流</p>
                        </div>
                        <div>
                            <p>车主联系方式：{{ itemOwner.owner_tel }}</p>
                            <p>物流联系人：小周 19925326131</p>
                        </div>
                        <div class="owner-c">
                            <span style="margin-right: 12px">车主身份证：  正面</span>
                            <preview-image :imageUrl="itemOwner.owner_identity_f"></preview-image>
                        </div>
                        <div class="owner-c">
                            <span style="margin-right: 12px">反面</span>
                            <preview-image :imageUrl="itemOwner.owner_identity_r"></preview-image>
                        </div>
                        <div class="owner-h">
<!--                            <p>订单状态：<span>已支付订金 {{ order.order_status }}</span></p>-->
                            <div>
<!--                                <Button v-if="itemOwner.order_status==-1" type="info">添加物流</Button>-->
                                <Button :disabled="itemOwner.reason?true:false" type="info" @click="handleEvent($event,itemOwner,false)">退款</Button>
                                <Button :disabled="itemOwner.order_status==2" type="warning" style="margin-right: 12px;" @click="handleEvent($event,itemOwner,true)">确认收车</Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 退款 -->
        <Modal v-model="showReturnModal" title="退款申请" :mask-closable="false" :footer-hide="true">
            <returnfind v-if="showReturnModal" :currentData="currentData" v-bind:showReturnModal.sync="showReturnModal"></returnfind>
        </Modal>

        <!-- 确认收货 -->
        <Modal v-model="showTakeoverModal" title="确认收货" :mask-closable="false" :footer-hide="true">
            <takeover v-if="showTakeoverModal" :currentData="currentData" v-bind:showTakeoverModal.sync="showTakeoverModal"></takeover>
        </Modal>
    </div>
</template>
<script>
import previewImage from '@/components/admin/preview/previewImage.vue'
import returnfind from './return/returnfind.vue'
import takeover from './takeover/takeover.vue'
import { edit } from '@/api/admin/owner.js'

function price(price) {
    return price / 1000000;
};
export default {
    props:['stores'],
    components:{
        previewImage,
        returnfind,
        takeover
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
            data:null,
            order:null,
            msg:'退款申请',
            showReturnModal:false,// 退款
            showTakeoverModal:false,// 确认收货
            currentData:null,
            formData:{
                owner:''
            },
            ruleCustom:{
                owner:[
                    {}
                ]
            }
        }
    },
    mounted(){
        var data = this.$route.query.data;
        var arr = [],
            carID = [],
            carColorID = [];

        if (data && data.store_owner){
            for (var key in data.store_owner){
                var carAttr = data.store_owner[key].car_number;
                if (!carID.includes(carAttr.car_id) || !carColorID.includes(carAttr.id)) {
                    var obj = {
                        car_id:carAttr.car_id,
                        car_color_id:carAttr.id,
                        car_details:data.store_owner[key].car_details,
                        car_number:data.store_owner[key].car_number,
                        count:0,
                        owner:[]
                    };
                    obj.count += data.store_owner[key].count;
                    obj.owner.push(data.store_owner[key]);
                    for (var k in data.store_owner) {
                        var owner = data.store_owner[k].car_number;
                        if ((carAttr.car_id == owner.car_id) && (carAttr.id == owner.id) && (data.store_owner[key].id != data.store_owner[k].id)) {
                            obj.count += data.store_owner[k].count;
                            obj.owner.push(data.store_owner[k]);
                        }
                    }
                    arr.push(obj);
                    carID.push(carAttr.car_id);
                    carColorID.push(carAttr.id);
                }

            }
        }
        if (arr.length){
            this.data = arr;
            this.order = data;
        }
    },
    filters:{
      carType(val){
            var name = ['商品车','运损车','换代新车','库存车','采集车','活动车'];
            return name[val-1];
        }
    },
    methods:{
        // 退款 /  确认收货
        handleEvent(e,owner,val){
            if(val){
                this.showTakeoverModal = true;
            }else {
                this.showReturnModal = true;
            }
            this.currentData = owner;
            // this.msg = '退款申请';
            // var el = e.target.parentNode;
            // edit(owner.id,{order_status:val}).then(res => {
            //     if (res.data.code == 1){
            //         el.setAttribute('disabled',true);
            //         this.$forceUpdate();
            //         return this.$Message.success(this.msg + '成功');
            //     }
            //     return this.$Message.error(this.msg + '失败');
            // });
        },
    }
}
</script>
<style lang="less" scoped>
.order-details{
    margin: 0 12px;
    width: 100%;
}
.process {
    background: #fff;
    padding: 12px;
    margin-bottom: 12px;
}
.body{

}
.details-item{
    .item-car{
        height: 160px;
        background: #fff;
        margin-bottom: 12px;
        padding: 12px;
        overflow: hidden;
        .tupian {
            width: 230px;
            height: 100%;
            margin-right: 20px;
            /*border: 1px solid #0e0e0e;*/
            float: left;
        }
        .message {
            height: 100%;
            float: left;
            .carName {

            }
            .price {
                margin-top: 15px;
                span {
                    margin-right: 15px;
                    font-weight: 900;
                    font-size: 16px
                }
            }
            .car-color {
                margin-top: 15px;
                font-size: 14px;
            }
            .car-number {
                font-size: 14px;
                margin-top: 12px;
            }
        }
    }

    .item-owner{
        &>h3{
            background: #F7F8FA;
            padding-left: 12px;
        }
        .owner-b{
            height: 116px;
            background: #fff;
            overflow: hidden;
            padding: 12px;
            position: relative;
            &>div{
                height: 100%;
                float: left;
                margin-right: 40px;
            }
            p{
                padding: 6px;
            }
            .owner-c{
                display: flex;
                align-items: center;
            }
            .owner-h{
                float: right;
                height: 68px;
                position:absolute;
                top: 50%;
                transform: translateY(-50%);
                right: 50px;
                &>p{
                    font-size: 14px;
                    font-weight: bold;
                }
                span{
                    margin-left: 12px;
                }
            }
        }
    }
}
</style>
