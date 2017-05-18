<?php
session_start();
require_once ('../../config/db.php');
$connection = new Db();
$mysqli = $connection->dbconnection();

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    //Request identified as ajax request

    if (@isset($_SERVER['HTTP_REFERER'])) {
        //HTTP_REFERER verification
        if ($_POST['token'] == $_SESSION['token']) {
            $appid = $_POST['hasid'];
            $is_ajax = $_POST['is_ajax'];

            $username = $_SESSION['username'];
            $custid = $_SESSION['custid'];
            if (trim($custid) != '') {
                $getuserid = "select id from author where custid='$custid'";
                $stmtuserid = $mysqli->prepare($getuserid);
                $stmtuserid->execute();
                $resultuserid = $stmtuserid->fetch(PDO::FETCH_ASSOC);
                $userId = $resultuserid['id'];

                $url = $connection->siteurl();
                $string = getcwd();
                $expStr = explode("modules", $string);
                $pathdir = $expStr[0] . 'apps/' . $appid;

                $directory = $pathdir;
                if (file_exists($directory)) {
                    $zip = new ZipArchive();
//                $zip_name = $directory. ".zip";
                    $zip_file = $directory . ".zip";
                    $zip->open($zip_file, ZipArchive::CREATE);
                    $files = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::LEAVES_ONLY
                    );
                    foreach ($files as $file) {
                        $path = $file->getRealPath();
                        //check file permission
                        if (fileperms($path) != "16895") {
                            $zip->addFromString(basename($path), file_get_contents($path));
                            $result = $url . 'apps/' . $appid . '.zip';
//                        echo "<span style='color:green;'>{$path} is added to zip file.<br /></span> ";
                        } else {
//                        echo"<span style='color:red;'>{$path} location could not be added to zip<br /></span>";
                            $result = "1";
                        }
                    }
                    $zip->close();
                    echo $result;
                } else {
                    mkdir($expStr[0] . 'apps/' . $appid, 0777, true);
                    if (file_exists($directory)) {
                        $zip = new ZipArchive();
//                $zip_name = $directory. ".zip";
                        $zip_file = $directory . ".zip";
                        $zip->open($zip_file, ZipArchive::CREATE);
                        $files = new RecursiveIteratorIterator(
                                new RecursiveDirectoryIterator($directory), RecursiveIteratorIterator::LEAVES_ONLY
                        );
                        foreach ($files as $file) {
                            $path = $file->getRealPath();
                            //check file permission
                            if (fileperms($path) != "16895") {
                                $zip->addFromString(basename($path), file_get_contents($path));
                                $result = $url . 'apps/' . $appid . '.zip';
//                        echo "<span style='color:green;'>{$path} is added to zip file.<br /></span> ";
                            } else {
//                        echo"<span style='color:red;'>{$path} location could not be added to zip<br /></span>";
                                $result = "1";
                            }
                        }
                        $zip->close();
                        echo $result;
                    } else{
                        echo "2";
                    }
                }
            } else {
                echo "App is not deleted.";
            }
        } else {
            echo "Request is not completed";
        }
    } else {
        echo "Request is not completed.";
    }
}
?>