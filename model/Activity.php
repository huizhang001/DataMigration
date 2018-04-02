<?php
require_once 'Base.php';
/**
 *  活动转换类
 */
class Activity extends Base
{
    private $table_old = 'activity';
    private $table_new = 'activity';

    /**
     * 数据转换传输
     *
     * @author 杨霄
     * @date   2018-03-05T20:48:45+0800
     * @return [type]                   [description]
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
    //活动类型。
    //'0'默认活动(无指定活动),'1'微信满赠活动，
    //'2'微信满减活动,'3'微信满折活动,
    //'4'自建满减活动,'5'自建折扣活动,
    //'6'导入活动,'7'随机立减，
    //'8'自建满赠活动,'9'线下满赠活动，
    //'10'线下满减活动,'11'线下满折活动,
    //12开卡即送活动 13 消费赠送
    // 14储值活动 15微信商品券活动
    // 活动类型
    // 0默认活动(无指定活动)  （赠送）10满赠券活动
    // 11储值活动 12关注赠送活动 13开卡即送活动
    // 14核销赠送 15每消费满送积分
    // 20微信满减券活动 21微信满折券活动
    // 22微信商品券活动 23自建满减活动 24自建折扣活动
    private function convertType($activity_type)
    {
        if ($activity_type == 1) {
            return 10;
        }
        if ($activity_type == 2) {
            return 20;
        }
        if ($activity_type == 3) {
            return 21;
        }
        if ($activity_type == 4) {
            return 23;
        }
        if ($activity_type == 5) {
            return 24;
        }
        if ($activity_type == 12) {
            return 13;
        }
        if ($activity_type == 14) {
            return 11;
        }
        if ($activity_type == 15) {
            return 22;
        }
    }

    private function getAllNewData($data)
    {
        $newData = array();
        foreach ($data as $key => $v) {
            $temp = [
                'activity_name' => $v['activity_name'],
                'activity_theme' => $v['activity_theme'],
                'activity_type' => $this->convertType($v['activity_type']),
                'activity_status' => $v['status'],
                'start_time' => $v['start_time'],
                'end_time' => $v['end_time'],
                'activity_apply_money' => $v['start_fee'],
                'activity_priority' => $v['activity_priority'],
                'create_time' => $v['create_time'],
                'update_time' => $v['update_time'],
                'activity_card_id' => $v['wechat_card_id'],
            ];
            foreach ($temp as $k => $nv) {
                if ($nv == null) {
                    $temp [$k] = '';
                }
            }
            $newData[] = $temp;
        }
        //print_r($newData);
        return $newData;
    }

    private function insterNew($data)
    {
        $this->new_db->delete($this->table_new, "*");
        $res = $this->new_db->truncate($this->table_new);
        print_r($res);
        if (count($data) == 0) {
            return;
        }
        $result = $this->new_db->insert($this->table_new, $data);
        echo $result->errorCode();
    }
}
