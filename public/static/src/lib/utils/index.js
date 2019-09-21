import axios from '../../api'
import validate from './validate.js'
import { message, warning, success, error} from './message.js'

export default {
    install(Vue, options) {
        Vue.prototype.axios = axios;
        Vue.prototype.$validator = validate;
        Vue.prototype.message = message;
        Vue.prototype.warning = warning;
        Vue.prototype.success = success;
        Vue.prototype.error = error;
    }
}