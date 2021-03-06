<?php
/**
 * 一个简单的导出excel数据的方法，不需要其他的类库
 */
//定义头部，表示输出excel，再以table的形式把数据库的信息循环的echo出来
header("Content-type:application/vnd.ms-excel");
header("Content-Disposition:filename=".$filename.".xls");

$cfg_dbhost = '192.168.1.202';
$cfg_dbname = 'youde';
$cfg_dbuser = 'root';
$cfg_dbpwd = 'YouDe202!';
$cfg_db_language = 'utf8';
// END 配置

//链接数据库
$link = mysql_connect($cfg_dbhost,$cfg_dbuser,$cfg_dbpwd);
mysql_select_db($cfg_dbname);
//选择编码
mysql_query("set names ".$cfg_db_language);

//users表
$sql = "desc ecs_users";

$res = mysql_query($sql);

echo "<table><tr>";
//导出表头（也就是表中拥有的字段）
while($row = mysql_fetch_array($res)){
    $t_field[] = $row['Field'];	//Field中的F要大写，否则没有结果
    echo "<th>".$row['Field']."</th>";
}
echo "</tr>";
//导出100条数据
$sql = "select * from ecs_users limit 100";
$res = mysql_query($sql);
while($row = mysql_fetch_array($res)){
    echo "<tr>";
    foreach($t_field as $f_key){
        echo "<td>".$row[$f_key]."</td>";
    }
    echo "</tr>";
}
echo "</table>";

/*********************************第二种********************************************/
//导出excel
function exportexcel($data=array(),$title=array(),$filename='report'){
    header("Content-type:aplication/octet-stream");
    header("Accept-Ranges:bytes");
    header("Content-type:application/vnd.ms-excel");
    header("Content-Disposition:attachment;filename=".$filename.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    if (!empty($title)){
        foreach($title as $k=>$v){
            $title[$k]=iconv("UTF-8","GB2312",$v);
        }
        $title=implode("\t",$title);//  \t是水平方向跳到下一制表符位置
        echo "$title\n";//  \n是回车并换行
    }

    if (!empty($data)){
        foreach($data as $key=>$val){
            foreach($val as $ck=>$cv){
                $data[$key][$ck]=iconv("UTF-8","GB2312",$cv);
            }
            $data[$key]=implode("\t",$data[$key]);
        }
        echo implode("\n",$data);
    }
}
