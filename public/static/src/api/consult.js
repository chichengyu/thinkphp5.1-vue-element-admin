import axios from './index.js'


/******************* 列表 *******************/
export const getTableData = (page,limit,keywords) => {
	return axios.request({
		url:'/user_reserve/lst?page='+page+'&limit='+limit+keywords,
		method:'get',
	});
}

/******************* 选择车辆 *******************/
export const getByBrandOrTypeCar = (params) => {
	return axios.request({
		url:'/user_reserve/car' + params,
		method:'get',
	});
}

/******************* 转发 *******************/
export const relay = (data,id) => {
	return axios.request({
		url:'/user_reserve/relay/'+id,
		method:'post',
		data:data
	});
}

/******************* 添加 *******************/
export const add = (data) => {
	return axios.request({
		url:'/user_reserve/add',
		method:'post',
		data:data
	});
}

/******************* 编辑 *******************/
export const edit = (data,id) => {
	return axios.request({
		url:'/user_reserve/edit/'+id,
		method:'post',
		data:data
	});
}

/******************* 删除 *******************/
export const del = (id) => {
	return axios.request({
		url:'/user_reserve/del/'+id,
		method:'get',
	});
}