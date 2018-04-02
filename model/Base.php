<?php
/**
 *  基础转换类
 */
class Base
{
    /**
     * User: 邢可盟
     *
     * @var \Medoo\Medoo
     */
    public $old_db;
    /**
     * User: 邢可盟
     *
     * @var \Medoo\Medoo
     */
    public $new_db;

    function __construct($old_db, $new_db)
    {
        $this->old_db = $old_db;
        $this->new_db = $new_db;
    }

    /**
     * 数据转换传输
     *
     * @author 杨霄
     * @date   2018-03-05T20:48:45+0800
     * @return [type]                   [description]
     */
    public function transfer()
    {
        //获取一条旧的数据
        //格式转换
        //写入新的数据
    }
}
