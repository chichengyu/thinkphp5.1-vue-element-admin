import regexp from './regexp.js'

export default Object.assign({
    //判断字符是否为空的方法
    isEmpty:function(obj){
        if(typeof obj == "undefined" || obj == null || obj == ""){
            return true;
        }else{
            return false;
        }
    },
    // 用户名验证
    isUsername:function(val){
        if (val && regexp.usenameRegExp.test(val)) {
            return true;
        }
        return false;
    },
    // 密码验证
    isPassword:function(val){
        if (val && regexp.passwordRegExp.test(val)) {
            return true;
        }
        return false;
    },
    // 邮箱验证
    isEmail:function(val){
        if (val && regexp.emailRegExp.test(val)) {
            return true;
        }
        return false;
    },
    // 手机验证
    isPhone:function(val){
        if (val && regexp.phoneRegExp.test(val)) {
            return true;
        }
        return false;
    },
    // 手机验证
    isTel:function(val){
        if (val && regexp.telRegExp.test(val)) {
            return true;
        }
        return false;
    },
    // 身份证验证
    isIdcard:function(val){
        if (val && regexp.idCardRegExp.test(val)) {
            return true;
        }
        return false;
    },
    // ip验证
    isIp:function(val){
        if (val && regexp.ipRegExp.test(val)) {
            return true;
        }
        return false;
    },
    // 中文验证
    isChinese:function(val){
        if (val && regexp.chineseRegExp.test(val)) {
            return true;
        }
        return false;
    },
    // 日期验证
    isDate:function(val){
        if (val && regexp.dateRegExp.test(val)) {
            return true;
        }
        return false;
    },
    // url验证
    isUrl:function(val){
        if (val && regexp.dateRegExp.test(val)) {
            return true;
        }
        return false;
    },
    // 非零正整数验证
    isInteger:function(val){
        if (val && regexp.integer.test(val)) {
            return true;
        }
        return false;
    },
},regexp);
