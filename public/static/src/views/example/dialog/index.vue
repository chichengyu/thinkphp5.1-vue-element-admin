<template>
    <div class="dialog">
        <el-button type="text" @click="dialogVisible = true">点击打开 Dialog</el-button>
        <dialog-custom
           :title="'Dialog弹窗'"
           :width="50"
           :visible.sync="dialogVisible"
           @handleCancel="handleCancel"
           @handleOk="handleOk">
            <div slot="dialog">
                <span>这是 Dialog 弹窗封装组件</span>
            </div>
        </dialog-custom>
    </div>
</template>

<script>
import dialogCustom from '@/components/dialog'
export default {
    name: "Dialog",
    components:{
        dialogCustom
    },
    data () {
        return {
            dialogVisible:false
        }
    },
    methods:{
        handleCancel(){
            console.log('取消了');
        },
        handleOk(){
            console.log('确定了');
        }
    }
}
</script>

<style lang="css" scoped>

</style>