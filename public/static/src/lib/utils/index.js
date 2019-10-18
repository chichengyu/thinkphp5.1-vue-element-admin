import axios from '@/api'
import table from '@/components/table'
import form from '@/components/form'
import dialog from '@/components/dialog'
import popover from '@/components/popover'
import preview from '@/components/preview'
import upload from '@/components/upload'
import fileUpload from '@/components/fileUpload'
import wangEditor from '@/components/wangEditor'
import validate from './validate.js'
import { message, warning, success, error, alert, prompt, confirm} from './message.js'

export default {
    install(Vue, options) {
        Vue.prototype.axios = axios;
        Vue.prototype.validator = validate;
        Vue.prototype.message = message;
        Vue.prototype.warning = warning;
        Vue.prototype.success = success;
        Vue.prototype.error = error;
        Vue.prototype.alert = alert;
        Vue.prototype.prompt = prompt;
        Vue.prototype.confirm = confirm;

        // 注册全局组件
        Vue.component('ComponentTable',table);
        Vue.component('ComponentDialog',dialog);
        Vue.component('ComponentForm',form);
        Vue.component('ComponentPopover',popover);
        Vue.component('ComponentPreview',preview);
        Vue.component('ComponentUpload',upload);
        Vue.component('ComponentFileUpload',fileUpload);
        Vue.component('ComponentWangEditor',wangEditor);
    },
}