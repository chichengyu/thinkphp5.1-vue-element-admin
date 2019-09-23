import directives from './permission'


export default {
    install(Vue, options) {
        Vue.directive('has',directives);
    }
}