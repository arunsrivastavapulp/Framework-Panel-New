<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
unset($_SESSION['token']);
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
if (!empty($_SESSION['username'])) {
    require_once('modules/myapp/myapps.php');
    $apps = new MyApps();
    $results = $apps->check_user_exit($_SESSION['username']);
    $user_id = $results['id'];
    $page = 0;
    $type = 1;
//    $data = array('user_id'=>$user_id,'type'=>$type,'page');
    $allapps = $apps->get_all_apps($user_id, $type, $page);
    $contappP1 = $apps->count_my_apps($user_id, $type);
    if ($contappP1 == 0) {
        $type = 0;
        $allapps = $apps->get_all_apps($user_id, $type, $page);
        $contappD1 = $apps->count_my_apps($user_id, $type);
        $setected = 'selected=selected';
    } else {
        $setected = '';
    }
} else {

    //header('Location: http://pulpstrategist.co.in/1353/index.php');
    echo "<script>window.location.href='".$basicUrl."'</script>";
    exit();
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        /*var rightHeight = $(window).height() - 45;
        $(".right_main").css("height", rightHeight + "px")*/

        $("aside ul li").removeClass("active");
        $("aside ul li.tablet").addClass("active");

    });
</script>
<style>
body{
	background:#FFF;
}
</style>
<section class="main">
    <section class="right_main">
        <div class="right_inner">
            <div class="apps_head">
                <div class="apps_head_left">
                    <h1>My Apps</h1>
                    <p>You can toggle between the apps that have been published by selecting 'Published', and the ones which are still in drafts by selecting 'Drafts' from the dropdown menu below.</p>
                </div>
                <div class="apps_head_right" style="display:none">
                    <input type="text" placeholder="Search" id="search">
                    <a href="#"><img src="images/search.png"></a>
                </div>
                <div class="clear"></div>
            </div>
            <div class="apps_body">
                <select id="app_status">
                    <option value="1" <?php echo $setected; ?>>Published</option>
                    <option value="0" <?php echo $setected; ?>>Drafts</option>

                </select>
                <div class="apps_list">
                    <?php
                    if ($contappD1 == 0) {
                        foreach ($allapps as $val) {
							if($val['type_app']==1)
								$file='myapps2.php';
							if($val['type_app']==2)
								$file='catalogue.php';
                            $img = $val['app_image'];
                            if ($img == '') {
                                $img = 'images/myapp1.jpg';
                            }
                            echo ' <div class="apps_box">
                        <a href="'.$file.'?appid=' . $val['id'] . '"><img src="' . $img . '"></a>
                        <div class="apps_box_name">
                            <h2><a href="'.$file.'?appid=' . $val['id'] . '">' .stripslashes($val['summary']) . '</a></h2>
                            <p><a href="'.$file.'?appid=' . $val['id'] . '">Android </a></p>
                        </div>
                        <div class="apps_box_download">
                         <div data-aapid="' . $val['id'] . '" class="downloadapp"><img src="images/app_download.png"></div>
                             <a href="" style="visibility: hidden;" id="zipUrl"></a>
                        </div>
                        <div class="clear"></div>
                    </div>';
                        }
                    } else if ($contappP1 == 0) {

                        foreach ($allapps as $val) {
							if($val['type_app']==1)
								$file='myapps2.php';
							if($val['type_app']==2)
								$file='catalogue.php';
                            $img = $val['app_image'];
                            if ($img == '') {
                                $img = 'images/myapp1.jpg';
                            }
                            echo ' <div class="apps_box">
                        <a href="'.$file.'?appid=' . $val['id'] . '"><img src="' . $img . '"></a>
                        <div class="apps_box_name">
                            <h2><a href="'.$file.'?appid=' . $val['id'] . '">' . stripslashes($val['summary']). '</a></h2>
                            <p><a href="'.$file.'?appid=' . $val['id'] . '">Android </a></p>
                        </div>
                        <div class="apps_box_download">
                        <div data-aapid="' . $val['id'] . '" class="deleteapp" ><img src="images/app_delete.png"></div>			
                        </div>
                        <div class="clear"></div>
                    </div>';
                        }
                    } else {
                        echo 'No records found';
                    }
                    ?>

                </div>
                <div class="clear"></div>
                <div id="app_loader"></div>
                <input type="hidden" id="user_id" value="<?php echo isset($_SESSION['username']) ? $user_id : '' ?>">
                <input type="hidden" id="app_type" value="">		

                <img src="images/more_button.png" id="load_more_apps" <?php
                     if ($contappP1 > 6) {
                         echo "style='display:block'";
                     } else if ($contappD1 > 6) {
                         echo "style='display:block'";
                     } else {
                         echo "style='display:none'";
                     }
                     ?>/>

                <script type="text/javascript">
                    $(document).ready(function () {
                        var select = $("#app_status").val();
                        $("#app_type").val(select);

                        $(document).on("click", ".deleteapp", deleteAppFunction);
                        function deleteAppFunction() {
                            $(".delete_app").css({'display': 'block'});
                            $(".popup_container").css({'display': 'block'});
                            $(this).attr("id", "current");
                        }

                        $(".delete_app input").click(function () {
                            var confirmVal = $(this).val();
                            var appid = $("#current").attr("data-aapid");
                            $("#current").removeAttr("id");
//                            alert(appid);

                            $(".delete_app").css({'display': 'none'});
                            $(".popup_container").css({'display': 'none'});

                            if (confirmVal == "Yes") {
                                var form_data = {
                                    token: '<?php echo $token; ?>', //used token here.                      
                                    hasid: appid,
                                    confirm: confirmVal,
                                    is_ajax: 2
                                };
                                $.ajax({
                                    type: "POST",
                                    url: 'modules/myapp/deleteapp.php',
                                    data: form_data,
                                    success: function (response)
                                    {
                                        if (response == 1) {
                                            $('select#app_status').val(0).trigger('change');
                                            console.log(response);

                                        } else if (response != 1) {
                                            console.log(response);
                                        }
                                    },
                                    error: function () {
                                        console.log("error in ajax call");
                                        alert("error in ajax call");
                                    }
                                });
                            }

                        });

                        $(document).on("click", ".downloadapp", createzipFunction);

                        function createzipFunction() {
                            var appid = $(this).attr("data-aapid");

                            $(".popup_container").css({'display': 'block'});
                            $("#page_ajax").html('<img src="images/ajax-loader.gif">');

                            var form_data = {
                                token: '<?php echo $token; ?>', //used token here.                      
                                hasid: appid,
                                is_ajax: 1
                            };
                            $.ajax({
                                type: "POST",
                                url: 'modules/myapp/createzip.php',
                                data: form_data,
                                success: function (response)
                                {
                                    if ((response != 1) && (response != 2)) {
                                        window.location = response;
//                                        $("#zipUrl").attr("href", response);
//                                        $(document).on("click", "#zipUrl", function(){
//                                            $('a').eq(0).click();
//                                        });
//                                        $("#zipUrl").trigger("click");

                                        console.log(response);
                                        $(".popup_container").css({'display': 'none'});
                                        $("#page_ajax").html('');

                                    } 
                                    else if (response == 1) {
                                        console.log(response);
                                        $(".popup_container").css({'display': 'none'});
                                        $("#page_ajax").html('');
                                    }
                                    else if (response == 2) {
                                        console.log("App Folder does not exist");
                                        $(".popup_container").css({'display': 'none'});
                                        $("#page_ajax").html('');
                                    }
                                },
                                error: function () {
                                    $(".popup_container").css({'display': 'none'});
                                    $("#page_ajax").html('');
                                    console.log("error in ajax call");
//                                    alert("error in ajax call");
                                }
                            });


                        };

                      
                    });
                </script>
            </div>
    </section>
</section>
</section>
</body>
</html>
