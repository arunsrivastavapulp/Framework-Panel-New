 <?php
  $date = date('Y/m/d h:i:s', time());
  $countryName='in';
  $countryCode ='91';
    $code_name = $countryName . '(+' . $countryCode . ')';
 $mainurl = 'http://182.74.47.186/universus/pulp_signup_api_new.php?customerid=&fname=Hemant&lname=gariya&company=pulp&email=hh12@jhg.com&mobileno=8742997059&app_type=1&app_template=2&created_date=' . $date . '&type=SIGNUP&organisation_size=4&industry=k&cust_remarks=k&ccode=' . $code_name;
$url = str_replace(' ', '-', $mainurl);
        ?>


<button>Get API check</button>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("button").click(function(){
        $.ajax({url: '<?php echo $url;?>', success: function(result){
            alert('success');
            console.log(result);
        }});
    });
});
</script>     