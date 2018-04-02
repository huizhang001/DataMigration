<?php
/**
 * Created by PhpStorm.
 * User: 邢可盟
 * Date: 2018/3/5
 * Time: 14:49
 */
namespace dbcopy;

use Medoo\Medoo;

class CopyDataHandle
{
    /**
     * 处理数据库信息
     *
     * @param Medoo  $oldDb         老数据库对象
     * @param Medoo  $newDb         新数据库对象
     * @param string $table_name    当前表名
     * @param array  $updateColumes 更新改变的表信息
     * @param array  $addColumes    添加列信息
     * @param int    $subNum        总数量
     */
    public static function HandleDb(Medoo $oldDb, Medoo $newDb, $table_name, array $updateColumes, array $addColumes, $subNum)
    {
        switch ($table_name) {
            case 'activity':
                self::HandleActivity($oldDb, $newDb, $table_name, $updateColumes, $addColumes, $subNum);
                break;
            case 'balance_record':
                self::HandleBalanceRecord($oldDb, $newDb, $table_name, $updateColumes, $addColumes, $subNum);
                break;
            case 'balance_refund_record':
                self::HandleBalanceRefund($oldDb, $newDb, $table_name, $updateColumes, $addColumes, $subNum);
                break;
            case 'follow_rule':
                self::HandleFollowRule($oldDb, $newDb, $table_name, $updateColumes, $addColumes, $subNum);
                break;
            case 'orders':
                self::HandleOrders($oldDb, $newDb, $table_name, $updateColumes, $addColumes, $subNum);
                break;
            case 'store':
                self::HandleStore($oldDb, $newDb, $table_name, $updateColumes, $addColumes, $subNum);
                break;
            case 'vip':
                self::HandleVip($oldDb, $newDb, $table_name, $updateColumes, $addColumes, $subNum);
                break;
            case 'vip_card':
                self::HandleVipCard($oldDb, $newDb, $table_name, $updateColumes, $addColumes, $subNum);
                break;
            case 'vip_vip_card':
                self::HandleVipVipCard($oldDb, $newDb, $table_name, $updateColumes, $addColumes, $subNum);
                break;
            default:
                echo '<h6>' . '(' . $subNum . ')' . "表名：" . $table_name . "  表状态：未处理状态    执行状态：FAIL";
        }
    }

    public static function HandleActivity(Medoo $oldDb, Medoo $newDb, $table_name, array $updateColumes, array $addColumes, $subNum)
    {
        $dbResult = $oldDb->select($table_name, '*');
        $row = 0;
        $status = $newDb->delete($table_name, []);
        if (!$status) {
            echo '表名：' . $table_name . '删除数据失败！！';
            echo '</br>';
        }
        foreach ($dbResult as $id => $data) {
            $status = $newDb->insert($table_name, $data);
            if (!$status) {
                echo '表名：' . $table_name . '插入数据失败！！';
                echo '</br>';
            }
            $row++;
        }
        echo '<h6>' . '(' . $subNum . ')' . "表名：" . $table_name . "  表状态：修改插入状态    响应行号：" . $row .
            "   总行号：" . count($dbResult) . "  执行状态：" . ($row == count($dbResult) ? "SUCCESS" : "FAIL") . '</h6>';
    }

    public static function HandleBalanceRecord(Medoo $oldDb, Medoo $newDb, $table_name, array $updateColumes, array $addColumes, $subNum)
    {
        $dbResult = $oldDb->select($table_name, '*');
        $row = 0;
        $status = $newDb->delete($table_name, []);
        if (!$status) {
            echo '表名：' . $table_name . '删除数据失败！！';
            echo '</br>';
        }
        foreach ($dbResult as $id => $data) {
            $status = $newDb->insert($table_name, $data);
            if (!$status) {
                echo '表名：' . $table_name . '插入数据失败！！';
                echo '</br>';
            }
            $row++;
        }
        echo '<h6>' . '(' . $subNum . ')' . "表名：" . $table_name . "  表状态：修改插入状态    响应行号：" . $row .
            "   总行号：" . count($dbResult) . "  执行状态：" . ($row == count($dbResult) ? "SUCCESS" : "FAIL") . '</h6>';
    }

    public static function HandleBalanceRefund(Medoo $oldDb, Medoo $newDb, $table_name, array $updateColumes, array $addColumes, $subNum)
    {
        $dbResult = $oldDb->select($table_name, '*');
        $row = 0;
        $status = $newDb->delete($table_name, []);
        if (!$status) {
            echo '表名：' . $table_name . '删除数据失败！！';
            echo '</br>';
        }
        foreach ($dbResult as $id => $data) {
            $status = $newDb->insert($table_name, $data);
            if (!$status) {
                echo '表名：' . $table_name . '插入数据失败！！';
                echo '</br>';
            }
            $row++;
        }
        echo '<h6>' . '(' . $subNum . ')' . "表名：" . $table_name . "  表状态：修改插入状态    响应行号：" . $row .
            "   总行号：" . count($dbResult) . "  执行状态：" . ($row == count($dbResult) ? "SUCCESS" : "FAIL") . '</h6>';
    }

    public static function HandleFollowRule(Medoo $oldDb, Medoo $newDb, $table_name, array $updateColumes, array $addColumes, $subNum)
    {
        $dbResult = $oldDb->select($table_name, '*');
        $row = 0;
        $status = $newDb->delete($table_name, []);
        if (!$status) {
            echo '表名：' . $table_name . '删除数据失败！！';
            echo '</br>';
        }
        foreach ($dbResult as $id => $data) {
            $status = $newDb->insert($table_name, $data);
            if (!$status) {
                echo '表名：' . $table_name . '插入数据失败！！';
                echo '</br>';
            }
            $row++;
        }
        echo '<h6>' . '(' . $subNum . ')' . "表名：" . $table_name . "  表状态：修改插入状态    响应行号：" . $row .
            "   总行号：" . count($dbResult) . "  执行状态：" . ($row == count($dbResult) ? "SUCCESS" : "FAIL") . '</h6>';
    }

    public static function HandleOrders(Medoo $oldDb, Medoo $newDb, $table_name, array $updateColumes, array $addColumes, $subNum)
    {
        $dbResult = $oldDb->select($table_name, '*');
        $row = 0;
        $status = $newDb->delete($table_name, []);
        if (!$status) {
            echo '表名：' . $table_name . '删除数据失败！！';
            echo '</br>';
        }
        foreach ($dbResult as $id => $data) {
            $status = $newDb->insert($table_name, $data);
            if (!$status) {
                echo '表名：' . $table_name . '插入数据失败！！';
                echo '</br>';
            }
            $row++;
        }
        echo '<h6>' . '(' . $subNum . ')' . "表名：" . $table_name . "  表状态：修改插入状态    响应行号：" . $row .
            "   总行号：" . count($dbResult) . "  执行状态：" . ($row == count($dbResult) ? "SUCCESS" : "FAIL") . '</h6>';
    }

    public static function HandleStore(Medoo $oldDb, Medoo $newDb, $table_name, array $updateColumes, array $addColumes, $subNum)
    {
        $dbResult = $oldDb->select($table_name, '*');
        $row = 0;
        $status = $newDb->delete($table_name, []);
        if (!$status) {
            echo '表名：' . $table_name . '删除数据失败！！';
            echo '</br>';
        }
        foreach ($dbResult as $id => $data) {
            $status = $newDb->insert($table_name, $data);
            if (!$status) {
                echo '表名：' . $table_name . '插入数据失败！！';
                echo '</br>';
            }
            $row++;
        }
        echo '<h6>' . '(' . $subNum . ')' . "表名：" . $table_name . "  表状态：修改插入状态    响应行号：" . $row .
            "   总行号：" . count($dbResult) . "  执行状态：" . ($row == count($dbResult) ? "SUCCESS" : "FAIL") . '</h6>';
    }

    public static function HandleVip(Medoo $oldDb, Medoo $newDb, $table_name, array $updateColumes, array $addColumes, $subNum)
    {
        $dbResult = $oldDb->select($table_name, '*');
        $row = 0;
        $status = $newDb->delete($table_name, []);
        if (!$status) {
            echo '表名：' . $table_name . '删除数据失败！！';
            echo '</br>';
        }
        foreach ($dbResult as $id => $data) {
            $status = $newDb->insert($table_name, $data);
            if (!$status) {
                echo '表名：' . $table_name . '插入数据失败！！';
                echo '</br>';
            }
            $row++;
        }
        echo '<h6>' . '(' . $subNum . ')' . "表名：" . $table_name . "  表状态：修改插入状态    响应行号：" . $row .
            "   总行号：" . count($dbResult) . "  执行状态：" . ($row == count($dbResult) ? "SUCCESS" : "FAIL") . '</h6>';
    }

    public static function HandleVipCard(Medoo $oldDb, Medoo $newDb, $table_name, array $updateColumes, array $addColumes, $subNum)
    {
        $dbResult = $oldDb->select($table_name, '*');
        $row = 0;
        $status = $newDb->delete($table_name, []);
        if (!$status) {
            echo '表名：' . $table_name . '删除数据失败！！';
            echo '</br>';
        }
        foreach ($dbResult as $id => $data) {
            $status = $newDb->insert($table_name, $data);
            if (!$status) {
                echo '表名：' . $table_name . '插入数据失败！！';
                echo '</br>';
            }
            $row++;
        }
        echo '<h6>' . '(' . $subNum . ')' . "表名：" . $table_name . "  表状态：修改插入状态    响应行号：" . $row .
            "   总行号：" . count($dbResult) . "  执行状态：" . ($row == count($dbResult) ? "SUCCESS" : "FAIL") . '</h6>';
    }

    public static function HandleVipVipCard(Medoo $oldDb, Medoo $newDb, $table_name, array $updateColumes, array $addColumes, $subNum)
    {
        $dbResult = $oldDb->select($table_name, '*');
        $row = 0;
        $status = $newDb->delete($table_name, []);
        if (!$status) {
            echo '表名：' . $table_name . '删除数据失败！！';
            echo '</br>';
        }
        foreach ($dbResult as $id => $data) {
            $status = $newDb->insert($table_name, $data);
            if (!$status) {
                echo '表名：' . $table_name . '插入数据失败！！';
                echo '</br>';
            }
            $row++;
        }
        echo '<h6>' . '(' . $subNum . ')' . "表名：" . $table_name . "  表状态：修改插入状态    响应行号：" . $row .
            "   总行号：" . count($dbResult) . "  执行状态：" . ($row == count($dbResult) ? "SUCCESS" : "FAIL") . '</h6>';
    }
}
