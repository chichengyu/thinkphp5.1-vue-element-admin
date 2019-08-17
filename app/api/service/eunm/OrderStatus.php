<?php
namespace app\api\service\eunm;


class OrderStatus{
    const UNPAID = 0;// 未付款
    const PAID = 1;// 已付款
    const DELIVERED  = 2;// 已发货
    const SIGINED = 3;// 已签收
    const REFUND = -1;// 退款申请
    const REFUNDING = -2;// 退款中
    const RETIRED = -3;// 已退货
    const CANCEL = -4;// 取消交易
}