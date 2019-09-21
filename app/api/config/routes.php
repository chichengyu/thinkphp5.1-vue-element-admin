<?php

return [
    'action' => [
        'api/index/delupload',// 删除上传图片
        'api/index/upload',// 广告图片上传
        'api/message/upload',// 资讯上传
        'api/carbrand/upload',// 品牌logo上传
        'api/store/upload',// 门店海报、logo上传
        'api/store/license',// 门店营业执照上传
        'api/cardetails/upload',// 车辆图片上传
        'api/storepersonnel/getuserinfo',// 获取个人资料
        'api/storepersonnel/myedit',// 修改个人资料
        'api/address/getaddress',// 获取城市下拉数据
        'api/cardetails/updaterec',// 获取城市下拉数据

        'api/address/getaddress',// 获取地址

        'api/userreserve/getbybrandortypecar',// 选择车辆

        'api/storeowner/add',// 添加车主
        'api/storeowner/updaloadidcard', //车主身份证上传

    ],
    // 支付回调
    'pay' => [
        'api/pay/receivenotifywechat', // 微信支付回调

        'api/pay/receivenotifyali', // 支付宝回调

        'api/pay/queryorderjump',// 轮询

        'api/pay/receivenotifyacp', // 银联支付后台回调
        'api/pay/receivenotifyacptest', // 银联支付前台回调
    ]
];