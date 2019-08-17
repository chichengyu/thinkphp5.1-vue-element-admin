import axios from './index.js'

/******************* 列表 *******************/
export const getTableData = (page,limit,keywords='') => {
	return axios.request({
		url:'/order/lst?page='+page+'&limit='+limit+keywords,
		method:'get',
	});
};

/******************* 删除 *******************/
export const del = (id) => {
	return axios.request({
		url:'/order/del/'+id,
		method:'get',
	});
};

/******************* 下单 *******************/
export const downOrder = (data) => {
	return axios.request({
		url:'/order',
		method:'post',
		data:data
	});
};

/******************* 支付 *******************/
export const pay = (idUrl) => {
	return axios.request({
		url:'/pay/' + idUrl,
		method:'get',
	});
};

/******************* 支付成功跳转 *******************/
export const paysuccess = (data) => {
	return axios.request({
		url:'/query',
		method:'post',
		data:data
	});
};

/******************* 银联支付 *******************/
export const unionpay = (data) => {
	return axios.request({
		url:'/unionPay',
		method:'post',
		data:data
	});
};

/******************* 退款申请 *******************/
export const returnOrder = (data) => {
	return axios.request({
		url:'/unionPay',
		method:'post',
		data:data
	});
};