<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<table cellpadding="0" cellspacing="0" width="650" align="center" autosize="0" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; border:1px solid #7c7c7c;">
	<tr>
    	<td style="background:#ffcc00;">
        	<table cellpadding="0" cellspacing="0" width="100%">
            	<tr>
                	<td style="padding:30px; width:360px; box-sizing:border-box;">
                    	<img src="{base_url}edm/images/logo.png">
                    </td>
                    <td style="padding:10px; width:230px; box-sizing:border-box;">
                    	<table cellpadding="0" cellspacing="0">
                        	<tr>
                                <td colspan="2" style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:14px;">Dear {customer_name}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:14px;">Thank you for your purchase.</td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; padding-top:30px;">Log into your account</td>
                                <td style="padding-top:30px; padding-left:10px;"><a href="{base_url}applisting.php" style="float:right;"><img src="{base_url}edm/images/myapps.png" style="float:right;padding:0 0 0 0"></a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td style="background:#7a7a7a; padding:10px 35px;">
        	<table cellpadding="0" cellspacing="0" width="100%">
            	<tr>
                	<td style="padding:10px 25px 10px 0; color:#fff;" width="60%">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#fff">To,</td>
							</tr>
							<tr>
								<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#fff; padding:10px 0 5px 20px; border-bottom:1px solid #fff;">M/s. {customer_name}</td>
							</tr>
							<tr>
								<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#fff; padding:10px 0 5px 20px; border-bottom:1px solid #fff;">{address},</td>
							</tr>
							<tr>
								<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#fff; padding:10px 0 5px 20px; border-bottom:1px solid #fff;">{state}</td>
							</tr>
						</table>
                    </td>
                    <td align="right" style=" padding:0; width:272px; text-align:right;" width="40%">
                    	<table cellpadding="0" cellspacing="0" width="100%" style="text-align:left; background:#fff;">
                        	<tr>
                            	<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#323232; padding:10px 20px 0;">Invoice Number:</td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#323232; padding:0px 20px;">{order_no}</td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:2px; color:#323232; padding:0px 20px;"><hr></td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#323232; padding:10px 20px 0;">Date:</td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#323232; padding:0px 20px;">{currentdate}</td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:2px; color:#323232; padding:0px 20px;"><hr></td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#323232; padding:10px 20px 0;">Customer ID:</td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#323232; padding:0px 20px;">{custid}</td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:2px; color:#323232; padding:0px 20px 10px 20px;"><hr></td>
                            </tr>
                            <tr>
                            	<td width="100%"><a href="https://www.surveymonkey.com/r/instappy"><img src="{base_url}edm/images/survey_btn.png" style="display:block;"></a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td style="padding:0 35px;">
        	<p style="margin:0;margin-top:30px;">Your Recent Order</p>
            <table cellpadding="0" cellspacing="0" width="100%" style="text-align:center;">
                <tr>
                    <th style="font-family:Arial, Helvetica, sans-serif; font-size:15px; background:#ffcc00; color:#fff; padding:10px 0; border-right:1px solid #ffd52d;">Product</th>
                    <th style="font-family:Arial, Helvetica, sans-serif; font-size:15px; background:#ffcc00; color:#fff; padding:10px 0; border-right:1px solid #ffd52d;">Quantity</th>
                    <th style="font-family:Arial, Helvetica, sans-serif; font-size:15px; background:#ffcc00; color:#fff; padding:10px 0; border-right:1px solid #ffd52d;">Term</th>
                    <th style="font-family:Arial, Helvetica, sans-serif; font-size:15px; background:#ffcc00; color:#fff; padding:10px 0;">Price</th>
                </tr>
                {order_items}
                <tr>
                	<td style="border-left:1px solid #333; padding:10px 0;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; text-align:right; padding:10px 0; color:#323232;">Discount (if any):</td>
                	<td></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; padding:10px 0; font-size:15px; border-right:1px solid #333; text-align:left; color:#323232; padding:10px 0;">{currency} {discount}</td>
                </tr>
                <tr>
                	<td style="border-left:1px solid #333; padding:10px 0;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; text-align:right; padding:10px 0; color:#323232;">Subtotal:</td>
                	<td style="padding:10px 0;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; border-right:1px solid #333; text-align:left; color:#323232; padding:10px 0;">{currency} {subtotal}</td>
                </tr> 
                <tr>
                	<td style="border-left:1px solid #333; padding:10px 0;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; padding:10px 0; font-size:15px; text-align:right; padding:10px 0; color:#323232;">Service Tax (14%):</td>
                	<td style="padding:10px 0;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; padding:10px 0; font-size:15px; border-right:1px solid #333; text-align:left; color:#323232; padding:10px 0;">{currency} {tax}</td>
                </tr>
                <tr>
                	<td style="border-left:1px solid #333; padding:10px 0; border-bottom:1px solid #333; border-top:1px solid #dbdbdb;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; text-align:right; padding:10px 0; border-bottom:1px solid #333; color:#323232; border-top:1px solid #dbdbdb;">Order Total:</td>
                	<td style="border-bottom:1px solid #333; padding:10px 0; border-top:1px solid #dbdbdb;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; border-bottom:1px solid #333; border-right:1px solid #333; border-top:1px solid #dbdbdb; color:#323232; text-align:left; padding:10px 0;">{currency} {order_total}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td style="font-family:Arial, Helvetica, sans-serif; font-size:9px; color:#323232; line-height:12px; padding:40px 35px;">Address: Pulp Strategy Technologies Pvt. Ltd. HO: Plot No-48, 2nd Floor, Okhla Industrial Estate, Phase III, New Delhi-110020</td>
    </tr>
    <tr width="100%" align="center">
        <td style="padding:0 35px;" width="100%" align="center">
            <img src="{base_url}edm/images/plan_head.png" style="display:block;width: 100%; text-align: center;"></td>
        </tr>
    <tr width="100%">
    	<td style="padding:0 35px;" width="100%" align="center">
       
            <table cellpadding="0" cellspacing="0" style="background:#7a7a7a;" width="100%">
            	<tr width="50%">
                    <td style="padding:25px 24px 0;" width="50%"><img src="{base_url}edm/images/plan1.png" style="text-align: left;"><img src="{base_url}edm/images/devider.png" style="text-align: center;"><img src="{base_url}edm/images/plan2.png" style="text-align: right;"></td>
                </tr>
                <tr width="50%">
                	<td style="padding:13px 25px 15px;" width="50%"><img src="{base_url}edm/images/plan3.png"></td>
                </tr>
<!--                <tr>
                	<td style="padding:13px 25px 0;"><img src="{base_url}edm/images/plan4.png"></td>
                </tr>-->
                <!-- tr>
                	<td style="padding:13px 25px 15px;"><img src="{base_url}edm/images/plan5.png"></td>
                </tr -->
            </table>
        </td>
    </tr>
    <tr>
    	<td style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#323232; line-height:12px; padding:40px 35px 10px 35px;">Offers cannot be used in conjunction with any other offer, sale, discount or promotion. After the initial purchase term, discounted products will renew at the then-current renewal list price.</td>
    </tr>
    <tr>
		<td style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#323232; line-height:12px; padding:0px 35px 10px 35px;">Prices are current as of {currentdate}, and may be changed without notice.</td>
    </tr>
    <tr>
		<td style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#323232; line-height:12px; padding:0px 35px 10px 35px;">By using these products, you agree that you are bound by the Universal Terms of Service and Privacy Policy.</td>
    </tr>
    <tr>
		<td style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#323232; line-height:12px; padding:0px 35px 10px 35px;">Please do not reply to this email. Emails sent to this address will not be answered.</td>
    </tr>
    <tr>
		<td style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#323232; line-height:12px; padding:0px 35px 10px 35px;">Sevice Tax No. : AAICP0148DSD001</td>
    </tr>
    <tr>
		<td style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#323232; line-height:12px; padding:0px 35px 10px 35px;">CIN No. : U74140DL2015PTC276149</td>
    </tr>
     <tr>
      <td style="color:#bcbcbc;margin:0;padding:15px; font-size:10px;font-family:Arial, Helvetica, sans-serif;text-align:justify">
        Disclaimer: You have received this email as you are a valued customer subscribed to Instappy.com or have registered for an account on Instappy.com. The information provided herein regarding products, services/offers, benefits etc of Instappy are governed by the detailed terms and conditions accepted by you. The information provided herein above does not amount to a discount, offer or sponsor or advice as regards any products or services of Instappy or any of its group companies unless explicitly specified and is not intended to create any rights or obligations. Any reference to service levels in this document are indicative and should not be construed to refer to any commitment by Instappy and are subject to change at any time at the sole discretion of Pulp Strategy Technologies or its group companies as it case maybe. The use of any information set out herein is entirely at the recipients own risk and discretion. The “Instappy" logo is a trade mark and property of Pulp Strategy Technologies Pvt Ltd. Misuse of any intellectual property or any other content displayed herein is strictly prohibited. Images used herein are for the purposes of illustration only.
     </td>
    </tr>
    <tr width="100%" align="center">
    	<td style="padding-top:120px" width="100%" align="center"><img src="{base_url}edm/images/footer.png" style="display:block; text-align: center;width: 100%"></td>
    </tr>
</table>
</body>
</html>