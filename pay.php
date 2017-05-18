<?php
            $csubject = 'Instappy Invoice for order 1443437625';
            //$basicUrl = $this->url;
            $chtmlcontent = file_get_contents('edm/invoice.php');
            $clastname = 'Chan';
            $cname = 'Kan';
            $chtmlcontent = str_replace('{customer_name}', 'Kan Chan', $chtmlcontent);
            $chtmlcontent = str_replace('{base_url}', 'http://www.instappy.com/', $chtmlcontent);
            $chtmlcontent = str_replace('{app_name}', 'TCB', $chtmlcontent);
      

            $cto = 'khriya@gmail.com';
            $cformemail = 'noreply@instappy.com';
            $key = 'f894535ddf80bb745fc15e47e42a595e';
            //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
            //$customerhead = file_get_contents($url);

         


            // invoice email
       
            //$basicUrl = $this->url;
           
           $order_items = '';
     $order_items .= '<tr>
													<td align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:30px 15px; border-left:1px solid #333; border-right:1px solid #dbdbdb  ; border-bottom:1px solid #dbdbdb  ; color:#323232  ;">
														<p style="margin:0;">Instappy Subscription Fee</p>
														<p style="margin:10px 0 0 0;">Inclusions:</p>
														<p style="margin:10px 0 0 20px;">TCB</p>
														<p style="margin:10px 0 0 20px;">"Content Publishing"</p>
														<p style="margin:10px 0 0 20px;">OS <b> Android + iOS</b></p>
														<p style="margin:10px 0 0 20px;">Package <b> Advanced</b></p>';
														 $order_items .= '</td>
													<td valign="top" style="font-family:Arial, Helvetica, sans-serif; color:#323232  ; font-size:15px; padding:30px 0; border-right:1px solid #dbdbdb  ; border-bottom:1px solid #dbdbdb  ;">1</td>
													<td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#323232  ; padding:30px 0; border-right:1px solid #dbdbdb  ; border-bottom:1px solid #dbdbdb  ;">1 Years</td>
													<td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; padding:30px 0; border-right:1px solid #333; border-bottom:1px solid #dbdbdb  ; color:#323232  ;"> Rs. 111842.00</td>
												</tr>';
        
            $chtmlcontent = str_replace('{address}', '90 A Red MIG Flats - Rajouri Garden,New Delhi', $chtmlcontent);
            $chtmlcontent = str_replace('{state}', 'Delhi', $chtmlcontent);
            $chtmlcontent = str_replace('{order_no}','1443437625', $chtmlcontent);
            $chtmlcontent = str_replace('{custid}', '1439289056', $chtmlcontent);
            $chtmlcontent = str_replace('{order_items}', $order_items, $chtmlcontent);
            $chtmlcontent = str_replace('{discount}', '111830.82', $chtmlcontent);
            $chtmlcontent = str_replace('{subtotal}', '11.18', $chtmlcontent);
            $chtmlcontent = str_replace('{tax}', '1.57', $chtmlcontent);
            $chtmlcontent = str_replace('{order_total}', '12.75', $chtmlcontent);
        //    $chtmlcontent = str_replace('{currentdate}', date('d-m-Y', time()), $chtmlcontent);
            $chtmlcontent = str_replace('{currentdate}', '29-09-2015', $chtmlcontent);
            $chtmlcontent = str_replace('{currency}','Rs. ', $chtmlcontent);
            $chtmlcontent = str_replace('{base_url}', 'http://www.instappy.com/', $chtmlcontent);
           
            
            $cbcc = 'dev@instappy.com';
         
        
          
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
                    'bcc' => $cbcc
                )
            ));
            $customerhead = curl_exec($curl);

            curl_close($curl);
            ?>
           

</body>
</html>
