<template>
    <div class="result" v-if="payData">
        <h3>选择支付方式</h3>
        <div class="content">
            <h2>购车方式</h2>
            <p>订单支付成功</p>
            <p>订单号：<b style="color: red">{{ payData.orderNo }}</b></p>
            <p>应付金额：<span class="p-s">￥ <b style="color: red">{{ payData.order_amount_total|total }}</b> 万元</span></p>
<!--            <p class="content-right">查看订单详情</p>-->
        </div>
        <div class="paytype" style="text-align: center">
            <h1 style="padding: 50px;color: red">订单支付成功！</h1>
        </div>
    </div>
</template>

<script>
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
            }
        },
        filters:{
            total(val){
                return val / 1000000;
            }
        },
        mounted () {
            let result = this.$route.query.result;
            result && (this.payData = result);
        }
    }
</script>

<style lang="less" scoped>
.result {
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
}

.color-red{
    color: red;
}
</style>
