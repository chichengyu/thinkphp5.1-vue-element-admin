<template>
    <div class="payment" v-if="payData">
        <h3>支付</h3>
        <div class="content">
            <h2>购车方式</h2>
            <p>订单提交成功，请尽快付款！</p>
            <p>订单号：<b style="color: red">{{ payData.orderNo }}</b></p>
            <p>应付金额：<span class="p-s">￥ <b style="color: red">{{ this.price(payData.order_amount_total) }}</b> 万元</span></p>
<!--            <p class="content-right">查看订单详情</p>-->
        </div>
        <div class="paytype" v-if="!timer">
            <h2>支付平台</h2>
            <RadioGroup v-model="payType">
                <Radio :label="'1'">微信支付</Radio>
                <Radio :label="'2'">支付宝</Radio>
                <Radio :label="'3'">银联支付</Radio>
            </RadioGroup>
        </div>
        <div class="qrcode">
            <div class="qr" v-show="qrcodeUrl">
                <div ref="qrcode" class="qrcode_img"></div>
                <p>{{ orderText }}</p>
                <h2>30分钟内有效，请尽快支付</h2>
            </div>
<!--            <h1 v-if="showSuccess" style="padding: 50px;color: red">订单支付成功！</h1>-->
        </div>
        <div class="footer">
            <div class="footer-right">
                <p>支付<span>￥<b style="color: red">{{ this.price(payData.order_amount_total) }}</b> 万元</span></p>
                <Button type="error" @click.stop="pay">去支付</Button>
            </div>
        </div>
    </div>
</template>

<script>
    import {pay,paysuccess,unionpay} from '@/api/admin/order.js'
    import QRCode from 'qrcodejs2'

    export default {
        data () {
            return {
                loading: false,
                payType:1,
                styles: {
                    height: 'calc(100% - 55px)',
                    overflow: 'auto',
                    paddingBottom: '53px',
                    position: 'static'
                },
                payData: {},
                qrcodeUrl:'',
                showSuccess:false,
                timer:null,
                orderText:'请用微信扫码完成支付'
            }
        },
        mounted () {
            let order = this.$route.query.order;
            order.id && (order.order_id = order.id);
            order.order_no && (order.orderNo = order.order_no);
            order && (this.payData = order);
        },
        methods:{
            pay(){
                if(this.payType==2)this.orderText='请用支付宝扫码完成支付';
                var params = this.payData.order_id + '?payType=' + this.payType;
                if(this.payType == 3){
                    pay(params).then(res => {
                        if (res.data.code == 1) {
                            if(res.data.data){
                                this.$router.push({path:'/union',query:{result:res.data.data.html}})
                            }
                        }else {
                            this.$Message.error(res.data.msg);
                        }
                    });
                }else {
                    pay(params).then(res => {
                        if (res.data.code == 1){
                            this.qrcodeUrl = res.data.data.qrcode;
                            // this.$forceUpdate();
                            new QRCode(this.$refs.qrcode, {
                                width: 132,
                                height: 132,
                                text: this.qrcodeUrl, // 二维码地址
                                colorDark : "#000",
                                colorLight : "#fff",
                            });
                            if (this.qrcodeUrl){
                                let oldTime = new Date();
                                let time = 30*60*1000;
                                    this.timer = setInterval(() => {
                                        if ((new Date() - oldTime) < time){
                                            paysuccess({orderNo:this.payData.orderNo}).then(res => {
                                                if (res.data.code == 1) {
                                                    res = res.data.data;
                                                    if ((res.return_code == 'SUCCESS') && (res.trade_state == 'SUCCESS')){
                                                        this.$router.push({path:'/pay_result',query:{result:this.payData}});
                                                        this.timer && clearInterval(this.timer);
                                                        this.qrcodeUrl = null;
                                                        this.showSuccess = true;
                                                    }else if (res.return_code == 'FAIL') {
                                                        this.timer && clearInterval(this.timer);
                                                        this.$Notice.error({
                                                            title: '支付结果！',
                                                            desc: '支付失败！请稍后再试！'
                                                        });
                                                    }
                                                }
                                            });
                                        }else {
                                            this.timer && clearInterval(this.timer);
                                        }
                                    },3000);
                            }
                        }
                    })
                }
            }
        },
        beforeRouteLeave (to, from, next){
            this.timer && clearInterval(this.timer);
            next();
        }
    }
</script>

<style lang="less" scoped>
.payment {
    margin: 0 12px;
    &>h3{
        margin-bottom: 12px;
    }
    .content{
        padding: 12px;
        background: #fff;
        overflow: hidden;
        &>h2{
            margin-bottom: 12px;
        }
        p{
            float: left;
            margin-right: 20px;
        }
        .p-s{
            font-weight: bold;
        }
        .content-right{
            float: right;
            color: #6D87FA;
        }
    }
    .paytype{
        padding: 12px;
        background: #fff;
        margin: 12px 0;
        &>h2{
            margin-bottom: 12px;
        }
    }

    .qrcode{
        background: #fff;
        text-align: center;

        .qr{
            margin-top: 32px;
            padding: 32px;
            text-align: center;

            .qrcode_img{
                display: inline-block;
            }

            h2{
                color: #fb8928;
            }
        }
    }

    .footer-right{
        display: flex;
        float: right;
        padding-right: 18px;
        &>p{
            display: flex;
            align-items: center;
            font-size: 14px;

            margin-right: 18px;
            span{
                color: red;
            }
        }
    }
}

.color-red{
    color: red;
}
</style>
