<?php
//echo 111;
function import($table, $bd)
{
    $user = "root";
    $pass = "123456";
    $db = new PDO('mysql:host=localhost;dbname='.$bd, $user, $pass, array(
          PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ));
    $mongo = new MongoClient("mongodb://localhost/");
    $mdb = $mongo->yp;
    $collection = $mdb->$table;
    $query = $db->query("SELECT * FROM `".$table."`");
    $result = $query->fetchAll(\PDO::FETCH_ASSOC);
    $start = microtime(true);
    foreach ($result as $key => $val) {
        $obj = "";
        $start1 = microtime(true);
        foreach ($val as $key1 => $val1) {
            $obj[$key1] = $val1;
        }
        $collection->insert($obj);
        $time1 = microtime(true) - $start1;
        echo "***".$time1."***\n";
    }
    $time = microtime(true) - $start;
    echo "%%%%".$time."%%%%";
}

function rerol() 
{
    //$m = new MongoClient();
    $mongo = new MongoClient();
    $mdb = $mongo->selectDB('yp');
    $collection = new MongoCollection($mdb, "object");
    $collection0 = new MongoCollection($mdb, "users");
    $collection1 = new MongoCollection($mdb, "ppab_view");
    $collection2 = new MongoCollection($mdb, "rubric");
    $content = $collection->find();
    foreach ($content as $val) {
        //echo $val['user_id']."___".$val['id']."\n";
        if ($user = $collection0->findOne(array('id' => $val['user_id']))) {
            if ($collection->update(array('_id' => $val['_id']), array('$set' => array('user_id' => $user['_id'])))) {
            }
        }
        else {
            $collection->remove(array('_id' => $val['_id']), array('address_id' => true));
        }
        
        if ($userAd = $collection1->findOne(array('id' => $val['address_id']))) {
            if ($collection->update(array('_id' => $val['_id']), array('$set' => array('address_id' => $userAd['_id'])))) {
            }
        }
        else {
            $collection->remove(array('_id' => $val['_id']), array('address_id' => true));
        }
        echo $val['_id']."\n";
        var_dump($val['rubrics']);
        if (array_key_exists('rubrics', $val)&&strlen($val['rubrics'])>0) {
            $rubric = explode(";",$val['rubrics']);
            $arrRub = array();
            foreach ($rubric as $num) {
                if (!empty(trim($num))) {
                    if ($userAd = $collection2->findOne(array('id' => $num))) {
                        $arrRub[] = $userAd['_id'];
                    }
                }
            }
            if (count($arrRub)>0) {
                $collection->update(array('_id' => $val['_id']), array('$set' => array('rubrics' => $arrRub)));
            }
            else {
                $collection->remove(array('_id' => $val['_id']), array('rubrics' => true));
            }
            unset($arrRub);
        }
    }
}

import("object", "yp");
//import("rubric", "yp");
//import("ppab_view", "postgis");
rerol();