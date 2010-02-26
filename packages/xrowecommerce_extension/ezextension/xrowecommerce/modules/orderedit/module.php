<?php
$Module = array( 
    "name" => "Order edit" 
);

$ViewList = array();
$ViewList["edit"] = array( 
    'functions' => array( 
        'orderedit' 
    ) , 
    'default_navigation_part' => 'ezshopnavigationpart' , 
    "script" => "edit.php" , 
    "params" => array( 
        "orderid" 
    ) 
);
$ViewList["product"] = array( 
    'functions' => array( 
        'orderedit' 
    ) , 
    'default_navigation_part' => 'ezshopnavigationpart' , 
    "script" => "product.php" 
);
$ViewList["taxes"] = array( 
    'functions' => array( 
        'orderedit' 
    ) , 
    'default_navigation_part' => 'ezshopnavigationpart' , 
    "script" => "taxes.php" , 
    "params" => array( 
        'StartYear' , 
        'StartMonth' , 
        'StopMonth' , 
        'StartDay' , 
        'StopDay' 
    ) 
);
$ViewList["inventory"] = array( 
    'functions' => array( 
        'orderedit' 
    ) , 
    'default_navigation_part' => 'ezshopnavigationpart' , 
    "script" => "inventory.php" 
);

$FunctionList['orderedit'] = array();
?>