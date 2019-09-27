import regexp from './regexp.js'
let validate = {
    isEmpty:function(obj){
        if(typeof obj == "undefined" || obj == null || obj == ""){
            return true;
        }else{
            return false;
        }
    }
};
for (let key in regexp){
    validate['is'+key.replace(key[0],key[0].toUpperCase())] = function(val){
        if (val && regexp[key].test(val)) {
            return true;
        }
        return false;
    }
}
export default Object.assign(regexp,validate);
