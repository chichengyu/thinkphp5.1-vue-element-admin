<template>
    <div id="editor">
        <wang-editor :height="350"></wang-editor>
    </div>
</template>

<script>
import WangEditor from '@/components/wangEditor'

export default {
    name: "editor",
    components:{
        WangEditor
    }
}
</script>

<style scoped>

</style>