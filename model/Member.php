<?php
require_once 'Base.php';
/**
 *  会员转换类
 */
class Member extends Base
{
    private $table_old = 'vip_vip_card';
    private $table_new = 'member';

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

    private function getAllNewData($data)
    {
        $newData = array();
        foreach ($data as $key => $v) {
            $id = $v['vip_vip_card_id'];
            unset($v['vip_vip_card_id']);
            $v['member_id'] = $id;
            //
            $status = $v['status'];
            unset($v['status']);
            $v['member_status'] = $status;
            //
            $level = $v['level'];
            unset($v['level']);
            $v['member_level'] = $level;
            //
            $point = $v['bonus'];
            unset($v['bonus']);
            $v['member_point'] = $point;
            //
            $lockpoint = $v['lock_bonus'];
            unset($v['lock_bonus']);
            $v['member_lock_point'] = $lockpoint;
            //
            $balance = $v['balance'];
            unset($v['balance']);
            $v['member_balance'] = $balance;
            //
            $lockbalance = $v['lock_balance'];
            unset($v['lock_balance']);
            $v['member_lock_balance'] = $lockbalance;
            //
            $name = $v['name'];
            unset($v['name']);
            $v['member_name'] = $name;
            //
            $phone = $v['phone'];
            unset($v['phone']);
            $v['member_telphone'] = $phone;
            //
            $sex = $v['sex'];
            unset($v['sex']);
            $v['member_sex'] = $sex;
            //
            $birthday = $v['birthday'];
            unset($v['birthday']);
            $v['member_birthday'] = $birthday;
            //
            $code = $v['code'];
            unset($v['code']);
            $v['wechat_member_code'] = $code;
            //
            $sub_openid = $v['sub_openid'];
            unset($v['sub_openid']);
            $v['wechat_openid'] = $sub_openid;
            //
            //获取vipcard
            $v['wechat_member_card_id'] = 'p4Edct_4a1BIRh3nMtt7EB5r8xBQ';
            //
            $vip = $this->old_db->select('vip', "*", ['vip_id' => $v['vip_id']]);
            $v['zk_openid'] = $vip[0]['open_id'];
            $v['wechat_subscribe'] = $vip[0]['subscribe_status'];
            //
            unset($v['discount']);
            unset($v['cuisine_lock']);
            unset($v['total_amount']);
            unset($v['level_total_amount']);
            unset($v['lock_total_amount']);
            unset($v['vip_id']);
            unset($v['vip_card_id']);
            unset($v['age']);
            if ($v['create_time'] == null) {
                $v['create_time'] = 0;
            }
            if ($v['update_time'] == null) {
                $v['update_time'] = 0;
            }
            if ($v['delete_time'] == null) {
                $v['delete_time'] = 0;
            }
            foreach ($v as $k => $nv) {
                if ($nv == null) {
                    $v[$k] = '';
                }
            }
            $newData[] = $v;
        }
        //print_r($newData);
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
