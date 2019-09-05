<template>
    <div class="Preview">
        <h2>点击查看大图</h2>
        <img :src="url" width="100" title="点击预览大图" @click="visible=true" style="cursor: pointer">
        <preview :title="'查看原图'" :visible.sync="visible" :src="url"></preview>
    </div>
</template>

<script>
    import preview from '@/components/preview'
    export default {
        name: "Preview",
        components:{
            preview
        },
        data () {
            return {
                visible:false,
                url:'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20'
            }
        }
    }
</script>

<style scoped>

</style>