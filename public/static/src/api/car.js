import axios from './index.js'

/******************* 列表 *******************/
export const getTableData = (page,limit,keywords) => {
	return axios.request({
		url:'/car/lst?page='+page+'&limit='+limit+keywords,
		method:'get',
	});
};


/******************* 添加 *******************/
export const add = (data) => {
	return axios.request({
		url:'/car/add',
		method:'post',
		data:data
	});
};

/******************* 编辑 *******************/
export const excel = (params) => {
	return axios.request({
		url:'/car/excel'+params,
		method:'get',
		// data:{}
	});
};

/******************* 编辑 *******************/
export const edit = (data,id) => {
	return axios.request({
		url:'/car/edit/'+id,
		method:'post',
		data:data
	});
};
/******************* 更新推荐 *******************/
export const editRec = (data,id) => {
	return axios.request({
		url:'/car/update_rec/'+id,
		method:'post',
		data:data
	});
};

/******************* 删除 *******************/
export const del = (id) => {
	return axios.request({
		url:'/car/del/'+id,
		method:'get',
	});
};

/******************* 添加属性 *******************/
export const addAttr = (data,id) => {
	return axios.request({
		url:'/car/attr_add/'+id,
		method:'post',
		data:data
	});
};

/************************ 车辆库存 ************************/
/******************* 列表 *******************/
export const getCarStockData = (carId,page,limit,keywords) => {
	return axios.request({
		url:'/car_number/lst/'+carId+'?page='+page+'&limit='+limit+keywords,
		method:'get',
	});
};


/******************* 添加 *******************/
export const getCarStockAdd = (data,carId) => {
	return axios.request({
		url:'/car_number/add/'+carId,
		method:'post',
		data:data
	});
};

/******************* 编辑 *******************/
export const getCarStockEdit = (data,id) => {
	return axios.request({
		url:'/car_number/edit/'+id,
		method:'post',
		data:data
	});
};

/******************* 删除 *******************/
export const getCarStockDel = (id) => {
	return axios.request({
		url:'/car_number/del/'+id,
		method:'get',
	});
};