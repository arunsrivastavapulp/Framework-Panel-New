<html>
    <head>
        <title> Non-Seamless-kit</title>
    </head>
    <body>
    <center>

        <?php include('Crypto.php') ?>
        <?php
        session_start();
        error_reporting(0);

        if (isset($_SESSION['totalPrice'])) {
            $totalPrice = $_SESSION['totalPrice'];
        }

        if (isset($_SESSION['total_saving'])) {
            $total_saving = $_SESSION['total_saving'];
        }
        if (isset($_SESSION['orderid'])) {
            $orderid = $_SESSION['orderid'];
        }
        $check = 0;
        if (isset($_POST)) {
            if ($_POST['order_id'] != $orderid) {
                $_POST['order_id'] = $orderid;
                if ($_POST['amount'] != $totalPrice) {

                    $_POST['amount'] = $totalPrice;

                    $check = 1;
                } else {
                    $check = 1;
                }
            } else {
                if ($_POST['amount'] != $totalPrice) {

                    $_POST['amount'] = $totalPrice;

                    $check = 1;
                } else {
                    $check = 1;
                }
            }
            $_SESSION['part_payment_id']=$_POST['part_payment_id'];
            $_SESSION['part_total_amount']=$totalPrice;
        }

        if ($check == 1) {
            $merchant_data = '72267';
            $working_key = '011B4AB3FAF2FCCE3B0678D730048C42'; //Shared by CCAVENUES
            $access_code = 'AVLI05CH98AO63ILOA'; //Shared by CCAVENUES


            foreach ($_POST as $key => $value) {
                $merchant_data.=$key . '=' . $value . '&';
            }
            $encrypted_data = encrypt($merchant_data, $working_key); // Method for encrypting the data.
            ?>
            <form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
            <?php
            echo "<input type=hidden name=encRequest value=$encrypted_data>";
            echo "<input type=hidden name=access_code value=$access_code>";
            ?>
            </form>
            <script language='javascript'>document.redirect.submit();</script>
                <?php
            }
            ?>

    </center>

</body>
</html>