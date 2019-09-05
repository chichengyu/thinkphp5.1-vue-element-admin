<template>
    <el-dialog
        :title="title"
        :visible.sync="visibled"
        :width="width+'%'"
        :close-on-click-modal="false"
        :before-close="handleClose">
        <img :src="src" width="100%" height="100%" alt="">
    </el-dialog>
</template>

<script>
    export default {
        props:{
            src:{
                type:String,
                required:true
            },
            width:{
                type:Number,
                default:30
            },
            title:{
                type:String,
                default: '预览'
            }
        },
        data() {
            return {};
        },
        computed:{
            visibled:{
                get(){
                    return this.$attrs.visible;
                },
                set(){}
            }
        },
        methods: {
            handleClose(done) {
                this.$emit('update:visible',false);
                done();
            }
        }
    };
</script>

<style lang="css" scoped>

</style>