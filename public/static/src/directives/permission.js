import store from '@/store'
import rules from '@/router/rules.js'


export default {
    inserted:(el,binding,vnode) => {
        let role = store.getters.userInfo.roles[0];
        if (role == 1){
            return ;
        }
        let routeName = vnode.context.$route.name;
        let btnName = binding.value;
        let logniRules = store.getters.userInfo.rules;
        let flag = rules.some(item => {
            if (item.name == routeName){
                let id = item.rulesBtn[btnName];
                if (id) return logniRules.some(item => item==id);
            }
            return false;
        });
        !flag && (el.parentNode.removeChild(el));
    }
}