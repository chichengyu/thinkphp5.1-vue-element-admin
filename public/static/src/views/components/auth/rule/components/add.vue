<template>
    <div class="add">
        <component-form :data="form" :width="80"></component-form>
    </div>
</template>
<script>
import { add } from '@/api/rule'

export default {
    props:['groups'],
    data () {
        return {
            form:{
                labelWidth:'200px',
                formFields:{
                    title: '',
                    name:'',
                    pid:[]
                },
                formLabel:[
                    {prop: 'title', title: '名称', type: 'input',disabled:false,placeholder:'请输入名称'},
                    {prop: 'name', title: '规则', type: 'input',disabled:false,placeholder:'请输入规则'},
                    {prop: 'pid', title: '上级', type: 'select',style:{width:'100%'},key:'title',value:'id',options:this.groups},
                ],
                buttons:{
                    align:'center',
                    options:[
                        {title:'提交',type:'primary',loading:false,click:(form,item) => {
                            form.validate(valid => {
                                if (valid){
                                    item.loading = true;
                                    add(this.form.formFields).then(res => {
                                        if (res.data.code == 1){
                                            this.success(res.data.msg);
                                            this.$emit('handleGetTableData');
                                        }else{
                                            this.error(res.data.msg);
                                        }
                                        setTimeout(()=>{
                                            item.loading = false;
                                        },2000);
                                    })
                                }
                            });
                        }},
                        {title:'重置',click:(form) => form.resetFields()},
                    ]
                },
                rules: {
                    title: [
                        { required: true, message: '请输入名称', trigger: 'blur' },
                        { min: 1, max: 10, message: '长度在 1 到 10 个字符', trigger: 'blur' }
                    ],
                }
            },
        }
    },
}
</script>
<style lang="css" scoped>

</style>