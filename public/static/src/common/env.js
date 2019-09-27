/************************* 变量文件(当前文件不会被打包) *************************/
const userInfo = 'userInfo';// 存个人信息的键名
const timeout = 1000;// 页面跳转时间
const pageOffset = 10;// 每页显示的条数

// 系统名称
const project = '棕牛管理系统';

const ip = 'http://zn.cn';
// const ip = 'http://192.168.5.100:8001';


const baseUrl = ip + '/api/v1';
/********************* 图片管理(上传目录可以随便定义) ***********************/
// 注意：删除图片，只需要改地址即可
const delUploadImageUrl = ip + '/upload/del';// 删除已上传图片地址
const upload = ip + '/upload/';

const uploadMessageUrl = upload + '456';// 图片上传地址,456 是目录，可自定义
const uploadExcelUrl = upload + 'excel';// excel
