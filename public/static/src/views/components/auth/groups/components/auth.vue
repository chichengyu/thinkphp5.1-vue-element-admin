<template>
    <div class="auth" style="padding-right: 60px">
        <Form ref="form" :label-width="100">
            <Row :gutter="32" v-if="rules" v-for="(item,key) in rules" :key="key">
                <Col>
                    <FormItem :label="item.title">
                        <CheckboxGroup v-model="ids" @on-change="checkAllGroupChange" >
                            <Checkbox v-if="item.child" v-for="(itemChild,k) in item.child" :key="k" :label="itemChild.id" >{{ itemChild.title }}</Checkbox>
                        </CheckboxGroup>
                    </FormItem>
                </Col>
            </Row>
        </Form>
        <div class="footer">
<!--            <Button style="margin-right: 8px" @click="showDrawer">返回</Button>-->
            <Button type="primary" :loading="loading" @click="handleSubmit">提交</Button>
        </div>
    </div>
</template>
<script>
    import { setRules } from '@/api/admin/groups.js'
    export default {
        props:['show','rules'],
        data () {
            return {
                loading: false,
                ids:[],
                formData:null,
                indeterminate: false,
                active:true
            }
        },
        methods:{
            // 显示隐藏模态表单
            showDrawer () {
                this.$emit('showTableAuthEvent');
            },
            // 修改后提交
            handleSubmit () {
                if (this.ids.length > 0) {
                    this.loading = true;
                    setTimeout(() => {
                        setRules({rules:this.ids.join(',')},this.formData.id).then(res =>{
                            if (res.data.code == 1){
                                this.$Message.success('修改成功');
                                this.showDrawer();
                            }else {
                                this.$Message.error(res.data.msg);
                            }
                            this.loading = false;
                        })
                    })
                }
            },
            // 接收修改时传递的函数
            currentEditData (data) {
                this.ids = data.rules.split(',');
                this.ids = this.ids.map(item => Number(item))
                this.formData = data;
            },
            checkAllGroupChange(ids){
                this.ids = ids;
            },
            // 选中  类名ivu-checkbox-checked
            // handleCheckAll (e,item) {
            //     var span = e.currentTarget.children[0];
            //     var classNameList = [].slice.call(span.classList);
            //     var currentIds = getIds(item);
            //     console.log(currentIds);
            //     if (classNameList.includes('ivu-checkbox-checked')) {
            //         span.classList.remove('ivu-checkbox-checked');
            //         this.ids = this.ids.filter(item => !currentIds.includes(item));
            //     }else {
            //         span.classList.add('ivu-checkbox-checked');
            //         this.ids = this.ids.concat(currentIds);
            //     }
            // },
        }
    }
    // 获取当前模块下的 ids
 function getIds (item) {
    var ids = [];
    for (var key in item){
        if (key == 'id'){
            ids.push(item.id)
        }
        if (key == 'child') {
            for(var k in item[key]){
                ids.push(item[key][k].id);
            }
        }
    }
    return ids;
}
</script>
<style lang="css" scoped>
.footer{
    width: 100%;
    margin-left: 10%;
    text-align: center;
}
</style>
