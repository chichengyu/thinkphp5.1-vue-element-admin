<template>
    <div class="upload">
        <el-upload :name="name" class="avatar-uploader"
            :action="action"
            :disabled="disabled"
            :list-type="listType"
            :auto-upload="autoUpload"
            :multiple="multiple"
            :accept="accept"
            :headers="headers"
            :file-list="fileImageList"
            :data="data"
            :before-upload="handleBefore"
            :on-success="handleSuccess"
            :on-error="handleError">
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
        <el-dialog title="预览" :visible.sync="visible" :append-to-body="appendToBody">
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
        data:{// 上传时附带的额外参数
            type:Object,
            default:() => {}
        },
        previewImages:[String,Array],// 这个fileList是自己定义的一个文件列表，显示的文件就是这个里边的文件
        autoUpload:{// 是否在选取文件后立即进行上传
            type:Boolean,
            default:true
        },
        appendToBody:{// 是否嵌套 嵌套的 Dialog 必须指定该属性并赋值为 true
            type:Boolean,
            default:false
        },
        params:[String,Number,Object,Array],// 传递的参数，用于外部判断
    },
    data() {
        return {
            dialogImageUrl: '',
            visible: false,
            headers:{token:this.$store.getters.userInfo.token||''},
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
                if (val){
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
            }
        },
    },
    methods: {
        handleRemove(file) {
            this.$emit('remove',file,this.fileImageList,this.params,()=>{
                this.fileImageList.includes(file) && this.fileImageList.splice(this.fileImageList.indexOf(file),1)
            },(delImageUrl,params,callBack,type='post',header={})=>{
                if (delImageUrl && params) {
                    this.axios.request({
                        // url:delUploadImageUrl,
                        url:delImageUrl,
                        method:type,
                        headers:header,
                        // data:{path:file.response.path}
                        data:params
                    }).then(res => {
                        if (res.data.code == 1){
                            this.fileImageList.includes(file) && this.fileImageList.splice(this.fileImageList.indexOf(file),1);
                            this.success('删除成功！');
                        }else{
                            this.error('删除失败！');
                        }
                        // this.$emit('remove',file,this.params);
                        return callBack && callBack(res);
                    }).catch(err => {
                        return Promise.reject('删除失败！',err);
                    });
                }else{
                    throw new Error('两个参数：接口地址或与数据必须传递！');return;
                }
            });
        },
        handlePictureCardPreview(file) {
            this.dialogImageUrl = file.url;
            this.visible = true;
        },
        handleBefore(file){
            return this.$emit('before',file,this.params);
        },
        handleSuccess(response, file, fileList){
            this.fileImageList = fileList;
            this.$emit('success',response, file, fileList,this.params,(success,error)=>{
                if (response.code == 1){
                    this.success('上传成功！');
                    return success && success();
                }else{
                    this.error('上传失败！');
                    return error && error();
                }
            });
        },
        handleError(err, file, fileList){
            this.$emit('error',err, file, fileList,this.params);
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