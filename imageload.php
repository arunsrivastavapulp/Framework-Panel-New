<?php
require_once('page.php');
$img = $_POST['image'];
$oldUrl=$_POST['oldsrc'];
$file = str_replace(' ', '_', $_POST['imgname']);
$file = htmlentities($file);
if (strpos($img, 'data:image/png') !== false) {
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
} elseif ((strpos($img, 'data:image/jpg') !== false) || (strpos($img, 'data:image/jpeg') !== false)) {
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
} elseif (strpos($img, 'data:image/bmp') !== false) {
    $img = str_replace('data:image/bmp;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
}
session_start();
$length       = 10;
$randomString = substr(str_shuffle(md5(time())), 0, $length);
$explode = explode('/', $file);
$filedata = explode('.', $explode[1]);
$directory = $explode[0] . '/' . $_SESSION['appid'] . '/' . $randomString . '__' . date('d_m_Y_H_i_s') . '.' . "jpeg";
 
 if (!file_exists($explode[0] . '/' . $_SESSION['appid'])) {
        mkdir($explode[0] . '/' . $_SESSION['appid'], 0777, true);
    }
	$entry = base64_decode($img);
$image = imagecreatefromstring($entry);
header ( 'Content-type:image/jpeg' ); 
imagejpeg($image, $directory);
imagedestroy ( $image ); 
$fileName = $_SESSION['appid'].'/'.$randomString . '__' . date('d_m_Y_H_i_s') . '.' . "jpeg";
$fileTempName = $directory;

if ($s3->putObjectFile($fileTempName, "sharmaji1104", $fileName, S3::ACL_PUBLIC_READ)) {
  list($width, $height) = getimagesize($directory);
        echo json_encode(array('imageurl' => 'https://s3-us-west-2.amazonaws.com/sharmaji1104/'.$fileName ,'height' => $height, 'width' => $width));
        unlink($file);
		unlink($directory);
			if (strpos($a, 'panelimage') !== false) {
			unlink($oldUrl);
			}
			elseif(strpos($a, 'sharmaji1104') !== false)
			{
			$r= explode('sharmaji1104/',$oldUrl);
			$uri= $r[1];	
			deleteObject("sharmaji1104", $uri);
			}
		
		
}else{
echo "Something went wrong while uploading your file... sorry.";
}

    ?>