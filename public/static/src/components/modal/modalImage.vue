<template>
    <Modal v-model='show'
       :title="title"
       :footer-hide="true"
       @on-visible-change="change"
    >
        <img :src="imageUrl" width="100%" height="100%" alt="">
    </Modal>
</template>
<script>
    export default {
        props:{
            show:{
                type:Boolean,
                default:false
            },
            title:{
                type:String,
                default:'查看图片'
            },
            imageUrl:{
                type:String,
                default:''
            }
        },
        data () {
            return {

            }
        },
        methods: {
            change (flag) {
                this.$emit('change',flag);
            }
        }
    }
</script>
