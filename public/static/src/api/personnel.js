import axios from './index.js'

/******************* 账号列表 *******************/
export const getTableData = (page,limit,keywords) => {
	return axios.request({
		url:'/personnel/lst?page='+page+'&limit='+limit+keywords,
		method:'get',
	});
};


/******************* 账号添加 *******************/
export const add = (data) => {
	return axios.request({
		url:'/personnel/add',
		method:'post',
		data:data
	});
};

/******************* 账号编辑 *******************/
export const edit = (data,id) => {
	return axios.request({
		url:'/personnel/edit/'+id,
		method:'post',
		data:data
	});
};

/******************* 账号删除 *******************/
export const del = (id) => {
	return axios.request({
		url:'/personnel/del/'+id,
		method:'get',
	});
};
