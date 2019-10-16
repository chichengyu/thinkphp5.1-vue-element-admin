<template>
    <div class="Preview">
		<component-preview :title="'查看原图'" :src="url" :width="100" :height="100" :appendToBody="true"></component-preview>
		
		<hr>
		
		<component-preview v-for="(item,index) in images" :key="index" :title="'查看原图'" :src="item" :appendToBody="true"></component-preview>
	</div>
</template>

<script>
export default {
    name: "Preview",
    data () {
        return {
            abc:false,
            url:'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
            images:[
                'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
                'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
                'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
                'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
                'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
            ],
        }
    }
}
</script>

<style scoped>

</style>