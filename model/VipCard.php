<?php
require_once 'Base.php';
/**
 *  会员卡表转换类
 */
class VipCard extends Base
{
    private $table = 'vip_card';

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
        //获取一条旧的数据
        $allOld = $this->getAllOld();
        // print_r($allOld);
        //格式转换
        $newData = $this->getAllNewData($allOld);
        //写入新的数据
        $this->insterNew($newData);
    }

    private function getAllOld()
    {
        return $this->old_db->select($this->table, "*");
    }

    private function getAllNewData($data)
    {
        $newData = array();
        foreach ($data as $key => $v) {
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
        $this->new_db->delete($this->table, "*");
        $this->new_db->truncate($this->table);
        if (count($data) == 0) {
            return;
        }
        $result = $this->new_db->insert($this->table, $data);
        echo $result->errorCode();
    }
}
