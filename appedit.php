<?php
require_once('includes/header.php');
require_once('includes/leftbar.php');
require_once('modules/myapp/myapps.php');
$apps = new MyApps();
require_once('modules/pricing/pricing.php');
$mypricing = new mypricing(); 
require_once('modules/formdata/formdata.php');
$formdata = new formdata();
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

if ((!empty($_SESSION['token'])) || (!empty($_SESSION['appid'])) || (!empty($_SESSION['catid'])) || (!empty($_SESSION['themeid']))) {
	unset($_SESSION['token']);
	unset($_SESSION['appid']);
	unset($_SESSION['catid']);
	unset($_SESSION['themeid']);

    $token = md5(rand(1000, 9999)); //you can use any encryption
    $_SESSION['token'] = $token;
    $appidEdit = isset($_GET['appid']) ? $_GET['appid'] : '';
    $_SESSION['appid'] = $appidEdit;
} else {
    $token = md5(rand(1000, 9999)); //you can use any encryption
    $_SESSION['token'] = $token;
    $appidEdit =  isset($_GET['appid']) ? $_GET['appid'] : '';
    $_SESSION['appid'] = $appidEdit;
}


$timezone = $apps->get_all_timezones();

$notifications = $apps->get_all_notifications($appidEdit);
$app_id=isset($_GET['appid']) ? $_GET['appid'] : '';

foreach ($timezone as $key => $valtimez) {
	if ($valtimez['id'] == '91') {
		$opt .= addslashes("<option value='" . $valtimez['id'] . "' selected>" . $valtimez['timezone'] .' - '. $valtimez['name']. "</option>");
		$opt1 .= "<option value='" . $valtimez['id'] . "' selected>" . $valtimez['timezone'] .' - '. $valtimez['name']. "</option>";
	} else {
		$opt .= addslashes("<option value='" . $valtimez['id'] . "'>" . $valtimez['timezone'] .' - '. $valtimez['name']. "</option>");
		$opt1 .= "<option value='" . $valtimez['id'] . "'>" . $valtimez['timezone'] .' - '. $valtimez['name']. "</option>";
	}
}
$apppublished = $apps->check_publish_app(isset($_GET['appid']) ? $_GET['appid'] : '');
$app_details = $apps->app_details(isset($_GET['appid']) ? $_GET['appid'] : '');
?>
<style>
	body{
		background:#fff;
	}

</style>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<?php 
$appType = $apps->get_app_type(isset($_GET['appid']) ? $_GET['appid'] : '');
$appVersion = $apps->get_app_version(isset($_GET['appid']) ? $_GET['appid'] : '');

?>
<section class="main">
	<section class="right_main">
		<?php if ($apppublished == 1) { ?>
			<?php 
			$app_part=$apps->check_app_part_payment($app_id);
			$last_payment_details=$apps->last_done_payment($app_id);			
			if($app_part->id>0 && count($last_payment_details->id)>0){
				$order_details=$apps->order_details($app_part->master_payment_id);
				$amount=round($app_part->part_amount,2) + round($app_part->part_amount * (14.5/100),2);
				$order_id=$order_details->order_id;
				$_SESSION['totalPrice']=$amount;
				$_SESSION['orderid']=$order_id;
				?>
				
				<div class="expire-bar">
					<div class="expire-content">
						<p>Due date for next payment: <span><?php
							$last_payment_details=$apps->last_done_payment($app_id);
							$date = new DateTime($last_payment_details->next_paymentdate);
							//$date->modify('+15 days');
							echo $date->format('d/m/Y');
						//date('d/m/Y',strtotime($app_part->next_paymentdate));?></span></p>
						</div>
						<div class="expire-button">
							<a  ia-track="IA10100068" href="javascript:void(0);"  onclick="paypalForm()" class="renew-app">Renew App</a>
						</div>
						<div class="expire-cross"><a ia-track="IA10100069" href="javascript:void(0);"><img alt="" src="image/expire-cross.png"></a></div>
						<div class="clearfix"></div>
					</div>
					<?php }
					else{
					//echo $back->format('d/m/Y');
						$paln_date= date('d-m-Y',strtotime($app_details->plan_expiry_date));

						$old_date= date('d-m-Y',strtotime($app_details->plan_expiry_date.'-30 days'));

						$curr_date= date('d-m-Y');

						$d2=date('d-m-Y',strtotime('today + 30 days'));


						if((strtotime($curr_date) <= strtotime($paln_date)) && (strtotime($paln_date)<=strtotime($d2)) ) 
						{
							?>

							<div class="expire-bar">
								<div class="expire-content">
									<p>Your app is about to expire:  <span><?php				

								//$date->modify('+15 days');
										echo $paln_date;
							//date('d/m/Y',strtotime($app_part->next_paymentdate));?></span></p>
									</div>
									<input type="hidden" id="renew_app_id" value="<?php echo $app_details->id;?>">
									<input type="hidden" id="app_platform" value="<?php echo $app_details->platform;?>" />
									<input type="hidden" id="app_name" value="<?php echo $app_details->summary;?>">				

									<div class="expire-button">
										<a ia-track="IA10100068" href="javascript:void(0);"  onclick="reneueaddtocart();" class="renew-app">Renew App</a>
									</div>
									<div class="expire-cross"><a ia-track="IA10100069" href="javascript:void();"><img alt="" src="image/expire-cross.png"></a></div>
									<div class="clearfix"></div>
								</div>
								<?php	
							}
							else{					

								if(strtotime($curr_date) >=strtotime($paln_date)){
									?>
									<div class="expire-bar">
										<div class="expire-content">
											<p>Your app has expired:  <span><?php
									//$date->modify('+15 days');
												echo $paln_date;
								//date('d/m/Y',strtotime($app_part->next_paymentdate));?></span></p>
											</div>
											<input type="hidden" id="renew_app_id" value="<?php echo $app_details->id;?>">
											<input type="hidden" id="app_platform" value="<?php echo $app_details->platform;?>" />
											<input type="hidden" id="app_name" value="<?php echo $app_details->summary;?>">				

											<div class="expire-button">
												<a ia-track="IA10100068" href="javascript:void(0);"  onclick="reneueaddtocart();" class="renew-app">Renew App</a>
											</div>
											<div class="expire-cross"><a ia-track="IA10100069" href="javascript:void(0);"><img alt="" src="image/expire-cross.png"></a></div>
											<div class="clearfix"></div>
										</div>
										<?php
									}
								}
							}
						}
						?>
						<div class="right_inner">
							<div class="myapp_name_disc">

<?php if ($apppublished == 1) { ?>
<div class="rightButton" style="width:200px;"><a ia-track="IA10100070" href="publish_android.php">Prepare Store Listing</a></div>
<?php } ?>
<div class="rightButton"><a  ia-track="IA10100071" href="support.php">Support</a></div>
<?php
$appHaveSplashIconCheck = $apps->appHaveSplashIconCheck($_SESSION['custid'], $appidEdit);
if ($appHaveSplashIconCheck['splashscreen_icon_check'] == 1) {
if(empty($_SESSION['cust_reseller_id'])){
if (($appType == 1)||($appType == 5)) {
?>
<div class="rightButton"><a ia-track="IA10100072" href="app-complete.php">Publish</a></div>
<?php } else { ?>
<div class="rightButton"><a ia-track="IA10100072" href="app-complete-retail.php?app_id=<?php echo isset($_GET['appid']) ? $_GET['appid'] : ''; ?>">Publish</a></div>
<?php
}
}
}
?>
<div class="myapp_name">
<h1> <?php
$appName = $apps->app_name(isset($_GET['appid']) ? $_GET['appid'] : '');
if ($appName['summary'] != '') {
echo $appName['summary'];
}
?></h1>
<div class="apps_preview_edit" style="float: left;">
<div class="apps_preview_edit_left">
<?php
$publishedappImage = $apps->publish_app_img(isset($_GET['appid']) ? $_GET['appid'] : '');
if ($publishedappImage != '') {
echo '<img src="' . $publishedappImage . '">';
} else {
echo '<img src="image/payment_app_icon.png">';
}
?>

													</div>
													<div class="apps_preview_edit_right">

														<p>Created On - <?php
															$createddate = $appName['created'];
															echo date("F j, Y", strtotime("$createddate"));
															?></p>
															<p>Last Updated Date  <?php
																$updateddate = $appName['updated'];
																if ($updateddate != '') {
																	echo " - ". date("F j, Y", strtotime("$updateddate"));
																}
																?></p>
                            <p><strong><?php 
										$jumpto = $appName['jump_to'];
			$jumptoapp = $appName['jump_to_app_id'];

if($jumpto == 1 && $jumptoapp != 0)
						{
							
							echo $paltform = 'Content and M-Store';
						}
						else
						{
							
							echo $appName['category_name']; 
						}?>
																	<?php if ($apppublished == 1) { ?>
																		<?php
																		$plantype = $mypricing->getplan($appName['plan_id']);
																		echo ' - ' . $plantype['plan_name'];
																		?>
																		<?php } ?>
																	</strong></p>
																	<?php if ($appType == 1 || $appType == 5) { ?>
																		<a  ia-track="IA10100073" href="javascript:void(0);" id="previewbutton">Preview</a>
																		<a ia-track="IA10100074" href="javascript:void(0);" id="editbutton">Edit</a>
																		<?php } ?>
																		<?php if ($appType == 2 || $appType == 3) {
																			if($appVersion != '1.0')
																			{
																				$targetUrl='catalogue_app.php';  
																			}
																			else
																			{
																				$targetUrl='catalogue-app.php'; 
																			}
																			?>
																			<a  ia-track="IA10100074"  href="<?php echo $targetUrl; ?>?appid=<?php echo isset($_GET['appid']) ? $_GET['appid'] : ''; ?>" >Edit</a>
																			<?php } ?>
																		</div>
																		<div class="clear"></div>
																	</div>
																	<div class="up_qr_code_box">

																		<div class="clear"></div>
																	</div>
																	<div class="clear"></div>
																</div>
																<?php
																if ($apppublished == 1) {
																	?>
																	<div class="myapp_qrcode">
																		<div class="generate_qrcode">
																			<div class="generate_qrcode_left">
																				<label>Platform :</label>
																			</div>
																			<div class="generate_qrcode_right">
																				<select class="my-select" id="platform">
																					<option value="1" data-img-src="image/android_icon.png">Android</option>
																					<option value="2" data-img-src="image/windows_icon.png">iOS</option>
																				 <option value="3" data-img-src="image/apple_icon.png">Wizard</option>
																				</select>
																				<span class="define_platform">Define platform for which you want the QR Code to redirect to.</span>
																				<div class="clear"></div>
																			</div>
																			<div class="generate_qrcode_left">
																				<label>App Url:</label>
																			</div>
																			<div class="generate_qrcode_right">
																				<input type="text" class='appUrl' value="">
																				<span class="define_platform">Your application's publicly accessable home page, where user can go to download, make use of, or find out more infomation about your application. This fully-qualified URL is used in the source attribute for tweets created by your application and will be shown in user-facing authorization screens.</span>
																				<div class="clear"></div>
																			</div>
																			<div class="generate_btn">
																				<a ia-track="IA10100075" href="javascript:void(0)" id="generateQR">Generate QR Code</a>
																			</div>
																			<div class="clear"></div>
																		</div>

																		<div class="get_qrcode">
																			<a ia-track="IA10100076" href="javascript:void(0);" >Get QR Code</a>
																			<div class="clear"></div>
																		</div>
																	</div>
																	<?php
																}
																?>

															</div>



															<div class="clear"></div>
															<?php 
															$part_payment_details=$apps->all_partpayment_details($app_id);	
															if($part_payment_details[0]->master_payment_id>0)
																$order_details=$apps->order_details($part_payment_details[0]->master_payment_id);				
															if(count($part_payment_details[0]->id)>0 && count($last_payment_details->id)>0){			?>
																<!-- start pay details block -->				
																<div class="pay-details-block">

																	<div class="pay-header">
																		<div class="pay-heading">
																			<h2>Payment Details</h2>
																			<span>Custom App - Advance Packages</span>
																		</div>
																		<div class="pay-content">
																			<p>Please find the payment details below.</p>
																		</div>                
																	</div>

																	<div class="pay-body">
																		<div class="pay-table">
																			<ul>
																				<li class="paid">
																					<span>Part Payment</span>
																					<span>Amount <?php if($order_details->currency_type_id==1) echo "(RS)"; else echo "($)";?></span>
																					<span>Recieved Date</span>
																					<span>Due Date</span>
																					<span>Status</span>
																				</li>
																				<?php 
																				$ii=1;
																				$total_amount1=0;										
																				$total_amount2=0;										
																				$total_amount3=0;	
																				foreach($part_payment_details as $val){										
																					if($val->payment_done==1){										
																						$date = new DateTime($val->transaction_date);					
																						echo '<li class="paid">
																						<span>Installment '.$ii.'</span>
																						<span>'.$val->part_amount.'</span>
																						<span>'.$date->format('d/m/Y').'</span>
																						<span>---</span>
																						<span>Amount Paid</span>
																					</li>';
																					$total_amount1+=round($val->part_amount,2);
																				}
																				else if($val->payment_done==0 && $app_part->id==$val->id ){	
																					$date = new DateTime($val->paymentdate);	
																					echo '<li class="selected">
																					<span>Installment '.$ii.'</span>
																					<span>'.$val->part_amount.'</span>
																					<span></span>
																					<span>'.$date->format('d/m/Y').'</span>
																					<span><a ia-track="IA10100077" class="pay-now"  href="javascript:void(0);" onclick="paypalForm()">Pay Now</a></span>
																				</li>';
																				$total_amount2+=round($val->part_amount,2);

																			}
																			else{
																				$date = new DateTime($val->paymentdate);	
																				echo ' <li class="unpaid">
																				<span>Installment '.$ii.'</span>
																				<span>'.$val->part_amount.'</span>
																				<span>---</span>
																				<span>'.$date->format('d/m/Y').'</span>
																				<span>Amount Due</span>
																			</li>';
																			$total_amount3+=round($val->part_amount,2);
																		}

																		$ii++;
																	}
																	?>
																</ul>
																<div class="pay-total">Total Amount <?php if($order_details->currency_type_id==1) echo "(RS)"; else echo "($)";?> : <cite class="pay-amount"><?php echo $total_amount1+$total_amount2+$total_amount3;?></cite></div>
																<div class="non-refund"><p>* Custom Plan is non refundable</p></div>
																<div class="non-refund"><p>* Taxes extra as applicable</p></div>
															</div>
														</div>
														<?php					
														if($app_part->id>0){
															?>
															<div class="due-date">Due date for next payment: <cite class="date"><?php 
																$date = new DateTime($last_payment_details->next_paymentdate);
							//$date->modify('+15 days');
																echo $date->format('d/m/Y');?></cite></div>
																<?php }?>
															</div>
															<!-- end pay details block -->
															<?php }?>

															<?php if ($apppublished == 1) { ?>
																<!-- start download user data -->
																<form action="export-app-details.php" id="user_user_data" method="post">
																	<div class="download-data-block">

																		<a ia-track="IA10100078" class="download-data-cl-img" href="#"><img src="images/download-data-cl.png" alt=""></a>

																		<div class="download-data-date">
																			<label>Start Date</label>
																			<input class="data-date" type="text" name="startdate1" value="" placeholder="Select Date">

<label>End Date</label>
<input class="data-date" type="text" name="enddate1" value="" placeholder="Select Date">
</div>
<input type="hidden" name="id" value="<?php echo $_REQUEST['appid'];?>">
<div class="rightButton"><a ia-track="IA10100079" class="downloaduserdata" href="javascript:void(0);" onclick="downloaduserdata()">Download User Data</a></div>

</div>
</form>
<!-- end download user data -->

																<?php


																$fromCount = count($formdata->showuserform($_REQUEST['appid'])); 
																if($fromCount != 0){


																	?>
																	<!-- start download form data -->
																	<form action="export-form-details.php" id="user_form_data" method="post">
																		<div class="download-data-block">

																			<a ia-track="IA10100078" class="download-data-cl-img" href="#"><img src="images/download-data-cl.png" alt=""></a>

																			<div class="download-data-date">
																				<label>Select Form</label>
																				<select name="formname" style="border: 1px solid #b9b9b9;border-radius: 5px;color: #ccc;font-size: 13px;padding: 4px 0;width: 190px;" class="formname">
																					<option value="0"> Select Form Screen</option>
																					<?php 
																					for($i=0;$i<$fromCount;$i++){
																						$formResult = $formdata->showuserform($_REQUEST['appid']);
																						?>
																						<option value="<?php echo $formResult[$i]['id']?>"><?php echo $formResult[$i]['title']?></option>
																						<?php
																					}

																					?>
																				</select>
																				<label>Start Date</label>
																				<input class="data-date" name="startdate"  type="text" value="" placeholder="Select Date">

																				<label>End Date</label>
																				<input class="data-date" name="enddate"  type="text" value="" placeholder="Select Date">

																				<input type="hidden" name="id" value="<?php echo $_REQUEST['appid'];?>">
																			</div>

<div class="rightButton"><a ia-track="IA10100080" class="downloadformdata" href="javascript:void(0);" onclick="downloadformdata()">Download Form Data</a></div>

																		</div>
																	</form>
																	<!-- end download form data -->
																	<?php }}?>


																	<?php
//$apppublished = $apps->check_publish_app(isset($_GET['appid']) ? $_GET['appid'] : '');
																	if ($apppublished == 1) {
																		?>
																		<div class="ios_notification">
																			<form name="ios_notification_form" id="ios_notification_form" action="" method="post" enctype="multipart/form-data">
																				<div class="ios_notification_head">
																					<h2>iOS Push Notification</h2>
																					<p>Apple Push Notification service (APNs) is available to apps distributed through the iOS App Store or Mac App Store, as well as to enterprise apps. Your app must be provisioned and code signed to use app services.<br /><br />To use Apple Push Notification you need to create an APP ID and a certificate for that APP. For further information please click <a href="http://quickblox.com/developers/How_to_create_APNS_certificates" target="_blank">here</a>. For support please contact our <a href="support.php">support</a> team. </p>
																					<a class="read_more_app">Read More...</a>
																					<div class="clear"></div>
																				</div>
																				<div class="ios_notification_body">
																					<h3>Please upload the certificate file for APNs (.ppk)<br /><a href="http://quickblox.com/developers/How_to_create_APNS_certificates" target="_blank">Learn more</a></h3>
																					<input type="text" disabled="disabled" class="uploaded_file_name" value="<?php echo isset($appName['ioscertificate']) ? $appName['ioscertificate'] : ''; ?>">
																					<input type="hidden" name="check_certificate" id="check_certificate" value="<?php echo isset($appName['ioscertificate']) ? $appName['ioscertificate'] : ''; ?>">

<a ia-track="IA10100084" href="#" class="browse_btn">Browse</a>
<input type="file" class="browse_input_btn" name="ios_file">

<div class="ios-video">
<a href="https://www.youtube.com/watch?v=_TDuXNE_i_o" target="_blank"><span>View Tutorial</span></a>
</div>

<div class="clearfix"></div>
<h3 class="phrase">Passphrase for Certificate</h3>
<input type="text" class="uploaded_file_phrase" maxlength="50" name="pass_phrase" value="<?php echo isset($appName['ios_passphrase']) ? $appName['ios_passphrase'] : ''; ?>"/>
<br/><br/><br/>
<p id="error_ios"></p>
</div>

<input type="hidden" name="app_id"  value="<?php echo isset($_GET['appid']) ? $_GET['appid'] : ''; ?>">
<input type="hidden" name="type"  value="ios_notification_ppk_file">
<input type="submit" name="submit" id="ios_save" value="Save">
</form>
</div>
<div class="schedule_notification">
<div class="schedule_new">
<h2>Create Push Notification</h2>
<p>Notifications are a great way to increase your engagement and your revenue. You can highlight new additions, drive traffic to offers or simply send a thank you to your customers. Plan your notifications well. The best notifications are simple clear and between 3-4 lines only. Your consumers device may not display more than 4 lines on a locked screen. You can add a URL so that when they click they can land on the screen with details. Remember you can send unlimited notifications but its best to limit to a maximum of 1 notification a week. No one likes to be spammed and spamming can result in the user deleting your app. </p>
<a class="read_more">Read More...</a>
<div class="clear"></div>
<a ia-track="IA10100085" href="javascript:void(0)" class="right_btn" id="scheduleNew">Create New</a>
</div>
<?php
if (count($notifications) > 0) {
$x = 0;
foreach ($notifications as $val) {
$x++;
?>

																					<div class="sche_noti_body">
																						<div class="notification_title">
																							<h3><?php echo isset($val['title']) ? $val['title'] : 'Title of the Push Notification'; ?></h3>
																							<p class="title"><span>Status:</span> <?php
																								if ($val['is_delivered'] == 1)
																									echo "Delivered";
																								else
																									echo "Pending";
																								?>, Schedule Date: <?php
																								$scheduledate = '';
																								$valtimezone = '';
																								$db_date = $date5 = date('Y-m-d h:i A', strtotime($val['schedule_date']));
																								foreach ($timezone as $key => $valtimez) {
																									if ($val['timezoneid'] == $valtimez['id']) {
																										$timezonesel .="<option value='" . $valtimez['id'] . "' selected>" . $valtimez['timezone'] .' - '. $valtimez['name']."</option>";
																										$valtimezone = $valtimez['timezone'];
																										$scheduledate = date_create($db_date, new DateTimeZone('UTC'))->setTimezone(new DateTimeZone($valtimezone))->format('d-m-Y h:i A');
																									} else {
																										$timezonesel .="<option value='" . $valtimez['id'] . "'>" . $valtimez['timezone'] .' - '. $valtimez['name']. "</option>";
																									}
																								}
																								echo isset($scheduledate) ? date('d-m-Y h:00 A', strtotime($scheduledate)) : '';
																								?> | Time Zone - <?php echo $valtimezone;?></p>
																								<p></p>
																								<a ia-track="IA10100086" href="javascript:void(0)" class="right_btn1 createNotification">Edit</a>
																								<a ia-track="IA10100087" href="javascript:void(0)" class="right_btn deleteNotification">Delete</a>
																							</div>
																							<div class="create_notification">
																								<div class="create_notification_text">
																									<h4>Create New <span>(You can send unlimited number of notifications to your app users, but you are allowed to schedule (by date and time for the future) a maximum of 5 notifications at one time. Remember to check all fields, best practices and spelling check before you hit send. Once it a notification goes out its can&apos;t be recalled. You can edit a notification any time before its sent)</span></h4>
																									<a class="read_more">Read More...</a>
																									<div class="clear"></div>
																								</div>

																								<form class="create_form">
																									<div class="two_input">
																										<div class="input_list">
																											<div class="input_label">
																												<label>Schedule :</label>
																											</div>
																											<div class="input_box">                                                   
																												<input type="text" name="date" class="schdule_date" value="<?php echo isset($scheduledate) ? date('d-m-Y', strtotime($scheduledate)) : ''; ?>">
																											</div>
																										</div>
																										<div class="input_list time">
																											<div class="input_label">
																												<label>Schedule Time</label>
																											</div>
																											<div class="input_box">
																												<?php
																												$time12hour = date('h:00 A', strtotime($scheduledate));
																												$timeExplode1 = explode(' ', $time12hour);
																												$timeExplode = explode(':', $timeExplode1[0]);
																												$timehour = $timeExplode[0];
																												$time12 = $timeExplode1[1];
																												?>

																												<select class="time1 <?php echo $x; ?>">                                                        
																													<option value="01">1:00</option>
																													<option value="02">2:00</option>
																													<option value="03">3:00</option>
																													<option value="04">4:00</option>
																													<option value="05">5:00</option>
																													<option value="06">6:00</option>
																													<option value="07">7:00</option>
																													<option value="08">8:00</option>
																													<option value="09">9:00</option>
																													<option value="10">10:00</option>
																													<option value="11">11:00</option>
																													<option value="12">12:00</option>
																												</select>
																												<select class="time2 <?php echo $x; ?>">
																													<option value="AM">AM</option>
																													<option value="PM">PM</option>
																												</select>
																												<select class="time3">
																													<?php
																													echo $timezonesel;
																													?>

																												</select>
																												<script type="text/javascript">
																													$(".time1.<?php echo $x; ?>").val('<?php echo $timehour; ?>');
																													$(".time2.<?php echo $x; ?>").val('<?php echo $time12; ?>');

</script>
</div>
</div>
<div class="clear"></div>
</div>
<div class="clear"></div>
<div class="input_list">
<div class="input_label">
<label>Title *:</label>
</div>
<div class="input_box">
<input type="text" placeholder="Title" class="pnotification_title" value="<?php echo isset($val['title']) ? $val['title'] : ''; ?>">
</div>
</div>
<div class="clear"></div>
<div class="input_list">
<div class="input_label">
<label>Description :</label>
</div>
<div class="input_box">
<textarea class="pnotification_desc"><?php echo isset($val['message']) ? $val['message'] : ''; ?></textarea>
</div>
</div>
<div class="notification_msg_left">Use notifications responsibly. Sending too many notifications, incorrect copy or sending confusing messages can lead to spam and result in the user disallowing notifications or even deleting your app. All the best</div>
<div class="notification_btns">
<a a-track="IA10100088" href="#" class="not-btn">Send Now</a>
<a a-track="IA10100089" href="#" class="schdule_not">Schedule for Later</a>
<p class="notification_msg">(Send now will ignore the Schedule time and Date)</p>
</div>
<input type="hidden" value="<?php echo isset($val['id']) ? $val['id'] : ''; ?>" class="current_id">
</form>
</div>
</div>
<?php
}
} else {
?>

<div class="sche_noti_body">
<div class="notification_title">
<h3>Title of the Notification</h3>
<p class="title"><span>Status:</span>, Schedule Date: </p>
<a ia-track="IA10100086" href="javascript:void(0)" class="right_btn createNotification">Edit</a>
<a ia-track="IA10100087" href="javascript:void(0)" class="right_btn deleteNotification" style="display:none;">Delete</a>
</div>
<div class="create_notification">
<div class="create_notification_text">
<h4>Create New <span>(You can send unlimited number of notifications to your app users, but you are allowed to schedule (by date and time for the future) a maximum of 5 notifications at one time. Remember to check all fields, best practices and spelling check before you hit send. Once it a notification goes out its can’t be recalled. You can edit a notification any time before its sent)</span></h4>
<a class="read_more">Read More...</a>
<div class="clear"></div>
</div>
<form class="create_form">
<div class="two_input">
<div class="input_list">
<div class="input_label">
<label>Schedule :</label>
</div>
<div class="input_box">
<input type="text" name="date" class="schdule_date" >
</div>
</div>
<div class="input_list time">
<div class="input_label">
<label>Schedule Time</label>
</div>
<div class="input_box">
<select class="time1">
<option value="01">1:00</option>
<option value="02">2:00</option>
<option value="03">3:00</option>
<option value="04">4:00</option>
<option value="05">5:00</option>
<option value="06">6:00</option>
<option value="07">7:00</option>
<option value="08">8:00</option>
<option value="09">9:00</option>
<option value="10">10:00</option>
<option value="11">11:00</option>
<option value="12">12:00</option>
</select>
<select class="time2">
<option value="am">AM</option>
<option value="pm">PM</option>
</select>
<select class="time3">
<?php echo $opt1; ?>
</select>
</div>
</div>
<div class="clear"></div>
</div>
<div class="clear"></div>
<div class="input_list">
<div class="input_label">
<label>Title *:</label>
</div>
<div class="input_box">
<input type="text" placeholder="Title" class="pnotification_title" >
</div>
</div>
<div class="clear"></div>
<div class="input_list">
<div class="input_label">
<label>Description :</label>
</div>
<div class="input_box">
<textarea class="pnotification_desc"></textarea>
</div>
</div>
<div class="notification_msg_left">Use notifications responsibly. Sending too many notifications, incorrect copy or sending confusing messages can lead to spam and result in the user disallowing notifications or even deleting your app. All the best</div>
<div class="notification_btns">

																									<a ia-track="IA10100088" href="javascript:void(0)" class="not-btn">Send Now</a>
																									<a ia-track="IA10100089" href="javascript:void(0)" class="schdule_not">Schedule for Later</a>
																									<p class="notification_msg">(Send now will ignore the Schedule time and Date)</p>
																								</div>
																							</form>
																						</div>
																					</div>
																					<?php
																				}
																				?>

																			</div>

                <!--<div class="current_pack">
               <div class="notification">
                   <h1>Send Notifications:</h1>
                   <p>Send Notification to your users.</p>
                   <div class="notificationform">
                       <div class="not-label">Title :</div>
                       <div class="not-textbox"><input type="text"  id="notification_title"></div>
                       <div class="not-label">Description :</div>
                       <div class="not-textbox"><textarea id="notification_desc"></textarea></div>
                       <div class="not-btn">Send Notification</div>
                   </div>
               </div>



           </div>-->

           <?php
       }
       
       ?>
       <div class="clear"></div>
	   <?php if(empty($_SESSION['cust_reseller_id'])){?>
       <div class="current_pack">
       	<?php
       	$asopackages = $mypricing->getASOprice();
       	?>
       	<div class="promo_code">
       		<div class="marketing_package">
       			<h2 class="head"> Recommended for you :</h2>
       			<div class="halfpackage package1">

       				<?php if ($currency == 1) { ?>
       					<div class="topHead"><img src="images/superdeal1.png"></div>                                  
       					<div class="readMoreSec">
       						<?php } else { ?>                                        
       							<div class="readMoreSec" style="border-radius: 10px 10px 0 0;">
       								<?php } ?>
       								<p class="heading">App Launch ASO Plan</p>
       								<p class="subheading"><span>App Store Optimisation, is possibly the single most important piece in launching your App. ASO is the process of optimizing mobile apps to rank higher in an app store’s search results. The higher your app ranks in an app store’s search results, the more visible it is to potential customers. That increased visibility tends to translate into more traffic to your app’s page in the app store. The goal of ASO is to drive more traffic to your app’s page in the app store, so searchers can take a specific action</span>
       									<a href="javascript:void(0)" class="img-tog">Read more</a></p>
       								</div>
       								<div class="half_package_img">
       									<div class="half_package_terms">
       										<ul>
       											<li>App Name Suggestions including Primary Suggestion and Variants</li>
       											<li>App Title</li>
       											<li>App Description</li>
       											<li>Keywords (research, identification and inclusion) upto 20 key words</li>
       											<li>Category Suggestions including Major and Sub-Categories.</li>
       											<li>Icon Recommendations Based on Concepts</li>
       											<li>Screenshot Recommendations Based on App Usage and compete analysis</li>
       											<li>SEO Optimized Product Description Based on App Usage</li>
       											<li>Professionally done Report included</li>
       											<li>Duration: 2 - 4 weeks</li>
       										</ul>
       										<div class="half_package_price">
       											<?php if ($currency == 1) { ?>
       												<h2><?php echo $currencyIcon . $asopackages[0]['discounted_amount'] ?></h2>
       												<p><?php echo $currencyIcon . $asopackages[0]['actual_amount'] ?></p>
       												<?php } else { ?>
       													<h2><?php echo $currencyIcon . $asopackages[0]['price_in_usd'] ?></h2>
       													<p><?php
       														if (!empty($asopackages[0]['usddiscounted_amount'])) {
       															echo $currencyIcon . $asopackages[0]['usddiscounted_amount'];
       														}
       														?></p>
       														<?php } ?>
       														<span>One Time Package</span>
       													</div>
       												</div>
       											</div>
       											<div class="addCartButton">
       												<a  ia-track="IA10100090" href="javascript:void(0)" class="mpackage" data-mpid="1" >Add to Cart</a>

       											</div>

       										</div>

       										<div class="halfpackage package2">                                    
       											<?php if ($currency == 1) { ?>
       												<div class="topHead"><img src="images/superdeal2.png"></div>                                     
       												<div class="readMoreSec">
       													<?php } else { ?>                                        
       														<div class="readMoreSec" style="border-radius: 10px 10px 0 0;">
       															<?php } ?>
       															<p class="heading">Live ASO Plan</p>
       															<p class="subheading"><span>If your app is already on the store but you missed the app store optimisation (ASO) all is not lost. You can still optimise your app for better visibility. ASO is the process of optimizing mobile apps to rank higher in an app store’s search results. The higher your app ranks in an app store’s search results, the more visible it is to potential customers. That increased visibility tends to translate into more traffic to your app’s page in the app store. The goal of ASO is to drive more traffic to your app’s page in the app store, so searchers can take a specific action</span>
       																<a href="javascript:void(0)" class="img-tog">Read more</a></p>

       															</div>
       															<div class="half_package_img">
       																<div class="half_package_terms">
       																	<ul>
       																		<li>Support for categories and countries on App Store and Google Play</li>
       																		<li>Icon Adjustment Based on Live Art</li>
       																		<li>Screenshot Recommendations Based on App Usage</li>
       																		<li>Rewriting of Product Description Leveraging Live Description</li>
       																		<li>App Analytics (Metrics and Insight into how users use your App create success)</li>
       																		<li>Alternative App Stores Suggestion</li>
       																		<li>Suggestion on Ongoing App Store Optimization</li>
       																		<li>App Reviews recommendation</li>
       																		<li>App Analysis and action points</li>
       																		<li>Analyze Traffic, Competition and Current app ranking of keywords</li>
       																		<li>Email/Phone Call Support</li>
       																		<li>Professionlly done Report Included</li>
       																		<li>Duration: 6 weeks</li>
       																	</ul>
       																	<div class="half_package_price">
       																		<?php if ($currency == 1) { ?>
       																			<h2><?php echo $currencyIcon . $asopackages[1]['discounted_amount'] ?></h2>
       																			<p><?php echo $currencyIcon . $asopackages[1]['actual_amount'] ?></p>
       																			<?php } else { ?>
       																				<h2><?php echo $currencyIcon . $asopackages[1]['price_in_usd'] ?></h2>
       																				<p><?php
       																					if (!empty($asopackages[1]['usddiscounted_amount'])) {
       																						echo $currencyIcon . $asopackages[1]['usddiscounted_amount'];
       																					}
       																					?></p>
       																					<?php } ?>
       																					<span>One Time Package</span>
       																				</div>
       																			</div>
       																		</div>
       																		<div class="addCartButton">
       																			<a ia-track="IA10100091" href="javascript:void(0)" class="mpackage" data-mpid="2" >Add to Cart</a>

       																		</div>
       																	</div>
       																	<div class="clear"></div>
       																	<div class="fullpackage package3">                                    
       																		<?php if ($currency == 1) { ?>
       																			<div class="topHead"><img src="images/superdeal3.png"></div>
       																			<div class="half_package_img">
       																				<div class="readMoreSec">
       																					<?php } else { ?>
       																						<div class="half_package_img">
       																							<div class="readMoreSec" style="border-radius: 10px 10px 0 0;">
       																								<?php } ?>
       																								<p class="heading">App Reviews</p>
       																								<p class="subheading">When you are launching a new app, naturally you want everyone in the world to see it! App review sites are a great way of gaining visibility, credibility and traction for your apps. Drive More Reviews & Installs Instantly.</p>
       																							</div>
       																							<table cellpadding="0" cellspacing="0">
       																								<tr>
       																									<td></td>
       																									<td>Basic Plan</td>
       																									<td>Advanced Plan</td>
       																									<td>Enterprise Plan</td>
       																								</tr>
       																								<tr>
       																									<td>App Submission to relevant review sites</td>
       																									<td>50</td>
       																									<td>100</td>
       																									<td>150+</td>
       																								</tr>
       																								<tr>
       																									<td>PR Release Creation &amp; Distribution</td>
       																									<td>N</td>
       																									<td>Y</td>
       																									<td>Y</td>
       																								</tr>
       																								<tr>
       																									<td>PR Release Distribution</td>
       																									<td>N</td>
       																									<td>Y</td>
       																									<td>Y</td>
       																								</tr>
       																								<tr>
       																									<td>Guaranteed Reviews</td>
       																									<td>5</td>
       																									<td>10</td>
       																									<td>15</td>
       																								</tr>
       																								<tr>
       																									<td>Get Featured on our Blog</td>
       																									<td>N</td>
       																									<td>Y</td>
       																									<td>Y</td>
       																								</tr>
       																								<tr>
       																									<td></td>
       																									<td>
       																										<?php if ($currency == 1) { ?>
       																											<span class="main_price"><?php echo $currencyIcon . $asopackages[2]['discounted_amount'] ?></span>
       																											<span class="strike_price no_strike"><?php echo $currencyIcon . $asopackages[2]['actual_amount'] ?></span>
       																											<?php } else { ?>
       																												<span class="main_price"><?php echo $currencyIcon . $asopackages[2]['price_in_usd'] ?></span>
       																												<span class="strike_price no_strike"><?php
       																													if (!empty($asopackages[2]['usddiscounted_amount'])) {
       																														echo $currencyIcon . $asopackages[2]['usddiscounted_amount'];
       																													}
       																													?></span>
       																													<?php } ?>
       																													<span class="package_name">One Time Package</span>
       																												</td>
       																												<td>
       																													<?php if ($currency == 1) { ?>
       																														<span class="main_price"><?php echo $currencyIcon . $asopackages[3]['discounted_amount'] ?></span>
       																														<span class="strike_price"><?php echo $currencyIcon . $asopackages[3]['actual_amount'] ?></span>
       																														<?php } else { ?>
       																															<span class="main_price"><?php echo $currencyIcon . $asopackages[3]['price_in_usd'] ?></span>
       																															<span class="strike_price"><?php
       																																if (!empty($asopackages[3]['usddiscounted_amount'])) {
       																																	echo $currencyIcon . $asopackages[3]['usddiscounted_amount'];
       																																}
       																																?></span>
       																																<?php } ?>
       																																<span class="package_name">One Time Package</span>
       																															</td>
       																															<td>
       																																<?php if ($currency == 1) { ?>
       																																	<span class="main_price"><?php echo $currencyIcon . $asopackages[4]['discounted_amount'] ?></span>
       																																	<span class="strike_price"><?php echo $currencyIcon . $asopackages[4]['actual_amount'] ?></span>
       																																	<?php } else { ?>
       																																		<span class="main_price"><?php echo $currencyIcon . $asopackages[4]['price_in_usd'] ?></span>
       																																		<span class="strike_price"><?php
       																																			if (!empty($asopackages[4]['usddiscounted_amount'])) {
       																																				echo $currencyIcon . $asopackages[4]['usddiscounted_amount'];
       																																			}
       																																			?></span>
       																																			<?php } ?>
       																																			<span class="package_name">One Time Package</span>
       																																		</td>
       																																	</tr>
       																																	<tr>
       																																		<td></td>
       																																		<td>
       																																			<a ia-track="IA10100092" href="javascript:void(0);" class="mpackage" data-mpid="3" >Add to Cart</a>
       																																		</td>
       																																		<td>
       																																			<a ia-track="IA10100093" href="javascript:void(0);" class="mpackage" data-mpid="4" >Add to Cart</a>
       																																		</td>
       																																		<td>
       																																			<a ia-track="IA10100094" href="javascript:void(0);" class="mpackage" data-mpid="5" >Add to Cart</a>
       																																		</td>
       																																	</tr>
       																																</table>
       																															</div>

       																														</div>
       																														<div class="fullpackage package3">                                    
       																															<?php if ($currency == 1) { ?>
       																																<div class="topHead"><img src="images/superdeal4.png"></div>
       																																<div class="half_package_img">
       																																	<div class="readMoreSec">
       																																		<?php } else { ?>
       																																			<div class="half_package_img">
       																																				<div class="readMoreSec" style="border-radius: 10px 10px 0 0;">
       																																					<?php } ?>
       																																					<p class="heading">App Downloads</p>
       																																					<p class="subheading">Boost Your App Downloads, Target The RIGHT People So you need to target people from specific locations? No problem! We help you target and drive your app downloads from the audience of your choice.</p>

       																																				</div>
       																																				<table cellpadding="0" cellspacing="0">
       																																					<tr>
       																																						<td></td>
       																																						<td>Basic Plan</td>
       																																						<td>Advanced Plan</td>
       																																						<td>Enterprise Plan</td>
       																																					</tr>
       																																					<tr>
       																																						<td>Targetted App downloads</td>
       																																						<td>200</td>
       																																						<td>420</td>
       																																						<td>750</td>
       																																					</tr>
       																																					<tr>
       																																						<td>Guaranteed Returns</td>
       																																						<td>Y</td>
       																																						<td>Y</td>
       																																						<td>Y</td>
       																																					</tr>
       																																					<tr>
       																																						<td>Positive Google Play Store Reviews</td>
       																																						<td>5</td>
       																																						<td>10</td>
       																																						<td>15</td>
       																																					</tr>
       																																					<tr>
       																																						<td>Unique Device</td>
       																																						<td>Y</td>
       																																						<td>Y</td>
       																																						<td>Y</td>
       																																					</tr>
       																																					<tr>
       																																						<td>Professionally done Report Included</td>
       																																						<td>N</td>
       																																						<td>Y</td>
       																																						<td>Y</td>
       																																					</tr>
       																																					<tr>
       																																						<td></td>
       																																						<td>
       																																							<?php if ($currency == 1) { ?>
       																																								<span class="main_price"><?php echo $currencyIcon . $asopackages[5]['discounted_amount'] ?></span>
       																																								<span class="strike_price no_strike"><?php echo $currencyIcon . $asopackages[5]['actual_amount'] ?></span>
       																																								<?php } else { ?>
       																																									<span class="main_price"><?php echo $currencyIcon . $asopackages[5]['price_in_usd'] ?></span>
       																																									<span class="strike_price no_strike"><?php
       																																										if (!empty($asopackages[5]['usddiscounted_amount'])) {
       																																											echo $currencyIcon . $asopackages[5]['usddiscounted_amount'];
       																																										}
       																																										?></span>
       																																										<?php } ?>
       																																										<span class="package_name">One Time Package</span>
       																																									</td>
       																																									<td>
       																																										<?php if ($currency == 1) { ?>
       																																											<span class="main_price"><?php echo $currencyIcon . $asopackages[6]['discounted_amount'] ?></span>
       																																											<span class="strike_price"><?php echo $currencyIcon . $asopackages[6]['actual_amount'] ?></span>
       																																											<?php } else { ?>
       																																												<span class="main_price"><?php echo $currencyIcon . $asopackages[6]['price_in_usd'] ?></span>
       																																												<span class="strike_price"><?php
       																																													if (!empty($asopackages[6]['usddiscounted_amount'])) {
       																																														echo $currencyIcon . $asopackages[6]['usddiscounted_amount'];
       																																													}
       																																													?></span>
       																																													<?php } ?>
       																																													<span class="package_name">One Time Package</span>
       																																												</td>
       																																												<td>
       																																													<?php if ($currency == 1) { ?>
       																																														<span class="main_price"><?php echo $currencyIcon . $asopackages[7]['discounted_amount'] ?></span>
       																																														<span class="strike_price no_strike"><?php echo $currencyIcon . $asopackages[7]['actual_amount'] ?></span>
       																																														<?php } else { ?>
       																																															<span class="main_price"><?php echo $currencyIcon . $asopackages[7]['price_in_usd'] ?></span>
       																																															<span class="strike_price no_strike"><?php
       																																																if (!empty($asopackages[7]['usddiscounted_amount'])) {
       																																																	echo $currencyIcon . $asopackages[7]['usddiscounted_amount'];
       																																																}
       																																																?></span>
       																																																<?php } ?>
       																																																<span class="package_name">One Time Package</span>
       																																															</td>
       																																														</tr>
       																																														<tr>
       																																															<td></td>
       																																															<td>
       																																																<a ia-track="IA10100095" href="javascript:void(0);" class="mpackage" data-mpid="6" >Add to Cart</a>
       																																															</td>
       																																															<td>
       																																																<a ia-track="IA10100096" href="javascript:void(0);" class="mpackage" data-mpid="7" >Add to Cart</a>
       																																															</td>
       																																															<td>
       																																																<a ia-track="IA10100097" href="javascript:void(0);" class="mpackage" data-mpid="8" >Add to Cart</a>
       																																															</td>
       																																														</tr>
       																																													</table>
       																																												</div>

       																																											</div>
       																																											<div class="fullpackage package3">
       																																												<?php if ($currency == 1) { ?>
       																																													<div class="topHead"><img src="images/superdeal5.png"></div>
       																																													<div class="half_package_img">
       																																														<div class="readMoreSec">
       																																															<?php } else { ?>
       																																																<div class="half_package_img">
       																																																	<div class="readMoreSec" style="border-radius: 10px 10px 0 0;">
       																																																		<?php } ?>

       																																																		<p class="heading">Refurbishment of Content / Content Writing</p>
       																																																		<p class="subheading">Just writing is not enough, it's a science to infuse the right keywords which impact your App's indexing by Google and at the same time make your app content a joy to read. Let’s face it – not everyone can write.  Given the importance of rich and appealing content in the development of any application, it’s worth committing resources to ensure that the job is done well.</p>
       																																																	</div>
       																																																	<table cellpadding="0" cellspacing="0" class="big_table">
       																																																		<tr>
       																																																			<td></td>
       																																																			<td>Basic Plan</td>
       																																																			<td>Advanced Plan</td>
       																																																			<td>Enterprise Plan</td>
       																																																		</tr>
       																																																		<tr>
       																																																			<td>Screen Pages Copywriting</td>
       																																																			<td>25</td>
       																																																			<td>50</td>
       																																																			<td>100</td>
       																																																		</tr>
       																																																		<tr>
       																																																			<td>Rewrite Search Friendly Content (Base Content Provided by Client)</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																		</tr>
       																																																		<tr>
       																																																			<td>Keywords (research, identification and inclusion) Uptoo 20 key words</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																		</tr>
       																																																		<tr>
       																																																			<td>Word Count Up-To 500 per screen / page</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																		</tr>
       																																																		<tr>
       																																																			<td>Proof read by Experts</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																		</tr>
       																																																		<tr>
       																																																			<td>On-Time Delivery</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																		</tr>
       																																																		<tr>
       																																																			<td>Load time Optimized Images</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																		</tr>
       																																																		<tr>
       																																																			<td>Multiple Device & OS Compatibility Images</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																		</tr>
       																																																		<tr>
       																																																			<td>Images Provided by the App publisher / client </td>
       																																																			<td>35</td>
       																																																			<td>65</td>
       																																																			<td>120</td>
       																																																		</tr>
       																																																		<tr>
       																																																			<td>1 Week Updation Time</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																			<td>Y</td>
       																																																		</tr>
       																																																		<tr>
       																																																			<td></td>
       																																																			<td>
       																																																				<?php if ($currency == 1) { ?>
       																																																					<span class="main_price"><?php echo $currencyIcon . $asopackages[8]['discounted_amount'] ?></span>
       																																																					<span class="strike_price"><?php echo $currencyIcon . $asopackages[8]['actual_amount'] ?></span>
       																																																					<?php } else { ?>
       																																																						<span class="main_price"><?php echo $currencyIcon . $asopackages[8]['price_in_usd'] ?></span>
       																																																						<span class="strike_price"><?php
       																																																							if (!empty($asopackages[8]['usddiscounted_amount'])) {
       																																																								echo $currencyIcon . $asopackages[8]['usddiscounted_amount'];
       																																																							}
       																																																							?></span>
       																																																							<?php } ?>
       																																																							<span class="package_name">One Time Package</span>
       																																																						</td>
       																																																						<td>
       																																																							<?php if ($currency == 1) { ?>
       																																																								<span class="main_price"><?php echo $currencyIcon . $asopackages[9]['discounted_amount'] ?></span>
       																																																								<span class="strike_price"><?php echo $currencyIcon . $asopackages[9]['actual_amount'] ?></span>
       																																																								<?php } else { ?>
       																																																									<span class="main_price"><?php echo $currencyIcon . $asopackages[9]['price_in_usd'] ?></span>
       																																																									<span class="strike_price"><?php
       																																																										if (!empty($asopackages[9]['usddiscounted_amount'])) {
       																																																											echo $currencyIcon . $asopackages[9]['usddiscounted_amount'];
       																																																										}
       																																																										?></span>
       																																																										<?php } ?>
       																																																										<span class="package_name">One Time Package</span>
       																																																									</td>
       																																																									<td>
       																																																										<?php if ($currency == 1) { ?>
       																																																											<span class="main_price"><?php echo $currencyIcon . $asopackages[10]['discounted_amount'] ?></span>
       																																																											<span class="strike_price"><?php echo $currencyIcon . $asopackages[10]['actual_amount'] ?></span>
       																																																											<?php } else { ?>
       																																																												<span class="main_price"><?php echo $currencyIcon . $asopackages[10]['price_in_usd'] ?></span>
       																																																												<span class="strike_price"><?php
       																																																													if (!empty($asopackages[11]['usddiscounted_amount'])) {
       																																																														echo $currencyIcon . $asopackages[11]['usddiscounted_amount'];
       																																																													}
       																																																													?></span>
       																																																													<?php } ?>
       																																																													<span class="package_name">One Time Package</span>
       																																																												</td>
       																																																											</tr>
       																																																											<tr>
       																																																												<td></td>
       																																																												<td>
       																																																													<a ia-track="IA10100098" href="javascript:void(0);" class="mpackage" data-mpid="9" >Add to Cart</a>
       																																																												</td>
       																																																												<td>
       																																																													<a ia-track="IA10100099" href="javascript:void(0);" class="mpackage" data-mpid="10">Add to Cart</a>
       																																																												</td>
       																																																												<td>
       																																																													<a ia-track="IA101000100" href="javascript:void(0);" class="mpackage" data-mpid="11">Add to Cart</a>
       																																																												</td>
       																																																											</tr>
       																																																										</table>
       																																																									</div>

       																																																								</div>
       																																																								<div class="clear"></div>

       																																																							</div>


       																																																						</div>
       																																																						<!-- end promocode details-->
       																																																						<div id="ovr"></div>
       																																																						<!--<span class="clk">Click here for disclaimer</span>-->
       																																																						<span style="
       																																																						font-size: 9px;
       																																																						line-height: 14px;
       																																																						color: #9d9d9d;
       																																																						display: block;
       																																																						margin: 5px 0;
       																																																						">Referral Code / coupon code discounts apply to new product purchases only.Not applicable to transaction fees, taxes, transfers, renewals or advertising budgets. Cannot be used in conjunction with any other offer, sale, discount or promotion. After the initial purchase term, discounted products will renew at the then-current renewal list price.Offers good towards new product purchases only and cannot be used on product renewals unless specifically mentioned on the coupon / offer. Savings based on Instappy.com's regular pricing.Annual discounts available on NEW purchases only.</span>
       																																																						<div id="p_up">
       																																																							<i>X</i>
       																																																							<p>Referral Code / coupon code discounts apply to new product purchases only.Not applicable to transaction fees, taxes, transfers, renewals or advertising budgets. Cannot be used in conjunction with any other offer, sale, discount or promotion. After the initial purchase term, discounted products will renew at the then-current renewal list price.Offers good towards new product purchases only and cannot be used on product renewals unless specifically mentioned on the coupon / offer. Savings based on Instappy.com's regular pricing.Annual discounts available on NEW purchases only.</p>
       																																																							<!-- <span class="closed">Close</span>-->
       																																																						</div>
                                                <!--                        <div class="next_step">
                                                                            <a href="#" class="back_cart">< &nbsp;&nbsp;&nbsp;Back</a>
                                                                            <div class="clear"></div>
                                                                        </div>-->
                                                                    </div>

                                                                    <div class="clear"></div>
                                                                </div>
																<?php }?>
                                                            </div>   </div>


                                                        </div>
                                                    </section>
                                                </section>
                                            </section>
                                            <script>
											var download = 0;
											function pageloadQR() {
												var appid = '<?php echo $appidEdit; ?>';
												var sessiontoken = '<?php echo $token; ?>';
												var param = {'appidQRPL': appid};

												var form_data = {
													data: param, //your data being sent with ajax
													token: sessiontoken, //used token here.
													is_ajax: 1
												};

												$.ajax({
													type: "POST",
													url: 'modules/myapp/generateQRcodePL1.php',
													data: form_data,
													success: function (response)
													{
														//                alert(response);
														if (response) {
															$(".up_qr_code_box").html(response);
														} else if (response) {
															console.log(response);
														}
													},
													error: function () {
														console.log("error in ajax call");
														
													}
												});
											}
											
											$(document).ready(function () {
												$(".leftsidemenu li").removeClass("active");
												$(".leftsidemenu li.tablet").addClass("active");
												$(window).resize(function () {
													var screenWidth = $(window).width();
													if (screenWidth <= 1023) {
														$('.screen_popup').fadeIn();
														$('.popup_container').fadeIn();
													} else {
														$('.screen_popup').fadeOut();
														$('.popup_container').fadeOut();
													}
													;
												});
												$('.screen_popup input:button').on('click', function () {
													$('.screen_popup').fadeOut();
													$('.popup_container').fadeOut();
												});

												$("a.read_more_app").on('click', function (e) {
													e.stopPropagation();
													var realHeight = $(this).prev()[0].scrollHeight;
													if ($(this).prev().height() == 36) {
														$(this).prev().animate({
															height: realHeight
														})
														$(this).text("Close");
													} else {
														$(this).prev().animate({
															height: 36
														})
														$(this).text("Read More...");
													}
												})

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
																var totalclassmp = 0;
																totalclassmp = $("p[id^='mptotal_']").length;
																totalclassmp = parseFloat(totalclassmp + 1);

																window.location = "payment_details.php";
																console.log(response);
															}
														}
													});
												});

												pageloadQR();

												$(".get_qrcode a").click(function () {
													$(this).parent().css("display", "none");
													$(".qr_code_box").css("display", "none");
													$(".generate_qrcode").fadeIn();
												});
												$(".generate_btn a").click(function () {
													$(this).parent().parent().css("display", "none");
													$(".qr_code_box").fadeIn();
													$(".get_qrcode").fadeIn();
												});
												/*var rightHeight = $(window).height() - 35;
												$(".right_main").css("height", rightHeight + "px")*/


												$("#editbutton").click(function () {

													var appid = '<?php echo $appidEdit; ?>';
													var sessiontoken = '<?php echo $token; ?>';
													var param = {'appidedit': appid};

													var form_data = {
														data: param, //your data being sent with ajax
														token: sessiontoken, //used token here.                                                            
														is_ajax: 1
													};

													$.ajax({
														type: "POST",
														url: 'modules/checkapp/editapp.php',
														data: form_data,
														success: function (response)
														{
															//                    alert(response);
															if (response == 1) {
																window.location = "panel.php?app=edit";
															} else if (response != 1) {
																console.log(response);
															}
														},
														error: function () {
															console.log("error in ajax call");
															
														}
													});
												});
												$("#previewbutton").click(function () {

													var appid = '<?php echo $appidEdit; ?>';
													var sessiontoken = '<?php echo $token; ?>';
													var param = {'appidedit': appid};

													var form_data = {
														data: param, //your data being sent with ajax
														token: sessiontoken, //used token here.                                                            
														is_ajax: 1
													};

													$.ajax({
														type: "POST",
														url: 'modules/checkapp/editapp.php',
														data: form_data,
														success: function (response)
														{
															//                    alert(response);
															if (response == 1) {
																window.location = "panel.php?app=edit&page=preview";
															} else if (response != 1) {
																console.log(response);
															}
														},
														error: function () {
															console.log("error in ajax call");
															//                    alert("error in ajax call");
														}
													});
												});


												$("#generateQR").click(function () {
													$(".popup_container").css("display", "block");
													$("#page_ajax").html('<img src="images/ajax-loader.gif">');
													var platform = $("#platform option:selected").val();
													var appurl = $(".appUrl").val();
													var appid = '<?php echo $appidEdit; ?>';
													var sessiontoken = '<?php echo $token; ?>';
													var param = {'appidQR': appid, 'platform': platform};

													var form_data = {
														data: param, //your data being sent with ajax
														token: sessiontoken, //used token here.                                                            
														appurl: appurl,
														is_ajax: 1
													};

													$.ajax({
														type: "POST",
														url: 'modules/myapp/generateQRcode.php',
														data: form_data,
														success: function (response)
														{
															//                    alert(response);
															if (response) {
																pageloadQR();
																$(".popup_container").css("display", "none");
																$("#page_ajax").html('');
																//                        window.location.reload(true);
																//                        console.log(response);

															} else if (response) {
																console.log(response);
																$(".popup_container").css("display", "none");
																$("#page_ajax").html('');
															}
														},
														error: function () {
															$(".popup_container").css("display", "none");
															$("#page_ajax").html('');
															console.log("error in ajax call");
															
														}
													});
												});
											});
											</script>

                            <script>
                            	$(document).ready(function () {


                            		$('.halfpackage .img-tog').click(function () {
                            			var img_visb = $(this).parents('.halfpackage , .half_package_img .half_package_terms').find('ul');
                            			$(img_visb).toggle();
                            			var realHeight = $(this).siblings()[0].scrollHeight;
                            			if ($(this).siblings().height() == 57) {
                            				$(this).siblings().animate({
                            					height: realHeight
                            				});
                            			} else {
                            				$(this).siblings().animate({
                            					height: 57
                            				});
                            			}
                            		});
                            		$(document).on('click', '.not-btn', function () {
                            			var index = $(this).parents(".create_notification").addClass("indexclass").index(".create_notification.indexclass");
                            			var title = $(".pnotification_title").eq(index).val();
                            			var desc = $(".pnotification_desc").eq(index).val();
                            			if (title == '' || title == null) {
                            				$(".pnotification_title").eq(index).css("border", "1px solid #ff0000");
                            				return false;
                            			}
                            			else {
                            				$(".pnotification_title").eq(index).css("border", "1px solid #9d9d9d");
                            			}
                            			if (desc == '' || desc == null) {
                            				$(".pnotification_desc").eq(index).css("border", "1px solid #ff0000");
                            				return false;
                            			}
                            			else {
                            				$(".pnotification_desc").eq(index).css("border", "1px solid #9d9d9d");
                            			}
                            			$("#screenoverlay").css("display", "block");
                            			jQuery.ajax({
                            				url: 'ajax.php',
                            				type: "post",
                            				data: "title=" + title + "&desc=" + desc + "&app_id=<?php echo $appidEdit; ?>&type=notification",
                            				success: function (response) {
                            					if (response == 'success') {
                            						$("#notification_title").val('');
                            						$("#notification_desc").val('');
                            						$("#screenoverlay").css("display", "none");
                            						$(".popup_container").css("display", "block");
                            						$(".confirm_name").css("display", "block");
                            						$(".confirm_name_form p").text("Notification Sent Successfully.");
                            						setTimeout(function () {
                            							$(".popup_container").css("display", "none");
                            							$(".confirm_name").css("display", "none");
                            							$(".confirm_name_form p").text("");
                            						}, 4000);
                            					}
                            					else if (response == 'fails') {
                            						$("#notification_title").val('');
                            						$("#notification_desc").val('');
                            						$("#screenoverlay").css("display", "none");
                            						$(".popup_container").css("display", "block");
                            						$(".confirm_name").css("display", "block");
                            						$(".confirm_name_form p").text("Please Try Again.");
                            						setTimeout(function () {
                            							$(".popup_container").css("display", "none");
                            							$(".confirm_name").css("display", "none");
                            							$(".confirm_name_form p").text("");
                            						}, 4000);

                            					}
                            					else if (response == 'backtime') {
                            						$("#notification_title").val('');
                            						$("#notification_desc").val('');
                            						$("#screenoverlay").css("display", "none");
                            						$(".popup_container").css("display", "block");
                            						$(".confirm_name").css("display", "block");
                            						$(".confirm_name_form p").text("Please select time greater than current time.");
                            						setTimeout(function () {
                            							$(".popup_container").css("display", "none");
                            							$(".confirm_name").css("display", "none");
                            							$(".confirm_name_form p").text("");
                            						}, 4000);

                            					}
                            					else {
                            						$("#notification_title").val('');
                            						$("#notification_desc").val('');
                            						$("#screenoverlay").css("display", "none");
                            						$(".popup_container").css("display", "block");
                            						$(".confirm_name").css("display", "block");
                            						$(".confirm_name_form p").text("Oops Something went wrong.Try again later.");
                            						setTimeout(function () {
                            							$(".popup_container").css("display", "none");
                            							$(".confirm_name").css("display", "none");
                            							$(".confirm_name_form p").text("");
                            						}, 4000);
                            					}
                            				},
                            				error: function () {
                            					$("#notification_title").val('');
                            					$("#notification_desc").val('');
                            					$(".confirm_name_form p").text("There is error while submit.");
                            					setTimeout(function () {
                            						$(".popup_container").css("display", "none");
                            						$(".confirm_name").css("display", "none");
                            						$(".confirm_name_form p").text("");
                            					}, 4000);

                            				}
                            			});

});

$(document).on('click', '.schdule_not', function () {
	var index = $(this).parents(".create_notification").addClass("indexclass").index(".create_notification.indexclass");
	var schdule_date = $(".schdule_date").eq(index).val();
	var time1 = $(".time1").eq(index).val();
	var time2 = $(".time2").eq(index).val();
	var time3 = $(".time3").eq(index).val();
	var current_id = $(".current_id").eq(index).val();
	if (current_id == '' || current_id == undefined) {
		current_id = 0;

	}
	var title = $(".pnotification_title").eq(index).val();
	var desc = $(".pnotification_desc").eq(index).val();
	if (schdule_date == '' || schdule_date == null) {
		$(".schdule_date").eq(index).css("border", "1px solid #ff0000");
		return false;
	}
	else {
		$(".schdule_date").eq(index).css("border", "1px solid #9d9d9d");
	}

	if (title == '' || title == null) {
		$(".pnotification_title").eq(index).css("border", "1px solid #ff0000");
		return false;
	}
	else {
		$(".pnotification_title").eq(index).css("border", "1px solid #9d9d9d");
	}
	if (desc == '' || desc == null) {
		$(".pnotification_desc").eq(index).css("border", "1px solid #ff0000");
		return false;
	}
	else {
		$(".pnotification_desc").eq(index).css("border", "1px solid #9d9d9d");
	}

	$("#screenoverlay").css("display", "block");
	jQuery.ajax({
		url: 'ajax.php',
		type: "post",
		data: "title=" + title + "&desc=" + desc + "&app_id=<?php echo $appidEdit; ?>&date=" + schdule_date + "&time1=" + time1 + "&time2=" + time2 + "&time3=" + time3 + "&current_id=" + current_id + "&type=schdule_notification",
		success: function (response) {
			if (response == 'success') {
				$("#notification_title").val('');
				$("#notification_desc").val('');
				$("#screenoverlay").css("display", "none");
				$(".popup_container").css("display", "block");
				$(".confirm_name").css("display", "block");
				$(".confirm_name_form p").text("Notification Scheduled Successfully.");
				window.location = 'appedit.php?appid=<?php echo $appidEdit; ?>'
			}
			else if (response == 'fails') {
				$("#notification_title").val('');
				$("#notification_desc").val('');
				$("#screenoverlay").css("display", "none");
				$(".popup_container").css("display", "block");
				$(".confirm_name").css("display", "block");
				$(".confirm_name_form p").text("Please Try Again.");
				setTimeout(function () {
					$(".popup_container").css("display", "none");
					$(".confirm_name").css("display", "none");
					$(".confirm_name_form p").text("");
				}, 4000);

			}
			else if (response == 'backtime') {
				$("#screenoverlay").css("display", "none");
				$(".popup_container").css("display", "block");
				$(".confirm_name").css("display", "block");
				$(".confirm_name_form p").text("Please select time greater than current time.");
				setTimeout(function () {
					$(".popup_container").css("display", "none");
					$(".confirm_name").css("display", "none");
					$(".confirm_name_form p").text("");
				}, 4000);

			}
			else {
				$("#notification_title").val('');
				$("#notification_desc").val('');
				$("#screenoverlay").css("display", "none");
				$(".popup_container").css("display", "block");
				$(".confirm_name").css("display", "block");
				$(".confirm_name_form p").text("Oops Something went wrong.Try again later.");
				setTimeout(function () {
					$(".popup_container").css("display", "none");
					$(".confirm_name").css("display", "none");
					$(".confirm_name_form p").text("");
				}, 4000);
			}
		},
		error: function () {
			$("#notification_title").val('');
			$("#notification_desc").val('');
			$(".confirm_name_form p").text("There is error while submit.");
			setTimeout(function () {
				$(".popup_container").css("display", "none");
				$(".confirm_name").css("display", "none");
				$(".confirm_name_form p").text("");
			}, 4000);

		}
	});
});

$(document).on('click', '.createNotification', function () {
	if ($(this).text() == 'Edit') {
		$(this).text('Close');
		$(this).parent('.notification_title').siblings('.create_notification').css('display', 'block');
	} else {
		$(this).text('Edit');
		$(this).parent('.notification_title').siblings('.create_notification').css('display', 'none');
	}
});
$('#scheduleNew').on('click', function () {
	if ($('.sche_noti_body').length == 5) {
		$('.popup_container').fadeIn();
		$('.confirm_name.more_five').fadeIn();
	} else {
		$(this).parent('.schedule_new').after('<div class="sche_noti_body"><div class="notification_title"><h3>Title of the Notification</h3><p class="title"><span>Status:</span>, Schedule Date: </p><a class="right_btn1 createNotification">Edit</a><a class="right_btn deleteNotification">Delete</a></div><div class="create_notification"><div class="create_notification_text"><h4>Create New <span>(You can send unlimited number of notifications to your app users, but you are allowed to schedule (by date and time for the future) a maximum of 5 notifications at one time. Remember to check all fields, best practices and spelling check before you hit send. Once it a notification goes out its can&apos;t be recalled. You can edit a notification any time before its sent)</span></h4><a class="read_more">Read More...</a><div class="clear"></div></div><form class="create_form"><div class="two_input"><div class="input_list"><div class="input_label"><label>Schedule :</label></div><div class="input_box"><input type="text" name="date" class="schdule_date"></div></div><div class="input_list time"><div class="input_label"><label>Schedule Time</label></div><div class="input_box"><select class="time1"><option value="01">1:00</option><option value="02">2:00</option><option value="03">3:00</option><option value="04">4:00</option><option value="05">5:00</option><option value="06">6:00</option><option value="07">7:00</option><option value="08">8:00</option><option value="09">9:00</option><option value="10">10:00</option><option value="11">11:00</option><option value="12">12:00</option></select><select class="time2"><option value="am">AM</option><option value="pm">PM</option></select><select class="time3"><?php echo $opt; ?></select></div></div><div class="clear"></div></div><div class="clear"></div><div class="input_list"><div class="input_label"><label>Title *:</label></div><div class="input_box"><input type="text" placeholder="Title" class="pnotification_title"></div></div><div class="clear"></div><div class="input_list"><div class="input_label"><label>Description :</label></div><div class="input_box"><textarea class="pnotification_desc"></textarea></div></div><div class="notification_msg_left">Use notifications responsibly. Sending too many notifications, incorrect copy or sending confusing messages can lead to spam and result in the user disallowing notifications or even deleting your app. All the best</div><div class="notification_btns"><a href="#" class="not-btn">Send Now</a><a href="#" class="schdule_not">Schedule for Later</a><p class="notification_msg">(Send now will ignore the Schedule time and Date)</p></div><input type="hidden" value="0" class="current_id"></form></div></div>');
		datepick();
		$('.sche_noti_body:first').children('.create_notification').hide();
	}
});
$(document).on('click', '.deleteNotification', function () {
	$("#present").removeAttr("id");
	$(".confirm_name_form").addClass("delete");
	$(".popup_container").css("display", "block");
	$(".confirm_name").css("display", "block");
	$(".confirm_name_form p").text("Are you sure want to delete?");
	$(this).attr("id", "present");

});
$(document).on("click", ".confirm_name_form.delete input[type='button']", function () {
	$(".confirm_name_form").removeClass("delete");
	var index1 = $("#present").parents(".sche_noti_body").index(".sche_noti_body");
	var current_id = $(".sche_noti_body").eq(index1).find(".current_id").val();
	$("#present").parents('.sche_noti_body').remove();
	if (current_id > 0) {
		$("#screenoverlay").css("display", "block");
		$.ajax({
			type: 'post',
			url: 'ajax.php',
			data: 'notification_id=' + current_id + "&type=delete_notification",
			success: function (response) {
				if (response == 'success') {
					$("#screenoverlay").css("display", "none");
					window.location = 'appedit.php?appid=<?php echo $appidEdit; ?>'
				}
				else if (response == 'fails') {
					$("#screenoverlay").css("display", "none");
					$(".popup_container").css("display", "block");
					$(".confirm_name").css("display", "block");
					$(".confirm_name_form p").text("Please Try Again.");
					setTimeout(function () {
						$(".popup_container").css("display", "none");
						$(".confirm_name").css("display", "none");
						$(".confirm_name_form p").text("");
					}, 4000);

				}
				else {
					$("#screenoverlay").css("display", "none");
					$(".popup_container").css("display", "block");
					$(".confirm_name").css("display", "block");
					$(".confirm_name_form p").text("Oops Something went wrong.Try again later.");
					setTimeout(function () {
						$(".popup_container").css("display", "none");
						$(".confirm_name").css("display", "none");
						$(".confirm_name_form p").text("");
					}, 4000);
				}
			},
		});
	}
	$("#present").removeAttr("id");
});



$(document).on('click', 'a.read_more', function () {
	var realHeight = $(this).prev()[0].scrollHeight;
	if ($(this).prev().height() == 18) {
		$(this).prev().animate({
			height: $(this).prev()[0].scrollHeight
		});
		$(this).text('Close');
	} else {
		$(this).prev().animate({
			height: 18
		});
		$(this).text('Read More...');
	}
	;
});

$('.browse_input_btn').on('change', function () {
	if ($(this).val() == null) {
		$('.uploaded_file_name').val("");
	} else {
		var str = $(this).val();
                                            //var path = "C:\\fakepath\\example.doc";
                                            var filename = str.replace(/^.*\\/, "");
                                            $('.uploaded_file_name').val(filename);
                                            $("#screenoverlay").css("display", "block");
                                            $('#ios_save').trigger('click');

                                        }
                                    });

$('.browse_btn').on('click', function (e) {
	e.preventDefault();
	$('.browse_input_btn').trigger('click');
});


$('#ios_notification_form').submit(function (e) {
	$("#error_ios").removeClass();
	$("#screenoverlay").css("display", "block");
	$.ajax({
		url: 'ajax.php',
		type: 'POST',
		data: new FormData(this),
		processData: false,
		contentType: false,
		success: function (response) {
			$("#screenoverlay").css("display", "none");
			var final_response = response.split('#');
			if (final_response[0] == 'success') {
				$(".uploaded_file_name").val(final_response[1]);
				$("#ios_notification_form").val(final_response[1]);
				$("#error_ios").addClass("success");
				$("#error_ios").text("File uploaded successfully.");
				setTimeout(function () {
					$("#error_ios").text("");
				}, 10000);
			}
			else if (final_response[0] == 'not_uploaded') {
				$("#error_ios").addClass("error");
				$("#error_ios").text("File upload failure.");
				setTimeout(function () {
					$("#error_ios").text("");
				}, 10000);

			}
			else if (final_response[0] == 'invaild_file') {
				$("#error_ios").addClass("error");
				$("#error_ios").text("This seems like an invalid .ppk file.");
				setTimeout(function () {
					$("#error_ios").text("");
				}, 10000);

			}
			else if (final_response[0] == 'invaild_size') {
				$("#error_ios").addClass("error");
				$("#error_ios").text("This seems like an invalid .ppk file.");
				setTimeout(function () {
					$("#error_ios").text("");
				}, 10000);

			}
			else {
				$("#error_ios").addClass("error");
				$("#error_ios").text("Oops Something went wrong.Try again later.");
				setTimeout(function () {
					$("#error_ios").text("");
				}, 10000);

			}
			return false;
		},
		error: function () {
					$("#screenoverlay").css("display", "none");
					$("#error_ios").text("There is error while submit.");
					$("#error_ios").addClass("error");
					setTimeout(function () {
					$("#error_ios").text("");
					}, 10000);
		}
	});
	e.preventDefault();
	return false;
});


$(".expire-cross a").on("click",function(){				
	$(".expire-bar").fadeOut();



});				




});

$(window).load(function () {
	var screenWidth = $(window).width();
	if (screenWidth <= 1023) {
		$('.screen_popup').fadeIn();
		$('.popup_container').fadeIn();
	} else {
		$('.screen_popup').fadeOut();
		$('.popup_container').fadeOut();
	}
	;
});


</script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script>
	function datepick() {
		$(".schdule_date, .data-date").datepicker({
			numberOfMonths: 1,
			dateFormat: 'dd-mm-yy',
			onSelect: function (selected) {
                                            //var dt = new Date(selected);
                                            //dt.setDate(dt.getDate());
                                            //$("#end_date").datepicker("option", "minDate", dt);
                                            $("#end_date").datepicker();

                                        },
                                       // minDate: new Date()
                                   });

	}
	datepick();




								//download user data validation
								function downloaduserdata(){

									var startdate = $('[name="startdate1"]').val();
									var enddate = $('[name="enddate1"]').val();
									

									if(startdate == ''){
										$(".confirm_name .confirm_name_form").html('<p>Please enter start date.</p><input type="button" value="OK">');
										$(".confirm_name").css({'display': 'block'});
										$(".close_popup").css({'display': 'block'});
										$(".popup_container").css({'display': 'block'});

										return false;
									}
									
									if(enddate == ''){
										$(".confirm_name .confirm_name_form").html('<p>Please enter end date.</p><input type="button" value="OK">');
										$(".confirm_name").css({'display': 'block'});
										$(".close_popup").css({'display': 'block'});
										$(".popup_container").css({'display': 'block'});
										return false;
									}



									if(startdate > enddate){
										$(".confirm_name .confirm_name_form").html('<p>End date should be greater than start date.</p><input type="button" value="OK">');
										$(".confirm_name").css({'display': 'block'});
										$(".close_popup").css({'display': 'block'});
										$(".popup_container").css({'display': 'block'});
										return false;
									}

									document.getElementById('user_user_data').submit();
								}
									//end download user data validation

								//download form data validation
								function downloadformdata(){

									var startdate = $('[name="startdate"]').val();
									var enddate = $('[name="enddate"]').val();
									var formname = $('select[name="formname"]').val();



									if(formname == 0){
										$(".confirm_name .confirm_name_form").html('<p>Please select form screen.</p><input type="button" value="OK">');
										$(".confirm_name").css({'display': 'block'});
										$(".close_popup").css({'display': 'block'});
										$(".popup_container").css({'display': 'block'});
										return false;
									}
									if(startdate == ''){
										$(".confirm_name .confirm_name_form").html('<p>Please enter start date.</p><input type="button" value="OK">');
										$(".confirm_name").css({'display': 'block'});
										$(".close_popup").css({'display': 'block'});
										$(".popup_container").css({'display': 'block'});

										return false;
									}
									
									if(enddate == ''){
										$(".confirm_name .confirm_name_form").html('<p>Please enter end date.</p><input type="button" value="OK">');
										$(".confirm_name").css({'display': 'block'});
										$(".close_popup").css({'display': 'block'});
										$(".popup_container").css({'display': 'block'});
										return false;
									}
									if(startdate > enddate){
										$(".confirm_name .confirm_name_form").html('<p>End date should be greater than start date.</p><input type="button" value="OK">');
										$(".confirm_name").css({'display': 'block'});
										$(".close_popup").css({'display': 'block'});
										$(".popup_container").css({'display': 'block'});
										return false;
									}

									document.getElementById('user_form_data').submit();
								}
									//end download form data validation

						// paypal form submit
						
						function paypalForm() {
							var app_id='<?php echo isset($_GET['appid']) ? $_GET['appid'] : '';?>';							
							window.location='payment_review_advance.php?appid='+app_id;
							
						}
						function reneueaddtocart() {
							download = 2;
							var platform = $("#app_platform").val();
							var appName = $("#app_name").val();
							var appid = $('#renew_app_id').val();
							var param = {'platform': platform, 'appName': appName};
							var form_data = {
								token: '<?php echo $token; ?>', //used token here.
								hasid: appid,
								is_ajax: 1,
								app_renew:1
							};
							
							$.ajax({
								type: "POST",
								url: 'modules/myapp/appAddtocart.php',
								data: form_data,
								async: false,
								success: function (response) {
									
									if (download == 2) {
										location.href = BASEURL + "payment_details.php";
									}
								}
							});
							
						}
						
						
					</script>
					<script type="text/javascript" src="js/trackuser.jquery.js?v=1.1"></script>
				</body>
				</html>
