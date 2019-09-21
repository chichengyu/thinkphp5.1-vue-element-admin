import axios from '@/api'

/******************* 登陆 *******************/
export const login = (data) => {
	return axios.request({
		url:'/login',
		method:'post',
		data:data
	});
}

/******************* 退出登陆 *******************/
export const logout = () => {
	return axios.request({
		url:'/logout',
		method:'get',
	});
}