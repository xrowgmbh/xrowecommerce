<?php
	require_once './boxcalculator.php';

	$boxes=array(
		array('id'=>62914, 'name' => "kleine 5er", 'typ'=>1, 'l'=>950, 'w'=>320, 'h'=>270),
		array('id'=>62912, 'name' => "kleine 1er", 'typ'=>1, 'l'=>310, 'w'=>240, 'h'=>170),
		array('id'=>62916, 'name' => "große 5er",  'typ'=>2, 'l'=>940, 'w'=>360, 'h'=>380),
		array('id'=>62915, 'name' => "große 1er",  'typ'=>2, 'l'=>320, 'w'=>340, 'h'=>180),
		array('id'=>62917, 'name' => "netpower",   'typ'=>3, 'l'=>520, 'w'=>145, 'h'=>330),
		array('id'=>62913, 'name' => "kleine 2er", 'typ'=>1, 'l'=>320, 'w'=>310, 'h'=>250)
	);

	$products=array(
		array("id" => 1640, 'name'=>"MX-M22M-Sec", 'l'=>305, 'w'=>235, 'h'=>160),
		array("id" => 1640, 'name'=>"MX-M22M-Sec", 'l'=>305, 'w'=>235, 'h'=>160),
		array("id" => 1640, 'name'=>"MX-M22M-Sec", 'l'=>305, 'w'=>235, 'h'=>160),
		array("id" => 1640, 'name'=>"MX-M22M-Sec", 'l'=>305, 'w'=>235, 'h'=>160),
		array("id" => 5912, 'name'=>"MX-M12M-Web", 'l'=>345, 'w'=>340, 'h'=>160),
		array("id" => 1640, 'name'=>"MX-M22M-Sec", 'l'=>305, 'w'=>235, 'h'=>160),
		array("id" => 1640, 'name'=>"MX-M22M-Sec", 'l'=>305, 'w'=>235, 'h'=>160),
		array("id" => 1640, 'name'=>"MX-M22M-Sec", 'l'=>305, 'w'=>235, 'h'=>160),
		array("id" => 1782, 'name'=>"Power-Adapter-Set", 'l'=>160, 'w'=>100, 'h'=>55),
		array("id" => 1782, 'name'=>"Power-Adapter-Set", 'l'=>160, 'w'=>100, 'h'=>55),
		array("id" => 1782, 'name'=>"Power-Adapter-Set", 'l'=>160, 'w'=>100, 'h'=>55)
	);

	$country='Germany';

	$calculator=new mxboxCalculator();
	$packlist=$calculator->calculate($boxes, $products, $country);

	print_r($packlist);

?>
  
