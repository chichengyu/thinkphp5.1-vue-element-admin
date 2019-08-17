import axios from './index.js'

/******************* 列表 *******************/
export const getTableData = (page,limit,keywords) => {
	return axios.request({
		url:'/store/lst?page='+page+'&limit='+limit+keywords,
		method:'get',
	});
}


/******************* 添加 *******************/
export const add = (data) => {
	return axios.request({
		url:'/store/add',
		method:'post',
		data:data
	});
}

/******************* 编辑 *******************/
export const edit = (data,id) => {
	return axios.request({
		url:'/store/edit/'+id,
		method:'post',
		data:data
	});
};

/******************* 删除 *******************/
export const del = (id) => {
	return axios.request({
		url:'/store/del/'+id,
		method:'get',
	});
};

// export const uploadImageUrl = 'http://192.168.1.10:8000/store/images';	// 门店海报/logo上传
// export const uploadLicenseUrl = 'http://192.168.1.10:8000/store/license';// 门店营业执照上传

/************************************** 门店审核 **************************************/
/******************* 审核列表 *******************/
export const auditorList = (page,limit,keywords) => {
	return axios.request({
		url:'/store/auditor/lst?page='+page+'&limit='+limit+keywords,
		method:'get',
	});
};


/******************* 审核 *******************/
export const auditor = (data,id) => {
	return axios.request({
		url:'/store/auditor/'+id,
		method:'post',
		data:data
	});
};

/************************************** 审核记录 **************************************/
export const recordList = (page,limit,keywords) => {
	return axios.request({
		url:'/store/record/lst?page='+page+'&limit='+limit+keywords,
		method:'get',
	});
};