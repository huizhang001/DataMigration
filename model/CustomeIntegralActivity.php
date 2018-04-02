<?php
require_once 'Base.php';
/**
 *  消费送积分活动转换类
 */
class CustomeIntegralActivity extends Base
{
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
            //格式转换
            $newData = $this->getAllNewData();
            //写入新的数据
            $this->insterNew($newData);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function getAllNewData()
    {
        $newData = array();
        $temp = [
            'activity_name' => '每支付消费送积分活动',
            'activity_theme' => '消费送积分',
            'activity_type' => 15,
            'activity_status' => 1,
            'start_time' => time(),
            'end_time' => 4294967295,
            'activity_priority' => 9,
            'create_time' => time(),
            'update_time' => time(),];
        $newData[] = $temp;
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
