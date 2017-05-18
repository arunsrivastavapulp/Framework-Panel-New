<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');

if (isset($_SESSION['currencyid'])) {
    $checkcountry = $_SESSION['country'];
    $currency = $_SESSION['currencyid'];
    $currencyIcon = $_SESSION['currency'];
} else {
    $db->get_country();
    $checkcountry = $_SESSION['country'];
    $currency = $_SESSION['currencyid'];
    $currencyIcon = $_SESSION['currency'];
}

require_once('modules/myapp/myapps.php');
$apps = new MyApps();
$custid = $_SESSION['custid'];
$myOrder = $apps->myorders($custid);
$x = 0;

if ($checkcountry == "IN") {
    $currency = 1;
    $currencyIcon = "Rs.";
} else {
    $currency = 2;
    $currencyIcon = "$";
}
?>
<style type="text/css">
    body{
        background: #fff;
    }
</style>
<section class="main">
    <section class="right_main">
        <div class="right_inner">
            <div class="myorders">
                <p class="head">My Orders:</p>
                <p class="rightbutton"><a href="payment_details.php">My Cart</a></p>
                <div class="clear"></div>
                <div class="cartTable">
                    <ul class="cartHead">
                        <li>No.</li>
                        <li>Invoice No.</li>
                        <li>App name <span class="caret"><i class="fa fa-angle-down"></i></span></li>
<!--                        <li>Package<span class="caret"><i class="fa fa-angle-down"></i></span></li>
                        <li>Years</li>
                        <li>ASO Pack<span class="caret"><i class="fa fa-angle-down"></i></span></li>   -->
                        <li>Date of Purchase<span class="caret"><i class="fa fa-angle-down"></i></span></li>
                        <li>Amount<span class="caret"><i class="fa fa-angle-down"></i></span></li>

                    </ul>
                    <?php
                    if (is_array($myOrder)) {
                        $count = count($myOrder);
                    } else {
                        $count = 0;
                    }
                    if ($count > 0 && $custid != '1462053357') {
                        foreach ($myOrder as $key => $value) {
                            $x++;
                            ?>
                            <ul class="cartrow">
                                <li><?php echo $x; ?></li>
                                <?php if ($value['invoicepdf'] != '') { ?>
                                    <li><a href="<?php echo $basicUrl . 'pdf/' . $value['authorid'] . '/' . $value['invoicepdf']; ?>" target="_blank"><?php echo $value['order_id']; ?></a></li>
                                <?php } else { ?>
                                    <li><a onclick="popopen()" target="_blank"><?php echo $value['order_id']; ?></a></li>
                                <?php }
                                ?>                                
                                <li><?php echo $value['AppNames']; ?></li>
                                <!--                        <li>Package name</li>
                                                        <li>1</li>
                                                        <li>ASO Package</li>   -->
                                <li><?php echo date("jS F, Y", strtotime($value['transaction_date'])); ?></li>
                                <li><?php
                                    if ($value['currency_type_id'] == 1) {
                                        echo 'Rs. ' . $value['total_amount_paid'];
                                    } else {
                                        echo '$ ' . $value['total_amount_paid'];
                                    }
                                    ?></li>
                            </ul>                   
                            <?php
                        }
                    } else {
                        ?>
                        <script>
                            $(".cartTable").replaceWith("<p class='emptycart'>You have not placed any orders.</p>");
                        </script>


                        <?php
                    }
                    ?>

                </div>
            </div>




        </div>
    </section>
</section>
</section>
</body>
<script>

    function popopen() {
         $(".popup_container").css({'display': 'block'});
        $(".confirm_name .confirm_name_form").html('<p>Invoice not available.</p><input type="button" value="OK">');
        $(".confirm_name").css({'display': 'block'});
        $(".close_popup").css({'display': 'block'});

    }
    $(document).ready(function () {
        $(".confirm_name input").click(function () {
            $(".confirm_name").css({'display': 'none'});
            $(".popup_container").css({'display': 'none'});
        });
    });
</script>
</html>