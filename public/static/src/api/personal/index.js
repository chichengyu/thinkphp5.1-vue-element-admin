import axios from '@/api'

/******************* 个人资料 *******************/
export const getPersonal = () => {
	return axios.request({
		url:'/administrators/personal',
		method:'get',
	});
};

/******************* 密码修改 *******************/
export const editPassword = (data) => {
	return axios.request({
		url:'/administrators/personal/edit',
		method:'post',
		data:data
	});
};