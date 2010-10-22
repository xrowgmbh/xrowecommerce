<?php

if (!function_exists('str_getcsv'))
{
    function str_getcsv($input, $delimiter = ",", $enclosure = '"', $escape = "\\")
    {
        $fiveMBs = 5 * 1024 * 1024;
        $fp = fopen("php://temp/maxmemory:$fiveMBs", 'r+');
        fputs($fp, $input);
        rewind($fp);

        $data = fgetcsv($fp, 1000, $delimiter, $enclosure); //  $escape only got added in 5.3.0

        fclose($fp);
        return $data;
    }
}

?>