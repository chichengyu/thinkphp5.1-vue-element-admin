import ElementUI from "element-ui";
export const message = function (msg) {
    ElementUI.Message({
        message: msg,
        center: true
    });
};
export const warning = function (msg) {
    ElementUI.Message({
        message: msg,
        type: 'warning',
        center: true
    });
};
export const success = function (msg) {
    ElementUI.Message({
        message: msg,
        type: 'success',
        center: true
    });
};
export const error = function (msg) {
    ElementUI.Message({
        message: msg,
        type: 'error',
        center: true
    });
};