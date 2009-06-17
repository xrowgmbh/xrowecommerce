<?php

class MXBoxCalculator
{

    function calculate( $boxes, $products )
    {
        $nbrBoxes = count( $boxes );
        $nbrProducts = count( $products );
        $segments = array();
        $packlist = array();
        $boxCounter = 0;
        
        // Volumen der Boxen ermitteln
        for ( $loop = 0; $loop < $nbrBoxes; $loop ++ )
        {
            $boxes[$loop]['v'] = $boxes[$loop]['l'] * $boxes[$loop]['w'] * $boxes[$loop]['h'];
        }
        
        // Boxen nach Volumen sortieren (einfach Bubblesort, da nicht viele Elemente)
        for ( $i = $nbrBoxes - 1; $i > 0; $i -- )
        {
            for ( $j = 0; $j < $i; $j ++ )
            {
                if ( $boxes[$j]['v'] > $boxes[$j + 1]['v'] )
                {
                    $swap = $boxes[$j];
                    $boxes[$j] = $boxes[$j + 1];
                    $boxes[$j + 1] = $swap;
                }
            }
        }
        
        // Volumen der Produkte ermitteln
        for ( $loop = 0; $loop < $nbrProducts; $loop ++ )
        {
            $products[$loop]['v'] = $products[$loop]['l'] * $products[$loop]['w'] * $products[$loop]['h'];
            $products[$loop]['done'] = FALSE;
        }
        
        // Produkte nach Volumen sortieren - größtes Volumen zuerst (einfach Bubblesort, da nicht viele Elemente)
        for ( $i = $nbrProducts - 1; $i > 0; $i -- )
        {
            for ( $j = 0; $j < $i; $j ++ )
            {
                if ( $products[$j]['v'] < $products[$j + 1]['v'] )
                {
                    $swap = $products[$j];
                    $products[$j] = $products[$j + 1];
                    $products[$j + 1] = $swap;
                }
            }
        }
        
        do
        {
            // Volumen aller noch nicht verpackten Produkte ermitteln
            $volumen = 0;
            for ( $loop = 0; $loop < $nbrProducts; $loop ++ )
            {
                if ( ! $products[$loop]['done'] )
                    $volumen += $products[$loop]['v'];
            }
            
            $index = 0;
            // erstes, noch nicht verpacktes Produkt suchen
            while ( $index < $nbrProducts && $products[$index]['done'] )
                $index ++;
            if ( $index >= $nbrProducts )
            {
                //				echo "Alles verpackt...\n";
                return $packlist;
            }
            
            // Sucht für das größte Produkt die kleinst möglichste Schachtel
            $boxIndex = 0;
            while ( $boxIndex < $nbrBoxes && $this->checkSize( $boxes[$boxIndex]['l'], $boxes[$boxIndex]['w'], $boxes[$boxIndex]['h'], $products[$index]['l'], $products[$index]['w'], $products[$index]['h'] ) == NULL )
                $boxIndex ++;
            if ( $boxIndex >= $nbrBoxes )
                return NULL; // keine passende Box für das Produkt
            $boxType = $boxes[$boxIndex]['typ'];
            
            // Sucht nach der kleinsten Kiste, welche die ermittelte Typennummer hat 
            // und in welches das Gesamtvolumen der nicht verpackten Produkte passt.
            $found = FALSE;
            $loop = 0;
            while ( $loop < $nbrBoxes && ! $found )
            {
                if ( $boxes[$loop]['typ'] == $boxType )
                {
                    $boxIndex = $loop;
                    if ( $boxes[$loop]['v'] >= $volumen )
                        $found = TRUE;
                }
                if ( ! $found )
                    $loop ++;
            }
            //			echo 'Verwendete Box='.$boxes[$boxIndex]['name'];
            $boxCounter ++;
            $currentBoxId = $boxes[$boxIndex]['id'];
            if ( ! array_key_exists( $currentBoxId, $packlist ) )
                $packlist[$currentBoxId] = array();
            $packlist[$currentBoxId][$boxCounter] = array();
            
            // Das größte Produkt als verpackt markieren und die freien Bereiche der Box 
            // in eine Tabelle übernehmen.
            $products[$index]['done'] = TRUE;
            $dimensions = $this->checkSize( $boxes[$boxIndex]['l'], $boxes[$boxIndex]['w'], $boxes[$boxIndex]['h'], $products[$index]['l'], $products[$index]['w'], $products[$index]['h'] );
            if ( $dimensions == NULL )
            {
                echo 'Ungültige Boxtabelle';
                return NULL;
            }
            $volumen = ( $boxes[$boxIndex]['l'] - $dimensions['l'] ) * $dimensions['w'] * $dimensions['h'];
            if ( $volumen > 0 )
                array_push( $segments, array( 
                    'l' => $boxes[$boxIndex]['l'] - $dimensions['l'] , 
                    'w' => $dimensions['w'] , 
                    'h' => $dimensions['h'] , 
                    'v' => $volumen 
                ) );
            $volumen = $boxes[$boxIndex]['l'] * $boxes[$boxIndex]['w'] * ( $boxes[$boxIndex]['h'] - $dimensions['h'] );
            if ( $volumen > 0 )
                array_push( $segments, array( 
                    'l' => $boxes[$boxIndex]['l'] , 
                    'w' => $boxes[$boxIndex]['w'] , 
                    'h' => $boxes[$boxIndex]['h'] - $dimensions['h'] , 
                    'v' => $volumen 
                ) );
            $volumen = $boxes[$boxIndex]['l'] * ( $boxes[$boxIndex]['w'] - $dimensions['w'] ) * $dimensions['h'];
            if ( $volumen > 0 )
                array_push( $segments, array( 
                    'l' => $boxes[$boxIndex]['l'] , 
                    'w' => $boxes[$boxIndex]['w'] - $dimensions['w'] , 
                    'h' => $dimensions['h'] , 
                    'v' => $volumen 
                ) );
            array_push( $packlist[$currentBoxId][$boxCounter], $products[$index]['id'] );
            
            $index = 0;
            // versuchen so viel wie möglich, der anderen Produkte in die Kiste zu packen
            while ( $index < $nbrProducts )
            {
                // Überprüfen, ob es in eines der Boxsegmente passt.
                $segmentIndex = 0;
                while ( ! $products[$index]['done'] && $segmentIndex < count( $segments ) )
                {
                    $dimensions = $this->checkSize( $segments[$segmentIndex]['l'], $segments[$segmentIndex]['w'], $segments[$segmentIndex]['h'], $products[$index]['l'], $products[$index]['w'], $products[$index]['h'] );
                    if ( $dimensions != NULL )
                    {
                        $products[$index]['done'] = TRUE;
                        $volumen = ( $segments[$segmentIndex]['l'] - $dimensions['l'] ) * $dimensions['w'] * $dimensions['h'];
                        if ( $volumen > 0 )
                            array_push( $segments, array( 
                                'l' => $segments[$segmentIndex]['l'] - $dimensions['l'] , 
                                'w' => $dimensions['w'] , 
                                'h' => $dimensions['h'] , 
                                'v' => $volumen 
                            ) );
                        $volumen = $segments[$segmentIndex]['l'] * $segments[$segmentIndex]['w'] * ( $segments[$segmentIndex]['h'] - $dimensions['h'] );
                        if ( $volumen > 0 )
                            array_push( $segments, array( 
                                'l' => $segments[$segmentIndex]['l'] , 
                                'w' => $segments[$segmentIndex]['w'] , 
                                'h' => $segments[$segmentIndex]['h'] - $dimensions['h'] , 
                                'v' => $volumen 
                            ) );
                        $volumen = $segments[$segmentIndex]['l'] * ( $segments[$segmentIndex]['w'] - $dimensions['w'] ) * $dimensions['h'];
                        if ( $volumen > 0 )
                            array_push( $segments, array( 
                                'l' => $segments[$segmentIndex]['l'] , 
                                'w' => $segments[$segmentIndex]['w'] - $dimensions['w'] , 
                                'h' => $dimensions['h'] , 
                                'v' => $volumen 
                            ) );
                            //						unset($segments[$segmentIndex]);
                        $segments[$segmentIndex]['v'] = 0;
                        $segments[$segmentIndex]['l'] = 0;
                        array_push( $packlist[$currentBoxId][$boxCounter], $products[$index]['id'] );
                    }
                    else
                    {
                        $segmentIndex ++;
                    }
                }
                $index ++;
            }
        
        }
        while ( TRUE );
    
    }

    // Überprüft, ob ein Produkt in eine Box passt.
    // Rückgabewert: array mit l,w,h des gedrehten Produkts oder NULL wenn es nicht passt.
    

    function checkSize( $lBox, $wBox, $hBox, $lProd, $wProd, $hProd )
    {
        $areaBox = $wBox * $hBox;
        
        // Produktgrundflächen ermitteln und absteigend sortieren
        $areas = array( 
            array( 
                'a' => $lProd * $wProd , 
                'l' => $hProd , 
                'w' => $lProd , 
                'h' => $wProd 
            ) , 
            array( 
                'a' => $lProd * $hProd , 
                'l' => $wProd , 
                'w' => $lProd , 
                'h' => $hProd 
            ) , 
            array( 
                'a' => $wProd * $hProd , 
                'l' => $lProd , 
                'w' => $wProd , 
                'h' => $hProd 
            ) , 
            array( 
                'a' => $lProd * $wProd , 
                'l' => $hProd , 
                'w' => $wProd , 
                'h' => $lProd 
            ) , 
            array( 
                'a' => $lProd * $hProd , 
                'l' => $wProd , 
                'w' => $hProd , 
                'h' => $lProd 
            ) , 
            array( 
                'a' => $wProd * $hProd , 
                'l' => $lProd , 
                'w' => $hProd , 
                'h' => $wProd 
            ) 
        );
        
        // Produktgrundflächen nach Fläche sortieren - größte Fläche zuerst (einfach Bubblesort, da nicht viele Elemente)
        for ( $i = 5; $i > 0; $i -- )
        {
            for ( $j = 0; $j < $i; $j ++ )
            {
                if ( $areas[$j]['a'] < $areas[$j + 1]['a'] )
                {
                    $swap = $areas[$j];
                    $areas[$j] = $areas[$j + 1];
                    $areas[$j + 1] = $swap;
                }
            }
        }
        
        // Sucht die Produktposition mit der größte Grundfläche in der Box.
        for ( $loop = 0; $loop < 6; $loop ++ )
        {
            if ( $areas[$loop]['l'] <= $lBox && $areas[$loop]['w'] <= $wBox && $areas[$loop]['h'] <= $hBox )
            {
                return array( 
                    'l' => $areas[$loop]['l'] , 
                    'w' => $areas[$loop]['w'] , 
                    'h' => $areas[$loop]['h'] 
                );
            }
        }
        // Produkt passt nicht in die Box
        return NULL;
    }
}
?>
  
