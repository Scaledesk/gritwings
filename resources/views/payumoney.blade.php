
<!DOCTYPE html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>
<body style="visibility: hidden">

<?php
// $salt='3sf0jURk';
// $key='C0Dr8m';
$salt='eCwWELxi';
$key='gtKFFx';
$phone=$mobile;
if($amount==0){
  $amount=100;
}

$curl='http://localhost/payumoney/cancel';
$txnid='PLS-10061-'.rand(999,9999);http://localhost:3000/#/user-dashboard
        print_r([
                'sha512',$key.'|'.$txnid.'|'.$amount.'|'.$product_info.'|'.$first_name.'|'.$email.'|||||||||||'.$salt
        ]);
$hash = hash('sha512',$key.'|'.$txnid.'|'.$amount.'|'.$product_info.'|'.$first_name.'|'.$email.'|||||||||||'.$salt);
?>
<form id="myform" action='https://test.payu.in/_payment' method='post'>
  First Name<input type="text" name="firstname" value="<?php echo $first_name; ?>" /><br/>
  Last Name<input type="text" name="lastname" value="<?php echo $last_name; ?>"/><br/>
  <input type="hidden" name="surl" value="<?php echo $surl; ?>" />
  Mobile<input type="text" name="phone" value="<?php echo $phone; ?>" /><br/>
  <input type="hidden" name="key" value="<?php echo $key; ?>" />
  <input type="hidden" name="hash" value = "<?php echo $hash; ?>" />
  <input type="hidden" name="curl" value="<?php echo $curl; ?>" />
  <input type="hidden" name="furl" value="<?php echo $furl; ?>" />
  <input type="hidden" name="txnid" value="<?php echo $txnid; ?>" />
  <input type="hidden" name="productinfo" value="<?php echo $product_info; ?>" />
 Amount<input type="number" name="amount" value="<?php echo $amount; ?>" /><br/>
  Email<input type="email" name="email" value="<?php echo $email; ?>" /><br/>
  <input type= "submit" value="submit">

</form>
<script>
    $('#myform').submit();
</script>
</body></html>  