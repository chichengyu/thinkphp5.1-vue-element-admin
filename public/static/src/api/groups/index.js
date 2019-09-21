import axios from '@/api'

/******************* 列表 *******************/
export const getTableData = (page,limit) => {
	return axios.request({
		url:'/groups/lst?page='+page+'&limit='+limit,
		method:'get',
	});
}


/******************* 添加 *******************/
export const add = (data) => {
	return axios.request({
		url:'/groups/add',
		method:'post',
		data:data
	});
}

/******************* 编辑 *******************/
export const edit = (data,id) => {
	return axios.request({
		url:'/groups/edit/'+id,
		method:'post',
		data:data
	});
}

/******************* 删除 *******************/
export const del = (id) => {
	return axios.request({
		url:'/groups/del/'+id,
		method:'get',
	});
}


/******************* 分配权限 *******************/
export const setRules = (data,id) => {
	return axios.request({
		url:'/groups/auth/'+id,
		method:'post',
		data:data
	});
}