import axios from '@/api'

/******************* 列表 *******************/
export const getTableData = () => {
	return axios.request({
		url:'/rule/lst',
		method:'get',
	});
}


/******************* 添加 *******************/
export const add = (data) => {
	return axios.request({
		url:'/rule/add',
		method:'post',
		data:data
	});
}

/******************* 编辑 *******************/
export const edit = (data,id) => {
	return axios.request({
		url:'/rule/edit/'+id,
		method:'post',
		data:data
	});
}

/******************* 删除 *******************/
export const del = (id) => {
	return axios.request({
		url:'/rule/del/'+id,
		method:'get',
	});
}