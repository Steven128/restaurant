<?php
@header("content-type:text/html;charset=utf8");
// if (isset($_SERVER['HTTP_REFERER']))
//     $ref = $_SERVER['HTTP_REFERER'];
// else
//     $ref = "";
// if ($ref == "") {
//     echo "不允许从地址栏访问";
//     exit();
// } else {
//     $url = parse_url($ref);
//     if ($url['host'] != "127.0.0.1" && $url['host'] != "localhost" &&$url['host']!="47.95.212.18") {
//         echo "get out";
//         exit();
//     }
// }
class uploadPic
{
    private $src;
    private $image;
    private $imageinfo;
    private $percent = 0.5;
    /**
     * 图片压缩
     * @param $src 源图
     * @param float $percent  压缩比例
     */
    // public function __construct($src, $percent = 1)
    // {
    //     $this->src = $src;
    //     $this->percent = $percent;
    // }
    /** 高清压缩图片
     * @param string $saveName  提供图片名（可不带扩展名，用源图扩展名）用于保存。或不提供文件名直接显示
     */
    public function compressImg($saveName = '')
    {
        $this->_openImage();
        if (!empty($saveName)) {
            $this->_saveImage($saveName);
        }
        //保存
        else {
            $this->_showImage();
        }

    }
    /**
     * 内部：打开图片
     */
    private function _openImage()
    {
        list($width, $height, $type, $attr) = getimagesize($this->src);
        $this->imageinfo = array(
            'width' => $width,
            'height' => $height,
            'type' => image_type_to_extension($type, false),
            'attr' => $attr,
        );
        $fun = "imagecreatefrom" . $this->imageinfo['type'];
        $this->image = $fun($this->src);
        $this->_thumpImage();
    }
    /**
     * 内部：操作图片
     */
    private function _thumpImage()
    {
        $new_width = $this->imageinfo['width'] * $this->percent;
        $new_height = $this->imageinfo['height'] * $this->percent;
        $image_thump = imagecreatetruecolor($new_width, $new_height);
        //将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度
        imagecopyresampled($image_thump, $this->image, 0, 0, 0, 0, $new_width, $new_height, $this->imageinfo['width'], $this->imageinfo['height']);
        imagedestroy($this->image);
        $this->image = $image_thump;
    }
    /**
     * 输出图片:保存图片则用saveImage()
     */
    private function _showImage()
    {
        header('Content-Type: image/' . $this->imageinfo['type']);
        $funcs = "image" . $this->imageinfo['type'];
        $funcs($this->image);
    }
    /**
     * 保存图片到硬盘：
     * @param  string $dstImgName  1、可指定字符串不带后缀的名称，使用源图扩展名 。2、直接指定目标图片名带扩展名。
     */
    private function _saveImage($dstImgName)
    {
        if (empty($dstImgName)) {
            return false;
        }

        $allowImgs = ['.jpg', '.jpeg', '.png', '.bmp', '.wbmp', '.gif']; //如果目标图片名有后缀就用目标图片扩展名 后缀，如果没有，则用源图的扩展名
        $dstExt = strrchr($dstImgName, ".");
        $sourseExt = strrchr($this->src, ".");
        if (!empty($dstExt)) {
            $dstExt = strtolower($dstExt);
        }

        if (!empty($sourseExt)) {
            $sourseExt = strtolower($sourseExt);
        }

        //有指定目标名扩展名
        if (!empty($dstExt) && in_array($dstExt, $allowImgs)) {
            $dstName = $dstImgName;
        } elseif (!empty($sourseExt) && in_array($sourseExt, $allowImgs)) {
            $dstName = $dstImgName . $sourseExt;
        } else {
            $dstName = $dstImgName . $this->imageinfo['type'];
        }
        $funcs = "image" . $this->imageinfo['type'];
        $funcs($this->image, $dstName);
    }
    /**
     * 销毁图片
     */
    // public function __destruct()
    // {
    //     imagedestroy($this->image);
    // }
    public function upload($request,$upid)
    {
        if ($request == "upload_admin_pic") {
            $admin="adm_admin";
            $table="admin";
            $base64_image_content = $_POST['adminPicData'];
            if($upid!="" and isset($upid)){
                
                $admin_id = $upid;
            }
            elseif(isset($_POST['admin_id'])){
                $admin_id = $_POST['admin_id'];
            }
            $id=$admin_id;
            //匹配出图片的格式
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                $type = $result[2];
                $new_file = "../../src/admin_pic/";
                if (!file_exists($new_file)) {
                    //检查是否有该文件夹，如果没有就创建，并给予最高权限
                    mkdir($new_file, 0700);
                }
                $new_file = $new_file . $admin_id . ".{$type}";
                if (!file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                    echo json_encode(array("message" => "error"));
                }
            }
        } else if ($request == "upload_employee_pic") {
            $admin="emp_admin";
            $table="employee";
            $base64_image_content = $_POST['employeePicData'];
            if(isset($_POST['employee_id'])){

                $employee_id = $_POST['employee_id'];
            }
            else{
                $employee_id = $upid;
            }
            $id=$employee_id;
            //匹配出图片的格式
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                $type = $result[2];
                $new_file = "../../src/employee_pic/";
                if (!file_exists($new_file)) {
                    //检查是否有该文件夹，如果没有就创建，并给予最高权限
                    mkdir($new_file, 0700);
                }
                $new_file = $new_file . $employee_id . ".{$type}";
                if (!file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                    echo json_encode(array("message" => "error"));
                }
            }
        } else if ($request == "upload_dish_pic") {
            $admin="dis_admin";
            $table="dish";
            $base64_image_content = $_POST['dishPicData'];
            if(isset($_POST['dish_id'])&&$_POST['dish_id']!=""){
                $dish_id = $_POST['dish_id'];
            }
            else{
                $dish_id = $upid;
            }
            $id=$dish_id;
            //匹配出图片的格式
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                $type = $result[2];
                $new_file = "../../src/dish_pic/";
                if (!file_exists($new_file)) {
                    //检查是否有该文件夹，如果没有就创建，并给予最高权限
                    mkdir($new_file, 0700);
                }
                $new_file = $new_file . $dish_id . ".{$type}";

                if (!file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                    echo json_encode(array("message" => "error"));
                }
            }
        }
        $this->percent = 1; #原图压缩，不缩放，但体积大大降低
        $this->src=$new_file;
        $image = $this->compressImg($new_file);
        $conn = oci_connect($admin, '123456', 'localhost:1521/ORCL', "AL32UTF8");
        $sql_insert = "UPDATE SCOTT.$table SET $table"."_pic='$new_file' WHERE $table"."_id='$id'";
        $statement = oci_parse($conn, $sql_insert);
        if (oci_execute($statement)) {
            echo json_encode(array("message" => "success"));
        } else {
            echo json_encode(array("message" => "error", "reason" => oci_error()));
        }
    }
}
