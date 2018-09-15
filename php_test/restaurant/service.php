<?php
    session_start(); //开启php_session
    $admin_id = $_GET['admin_id']; //获取admin_id
    if (isset($_SESSION['admin_id']) && $_SESSION['admin_id'] == $admin_id) { //如果已设置session且session对应用户为当前访问用户
        $request = $_GET['request']; //获取请求内容
        
        $conn = oci_connect('scott', '123456', 'localhost:1521/ORCL', "AL32UTF8"); //连接oracle数据库
        if (!$conn) { //未连接成功，终止脚本并返回错误信息
            $e = oci_error();
            die(json_encode($e));
        } else { //连接成功
            if ($request == "addOrder") { //请求
                echo addOrder($conn);
            }else if($request=="deleteOrder"){
                echo deleteOrder($conn);
            }else if($request=="pay"){
                echo pay($conn);
            }else if($request=="changeOeder"){
                echo changeOrder($conn);
            }
        }
    }


    function replace_specialChar($strParam){
        $regex = "/\/|\～|\，|\。|\！|\？|\“|\”|\【|\】|\『|\』|\：|\；|\《|\》|\’|\‘|\ |\·|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        return preg_replace($regex,"",$strParam);
    }


    function addOreder($conn){
        
    }
    function deleteOrder($conn){

    }
    function pay($conn){

    }
    function changeOrder($conn){

    }