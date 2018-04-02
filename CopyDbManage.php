<?php
namespace dbcopy;

/**
 * Created by PhpStorm.
 * User: 邢可盟
 * Date: 2018/3/5
 * Time: 9:42
 */
require 'lib\Medoo.php';
require 'CopyDataHandle.php';

use Medoo\Medoo;

define('IS_ANALYSIS', true);//是否分析模式  分析模式不操作数据库写入
//---------------------------------------------HEAD---------------------------------------------
header("Content-Type:text/html;charset=UTF-8");
$config = ['database_type' => 'mysql', 'database_name' => '', 'server' => '42.96.193.57', 'username' => 'root',
    'password' => 'xinliu2016*', 'logging' => true, 'command' => ['SET SQL_MODE=ANSI_QUOTES'], 'charset' => 'utf8',];
$mainConfig = array_merge($config, ['database_name' => 'information_schema']);
$oldConfig = array_merge($config, ['database_name' => 'jrnp_zs_0329']);
$newConfig = array_merge($config, ['database_name' => 'jrnp_new']);
echo "<html><header>
<style type=\"text/css\">
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>
</header><body><h1>数据库迁移信息</h1>";
//---------------------------------------------FUNCTION---------------------------------------------
/**
 * 获取表名
 *
 * @param array $res
 *
 * @return array
 */
function getDataNames(array $res)
{
    $tables = array();
    foreach ($res as $keys => $values) {
        foreach ($values as $k => $v) {
            array_push($tables, $v);
        }
    }
    asort($tables);
    return $tables;
}

/**
 * 是否存在key
 *
 * @param array  $datas 数据 ['v','v2']
 * @param string $name  名称
 *
 * @return bool
 */
function IsHasKey(array $datas, $name)
{
    $status = false;
    foreach ($datas as $k => $v) {
        if ($v == $name) {
            $status = true;
            break;
        }
    }
    return $status;
}

/**
 * 是否存在key
 *
 * @param array  $datas 更新表数据
 * @param string $name  表名
 *
 * @return bool|array
 */
function IsHasTable(array $datas, $name)
{
    $status = false;
    foreach ($datas as $key => $value) {
        foreach ($value as $k => $v) {
            if ($v['TABLE_NAME'] == $name) {
                return $v;
            }
        }
    }
    return $status;
}

/**
 * 打印表格
 *
 * @param array $datas   打印数据 [[k=>v]]
 * @param bool  $oneHead 是否打印一个头信息 默认true
 */
function printTable(array $datas, $oneHead = true)
{
    if (count($datas) == 0) {
        return;
    }
    echo "<table class=\"gridtable\">";
    //打印内容
    foreach ($datas as $id => $data) {
        $names = $values = "";
        foreach ($data as $k => $v) {
            if ($oneHead && $id == 0) {
                $names .= '<th>' . $k . '</th>';
            }
            $values .= '<td>' . $v . '</td>';
        }
        if ($oneHead && $id == 0) {
            echo '<tr><th>row</th>' . $names . '</tr>';
        }
        echo '<tr><td>' . ($id + 1) . '</td>' . $values . '</tr>';
    }
    echo "</table></br>";
}

/**
 * 比对数据库表信息
 *
 * @param array $oldTable 老数据库表
 * @param array $newTable 新数据库表
 *
 * @return array   ['add'=>[],'del'=>[]]
 */
function compareTableInfo(array $oldTable, array $newTable)
{
    $delTables = array();
    //判断删除的表
    foreach ($oldTable as $value) {
        $status = false;
        foreach ($newTable as $value2) {
            if ($value == $value2) {
                $status = true;
                break;
            }
        }
        if (!$status) {
            array_push($delTables, $value);
        }
    }
    $addTables = array();
    //判断新增的表
    foreach ($newTable as $value) {
        $status = false;
        foreach ($oldTable as $value2) {
            if ($value == $value2) {
                $status = true;
                break;
            }
        }
        if (!$status) {
            array_push($addTables, $value);
        }
    }
    return ['new' => $addTables, 'del' => $delTables];
}

/**
 * 比较列信息
 *
 * @param array $oldColumns 列对象1 旧
 * @param array $newColumns 列对象2 新
 *
 * @return array
 */
function compareColumnInfo(array $oldColumns, array $newColumns)
{
    $delColumns = array();
    $addColumns = array();
    $changeColumns = array();
    //判断删除字段
    foreach ($oldColumns as $id => $value) {
        $status = false;
        foreach ($newColumns as $id2 => $value2) {
            if ($value['COLUMN_NAME'] == $value2['COLUMN_NAME']) {
                $status = true;
                $changeStatus = 0;
                if ($value['COLUMN_DEFAULT'] !== $value2['COLUMN_DEFAULT']) {
                    $changeStatus++;
                }
                if (trim($value['COLUMN_COMMENT']) !== trim($value2['COLUMN_COMMENT'])) {
                    $changeStatus += 2;
                }
                if ($changeStatus !== 0) {
                    $temp = $value;
                    $temp['COLUMN_DEFAULT_NEW'] = $value2['COLUMN_DEFAULT'];
                    $temp['COLUMN_COMMENT_NEW'] = $value2['COLUMN_COMMENT'];
                    if ($changeStatus === 1) {
                        $temp['COLUMN_UPDATE_TYPE'] = '默认值修改';
                    } else {
                        if ($changeStatus === 3) {
                            $temp['COLUMN_UPDATE_TYPE'] = "默认值+注释修改";
                        } else {
                            $temp['COLUMN_UPDATE_TYPE'] = "注释修改";
                        }
                    }
                    array_push($changeColumns, $temp);
                }
                break;
            }
        }
        if (!$status) {
            array_push($delColumns, $value);
        }
    }
    //判断新增字段
    foreach ($newColumns as $id => $value) {
        $status = false;
        foreach ($oldColumns as $id2 => $value2) {
            if ($value['COLUMN_NAME'] == $value2['COLUMN_NAME']) {
                $status = true;
                break;
            }
        }
        if (!$status) {
            $temp2 = $value;
            $temp2['COLUMN_DEFAULT_NEW'] = $value['COLUMN_DEFAULT'];
            $temp2['COLUMN_COMMENT_NEW'] = $value['COLUMN_COMMENT'];
            $temp2['COLUMN_DEFAULT'] = '';
            $temp2['COLUMN_COMMENT'] = '';
            array_push($addColumns, $temp2);
            $temp3 = $temp2;
            $temp3['COLUMN_UPDATE_TYPE'] = "新增字段";
            array_push($changeColumns, $temp3);
        }
    }
    return ['add' => $addColumns, 'del' => $delColumns, 'update' => $changeColumns];
}

//---------------------------------------------BODY---------------------------------------------
try {
    //获取表名信息
    $mysql = @new Medoo($mainConfig);
    $res = $mysql->select('tables', ['TABLE_NAME'], ['TABLE_SCHEMA' => $oldConfig['database_name']]);
    $oldTable = getDataNames($res);
    $res2 = $mysql->select('tables', ['TABLE_NAME'], ['TABLE_SCHEMA' => $newConfig['database_name']]);
    $newTable = getDataNames($res2);
    //检查表信息
    echo '<h2>1.数据库信息</h2>';
    $datas = [['数据库名称' => $oldConfig['database_name'], '表数量' => count($oldTable), '数据库表' => @implode(",", $oldTable)],
        ['数据库名称' => $newConfig['database_name'], '表数量' => count($newTable), '数据库表' => @implode(",", $newTable)],];
    printTable($datas);
    echo '<h2>2.数据库比对信息</h2>';
    $tabeOverNum = count($newTable) - count($oldTable);
    $compareTableRes = compareTableInfo($oldTable, $newTable);
    $datas = [[
        '数据库比对规则' => $newConfig['database_name'] . '(新)/' . $oldConfig['database_name'] . '(旧)',
        '表数量变化' => $tabeOverNum,
        '删除表' => @implode(",", $compareTableRes['del']),
        '新增表' => @implode(",", $compareTableRes['new']),
    ],
    ];
    printTable($datas);
    echo '<h2>3.数据库表字段比对信息</h2>';
    $delColumnsInfos = array();
    $addColumnsInfos = array();
    $updateColumnsInfos = array();
    foreach ($oldTable as $table_name) {
        $columns1 = $mysql->select('columns', ['TABLE_SCHEMA', 'TABLE_NAME', 'COLUMN_NAME', 'COLUMN_DEFAULT',
            'COLUMN_TYPE', 'COLUMN_COMMENT'], ['TABLE_NAME' => $table_name,
            'TABLE_SCHEMA' => $oldConfig['database_name']]);
        $columns2 = $mysql->select('columns', ['TABLE_SCHEMA', 'TABLE_NAME', 'COLUMN_NAME', 'COLUMN_DEFAULT',
            'COLUMN_TYPE', 'COLUMN_COMMENT'], ['TABLE_NAME' => $table_name,
            'TABLE_SCHEMA' => $newConfig['database_name']]);
        $columnsInfo = compareColumnInfo($columns1, $columns2);
        array_push($delColumnsInfos, $columnsInfo['del']);
        array_push($addColumnsInfos, $columnsInfo['add']);
        array_push($updateColumnsInfos, $columnsInfo['update']);
    }
    echo '<h4>1）删除字段字段信息</h4>';
    foreach ($delColumnsInfos as $v) {
        printTable($v);
    }
    echo '<h4>2）新增字段字段信息</h4>';
    foreach ($addColumnsInfos as $v) {
        printTable($v);
    }
    echo '<h4>3）修改字段字段信息</h4>';
    foreach ($updateColumnsInfos as $v) {
        printTable($v);
    }
    //-------------------------------------执行操作数据库--------------------------------------
    if (!IS_ANALYSIS) {
        echo '<h2>4.执行插入数据库表信息</h2>';
        $oldDb = new Medoo($oldConfig);
        $newDb = new Medoo($newConfig);
        $subNum = 0;
        foreach ($oldTable as $table_name) {
            $subNum++;
            if (IsHasKey($compareTableRes['del'], $table_name)) {
                echo '表名：' . $table_name . '   表状态：删除状态   执行结果：SUCCESS';
                echo '</br>';
                continue;
            }
            $updateStatus = IsHasTable($updateColumnsInfos, $table_name);
            $addStatus = IsHasTable($addColumnsInfos, $table_name);
            if ($updateStatus !== false || $addStatus !== false) {
                $updateStatus = $updateStatus === false ? [] : $updateStatus;
                $addStatus = $addStatus === false ? [] : $addStatus;
                CopyDataHandle::HandleDb($oldDb, $newDb, $table_name, $updateStatus, $addStatus, $subNum);
            } else {
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
                echo '(' . $subNum . ')' . "表名：" . $table_name . "  表状态：拷贝状态    响应行号：" . $row . "   总行号：" . count($dbResult) . "  执行状态：" . ($row == count($dbResult) ? "SUCCESS" : "FAIL");
                echo '</br>';
            }
        }
    }
    ///-----------------------------末尾-------------------------------
    echo '</br>';
    echo '</br>';
    echo '</br>';
    echo '</br>';
    echo '</br>';
    echo '</br>';
    echo '<h2>数据库表字段信息</h2>';
    $oldColumnInfos = array();
    $newColumnInfos = array();
    foreach ($oldTable as $table_name) {
        $columns1 = $mysql->select('columns', ['TABLE_SCHEMA', 'TABLE_NAME', 'COLUMN_NAME', 'COLUMN_DEFAULT',
            'COLUMN_TYPE', 'COLUMN_COMMENT'], ['TABLE_NAME' => $table_name,
            'TABLE_SCHEMA' => $oldConfig['database_name']]);
        array_push($oldColumnInfos, $columns1);
    }
    foreach ($newTable as $table_name) {
        $columns2 = $mysql->select('columns', ['TABLE_SCHEMA', 'TABLE_NAME', 'COLUMN_NAME', 'COLUMN_DEFAULT',
            'COLUMN_TYPE', 'COLUMN_COMMENT'], ['TABLE_NAME' => $table_name,
            'TABLE_SCHEMA' => $newConfig['database_name']]);
        array_push($newColumnInfos, $columns2);
    }
    echo '<h4>1）旧表字段信息</h4>';
    foreach ($oldColumnInfos as $v) {
        printTable($v);
    }
    echo '<h4>2）新表字段信息</h4>';
    foreach ($newColumnInfos as $v) {
        printTable($v);
    }
    echo '<h4>3）新增表字段信息</h4>';
    foreach ($newColumnInfos as $v) {
        $status = false;
        foreach ($v as $ks => $vs) {
            foreach ($compareTableRes['new'] as $nk => $nv) {
                if ($vs['TABLE_NAME'] == $nv) {
                    $status = true;
                    break;
                }
            }
            if ($status) {
                break;
            }
        }
        $status && printTable($v);
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}
//---------------------------------------------FOOT---------------------------------------------
echo "</body></html>";