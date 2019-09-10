<template>
    <div class="personal" style="font-size: 12px">
        <span style="margin-right:26px;">你好！欢迎你
            <b style="color: red">{{ this.$store.getters.userInfo['name'] }}</b>
        </span>
        <el-dropdown trigger="hover" style="cursor: pointer">
            <span class="el-dropdown-link">
                <img src="@/assets/images/touxiang.jpg" width="32" style="vertical-align: middle;border-radius: 8px">
                <i class="el-icon-caret-bottom el-icon--right" style="vertical-align: middle"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item>个人资料</el-dropdown-item>
                <el-dropdown-item>密码修改</el-dropdown-item>
                <el-dropdown-item @click.native="() => logout()">退出登录</el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
    </div>
</template>

<script>
import { edit } from '@/api/personal'
import { logout } from '@/api/login'

export default {
    name: "personal",
    data () {
        return {

        }
    },
    methods:{
        logout () {
            // logout().then(res => {
            //     if (res.data.code == 1){
            //         this.success(res.data.msg);
                    this.success('退出登陆');
                    this.$ls.clear();
                    this.$store.dispatch('clearLoginOut');
                    this.$router.push('/login');
                    setTimeout(() => {
                        window.location.reload();
                    },600);
                // }
            // })
        }
    }
}
</script>

<style lang="css" scoped>

</style>