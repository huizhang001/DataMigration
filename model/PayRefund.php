<?php
require_once 'Base.php';
class PayRefund extends Base
{
    private $table_old = 'orders_refund';
    private $table_new = 'pay_refund';
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
            $orders = $this->old_db->get('orders', "*", ['out_trade_no' => $v['out_trade_no']]);
            $oneNewData = [
                'pay_refund_id'         => $v['order_refund_id'],
                'out_trade_no'          => $v['out_trade_no'],
                'third_order_no'        => $orders['transaction_id'],
                'out_refund_no'         => $v['out_refund_no'],
                'third_refund_no'       => $v['transaction_id'],
                'refund_fee'            => $v['wechat_refund_fee'] + $v['balance_refund_fee'],
                'refund_status'         => array_search($v['status'],[
                                                0   => 0,
                                                1   => 1,
                                                2   => -1,
                                            ]),
                'create_time'           => $v['create_time'],
                'finished_time'         => $v['complete_time'],
                'update_time'           => $v['update_time']];
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
