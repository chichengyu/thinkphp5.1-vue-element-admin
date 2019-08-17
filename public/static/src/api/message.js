import axios from './index.js'

/******************* 列表 *******************/
export const getTableData = (page,limit,keywords='') => {
	return axios.request({
		url:'/message/lst?page='+page+'&limit='+limit+keywords,
		method:'get',
	});
};


/******************* 添加 *******************/
export const add = (data) => {
	return axios.request({
		url:'/message/add',
		method:'post',
		data:data
	});
};

/******************* 编辑 *******************/
export const edit = (data,id) => {
	return axios.request({
		url:'/message/edit/'+id,
		method:'post',
		data:data
	});
};

/******************* 删除 *******************/
export const del = (id) => {
	return axios.request({
		url:'/message/del/'+id,
		method:'get',
	});
};