<aside>
    <div class="user_img"> <div class="user_img_icon userprofileclick" title="Edit My Profile"><?php echo $mg; ?></div><div class="clear"></div> <span>Hi, <?php echo $name; ?></span></div>
    <div id="loginout">
        <?php if (!empty($_SESSION['username']) || !empty($_SESSION['custid'])) { ?>
            <a href="logout.php" id="panel_logout" onclick="logout();"> <img src="images/login_icon.png" /> <span>Logout</span></a><?php
    } else {
            ?>
            <a href="javascript:void(0)" id="panel_login"> <img src="images/login_icon.png" /> <span>Login</span></a>
            <?php
        }
        ?>
    </div>

    <ul class="leftsidemenu" >
        <li class="mobile-icon active"><a href="content-publishing-apps.php?id=check" title="Edit App"><img src="images/create_app_icon.png" alt="Image Not Found"> </a></li>
        <li class="tablet"><a href="applisting.php" id="myapps" title="My Apps"><img src="images/my_app_icon.png" alt="Image Not Found"></a></li>
        <li class="cart"><a href="payment_details.php" title="Cart"><img src="images/cart_left.png" alt="Image Not Found"></a></li>
        <li class="user"><a href="choose_package.php" title="Package"><img src="images/choose_package_icon.png" alt="Image Not Found"></a></li>
        <li class="truck"><a href="statistics.php" title="Statistics"><img src="images/statistics_icon.png" alt="Image Not Found"></a></li>
        <li class="truck"><a href="notification.php" title="Notification"><img src="images/bell_icon.png" alt="Image Not Found"></a></li>
        <li class="support"><a href="support.php" title="Support"><img src="images/support.png" alt="Image Not Found"></a></li>
    </ul>
</aside>