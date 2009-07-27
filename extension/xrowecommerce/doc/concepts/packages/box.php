<?php
require_once 'autoload.php';

$boxes = array( 
    array( 
        'id' => 62914 , 
        'name' => "kleine 5er" , 
        'typ' => 1 , 
        'l' => 950 , 
        'w' => 320 , 
        'h' => 270 
    ) , 
    array( 
        'id' => 62912 , 
        'name' => "kleine 1er" , 
        'typ' => 1 , 
        'l' => 310 , 
        'w' => 240 , 
        'h' => 170 
    ) , 
    array( 
        'id' => 62916 , 
        'name' => "große 5er" , 
        'typ' => 2 , 
        'l' => 940 , 
        'w' => 360 , 
        'h' => 380 
    ) , 
    array( 
        'id' => 62915 , 
        'name' => "große 1er" , 
        'typ' => 2 , 
        'l' => 320 , 
        'w' => 340 , 
        'h' => 180 
    ) , 
    array( 
        'id' => 62917 , 
        'name' => "netpower" , 
        'typ' => 20 , 
        'l' => 520 , 
        'w' => 145 , 
        'h' => 330 
    ) , 
    array( 
        'id' => 62913 , 
        'name' => "kleine 2er" , 
        'typ' => 1 , 
        'l' => 320 , 
        'w' => 310 , 
        'h' => 250 
    ) 
);

$products = array( 
    array( 
        "id" => 1640 , 
        'name' => "MX-M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1640 , 
        'name' => "MX-M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1640 , 
        'name' => "MX-M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1640 , 
        'name' => "MX-M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 5912 , 
        'name' => "MX-M12M-Web" , 
        'l' => 345 , 
        'w' => 340 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1640 , 
        'name' => "MX-M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1640 , 
        'name' => "MX-M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1640 , 
        'name' => "MX-M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1782 , 
        'name' => "Power-Adapter-Set" , 
        'l' => 160 , 
        'w' => 100 , 
        'h' => 55 
    ) , 
    array( 
        "id" => 1782 , 
        'name' => "Power-Adapter-Set" , 
        'l' => 160 , 
        'w' => 100 , 
        'h' => 55 
    ) , 
    array( 
        "id" => 1782 , 
        'name' => "Power-Adapter-Set" , 
        'l' => 160 , 
        'w' => 100 , 
        'h' => 55 
    ) , 
    array( 
        "id" => 9999 , 
        'name' => "NetPower" , 
        'l' => 520 , 
        'w' => 145 , 
        'h' => 330 
    ) , 
    array( 
        "id" => 9999 , 
        'name' => "NetPower" , 
        'l' => 520 , 
        'w' => 145 , 
        'h' => 330 
    ) , 
    array( 
        "id" => 9999 , 
        'name' => "NetPower" , 
        'l' => 520 , 
        'w' => 145 , 
        'h' => 330 
    ) 
);

/*  $products=array(
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 3, 'name'=>"D12Di-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 4, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 5, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 6, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 7, 'name'=>"MX-D12D-OPT-DCS", 'l'=>120, 'w'=>120, 'h'=>80),
        array("id" => 8, 'name'=>"MX-D22M-OPT-IC", 'l'=>305, 'w'=>235, 'h'=>160),
        array("id" => 9, 'name'=>"MX-OPT-AP-10DEG", 'l'=>305, 'w'=>235, 'h'=>160),
        array("id" => 10, 'name'=>"MX-NPA-Set-DE", 'l'=>160, 'w'=>100, 'h'=>55),
        array("id" => 10, 'name'=>"MX-NPA-Set-DE", 'l'=>160, 'w'=>100, 'h'=>55),
        array("id" => 10, 'name'=>"MX-NPA-Set-DE", 'l'=>160, 'w'=>100, 'h'=>55),
        array("id" => 10, 'name'=>"MX-NPA-Set-DE", 'l'=>160, 'w'=>100, 'h'=>55),
        array("id" => 12, 'name'=>"MX-Q24M-Sec-D11", 'l'=>305, 'w'=>235, 'h'=>160)
    ); */

/*  $products=array(
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 1, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),

        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 2, 'name'=>"MX-M12D-Sec", 'l'=>345, 'w'=>340, 'h'=>160),

        array("id" => 3, 'name'=>"D12Di-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 3, 'name'=>"D12Di-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 3, 'name'=>"D12Di-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 3, 'name'=>"D12Di-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 3, 'name'=>"D12Di-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 3, 'name'=>"D12Di-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 3, 'name'=>"D12Di-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 3, 'name'=>"D12Di-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 3, 'name'=>"D12Di-Sec", 'l'=>345, 'w'=>340, 'h'=>160),
        array("id" => 3, 'name'=>"D12Di-Sec", 'l'=>345, 'w'=>340, 'h'=>160),

        array("id" => 4, 'name'=>"MX-OPT14-L43", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 4, 'name'=>"MX-OPT14-L43", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 4, 'name'=>"MX-OPT14-L43", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 4, 'name'=>"MX-OPT14-L43", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 4, 'name'=>"MX-OPT14-L43", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 4, 'name'=>"MX-OPT14-L43", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 4, 'name'=>"MX-OPT14-L43", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 4, 'name'=>"MX-OPT14-L43", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 4, 'name'=>"MX-OPT14-L43", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 4, 'name'=>"MX-OPT14-L43", 'l'=>90, 'w'=>40, 'h'=>60),

        array("id" => 5, 'name'=>"MX-OPT14-L22", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 5, 'name'=>"MX-OPT14-L22", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 5, 'name'=>"MX-OPT14-L22", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 5, 'name'=>"MX-OPT14-L22", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 5, 'name'=>"MX-OPT14-L22", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 5, 'name'=>"MX-OPT14-L22", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 5, 'name'=>"MX-OPT14-L22", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 5, 'name'=>"MX-OPT14-L22", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 5, 'name'=>"MX-OPT14-L22", 'l'=>90, 'w'=>40, 'h'=>60),
        array("id" => 5, 'name'=>"MX-OPT14-L22", 'l'=>90, 'w'=>40, 'h'=>60)
    );  

$products = array( 
    array( 
        "id" => 1 , 
        'name' => "M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1 , 
        'name' => "M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1 , 
        'name' => "M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1 , 
        'name' => "M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1 , 
        'name' => "M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1 , 
        'name' => "M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1 , 
        'name' => "M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1 , 
        'name' => "M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1 , 
        'name' => "M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1 , 
        'name' => "M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1 , 
        'name' => "M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    array( 
        "id" => 1 , 
        'name' => "M22M-Sec" , 
        'l' => 305 , 
        'w' => 235 , 
        'h' => 160 
    ) , 
    
    array( 
        "id" => 2 , 
        'name' => "Masthalter" , 
        'l' => 200 , 
        'w' => 70 , 
        'h' => 150 
    ) , 
    array( 
        "id" => 2 , 
        'name' => "Masthalter" , 
        'l' => 200 , 
        'w' => 70 , 
        'h' => 150 
    ) , 
    array( 
        "id" => 2 , 
        'name' => "Masthalter" , 
        'l' => 200 , 
        'w' => 70 , 
        'h' => 150 
    ) , 
    array( 
        "id" => 2 , 
        'name' => "Masthalter" , 
        'l' => 200 , 
        'w' => 70 , 
        'h' => 150 
    ) , 
    array( 
        "id" => 2 , 
        'name' => "Masthalter" , 
        'l' => 200 , 
        'w' => 70 , 
        'h' => 150 
    ) , 
    
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    array( 
        "id" => 3 , 
        'name' => "Objektivabdeckkappe" , 
        'l' => 105 , 
        'w' => 85 , 
        'h' => 50 
    ) , 
    
    array( 
        "id" => 4 , 
        'name' => "Power-Adapter-Set" , 
        'l' => 160 , 
        'w' => 100 , 
        'h' => 55 
    ) , 
    array( 
        "id" => 4 , 
        'name' => "Power-Adapter-Set" , 
        'l' => 160 , 
        'w' => 100 , 
        'h' => 55 
    ) , 
    array( 
        "id" => 4 , 
        'name' => "Power-Adapter-Set" , 
        'l' => 160 , 
        'w' => 100 , 
        'h' => 55 
    ) , 
    array( 
        "id" => 4 , 
        'name' => "Power-Adapter-Set" , 
        'l' => 160 , 
        'w' => 100 , 
        'h' => 55 
    ) , 
    array( 
        "id" => 4 , 
        'name' => "Power-Adapter-Set" , 
        'l' => 160 , 
        'w' => 100 , 
        'h' => 55 
    ) , 
    array( 
        "id" => 4 , 
        'name' => "Power-Adapter-Set" , 
        'l' => 160 , 
        'w' => 100 , 
        'h' => 55 
    ) , 
    array( 
        "id" => 4 , 
        'name' => "Power-Adapter-Set" , 
        'l' => 160 , 
        'w' => 100 , 
        'h' => 55 
    ) , 
    array( 
        "id" => 4 , 
        'name' => "Power-Adapter-Set" , 
        'l' => 160 , 
        'w' => 100 , 
        'h' => 55 
    ) , 
    array( 
        "id" => 4 , 
        'name' => "Power-Adapter-Set" , 
        'l' => 160 , 
        'w' => 100 , 
        'h' => 55 
    ) , 
    
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    array( 
        "id" => 5 , 
        'name' => "Network-Power-Box" , 
        'l' => 340 , 
        'w' => 185 , 
        'h' => 110 
    ) , 
    
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) , 
    array( 
        "id" => 6 , 
        'name' => "Objektiv" , 
        'l' => 90 , 
        'w' => 65 , 
        'h' => 40 
    ) 
);
*/
foreach ( $boxes as $item )
{
    $tmp = new xrowPackage( $item['l'], $item['w'], $item['h'] );
    $tmp->name = $item['name'];
    $tmp->typ = $item['typ'];
    $parcels[] = $tmp;
}
foreach ( $products as $item )
{
    $tmp = new xrowShippedProduct( $item['l'], $item['w'], $item['h'] );
    $tmp->name = $item['name'];
    $tmp->id = $item['id'];
    $items[] = $tmp;
}

$packlist = mxShipping::compute( $parcels, $items );

foreach ( $packlist as $parcel )
{
    echo $parcel->name . " includes : \n";
    foreach ( $parcel->contains as $product )
    {
        echo "   " . $product->name . " \n";
    }
}
echo " ALTE BERECHUNG  ";
$country = 'Germany';
$calculator = new MXBoxCalculator( );
$packlist = $calculator->calculate( $boxes, $products, $country );
foreach ( $packlist as $key => $parcel )
{
    foreach ( $boxes as $box )
    {
        if ( $box['id'] == $key )
        {
            
            break;
        }
    }
    foreach ( $parcel as $pa )
    {
    echo $box['name'] . " includes : \n";
            foreach ( $pa as $product )
            {
            
                foreach ( $products as $product2 )
                {
                    if ( $product2['id'] == $product )
                    {
                        echo "   " . $product2['name'] . "\n";
                        break;
                    }
                }
            }
     
    }
}

?>
  
