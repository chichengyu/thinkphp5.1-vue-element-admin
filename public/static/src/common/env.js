/************************* 变量文件(当前文件不会被打包) *************************/
const userInfo = 'userInfo';// 存个人信息的键名
const timeout = 1000;// 页面跳转时间
const pageOffset = 10;// 每页显示的条数


const ip = 'http://el.cn';
// const ip = 'http://192.168.5.100:8001';

export const baseUrl = ip + '/api/v1';


/********************* 图片管理 ***********************/
const delUploadImageUrl = 'http://source.cybcar.cn/uploads/del';// 删除已上传图片地址
const uploadMessageUrl = 'http://source.cybcar.cn/upload/message';// 资讯上传
const uploadOwnerIdCardUrl = 'http://source.cybcar.cn/upload/owner';// 车主身份证上传
