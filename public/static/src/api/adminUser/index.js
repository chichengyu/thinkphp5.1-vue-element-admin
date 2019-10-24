import axios from '@/api'

/******************* 列表 *******************/
export const getTableData = (page,limit,keywords) => {
	return axios.request({
		url:'/administrators/lst?page=' + page + '&limit=' + limit + keywords,
		method:'get',
	});
}


/******************* 添加 *******************/
export const add = (data) => {
	return axios.request({
		url:'/administrators/add',
		method:'post',
		data:data
	});
}

/******************* 编辑 *******************/
export const edit = (data,id) => {
	return axios.request({
		url:'/administrators/edit/'+id,
		method:'post',
		data:data
	});
}

/******************* 重置密码 *******************/
export const password = (id) => {
	return axios.request({
		url:'/administrators/password/'+id,
		method:'get',
	});
}

/******************* 删除 *******************/
export const del = (id) => {
	return axios.request({
		url:'/administrators/del/'+id,
		method:'get',
	});
}