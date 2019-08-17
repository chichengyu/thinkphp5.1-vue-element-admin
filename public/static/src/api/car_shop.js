import axios from './index.js'

/******************* 列表 *******************/
export const getTableData = () => {
	return axios.request({
		url:'/shopcar/lst',
		method:'get',
	});
};


/******************* 添加 *******************/
export const add = (data) => {
	return axios.request({
		url:'/shopcar/add',
		method:'post',
		data:data
	});
};


/******************* 删除 *******************/
export const del = (ids) => {
	return axios.request({
		url:'/shopcar/del?ids='+ids,
		method:'get',
	});
};
