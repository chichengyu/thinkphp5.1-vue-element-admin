import axios from './index.js'

/******************* 账号列表 *******************/
export const getTableData = (page,limit,keywords) => {
	return axios.request({
		url:'/number_user/lst?page='+page+'&limit='+limit+keywords,
		method:'get',
	});
};


/******************* 账号添加 *******************/
export const add = (data) => {
	return axios.request({
		url:'/number_user/add',
		method:'post',
		data:data
	});
};

/******************* 账号编辑 *******************/
export const edit = (data,id) => {
	return axios.request({
		url:'/number_user/edit/'+id,
		method:'post',
		data:data
	});
};

/******************* 账号删除 *******************/
export const del = (id) => {
	return axios.request({
		url:'/number_user/del/'+id,
		method:'get',
	});
};

/******************* 个人资料 *******************/
export const myUser = () => {
	return axios.request({
		url:'/my/user',
		method:'get',
	});
};
export const myEdit = (data) => {
	return axios.request({
		url:'/my/edit',
		method:'post',
		data:data
	});
};