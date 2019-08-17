<template>
    <html lang="en" v-html="html"></html>
<!--    <div v-html="html"></div>-->
</template>

<script>
export default {
    data () {
        return {
            html:'',
            styles: {
                height: 'calc(100% - 55px)',
                overflow: 'auto',
                paddingBottom: '53px',
                position: 'static'
            },
        }
    },
    mounted () {
        let str = this.$route.query.result;
        this.html = str;
        setTimeout(() => {
            window.location.reload();
        },1000);
    },
    methods:{

    },
}
</script>

<style lang="less" scoped>

</style>