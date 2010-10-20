<?php
// Created on: <28-Mar-2004 21:56:00 gwf>
//
// Copyright (C) 2004 Verlag Franz. All rights reserved.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
//  Utilities for converting text strings in ez3, http://www.ez.no
//  Written by Georg Franz,
//  Feedback / Bug reports: Georg Franz <georg@verlagfranz.com>
//  uses the php-extensions mbstring and iconv, if they are installed
//
//  Attention: The class is not using the textcodec-methods of ez3!!
//  Use it only if you are using UTF-8 in combination with ISO-8859-1 charsets
//  or if you are using ISO-8859-1 charset alone.
//  Otherwise you have to rewrite some methods!
//
// Installation:
// You need
// [MailSettings]
// MailHost=www.yourdomain.com
// in your site.ini.append if you are using self::add_host
// Look there for further infos.
//
// If you are using the "email conversion" you need the javascript code too.
// look at self::change_email for further details
//
/*
javascript code:
--------------------
function buildtext(friday,monday,tuesday,sunday,thirsday)
{
  window.open(tuesday+sunday+monday,'','');
}

function writeMessage(friday,monday,tuesday,sunday,thirsday,christmas)
{
  document.write(christmas+thirsday+monday+sunday);
}

function displayEmailStatusMsg(sunday,friday,monday,tuesday,thirsday) {
    status=friday+thirsday+tuesday;
    document.MM_returnValue = true;
}

function MM_displayStatusMsg(msgStr)
{
    status=msgStr;
    document.MM_returnValue = true;
}

*/
class xrowTextUtils
{
    public static function add_host( $text, $host = '', $include_images = false )
    {
        if ($host == '')
        {
            $host = eZSys::serverVariable( 'HTTP_HOST' );
        }

        if ( $host === false or strlen( $host ) == 0 )
        {
            return $text;
        }

        $temp = $text;

        // urls
        $temp = preg_replace ("#(href=['|\"])([^'|\"]*)(\s|'|\")#sie",
                              "self::add_host_helper('\\1','\\2','\\3','$host')", $temp );

        // images
        if ( $include_images )
        {
            $temp = preg_replace ("#(src=['|\"])([^'|\"]*)(\s|'|\")#sie",
                              "self::add_host_helper('\\1','\\2','\\3','$host')", $temp );
        }

        return $temp;
    }

    public static function add_host_helper( $href_begin, $url, $href_end, $host )
    {

        if ( substr( $host, 0, 4 ) != 'http' )
        {
            $host = 'http://' . $host;
        }
        if ( substr( $url,0,4 ) == "www." )
        {
            $url = "http://" . $url;
        }
        else
        {
            if ( substr( $url,0,1 ) == "/" )
            {
                $url = $host . $url;
            }
            else
            {
                if ( strstr( $url,"://" ) === false )
                {
                    if ( substr( $url,0,6 ) != "mailto" and substr( $url,0,4 ) != "java" )
                    {
                        $url = "http://" . $host . $url;
                    }
                }
            }
        }

        $text = $href_begin . $url . $href_end;
        return stripslashes( $text );
    }

    public static function convertSpecialCharacters ($text)
    {
        $specialChars  = array ( "à", "á", "â", "ã", "ä", "å", "æ", "è", "é", "ê", "ß", " ", "'", "´", "`",
                                 "ë", "Ç", "í", "ì", "ò", "ó", "ô", "õ", "ö", "ù", "ú", "û", "ü");
        $normalChars   = array ( "a", "a", "a", "a", "ae", "a", "ae", "e", "e", "e", "ss", "-", "", "", "",
                                 "e", "c", "i", "i", "o", "o", "o", "o", "oe", "u", "u", "u", "ue");

        return str_replace ( $specialChars, $normalChars, $text );
    }



    /*
        converts a text for output
        usefull for e.g. forum messages
    */
    public static function textWash( $text )
    {
        $text = str_replace ( "&amp;#", "&#", $text);
        $text = self::replaceWordChars($text,true);

        $text = self::replaceHtmlEntities($text);
        $text = self::replaceWordChars($text,true);
        $text = self::cleanUpHTML($text);
        $text = wordwrap( $text, 120, "\n", 1);
        $text = htmlspecialchars ( $text );
        $text = nl2br($text);

        return $text;
    }

    /*
        Converts a html-text to plain text.
        e.g. Usefull for getting a html-mail input and make a
        "plain text" copy

        text: The input text
        break_word: if using word_wrap()
        break_width: the cutting position of word_wrap()
    */
    public static function convert_plain( $text, $break_word = false, $break_width = 60 )
    {
        $temp = $text;

        $temp = str_replace ("\r", "", $temp);
        $temp = str_replace ("\t", " ", $temp);
        $temp = preg_replace ("#<br([^>]*)>#si", "\n", $temp);

        $temp = self::replaceWordChars($temp,true);
        $temp = self::replaceHtmlEntities($temp);

        $temp = self::remove_img($temp,true);
        $temp = self::convert_url($temp,true);

        $temp = self::cleanUpHTML($temp);

        $temp = html_entity_decode( $temp, ENT_COMPAT, 'UTF-8' );

        $temp = self::strip_whitespaces($temp);
        $temp = self::strip_multiple_lines($temp);

        if ($break_word === true)
        {
            $temp = wordwrap( $temp, $break_width, "\n", 0);
        }

        $temp = trim($temp);

        return $temp;
    }

    /*
        Helper public static function to get a clean mail name
        e.g. input:
        &#8220;John Doe&#8222;
        becomes to
        „John Doe“

    */
    public static function convert_mail_line($text)
    {
        // converts unwanted characters
        $temp = $text;

        $temp = str_replace ("\t", " ", $temp);
        $temp = html_entity_decode( $temp, ENT_COMPAT, 'UTF-8' );
        $temp = self::replaceHtmlEntities($temp);
        $temp = self::replaceWordChars($temp,true);
        $temp = self::strip_whitespaces($temp);

        return $temp;
    }

    /*
        Helper public static function to convert a email-address for not beeing accessed by spammers
        It uses a javascript.

        Example:
        melker@kuh.at will be convert e.g. to
        <a href="javascript:buildtext('&#69;&#x6e;&#x6e;&#x44;&#x48;&#46;','&#46;&#x61;&#116;','&#x6d;&#97;&#105;&#x6c;&#x74;&#x6f;&#x3a;&#109;&#x65;&#x6c;&#107;&#101;','&#x72;&#x40;&#107;&#117;&#104;','&#97;&#117;&#x59;&#x46;&#x4b;&#105;&#46;');" onMouseOver="displayEmailStatusMsg('&#97;&#117;&#x59;&#x46;&#x4b;&#105;&#46;','&#x6d;&#97;&#105;&#x6c;&#x74;&#x6f;&#x3a;&#109;&#x65;&#x6c;&#107;&#101;','&#69;&#x6e;&#x6e;&#x44;&#x48;&#46;','&#46;&#x61;&#116;','&#x72;&#x40;&#107;&#117;&#104;');return document.MM_returnValue" onmouseout="MM_displayStatusMsg('');"><script type="text/javascript">
<!--
    writeMessage('&#x68;&#x70;&#x66;&#117;&#78;&#x59;&#x43;&#x2e;','&#x4d;&#x65;&#x6c;','&#x7a;&#68;&#112;&#x33;&#107;&#103;&#110;&#46;','&#107;&#101;&#114;','&#x65;&#x66;&#x20;','&#x4a;&#111;&#115;');
//-->
</script></a>

        Advantages:
        Real visitors are able to click on e-mail links and the mail program opens.

        The js works with all browsers except Opera.

    */
    public static function change_email( $email, $link_text='', $first_character='', $last_character = '' )
    {
        $email = str_replace ("mailto:","",$email);
        if ($link_text =='')
        {
            $link_text = $email;
        }
        else
        {
            $link_text = $link_text;
        }

        $link_text = addslashes($link_text);

        $dummy1 = self::createPassword (rand(5,10)).".";
        $dummy2 = self::createPassword (rand(5,10)).".";

        $dummy1 = self::encodeEmail ($dummy1);
        $dummy2 = self::encodeEmail ($dummy2);

        $link_1 = '';
        $link_2 = '';
        $link_3 = '';
        $link_4 = '';

        $link_array = array();
        if ( strlen ( $link_text ) >= 4 )
        {
            $link_len = ceil( strlen ($link_text) / 4 );

            $link_array = chunk_split ($link_text,$link_len,'#ö#');
            $link_array = explode ("#ö#", $link_array);
        }
        else
            $link_1 = $link_text;

        if ( isset ( $link_array[0] ) )
            $link_1 = $link_array[0];

        if ( isset ( $link_array[1] ) )
            $link_2 = $link_array[1];

        if ( isset ( $link_array[2] ) )
            $link_3 = $link_array[2];

        if ( isset ( $link_array[3] ) )
            $link_4 = $link_array[3];

        $link_1 = self::encodeEmail ($link_1);
        $link_2 = self::encodeEmail ($link_2);
        $link_3 = self::encodeEmail ($link_3);
        $link_4 = self::encodeEmail ($link_4);

        $link_text = "<script type=\"text/javascript\">
<!--
    writeMessage('$dummy1','$link_3','$dummy2','$link_4','$link_2','$link_1');
//-->
</script>";

        $dummy1 = self::createPassword (rand(5,10)).".";
        $dummy2 = self::createPassword (rand(5,10)).".";

        $dummy1 = self::encodeEmail ($dummy1);
        $dummy2 = self::encodeEmail ($dummy2);

        $mailto = $email;

        $email_len = ceil( strlen ($mailto) /3 );

        $email_array = chunk_split ($mailto,$email_len,'#ö#');
        $email_array = explode ("#ö#", $email_array);

        $email_1 = "mailto:".$email_array[0];
        $email_2 = $email_array[1];
        $email_3 = $email_array[2];

        $email_1 = self::encodeEmail ($email_1);
        $email_2 = self::encodeEmail ($email_2);
        $email_3 = self::encodeEmail ($email_3);

        $email_link = $first_character."<a href=\"javascript:buildtext('$dummy1','$email_3','$email_1','$email_2','$dummy2');\" onMouseOver=\"displayEmailStatusMsg('$dummy2','$email_1','$dummy1','$email_3','$email_2');return document.MM_returnValue\" onmouseout=\"MM_displayStatusMsg('');\">$link_text</a>".$last_character;

        return $email_link;
    }

    public static function encryptText ( $text )
    {
        $dummy1 = self::createPassword (rand(5,10)).".";
        $dummy2 = self::createPassword (rand(5,10)).".";

        $dummy1 = self::encodeEmail ($dummy1);
        $dummy2 = self::encodeEmail ($dummy2);

        $text_len = ceil( strlen ($text) / 4 );

        $text_array = chunk_split ($text,$text_len,'#ö#');
        $text_array = explode ("#ö#", $text_array);

        $text_1 = $text_array[0];
        $text_2 = substr ( $text_1, 2 ) . $text_array[1];
        $text_1 = substr ( $text_1, 0, 2 );
        $text_3 = $text_array[2];
        $text_4 = $text_array[3];

        $text_1 = self::encodeEmail ($text_1);
        $text_2 = self::encodeEmail ($text_2);
        $text_3 = self::encodeEmail ($text_3);
        $text_4 = self::encodeEmail ($text_4);

        $text = "<script type=\"text/javascript\">
<!--
    writeMessage('$dummy1','$text_3','$dummy2','$text_4','$text_2','$text_1');
//-->
</script>";

        return $text;
    }

    public static function formatUri( $url, $max )
    {
        $text = $url;
        if (strlen($text) > $max)
        {
            $text = substr($text, 0, ($max / 2) - 3). '...'. substr($text, strlen($text) - ($max / 2));
        }
        return "<a href=\"$url\" title=\"$url\" target=\"_blank\">$text</a>";
    }

    /*!
     \static
    */
    public static function addURILinks( $text, $max, $methods = 'http|https|ftp' )
    {
        return preg_replace(
            "!($methods):\/\/[\w]+(.[\w]+)([\w\-\.,@?^=%&:\/~\+#;*\(\)\!\']*[\w\-\@?^=%&\/~\+#;*\(\)\!\'])?!e",
            'self::formatUri("$0", '. $max. ')',
            $text
        );
    }

    /*
        Helper for change_email
    */
    public static function encodeEmail ($originalString, $mode = 3)
    {
        if ( strlen ( $originalString ) == 0 )
            return $originalString;

        $encodedString = "";
        $nowCodeString = "";
        $randomNumber = -1;
        $max = 3;

        $originalLength = strlen($originalString);
        $encodeMode = $mode;

        for ( $i = 0; $i < $originalLength; $i++)
        {
            if ($mode == 3 )
            {
                $encodeMode = rand(1,$max);
            }

            switch ($encodeMode)
            {
                case 1: // Decimal code
                {
                    $nowCodeString = "&#" . ord($originalString{$i}) . ";";
                }
                break;
                case 2: // Hexadecimal code
                {
                    $nowCodeString = "&#x" . dechex(ord($originalString{$i})) . ";";
                }
                break;
                case 3: // Normal
                {
                    $nowCodeString = $originalString{$i};
                }
                break;
            }
            $encodedString .= $nowCodeString;
        }
        return $encodedString;
    }

    /*
        removes all img-tags from html-input
    */
    public static function remove_img($text)
    {
        return preg_replace ("#<img([^>]*)>#si", "", $text);
    }

    // replace <a href="http://www.kuh.at">kuh.at</a>
    // to
    // kuh.at ( http://www.kuh.at )
    // Usefull for plain text emails

    public static function convert_url ($text)
    {
        $text = str_replace ( '"', "'", $text );
        return preg_replace("#<a .*?href='([^']*)'[^>]*>([^<]*)</a>#sie",
                            "self::check_url_string('\\1','\\2')", $text );
    }

    /*
        private
        used by convert_url
    */
    public static function check_url_string ($href, $name)
    {
        $href = trim ( $href );
        $name = trim ( $name );
        $name = str_replace ( "\n", "", $name );
        $name = str_replace ( "\r", "", $name );
        $name = str_replace ( "\t", "", $name );
        $name = preg_replace ( "# +#", " ", $name );

        if ( $name == "")
        {
            return "";
        }

        if ( $href == $name )
        {
            return $href;
        }
        elseif ( $href == 'mailto:' . $name )
        {
            return $name;
        }
        else
        {
            return "$name ( $href )";
        }
    }

    /*
        strips out multiple, empty lines
        used by html to plain text conversion for emails
    */
    public static function strip_multiple_lines ($text, $line_search = 3, $line_replace = 2)
    {
        $temp = $text;
        $temp = str_replace ( "\r", "", $temp );
        $replace = str_repeat("\n",$line_replace);
        $temp = preg_replace ("#(\n{".$line_search.",})#i", $replace, $temp ); // strip out multiple lines

        return $temp;
    }

    /*
        strips out whitespaces
    */
    public static function strip_whitespaces ($text, $break = false)
    {
        $temp = $text;

        $temp = preg_replace("#([\t| ]{1,})#si", " ", $temp ); // strip out multiple whitespaces
        $temp = preg_replace("#\n[ |\t]*#si", "\n", $temp);

        return $temp;
    }

    /*
        the opposite of htmlspecialchars()
    */
    public static function html_normal_chars( $text )
    {
       $text = html_entity_decode( $text, ENT_COMPAT, 'UTF-8' );
       return $text;
    }

    /*
        strips out html-code of $text
    */
    public static function cleanUpHTML( $text )
    {
        $search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
                 "'<style[^>]*?>.*?</style>'si",
                 "'<noscript>*?</noscript>'si",
                 "'<title([^>]*)>([^<]*)</title>'si", // Strip html-title
                 "#<\/li>\s+<li[^>]*>#si",
                 "'<[\/\!]*?[^<>]*?>'si",           // Strip out html tags
                 "# +,#si"
                 );

        $replace = array ("",
                  "",
                  "",
                  "",
                  ", ",
                  " ",
                  ","
                 );
        $text = preg_replace( $search, $replace, $text );
        #eZDebug::writeDebug( $text, 'text' );
        return $text;
    }

    /*
        Replaces HTML entities
    */
    public static function replaceHtmlEntities ($text)
    {
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans = array_flip($trans);
        return strtr($text, $trans);
        /*
        $search = array (
                 "'&(quot|#34);'i",                 // Replace html entities
                 "'&(amp|#38);'i",
                 "'&(lt|#60);'i",
                 "'&(gt|#62);'i",
                 "'&(euro|#128);'i",
                 "'&(nbsp|#160);'i",
                 "'&(iexcl|#161);'i",
                 "'&(cent|#162);'i",
                 "'&(pound|#163);'i",
                 "'&(yen|#165);'i",
                 "'&(copy|#169);'i",
                 "'&(laquo|#171);'i",
                 "'&(reg|#174);'i",
                 "'&(raquo|#187);'i",
                 "'&(frac14|#188);'i",
                 "'&(frac12|#189);'i",
                 "'&(frac34|#190);'i",
                 "'&(Agrave|#192);'i",
                 "'&(Acirc|#194);'i",
                 "'&(Auml|#196);'i",
                 "'&(AElig|#198);'i",
                 "'&(Ccedil|#199);'i",
                 "'&(Egrave|#200);'i",
                 "'&(Eacute|#201);'i",
                 "'&(Ecirc|#202);'i",
                 "'&(Igrave|#204);'i",
                 "'&(Icirc|#206);'i",
                 "'&(Ograve|#210);'i",
                 "'&(Ocirc|#212);'i",
                 "'&(Ouml|#214);'i",
                 "'&(Ugrave|#217);'i",
                 "'&(Ucirc|#219);'i",
                 "'&(Uuml|#220);'i",
                 "'&(szlig|#223);'i",
                 "'&(agrave|#224);'i",
                 "'&(aacute|#225);'i",
                 "'&(acirc|#226);'i",
                 "'&(auml|#228);'i",
                 "'&(aelig|#230);'i",
                 "'&(ccedil|#231);'i",
                 "'&(egrave|#232);'i",
                 "'&(eacute|#233);'i",
                 "'&(ecirc|#234);'i",
                 "'&(igrave|#236);'i",
                 "'&(icirc|#238);'i",
                 "'&(ograve|#242);'i",
                 "'&(ocirc|#244);'i",
                 "'&(ouml|#246);'i",
                 "'&(ugrave|#249);'i",
                 "'&(ucirc|#251);'i",
                 "'&(uuml|#252);'i"
                 );

        $replace = array (
                  chr(34),
                  chr(38),
                  chr(60),
                  chr(62),
                  chr(128),
                  chr(160),
                  chr(161),
                  chr(162),
                  chr(163),
                  chr(165),
                  chr(169),
                  chr(171),
                  chr(174),
                  chr(187),
                  chr(188),
                  chr(189),
                  chr(190),
                  chr(192),
                  chr(194),
                  chr(196),
                  chr(198),
                  chr(199),
                  chr(200),
                  chr(201),
                  chr(202),
                  chr(204),
                  chr(206),
                  chr(210),
                  chr(212),
                  chr(214),
                  chr(217),
                  chr(219),
                  chr(220),
                  chr(223),
                  chr(224),
                  chr(225),
                  chr(226),
                  chr(228),
                  chr(230),
                  chr(231),
                  chr(232),
                  chr(233),
                  chr(234),
                  chr(236),
                  chr(238),
                  chr(242),
                  chr(244),
                  chr(246),
                  chr(249),
                  chr(251),
                  chr(252)
                 );

        return preg_replace ($search, $replace, $text);
        */
    }

    public static function replaceNumbers ( $text )
    {
        $searchArray = array ( "0", "1", "2", "3", "4", "5", "6", "7", "8", "9" );
        $replaceArray = array ( "zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine" );
        return str_replace ( $searchArray, $replaceArray, $text );
    }

    /*
        replaces characters which come mostly from ms word
        by copy & paste
    */
    public static function replaceWordChars ($contents, $stripUnkowns = true)
    {

        $searchArray  = array ();
        $searchArray[0] = "&#8364;";
        $searchArray[1] = "&#8230;";
        $searchArray[2] = "&#8222;";
        $searchArray[3] = "&#8221;";
        $searchArray[4] = "&#8220;";
        $searchArray[5] = "&#8218;";
        $searchArray[6] = "&#8217;";
        $searchArray[7] = "&#8216;";
        $searchArray[8] = "&#8211;";

        $replaceArray = array ();
        $replaceArray[0] = "€";
        $replaceArray[1] = "... ";
        $replaceArray[2] = '"';
        $replaceArray[3] = '"';
        $replaceArray[4] = '"';
        $replaceArray[5] = '"';
        $replaceArray[6] = "'";
        $replaceArray[7] = "'";
        $replaceArray[8] = "-";

        $contents = str_replace ( $searchArray, $replaceArray, $contents );

        foreach ( array_keys ( $searchArray ) as $key )
        {
            $searchArray[$key] = substr ( $searchArray[$key], 1);
        }
        $contents = str_replace ( $searchArray, $replaceArray, $contents );

        if ($stripUnkowns)
        {
            $contents = preg_replace ( "'&#(\d+);'e", "chr(\\1)", $contents);
            $contents = preg_replace ( "'#(\d+);'e", "chr(\\1)", $contents);
        }

        return $contents;
    }

    /*
        strtolower() and strtoupper()
        don't work for "special characters"
        So use mb_strtoupper() or mb_strtolower instead - if installed or
        do it manually ...
    */
    public static function convertSpecialChars($text, $toLower = true)
    {

        if ( $toLower )
            return mb_strtolower ($text);
        else
            return mb_strtoupper ($text);
    }

    /*
        Strip out all urls
    */
    public static function stripUrl($text)
    {
        $text = preg_replace("/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i", "$1http://$2", $text);
        //make sure there is an http: on all URLs

        $text = preg_replace("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i",
                                    "", $text);
        return $text;
    }

    public static function passwordCharacterTable()
    {
        $table = array();
        for ( $i = ord( 'a' ); $i <= ord( 'z' ); ++$i )
        {
            $char = chr( $i );
            $table[] = $char;
            $table[] = strtoupper( $char );
        }
        for ( $i = 0; $i <= 9; ++$i )
        {
            $table[] = "$i";
        }
        //$table[] = "@";
        //$table[] = ".";
        // Remove some characters that are too similar visually
        $table = array_diff( $table, array( 'I', 'l', 'o', 'O', '0' ) );
        $tableTmp = $table;
        $table = array();
        foreach ( $tableTmp as $item )
        {
            $table[] = $item;
        }
        return $table;
    }

    public static function createPassword( $passwordLength )
    {
        $chars = 0;
        $password = '';
        if ( $passwordLength < 1 )
            $passwordLength = 1;
        $decimal = 0;

        $characterTable = self::passwordCharacterTable();
        $tableCount = count( $characterTable );

        for ($i = 0; $i < $passwordLength; $i++)
        {
            $password .= $characterTable [ array_rand ($characterTable,1) ];
        }

        return $password;
    }

}

?>