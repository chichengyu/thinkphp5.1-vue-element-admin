<template>
    <div class="downshopcar">
        <h3>下单页</h3>
        <div class="content">
            <p>购车方式</p>
            <RadioGroup v-model="payType">
                <Radio :label="0">全款支付订金(10%)</Radio>
                <Radio :label="1">垫资订金(30%)</Radio>
            </RadioGroup>
        </div>
        <div class="body" v-if="downorderData.length" v-for="(item,index) in downorderData" :key="index">
            <div class="body-item">
                <div class="body-car">
                    <div class="tupian"><img :src="item.thumbnail" width="100%" height="100%"></div>
                    <div class="message">
                        <div class="carName">
                            <h2>{{item.car_name}} <Tag color="success">{{classifyType(item.classify)}}</Tag></h2>
                        </div>
                        <div class="price">
                            <span>指导价：<b>{{item.price_zdj}}万</b></span>
                            <span>拿车价：<b class="color-red">{{item.price_jxs}}万</b></span>
                            <span>门店价：<b class="color-red">{{item.price_mdj}}万</b></span>
                        </div>
                        <p class="car-color">{{item.car_color}} X{{item.car_number}}</p>
                        <div class="car-number">共计：<span style="color: red;">{{(item.price_jxs*item.car_number).toFixed(4)}}万</span></div>
                    </div>
                </div>
                <div class="body-owner" v-for="(itemowner,key) in item.owner" :key="key">
                    <div class="owner-b">用户{{key+1}} 基本信息
                        <span @click="addOwnerList(index,item.car_number)" v-show="key==0">添加用户信息</span>
                        <span @click="deleteOwnerList(index,key)" v-show="key!=0">删除</span>
                    </div>
                    <div class="owner-a">
                        <div>
                            <span class="owner-s">车主姓名</span>
                            <Input v-model="itemowner.ownerName" size="large" placeholder="" style="width: 180px;"/>
                        </div>
                        <div>
                            <span class="owner-s">车主联系方式</span>
                            <Input v-model="itemowner.ownerTel" size="large" placeholder="" style="width: 180px;" @on-blur="changeBlur(itemowner.ownerTel)"/>
                        </div>
                        <div>
                            <span class="owner-s">车主身份证 &nbsp;&nbsp;&nbsp; 正面</span>
                            <upload style="margin-top:-20px;" :uploadUrl="uploadUrl" :params="{key,index}" ref="uploadImage" @changeImageUrl='uploadImageUrlF' ></upload>
                        </div>
                        <div>
                            <span class="owner-s">反面</span>
                            <upload style="margin-top:-20px;" :uploadUrl="uploadUrl" :params="{key,index}" ref="uploadImage" @changeImageUrl='uploadImageUrlR' ></upload>
                        </div>
                        <div>
                            <p class="c-p" style="float: left;align-self: center;">
                                <span style="border-right: none" @click="countNum(false,index,key)">-</span>
                                <input type="text" style="width: 50px;" v-model="itemowner.carNumber">
                                <span style="border-left: none" @click="countNum(true,index,key)">+</span>
                            </p>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="footer">
            <div class="footer-right">
                <p>总价：￥{{totalPrices()}}万</p>
                <p>定金：￥{{earnestMoney(this.payType)}}万</p>
                <p>余款：￥{{spareMoney()}}万</p>
                <div class="footer-a">
                    <p>应支付金额：<span>￥{{earnestMoney(this.payType)}}万</span></p>
                    <Button type="error" @click="confirmPayment()">确认支付</Button>
<!--                    <router-link tag="Button" to="../payment" style="float: right;width: 150px;font-size: 14px;background:#f16643;color: #fff;">确认支付</router-link>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import upload from './upload/upload.vue'
    import {uploadOwnerIdCardUrl} from '@/env.js'
    import {downOrder} from '@/api/admin/order.js'

    export default {
        // props:['show','groups'],
        components:{
            upload
        },
        data () {
            return {
                loading: false,
                payType:0,
                downorderData:'',
                styles: {
                    height: 'calc(100% - 55px)',
                    overflow: 'auto',
                    paddingBottom: '53px',
                    position: 'static'
                },
                uploadUrl:uploadOwnerIdCardUrl,
                telVerfy:false,
                isDataVerfy:false
            }
        },
        mounted () {
            var shopcar = this.$ls.get('shopcar');
            shopcar && (this.downorderData = shopcar);
        },

        methods:{
            //添加数量
            countNum(flag,index,key){
                var arr = [];
                var num = 0; // 车主车辆的总数
                var owner = this.downorderData[index].owner;
                for(var k2 in owner){
                    num += owner[k2].carNumber;
                }
                for(var i= 0;i < owner.length;i++){
                    arr.push(owner[i]);
                }
                var obj = {};
                for (var k1 in arr[key]){
                    obj[k1] = arr[key][k1];
                }
                if(flag){
                    if(this.downorderData[index].car_number>num){
                        obj.carNumber++;
                        arr[key] = obj;
                        this.downorderData[index].owner = arr;
                    }
                }else{
                    if(obj.carNumber > 1){
                        obj.carNumber--;
                        arr[key] = obj;
                        this.downorderData[index].owner = arr;
                    }
                }
                this.$forceUpdate();
            },

            // 汽车类型
            classifyType(classify){
                 let car_type='';
                 switch (classify) {
                     case 1:car_type='新款车';break;
                     case 2:car_type='运损车';break;
                     case 3:car_type='换代新车';break;
                     case 4:car_type='库存车';break;
                     case 5:car_type='集采车';break;
                     case 6:car_type='活动车';break;
                 }
                 return car_type;
            },
            //添加车主
            addOwnerList(index,count){
                var length = this.downorderData[index].owner.length;
                if(count>length){
                    let arr = [];
                    var val = this.downorderData[index].owner;
                    for(let key in val){
                        arr.push(val[key]);
                    }
                    arr.push(
                        {
                            ownerName:'',
                            ownerTel:'',
                            identityCardL:'',
                            identityCardF:'',
                            carNumber:0,
                        }
                    );
                    this.downorderData[index].owner = arr;
                }
            },
            //删除车主
            deleteOwnerList(index,key){
                this.downorderData[index].owner.splice(key,1);
            },
            //总价格
            totalPrices(){
                var num=0;
                var arr = this.downorderData;
                for(var key in arr){
                    num =num + (arr[key].price_jxs*arr[key].car_number);
                }
                return num.toFixed(4);
            },
            //定金
            earnestMoney(type){
                if(type==0){
                    var num=0;
                    num = this.totalPrices()*0.1;
                    return num.toFixed(4);
                }else if(type==1){
                    var num=0;
                    num = this.totalPrices()*0.3;
                    return num.toFixed(4);
                }
            },
            //余款
            spareMoney(){
                var num=0;
                num = this.totalPrices()-this.earnestMoney(this.payType);
                return num.toFixed(4);
            },
            //表单数据
            formData(){
                var arr = {};
                var Data = this.downorderData;
                arr["pay_type"] = this.payType;
                arr["store_cart_ids"] = [];
                arr["products"] = [];
                for(var key1 in Data){
                    arr["store_cart_ids"].push(Data[key1].id);
                }
                for(var key in Data){
                    arr.products[key]={
                        "car_id":Data[key].car_id,
                        "car_number_id": Data[key].car_attr_id,
                        "count": Data[key].car_number,
                        "owner":[]
                    };
                    for(var k in Data[key].owner){
                        let owner = Data[key].owner[k];
                        if (owner.ownerName && owner.ownerTel && owner.ownerIdentityF && owner.ownerIdentityR && owner.carNumber){
                            arr.products[key].owner.push({
                                "owner_name": owner.ownerName,
                                "owner_tel": owner.ownerTel,
                                "owner_identity_f": owner.ownerIdentityF,
                                "owner_identity_r": owner.ownerIdentityR,
                                "count": owner.carNumber,
                            })
                        }
                    }
                }
                return arr;
            },
            //确认提交订单 确认支付
            confirmPayment(){
                if (!this.telVerfy){
                    return this.$Message.error('车主手机号码填写不正确！');
                }
                var data = this.formData();
                var flag = data.products.some(item => {
                    if (item && item.owner){
                        var count = 0;
                        for(var key in item.owner){
                            count += item.owner[key].count;
                        }
                        return item.count != count;
                    }
                });
                if (flag){
                    return this.$Message.error('车主信息填写不正确！');
                }
                downOrder(data).then(res => {
                    if (res.data.code == 1){
                        this.$ls.set('shopcar',null);
                        this.$Message.success(res.data.msg);
                        this.$router.push({path:'/payment',query:{order:res.data.data}});
                    }else {
                        this.$Message.error(res.data.msg);
                    }
                });
            },
            changeBlur (val) {
                let reg = /^1(3|4|5|7|8|9)[0-9]\d{8}$/;
                if(val && reg.test(val)){
                    this.telVerfy = true;
                }else {
                    this.$Message.error('车主手机号码填写不正确！');
                }
            },
            uploadImageUrlF(path,params){
                console.log(path);
                this.downorderData[params.index].owner[params.key]['ownerIdentityF'] = path;
            },
            uploadImageUrlR(path,params){
                this.downorderData[params.index].owner[params.key]['ownerIdentityR'] = path;
            }
        }
    }
</script>

<style lang="less" scoped>
.downshopcar{
    margin: 0 12px;

    &>h3{

    }
    .content{
        background: #fff;
        width: 100%;
        padding: 12px;
        margin-bottom: 12px;
        &>p{
            padding-bottom: 12px;
            font-weight: bold;
            font-size: 14px;
        }
    }
    .body{

    }
    .body-item{
        .body-car{
            height: 160px;
            background: #fff;
            margin-bottom: 12px;
            padding: 12px;
            overflow: hidden;
            .tupian {
                width: 230px;
                height: 100%;
                margin-right: 20px;
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
                .car-number{
                    font-size: 14px;
                    margin-top: 12px;
                }
            }
        }
        .body-owner{
            background: #fff;
            margin-bottom: 12px;
            padding: 12px;
            overflow: hidden;
            .owner-b{
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 12px;
                &>span{
                    float: right;
                    cursor: pointer;
                    user-select: none;
                }
            }
            .owner-a{
                display: flex;
                align-items: center;
                &>div{
                    margin-right: 20px;
                    display: flex;
                    /*border: 1px solid red;*/
                    position: relative;
                    width: 268px;
                    height: 38px;
                }
                .owner-s{
                    display: flex;
                    align-items: center;
                    margin-right: 12px;
                }

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
                    user-select: none;
                    cursor: pointer;
                    font-size: 15px;
                    font-weight: bold;
                }
                input{
                    height: 31px;
                    text-align: center;
                }
            }
        }
    }
    .footer{
        background: #fff;
        overflow: hidden;
        padding: 12px;
        .footer-right{
            float: right;
            /*border: 1px solid #0e0e0e;*/
            &>p{
                padding-left: 80px;
                font-size: 14px;
                font-weight: bold;
            }
            .footer-a{
                display: flex;

                &>p{
                    display: flex;
                    margin-right: 12px;
                    align-items: center;
                    span{
                        color: red;
                        font-size: 14px;
                    }
                }
            }
        }
    }

}

.color-red{
    color: red;
}
</style>
