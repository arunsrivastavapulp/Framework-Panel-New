<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/pricing/pricing.php');
require_once('modules/myapp/myapps.php');
$apps = new MyApps();
$token = md5(rand(1000, 9999)); //you can use any encryption
$_SESSION['token'] = $token;
$mypricing = new mypricing();
$custid = $_SESSION['custid'];
$palnprice = $mypricing->getallappsplan($custid);
$mpackages = $mypricing->getallappsMpackages($custid);
$paidapp = $mypricing->getallPaidapps($custid);
$orderid = '';
$x = 0;
$y = 0;
?>
<section class="main">
    <section class="right_main">
        <div class="right_inner">
            <div class="bannertitle">
                <h2 style="font-size:16px;line-height:28px">Banner Title</h2>
                <p style="margin-top:10px">Apps built using the Instappy.com are packed with features that are built for your success.
                    Instappy apps are <span>instant, affordable, stunning, intuitive</span> and will <span>change the way you interact 
                        with your customers.</span> Our business model allows us to provide everyone with world-class, feature-packed 
                    applications on Android and iOS for a fraction of the cost it takes to develop an app otherwise.</p>


            </div>
            <div class="bannertitle noapp">
             <span>Your cart is empty<br/>
             <a href="applisting.php">Complete Your App</a> or <a href="content-publishing-apps.php?id=check">Create New</a>
            </span>
            </div>
            
        </div>
    </section>
</section>
</section>
</body>
<script>

    function getPrice() {

//console.log($(this).attr("id"));

        var tar = $(this).attr("id");

        var suffix = tar.split("_");
//     console.log(suffix[0]+'000'+suffix[1]);
        var selecttext = suffix[0];
        var selectno = suffix[1];

        var timePeriod = '';
        var packageType = '';
        var appAllowed = '';
        var priceValue = '';

        if (selecttext == 'timePeriod' || selecttext == 'packageType' || selecttext == 'appAllowed') {
            var currenttimePeriod = '#timePeriod_' + selectno;
            var currentpackageType = '#packageType_' + selectno;
            var currentappAllowed = '#appAllowed_' + selectno;

            timePeriod = $(currenttimePeriod).val();
            packageType = $(currentpackageType).val();
            appAllowed = $(currentappAllowed).val();

        }

        $.ajax({
            type: "POST",
            url: BASEURL + 'API/getPrice.php/getPrice',
            data: 'timePeriod=' + timePeriod + '&appAllowed=' + appAllowed + '&packageType=' + packageType + '&appType=1',
            dataType: 'json',
            success: function (response) {
                priceValue = response[0].amount_intransaction;
                savingTotal = response[0].total_saving;
                var currentfinalPrice = '#finalPrice_' + selectno;
                var currentapptotalPrice = '#apptotalPrice_' + selectno;
                var topprice = priceValue;
                $(currentfinalPrice).html(topprice);
                var splashscreen = '#splashscreen_' + selectno;
                var iconP = '#icon_' + selectno;
                var ss = $(splashscreen).val();
                var icon = $(iconP).val();

                if (ss == undefined) {
                    ss = 0;
                }
                if (icon == undefined) {
                    icon = 0;
                }
                if (priceValue == undefined) {
                    priceValue = 0;
                }

                var apptoalPrice = (parseInt(priceValue) + parseInt(ss) + parseInt(icon)) + '.00';
                $(currentapptotalPrice).html(apptoalPrice);
                calculate();
            },
        });

    }

    function discount() {
        var promocode = document.getElementById("promocode").value;
        var totalamount = $("#finalPrice").html();
        $.ajax({
            type: "POST",
            url: BASEURL + 'modules/pricing/discountcheck.php',
            data: 'promocode=' + promocode + '&token=<?php echo $token; ?>' + '&od=<?php echo $orderid; ?>' + '&totalam=' + totalamount,
            success: function (response) {

                if (response == 1) {
                    $('#errorMsg1').show();
                    $('#errorMsg2').hide();
                    $('#errorMsg3').hide();
                } else if (response == 2) {
                    $('#errorMsg2').show();
                    $('#errorMsg1').hide();
                    $('#errorMsg3').hide();
                } else if (response == 3) {
                    $('#errorMsg1').show();
                    $('#errorMsg2').hide();
                    $('#errorMsg3').hide();
                } else {
                    var obj = JSON.parse(response);

                    $(".tsaving").html(obj.discount);
                    $("#finalPrice3").html(obj.afterdiscount);
                    $("#finalPrice2").html(obj.afterdiscount);
                    $('#errorMsg3').show();
                    $('#errorMsg2').hide();
                    $('#errorMsg1').hide();
//                    console.log(response);
                }
            }
        });

    }


    function SetValues() {
        var totalapp = $("div[id^='finalPrice_']").length;
        for (var i = 1; i <= totalapp; i++) {

            var timePeriod = '#timePeriod_' + i;
            var packageType = '#packageType_' + i;
            var appAllowed = '#appAllowed_' + i;
            var appid = '#appid_' + i;

            var tp = $(timePeriod).val();
            var pt = $(packageType).val();
            var aa = $(appAllowed).val();
            var ai = $(appid).attr('data-appid');

            $.ajax({
                type: "POST",
                url: BASEURL + 'modules/pricing/s_price.php',
                data: 'timeperiod=' + tp + '&appAllowed=' + aa + '&packageType=' + pt + '&appid=' + ai + '&token=<?php echo $token; ?>',
                success: function (response) {
                    console.log(response);
                    var totalmp = $("select[id^='appName_']").length;
                    for (var y = 1; y <= totalmp; y++) {
                        var appname = '#appName_' + y;
                        var mpID = '#mp_id_' + y;
                        var sapp = $(appname).val();
                        var mp_ID = $(mpID).val();
                        $.ajax({
                            type: "POST",
                            url: BASEURL + 'modules/pricing/mp_price.php',
                            data: 'appselected=' + sapp + '&token=<?php echo $token; ?>' + '&mpid=' + mp_ID,
                            success: function (response) {
                                console.log(response);
                                var total = $("#finalPrice").html();
                                var total_ad = $("#finalPrice2").html();
                                $.ajax({
                                    type: "POST",
                                    url: BASEURL + 'modules/pricing/totalamount.php',
                                    data: 'token=<?php echo $token; ?>' + '&total=' + total + '&totalad=' + total_ad,
                                    success: function (response) {
                                        console.log(response);
                                        if (response == 1) {
                                            window.location = BASEURL + 'payment_info.php';
                                        } else {
                                            $(".confirm_name .confirm_name_form").html('<p>Something went wrong.</p><input type="button" value="OK">');
                                            $(".confirm_name").css({'display': 'block'});
                                            $(".close_popup").css({'display': 'block'});
                                            $(".popup_container").css({'display': 'block'});
                                        }

                                    }
                                });

                            }
                        });
                    }
                }
            });
        }



//        
    }


    function calculate() {
        var mp = '0.00';
        var appprice = '0.00';

        var totalclassmp = $("p[id^='mptotal_']").length;
        for (var y = 1; y <= totalclassmp; y++) {

            var mptotal = '#mptotal_' + y;
            mp = parseInt(mp) + parseInt($(mptotal).html());

        }

        var totalclassapp = $("div[id^='apptotalPrice_']").length;
        for (var y = 1; y <= totalclassapp; y++) {

            var apptotal = '#apptotalPrice_' + y;
            appprice = parseInt(appprice) + parseInt($(apptotal).html());

        }

        var total = parseInt(appprice + mp) + '.00';
        $("#finalPrice2").html(total);
        var discountS = $(".tsaving").html();

        if (discountS != '') {
            var afterdiscount = parseInt(total) - parseInt(discountS) + '.00';
            $("#finalPrice").html(afterdiscount);
            $("#finalPrice3").html(afterdiscount);
        } else {
            $("#finalPrice").html(total);
            $("#finalPrice3").html(total);
        }
    }

    $(document).ready(function () {

        var totalclass = $("div[id^='finalPrice_']").length;
        for (var i = 1; i <= totalclass; i++) {

            var splashs = '#splashscreen_' + i;
            var iconPrice = '#icon_' + i;
            var finalPr = '#finalPrice_' + i;
            var apptotalPr = '#apptotalPrice_' + i;
            var splashs = $(splashs).val();
            var iconsP = $(iconPrice).val();
            var finaltopP = $(finalPr).html();

            if (splashs == undefined) {
                splashs = 0;
            }
            if (iconsP == undefined) {
                iconsP = 0;
            }
            if (finaltopP == undefined) {
                finaltopP = 0;
            }
            var gettotal = (parseInt(finaltopP) + parseInt(splashs) + parseInt(iconsP)) + '.00';
            $(apptotalPr).html(gettotal);
        }

        calculate();

        $(document).on("change", ".app select", getPrice);

        $(".mpackage").click(function () {
            var mpid = $(this).attr("data-mpid");
            $.ajax({
                type: "POST",
                url: BASEURL + 'modules/pricing/packageaddtocart.php',
                data: 'packageid=' + mpid + '&token=<?php echo $token; ?>',
                success: function (response) {
                    if (response == 1) {
                        $(".confirm_name .confirm_name_form").html('<p>Please publish your app first.</p><input type="button" value="OK">');
                        $(".confirm_name").css({'display': 'block'});
                        $(".close_popup").css({'display': 'block'});
                        $(".popup_container").css({'display': 'block'});
                    } else {
                        window.location = window.location;
                        console.log(response);
                    }
                }
            });
        });

        //if($(".mp .ms-choice").not('.open')){
        //    var mp_id = $(this).parent().prev('select').val();
        //    alert(mp_id);
        //}
        $(".ms-parent.appName").click(function () {
            var mp_id = $(this).prev('select').val();

            if (mp_id != null)
            {
                var mdlength = mp_id.length;
                var amount = parseInt($(this).parents(".payment_left_common.mp").find(".package_box_right .mpamount").val());
//                var amount=parseInt($(this).parents(".payment_left_common.mp").find(".package_box_right p").text());
                $(this).parents(".payment_left_common.mp").find(".package_box_right p").text(amount * parseInt(mdlength) + '.00');
                calculate();

            } else {
                $(this).parents(".payment_left_common.mp").find(".package_box_right p").text('0.00');
                calculate();
            }

        });

        $(".confirm_name input").click(function () {
            $(".confirm_name").css({'display': 'none'});
            $(".popup_container").css({'display': 'none'});
        });

        $(".payment_files_name .files_name_right span").click(function () {
            if ($(this).is(':empty')) {
                $(this).parent().parent().addClass("item_disabled"); //line 2125
                $(this).css("background", "none").append("<input type='checkbox'>");
            } else {
                $(this).parent().parent().removeClass("item_disabled");
                $(this).css("background", "url('images/menu_delete.png')");
                $(this).children("input").remove();
            }
        });
        $(".stats_download a").click(function () {
            $(this).next().toggleClass("show_pop");
            $(this).parent().siblings().children("div").removeClass("show_pop");
        });
        $(document).click(function () {
            $(".stats_download a + div").removeClass("show_pop");
        });
        $('.stats_download a').on('click', function (e) {
            e.stopPropagation();
        });
        $('.stats_download_tooltip').on('click', function (e) {
            e.stopPropagation();
        });

        /*var rightHeight = $(window).height() - 45;
         $(".right_main").css("height", rightHeight + "px")*/
    });
    var sessionvalue = '';
    var savingamount = '';



</script>

<script>
    $(document).ready(function () {
        $('.img-tog').click(function () {
            var img_visb = $(this).parents('.halfpackage , .half_package_img').find('img');
            $(img_visb).toggle()
        })

        $('.clk').click(function () {
            $('#ovr , #p_up').fadeIn();
        });

        $('#ovr , #p_up i , .closed').click(function () {
            $('#ovr , #p_up').fadeOut();
        });

        $('.app_select_box .open_app_box').on('click', function () {
            $('.app_select_box_drop').toggle();
        });
        $('.app_select_box .open_app_box').on('focus', function () {
            $(this).blur();
        });
        $('.app_select_box_drop ul li input:checkbox').on('click', function () {
            if ($(this).prop('checked') == true) {
                $('.app_select_box').find('.open_app_box').val($('.app_select_box').find('.open_app_box').val() + $(this).siblings('label').text() + ',');
            }
            else {
                $('.app_select_box').find('.open_app_box').val($('.app_select_box').find('.open_app_box').val().replace($(this).siblings('label').text() + ',', ""));

            }

        });
    })
</script>
<link href="css/multiple-select.css" rel="stylesheet"/>
<script src="js/jquery.multiple.select.js"></script>
<script>

//    $(document).on("change", ".mp select", getPrice);
    $('div.selectdiv select.appName').multipleSelect({placeholder: "Select App"});



</script>

</html>
