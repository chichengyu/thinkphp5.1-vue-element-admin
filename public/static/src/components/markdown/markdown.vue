<template>
    <div class="markdown" >
         <mavon-editor :subfield="false" v-model='value' style="height: 100%" @change="eventContent"></mavon-editor>
    </div>
</template>

<script>
    import mavonEditor from 'mavon-editor'
    import 'mavon-editor/dist/css/index.css'
    export default {
        name:'Markdown',
        data () {
            return {
                value:'',
                // markdownOption:{
                //     // subfield:false,
                //     // defaultOpen:'edit'
                // }
            }
        },
        components:{
            'mavon-editor': mavonEditor.mavonEditor
        },
        methods:{
            eventContent(){
                this.$emit('getMarkdownVal',this.value);
            }
        }
    }
</script>

<style lang="stylus" scoped>
    .markdown{
        height: 100%;
        background: #fff;
    }
</style>