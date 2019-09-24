<template>
    <div style="">
        <!-- 单张 -->
        <upload :action="'#'" :previewImages="url"
            @before="handleBefore"
            @success="handleSuccess"
            @remove="handleRemove">
        </upload>

        <hr>

        <!-- 多张 -->
        <upload :action="action" :previewImages="images"
            @before="handleBefore"
            @success="handleSuccess"
            @remove="handleRemove" :autoUpload="true">
        </upload>

        <hr>

        <!-- 多张 -->
        <file-upload :action="actionFile"></file-upload>
    </div>
</template>

<script>
import upload from '@/components/upload'
import fileUpload from '@/components/fileUpload'
export default {
    name: "Upload",
    components:{
        upload,
        fileUpload
    },
    data () {
        return {
            url:'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
            images:[
                'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
                'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
                'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
                'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
                'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
            ],
            action:'',
            actionFile:''
        }
    },
    methods:{
        handleBefore(file){
            console.log(file);
        },
        handleSuccess(response, file, fileList){
            console.log(response);
        },
        handleRemove(file){
            console.log('删除图片',file);
        }
    }
}
</script>

<style lang="css" scoped>

</style>