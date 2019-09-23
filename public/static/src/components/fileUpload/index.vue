<template>
    <div class="file-upload">
        <el-upload :name="name" class="avatar-uploader"
            :action="action"
            :show-file-list="showFileList"
            :disabled="disabled"
            :auto-upload="autoUpload"
            :accept="accept"
            :headers="headers"
            :file-list="fileImageList"
            :data="params"
            :before-upload="handleBefore"
            :on-success="handleSuccess"
            :on-error="handleError">
            <el-button :size="size" type="primary">{{ title }}</el-button>
        </el-upload>
    </div>
</template>

<script>
export default {
    name: "File-upload",
    props:{
        name:{
            type:String,
            default:'file',
        },
        disabled:{// 是否禁用
            type:Boolean,
            default: false
        },
        action:{// 必选参数，上传的地址
            type:String,
            requred:true
        },
        accept:{// 支持的图片格式
            type:String,
            default:'.xlsx'
        },
        params:{// 上传时附带的额外参数
            type:Object,
            default:() => {}
        },
        autoUpload:{// 是否在选取文件后立即进行上传
            type:Boolean,
            default:true
        },
        showFileList:{
            type:Boolean,
            default:false
        },
        title:{
            type:String,
            default:'上传'
        },
        size:{
            type:String,
            default:'small'
        },
    },
    data() {
        return {
            visible: false,
            headers:{token:this.$store.getters.userInfo.token||'1231321'},
            // fileList:[
            //     {name: 'food.jpg', url:'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20'}
            // ],
            fileImageList:[]
        };
    },
    methods: {
        /*handleRemove(file) {
            if (file.response && file.response.path) {
                this.axios.request({
                    url:delUploadImageUrl,
                    method:'post',
                    data:{path:file.response.path}
                }).then(res => {
                    if (res.data.code == 1){
                        this.fileImageList.includes(file) && this.fileImageList.splice(this.fileImageList.indexOf(file),1);
                        this.success('删除成功！');
                    }else{
                        this.error('删除失败！');
                    }
                    this.$emit('remove',file);
                }).catch(err => {
                   return Promise.reject('删除失败！',err);
                });
            }
        },*/
        handleBefore(file){
            this.$emit('before',file);
        },
        handleSuccess(response, file, fileList){
            if (response.code == 1){
                this.success('上传成功！');
            }else{
                this.error('上传失败！');
            }
            this.fileImageList = fileList;
            this.$emit('success',response, file, fileList);
        },
        handleError(err, file, fileList){
            this.$emit('error',err, file, fileList);
        }
    }
}
</script>

<style lang="css" scoped>

</style>