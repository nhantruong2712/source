<html>
<head>
<base href="<?=BASE_URL?>" />

<style>
BODY, TD {
	color:#004c93;
	font-family: Arial;
	font-size: 12px;
}
A  {
	color:#B34505;
	font-family:tahoma, arial, verdana,sans-serif;
	text-decoration: none;
	font-size: 12px;
	
}
A:hover{
	color:#5F7E06;
	font-size: 12px;
	text-decoration: none;
}
</style>
<title>Đang chuyển trang</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="REFRESH" content="1; url=<?=$location?>" />
</head>
<body>
  <center>
  <table border="0" width="500" align="center" height="200" cellspacing="0" style="border: 1px solid #CBE2EB">
    <tr>
      <td align="center" class=textbody><br/>
<?=$message?><br /><br /><img src="bms/images/wait.gif" /><br />

(<a class="cart_payment" href="<?=$location?>">Click vào đây nếu bạn không muốn chờ lâu</a>)
</td>
    </tr>
  </table>
  </center>
 </body>
 </html>