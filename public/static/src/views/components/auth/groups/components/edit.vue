<template>
    <div class="add">
        <component-form :data="form" :width="80"></component-form>
    </div>
</template>
<script>
import { edit } from '@/api/groups'

export default {
    data () {
        return {
            formData:null,
            form:{
                formLabelWidth:'200px',
                formFields:{
                    title: '',
                    status:1,
                },
                formLabel:[
                    {prop: 'title', title: '名称', type: 'input',disabled:false,placeholder:'请输入姓名'}
                ],
                buttons:{
                    align:'center',
                    options:[
                        {title:'提交',type:'primary',loading:false,click:(form,item) => {
                            form.validate(valid => {
                                if (valid){
                                    item.loading = true;
                                    edit(this.form.formFields,this.form.formFields.id).then(res => {
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
                            })
                        }},
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
    methods:{
        currentData(currentData){
            this.$nextTick(() => {
                currentData&&(this.form.formFields = currentData);
            })
        }
    }
}
</script>
<style lang="css" scoped>

</style>