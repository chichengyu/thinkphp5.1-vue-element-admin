<template>
	<div class="login">
		<div class="mask">
			<div class="login-container">
				<h2 class="title">棕牛管理系统</h2>
				<div class="login-form">
					<el-form ref="formInline" :label-position="'right'" :model="formInline">
						<el-form-item label="">
							<el-input v-model="formInline.username" prefix-icon="el-icon-user" placeholder="请输入账号"></el-input>
						</el-form-item>
						<el-form-item label="">
							<el-input v-model="formInline.password" :show-password="true" prefix-icon="el-icon-lock" @keyup.enter.native="handleSubmit('formInline')" placeholder="请输入密码"></el-input>
						</el-form-item>
						<el-form-item label="">
							<el-button type="primary" @click.native="handleSubmit('formInline')">登录</el-button>
						</el-form-item>
					</el-form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import md5 from 'md5'
import { login } from '@/api/login'

function hasErrors (fieldsError) {
    return Object.keys(fieldsError).some(field => fieldsError[field])
}
export default {
    name:'login',
    data () {
        return {
            formInline: {
                username: '',
                password: ''
            },
            ruleInline: {
                username: [
                    { required: true, message: '请输入账号', trigger: 'blur' }
                ],
                password: [
                    { required: true, message: '请输入密码', trigger: 'blur' },
                ]
            }
        }
    },
    methods: {
        handleSubmit(name) {
        	// 没有登陆接口时，直接放开登陆，默认以超级管理员登陆
			var userInfo = {
				name: "超级管理员",
				roles: 1,
				rules: [],
				token: "9ee7bSOKJchJxFqC5/gx/6JTXgOWpfQlpYvzyhjz3Ib1I3Mom9xs3GBjwuNtkqMV/sOskfJKI2ZvOwPmmI702IPl0paT"
			};
			
			this.$ls.set('userInfo',userInfo);
			this.$store.dispatch('setUserInfo',userInfo);
			this.success('登陆成功！');
			this.$router.push('/home');
			return;

			this.$progress.start();
            var username = this.formInline.username,
               	password = md5(this.formInline.password);
            if(!username){
        		this.error('请输入账号!');
				this.$progress.done();
            	return;
        	}
        	if (!password) {
        		this.error('请输入密码!');
				this.$progress.done();
            	return;
        	}
            this.$refs[name].validate((valid) => {
                if (valid) {
					login({username,password}).then(res => {
						res = res.data;
						if (res.code == 1) {
							this.$ls.set(userInfo,res.data);
							this.$store.dispatch('setUserInfo',res.data);
							this.success(res.msg);
							this.$router.push('/home');
		        		}else {
							this.error(res.msg);
						}
						this.$progress.done();
					});
                } else {
					this.$progress.done();
                }
            })
        }
    }
}
</script>
<style lang="stylus" scoped>
@import '~@/assets/style/varibles.styl'

.login{
	position: relative;
	width: 100%;
	height: 100%;
	overflow: hidden;
	text-align: center;
	background: url($bgImgLogin) no-repeat center/cover;
}
.mask{
	width: 100%;
	height: 100%;
	background:rgba(8,0,0,.2);
}
.login-container{
	width: 25%;
	min-width: 25%;
	/*height: 350px;*/
	position: absolute;
	top: 15%;
	left: 32%;
	padding-top: 2%;
	/*margin: 0 auto;*/
	background: rgba(255,255,255,.5);
	border-radius: 16px;
}
.login-container .title{
	margin-top: 0px;
	font-size: 26px;
	color:#eee;
}
.login-container .login-form{
	width: 60%;
	margin: 0 auto;
	padding-bottom: 10px;
}
</style>