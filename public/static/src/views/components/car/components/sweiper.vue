<template>
    <div class="swiper" v-if="banner.length">
        <swiper :options="swiperOption" ref="mySwiper">
            <!-- slides -->
            <swiper-slide class="img-item stop-swiping" v-for="(item,key) in banner" :key="key">
                <img :src="item.image" @click.stop="changeThumbnailImage(item.image)" width="100%" height="100%">
            </swiper-slide>
            <!-- Optional controls -->
            <div class="swiper-button-prev swiper-button-white" slot="button-prev" @click.stop="bannerChange(false)"></div>
            <div class="swiper-button-next swiper-button-white" slot="button-next" @click.stop="bannerChange(true)"></div>
        </swiper>
    </div>
</template>
<script>
    import 'swiper/dist/css/swiper.css'
    import { swiper, swiperSlide } from 'vue-awesome-swiper'
    export default {
        name: 'carrousel',
        props:['banner'],
        data() {
            return {
                swiperOption: {
                    // some swiper options/callbacks
                    // 所有的参数同 swiper 官方 api 参数
                    // ...
                    slidesPerView: 3,
                    allowSwipeToPrev : false,
                    allowSwipeToNext : false,
                    noSwiping : true,
                    noSwipingClass : 'stop-swiping',
                },
                index:0
            }
        },
        components:{
            swiper,
            swiperSlide
        },
        computed: {
            swiper() {
                return this.$refs.mySwiper.swiper
            }
        },
        mounted() {
            this.swiper.slideTo(this.index, 1000, false)
        },
        methods:{
            bannerChange(flag){
                if (flag){
                    (this.index+3 < this.banner.length) && (this.index+=3);
                }else {
                    (this.index-3 >= 0) && (this.index-=3);
                }
                this.swiper.slideTo(this.index, 1000, false)
            },
            changeThumbnailImage(url){
                this.$emit('update:thumbnailImage',url);
            }
        }
    }
</script>

<style lang="css" scoped>
.swiper{
    height: 100%;
}
.swiper .img-item {
    float: left;
    width: 31% !important;
    height: 100%;
    cursor: pointer;
}
.swiper .img-item +.img-item{
     margin-left: 10px;
}
.swiper-container{
    height: 100%;
}
.swiper-button-next, .swiper-button-prev{
    top: 74%;
    width: 20px;
    height: 20px;
    background-size: 7px;
    background-color: rgba(0,0,0,.3);
    border-radius: 50%;
}
</style>