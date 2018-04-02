<?php
require_once 'Base.php';
/**
 *  储值活动转换类
 */
class RechargeActivity extends Base
{
    private $table_old = 'balance_rule';
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

    private function getAllNewData($data)
    {
        $newData = array();
        foreach ($data as $key => $v) {
            $temp = [
                'activity_name' => sprintf('储值满%s元送%s元活动', $v['balance_fee'] * 1.0 / 100, $v['send_fee'] * 1.0 / 100),
                'activity_theme' => '储值活动',
                'activity_type' => 11,
                'activity_status' => $v['state'],
                'start_time' => time() - 100,
                'end_time' => 4294967295,
                'activity_apply_money' => $v['balance_fee'],
                'activity_send_fee' => $v['send_fee'],
                'create_time' => $v['create_time'],
                'update_time' => $v['update_time'],
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
        //$this->new_db->delete($this->table_new, "*");
        if (count($data) == 0) {
            return;
        }
        $result = $this->new_db->insert($this->table_new, $data);
        echo $result->errorCode();
    }
}
