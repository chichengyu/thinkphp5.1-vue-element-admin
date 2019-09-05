<template>
    <div class="upload">
        <el-upload :name="name" class="avatar-uploader"
            :action="action"
            :list-type="listType"
            :auto-upload="autoUpload"
            :multiple="multiple"
            :accept="accept"
            :headers="headers"
            :file-list="fileImageList"
            :data="params"
            @before-upload="handleBefore"
            @on-success="handleSuccess"
            @on-error="handleError">
            <i slot="default" class="el-icon-plus avatar-uploader-icon"></i>
            <div slot="file" slot-scope="{file}">
                <img class="el-upload-list__item-thumbnail" :src="file.url" width="100%" height="100%">
                <span class="el-upload-list__item-actions" style="font-size: unset">
                    <span class="el-upload-list__item-preview" @click="handlePictureCardPreview(file)">
                        <i class="el-icon-zoom-in"></i>
                    </span>
                    <span class="el-upload-list__item-delete" style="margin-left: 6px" @click="handleRemove(file)">
                        <i class="el-icon-delete"></i>
                    </span>
                </span>
            </div>
        </el-upload>
        <el-dialog title="预览" :visible.sync="visible">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>
    </div>
</template>

<script>
export default {
    name: "Upload",
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
        listType:{// 文件列表的类型 text/picture/picture-card 默认 text
            type:String,
            default:'picture-card'
        },
        multiple:{// 是否支持多上传
            type:Boolean,
            default:false
        },
        accept:{// 支持的图片格式
            type:String,
            default:'.jpg,.jpeg,.png'
        },
        params:{// 上传时附带的额外参数
            type:Object,
            default:() => {}
        },
        previewImages:[String,Array],// 这个fileList是自己定义的一个文件列表，显示的文件就是这个里边的文件
        autoUpload:{// 是否在选取文件后立即进行上传
            type:Boolean,
            default:false
        }
    },
    data() {
        return {
            dialogImageUrl: '',
            visible: false,
            headers:{token:this.$store.getters.userInfo.token||'1231321'},
            // fileList:[
            //     {name: 'food.jpg', url:'http://p1.meituan.net/wedding/b8a49d1f1b3eb3d4e9eefcddb67bd16b166188.jpg%40watermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20'}
            // ],
            fileImageList:[]
        };
    },
    watch:{
        previewImages:{
            immediate:true,
            handler(val){
                let type = typeof val;
                if (type == 'string'){
                    this.fileImageList = [{name:'01',url:val}];
                }else if (type == 'object'){
                    let arr = [];
                    for (let key in val) {
                        arr.push({name:key,url:val[key]});
                    }
                    this.fileImageList = arr;
                }
            }
        },
    },
    methods: {
        handleRemove(file) {
            this.$emit('upload-remove',file);
        },
        handlePictureCardPreview(file) {
            this.dialogImageUrl = file.url;
            this.visible = true;
        },
        handleBefore(file){
            this.$emit('upload-before',file);
        },
        handleSuccess(response, file, fileList){
            this.$emit('upload-success',response, file, fileList);
        },
        handleError(err, file, fileList){
            this.$emit('upload-error',err, file, fileList);
        }
    }
}
</script>

<style lang="css" scoped>
.upload >>> .el-upload-list--picture-card .el-upload-list__item{
    width: 58px;
    height: 58px;
}
.upload >>> .el-upload-list--picture-card .el-upload-list__item > div{
    width: 100%;
    height: 100%;
}
.upload >>> .el-upload.el-upload--picture-card{
    width: 58px;
    height: 58px;
    line-height: 58px;
}
.avatar-uploader-icon{
    width: 100%;
    height: 100%;
}
.el-upload-list__item-thumbnail{
    width: 100% !important;
    height: 100% !important;
}
</style>