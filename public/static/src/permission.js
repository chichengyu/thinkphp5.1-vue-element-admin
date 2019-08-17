import directives from './directtives'


export default {
    install(Vue, options) {
        Vue.directive('has',directives);
    }
}