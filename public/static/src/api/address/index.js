import axios from '@/api'

/******************* 编辑 *******************/
export const getAddress = (id) => {
	return axios.request({
		url:'/address',
		method:'get',
	});
};
