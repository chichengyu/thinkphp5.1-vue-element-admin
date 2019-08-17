<template>
    <div class="shopcar">
        <div class="img" v-if="!shopcar.length">
            <img src="@/assets/admin/images/gouwuche.png" alt="">
        </div>
        <div v-else>
            <div class="content">
                <div class="item" v-for="(item,index) in shopcar" :key="index">
                    <div class="box">
                        <Checkbox v-model="item.check"/>
                    </div>
                    <div class="cars">
                        <div class="tupian"><img :src="item.thumbnail" width="100%" height="100%"></div>
                        <div class="message">
                            <div class="carName">
                                <h2>{{ item.car_name }} <Tag color="success">{{ item.classify|carType }}</Tag></h2>
                            </div>
                            <div class="price">
                                <span>指导价：<b>{{ item.price_zdj }}万</b></span>
                                <span>拿车价：<b class="color-red">{{ item.price_jxs }}万</b></span>
                                <span>门店价：<b class="color-red">{{ item.price_mdj }}万</b></span>
                            </div>
                            <p class="car-color">{{ item.car_color }}</p>
                            <p class="c-p" style="float: left;align-self: center;">
                                <span style="border-right: none" @click="countNum(index,false)">-</span>
                                <input type="text" style="width: 50px;" :value="item.car_number">
                                <span style="border-left: none" @click="countNum(index,true)">+</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer">
                <Button size="small" type="primary" style="margin-right: 12px;" @click="handleCheckAll">全 选</Button>
                <Button size="small" type="error" @click="deleteCheck()">删 除</Button>
                <Button size="small" type="error" class="footer-r" @click="getData()">结 算</Button>
            </div>
        </div>
    </div>
</template>

<script>
import { getTableData,del } from '@/api/admin/car_shop.js'

export default {
    data () {
        return {
            shopcar:[],
        }
    },
    mounted () {
        this.getListData();
    },
    filters:{
        carType (classify){
            var name = ['商品车','运损车','换代新车','库存车','采集车','活动车'];
            return name[classify-1];
        }
    },
    methods:{
        getListData() {
            getTableData().then(res =>{
                if (res.data.code == 1){
                    res = res.data.data.data;
                    res.data.map(item => {
                        item.check = false;
                        item.price_jxs && (item.price_jxs = this.price(item.price_jxs));
                        item.price_mdj && (item.price_mdj = this.price(item.price_mdj));
                        item.price_zdj && (item.price_zdj = this.price(item.price_zdj));
                    });
                    this.shopcar = res.data;
                }
            })
        },
        handleCheckAll(){
            this.shopcar.map(item => item.check=true);
            this.$forceUpdate();
        },
        deleteCheck(){
            var ids = [];
            this.shopcar.map((item,index) => {
                if (item.check){
                    ids.push(item.id);
                    this.shopcar.splice(index,1);
                }
            });
            if (ids.length){
                setTimeout(() => {
                    del(ids.join(',')).then(res => {
                        if (res.data.code == 1){
                            this.$Message.success(res.data.msg);
                        }else {
                            this.$Message.error(res.data.msg);
                        }
                    });
                })
            }
        },
        countNum(index,flag){
            if(flag){
                this.shopcar[index].car_number++;
            }else{
                if(this.shopcar[index].car_number > 1){
                    this.shopcar[index].car_number--;
                }
            }
        },
        // 页面跳转
        getData(){
            this.shopcar.map(item => {
                if (item.check){
                    item.owner = [{
                        ownerName:'',
                        ownerTel:'',
                        ownerIdentityF:'',
                        ownerIdentityR:'',
                        carNumber:0,
                    }];
                }
            });
            this.$ls.set('shopcar',this.shopcar.filter(item => item.check==true));
            this.$router.push('/downorder');
        },
    }
}
</script>

<style lang="less" scoped>
.shopcar {
    margin: 0 12px;
    .img{
        width: 100%;
        padding: 50px;
        text-align: center;
    }
    .content {
        /*height: 300px;*/
        /*background: red;*/
        .item {
            height: 185px;
            margin: 15px 0;
            padding: 15px 0;
            background: #fff;
            overflow: hidden;
            /*position: relative;*/
            .box {
                width: 4%;
                height: 100%;
                float: left;
                display: flex;
                justify-content: center;
                align-items: center;
                & > p {
                    display: inline-block;
                    border: 1px solid #2d8cf0;
                    width: 15px;
                    height: 15px;
                }
            }
            .cars {
                float: left;
                width: 96%;
                height: 100%;
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
                    .car-color{
                        margin-top: 15px;
                        font-size: 14px;
                    }
                    .c-p{
                        display: flex;
                        align-self: center;
                        margin-top: 15px;
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
        }
    }
    .footer {
        padding: 15px;
        /*height: 32px;*/
        button{
            font-size: 12px;
            font-weight: bold;
        }
        .footer-r{
            float:right;
            font-size: 14px;
            /*height:30px;*/
            width:120px
        }
    }
}
.color-red{
    color: red;
}
</style>