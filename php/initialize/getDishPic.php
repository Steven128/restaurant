<?php
$url = "http://www.5ikfc.com/kfc/menu/Meal";

$HTTP_REQUEST_HEADER = array(
    "Host: www.5ikfc.com",
    "Proxy-Connection: keep-alive",
    "Pragma: no-cache",
    "Cache-Control: max-age=3600",
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8",
    "Upgrade-Insecure-Requests: 1",
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.81 Safari/537.36",
    "Content-Type: text/html; charset=utf-8",
    "Accept-Encoding: gzip, deflate",
    "Accept-Language: zh-CN,zh;q=0.9",
    "Referer: http://www.5ikfc.com/kfc/menu/",
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $HTTP_REQUEST_HEADER);
curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$rs = curl_exec($ch);
$rs = mb_convert_encoding($rs, 'utf-8', 'GBK,UTF-8,ASCII');
// var_dump($rs);
preg_match_all("/\"http:\/\/cache.5ikfc.com\/imgs\/kfc\/menu\/(.*?)\" alt=\"(.*?)\"/", $rs, $matches);
$dish = array();
foreach ($matches[0] as $value) {
    preg_match("/\"http:\/\/cache.5ikfc.com\/imgs\/kfc\/menu\/(.*?)\"/", $value, $picurl);
    preg_match("/alt=\"(.*?)\"/", $value, $picname);
    $picurl = str_replace("\"", "", $picurl[0]);
    $picname = str_replace("alt=\"KFC菜单图片:","",$picname[0]);
    $picname = str_replace("\"","",$picname);
    $savename = mt_rand(10000,99999).mt_rand(10000,99999).".jpg";
    $dl = getImage($picurl,'dish_pic/',$savename);
    if($dl['error'] == 0) {
        $savename = "../../src/dish_pic/".$savename;
        $dish_single = array("dish_pic"=> $savename,"dish_name"=>$picname,"dish_type"=>2,"dish_price"=>"");
        array_push($dish,$dish_single);
    }
}
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$dish_json = json_encode($dish);
echo $dish_json;

function getImage($url, $save_dir = '', $filename = '', $type = 1)
{
    if (trim($url) == '') {
        return array('file_name' => '', 'save_path' => '', 'error' => 1);
    }
    if (trim($save_dir) == '') {
        $save_dir = './';
    }
    if (trim($filename) == '') { //保存文件名
        $ext = strrchr($url, '.');
        if ($ext != '.gif' && $ext != '.jpg') {
            return array('file_name' => '', 'save_path' => '', 'error' => 3);
        }
        $filename = time() . $ext;
    }
    if (0 !== strrpos($save_dir, '/')) {
        $save_dir .= '/';
    }
    //创建保存目录
    if (!file_exists($save_dir) && !mkdir($save_dir, 0777, true)) {
        return array('file_name' => '', 'save_path' => '', 'error' => 5);
    }
    //获取远程文件所采用的方法
    if ($type) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $img = curl_exec($ch);
        curl_close($ch);
    } else {
        ob_start();
        readfile($url);
        $img = ob_get_contents();
        ob_end_clean();
    }
    //$size=strlen($img);
    //文件大小
    $fp2 = @fopen($save_dir . $filename, 'a');
    fwrite($fp2, $img);
    fclose($fp2);
    unset($img, $url);
    return array('file_name' => $filename, 'save_path' => $save_dir . $filename, 'error' => 0);
}
