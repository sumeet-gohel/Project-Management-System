<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
$Merchant_Id = "CC52541";//
$Amount = 15000;
$Order_Id ="C12541";//unique Id that should be passed to payment gateway
$WorkingKey = "TRG5241";//Given to merchant by ccavenue
$Redirect_Url ="www.yahoo.com";
$Checksum = getCheckSum($Merchant_Id,$Amount,$Order_Id ,$Redirect_Url,$WorkingKey);                 // Validate All value
//creating a signature using the given details for security reasons
function getchecksum($MerchantId,$Amount,$OrderId ,$URL,$WorkingKey)
{
$str ="$MerchantId|$OrderId|$Amount|$URL|$WorkingKey";
$adler = 1;
$adler = adler32($adler,$str);
return $adler;
}
//functions
function adler32($adler , $str)
{
$BASE = 65521 ;
$s1 = $adler & 0xffff ;
$s2 = ($adler >> 16) & 0xffff;
for($i = 0 ; $i < strlen($str) ; $i++)
{
$s1 = ($s1 + Ord($str[$i])) % $BASE ;
$s2 = ($s2 + $s1) % $BASE ;
}
return leftshift($s2 , 16) + $s1;
}
//leftshift function
function leftshift($str , $num)
{
$str = DecBin($str);
for( $i = 0 ; $i < (64 - strlen($str)) ; $i++)
$str = "0".$str ;
for($i = 0 ; $i < $num ; $i++)
{
$str = $str."0";
$str = substr($str , 1 ) ;
}
return cdec($str) ;
}
//cdec function
function cdec($num)
{
for ($n = 0 ; $n < strlen($num) ; $n++)
{
$temp = $num[$n] ;
$dec = $dec + $temp*pow(2 , strlen($num) - $n - 1);
}
return $dec;
}
?>
<form id="ccavenue" method="post" action="https://www.ccavenue.com/shopzone/cc_details.jsp">
<input type=hidden name="Merchant_Id" value="WW52521">
<input type="hidden" name="Amount" value="15000">
<input type="hidden" name="Order_Id" value="OD1011">
<input type="hidden" name="Redirect_Url" value="www.yahoo.com">
<input type="hidden" name="TxnType" value="A">
<input type="hidden" name="ActionID" value="TXN">
<input type="hidden" name="Checksum" value="<?php echo $Checksum; ?>">
<input type="hidden" name="billing_cust_name" value="sumeet">
<input type="hidden" name="billing_cust_address" value="rajkot">
<input type="hidden" name="billing_cust_country" value="india">
<input type="hidden" name="billing_cust_state" value="gujarat">
<input type="hidden" name="billing_cust_city" value="rajkot">
<input type="hidden" name="billing_zip" value="360001">
<input type="hidden" name="billing_cust_tel" value="2225754">
<input type="hidden" name="billing_cust_email" value="sumeet.gohel@yahoo.com">
<input type="hidden" name="delivery_cust_name" value="sumeet">
<input type="hidden" name="delivery_cust_address" value="Rajkot">
<input type="hidden" name="delivery_cust_country" value="India">
<input type="hidden" name="delivery_cust_state" value="Gujarat">
<input type="hidden" name="delivery_cust_tel" value="2225423">
<input type="hidden" name="delivery_cust_notes" value="this is a test">
<input type="hidden" name="Merchant_Param" value="">
<input type="hidden" name="billing_zip_code" value="360001">
<input type="hidden" name="delivery_cust_city" value="rajkot">

<input type="submit" value="Buy Now" />
</form>
</body>
</html>