<template>
    <div class="auth" v-if="rules && rules.length>0">
        <el-row :gutter="20" v-for="(item,key) in rules" :key="key">
            <el-col>
                <el-form label-width="80px">
                    <el-form-item :label="item.title">
                        <el-checkbox-group v-model="ids" @change="change">
                            <el-checkbox v-if="item.children" v-for="(itemChild,k) in item.children" :key="k" :label="itemChild.id">{{ itemChild.title }}</el-checkbox>
                        </el-checkbox-group>
                    </el-form-item>
                </el-form>
<!--                <div style="margin:12px 24px">-->
<!--                    <span> {{ item.title }} ： </span>-->
<!--                    <div style="display: inline-block">-->
<!--                        <el-checkbox-group v-model="ids" @change="change">-->
<!--                            <el-checkbox v-if="item.children" v-for="(itemChild,k) in item.children" :key="k" :label="itemChild.id">{{ itemChild.title }}</el-checkbox>-->
<!--                        </el-checkbox-group>-->
<!--                    </div>-->
<!--                </div>-->
            </el-col>
        </el-row>
        <div align="center" style="margin-top: 12px"><el-button type="primary" :loading="loading" @click="handleSubmit">提交</el-button></div>
    </div>
</template>
<script>
import { setRules } from '@/api/groups'
export default {
    name:'auth-rule',
    props:['rules'],
    data () {
        return {
            formData:null,
            ids: [],
            loading:false,
        }
    },
    methods:{
        handleSubmit(){
            this.loading = true;
            setRules({rules:this.ids.join(',')},this.formData.id).then(res => {
                if (res.data.code == 1){
                    this.success(res.data.msg);
                    this.$emit('handleGetTableData');
                }else {
                    this.error(res.data.msg);
                }
                setTimeout(()=>{
                    this.loading = false;
                },2000);
            });
        },
        currentData(currentData){
            this.$nextTick(() => {
                if(currentData){
                    this.ids = currentData.rules.split(',');
                    this.ids = this.ids.map(item => Number(item));
                    this.formData = currentData;
                }
            })
        },
        change(ids){
            this.ids = ids;
        }
    }
}
</script>
<style lang="css" scoped>

</style>