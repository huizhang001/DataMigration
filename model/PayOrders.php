<?php
require_once 'Base.php';
class PayOrders extends Base
{
    private $table_old = 'orders';
    private $table_new = 'pay_orders';

    /**
     * Description:
     * User: 郭玉朝
     * CreateTime: 2018/4/2 下午5:26
     */
    public function transfer()
    {
        echo "<pre>";
        try {
            //获取一条旧的数据
            $allOld = $this->getAllOld();
            //格式转换
            $newData = $this->getAllNewData($allOld);
            //写入新的数据
            $this->insterNew($newData);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function getAllOld()
    {
        return $this->old_db->select($this->table_old, '*');
    }

    private function getAllNewData($data)
    {
        $newData = [];
        foreach ($data as $key => $v) {
            $oneNewData = [];
            $oneNewData = [
                'pay_order_id'          => $v['order_id'],
                'zk_openid'             => $v['openid'],
                'wechat_openid'         => $v['sub_openid'],
                'wechat_sub_mch_id'     => $v['sub_mch_id'],
                'out_trade_no'          => $v['out_trade_no'],
                'wechat_transaction_id' => $v['transaction_id'],
                'wechat_total_fee'      => $v['actual_fee'],
                'finished_time'         => $v['complete_time'],
                'create_time'           => $v['create_time'],
                'update_time'           => $v['update_time'],
                'order_fee'             => $v['original_fee'],
                'preferential_fee'      => $v['activity_fee'],
                'actual_fee'            => $v['actual_fee'] + $v['balance_fee'],
                'user_pay_fee'          => $v['actual_fee'],
                'rechage_pay_fee'       => $v['balance_fee'],
                'refund_fee'            => $v['wechat_refund_fee'] + $v['balance_refund_fee'],
                'rechage_refund_fee'    => $v['balance_refund_fee'],
                'wechat_refund_fee'     => $v['wechat_refund_fee'],
                'trade_state'           => array_search($v['status'],[
                    2   => 1,
                    0   => 0,
                    00  => -1,
                    6  => -2,
                    7  => -3,
                    4  => -4,
                    3  => -5,
                    8  => -6,
                    1  => -7,
                    5  => -8
                ]), // 交易类型可能会更改
                'trade_type'            => 1,
                'store_id'              => 1, // 门店信息需要修改
                'store_name'            => '匠人牛品北京合生汇店',
            ];
            array_push($newData, $oneNewData);
        }
        return $newData;
    }

    private function insterNew($data)
    {
        $this->new_db->delete($this->table_new, "*");
        $this->new_db->truncate($this->table_new);
        if (count($data) == 0) {
            return;
        }
        $result = $this->new_db->insert($this->table_new, $data);
        echo $result->errorCode();
    }
}
