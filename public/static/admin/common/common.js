/************************* 公共函数文件(当前文件不会被打包) *************************/
/**
 * 页面跳转
 * @param data 请求返回的数据对象
 * @param This vue实例
 */
const jumpUrl = (data,This) => {
    if (data.code == 401){
        This.$router.push('/401');
    }else {
        This.$store.dispatch('clearLoginOut');
        This.$router.push('/login');
    }
    This.error(data.msg);
};

/**
 * 搜索关键词（默认值）及数据拼接
 * @param data  待搜索的数据对象
 * @returns {string}
 */
const handleSearchData = (data) => {
    var searchWords = '';
    if (data){
        for (let key in data){
            if (data[key] != ''){
                searchWords += '&' + key + '=' + data[key];
            }
        }
    }
    return searchWords;
};