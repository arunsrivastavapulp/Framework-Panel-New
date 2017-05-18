<?php
            $csubject = 'Instappy Invoice for order 1442563230';
            //$basicUrl = $this->url;
           
            $clastname = 'Singh';
            $cname = 'Manvinder';
            $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
            $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
            $chtmlcontent = str_replace('{app_name}', 'Dr H S Rawat', $chtmlcontent);
      

            $cto = 'hemant@pulpstrategy.com';
            $cformemail = 'noreply@instappy.com';
            $key = 'f894535ddf80bb745fc15e47e42a595e';
            //$url          = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($csubject).'&fromname='.rawurlencode($csubject).'&from='.$cformemail.'&content='.rawurlencode($chtmlcontent).'&recipients='.$cto;
            //$customerhead = file_get_contents($url);

         


            // invoice email
       
            //$basicUrl = $this->url;
            $chtmlcontent = file_get_contents('edm/invoice.php');
          
    
        
            $chtmlcontent = str_replace('{address}', 'C-9 Mahindra Park - Pankha Road,New Delhi', $chtmlcontent);
            $chtmlcontent = str_replace('{state}', 'Delhi', $chtmlcontent);
            $chtmlcontent = str_replace('{order_no}','1442563230', $chtmlcontent);
            $chtmlcontent = str_replace('{custid}', '1439394590', $chtmlcontent);
            $chtmlcontent = str_replace('{order_items}', $order_items, $chtmlcontent);
            $chtmlcontent = str_replace('{discount}', '197348.26', $chtmlcontent);
            $chtmlcontent = str_replace('{subtotal}', '19.74', $chtmlcontent);
            $chtmlcontent = str_replace('{tax}', '2.76', $chtmlcontent);
            $chtmlcontent = str_replace('{order_total}', '22.5', $chtmlcontent);
            $chtmlcontent = str_replace('{currentdate}', date('d-m-Y', time()), $chtmlcontent);
            
            $cbcc = 'hemant@pulpstrategy.com';
         
        
          
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
