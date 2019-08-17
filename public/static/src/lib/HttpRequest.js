import axios from 'axios'
import config from '@/config'
import store from '@/store/index.js'

class HttpRequest{
	constructor(baseUrl = config.baseUrl){
		this.baseUrl = baseUrl;
		this.queue = {};
	}
	getInsideConfig(){
		const config = {
			baseURL:this.baseUrl,
			headers:{}
		};
		return config;
	}
	interceptors(instance,url){
		instance.interceptors.request.use(config => {
			if (Object.keys(this.queue).length) {
				this.queue[url] = true;
			}
			return config;
		},error => {
			delete this.queue[url];
			return Promise.reject(error);
		});
		instance.interceptors.response.use(response => {
			delete this.queue[url];
			const { data, status } = response;
			return { data, status };
		},error => {
			delete this.queue[url];
			return Promise.reject(error);
		});
	}
	request(options){
		const configInit = this.getInsideConfig();
		const userInfo = store.getters.userInfo;
		if (userInfo && userInfo.token) {
			configInit.headers["token"] = userInfo.token;
		}
		const instance = axios.create();
		options = Object.assign(configInit,options);
		this.interceptors(instance,options.url);
		return instance(options);
	}
}

export default HttpRequest