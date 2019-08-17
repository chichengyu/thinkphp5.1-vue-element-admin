import axios from './index.js'


/******************* 编辑 *******************/
export const edit = (id,data) => {
	return axios.request({
		url:'/owner/edit/'+id,
		method:'post',
		data:data
	});
};
