<template>
    <div style="">
        <!-- 单张 -->
        <component-upload :action="'#'" :previewImages="url"
            @before="handleBefore"
            @success="handleSuccess"
            @remove="handleRemove">
        </component-upload>

        <hr>

        <!-- 多张 -->
        <component-upload :action="action" :previewImages="images"
            @before="handleBefore"
            @success="handleSuccess"
            @remove="handleRemove" :autoUpload="true">
        </component-upload>

        <!-- 文件上传 -->
        <component-file-upload :action="actionFileUploadUrl" :size="'default'" :title="'导入'" @before="handleExcelBefore" @success="handleExcelbSuccess" @error="handleExcelError"></component-file-upload>

    </div>
</template>

<script>
export default {
    name: "Upload",
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
            actionFile:'',
            actionFileUploadUrl:''
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
        },
        handleExcelBefore(params){
            console.log(params);
        },
        handleExcelbSuccess(params){
            console.log(params);
        },
        handleExcelError(params){
            tconsole.log(params);
        },
    }
}
</script>

<style lang="css" scoped>

</style>