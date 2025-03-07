<?php

// This is a class for all general purpose static functions.

class Model_StaticFunctions {

    // Returns are handled for the session content.
    static function sessionContent() {
        $sessionContent = Zend_Auth::getInstance()->getStorage()->read();
        return $sessionContent;
    }

    // Formats a Unix timestamp to a particular format according to the timezones, timezone offsets come from a cookie.
    static function formatDateFromTimestamp($datetime, $type = NULL) {

        $result = date("l, F d, Y \a\\t g:i a", strtotime($datetime) + ($_COOKIE['tz']) * 60 * 60);

        return $result;
    }
    
    static function salted_sha1($string){
        return sha1("#<95a[^>]*>#i'54327,'‡='#$^%&$%^&*%^&340920357".$string."@<he#<a[^32>]*>#i',547 '‡='##<95a^&*%^&3920357ad>@s40i40u");
    }

    // Strips all html tags, exeptions can be specified through the $exeptions argument.
    static function stripHtmlTags($text, $exceptions = NULL) {
        $text = preg_replace(
                        array(
                            // Remove invisible content
                            '@<head[^>]*?>.*?</head>@siu',
                            '@<style[^>]*?>.*?</style>@siu',
                            '@<script[^>]*?.*?</script>@siu',
                            '@<object[^>]*?.*?</object>@siu',
                            '@<embed[^>]*?.*?</embed>@siu',
                            '@<applet[^>]*?.*?</applet>@siu',
                            '@<noframes[^>]*?.*?</noframes>@siu',
                            '@<noscript[^>]*?.*?</noscript>@siu',
                            '@<noembed[^>]*?.*?</noembed>@siu',
                            // Add line breaks before and after blocks
                            '@</?((address)|(blockquote)|(center)|(del))@iu',
                            '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                            '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                            '@</?((table)|(th)|(td)|(caption))@iu',
                            '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                            '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                            '@</?((frameset)|(frame)|(iframe))@iu',
                        ),
                        array(
                            ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                            "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
                            "\n\$0", "\n\$0",
                        ),
                        $text);

        return strip_tags($text, $exceptions);
    }

// Trims all link and image tags, counts the text, trims it, puts the links and images back and returns the text.
// takes in 3 argument. 
// $text(string): The text that needs to be trimed
// $trimCount(int): The trimed length of the string in ints.
// $endString(string): the string that will go on to the end of the trimmed text.

    static function trimWithoutImgAndLinks($text, $trimCount, $endString) {

        preg_match_all('#<a[^>]*>#i', $text, $matchLink);

        $linkId = 0;

        foreach ($matchLink[0] as $link) {

            $text = preg_replace('#<a[^>]*>#i', '‡=' . $linkId, $text, 1);

            $linkId++;
        }
//        preg_match_all('#<img[^>]*>#i', $text, $matchImg);
//
//        $imgId = 0;
//
//        foreach ($matchImg[0] as $img) {
//
//            $text = preg_filter('#<img[^>]*>#i', '__i=' . $imgId, $text, 1);
//
//            $imgId++;
//        }
        // MID FUNCTIONS___start

        $text = self::subStrWordsUTF8($text, $trimCount - 10, $endString);

        //MID FUNCTIONS___end


        preg_match_all('/‡=\d/', $text, $linkIds);

        foreach ($linkIds[0] as $id => $link) {
            if (array_key_exists($id, $matchLink[0])) {

                $text = preg_replace("/‡=" . $id . "/", $matchLink[0][$id], $text, 1);
            }
        }

//        preg_match_all('/__i=\d/', $text, $imgIds);
//
//        foreach ($imgIds[0] as $id => $img) {
//            if (array_key_exists($id, $matchImg[0])) {
//
//                $text = preg_replace("/__i=" . $id . "/", $matchImg[0][$id], $text, 1);
//            }
//        }

        return $text;
    }

// takes in text, trims with respect to complete words. mb_strlen($latestScapeContent,'UTF8')

    static function subStrWords($text, $maxchar, $end='') {
        if (strlen($text) > $maxchar) {
            $words = explode(" ", $text);
            $output = '';
            $i = 0;
            while (1) {
                $length = (strlen($output) + strlen($words[$i]));
                if ($length > $maxchar) {
                    break;
                } else {
                    $output = $output . " " . $words[$i];
                    ++$i;
                };
            };
        } else {
            $output = $text;
        }
        return $output . $end;
    }

    static function stripExtraSpace($s) {
        $newstr = "";

        for ($i = 0; $i < strlen($s); $i++) {
            $newstr = $newstr . substr($s, $i, 1);
            if (substr($s, $i, 1) == ' ')
                while (substr($s, $i + 1, 1) == ' ')
                    $i++;
        }

        return $newstr;
    }

    static function search($array, $key, $value) {
        $results = array();

        if (is_array($array)) {
            if ($array[$key] == $value)
                $results[] = $array;

            foreach ($array as $subarray)
                $results = array_merge($results, self::search($subarray, $key, $value));
        }

        return $results;
    }

    static function subStrWordsUTF8($text, $maxchar, $end='') {
        if (mb_strlen($text,'UTF8') > $maxchar) {
            $words = explode(" ", $text);
            $output = '';
            $i = 0;
            while (1) {
                $length = (mb_strlen($output,'UTF8') + mb_strlen($words[$i],'UTF8'));
                if ($length > $maxchar) {
                    break;
                } else {
                    $output = $output . " " . $words[$i];
                    ++$i;
                };
            };
        } else {
            $output = $text;
        }
        return $output . $end;
    }


    // UTF to unicode----
    static function UTF_to_Unicode($input, $array=False) {

        $bit1 = pow(64, 0);
        $bit2 = pow(64, 1);
        $bit3 = pow(64, 2);
        $bit4 = pow(64, 3);
        $bit5 = pow(64, 4);
        $bit6 = pow(64, 5);

        $value = '';
        $val = array();

        for ($i = 0; $i < strlen($input); $i++) {

            $ints = ord($input[$i]);

            $z = ord($input[$i]);
            $y = ord($input[$i + 1]) - 128;
            $x = ord($input[$i + 2]) - 128;
            $w = ord($input[$i + 3]) - 128;
            $v = ord($input[$i + 4]) - 128;
            $u = ord($input[$i + 5]) - 128;

            if ($ints >= 0 && $ints <= 127) {
                // 1 bit
                $value .= $input[$i];
                $val[] = $value;
            }
            if ($ints >= 192 && $ints <= 223) {
                // 2 bit
                $value .= '&#' . (($z - 192) * $bit2 + $y * $bit1) . ';';
                $val[] = $value;
            }
            if ($ints >= 224 && $ints <= 239) {
                // 3 bit
                $value .= '&#' . (($z - 224) * $bit3 + $y * $bit2 + $x * $bit1) . ';';
                $val[] = $value;
            }
            if ($ints >= 240 && $ints <= 247) {
                // 4 bit
                $value .= '&#' . (($z - 240) * $bit4 + $y * $bit3 +
                        $x * $bit2 + $w * $bit1) . ';';
                $val[] = $value;
            }
            if ($ints >= 248 && $ints <= 251) {
                // 5 bit
                $value .= '&#' . (($z - 248) * $bit5 + $y * $bit4
                        + $x * $bit3 + $w * $bit2 + $v * $bit1) . ';';
                $val[] = $value;
            }
            if ($ints == 252 && $ints == 253) {
                // 6 bit
                $value .= '&#' . (($z - 252) * $bit6 + $y * $bit5
                        + $x * $bit4 + $w * $bit3 + $v * $bit2 + $u * $bit1) . ';';
                $val[] = $value;
            }
            if ($ints == 254 || $ints == 255) {
                echo 'Wrong Result!<br>';
            }
        }

        if ($array === False) {
            return $unicode = $value;
        }
    }
    // unicode to UTF----
    static function Unicode_to_UTF($input, $array=TRUE) {

        $utf = '';
        if (!is_array($input)) {
            $input = str_replace('&#', '', $input);
            $input = explode(';', $input);
            $len = count($input);
            unset($input[$len - 1]);
        }
        for ($i = 0; $i < count($input); $i++) {

            if ($input[$i] < 128) {
                $byte1 = $input[$i];
                $utf .= $byte1;
            }
            if ($input[$i] >= 128 && $input[$i] <= 2047) {

                $byte1 = 192 + (int) ($input[$i] / 64);
                $byte2 = 128 + ($input[$i] % 64);
                $utf .= chr($byte1) . chr($byte2);
            }
            if ($input[$i] >= 2048 && $input[$i] <= 65535) {

                $byte1 = 224 + (int) ($input[$i] / 4096);
                $byte2 = 128 + ((int) ($input[$i] / 64) % 64);
                $byte3 = 128 + ($input[$i] % 64);

                $utf .= chr($byte1) . chr($byte2) . chr($byte3);
            }
            if ($input[$i] >= 65536 && $input[$i] <= 2097151) {

                $byte1 = 240 + (int) ($input[$i] / 262144);
                $byte2 = 128 + ((int) ($input[$i] / 4096) % 64);
                $byte3 = 128 + ((int) ($input[$i] / 64) % 64);
                $byte4 = 128 + ($input[$i] % 64);
                $utf .= chr($byte1) . chr($byte2) . chr($byte3) .
                        chr($byte4);
            }
            if ($input[$i] >= 2097152 && $input[$i] <= 67108863) {

                $byte1 = 248 + (int) ($input[$i] / 16777216);
                $byte2 = 128 + ((int) ($input[$i] / 262144) % 64);
                $byte3 = 128 + ((int) ($input[$i] / 4096) % 64);
                $byte4 = 128 + ((int) ($input[$i] / 64) % 64);
                $byte5 = 128 + ($input[$i] % 64);
                $utf .= chr($byte1) . chr($byte2) . chr($byte3) .
                        chr($byte4) . chr($byte5);
            }
            if ($input[$i] >= 67108864 && $input[$i] <= 2147483647) {

                $byte1 = 252 + ($input[$i] / 1073741824);
                $byte2 = 128 + (($input[$i] / 16777216) % 64);
                $byte3 = 128 + (($input[$i] / 262144) % 64);
                $byte4 = 128 + (($input[$i] / 4096) % 64);
                $byte5 = 128 + (($input[$i] / 64) % 64);
                $byte6 = 128 + ($input[$i] % 64);
                $utf .= chr($byte1) . chr($byte2) . chr($byte3) .
                        chr($byte4) . chr($byte5) . chr($byte6);
            }
        }
        return $utf;
    }

    // unicode to UTF (perchar)----
    static function unicodeToUtf8(&$src) {
        $dest = '';
//  foreach ($arr as $src) {
        if ($src < 0) {
            return false;
        } else if (strlen($src) === 1) {
            $dest .= $src;
        } else if ($src <= 0x007f) {
            $dest .= chr($src);
        } else if ($src <= 0x07ff) {
            $dest .= chr(0xc0 | ($src >> 6));
            $dest .= chr(0x80 | ($src & 0x003f));
        } else if ($src == 0xFEFF) {
            // nop -- zap the BOM
        } else if ($src >= 0xD800 && $src <= 0xDFFF) {
            // found a surrogate
            return false;
        } else if ($src <= 0xffff) {
            $dest .= chr(0xe0 | ($src >> 12));
            $dest .= chr(0x80 | (($src >> 6) & 0x003f));
            $dest .= chr(0x80 | ($src & 0x003f));
        } else if ($src <= 0x10ffff) {
            $dest .= chr(0xf0 | ($src >> 18));
            $dest .= chr(0x80 | (($src >> 12) & 0x3f));
            $dest .= chr(0x80 | (($src >> 6) & 0x3f));
            $dest .= chr(0x80 | ($src & 0x3f));
        } else {
            // out of range
            return false;
        }
//  }
        return $dest;
    }
 // Relative time ---- user friendly time

    static function relativeTime($time) {

// this function will calculate a friendly date difference string
// based upon $time and how it compares to the current time
// for example it will return "1 minute ago" if the difference
// in seconds is between 60 and 120 seconds
// $time is a GM-based Unix timestamp, this makes for a timezone
// neutral comparison
    define(MINUTE, 60);
    define(HOUR, 60 * 60);
    define(DAY, 60 * 60 * 24);
    define(MONTH, 60 * 60 * 24 * 30);

    $delta = strtotime(gmdate("Y-m-d H:i:s", time())) - $time;

    if ($delta < 1 * MINUTE) {
        return $delta <= 1 ? "a moment ago" : $delta . " seconds ago";
    }
    if ($delta < 2 * MINUTE) {
        return "1 minute ago";
    }
    if ($delta < 45 * MINUTE) {
        return floor($delta / MINUTE) . " minutes ago";
    }
    if ($delta < 90 * MINUTE) {
        return "an hour ago";
    }
    if ($delta < 24 * HOUR) {
        return ceil($delta / HOUR) . " hours ago";
    }
    if ($delta < 48 * HOUR) {
        return "yesterday";
    }
    if ($delta < 30 * DAY) {
        return floor($delta / DAY) . " days ago";
    }
    if ($delta < 12 * MONTH) {
        $months = floor($delta / DAY / 30);
        return $months <= 1 ? "one month ago" : $months . " months ago";
    } else {
        $years = floor($delta / DAY / 365);
        return $years <= 1 ? "one year ago" : $years . " years ago";
    }
}

}
?>
