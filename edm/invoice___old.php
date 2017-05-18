<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<table cellpadding="0" cellspacing="0" width="650" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; border:1px solid #7c7c7c;">
	<tr>
    	<td><img src="{base_url}edm/images/head.png" style="display:block;"></td>
    </tr>
    <tr>
    	<td style="background:#7a7a7a; padding:10px 35px;">
        	<table cellpadding="0" cellspacing="0" width="100%">
            	<tr>
                	<td style="padding:10px 0; color:#fff;">
                    	<p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:20px; color:#fff">To,</p>
                        <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#fff; margin-bottom:10px; margin-left:15px; padding-bottom:4px; padding-left:10px;">{customer_name}</p>
                        <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#fff; margin-bottom:10px; margin-left:15px; padding-bottom:4px; padding-left:10px;">{address},</p>
                        <p style="margin:0; font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#fff; margin-bottom:10px; margin-left:15px; padding-bottom:4px; padding-left:10px;">{state}</p>
                    </td>
                    <td align="right" style=" padding:0; width:176px; text-align:right;">
                    	<table cellpadding="0" cellspacing="0" width="100%" style="text-align:left; background:#fff;">
                        	<tr>
                            	<td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#323232; padding:10px 20px 0;">Order Number:</td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#323232; padding:5px 20px 0;">{order_no}</td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#323232; padding:5px 20px;"><hr></td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#323232; padding:5px 20px 5px;">Customer ID:</td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#323232; padding:5px 20px 0px;">{custid}</td>
                            </tr>
                            <tr>
                                <td style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#323232; padding:5px 20px;"><hr></td>
                            </tr>
                            <tr>
                            	<td><a href="https://www.surveymonkey.com/r/instappy"><img src="{base_url}edm/images/survey_btn.png" style="display:block;"></a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td style="padding:0 35px;">
        	<p style="margin-top:30px;">Your Recent Order</p>
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
                	<td style="font-family:Arial, Helvetica, sans-serif; padding:10px 0; font-size:15px; border-right:1px solid #333; text-align:left; color:#323232; padding:10px 0;">Rs. {discount}</td>
                </tr>
                <tr>
                	<td style="border-left:1px solid #333; padding:10px 0;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; text-align:right; padding:10px 0; color:#323232;">Subtotal:</td>
                	<td style="padding:10px 0;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; border-right:1px solid #333; text-align:left; color:#323232; padding:10px 0;">Rs. {subtotal}</td>
                </tr>
                <tr>
                	<td style="border-left:1px solid #333; padding:10px 0;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; padding:10px 0; font-size:15px; text-align:right; padding:10px 0; color:#323232;">Tax (14%):</td>
                	<td style="padding:10px 0;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; padding:10px 0; font-size:15px; border-right:1px solid #333; text-align:left; color:#323232; padding:10px 0;">Rs. {tax}</td>
                </tr>
                <tr>
                	<td style="border-left:1px solid #333; padding:10px 0; border-bottom:1px solid #333; border-top:1px solid #dbdbdb;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; text-align:right; padding:10px 0; border-bottom:1px solid #333; color:#323232; border-top:1px solid #dbdbdb;">Order Total:</td>
                	<td style="border-bottom:1px solid #333; padding:10px 0; border-top:1px solid #dbdbdb;"></td>
                	<td style="font-family:Arial, Helvetica, sans-serif; font-size:15px; border-bottom:1px solid #333; border-right:1px solid #333; border-top:1px solid #dbdbdb; color:#323232; text-align:left; padding:10px 0;">Rs. {order_total}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#323232; line-height:12px; padding:40px 35px;"><span style="color:#e93639; font-weight:bolder;">NOTE:</span> Unless you have specifically selected the manual renewal option, your purchase includes enrollment in our automatic renewal service. This keeps your products up and running by automatically charging the then-current renewal fees to your payment method on file just before they're set to expire, with no further action on your part. You may cancel this service at any time by turning off the auto-renewal feature in your Instappy account.</td>
    </tr>
    <tr>
    	<td style="padding:0 35px;">
        	<img src="{base_url}edm/images/plan_head.png" style="display:block;">
            <table cellpadding="0" cellspacing="0" style="background:#7a7a7a;" width="100%">
            	<tr>
                	<td style="padding:25px 25px 0;"><img src="{base_url}edm/images/plan1.png"><img src="{base_url}edm/images/devider.png"><img src="{base_url}edm/images/plan2.png"></td>
                </tr>
                <tr>
                	<td style="padding:13px 25px 0;"><img src="{base_url}edm/images/plan3.png"></td>
                </tr>
                <tr>
                	<td style="padding:13px 25px 0;"><img src="{base_url}edm/images/plan4.png"></td>
                </tr>
                <tr>
                	<td style="padding:13px 25px 15px;"><img src="{base_url}edm/images/plan5.png"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#323232; line-height:12px; padding:40px 35px 10px 35px;">*Not applicable to ICANN fees, taxes, transfers, premium domains, premium templates, Search Engine Visibility advertising budget, gift cards or Trademark Holders/Priority Pre-registration or pre-registration fees. Cannot be used in conjunction with any other offer, sale, discount or promotion. After the initial purchase term, discounted products will renew at the then-current renewal list price. Offer good towards new product purchases only and cannot be used on product renewals.</td>
    </tr>
    <tr>
		<td style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#323232; line-height:12px; padding:0px 35px 10px 35px;">Prices are current as of <?php echo date('d-m-Y');?>, and may be changed without notice.</td>
    </tr>
    <tr>
		<td style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#323232; line-height:12px; padding:0px 35px 10px 35px;">By using these products, you agree that you are bound by the Universal Terms of Service and Privacy Policy.</td>
    </tr>
    <tr>
		<td style="font-family:Arial, Helvetica, sans-serif; font-size:10px; color:#323232; line-height:12px; padding:0px 35px 10px 35px;">Please do not reply to this email. Emails sent to this address will not be answered.</td>
    </tr>
    <tr >
    	<td style="padding-top:120px"><img src="{base_url}edm/images/footer.png" style="display:block;"></td>
    </tr>
</table>
</body>
</html>
