import axios from './index.js'

/******************* 车型列表 *******************/
export const getTableData = (page,limit) => {
	return axios.request({
		url:'/type/lst?page='+page+'&limit='+limit,
		method:'get',
	});
}


/******************* 车型添加 *******************/
export const add = (data) => {
	return axios.request({
		url:'/type/add',
		method:'post',
		data:data
	});
}

/******************* 车型编辑 *******************/
export const edit = (data,id) => {
	return axios.request({
		url:'/type/edit/'+id,
		method:'post',
		data:data
	});
}

/******************* 车型删除 *******************/
export const del = (id) => {
	return axios.request({
		url:'/type/del/'+id,
		method:'get',
	});
}