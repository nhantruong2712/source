<?php
set_time_limit(0);
$xacnhan=empty($_GET['ma'])?'':$_GET['ma'];
$gianhang=empty($_GET['name'])?'':$_GET['name'];

if(!$xacnhan || !$gianhang){
    die('Error');
}
date_default_timezone_set('Asia/Saigon');
error_reporting(0);

include "cf.php";

$link=mysqli_connect("$host", "$username1", "$password")or die("cannot connect to server");
//if (!$link) {
//    die('Could not connect: ' /*. mysqli_error()*/);
//}
 
mysqli_select_db($link,"qlt") or die("cannot select DB");
$sql1="SELECT * FROM username WHERE activation='$xacnhan'";
$query1=mysqli_query($link,$sql1);
 
$count=mysqli_num_rows($query1);
if($count){

   $sql4= "UPDATE username SET activation='' WHERE activation='$xacnhan'";
   
   $query4=mysqli_query($link,$sql4);
    //echo 'Kích hoạt thành công';
	$db='qlt_'.$gianhang;
	$sql11= "CREATE DATABASE ".$db;
    
	$query11=mysqli_query($link,$sql11);
	mysqli_select_db($link,"$db") or die("cannot select DB");
    
	$sql12='CREATE TABLE IF NOT EXISTS `bankaccount` (
  `id` int(11) NOT NULL auto_increment,
  `bank` varchar(100) NOT NULL,
  `no` varchar(50) NOT NULL,
  `note` mediumtext NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
    $sql13='CREATE TABLE IF NOT EXISTS `bill_type` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `type` tinyint(2) NOT NULL default "1",
  `note` text NOT NULL,
  `used` tinyint(2) NOT NULL default "1",
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 

	$sql14='CREATE TABLE IF NOT EXISTS `branch` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `address` varchar(400) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

	$sql15='CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL auto_increment,
  `type` tinyint(2) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `address` varchar(400) NOT NULL,
  `zone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` tinyint(2) NOT NULL,
  `birthday` date NOT NULL,
  `group` int(11) NOT NULL,
  `company` varchar(200) NOT NULL,
  `taxcode` varchar(100) NOT NULL,
  `note` text NOT NULL,
  `branch` int(11) NOT NULL,
  `debt` int(11) NOT NULL,
  `sold` int(11) NOT NULL,
  `organization` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 
	$sql16='CREATE TABLE IF NOT EXISTS `customer_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `discount` varchar(50) NOT NULL,
  `type` tinyint(2) NOT NULL default "0",
  `note` text NOT NULL,
  `branch` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 

	$sql17='CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL auto_increment,
  `type` tinyint(3) NOT NULL,
  `status` tinyint(3) NOT NULL,
  `code` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  `branch` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `note` mediumtext NOT NULL,
  `price` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `discounttype` tinyint(2) NOT NULL,
  `payment` tinyint(3) NOT NULL,
  `account` int(11) NOT NULL,
  `paying` int(11) NOT NULL,
  `table` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `addtoaccount` tinyint(2) NOT NULL,
  `create` int(11) NOT NULL,
  `invoice` int(11) NOT NULL,
  `cashflowtype` tinyint(3) NOT NULL,
  `partnertype` tinyint(3) NOT NULL,
  `partner` int(11) NOT NULL,
  `used` tinyint(2) NOT NULL default "1",
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 
	$sql18='CREATE TABLE IF NOT EXISTS `order_product` (
  `id` int(11) NOT NULL auto_increment,
  `order` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` varchar(50) NOT NULL,
  `discount` int(11) NOT NULL,
  `note` text NOT NULL,
  `deliveryqty` int(11) NOT NULL,
  `processingqty` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 
	$sql19='CREATE TABLE IF NOT EXISTS `partner` (
  `id` int(11) NOT NULL auto_increment,
  `type` tinyint(2) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `address` varchar(400) NOT NULL,
  `zone` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` tinyint(2) NOT NULL,
  `birthday` date NOT NULL,
  `group` int(11) NOT NULL,
  `company` varchar(200) NOT NULL,
  `taxcode` varchar(100) NOT NULL,
  `note` text NOT NULL,
  `branch` int(11) NOT NULL,
  `debt` int(11) NOT NULL,
  `sold` int(11) NOT NULL,
  `organization` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 
	$sql20='CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` int(11) NOT NULL,
  `sell` tinyint(2) NOT NULL,
  `image` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `type` tinyint(2) NOT NULL,
  `unit` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL default "1",
  `parent` int(11) NOT NULL,
  `expire` date NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `code` (`code`),
  KEY `category` (`category`),
  KEY `unit` (`unit`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 
	$sql21='CREATE TABLE IF NOT EXISTS `product_branch` (
  `id` int(11) NOT NULL auto_increment,
  `product` int(11) NOT NULL,
  `price` int(11) NOT NULL COMMENT "gia ban",
  `price2` int(11) NOT NULL COMMENT "gia von",
  `pricelast` int(11) NOT NULL,
  `branch` int(11) NOT NULL,
  `quantity` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `product` (`product`),
  KEY `branch` (`branch`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 
	$sql22='CREATE TABLE IF NOT EXISTS `product_category` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 

	$sql23='CREATE TABLE IF NOT EXISTS `product_formula` (
  `id` int(11) NOT NULL auto_increment,
  `product` int(11) NOT NULL,
  `child` int(11) NOT NULL,
  `quantity` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `product` (`product`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 
	$sql24='CREATE TABLE IF NOT EXISTS `product_unit` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `value` varchar(50) NOT NULL,
  `product_code` varchar(20) NOT NULL,
  `main` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `product_code` (`product_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 
	$sql25='CREATE TABLE IF NOT EXISTS `supplier` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `address` varchar(400) NOT NULL,
  `taxcode` varchar(50) NOT NULL,
  `note` text NOT NULL,
  `branch` int(11) NOT NULL,
  `debt` int(11) NOT NULL,
  `bought` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 
	$sql26='CREATE TABLE IF NOT EXISTS `table` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `note` text NOT NULL,
  `group` int(11) NOT NULL,
  `branch` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 

	$sql27='CREATE TABLE IF NOT EXISTS `table_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `branch` int(11) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
 

	$sql28='CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `birthday` date NOT NULL default \'1970-01-01\',
  `address` varchar(400) NOT NULL,
  `name` varchar(400) NOT NULL,
  `admin` tinyint(2) NOT NULL,
  `status` tinyint(2) NOT NULL default \'1\',  
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

$qqq = mysqli_fetch_assoc($query1);
$u1=$qqq['namelogin'];
$p1=$qqq['password'];
$fmail1=$qqq['email'];
$t1=$qqq['phone'];
$a1=$qqq['address'];
$n1=$qqq['name'];
$tt=$qqq['type'];

$sql31="insert into user(`id`,username,password,email,phone,address,name,`admin`) values(1,'$u1','$p1','$fmail1','$t1','$a1','$n1',1)";

$sql33="INSERT INTO `branch` (`id`, `name`, `address`, `email`, `phone`) VALUES
(1, 'Trung tâm', '$a1', '$fmail1', '$t1') ; ";

	$sql29='CREATE TABLE IF NOT EXISTS `user_permission` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `branch` int(11) NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user_2` (`user`,`branch`),
  KEY `user` (`user`),
  KEY `branch` (`branch`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';
/* 
	$sql30='CREATE TABLE IF NOT EXISTS `zone` (
  `id` int(11) NOT NULL auto_increment,
  `level` tinyint(2) NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `description` varchar(255) collate utf8_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL,
  `a` varchar(200) character set ascii NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `a` (`a`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1';

$sql32 = "INSERT INTO `zone` (`id`, `level`, `name`, `description`, `parent`, `a`) VALUES
(9, 1, 'Đồng Tháp', 'Tỉnh', 0, 'dong-thap'),
(10, 1, 'An Giang', 'Tỉnh', 0, 'an-giang'),
(11, 1, 'Bạc Liêu', 'Tỉnh', 0, 'bac-lieu'),
(12, 1, 'Bến Tre', 'Tỉnh', 0, 'ben-tre'),
(13, 1, 'Cần Thơ', 'Thành phố', 0, 'can-tho'),
(14, 1, 'Cà Mau', 'Tỉnh', 0, 'ca-mau'),
(15, 1, 'Hậu Giang', 'Tỉnh', 0, 'hau-giang'),
(16, 1, 'Kiên Giang', 'Tỉnh', 0, 'kien-giang'),
(17, 1, 'Long An', 'Tỉnh', 0, 'long-an'),
(18, 1, 'Sóc Trăng', 'Tỉnh', 0, 'soc-trang'),
(19, 1, 'Tiền Giang', 'Tỉnh', 0, 'tien-giang'),
(20, 1, 'Trà Vinh', 'Tỉnh', 0, 'tra-vinh'),
(21, 1, 'Vĩnh Long', 'Tỉnh', 0, 'vinh-long'),
(22, 1, 'Bắc Ninh', 'Tỉnh', 0, 'bac-ninh'),
(23, 1, 'Hải Dương', 'Tỉnh', 0, 'hai-duong'),
(24, 1, 'Hải Phòng', 'Thành phố', 0, 'hai-phong'),
(25, 1, 'Hưng Yên', 'Tỉnh', 0, 'hung-yen'),
(26, 1, 'Hà Nội', 'Thành phố', 0, 'ha-noi'),
(27, 1, 'Hà Nam', 'Tỉnh', 0, 'ha-nam'),
(28, 1, 'Hà Tây', 'Tỉnh', 0, 'ha-tay'),
(29, 1, 'Nam Định', 'Tỉnh', 0, 'nam-dinh'),
(30, 1, 'Ninh Bình', 'Tỉnh', 0, 'ninh-binh'),
(31, 1, 'Thái Bình', 'Tỉnh', 0, 'thai-binh'),
(32, 1, 'Vĩnh Phúc', 'Tỉnh', 0, 'vinh-phuc'),
(33, 1, 'Bắc Giang', 'Tỉnh', 0, 'bac-giang'),
(34, 1, 'Bắc Kạn', 'Tỉnh', 0, 'bac-kan'),
(35, 1, 'Cao Bằng', 'Tỉnh', 0, 'cao-bang'),
(36, 1, 'Hà Giang', 'Tỉnh', 0, 'ha-giang'),
(37, 1, 'Lạng Sơn', 'Tỉnh', 0, 'lang-son'),
(38, 1, 'Lào Cai', 'Tỉnh', 0, 'lao-cai'),
(39, 1, 'Phú Thọ', 'Tỉnh', 0, 'phu-tho'),
(40, 1, 'Quảng Ninh', 'Tỉnh', 0, 'quang-ninh'),
(41, 1, 'Thái Nguyên', 'Tỉnh', 0, 'thai-nguyen'),
(42, 1, 'Tuyên Quang', 'Tỉnh', 0, 'tuyen-quang'),
(43, 1, 'Yên Bái', 'Tỉnh', 0, 'yen-bai'),
(44, 1, 'Đồng Nai', 'Tỉnh', 0, 'dong-nai'),
(45, 1, 'Bà Rịa - Vũng Tàu', 'Tỉnh', 0, 'ba-ria-vung-tau'),
(46, 1, 'Bình Dương', 'Tỉnh', 0, 'binh-duong'),
(47, 1, 'Bình Phước', 'Tỉnh', 0, 'binh-phuoc'),
(48, 1, 'Bình Thuận', 'Tỉnh', 0, 'binh-thuan'),
(49, 1, 'Hồ Chí Minh', 'Thành phố', 0, 'ho-chi-minh'),
(50, 1, 'Ninh Thuận', 'Tỉnh', 0, 'ninh-thuan'),
(51, 1, 'Tây Ninh', 'Tỉnh', 0, 'tay-ninh'),
(52, 1, 'Hà Tĩnh', 'Tỉnh', 0, 'ha-tinh'),
(53, 1, 'Nghệ An', 'Tỉnh', 0, 'nghe-an'),
(54, 1, 'Quảng Bình', 'Tỉnh', 0, 'quang-binh'),
(55, 1, 'Quảng Trị', 'Tỉnh', 0, 'quang-tri'),
(56, 1, 'Thừa Thiên - Huế', 'Tỉnh', 0, 'thua-thien-hue'),
(57, 1, 'Thanh Hóa', 'Tỉnh', 0, 'thanh-hoa'),
(58, 1, 'Đà Nẵng', 'Thành phố', 0, 'da-nang'),
(59, 1, 'Bình Định', 'Tỉnh', 0, 'binh-dinh'),
(60, 1, 'Khánh Hòa', 'Tỉnh', 0, 'khanh-hoa'),
(61, 1, 'Phú Yên', 'Tỉnh', 0, 'phu-yen'),
(62, 1, 'Quảng Nam', 'Tỉnh', 0, 'quang-nam'),
(63, 1, 'Quảng Ngãi', 'Tỉnh', 0, 'quang-ngai'),
(64, 1, 'Điện Biên', 'Tỉnh', 0, 'dien-bien'),
(65, 1, 'Hòa Bình', 'Tỉnh', 0, 'hoa-binh'),
(66, 1, 'Lai Châu', 'Tỉnh', 0, 'lai-chau'),
(67, 1, 'Sơn La', 'Tỉnh', 0, 'son-la'),
(68, 1, 'Đắk Lắk', 'Tỉnh', 0, 'dak-lak'),
(69, 1, 'Đắk Nông', 'Tỉnh', 0, 'dak-nong'),
(70, 1, 'Gia Lai', 'Tỉnh', 0, 'gia-lai'),
(71, 1, 'Kon Tum', 'Tỉnh', 0, 'kon-tum'),
(72, 1, 'Lâm Đồng', 'Tỉnh', 0, 'lam-dong'),
(73, 2, 'Long Xuyên', 'Thành phố', 10, 'an-giang/long-xuyen'),
(74, 2, 'Phú Tân', 'Huyện', 10, 'an-giang/phu-tan'),
(75, 2, 'Tịnh Biên', 'Huyện', 10, 'an-giang/tinh-bien'),
(76, 2, 'Tân Châu', 'Huyện', 10, 'an-giang/tan-chau'),
(77, 2, 'Thoại Sơn', 'Huyện', 10, 'an-giang/thoai-son'),
(78, 2, 'Tri Tôn', 'Huyện', 10, 'an-giang/tri-ton'),
(79, 2, 'Đông Hải', 'Huyện', 11, 'bac-lieu/dong-hai'),
(80, 2, 'Bạc Liêu', 'Thị xã', 11, 'bac-lieu/bac-lieu'),
(81, 2, 'Giá Rai', 'Huyện', 11, 'bac-lieu/gia-rai'),
(82, 2, 'Hồng Dân', 'Huyện', 11, 'bac-lieu/hong-dan'),
(83, 2, 'Hòa Bình', 'Huyện', 11, 'bac-lieu/hoa-binh'),
(84, 2, 'Phước Long', 'Huyện', 11, 'bac-lieu/phuoc-long'),
(85, 2, 'Vĩnh Lợi', 'Huyện', 11, 'bac-lieu/vinh-loi'),
(86, 2, 'Bến Tre', 'Thị xã', 12, 'ben-tre/ben-tre'),
(87, 2, 'Ba Tri', 'Huyện', 12, 'ben-tre/ba-tri'),
(88, 2, 'Bình Đại', 'Huyện', 12, 'ben-tre/binh-dai'),
(89, 2, 'Chợ Lách', 'Huyện', 12, 'ben-tre/cho-lach'),
(90, 2, 'Châu Thành', 'Huyện', 12, 'ben-tre/chau-thanh'),
(91, 2, 'Tam Dương', 'Huyện', 32, 'vinh-phuc/tam-duong'),
(92, 2, 'Tam Đảo', 'Huyện', 32, 'vinh-phuc/tam-dao'),
(94, 2, 'Vĩnh Tường', 'Huyện', 32, 'vinh-phuc/vinh-tuong'),
(95, 2, 'Vĩnh Yên', 'Thị xã', 32, 'vinh-phuc/vinh-yen'),
(96, 2, 'Yên Lạc', 'Huyện', 32, 'vinh-phuc/yen-lac'),
(97, 2, 'Bắc Giang', 'Thị xã', 33, 'bac-giang/bac-giang'),
(98, 2, 'Hiệp Hòa', 'Huyện', 33, 'bac-giang/hiep-hoa'),
(99, 2, 'Lục Nam', 'Huyện', 33, 'bac-giang/luc-nam'),
(100, 2, 'Lục Ngạn', 'Huyện', 33, 'bac-giang/luc-ngan'),
(101, 2, 'Lạng Giang', 'Huyện', 33, 'bac-giang/lang-giang'),
(102, 2, 'Sơn Động', 'Huyện', 33, 'bac-giang/son-dong'),
(103, 2, 'Tân Yên', 'Huyện', 33, 'bac-giang/tan-yen'),
(104, 2, 'Việt Yên', 'Huyện', 33, 'bac-giang/viet-yen'),
(105, 2, 'Yên Dũng', 'Huyện', 33, 'bac-giang/yen-dung'),
(106, 2, 'Yên Thế', 'Huyện', 33, 'bac-giang/yen-the'),
(107, 2, 'Bạch Thông', 'Huyện', 34, 'bac-kan/bach-thong'),
(108, 2, 'Ba Bể', 'Huyện', 34, 'bac-kan/ba-be'),
(109, 2, 'Bắc Kạn', 'Thị xã', 34, 'bac-kan/bac-kan'),
(110, 2, 'Chợ Đồn', 'Huyện', 34, 'bac-kan/cho-don'),
(111, 2, 'Cao Lãnh', 'Thị xã', 9, 'dong-thap/tp-cao-lanh'),
(112, 2, 'Cao Lãnh', 'Huyện', 9, 'dong-thap/cao-lanh'),
(113, 2, 'Châu Thành', 'Huyện', 9, 'dong-thap/chau-thanh'),
(114, 2, 'Hồng Ngự', 'Huyện', 9, 'dong-thap/hong-ngu'),
(115, 2, 'Lấp Vò', 'Huyện', 9, 'dong-thap/lap-vo'),
(116, 2, 'Lai Vung', 'Huyện', 9, 'dong-thap/lai-vung'),
(117, 2, 'Sa Đéc', 'Thị xã', 9, 'dong-thap/sa-dec'),
(118, 2, 'Tam Nông', 'Huyện', 9, 'dong-thap/tam-nong'),
(119, 2, 'Tân Hồng', 'Huyện', 9, 'dong-thap/tan-hong'),
(120, 2, 'Thanh Bình', 'Huyện', 9, 'dong-thap/thanh-binh'),
(121, 2, 'Tháp Mười', 'Huyện', 9, 'dong-thap/thap-muoi'),
(122, 2, 'An Phú', 'Huyện', 10, 'an-giang/an-phu'),
(123, 2, 'Chợ Mới', 'Huyện', 10, 'an-giang/cho-moi'),
(124, 2, 'Châu Đốc', 'Thị xã', 10, 'an-giang/chau-doc'),
(125, 2, 'Châu Phú', 'Huyện', 10, 'an-giang/chau-phu'),
(126, 2, 'Châu Thành', 'Huyện', 10, 'an-giang/chau-thanh'),
(127, 2, 'Giồng Trôm', 'Huyện', 12, 'ben-tre/giong-trom'),
(128, 2, 'Mỏ Cày', 'Huyện', 12, 'ben-tre/mo-cay'),
(129, 2, 'Thạnh Phú', 'Huyện', 12, 'ben-tre/thanh-phu'),
(130, 2, 'Bình Thủy', 'Quận', 13, 'can-tho/binh-thuy'),
(131, 2, 'Cờ Đỏ', 'Huyện', 13, 'can-tho/co-do'),
(132, 2, 'Cái Răng', 'Quận', 13, 'can-tho/cai-rang'),
(133, 2, 'Ninh Kiều', 'Quận', 13, 'can-tho/ninh-kieu'),
(134, 2, 'Ô Môn', 'Quận', 13, 'can-tho/o-mon'),
(135, 2, 'Phong Điền', 'Huyện', 13, 'can-tho/phong-dien'),
(136, 2, 'Thốt Nốt', 'Huyện', 13, 'can-tho/thot-not'),
(137, 2, 'Vĩnh Thạnh', 'Huyện', 13, 'can-tho/vinh-thanh'),
(138, 2, 'Đầm Dơi', 'Huyện', 14, 'ca-mau/dam-doi'),
(139, 2, 'Cà Mau', 'Thành phố', 14, 'ca-mau/ca-mau'),
(140, 2, 'Cái Nước', 'Huyện', 14, 'ca-mau/cai-nuoc'),
(141, 2, 'Năm Căn', 'Huyện', 14, 'ca-mau/nam-can'),
(142, 2, 'Ngọc Hiển', 'Huyện', 14, 'ca-mau/ngoc-hien'),
(143, 2, 'Phú Tân', 'Huyện', 14, 'ca-mau/phu-tan'),
(144, 2, 'Thới Bình', 'Huyện', 14, 'ca-mau/thoi-binh'),
(145, 2, 'Trần Văn Thời', 'Huyện', 14, 'ca-mau/tran-van-thoi'),
(146, 2, 'U Minh', 'Huyện', 14, 'ca-mau/u-minh'),
(147, 2, 'Châu Thành A', 'Huyện', 15, 'hau-giang/chau-thanh-a'),
(148, 2, 'Châu Thành', 'Huyện', 15, 'hau-giang/chau-thanh'),
(149, 2, 'Long Mỹ', 'Huyện', 15, 'hau-giang/long-my'),
(150, 2, 'Phụng Hiệp', 'Huyện', 15, 'hau-giang/phung-hiep'),
(151, 2, 'Vị Thanh', 'Thị xã', 15, 'hau-giang/vi-thanh'),
(152, 2, 'Vị Thủy', 'Huyện', 15, 'hau-giang/vi-thuy'),
(153, 2, 'An Biên', 'Huyện', 16, 'kien-giang/an-bien'),
(154, 2, 'An Minh', 'Huyện', 16, 'kien-giang/an-minh'),
(155, 2, 'Châu Thành', 'Huyện', 16, 'kien-giang/chau-thanh'),
(156, 2, 'Giồng Riềng', 'Huyện', 16, 'kien-giang/giong-gieng'),
(157, 2, 'Gò Quao', 'Huyện', 16, 'kien-giang/go-quao'),
(158, 2, 'Hà Tiên', 'Thị xã', 16, 'kien-giang/ha-tien'),
(159, 2, 'Hòn Đất', 'Huyện', 16, 'kien-giang/hon-dat'),
(160, 2, 'Kiên Hải', 'Huyện', 16, 'kien-giang/kien-hai'),
(161, 2, 'Kiên Lương', 'Huyện', 16, 'kien-giang/kien-luong'),
(162, 2, 'Phú Quốc', 'Huyện', 16, 'kien-giang/phu-quoc'),
(163, 2, 'Rạch Giá', 'Thị xã', 16, 'kien-giang/rach-gia'),
(164, 2, 'Tân Hiệp', 'Huyện', 16, 'kien-giang/tan-hiep'),
(165, 2, 'U Minh Thượng', 'Huyện', 16, 'kien-giang/u-minh-thuong'),
(166, 2, 'Vĩnh Thuận', 'Huyện', 16, 'kien-giang/vinh-thuan'),
(167, 2, 'Đức Hòa', 'Huyện', 17, 'long-an/duc-hoa'),
(168, 2, 'Đức Huệ', 'Huyện', 17, 'long-an/duc-hue'),
(169, 2, 'Bến Lức', 'Huyện', 17, 'long-an/ben-luc'),
(170, 2, 'Cần Đước', 'Huyện', 17, 'long-an/can-duoc'),
(171, 2, 'Cần Giuộc', 'Huyện', 17, 'long-an/can-giuoc'),
(172, 2, 'Châu Thành', 'Huyện', 17, 'long-an/chau-thanh'),
(173, 2, 'Mộc Hóa', 'Huyện', 17, 'long-an/moc-hoa'),
(174, 2, 'Tân An', 'Thị xã', 17, 'long-an/tan-an'),
(175, 2, 'Tân Hưng', 'Huyện', 17, 'long-an/tan-hung'),
(176, 2, 'Tân Thạnh', 'Huyện', 17, 'long-an/tan-thanh'),
(177, 2, 'Tân Trụ', 'Huyện', 17, 'long-an/tan-tru'),
(178, 2, 'Thủ Thừa', 'Huyện', 17, 'long-an/thu-thua'),
(179, 2, 'Thạnh Hóa', 'Huyện', 17, 'long-an/thanh-hoa'),
(180, 2, 'Vĩnh Hưng', 'Huyện', 17, 'long-an/vinh-hung'),
(181, 2, 'Châu Thành', 'Huyện', 18, 'soc-trang/chau-thanh'),
(182, 2, 'Cù Lao Dung', 'Huyện', 18, 'soc-trang/cu-lao-dung'),
(183, 2, 'Kế Sách', 'Huyện', 18, 'soc-trang/ke-sach'),
(184, 2, 'Sóc Trăng', 'Thị xã', 18, 'soc-trang/soc-trang'),
(185, 2, 'Long Phú', 'Huyện', 18, 'soc-trang/long-phu'),
(186, 2, 'Mỹ Tú', 'Huyện', 18, 'soc-trang/my-tu'),
(187, 2, 'Mỹ Xuyên', 'Huyện', 18, 'soc-trang/my-xuyen'),
(188, 2, 'Ngã Năm', 'Huyện', 18, 'soc-trang/nga-nam'),
(189, 2, 'Thạnh Trị', 'Huyện', 18, 'soc-trang/thanh-tri'),
(190, 2, 'Vĩnh Châu', 'Huyện', 18, 'soc-trang/vinh-chau'),
(191, 2, 'Cái Bè', 'Huyện', 19, 'tien-giang/cai-be'),
(192, 2, 'Cai Lậy', 'Huyện', 19, 'tien-giang/cai-lay'),
(193, 2, 'Chợ Gạo', 'Huyện', 19, 'tien-giang/cho-gao'),
(194, 2, 'Châu Thành', 'Huyện', 19, 'tien-giang/chau-thanh'),
(195, 2, 'Gò Công Đông', 'Huyện', 19, 'tien-giang/go-cong-dong'),
(196, 2, 'Gò Công Tây', 'Huyện', 19, 'tien-giang/go-cong-tay'),
(197, 2, 'Gò Công', 'Thị xã', 19, 'tien-giang/go-cong'),
(198, 2, 'Mỹ Tho', 'Thành phố', 19, 'tien-giang/my-tho'),
(199, 2, 'Tân Phước', 'Huyện', 19, 'tien-giang/tan-phuoc'),
(200, 2, 'Tân Phú Đông', 'Huyện', 19, 'tien-giang/tan-phu-dong'),
(201, 2, 'Cầu Kè', 'Huyện', 20, 'tra-vinh/cau-ke'),
(202, 2, 'Cầu Ngang', 'Huyện', 20, 'tra-vinh/cau-ngang'),
(203, 2, 'Càng Long', 'Huyện', 20, 'tra-vinh/cang-long'),
(204, 2, 'Châu Thành', 'Huyện', 20, 'tra-vinh/chau-thanh'),
(205, 2, 'Duyên Hải', 'Huyện', 20, 'tra-vinh/duyen-hai'),
(206, 2, 'Tiểu Cần', 'Huyện', 20, 'tra-vinh/tieu-can'),
(207, 2, 'Trà Cú', 'Huyện', 20, 'tra-vinh/tra-cu'),
(208, 2, 'Trà Vinh', 'Thị xã', 20, 'tra-vinh/tra-vinh'),
(209, 2, 'Bình Minh', 'Huyện', 21, 'vinh-long/binh-minh'),
(210, 2, 'Bình Tân', 'Huyện', 21, 'vinh-long/binh-tan'),
(211, 2, 'Long Hồ', 'Huyện', 21, 'vinh-long/long-ho'),
(212, 2, 'Mang Thít', 'Huyện', 21, 'vinh-long/mang-thit'),
(213, 2, 'Tam Bình', 'Huyện', 21, 'vinh-long/tam-binh'),
(214, 2, 'Trà Ôn', 'Huyện', 21, 'vinh-long/tra-on'),
(215, 2, 'Vũng Liêm', 'Huyện', 21, 'vinh-long/vung-liem'),
(216, 2, 'Vĩnh Long', 'Thị xã', 21, 'vinh-long/vinh-long'),
(217, 2, 'Bắc Ninh', 'Thị xã', 22, 'bac-ninh/bac-ninh'),
(218, 2, 'Gia Bình', 'Huyện', 22, 'bac-ninh/gia-binh'),
(219, 2, 'Lương Tài', 'Huyện', 22, 'bac-ninh/luong-tai'),
(220, 2, 'Quế Võ', 'Huyện', 22, 'bac-ninh/que-vo'),
(221, 2, 'Từ Sơn', 'Huyện', 22, 'bac-ninh/tu-son'),
(222, 2, 'Thuận Thành', 'Huyện', 22, 'bac-ninh/thuan-thanh'),
(223, 2, 'Tiên Du', 'Huyện', 22, 'bac-ninh/tien-du'),
(224, 2, 'Yên Phong', 'Huyện', 22, 'bac-ninh/yen-phong'),
(225, 2, 'Bình Giang', 'Huyện', 23, 'hai-duong/binh-giang'),
(226, 2, 'Cẩm Giàng', 'Huyện', 23, 'hai-duong/cam-giang'),
(227, 2, 'Chí Linh', 'Huyện', 23, 'hai-duong/chi-linh'),
(228, 2, 'Gia Lộc', 'Huyện', 23, 'hai-duong/gia-loc'),
(229, 2, 'Hải Dương', 'Thành phố', 23, 'hai-duong/hai-duong'),
(230, 2, 'Kim Thành', 'Huyện', 23, 'hai-duong/kim-thanh'),
(231, 2, 'Kinh Môn', 'Huyện', 23, 'hai-duong/kinh-mon'),
(232, 2, 'Nam Sách', 'Huyện', 23, 'hai-duong/nam-sach'),
(233, 2, 'Ninh Giang', 'Huyện', 23, 'hai-duong/ninh-giang'),
(234, 2, 'Tứ Kỳ', 'Huyện', 23, 'hai-duong/tu-ky'),
(235, 2, 'Thanh Hà', 'Huyện', 23, 'hai-duong/thanh-ha'),
(236, 2, 'Thanh Miện', 'Huyện', 23, 'hai-duong/thanh-mien'),
(237, 2, 'Đồ Sơn', 'Thị xã', 24, 'hai-phong/do-son'),
(238, 2, 'An Dương', 'Huyện', 24, 'hai-phong/an-duong'),
(239, 2, 'An Lão', 'Huyện', 24, 'hai-phong/an-lao'),
(240, 2, 'Bạch Long Vỹ', 'Island', 24, 'hai-phong/bach-long-vi'),
(241, 2, 'Cát Hải', 'Island', 24, 'hai-phong/cat-hai'),
(242, 2, 'Hải An', 'Quận', 24, 'hai-phong/hai-an'),
(243, 2, 'Hồng Bàng', 'Quận', 24, 'hai-phong/hong-bang'),
(244, 2, 'Kiến An', 'Quận', 24, 'hai-phong/kien-an'),
(245, 2, 'Kiến Thụy', 'Huyện', 24, 'hai-phong/kien-thuy'),
(246, 2, 'Lê Chân', 'Quận', 24, 'hai-phong/le-chan'),
(247, 2, 'Ngô Quyền', 'Quận', 24, 'hai-phong/ngo-quyen'),
(248, 2, 'Thủy Nguyên', 'Huyện', 24, 'hai-phong/thuy-nguyen'),
(249, 2, 'Tiên Lãng', 'Huyện', 24, 'hai-phong/tien-lang'),
(250, 2, 'Vĩnh Bảo', 'Huyện', 24, 'hai-phong/vinh-bao'),
(251, 2, 'Ân Thi', 'Huyện', 25, 'hung-yen/an-thi'),
(252, 2, 'Hưng Yên', 'Thị xã', 25, 'hung-yen/hung-yen'),
(253, 2, 'Khoái Châu', 'Huyện', 25, 'hung-yen/khoai-chau'),
(254, 2, 'Kim Động', 'Huyện', 25, 'hung-yen/kim-dong'),
(255, 2, 'Mỹ Hào', 'Huyện', 25, 'hung-yen/my-hao'),
(256, 2, 'Phù Cừ', 'Huyện', 25, 'hung-yen/phu-cu'),
(257, 2, 'Tiên Lữ', 'Huyện', 25, 'hung-yen/tien-lu'),
(258, 2, 'Văn Giang', 'Huyện', 25, 'hung-yen/van-giang'),
(259, 2, 'Văn Lâm', 'Huyện', 25, 'hung-yen/van-lam'),
(260, 2, 'Yên Mỹ', 'Huyện', 25, 'hung-yen/yen-my'),
(261, 2, 'Đống Đa', 'Quận', 26, 'ha-noi/dong-da'),
(262, 2, 'Đông Anh', 'Huyện', 26, 'ha-noi/dong-anh'),
(263, 2, 'Ba Đình', 'Quận', 26, 'ha-noi/ba-dinh'),
(264, 2, 'Cầu Giấy', 'Quận', 26, 'ha-noi/cau-giay'),
(265, 2, 'Gia Lâm', 'Huyện', 26, 'ha-noi/gia-lam'),
(266, 2, 'Hai Bà Trưng', 'Quận', 26, 'ha-noi/hai-ba-trung'),
(267, 2, 'Hoàn Kiếm', 'Quận', 26, 'ha-noi/hoan-kiem'),
(268, 2, 'Hoàng Mai', 'Quận', 26, 'ha-noi/hoang-mai'),
(269, 2, 'Long Biên', 'Quận', 26, 'ha-noi/long-bien'),
(270, 2, 'Sóc Sơn', 'Huyện', 26, 'ha-noi/soc-son'),
(271, 2, 'Từ Liêm', 'Huyện', 26, 'ha-noi/tu-liem'),
(272, 2, 'Tây Hồ', 'Quận', 26, 'ha-noi/tay-ho'),
(273, 2, 'Thanh Trì', 'Huyện', 26, 'ha-noi/thanh-tri'),
(274, 2, 'Thanh Xuân', 'Quận', 26, 'ha-noi/thanh-xuan'),
(275, 2, 'Bình Lục', 'Huyện', 27, 'ha-nam/binh-luc'),
(276, 2, 'Duy Tiên', 'Huyện', 27, 'ha-nam/duy-tien'),
(277, 2, 'Kim Bảng', 'Huyện', 27, 'ha-nam/kim-bang'),
(278, 2, 'Lý Nhân', 'Huyện', 27, 'ha-nam/ly-nhan'),
(279, 2, 'Phủ Lý', 'Thị xã', 27, 'ha-nam/phu-ly'),
(280, 2, 'Thanh Liêm', 'Huyện', 27, 'ha-nam/thanh-liem'),
(281, 2, 'Đan Phượng', 'Huyện', 28, 'ha-tay/dan-phuong'),
(282, 2, 'Ứng Hòa', 'Huyện', 28, 'ha-tay/ung-hoa'),
(283, 2, 'Ba Vì', 'Huyện', 28, 'ha-tay/ba-vi'),
(284, 2, 'Chương Mỹ', 'Huyện', 28, 'ha-tay/chuong-my'),
(285, 2, 'Hà Đông', 'Thị xã', 28, 'ha-tay/ha-dong'),
(286, 2, 'Hoài Đức', 'Huyện', 28, 'ha-tay/hoai-duc'),
(287, 2, 'Mỹ Đức', 'Huyện', 28, 'ha-tay/my-duc'),
(288, 2, 'Phú Xuyên', 'Huyện', 28, 'ha-tay/phu-xuyen'),
(289, 2, 'Phúc Thọ', 'Huyện', 28, 'ha-tay/phuc-tho'),
(290, 2, 'Quốc Oai', 'Huyện', 28, 'ha-tay/quoc-oai'),
(291, 2, 'Sơn Tây', 'Thị xã', 28, 'ha-tay/son-tay'),
(292, 2, 'Thường Tín', 'Huyện', 28, 'ha-tay/thuong-tin'),
(293, 2, 'Thạch Thất', 'Huyện', 28, 'ha-tay/thach-that'),
(294, 2, 'Thanh Oai', 'Huyện', 28, 'ha-tay/thanh-oai'),
(295, 2, 'Giao Thủy', 'Huyện', 29, 'nam-dinh/giao-thuy'),
(296, 2, 'Hải Hậu', 'Huyện', 29, 'nam-dinh/hai-hau'),
(297, 2, 'Mỹ Lộc', 'Huyện', 29, 'nam-dinh/my-loc'),
(298, 2, 'Nam Định', 'Thành phố', 29, 'nam-dinh/nam-dinh'),
(299, 2, 'Nam Trực', 'Huyện', 29, 'nam-dinh/nam-truc'),
(300, 2, 'Nghĩa Hưng', 'Huyện', 29, 'nam-dinh/nghia-hung'),
(301, 2, 'Trực Ninh', 'Huyện', 29, 'nam-dinh/truc-ninh'),
(302, 2, 'Vụ Bản', 'Huyện', 29, 'nam-dinh/vu-ban'),
(303, 2, 'Xuân Trường', 'Huyện', 29, 'nam-dinh/xuan-truong'),
(304, 2, 'Ý Yên', 'Huyện', 29, 'nam-dinh/y-yen'),
(305, 2, 'Gia Viễn', 'Huyện', 30, 'ninh-binh/gia-vien'),
(306, 2, 'Hoa Lư', 'Huyện', 30, 'ninh-binh/hoa-lu'),
(307, 2, 'Kim Sơn', 'Huyện', 30, 'ninh-binh/kim-son'),
(308, 2, 'Nho Quan', 'Huyện', 30, 'ninh-binh/nho-quan'),
(309, 2, 'Ninh Bình', 'Thị xã', 30, 'ninh-binh/ninh-binh'),
(310, 2, 'Tam Điệp', 'Thị xã', 30, 'ninh-binh/tam-diep'),
(311, 2, 'Yên Khánh', 'Huyện', 30, 'ninh-binh/yen-khanh'),
(312, 2, 'Yên Mô', 'Huyện', 30, 'ninh-binh/yen-mo'),
(313, 2, 'Đông Hưng', 'Huyện', 31, 'thai-binh/dong-hung'),
(314, 2, 'Hưng Hà', 'Huyện', 31, 'thai-binh/hung-ha'),
(315, 2, 'Kiến Xương', 'Huyện', 31, 'thai-binh/kien-xuong'),
(316, 2, 'Quỳnh Phụ', 'Huyện', 31, 'thai-binh/quynh-phu'),
(317, 2, 'Thái Bình', 'Thành phố', 31, 'thai-binh/thai-binh'),
(318, 2, 'Thái Thụy', 'Huyện', 31, 'thai-binh/thai-thuy'),
(319, 2, 'Tiền Hải', 'Huyện', 31, 'thai-binh/tien-hai'),
(320, 2, 'Vũ Thư', 'Huyện', 31, 'thai-binh/vu-thu'),
(321, 2, 'Bình Xuyên', 'Huyện', 32, 'vinh-phuc/binh-xuyen'),
(322, 2, 'Lập Thạch', 'Huyện', 32, 'vinh-phuc/lap-thach'),
(323, 2, 'Mê Linh', 'Huyện', 32, 'vinh-phuc/me-linh'),
(324, 2, 'Phúc Yên', 'Thị xã', 32, 'vinh-phuc/phuc-yen'),
(325, 2, 'Chợ Mới', 'Huyện', 34, 'bac-kan/cho-moi'),
(326, 2, 'Na Rì', 'Huyện', 34, 'bac-kan/na-ri'),
(327, 2, 'Ngân Sơn', 'Huyện', 34, 'bac-kan/ngan-son'),
(328, 2, 'Pác Nặm', 'Huyện', 34, 'bac-kan/pac-nam'),
(329, 2, 'Bảo Lạc', 'Huyện', 35, 'cao-bang/bao-lac'),
(330, 2, 'Bảo Lâm', 'Huyện', 35, 'cao-bang/bao-lam'),
(331, 2, 'Cao Bằng', 'Thị xã', 35, 'cao-bang/cao-bang'),
(332, 2, 'Hạ Lang', 'Huyện', 35, 'cao-bang/ha-lang'),
(333, 2, 'Hà Quảng', 'Huyện', 35, 'cao-bang/ha-quang'),
(334, 2, 'Hòa An', 'Huyện', 35, 'cao-bang/hoa-an'),
(335, 2, 'Nguyên Bình', 'Huyện', 35, 'cao-bang/nguyen-binh'),
(336, 2, 'Phục Hòa', 'Huyện', 35, 'cao-bang/phuc-hoa'),
(337, 2, 'Quảng Uyên', 'Huyện', 35, 'cao-bang/quang-uyen'),
(338, 2, 'Thạch An', 'Huyện', 35, 'cao-bang/thach-an'),
(339, 2, 'Thông Nông', 'Huyện', 35, 'cao-bang/thong-nong'),
(340, 2, 'Trà Lĩnh', 'Huyện', 35, 'cao-bang/tra-linh'),
(341, 2, 'Trùng Khánh', 'Huyện', 35, 'cao-bang/trung-khanh'),
(342, 2, 'Đồng Văn', 'Huyện', 36, 'ha-giang/dong-van'),
(343, 2, 'Bắc Mê', 'Huyện', 36, 'ha-giang/bac-me'),
(344, 2, 'Bắc Quang', 'Huyện', 36, 'ha-giang/bac-quang'),
(345, 2, 'Hà Giang', 'Thị xã', 36, 'ha-giang/ha-giang'),
(346, 2, 'Hoàng Su Phì', 'Huyện', 36, 'ha-giang/hoang-su-phi'),
(347, 2, 'Mèo Vạc', 'Huyện', 36, 'ha-giang/meo-vac'),
(348, 2, 'Quản Bạ', 'Huyện', 36, 'ha-giang/quan-ba'),
(349, 2, 'Quang Bình', 'Huyện', 36, 'ha-giang/quang-binh'),
(350, 2, 'Vị Xuyên', 'Huyện', 36, 'ha-giang/vi-xuyen'),
(351, 2, 'Xín Mần', 'Huyện', 36, 'ha-giang/xin-man'),
(352, 2, 'Yên Minh', 'Huyện', 36, 'ha-giang/yen-minh'),
(353, 2, 'Đình Lập', 'Huyện', 37, 'lang-son/dinh-lap'),
(354, 2, 'Bắc Sơn', 'Huyện', 37, 'lang-son/bac-son'),
(355, 2, 'Bình Gia', 'Huyện', 37, 'lang-son/binh-gia'),
(356, 2, 'Cao Lộc', 'Huyện', 37, 'lang-son/cao-loc'),
(357, 2, 'Chi Lăng', 'Huyện', 37, 'lang-son/chi-lang'),
(358, 2, 'Hữu Lũng', 'Huyện', 37, 'lang-son/huu-lung'),
(359, 2, 'Lộc Bình', 'Huyện', 37, 'lang-son/loc-binh'),
(360, 2, 'Lạng Sơn', 'Thành phố', 37, 'lang-son/lang-son'),
(361, 2, 'Tràng Định', 'Huyện', 37, 'lang-son/trang-dinh'),
(362, 2, 'Văn Quan', 'Huyện', 37, 'lang-son/van-quan'),
(363, 2, 'Văn Lãng', 'Huyện', 37, 'lang-son/van-lang'),
(364, 2, 'Bắc Hà', 'Huyện', 38, 'lao-cai/bac-ha'),
(365, 2, 'Bảo Thắng', 'Huyện', 38, 'lao-cai/bao-thang'),
(366, 2, 'Bảo Yên', 'Huyện', 38, 'lao-cai/bao-yen'),
(367, 2, 'Bát Xát', 'Huyện', 38, 'lao-cai/bat-xat'),
(368, 2, 'Lào Cai', 'Thành phố', 38, 'lao-cai/lao-cai'),
(369, 2, 'Mường Khương', 'Huyện', 38, 'lao-cai/muong-khuong'),
(370, 2, 'Sa Pa', 'Huyện', 38, 'lao-cai/sa-pa'),
(371, 2, 'Si Ma Cai', 'Huyện', 38, 'lao-cai/si-ma-cai'),
(372, 2, 'Văn Bàn', 'Huyện', 38, 'lao-cai/van-ban'),
(373, 2, 'Đoan Hùng', 'Huyện', 39, 'phu-tho/doan-hung'),
(374, 2, 'Cẩm Khê', 'Huyện', 39, 'phu-tho/cam-khe'),
(375, 2, 'Hạ Hòa', 'Huyện', 39, 'phu-tho/ha-hoa'),
(376, 2, 'Lâm Thao', 'Huyện', 39, 'phu-tho/lam-thao'),
(377, 2, 'Phù Ninh', 'Huyện', 39, 'phu-tho/phu-ninh'),
(378, 2, 'Phú Thọ', 'Thị xã', 39, 'phu-tho/phu-tho'),
(379, 2, 'Tam Nông', 'Huyện', 39, 'phu-tho/tam-nong'),
(380, 2, 'Thanh Ba', 'Huyện', 39, 'phu-tho/thanh-ba'),
(381, 2, 'Thanh Sơn', 'Huyện', 39, 'phu-tho/thanh-son'),
(382, 2, 'Thanh Thủy', 'Huyện', 39, 'phu-tho/thanh-thuy'),
(383, 2, 'Việt Trì', 'Thành phố', 39, 'phu-tho/viet-tri'),
(384, 2, 'Yên Lập', 'Huyện', 39, 'phu-tho/yen-lap'),
(385, 2, 'Đầm Hà', 'Huyện', 40, 'quang-ninh/dam-ha'),
(386, 2, 'Đông Triều', 'Huyện', 40, 'quang-ninh/dong-trieu'),
(387, 2, 'Ba Chẽ', 'Huyện', 40, 'quang-ninh/ba-che'),
(388, 2, 'Bình Liêu', 'Huyện', 40, 'quang-ninh/binh-lieu'),
(389, 2, 'Cẩm Phả', 'Thị xã', 40, 'quang-ninh/cam-pha'),
(390, 2, 'Cô Tô', 'Huyện', 40, 'quang-ninh/co-to'),
(391, 2, 'Hạ Long', 'Thành phố', 40, 'quang-ninh/ha-long'),
(392, 2, 'Hải Hà', 'Huyện', 40, 'quang-ninh/hai-ha'),
(393, 2, 'Hoành Bồ', 'Huyện', 40, 'quang-ninh/hoanh-bo'),
(394, 2, 'Móng Cái', 'Thị xã', 40, 'quang-ninh/mong-cai'),
(395, 2, 'Tiên Yên', 'Huyện', 40, 'quang-ninh/tien-yen'),
(396, 2, 'Uông Bí', 'Thị xã', 40, 'quang-ninh/uong-bi'),
(397, 2, 'Vân Đồn', 'Huyện', 40, 'quang-ninh/van-don'),
(398, 2, 'Yên Hưng', 'Huyện', 40, 'quang-ninh/yen-hung'),
(399, 2, 'Đại Từ', 'Huyện', 41, 'thai-nguyen/dai-tu'),
(400, 2, 'Đồng Hỷ', 'Huyện', 41, 'thai-nguyen/dong-hy'),
(401, 2, 'Định Hoá', 'Huyện', 41, 'thai-nguyen/dinh-hoa'),
(402, 2, 'Phổ Yên', 'Huyện', 41, 'thai-nguyen/pho-yen'),
(403, 2, 'Phú Bình', 'Huyện', 41, 'thai-nguyen/phu-binh'),
(404, 2, 'Phú Lương', 'Huyện', 41, 'thai-nguyen/phu-luong'),
(405, 2, 'Sông Công', 'Thị xã', 41, 'thai-nguyen/song-cong'),
(406, 2, 'Thái Nguyên', 'Thành phố', 41, 'thai-nguyen/thai-nguyen'),
(407, 2, 'Võ Nhai', 'Huyện', 41, 'thai-nguyen/vo-nhai'),
(408, 2, 'Chiêm Hóa', 'Huyện', 42, 'tuyen-quang/chiem-hoa'),
(409, 2, 'Hàm Yên', 'Huyện', 42, 'tuyen-quang/ham-yen'),
(410, 2, 'Na Hang', 'Huyện', 42, 'tuyen-quang/na-hang'),
(411, 2, 'Sơn Dương', 'Huyện', 42, 'tuyen-quang/son-duong'),
(412, 2, 'Tuyên Quang', 'Thị xã', 42, 'tuyen-quang/tuyen-quang'),
(413, 2, 'Yên Sơn', 'Huyện', 42, 'tuyen-quang/yen-son'),
(414, 2, 'Lục Yên', 'Huyện', 43, 'yen-bai/luc-yen'),
(415, 2, 'Mù Cang Chải', 'Huyện', 43, 'yen-bai/mu-cang-chai'),
(416, 2, 'Nghĩa Lộ', 'Thị xã', 43, 'yen-bai/nghia-lo'),
(417, 2, 'Trạm Tấu', 'Huyện', 43, 'yen-bai/tram-tau'),
(418, 2, 'Trấn Yên', 'Huyện', 43, 'yen-bai/tran-yen'),
(419, 2, 'Văn Chấn', 'Huyện', 43, 'yen-bai/van-chan'),
(420, 2, 'Văn Yên', 'Huyện', 43, 'yen-bai/van-yen'),
(421, 2, 'Yên Bái', 'Thành phố', 43, 'yen-bai/yen-bai'),
(422, 2, 'Yên Bình', 'Huyện', 43, 'yen-bai/yen-binh'),
(423, 2, 'Định Quán', 'Huyện', 44, 'dong-nai/dinh-quan'),
(424, 2, 'Biên Hòa', 'Thành phố', 44, 'dong-nai/bien-hoa'),
(425, 2, 'Cẩm Mỹ', 'Huyện', 44, 'dong-nai/cam-my'),
(426, 2, 'Long Khánh', 'Huyện', 44, 'dong-nai/long-khanh'),
(427, 2, 'Long Thành', 'Huyện', 44, 'dong-nai/long-thanh'),
(428, 2, 'Nhơn Trạch', 'Huyện', 44, 'dong-nai/nhon-trach'),
(429, 2, 'Tân Phú', 'Huyện', 44, 'dong-nai/tan-phu'),
(430, 2, 'Thống Nhất', 'Huyện', 44, 'dong-nai/thong-nhat'),
(431, 2, 'Trảng Bom', 'Huyện', 44, 'dong-nai/trang-bom'),
(432, 2, 'Vĩnh Cửu', 'Huyện', 44, 'dong-nai/vinh-cuu'),
(433, 2, 'Xuân Lộc', 'Huyện', 44, 'dong-nai/xuan-loc'),
(434, 2, 'Đất Đỏ', 'Huyện', 45, 'ba-ria-vung-tau/dat-do'),
(435, 2, 'Bà Rịa', 'Thị xã', 45, 'ba-ria-vung-tau/ba-ria'),
(436, 2, 'Châu Đức', 'Huyện', 45, 'ba-ria-vung-tau/chau-duc'),
(437, 2, 'Côn Đảo', 'Island', 45, 'ba-ria-vung-tau/con-dao'),
(438, 2, 'Long Điền', 'Huyện', 45, 'ba-ria-vung-tau/long-dien'),
(439, 2, 'Tân Thành', 'Huyện', 45, 'ba-ria-vung-tau/tan-thanh'),
(440, 2, 'Vũng Tàu', 'Thành phố', 45, 'ba-ria-vung-tau/vung-tau'),
(441, 2, 'Xuyên Mộc', 'Huyện', 45, 'ba-ria-vung-tau/xuyen-moc'),
(442, 2, 'Bến Cát', 'Huyện', 46, 'binh-duong/ben-cat'),
(443, 2, 'Dầu Tiếng', 'Huyện', 46, 'binh-duong/dau-tieng'),
(444, 2, 'Dĩ An', 'Huyện', 46, 'binh-duong/di-an'),
(445, 2, 'Phú Giáo', 'Huyện', 46, 'binh-duong/phu-giao'),
(446, 2, 'Tân Uyên', 'Huyện', 46, 'binh-duong/tan-uyen'),
(447, 2, 'Thủ Dầu Một', 'Thị xã', 46, 'binh-duong/thu-dau-mot'),
(448, 2, 'Thuận An', 'Huyện', 46, 'binh-duong/thuan-an'),
(449, 2, 'Đồng Phú', 'Huyện', 47, 'binh-phuoc/dong-phu'),
(450, 2, 'Bình Long', 'Huyện', 47, 'binh-phuoc/binh-long'),
(451, 2, 'Bù Đăng', 'Huyện', 47, 'binh-phuoc/bu-dang'),
(452, 2, 'Bù Đốp', 'Huyện', 47, 'binh-phuoc/bu-dop'),
(453, 2, 'Chơn Thành', 'Huyện', 47, 'binh-phuoc/chon-thanh'),
(454, 2, 'Đồng Xoài', 'Thị xã', 47, 'binh-phuoc/dong-xoai'),
(455, 2, 'Lộc Ninh', 'Huyện', 47, 'binh-phuoc/loc-ninh'),
(456, 2, 'Phước Long', 'Huyện', 47, 'binh-phuoc/phuoc-long'),
(457, 2, 'Đức Linh', 'Huyện', 48, 'binh-thuan/duc-linh'),
(458, 2, 'Bắc Bình', 'Huyện', 48, 'binh-thuan/bac-binh'),
(459, 2, 'Hàm Tân', 'Huyện', 48, 'binh-thuan/ham-tan'),
(460, 2, 'Hàm Thuận Bắc', 'Huyện', 48, 'binh-thuan/ham-thuan-bac'),
(461, 2, 'Hàm Thuận Nam', 'Huyện', 48, 'binh-thuan/ham-thuan-nam'),
(462, 2, 'La Gi', 'Huyện', 48, 'binh-thuan/la-gi'),
(463, 2, 'Phan Thiết', 'Thành phố', 48, 'binh-thuan/phan-thiet'),
(464, 2, 'Phú Quý', 'Island', 48, 'binh-thuan/phu-qui'),
(465, 2, 'Tánh Linh', 'Huyện', 48, 'binh-thuan/tanh-linh'),
(466, 2, 'Tuy Phong', 'Huyện', 48, 'binh-thuan/tuy-phong'),
(467, 2, 'Bình Chánh', 'Huyện', 49, 'ho-chi-minh/binh-chanh'),
(468, 2, 'Bình Tân', 'Quận', 49, 'ho-chi-minh/binh-tan'),
(469, 2, 'Bình Thạnh', 'Quận', 49, 'ho-chi-minh/binh-thanh'),
(470, 2, 'Củ Chi', 'Huyện', 49, 'ho-chi-minh/cu-chi'),
(471, 2, 'Cần Giờ', 'Huyện', 49, 'ho-chi-minh/can-gio'),
(472, 2, 'Gò Vấp', 'Quận', 49, 'ho-chi-minh/go-vap'),
(473, 2, 'Hóc Môn', 'Huyện', 49, 'ho-chi-minh/hoc-mon'),
(474, 2, 'Nhà Bè', 'Huyện', 49, 'ho-chi-minh/nha-be'),
(475, 2, 'Phú Nhuận', 'Quận', 49, 'ho-chi-minh/phu-nhuan'),
(476, 2, '1', 'Quận', 49, 'ho-chi-minh/1'),
(477, 2, '10', 'Quận', 49, 'ho-chi-minh/10'),
(478, 2, '11', 'Quận', 49, 'ho-chi-minh/11'),
(479, 2, '12', 'Quận', 49, 'ho-chi-minh/12'),
(480, 2, '2', 'Quận', 49, 'ho-chi-minh/2'),
(481, 2, '3', 'Quận', 49, 'ho-chi-minh/3'),
(482, 2, '4', 'Quận', 49, 'ho-chi-minh/4'),
(483, 2, '5', 'Quận', 49, 'ho-chi-minh/5'),
(484, 2, '6', 'Quận', 49, 'ho-chi-minh/6'),
(485, 2, '7', 'Quận', 49, 'ho-chi-minh/7'),
(486, 2, '8', 'Quận', 49, 'ho-chi-minh/8'),
(487, 2, '9', 'Quận', 49, 'ho-chi-minh/9'),
(488, 2, 'Tân Bình', 'Quận', 49, 'ho-chi-minh/tan-binh'),
(489, 2, 'Tân Phú', 'Quận', 49, 'ho-chi-minh/tan-phu'),
(490, 2, 'Thủ Đức', 'Quận', 49, 'ho-chi-minh/thu-duc'),
(491, 2, 'Bác Ái', 'Huyện', 50, 'ninh-thuan/bac-ai'),
(492, 2, 'Ninh Hải', 'Huyện', 50, 'ninh-thuan/ninh-hai'),
(493, 2, 'Ninh Phước', 'Huyện', 50, 'ninh-thuan/ninh-phuoc'),
(494, 2, 'Ninh Sơn', 'Huyện', 50, 'ninh-thuan/ninh-son'),
(495, 2, 'Phan Rang-Tháp Chàm', 'Thị xã', 50, 'ninh-thuan/phan-rang-thap-cham'),
(496, 2, 'Bến Cầu', 'Huyện', 51, 'tay-ninh/ben-cau'),
(497, 2, 'Châu Thành', 'Huyện', 51, 'tay-ninh/chau-thanh'),
(498, 2, 'Dương Minh Châu', 'Huyện', 51, 'tay-ninh/duong-minh-chau'),
(499, 2, 'Gò Dầu', 'Huyện', 51, 'tay-ninh/go-dau'),
(500, 2, 'Hòa Thành', 'Huyện', 51, 'tay-ninh/hoa-thanh'),
(501, 2, 'Tân Biên', 'Huyện', 51, 'tay-ninh/tan-bien'),
(502, 2, 'Tân Châu', 'Huyện', 51, 'tay-ninh/tan-chau'),
(503, 2, 'Tây Ninh', 'Thị xã', 51, 'tay-ninh/tay-ninh'),
(504, 2, 'Trảng Bàng', 'Huyện', 51, 'tay-ninh/trang-bang'),
(505, 2, 'Đức Thọ', 'Huyện', 52, 'ha-tinh/duc-tho'),
(506, 2, 'Cẩm Xuyên', 'Huyện', 52, 'ha-tinh/cam-xuyen'),
(507, 2, 'Can Lộc', 'Huyện', 52, 'ha-tinh/can-loc'),
(508, 2, 'Hương Khê', 'Huyện', 52, 'ha-tinh/huong-khe'),
(509, 2, 'Hương Sơn', 'Huyện', 52, 'ha-tinh/huong-son'),
(510, 2, 'Hồng Lĩnh', 'Thị xã', 52, 'ha-tinh/hong-linh'),
(511, 2, 'Hà Tĩnh', 'Thành phố', 52, 'ha-tinh/ha-tinh'),
(512, 2, 'Kỳ Anh', 'Huyện', 52, 'ha-tinh/ky-anh'),
(513, 2, 'Nghi Xuân', 'Huyện', 52, 'ha-tinh/nghi-xuan'),
(514, 2, 'Thạch Hà', 'Huyện', 52, 'ha-tinh/thach-ha'),
(515, 2, 'Vũ Quang', 'Huyện', 52, 'ha-tinh/vu-quang'),
(516, 2, 'Đô Lương', 'Huyện', 53, 'nghe-an/do-luong'),
(517, 2, 'Anh Sơn', 'Huyện', 53, 'nghe-an/anh-son'),
(518, 2, 'Cửa Lò', 'Thị xã', 53, 'nghe-an/cua-lo'),
(519, 2, 'Con Cuông', 'Huyện', 53, 'nghe-an/con-cuong'),
(520, 2, 'Diễn Châu', 'Huyện', 53, 'nghe-an/dien-chau'),
(521, 2, 'Hưng Nguyên', 'Huyện', 53, 'nghe-an/hung-nguyen'),
(522, 2, 'Kỳ Sơn', 'Huyện', 53, 'nghe-an/ky-son'),
(523, 2, 'Nam Đàn', 'Huyện', 53, 'nghe-an/nam-dan'),
(524, 2, 'Nghĩa Đàn', 'Huyện', 53, 'nghe-an/nghia-dan'),
(525, 2, 'Nghi Lộc', 'Huyện', 53, 'nghe-an/nghi-loc'),
(526, 2, 'Quỳ Châu', 'Huyện', 53, 'nghe-an/quy-chau'),
(527, 2, 'Quỳ Hợp', 'Huyện', 53, 'nghe-an/quy-hop'),
(528, 2, 'Quế Phong', 'Huyện', 53, 'nghe-an/que-phong'),
(529, 2, 'Quỳnh Lưu', 'Huyện', 53, 'nghe-an/quynh-luu'),
(530, 2, 'Tương Dương', 'Huyện', 53, 'nghe-an/tuong-duong'),
(531, 2, 'Tân Kỳ', 'Huyện', 53, 'nghe-an/tan-ky'),
(532, 2, 'Thái Hòa', 'Thị xã', 53, 'nghe-an/thai-hoa'),
(533, 2, 'Thanh Chương', 'Huyện', 53, 'nghe-an/thanh-chuong'),
(534, 2, 'Vinh', 'Thành phố', 53, 'nghe-an/vinh'),
(535, 2, 'Yên Thành', 'Huyện', 53, 'nghe-an/yen-thanh'),
(536, 2, 'Đồng Hới', 'Thành phố', 54, 'quang-binh/dong-hoi'),
(537, 2, 'Bố Trạch', 'Huyện', 54, 'quang-binh/bo-trach'),
(538, 2, 'Lệ Thủy', 'Huyện', 54, 'quang-binh/le-thuy'),
(539, 2, 'Minh Hóa', 'Huyện', 54, 'quang-binh/minh-hoa'),
(540, 2, 'Quảng Ninh', 'Huyện', 54, 'quang-binh/quang-ninh'),
(541, 2, 'Quảng Trạch', 'Huyện', 54, 'quang-binh/quang-trach'),
(542, 2, 'Tuyên Hóa', 'Huyện', 54, 'quang-binh/tuyen-hoa'),
(543, 2, 'Đa Krông', 'Huyện', 55, 'quang-tri/da-krong'),
(544, 2, 'Đông Hà', 'Thị xã', 55, 'quang-tri/dong-ha'),
(545, 2, 'Cồn Cỏ', 'Island', 55, 'quang-tri/con-co'),
(546, 2, 'Cam Lộ', 'Huyện', 55, 'quang-tri/cam-lo'),
(547, 2, 'Gio Linh', 'Huyện', 55, 'quang-tri/gio-linh'),
(548, 2, 'Hướng Hóa', 'Huyện', 55, 'quang-tri/huong-hoa'),
(549, 2, 'Hải Lăng', 'Huyện', 55, 'quang-tri/hai-lang'),
(550, 2, 'Quảng Trị', 'Thị xã', 55, 'quang-tri/quang-tri'),
(551, 2, 'Triệu Phong', 'Huyện', 55, 'quang-tri/trieu-phong'),
(552, 2, 'Vĩnh Linh', 'Huyện', 55, 'quang-tri/vinh-linh'),
(553, 2, 'A Lưới', 'Huyện', 56, 'thua-thien-hue/a-luoi'),
(554, 2, 'Hương Thủy', 'Huyện', 56, 'thua-thien-hue/huong-thuy'),
(555, 2, 'Hương Trà', 'Huyện', 56, 'thua-thien-hue/huong-tra'),
(556, 2, 'Huế', 'Thành phố', 56, 'thua-thien-hue/hue'),
(557, 2, 'Nam Đông', 'Huyện', 56, 'thua-thien-hue/nam-dong'),
(558, 2, 'Phong Điền', 'Huyện', 56, 'thua-thien-hue/phong-dien'),
(559, 2, 'Phú Lộc', 'Huyện', 56, 'thua-thien-hue/phu-loc'),
(560, 2, 'Phú Vang', 'Huyện', 56, 'thua-thien-hue/phu-vang'),
(561, 2, 'Quảng Điền', 'Huyện', 56, 'thua-thien-hue/quang-dien'),
(562, 2, 'Đông Sơn', 'Huyện', 57, 'thanh-hoa/dong-son'),
(563, 2, 'Bá Thước', 'Huyện', 57, 'thanh-hoa/ba-thuoc'),
(564, 2, 'Bỉm Sơn', 'Thị xã', 57, 'thanh-hoa/bim-son'),
(565, 2, 'Cẩm Thủy', 'Huyện', 57, 'thanh-hoa/cam-thuy'),
(566, 2, 'Hậu Lộc', 'Huyện', 57, 'thanh-hoa/hau-loc'),
(567, 2, 'Hà Trung', 'Huyện', 57, 'thanh-hoa/ha-trung'),
(568, 2, 'Hoằng Hóa', 'Huyện', 57, 'thanh-hoa/hoang-hoa'),
(569, 2, 'Lang Chánh', 'Huyện', 57, 'thanh-hoa/lang-chanh'),
(570, 2, 'Mường Lát', 'Huyện', 57, 'thanh-hoa/muong-lat'),
(571, 2, 'Ngọc Lạc', 'Huyện', 57, 'thanh-hoa/ngoc-lac'),
(572, 2, 'Nga Sơn', 'Huyện', 57, 'thanh-hoa/nga-son'),
(573, 2, 'Như Thanh', 'Huyện', 57, 'thanh-hoa/nhu-thanh'),
(574, 2, 'Như Xuân', 'Huyện', 57, 'thanh-hoa/nhu-xuan'),
(575, 2, 'Nông Cống', 'Huyện', 57, 'thanh-hoa/nong-cong'),
(576, 2, 'Quảng Xương', 'Huyện', 57, 'thanh-hoa/quang-xuong'),
(577, 2, 'Quan Hóa', 'Huyện', 57, 'thanh-hoa/quan-hoa'),
(578, 2, 'Quan Sơn', 'Huyện', 57, 'thanh-hoa/quan-son'),
(579, 2, 'Sầm Sơn', 'Thị xã', 57, 'thanh-hoa/sam-son'),
(580, 2, 'Tĩnh Gia', 'Huyện', 57, 'thanh-hoa/tinh-gia'),
(581, 2, 'Thọ Xuân', 'Huyện', 57, 'thanh-hoa/tho-xuan'),
(582, 2, 'Thường Xuân', 'Huyện', 57, 'thanh-hoa/thuong-xuan'),
(583, 2, 'Thạch Thành', 'Huyện', 57, 'thanh-hoa/thach-thanh'),
(584, 2, 'Thanh Hóa', 'Thành phố', 57, 'thanh-hoa/thanh-hoa'),
(585, 2, 'Thiệu Hóa', 'Huyện', 57, 'thanh-hoa/thieu-hoa'),
(586, 2, 'Triệu Sơn', 'Huyện', 57, 'thanh-hoa/trieu-son'),
(587, 2, 'Vĩnh Lộc', 'Huyện', 57, 'thanh-hoa/vinh-loc'),
(588, 2, 'Yên Định', 'Huyện', 57, 'thanh-hoa/yen-dinh'),
(589, 2, 'Cẩm Lệ', 'Quận', 58, 'da-nang/cam-le'),
(590, 2, 'Hải Châu', 'Quận', 58, 'da-nang/hai-chau'),
(591, 2, 'Hòa Vang', 'Huyện', 58, 'da-nang/hoa-vang'),
(592, 2, 'Liên Chiểu', 'Quận', 58, 'da-nang/lien-chieu'),
(593, 2, 'Ngũ Hành Sơn', 'Quận', 58, 'da-nang/ngu-hanh-son'),
(594, 2, 'Sơn Trà', 'Quận', 58, 'da-nang/son-tra'),
(595, 2, 'Thanh Khê', 'Quận', 58, 'da-nang/thanh-khe'),
(596, 2, 'An Lão', 'Huyện', 59, 'binh-dinh/an-lao'),
(597, 2, 'An Nhơn', 'Huyện', 59, 'binh-dinh/an-nhon'),
(598, 2, 'Hoài Ân', 'Huyện', 59, 'binh-dinh/hoai-an'),
(599, 2, 'Hoài Nhơn', 'Huyện', 59, 'binh-dinh/hoai-nhon'),
(600, 2, 'Phù Cát', 'Huyện', 59, 'binh-dinh/phu-cat'),
(601, 2, 'Phù Mỹ', 'Huyện', 59, 'binh-dinh/phu-my'),
(602, 2, 'Qui Nhơn', 'Thành phố', 59, 'binh-dinh/qui-nhon'),
(603, 2, 'Tây Sơn', 'Huyện', 59, 'binh-dinh/tay-son'),
(604, 2, 'Tuy Phước', 'Huyện', 59, 'binh-dinh/tuy-phuoc'),
(605, 2, 'Vĩnh Thạnh', 'Huyện', 59, 'binh-dinh/vinh-thanh'),
(606, 2, 'Vân Canh', 'Huyện', 59, 'binh-dinh/van-canh'),
(607, 2, 'Cam Lâm', 'Huyện', 60, 'khanh-hoa/cam-lam'),
(608, 2, 'Cam Ranh', 'Thị xã', 60, 'khanh-hoa/cam-ranh'),
(609, 2, 'Diên Khánh', 'Huyện', 60, 'khanh-hoa/dien-khanh'),
(610, 2, 'Khánh Sơn', 'Huyện', 60, 'khanh-hoa/khanh-son'),
(611, 2, 'Khánh Vĩnh', 'Huyện', 60, 'khanh-hoa/khanh-vinh'),
(612, 2, 'Nha Trang', 'Thành phố', 60, 'khanh-hoa/nha-trang'),
(613, 2, 'Ninh Hòa', 'Huyện', 60, 'khanh-hoa/ninh-hoa'),
(614, 2, 'Vạn Ninh', 'Huyện', 60, 'khanh-hoa/van-ninh'),
(615, 2, 'Đồng Xuân', 'Huyện', 61, 'phu-yen/dong-xuan'),
(616, 2, 'Đông Hòa', 'Huyện', 61, 'phu-yen/dong-hoa'),
(617, 2, 'Phú Hòa', 'Huyện', 61, 'phu-yen/phu-hoa'),
(618, 2, 'Sơn Hòa', 'Huyện', 61, 'phu-yen/son-hoa'),
(619, 2, 'Sông Cầu', 'Huyện', 61, 'phu-yen/song-cau'),
(620, 2, 'Sông Hinh', 'Huyện', 61, 'phu-yen/song-hinh'),
(621, 2, 'Tuy An', 'Huyện', 61, 'phu-yen/tuy-an'),
(622, 2, 'Tuy Hòa', 'Thị xã', 61, 'phu-yen/tuy-hoa'),
(623, 2, 'Đại Lộc', 'Huyện', 62, 'quang-nam/dai-loc'),
(624, 2, 'Điện Bàn', 'Huyện', 62, 'quang-nam/dien-ban'),
(625, 2, 'Đông Giang', 'Huyện', 62, 'quang-nam/dong-giang'),
(626, 2, 'Bắc Trà My', 'Huyện', 62, 'quang-nam/bac-tra-my'),
(627, 2, 'Duy Xuyên', 'Huyện', 62, 'quang-nam/duy-xuyen'),
(628, 2, 'Hội An', 'Thị xã', 62, 'quang-nam/hoi-an'),
(629, 2, 'Hiệp Đức', 'Huyện', 62, 'quang-nam/hiep-duc'),
(630, 2, 'Nam Giang', 'Huyện', 62, 'quang-nam/nam-giang'),
(631, 2, 'Nam Trà My', 'Huyện', 62, 'quang-nam/nam-tra-my'),
(632, 2, 'Núi Thành', 'Huyện', 62, 'quang-nam/nui-thanh'),
(633, 2, 'Phước Sơn', 'Huyện', 62, 'quang-nam/phuoc-son'),
(634, 2, 'Phú Ninh', 'Huyện', 62, 'quang-nam/phu-ninh'),
(635, 2, 'Quế Sơn', 'Huyện', 62, 'quang-nam/que-son'),
(636, 2, 'Tam Kỳ', 'Thị xã', 62, 'quang-nam/tam-ky'),
(637, 2, 'Tây Giang', 'Huyện', 62, 'quang-nam/tay-giang'),
(638, 2, 'Thăng Bình', 'Huyện', 62, 'quang-nam/thang-binh'),
(639, 2, 'Tiên Phước', 'Huyện', 62, 'quang-nam/tien-phuoc'),
(640, 2, 'Đức Phổ', 'Huyện', 63, 'quang-ngai/duc-pho'),
(641, 2, 'Ba Tơ', 'Huyện', 63, 'quang-ngai/ba-to'),
(642, 2, 'Bình Sơn', 'Huyện', 63, 'quang-ngai/binh-son'),
(643, 2, 'Lý Sơn', 'Huyện', 63, 'quang-ngai/ly-son'),
(644, 2, 'Mộ Đức', 'Huyện', 63, 'quang-ngai/mo-duc'),
(645, 2, 'Minh Long', 'Huyện', 63, 'quang-ngai/minh-long'),
(646, 2, 'Nghĩa Hành', 'Huyện', 63, 'quang-ngai/nghia-hanh'),
(647, 2, 'Quảng Ngãi', 'Thị xã', 63, 'quang-ngai/quang-ngai'),
(648, 2, 'Sơn Hà', 'Huyện', 63, 'quang-ngai/son-ha'),
(649, 2, 'Sơn Tịnh', 'Huyện', 63, 'quang-ngai/son-tinh'),
(650, 2, 'Sơn Tây', 'Huyện', 63, 'quang-ngai/son-tay'),
(651, 2, 'Tư Nghĩa', 'Huyện', 63, 'quang-ngai/tu-nghia'),
(652, 2, 'Tây Trà', 'Huyện', 63, 'quang-ngai/tay-tra'),
(653, 2, 'Trà Bồng', 'Huyện', 63, 'quang-ngai/tra-bong'),
(654, 2, 'Điện Biên Đông', 'Huyện', 64, 'dien-bien/dien-bien-dong'),
(655, 2, 'Điện Biên', 'Huyện', 64, 'dien-bien/dien-bien'),
(656, 2, 'Điên Biên Phủ', 'Thành phố', 64, 'dien-bien/dien-bien-phu'),
(657, 2, 'Mường Chà', 'Huyện', 64, 'dien-bien/muong-cha'),
(658, 2, 'Mường Lay', 'Thị xã', 64, 'dien-bien/muong-lay'),
(659, 2, 'Mường Nhé', 'Huyện', 64, 'dien-bien/muong-nhe'),
(660, 2, 'Tủa Chùa', 'Huyện', 64, 'dien-bien/tua-chua'),
(661, 2, 'Tuần Giáo', 'Huyện', 64, 'dien-bien/tuan-giao'),
(662, 2, 'Đà Bắc', 'Huyện', 65, 'hoa-binh/da-bac'),
(663, 2, 'Cao Phong', 'Huyện', 65, 'hoa-binh/cao-phong'),
(664, 2, 'Hòa Bình', 'Thị xã', 65, 'hoa-binh/hoa-binh'),
(665, 2, 'Kỳ Sơn', 'Huyện', 65, 'hoa-binh/ky-son'),
(666, 2, 'Kim Bôi', 'Huyện', 65, 'hoa-binh/kim-boi'),
(667, 2, 'Lương Sơn', 'Huyện', 65, 'hoa-binh/luong-son'),
(668, 2, 'Lạc Sơn', 'Huyện', 65, 'hoa-binh/lac-son'),
(669, 2, 'Lạc Thủy', 'Huyện', 65, 'hoa-binh/lac-thuy'),
(670, 2, 'Mai Châu', 'Huyện', 65, 'hoa-binh/mai-chau'),
(671, 2, 'Tân Lạc', 'Huyện', 65, 'hoa-binh/tan-lac'),
(672, 2, 'Yên Thủy', 'Huyện', 65, 'hoa-binh/yen-thuy'),
(673, 2, 'Lai Châu', 'Thị xã', 66, 'lai-chau/lai-chau'),
(674, 2, 'Mường Tè', 'Huyện', 66, 'lai-chau/muong-te'),
(675, 2, 'Phong Thổ', 'Huyện', 66, 'lai-chau/phong-tho'),
(676, 2, 'Sìn Hồ', 'Huyện', 66, 'lai-chau/sin-ho'),
(677, 2, 'Tam Đường', 'Huyện', 66, 'lai-chau/tam-duong'),
(678, 2, 'Than Uyên', 'Huyện', 66, 'lai-chau/than-uyen'),
(679, 2, 'Bắc Yên', 'Huyện', 67, 'son-la/bac-yen'),
(680, 2, 'Mường La', 'Huyện', 67, 'son-la/muong-la'),
(681, 2, 'Mộc Châu', 'Huyện', 67, 'son-la/moc-chau'),
(682, 2, 'Mai Sơn', 'Huyện', 67, 'son-la/mai-son'),
(683, 2, 'Phù Yên', 'Huyện', 67, 'son-la/phu-yen'),
(684, 2, 'Quỳnh Nhai', 'Huyện', 67, 'son-la/quynh-nhai'),
(685, 2, 'Sơn La', 'Thị xã', 67, 'son-la/son-la'),
(686, 2, 'Sốp Cộp', 'Huyện', 67, 'son-la/sop-cop'),
(687, 2, 'Sông Mã', 'Huyện', 67, 'son-la/song-ma'),
(688, 2, 'Thuận Châu', 'Huyện', 67, 'son-la/thuan-chau'),
(689, 2, 'Yên Châu', 'Huyện', 67, 'son-la/yen-chau'),
(690, 2, 'Buôn Đôn', 'Huyện', 68, 'dak-lak/buon-don'),
(691, 2, 'Buôn Ma Thuột', 'Thành phố', 68, 'dak-lak/buon-ma-thuot'),
(692, 2, 'Cư M''gar', 'Huyện', 68, 'dak-lak/cu-m-gar'),
(693, 2, 'Ea H''leo', 'Huyện', 68, 'dak-lak/ea-h-leo'),
(694, 2, 'Ea Kar', 'Huyện', 68, 'dak-lak/ea-kar'),
(695, 2, 'Ea Súp', 'Huyện', 68, 'dak-lak/ea-sup'),
(696, 2, 'Krông Ana', 'Huyện', 68, 'dak-lak/krong-a-na'),
(697, 2, 'Krông Bông', 'Huyện', 68, 'dak-lak/krong-bong'),
(698, 2, 'Krông Buk', 'Huyện', 68, 'dak-lak/krong-buk'),
(699, 2, 'Krông Năng', 'Huyện', 68, 'dak-lak/krong-nang'),
(700, 2, 'Krông Pắk', 'Huyện', 68, 'dak-lak/krong-pac'),
(701, 2, 'Lắk', 'Huyện', 68, 'dak-lak/lak'),
(702, 2, 'M''Đrak', 'Huyện', 68, 'dak-lak/m-drak'),
(703, 2, 'Đak Glong', 'Huyện', 69, 'dak-nong/dak-glong'),
(704, 2, 'Đak Mil', 'Huyện', 69, 'dak-nong/dak-mil'),
(705, 2, 'Đak R''Lấp', 'Huyện', 69, 'dak-nong/dak-r-lap'),
(706, 2, 'Cư Jút', 'Huyện', 69, 'dak-nong/cu-jut'),
(707, 2, 'Đak Song', 'Huyện', 69, 'dak-nong/dak-song'),
(708, 2, 'Krông Nô', 'Huyện', 69, 'dak-nong/krong-no'),
(709, 2, 'Tuy Đức', 'Huyện', 69, 'dak-nong/tuy-duc'),
(710, 2, 'Đức Cơ', 'Huyện', 70, 'gia-lai/duc-co'),
(711, 2, 'Đắk Đoa', 'Huyện', 70, 'gia-lai/dak-doa'),
(712, 2, 'Đắk Pơ', 'Huyện', 70, 'gia-lai/dak-po'),
(713, 2, 'An Khê', 'Thị xã', 70, 'gia-lai/an-khe'),
(714, 2, 'Ayun Pa', 'Huyện', 70, 'gia-lai/ayun-pa'),
(715, 2, 'Chư Păh', 'Huyện', 70, 'gia-lai/chu-pah'),
(716, 2, 'Chư Prông', 'Huyện', 70, 'gia-lai/chu-prong'),
(717, 2, 'Chư Sê', 'Huyện', 70, 'gia-lai/chu-se'),
(718, 2, 'Ia Grai', 'Huyện', 70, 'gia-lai/ia-grai'),
(719, 2, 'Ia Pa', 'Huyện', 70, 'gia-lai/ia-pa'),
(720, 2, 'K''Bang', 'Huyện', 70, 'gia-lai/kbang'),
(721, 2, 'Kông Chro', 'Huyện', 70, 'gia-lai/kong-chro'),
(722, 2, 'Krông Pa', 'Huyện', 70, 'gia-lai/krong-pa'),
(723, 2, 'Mang Yang', 'Huyện', 70, 'gia-lai/mang-yang'),
(724, 2, 'Phú Thiện', 'Huyện', 70, 'gia-lai/phu-thien'),
(725, 2, 'Pleiku', 'Thành phố', 70, 'gia-lai/pleiku'),
(726, 2, 'Đak Glei', 'Huyện', 71, 'kon-tum/dak-glei'),
(727, 2, 'Đak Hà', 'Huyện', 71, 'kon-tum/dak-ha'),
(728, 2, 'Đak Tô', 'Huyện', 71, 'kon-tum/dak-to'),
(729, 2, 'Kon Plông', 'Huyện', 71, 'kon-tum/kon-plong'),
(730, 2, 'Kon Rẫy', 'Huyện', 71, 'kon-tum/kon-ray'),
(731, 2, 'Kon Tum', 'Thị xã', 71, 'kon-tum/kon-tum'),
(732, 2, 'Ngọc Hồi', 'Huyện', 71, 'kon-tum/ngoc-hoi'),
(733, 2, 'Sa Thầy', 'Huyện', 71, 'kon-tum/sa-thay'),
(734, 2, 'Tu Mơ Rông', 'Huyện', 71, 'kon-tum/tu-mo-rong'),
(735, 2, 'Đạ Huoai', 'Huyện', 72, 'lam-dong/da-huoai'),
(736, 2, 'Đạ Tẻh', 'Huyện', 72, 'lam-dong/da-teh'),
(737, 2, 'Đức Trọng', 'Huyện', 72, 'lam-dong/duc-trong'),
(738, 2, 'Đơn Dương', 'Huyện', 72, 'lam-dong/don-duong'),
(739, 2, 'Đà Lạt', 'Thành phố', 72, 'lam-dong/da-lat'),
(740, 2, 'Đam Rông', 'Huyện', 72, 'lam-dong/dam-rong'),
(741, 2, 'Bảo Lộc', 'Thị xã', 72, 'lam-dong/bao-loc'),
(742, 2, 'Bảo Lâm', 'Huyện', 72, 'lam-dong/bao-lam'),
(743, 2, 'Cát Tiên', 'Huyện', 72, 'lam-dong/cat-tien'),
(744, 2, 'Di Linh', 'Huyện', 72, 'lam-dong/di-linh'),
(745, 2, 'Lạc Dương', 'Huyện', 72, 'lam-dong/lac-duong'),
(746, 2, 'Lâm Hà', 'Huyện', 72, 'lam-dong/lam-ha');";

 */

$sql35 = "INSERT INTO `user_permission` (`id`, `user`, `branch`, `permission`) VALUES (1, 1, 1, 1);";

$sql36 = "CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `roles` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$sql37 = "INSERT INTO `user_role` (`id`, `name`, `roles`) VALUES
(1, 'Quản trị chi nhánh', '1.1.1,1.1.2,1.1.3,1.1.4,1.1.5,1.2.1,1.2.2,1.2.3,1.2.4,2.1.1,2.1.2,2.1.3,2.1.4,2.2.1,2.2.2,2.2.3,2.2.4,3.1.1,3.1.2,3.1.3,3.1.4,3.2.1,3.2.2,3.2.3,3.2.4,3.3.1,3.3.2,3.3.3,3.3.4,3.4.1,3.4.2,3.4.3,3.4.4,4.1.1,4.1.2,4.1.3,4.1.4,4.2.1,4.2.2,4.2.3,4.2.4,5.1.1,6.1.1,6.1.2,6.1.3,6.1.4".($tt==1?",7.1.1,7.1.2,7.1.3,7.1.4":"")."'),
(2, 'Nhân viên kho', '2.1.1,2.1.2,2.1.3,2.1.4,2.2.1,2.2.2,2.2.3,3.2.1,3.2.2,3.2.3,3.2.4,3.3.1,3.3.2,3.3.3,3.3.4,3.4.1,3.4.2,3.4.3,3.4.4,4.2.1,4.2.2,4.2.3,4.2.4'),
(3, 'Nhân viên thu ngân', '3.1.1,3.1.2,3.1.3,3.1.4,4.1.1,4.1.2,4.1.3,4.1.4,5.1.1')".($tt==1?",
(4, 'Nhân viên bếp', '2.1.1,2.1.2,2.1.3,2.1.4,2.2.1,2.2.2,2.2.3,3.1.1,3.1.2,3.1.3,4.1.1')":"").";";

$sql38 = "ALTER TABLE  `product` ADD  `active` TINYINT( 2 ) NOT NULL DEFAULT  '1',
ADD  `minqty` FLOAT( 13, 3 ) NOT NULL DEFAULT  '0',
ADD  `maxqty` FLOAT( 13, 3 ) NOT NULL DEFAULT  '0',
ADD  `weight` FLOAT( 11, 3 ) NOT NULL DEFAULT  '0'";

$sql39 = "CREATE TABLE IF NOT EXISTS `table_order` (
  `id` int(11) NOT NULL auto_increment,
  `table` int(11) NOT NULL,
  `note` varchar(300) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$sql40 = "ALTER TABLE `table_order` ADD UNIQUE(`date`,`table`)";

$sql41 = "ALTER TABLE  `table` ADD  `status` TINYINT( 2 ) NOT NULL";
 
mysqli_query($link,$sql11);
mysqli_query($link,$sql12);
mysqli_query($link,$sql13);
mysqli_query($link,$sql14);
mysqli_query($link,$sql15);
mysqli_query($link,$sql16);
mysqli_query($link,$sql17);
mysqli_query($link,$sql18);
mysqli_query($link,$sql19);
mysqli_query($link,$sql20);
mysqli_query($link,$sql21);
mysqli_query($link,$sql22);
mysqli_query($link,$sql23);
mysqli_query($link,$sql24);
mysqli_query($link,$sql25);
mysqli_query($link,$sql26);
mysqli_query($link,$sql27);
mysqli_query($link,$sql28);
mysqli_query($link,$sql29);
////mysqli_query($link,$sql30);
mysqli_query($link,$sql31);
////mysqli_query($link,$sql32);
mysqli_query($link,$sql33);

 
mysqli_query($link,$sql35);
mysqli_query($link,$sql36);
mysqli_query($link,$sql37);

mysqli_query($link,$sql38);

mysqli_query($link,$sql39);
mysqli_query($link,$sql40);
mysqli_query($link,$sql41);

    
    if(!defined('DB_PREFIX')){
        define('DB_PREFIX','');
    }
    
    //print
$sql = 'CREATE TABLE IF NOT EXISTS `'. DB_PREFIX .'print` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;  ';
mysqli_query($link,$sql); 
 
    $sql = 'ALTER TABLE  `'. DB_PREFIX .'print` ADD UNIQUE (
`name`
)';
mysqli_query($link,$sql); 

//invoice return   
    mysqli_query($link,"ALTER TABLE  `order` ADD  `fee` INT( 11 ) NOT NULL DEFAULT  '0'"); 

//COD
    mysqli_query($link,"ALTER TABLE  `product` CHANGE  `weight`  `weight` INT( 11 ) NOT NULL DEFAULT  '0'");
    
    //service with time   
    mysqli_query($link,"ALTER TABLE  `product` ADD  `time` TINYINT( 2 ) NOT NULL DEFAULT  '0'"); 
    mysqli_query($link,"ALTER TABLE  `order_product` ADD  `t1` INT( 11 ) NOT NULL DEFAULT  '0',
ADD  `t2` INT( 11 ) NOT NULL DEFAULT  '0',
ADD  `run` TINYINT( 2 ) NOT NULL DEFAULT  '0'"); 

    //topping
    mysqli_query($link,"ALTER TABLE  `product` ADD  `extra` TINYINT( 2 ) NOT NULL DEFAULT  '0'");
    mysqli_query($link,"ALTER TABLE  `product` ADD  `topping` VARCHAR( 500 ) NOT NULL DEFAULT  ''"); 
    mysqli_query($link,"ALTER TABLE  `order_product` ADD  `topping` VARCHAR( 500 ) NOT NULL DEFAULT  ''"); 
    
    //multi tables for one invoice
    mysqli_query($link,"ALTER TABLE  `order` CHANGE  `table`  `table` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT  ''");
    
    //update customer
    mysqli_query($link,"ALTER TABLE  `customer` ADD  `lasttrade` DATE NOT NULL ,
ADD  `point` INT( 11 ) NOT NULL DEFAULT  '0',
ADD  `status` TINYINT( 2 ) NOT NULL DEFAULT  '1'"); 
    
    mysqli_query($link,"ALTER TABLE  `customer` ADD  `facebook` VARCHAR( 500 ) NOT NULL ,
ADD  `ward` VARCHAR( 500 ) NOT NULL,
ADD  `totalpoint` INT( 11 ) NOT NULL DEFAULT  '0'"); //06/02/2019

    mysqli_query($link,"ALTER TABLE  `customer` ADD  `totalpoint` INT( 11 ) NOT NULL DEFAULT  '0'"); //06/03/2019

    mysqli_query($link,"drop table if exists `zone`");
    
    //gio vao
    mysqli_query($link,'ALTER TABLE  `order` ADD  `datestart` DATETIME NOT NULL');
    
    mysqli_query($link,'CREATE TABLE IF NOT EXISTS `supplier_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,   
  `note` text NOT NULL,
  `branch` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

    mysqli_query($link,'CREATE TABLE IF NOT EXISTS `channel` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(500) NOT NULL,
  `img` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;');

    mysqli_query($link,"CREATE TABLE IF NOT EXISTS `surcharge` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` int(11) NOT NULL default '0',
  `valueratio` smallint(5) NOT NULL default '0',
  `order` smallint(5) NOT NULL default '0',
  `isauto` tinyint(2) NOT NULL default '0',
  `isreturnauto` tinyint(2) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

    mysqli_query($link,"ALTER TABLE  `order` ADD  `surcharge` INT( 11 ) NOT NULL DEFAULT  '0',
ADD  `extra` TEXT CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL");

//business
mysqli_query($link,"CREATE TABLE IF NOT EXISTS `news_category` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
 `parent_id` INT( 11 ) NOT NULL ,
 `name` VARCHAR( 100 ) NOT NULL ,
 `level` INT( 11 ) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM DEFAULT CHARSET = utf8;");
        mysqli_query($link,"CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` int(11) NOT NULL,
  `image` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL default '1',
  `content` text NOT NULL,
  `tags` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `code` (`code`),
  KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;");
        mysqli_query($link,"ALTER TABLE  `news_category` ADD  `code` VARCHAR( 100 ) NOT NULL;");
        mysqli_query($link,"ALTER TABLE  `news` ADD  `date` INT( 11 ) NOT NULL;");
        mysqli_query($link,"ALTER TABLE `news_category` ADD UNIQUE ( `code` );");
        mysqli_query($link,"ALTER TABLE `news` ADD UNIQUE ( `code` );");
        mysqli_query($link,"ALTER TABLE  `news` CHANGE  `date`  `date` DATETIME NOT NULL;");
        
      //---------
      //09/04/2019
    //mysqli_query($link,"ALTER TABLE  `product` ADD  `point` INT( 11 ) NOT NULL DEFAULT  '0'");
    mysqli_query($link,"ALTER TABLE  `product_branch` ADD  `point` INT( 11 ) NOT NULL DEFAULT  '0'");
    mysqli_query($link,"ALTER TABLE  `product` ADD  `ispoint` TINYINT( 2 ) NOT NULL DEFAULT  '0'");
    
    //09/17/2019
    mysqli_query($link,"ALTER TABLE  `customer` ADD  `numsold` INT( 11 ) NOT NULL");
    mysqli_query($link,"update `customer` c set c.numsold = (select count(d.id) from `order` d where d.customer=c.id)");
    
    //09/25/2019
    mysqli_query($link,"ALTER TABLE  `bankaccount` CHANGE  `no`  `account` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE  `note`  `description` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
    mysqli_query($link,"ALTER TABLE  `bankaccount` ADD  `bankcode` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL ,
ADD  `accountname` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
ADD  `branch` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");  



//2020/03/01
    mysqli_query($link,"CREATE TABLE IF NOT EXISTS `product_diary` (
  `id` int(11) NOT NULL auto_increment,
  `product` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(1000) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `product` (`product`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
    //2020/03/12
    mysqli_query($link,"ALTER TABLE product add created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;");
    mysqli_query($link,"ALTER TABLE `product` ADD `user` INT( 11 ) NOT NULL DEFAULT '0'");



function _get($gianhang){
     
    $curl = curl_init( );

	curl_setopt($curl, CURLOPT_URL,  "https://api.cloudflare.com/client/v4/zones/020b2ab776603ebc91c127bcb8badc89/dns_records");
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
        ,'X-Auth-Email: longpt.8688@gmail.com' 
        ,'X-Auth-Key: 1cb0f68b34c73f07e8855871b69b5b894e2f8'
    ));
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($curl, CURLOPT_HEADER, false); 
    
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
    
    curl_setopt($curl, CURLOPT_POSTFIELDS , '{"type":"A","name":"'.$gianhang.'.iquanly.com","content":"103.153.215.193","ttl":1,"proxied":true}'); 
    
	return curl_exec($curl);
}

_get($gianhang);

 
@header("Location: http://".$gianhang.".iquanly.com");
echo '<meta http-equiv="refresh" content="0;URL=http://'.$gianhang.'.iquanly.com"> ';
}
else echo 'mã kích hoạt không tồn tại';  

						
?>