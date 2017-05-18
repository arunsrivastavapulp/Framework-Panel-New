<?php

session_start();
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';
require 'includes/db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/sendproforma', 'sendProforma');
$app->post('/sendinvoice', 'sendInvoice');
$app->post('/sendacknowledgement', 'sendAcknowledgement');

function sendProforma() {
    $data = array();
    $data['app_id'] = $_POST['app_id'];
    $data['type'] = $_POST['type'];
    $data['status'] = $_POST['status'];
    if ($data['type'] == 'full') {
        $data['id'] = master_payment_id($data['app_id']);
        invoice_full_payment('Proforma', $data, 'proforma.php');
    } elseif ($data['type'] == 'part') {
        $paymentType = 1;
        $data['id'] = master_payment_id($data['app_id']);
        final_invoice_part_payment('Proforma', $data, 'proforma.php', $paymentType);
    } else {
        die("invalid_request");
    }

}

function sendInvoice() {
    $data = array();
    $data['app_id'] = $_POST['app_id'];
    $data['type'] = $_POST['type'];
    $data['status'] = $_POST['status'];
    if ($data['type'] == 'full') {
        $data['id'] = master_payment_id($data['app_id']);
        invoice_full_payment('Invoice', $data, 'invoice_manual.php');
    } elseif ($data['type'] == 'part') {
        $data['id'] = master_payment_id($data['app_id']);
        $paymentType = 0;
        final_invoice_part_payment('Invoice', $data, 'invoice_manual.php', $paymentType);
    } else {
        die("invalid_request");
    }
}

function sendAcknowledgement(){
    $data = array();
    $data['app_id'] = $_POST['app_id'];
    $data['id'] = $_POST['id'];
    $data['status'] = $_POST['status'];
    
    send_acknowledgement_part_payment('',$data,'payment_acknowledgement.php');
}

function master_payment_id($app_id) {
    $dbCon = content_db();
    $query = "select mp.id as mid from master_payment as mp
     join author_payment as ap on mp.id=ap.master_payment_id  where ap.plan_id is not null and ap.plan_id<>'' 
     and  ap.app_id='" . $app_id . "' order by ap.id desc limit 1";
    $queryRun = $dbCon->query($query);
    $rowFetch = $queryRun->fetch(PDO::FETCH_ASSOC);
    return $rowFetch['mid'];
}

function author_details($id) {
    $dbCon = content_db();
    $query = "select * from author where id=:id and deleted=0";
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':id', $id, PDO::PARAM_INT);
    $queryRun->execute();
    $rowFetch = $queryRun->fetch(PDO::FETCH_ASSOC);
    return $rowFetch;
}

function app_details($id) {
    $dbCon = content_db();
    $query = "select * from app_data where id=:id and deleted=0";
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':id', $id, PDO::PARAM_INT);
    $queryRun->execute();
    $rowFetch = $queryRun->fetch(PDO::FETCH_ASSOC);
    return $rowFetch;
}

function plan_name($id, $is_custom) {
    $dbCon = content_db();
    if ($is_custom == 1) {
        $query = "select * from custom_plans where id=:id and deleted=0";
    } else {
        $query = "select * from plans where id=:id and deleted=0";
    }
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':id', $id, PDO::PARAM_INT);
    $queryRun->execute();
    $rowFetch = $queryRun->fetch(PDO::FETCH_ASSOC);
    return $rowFetch;
}

function author_payemnt_details($author_payment_id) {
    $dbCon = content_db();
    $query = "select * from author_payment_details where author_payment_id=:author_payment_id";
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':author_payment_id', $author_payment_id, PDO::PARAM_INT);
    $queryRun->execute();
    $rowFetch = $queryRun->fetchAll(PDO::FETCH_ASSOC);
    return $rowFetch;
}

function curency_details($currency) {
    $dbCon = content_db();
    $query = "select * from currency_type where id=:currency";
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':currency', $currency, PDO::PARAM_INT);
    $queryRun->execute();
    $rowFetch = $queryRun->fetch(PDO::FETCH_ASSOC);
    return $rowFetch;
}

function plateform_details($platform) {
    $dbCon = content_db();
    $query = "select * from platform where id=:platform";
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':platform', $platform, PDO::PARAM_INT);
    $queryRun->execute();
    $rowFetch = $queryRun->fetch(PDO::FETCH_ASSOC);
    return $rowFetch;
}

function app_type($id) {
    $dbCon = content_db();
    $query = "select * from app_type where id=:id and deleted=0";
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':id', $id, PDO::PARAM_INT);
    $queryRun->execute();
    $rowFetch = $queryRun->fetch(PDO::FETCH_ASSOC);
    return $rowFetch;
}

function author_state($zone_id) {
    $dbCon = content_db();
    $query = "SELECT * FROM oc_zone WHERE zone_id=:zone_id";
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':zone_id', $zone_id, PDO::PARAM_INT);
    $queryRun->execute();
    $rowFetch = $queryRun->fetch(PDO::FETCH_ASSOC);
    return $rowFetch;
}

function all_amount($app_id) {
    $dbCon = content_db();
    $query = "SELECT SUM(part_amount) AS total FROM master_payment_part WHERE app_id=:app_id and deleted=0";
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':app_id', $app_id, PDO::PARAM_INT);
    $queryRun->execute();
    $rowFetch = $queryRun->fetch(PDO::FETCH_ASSOC);
    return $rowFetch['total'];
}

function app_payment_part($app_id) {
    $dbCon = content_db();
    $query = "SELECT * FROM master_payment_part WHERE app_id=:app_id and deleted=0 order by id asc";
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':app_id', $app_id, PDO::PARAM_INT);
    $queryRun->execute();
    $rowFetch = $queryRun->fetchAll(PDO::FETCH_ASSOC);
    return $rowFetch;
}

function service_tax() {
    $dbCon = content_db();
    $query = "SELECT perc_tax FROM service_tax 
    WHERE DATE(NOW()+INTERVAL 5 HOUR+INTERVAL 30 MINUTE)>=implementation_date OR is_active=1
    ORDER BY implementation_date DESC,id DESC
    LIMIT 1";
    $queryRun = $dbCon->prepare($query);
    $queryRun->execute();
    $rowFetch = $queryRun->fetch(PDO::FETCH_ASSOC);
    return $rowFetch['perc_tax'];
}

function part_remaing_amt($app_id) {
    $dbCon = content_db();
    $query = "SELECT sum(part_amount) as paid FROM master_payment_part WHERE app_id=:app_id and payment_done=1 and deleted=0";
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':app_id', $app_id, PDO::PARAM_INT);
    $queryRun->execute();
    $rowFetch = $queryRun->fetch(PDO::FETCH_ASSOC);

    $service_tax = service_tax();
    $total = all_amount($app_id);

    $total_amt = $total + ($total * $service_tax / 100);
    $paid_amt = $rowFetch['paid'] + ($rowFetch['paid'] * $service_tax / 100);
    $remaing = $total_amt - $paid_amt;
    return $remaing;
}

function invoice_full_payment($subject = '', $data, $template = '') {
    $dbCon = content_db();
    $id = $data['id'];
    $query = "select mp.id as mid,ap.id as aid ,mp.author_id,mp.order_id,mp.total_amount,mp.service_tax,mp.discount,mp.currency_type_id,ap.plan_id,ap.is_custom,ap.platform,ap.total_amount as plan_amount,ap.app_customization from master_payment as mp
     join author_payment as ap on mp.id=ap.master_payment_id  where ap.plan_id is not null and ap.plan_id<>'' 
     and  mp.id=:id order by ap.id desc limit 1";

    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':id', $id, PDO::PARAM_INT);
    $queryRun->execute();
    $master_payment = $queryRun->fetch(PDO::FETCH_ASSOC);
    
    $author = author_details($master_payment['author_id']);
    $app_details = app_details($data['app_id']);
    $plan = plan_name($master_payment['plan_id'], $master_payment['is_custom']);
    $author_payemnt_details = author_payemnt_details($master_payment['aid']);
    $curency_details = curency_details($master_payment['currency_type_id']);
    $plateform_details = plateform_details($master_payment['platform']);
    $app_type = app_type($app_details['type_app']);
    if ($author['state']) {
        $state = author_state($author['state']);
        $state = $state['name'];
    } else {
        $state = '';
    }

    $years = ($plan['validity_in_days'] / 365); // days / 365 days
    $years = floor($years); // Remove all decimals
    if ($master_payment['is_custom'] == 1) {
        $yyyyy = $plan['validity_in_days'] . ' Days';
    } else {
        if ($years > 1) {
            $yyyyy = $years . ' Years';
        } else {
            $yyyyy = $years . ' Year';
        }
    }

    $serviceTaxhtml = '';
//        $basicUrl = 'http://54.149.60.166/testing/';
    $planimg = "market_packages2.png";
    $my_app_type = $app_type['name'];
    $p_platform = $plateform_details['name'];
    $currencyIcon = $curency_details['Name'];

    $currappbaseprice = $master_payment['plan_amount'] + ($author_payemnt_details[0]['amount'] + $author_payemnt_details[1]['amount']);

    $discount = $master_payment['discount'];
    $subtotal = round($currappbaseprice - $discount, 2);
    $service_tax = $master_payment['service_tax'];
    if ($master_payment['app_customization'] == 1) {
        $plan_title = $plan['plan_name'] . "+ Customize";
    } else {

        $plan_title = $plan['plan_name'];
    }

    $payment_part = '';
    $order_items = '';
    $order_items .= '<tr>
            <td align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:30px 15px; border-left:1px solid #333; border-right:1px solid #dbdbdb  ; border-bottom:1px solid #dbdbdb  ; color:#323232  ;">
                <p style="margin:0;">Instappy Subscription Fee</p>
                <p style="margin:10px 0 0 0;">Inclusions:</p>
                <p style="margin:10px 0 0 20px;">"' . $app_details['summary'] . '"</p>
                <p style="margin:10px 0 0 20px;">"' . $my_app_type . '"</p>
                <p style="margin:10px 0 0 20px;">OS <b>"' . $p_platform . '"</b></p>
                <p style="margin:10px 0 0 20px;">Package <b>"' . $plan_title . '"</b></p>';

    $order_items .= '<p style="margin:10px 0 0 20px;">App Icon</p>';
    $order_items .= '<p style="margin:10px 0 0 20px;">Splash Screen</p>';

    $order_items .= '</td>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; color:#323232  ; font-size:15px; padding:30px 0; border-right:1px solid #dbdbdb  ; border-bottom:1px solid #dbdbdb  ;">1</td>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#323232  ; padding:30px 0; border-right:1px solid #dbdbdb  ; border-bottom:1px solid #dbdbdb  ;">' . $yyyyy . '</td>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:30px 0; border-right:1px solid #333; border-bottom:1px solid #dbdbdb  ; color:#323232  ;"> ' . $currencyIcon . ' ' . $currappbaseprice . '</td>
            </tr>';

    // invoice email
    $order_id = $master_payment['order_id'];
    $csubject = 'Instappy ' . $subject . ' for order ' . $order_id;
    $basicUrl = baseUrlWeb();
    $chtmlcontent = file_get_contents($basicUrl . 'edm/' . $template);
    $clastname = $author['last_name'] != '' ? ' ' . $author['last_name'] : $author['last_name'];
    $cname = ucwords($author['first_name'] . $clastname);
    $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
    $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
    $chtmlcontent = str_replace('{address}', $author['street'] . ',' . $author['city'], $chtmlcontent);
    $chtmlcontent = str_replace('{state}', $state, $chtmlcontent);
    $chtmlcontent = str_replace('{order_no}', $order_id, $chtmlcontent);
    $chtmlcontent = str_replace('{custid}', $author['custid'], $chtmlcontent);
    $chtmlcontent = str_replace('{payment_part}', $payment_part, $chtmlcontent);
    $chtmlcontent = str_replace('{order_items}', $order_items, $chtmlcontent);
    $chtmlcontent = str_replace('{discount}', round($discount, 2), $chtmlcontent);
    $chtmlcontent = str_replace('{subtotal}', round($subtotal, 2), $chtmlcontent);
    $chtmlcontent = str_replace('{tax}', $service_tax, $chtmlcontent);
    $chtmlcontent = str_replace('{order_total}', round($master_payment['total_amount'], 2), $chtmlcontent);
    $chtmlcontent = str_replace('{currentdate}', date('d-m-Y', time()), $chtmlcontent);
    $chtmlcontent = str_replace('{currency}', $currencyIcon, $chtmlcontent);
    $chtmlcontent = str_replace('{planimg}', $planimg, $chtmlcontent);

    $string = '/var/www/html';

    $pathdir = $string . '/pdf/' . $master_payment['author_id'];
    $directory = $pathdir;
    if (!file_exists($directory)) {
        mkdir($string . '/pdf/' . $master_payment['author_id'], 0777, true);
    }

    // make pdf
    $waterMark = "Paid";
    if($subject=='Proforma'){
        $waterMark = "Proforma";
    }
    $file = fopen($string . "/convertTopdf.php", "w");
    fwrite($file, $chtmlcontent);
    fclose($file);
    $pdfname = $order_id . '-' . strtolower($subject) . '.pdf';
    ob_start();
    ob_get_clean();
    include($string . '/mpdf60/mpdf.php');
    $mpdf = new mPDF('c', 'A4', '', '', 10, 10, 10, 10);
    $mpdf->SetProtection(array('print'));
    $mpdf->SetTitle("Instappy.com - $subject");
    $mpdf->SetAuthor("Instappy.com");
    $mpdf->SetWatermarkText($waterMark);
    $mpdf->showWatermarkText = true;
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.1;
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->WriteHTML(file_get_contents($string . "/convertTopdf.php"));
    $mpdf->Output($directory . '/' . $pdfname, 'F');
    $queryUInvoice = 'UPDATE master_payment set invoice_link="' . $pdfname . '" WHERE order_id="' . $order_id . '"';
    $stmtUInvoice = $dbCon->prepare($queryUInvoice);
    $stmtUInvoice->execute();
    
    if( $data['status']=='1'){
//    $bcc = "nitin@pulpstrategy.com";
         $bcc = "crminfo@instappy.com";
    $cto = $author['email_address'];
    $cformemail = 'noreply@instappy.com';
    $key = 'f894535ddf80bb745fc15e47e42a595e';
    //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
    //$customerhead = file_get_contents($url);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            'api_key' => $key,
            'subject' => $csubject,
            'fromname' => 'Instappy',
            'from' => $cformemail,
            'content' => $chtmlcontent,
            'recipients' => $cto,
            'bcc' => $bcc
        )
    ));
    $customerhead = curl_exec($curl);

    curl_close($curl);
    echo "success";
    } else{
     $pdfPath = $basicUrl.'pdf/'.$master_payment['author_id'].'/'.$pdfname;
     echo $pdfPath;
    }
}

function final_invoice_part_payment($subject = '', $data, $template = '', $paymentType) {
    $dbCon = content_db();
    $id = $data['id'];
    $query = "select mp.id as mid,ap.id as aid ,mp.author_id,mp.order_id,mp.total_amount,mp.service_tax,mp.discount,mp.currency_type_id,ap.plan_id,ap.is_custom,ap.platform,ap.total_amount as plan_amount,ap.app_customization from master_payment as mp
             join author_payment as ap on mp.id=ap.master_payment_id  where ap.plan_id is not null and ap.plan_id<>'' 
             and  mp.id=:id order by ap.id desc limit 1";
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':id', $id, PDO::PARAM_INT);
    $queryRun->execute();
    $master_payment = $queryRun->fetch(PDO::FETCH_ASSOC);

    $author = author_details($master_payment['author_id']);
    $app_details = app_details($data['app_id']);
    $plan = plan_name($master_payment['plan_id'], $master_payment['is_custom']);
    $author_payemnt_details = author_payemnt_details($master_payment['aid']);
    $curency_details = curency_details($master_payment['currency_type_id']);
    $plateform_details = plateform_details($master_payment['platform']);
    $app_type = app_type($app_details['type_app']);
    $total_pay = all_amount($data['app_id']);
    $payment_partA = app_payment_part($data['app_id']);
    $service_tax_per = service_tax();

    if ($author['state']) {
        $state = author_state($author['state']);
        $state = $state['name'];
    } else {
        $state = '';
    }

    $years = ($plan['validity_in_days'] / 365); // days / 365 days
    $years = floor($years); // Remove all decimals
    if ($master_payment['is_custom'] == 1) {
        $yyyyy = $plan['validity_in_days'] . ' Days';
    } else {
        if ($years > 1) {
            $yyyyy = $years . ' Years';
        } else {
            $yyyyy = $years . ' Year';
        }
    }
    
    $serviceTaxhtml = '';
//        $basicUrl = 'http://54.149.60.166/testing/';
    $planimg = "market_packages2.png";
    $my_app_type = $app_type['name'];
    $p_platform = $plateform_details['name'];
    $currencyIcon = $curency_details['Name'];

    $currappbaseprice = $total_pay;

    $discount = $master_payment['discount'];
    $subtotal = round($currappbaseprice - $discount, 2);
    $service_tax = $subtotal * $service_tax_per / 100;
    $final_pay = $subtotal + $service_tax;
    if ($master_payment['app_customization'] == 1) {
        $plan_title = $plan['plan_name'] . "+ Customize";
    } else {

        $plan_title = $plan['plan_name'];
    }

    $payment_part = '';
    $waterMark='Paid';
    if ($paymentType == 1) {
        $waterMark='Proforma';
        $payment_part .= '<tr>
    	<td style="padding:0 35px;">
        	<p style="margin:0;margin-top:30px;">Payment Details</p>
            <table cellpadding="0" cellspacing="0" width="100%" style="text-align:center;">
                <tr>
                    <th style="font-family:Arial, Helvetica, sans-serif; font-size:15px; background:#ffcc00; color:#fff; padding:10px 0; border-right:1px solid #ffd52d; width:20%;">Part Payment</th>
                    <th style="font-family:Arial, Helvetica, sans-serif; font-size:15px; background:#ffcc00; color:#fff; padding:10px 0; border-right:1px solid #ffd52d; width:20%;">Amount (' . $currencyIcon . ')</th>
                    <th style="font-family:Arial, Helvetica, sans-serif; font-size:15px; background:#ffcc00; color:#fff; padding:10px 0; border-right:1px solid #ffd52d; width:20%;">Due Date</th>
                    <th style="font-family:Arial, Helvetica, sans-serif; font-size:15px; background:#ffcc00; color:#fff; padding:10px 0;border-right:1px solid #ffd52d; width:20%;">Received Date</th>
                    <th style="font-family:Arial, Helvetica, sans-serif; font-size:15px; background:#ffcc00; color:#fff; padding:10px 0;">Status</th>
                </tr>';
        if (count($payment_partA) > 0) {
            $i = 1;
            foreach ($payment_partA as $keyA => $valueA) {

                $payment_part .= '<tr>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:30px 0; border-right:1px solid #dbdbdb;border-left:1px solid #333; border-bottom:1px solid #dbdbdb; color:#323232; width:20%;">Instalment ' . $i . '</td>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:30px 0; border-right:1px solid #dbdbdb;border-left:1px solid #dbdbdb; border-bottom:1px solid #dbdbdb; color:#323232; width:20%;">' . $currencyIcon . ' ' . $valueA['part_amount'] . '</td>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:30px 0; border-right:1px solid #dbdbdb;border-left:1px solid #dbdbdb; border-bottom:1px solid #dbdbdb; color:#323232; width:20%;"> ' . date('d-m-Y',strtotime($valueA['paymentdate'])) . '</td>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:30px 0; border-right:1px solid #dbdbdb;border-left:1px solid #dbdbdb; border-bottom:1px solid #dbdbdb; color:#323232; width:20%;"> ' . ($valueA['transaction_date']?date('d-m-Y',strtotime($valueA['transaction_date'])):'-') . '</td>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:30px 0; border-right:1px solid #333;border-left:1px solid #dbdbdb; border-bottom:1px solid #dbdbdb; color:#323232;"> ' . ($valueA['payment_done'] == 0 ? 'Pending' : 'Paid') . '</td>
            </tr>';
                $i++;
            }
        }
        $payment_part .= '</table>
        </td>
    </tr>';
    }
    $order_items = '';
    $order_items .= '<tr>
            <td align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:30px 15px; border-left:1px solid #333; border-right:1px solid #dbdbdb  ; border-bottom:1px solid #dbdbdb  ; color:#323232  ;">
                <p style="margin:0;">Instappy Subscription Fee</p>
                <p style="margin:10px 0 0 0;">Inclusions:</p>
                <p style="margin:10px 0 0 20px;">"' . $app_details['summary'] . '"</p>
                <p style="margin:10px 0 0 20px;">"' . $my_app_type . '"</p>
                <p style="margin:10px 0 0 20px;">OS <b>"' . $p_platform . '"</b></p>
                <p style="margin:10px 0 0 20px;">Package <b>"' . $plan_title . '"</b></p>';

    $order_items .= '<p style="margin:10px 0 0 20px;">App Icon</p>';
    $order_items .= '<p style="margin:10px 0 0 20px;">Splash Screen</p>';

    $order_items .= '</td>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; color:#323232  ; font-size:15px; padding:30px 0; border-right:1px solid #dbdbdb  ; border-bottom:1px solid #dbdbdb  ;">1</td>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#323232  ; padding:30px 0; border-right:1px solid #dbdbdb  ; border-bottom:1px solid #dbdbdb  ;">' . $yyyyy . '</td>
                <td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:30px 0; border-right:1px solid #333; border-bottom:1px solid #dbdbdb  ; color:#323232  ;"> ' . $currencyIcon . ' ' . $currappbaseprice . '</td>
            </tr>';
    
    // invoice email
    $order_id =$master_payment['order_id'];
    $csubject = 'Instappy ' . $subject . ' for order ' . $order_id;
    $basicUrl = baseUrlWeb();
    $chtmlcontent = file_get_contents($basicUrl . 'edm/' . $template);
    $clastname = $author['last_name'] != '' ? ' ' . $author['last_name'] : $author['last_name'];
    $cname = ucwords($author['first_name'] . $clastname);
    $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
    $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
    $chtmlcontent = str_replace('{address}', $author['street'] . ',' . $author['city'], $chtmlcontent);
    $chtmlcontent = str_replace('{state}', $state, $chtmlcontent);
    $chtmlcontent = str_replace('{order_no}', $order_id, $chtmlcontent);
    $chtmlcontent = str_replace('{custid}', $author['custid'], $chtmlcontent);
    $chtmlcontent = str_replace('{order_items}', $order_items, $chtmlcontent);
    $chtmlcontent = str_replace('{payment_part}', $payment_part, $chtmlcontent);
    $chtmlcontent = str_replace('{discount}', round($discount, 2), $chtmlcontent);
    $chtmlcontent = str_replace('{subtotal}', round($subtotal, 2), $chtmlcontent);
    $chtmlcontent = str_replace('{tax}', $service_tax, $chtmlcontent);
    $chtmlcontent = str_replace('{order_total}', round($final_pay, 2), $chtmlcontent);
    $chtmlcontent = str_replace('{currentdate}', date('d-m-Y', time()), $chtmlcontent);
    $chtmlcontent = str_replace('{currency}', $currencyIcon, $chtmlcontent);
    $chtmlcontent = str_replace('{planimg}', $planimg, $chtmlcontent);

    $string = '/var/www/html';

    $pathdir = $string . '/pdf/' . $master_payment['author_id'];
    $directory = $pathdir;
    if (!file_exists($directory)) {
        mkdir($string . '/pdf/' . $master_payment['author_id'], 0777, true);
    }

//         make pdf
    
    
    $file = fopen($string . "/convertTopdf.php", "w");
    fwrite($file, $chtmlcontent);
    fclose($file);
    $pdfname = $order_id . '-' . strtolower($subject) . '.pdf';
    ob_start();
    ob_get_clean();
    include($string . '/mpdf60/mpdf.php');
    $mpdf = new mPDF('c', 'A4', '', '', 10, 10, 10, 10);
    $mpdf->SetProtection(array('print'));
    $mpdf->SetTitle("Instappy.com - $subject");
    $mpdf->SetAuthor("Instappy.com");
    $mpdf->SetWatermarkText($waterMark);
    $mpdf->showWatermarkText = true;
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.1;
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->WriteHTML(file_get_contents($string . "/convertTopdf.php"));
    $mpdf->Output($directory . '/' . $pdfname, 'F');
    $queryUInvoice = 'UPDATE master_payment set invoice_link="' . $pdfname . '" WHERE order_id="' . $order_id . '"';
    $stmtUInvoice = $dbCon->prepare($queryUInvoice);
    $stmtUInvoice->execute();
    
    if( $data['status']=='1'){
//    $bcc = "nitin@pulpstrategy.com";
         $bcc = "crminfo@instappy.com";
    $cto = $author['email_address'];
    $cformemail = 'noreply@instappy.com';
    $key = 'f894535ddf80bb745fc15e47e42a595e';
   
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            'api_key' => $key,
            'subject' => $csubject,
            'fromname' => 'Instappy',
            'from' => $cformemail,
            'content' => $chtmlcontent,
            'recipients' => $cto,
            'bcc' => $bcc
        )
    ));
    $customerhead = curl_exec($curl);

    curl_close($curl);
    echo "success";
    } else{
     $pdfPath = $basicUrl.'pdf/'.$master_payment['author_id'].'/'.$pdfname;
     echo $pdfPath;
    }
}

function send_acknowledgement_part_payment($subject = '', $data, $template = '') {
    $dbCon = content_db();
    $id = $data['id'];
    $query="select * from master_payment_part where id=:id and deleted=0";
    $queryRun = $dbCon->prepare($query);
    $queryRun->bindParam(':id', $id, PDO::PARAM_INT);
    $queryRun->execute();
    $master_payment_part = $queryRun->fetch(PDO::FETCH_ASSOC);
    
    $mpid = $master_payment_part['master_payment_id'];
    $query2="select mp.id as mid,ap.id as aid ,mp.author_id,mp.order_id,mp.total_amount,mp.service_tax,mp.discount,mp.currency_type_id,ap.plan_id,ap.is_custom,ap.platform,ap.total_amount as plan_amount,ap.app_customization from master_payment as mp
             join author_payment as ap on mp.id=ap.master_payment_id  where ap.plan_id is not null and ap.plan_id<>'' 
             and  mp.id=:mpid order by ap.id desc limit 1";
    
    $queryRun2 = $dbCon->prepare($query2);
    $queryRun2->bindParam(':mpid', $mpid, PDO::PARAM_INT);
    $queryRun2->execute();
    $master_payment = $queryRun2->fetch(PDO::FETCH_ASSOC);
   
    $author = author_details($master_payment['author_id']);
    $app_details = app_details($data['app_id']);
    $plan = plan_name($master_payment['plan_id'], $master_payment['is_custom']);
    $author_payemnt_details = author_payemnt_details($master_payment['aid']);
    $curency_details = curency_details($master_payment['currency_type_id']);
    $plateform_details = plateform_details($master_payment['platform']);
    $app_type = app_type($app_details['type_app']);
    $total_pay = all_amount($data['app_id']);
    $service_tax_per = service_tax();
    
    $years = ($plan['validity_in_days'] / 365); // days / 365 days
    $years = floor($years); // Remove all decimals
    if ($master_payment['is_custom'] == 1) {
        $yyyyy = $plan['validity_in_days'] . ' Days';
    } else {
        if ($years > 1) {
            $yyyyy = $years . ' Years';
        } else {
            $yyyyy = $years . ' Year';
        }
    }
    $serviceTaxhtml = '';
//        $basicUrl = 'http://54.149.60.166/testing/';
    $planimg = "market_packages2.png";
    $my_app_type = $app_type['name'];
    $p_platform = $plateform_details['name'];
    $currencyIcon = $curency_details['Name'];
    $currappbaseprice = $master_payment_part['part_amount'];
    $discount = $master_payment['discount'];
    $subtotal = round($currappbaseprice - $discount, 2);
    $service_tax = $service_tax_per * $subtotal / 100;
    $total_amt = round($total_pay - $discount, 2);
    if ($master_payment['app_customization'] == 1) {
        $plan_title = $plan['plan_name'] . "+ Customize";
    } else {

        $plan_title = $plan['plan_name'];
    }
    $amount = 0;
    $amount = round($master_payment_part['part_amount'] + $service_tax, 2);
    $total_amount = round($total_amt + ($total_amt * $service_tax_per / 100), 2);
    $remaing_amt = part_remaing_amt($data['app_id']);
    if ($author['state']) {
        $state = author_state($author['state']);
        $state = $state['name'];
    } else {
        $state = '';
    }
    $summar_str = '';
    if ($remaing_amt == 0) {

        $summar_str.='';
    } else {

        $summar_str.='<tr>
          <td border="1" width="100%">The remaining payment as per your payment plan is ' . $currencyIcon . ' ' . $remaing_amt . ' which needs to be paid timely to ensure smooth functioning of your application.</td>
      </tr>';
    }
    // invoice email
    $order_id =$master_payment['order_id'];
    $csubject = 'Payment Receipt for order ' . $order_id;
    $basicUrl = baseUrlWeb();
    $chtmlcontent = file_get_contents($basicUrl . 'edm/' . $template);
    $clastname = $author['last_name'] != '' ? ' ' . $author['last_name'] : $author['last_name'];
    $cname = ucwords($author['first_name'] . $clastname);
    $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
    $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
     $chtmlcontent = str_replace('{address}', $author['street'] . ',' . $author['city'], $chtmlcontent);
    $chtmlcontent = str_replace('{state}', $state, $chtmlcontent);
    $chtmlcontent = str_replace('{order_no}', $order_id, $chtmlcontent);
    $chtmlcontent = str_replace('{custid}', $author['custid'], $chtmlcontent);
    $chtmlcontent = str_replace('{currencyicon}', $currencyIcon, $chtmlcontent);
    $chtmlcontent = str_replace('{amount}', $amount, $chtmlcontent);
    $chtmlcontent = str_replace('{total_amount}', $remaing_amt, $chtmlcontent);
    $chtmlcontent = str_replace('{app_name}', $app_details['summary'], $chtmlcontent);
    $chtmlcontent = str_replace('{currentdate}', date('d-m-Y', time()), $chtmlcontent);
    $chtmlcontent = str_replace('{order_summary}', $summar_str, $chtmlcontent);
    $chtmlcontent = str_replace('{planimg}', $planimg, $chtmlcontent);
    $chtmlcontent = str_replace('{serviceTax}', $service_tax_per, $chtmlcontent);

    $string = '/var/www/html';

    $pathdir = $string . '/pdf/' . $master_payment['author_id'];
    $directory = $pathdir;
    if (!file_exists($directory)) {
        mkdir($string . '/pdf/' . $master_payment['author_id'], 0777, true);
    }

    // make pdf                
    $file = fopen($string . "/convertTopdf.php", "w");
    fwrite($file, $chtmlcontent);
    fclose($file);
    $pdfname = $order_id . '-acknowledgement.pdf';
    ob_start();
    ob_get_clean();
    include($string . '/mpdf60/mpdf.php');
    $mpdf = new mPDF('c', 'A4', '', '', 10, 10, 10, 10);
    $mpdf->SetProtection(array('print'));
    $mpdf->SetTitle("Instappy.com - Acknowledgement");
    $mpdf->SetAuthor("Instappy.com");
    $mpdf->SetWatermarkText("Paid");
    $mpdf->showWatermarkText = true;
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.1;
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->WriteHTML(file_get_contents($string . "/convertTopdf.php"));
    $mpdf->Output($directory . '/' . $pdfname, 'F');
    $queryUInvoice = 'UPDATE master_payment set invoice_link="' . $pdfname . '" WHERE order_id="' . $order_id . '"';
    $stmtUInvoice = $dbCon->prepare($queryUInvoice);
    $stmtUInvoice->execute();
    
    if( $data['status']=='1'){
//    $bcc = "nitin@pulpstrategy.com";
         $bcc = "crminfo@instappy.com";
    $cto = $author['email_address'];
    $cformemail = 'noreply@instappy.com';
    $key = 'f894535ddf80bb745fc15e47e42a595e';
    //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
    //$customerhead = file_get_contents($url);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            'api_key' => $key,
            'subject' => $csubject,
            'fromname' => 'Instappy',
            'from' => $cformemail,
            'content' => $chtmlcontent,
            'recipients' => $cto,
            'bcc' => $bcc
        )
    ));
    $customerhead = curl_exec($curl);

    curl_close($curl);
    echo "success";
    } else{
     $pdfPath = $basicUrl.'pdf/'.$master_payment['author_id'].'/'.$pdfname;
     echo $pdfPath;
    }
}

$app->run();
