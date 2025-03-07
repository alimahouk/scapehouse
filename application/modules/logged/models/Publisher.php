<?php

class Logged_Model_Publisher {

    static function sanatizePost($text, $type=NULL) {

//        if($type == "text") {
//            $result = htmlentities($text);
//        }else{
            // decode unicode chars
//            $result = self::decode_unicode_url($text);
            // make urls into clickable links
                   
//        }
        
    require_once '../HTMLPurifier/library/HTMLPurifier.auto.php';
    
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Attr.AllowedFrameTargets', array("_blank"));
        $config->set('HTML.AllowedAttributes', array("href","target","class","title","src","alt","width","height","align"));
        
        $purifier = new HTMLPurifier($config);
        $result = $purifier->purify($text);
        
        $result = make_clickable($result);
    
       // Strip attributes
      // $striper = new Logged_Model_StripAttributes;
     //  $striper->exceptions = array('a' => array('href', 'target'), 'img' => array('class', 'title', 'src', 'alt','width','height','align'));
            
      // $result = $striper->strip($result);
            
        
//----- Replace unicode values DONT THROW AWAY THIS FUNCTION ITS USEFUL.
     /*   if ($result) {
            preg_match_all('/(?<=&#)\d*(?=;)/', $result, $unicodes);

            foreach ($unicodes[0] as $unicode) {
                $replace["&#$unicode;"] = Model_StaticFunctions::unicodeToUtf8($unicode);
            }
            if ($replace) {
                $result = str_replace(array_keys($replace), $replace, $result);
            }
        } */
//------

        if($type=="text") {
            return self::stripHtmlTags($result,"text");
        }elseif($type == "scape") {
            return self::stripHtmlTags($result,"scape");
        }elseif($type == "reply"){
            return self::stripHtmlTags($result,"reply");
        }elseif($type == "comment"){
           return self::stripHtmlTags($result,"comment"); 
        }

    }

    function trim_words($what, $words, $char_list = '') {
        if (!is_array($words))
            return false;
        $char_list .= " \t\n\r\0\x0B"; // default trim chars
        $pattern = "(" . implode("|", array_map(null, $words)) . ")+";
        $str = trim(preg_replace('~' . $pattern . '$~i', '', preg_replace('~^' . $pattern . '~i', '', trim($what, $char_list))), $char_list);
        return $str;
    }


    function stripHtmlTags($text, $type=NULL) {
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

        switch (true) {
            case($type == "comment"):
                return strip_tags($text, '<br><s><strong><em><u><a>');
                break;
            case($type == "scape"):
                return strip_tags($text, '<br><s><strong><em><u><sub><sup><ol><ul><li><a><img>');
                break;
            case($type == "reply"):
                return strip_tags($text, '<br><s><strong><em><u><sub><sup><ol><ul><li><a>');
                break;
            case($type == "text"):
                return strip_tags($text);
                break;
        }
    }

    static function addEmotions($text) {

        $text = str_replace(array("&nbsp;", "&lt;", "&gt;", "&amp;"), array("†nbsp‡", "†lt‡", "†gt‡", "†amp‡"), $text);

        $text = preg_replace(array(
                '/:p/i',
                '/:d/i',
                '/:\)/',
                '/:\(/',
                '/:\'\(|\B;\(/',
                '/†lt‡3|\(h\)/',
                '/:O/',
                '/\(a\)/',
                '/:\$/',
                '/\(cool\)/',
                '/\B;\)/',
                '/8\|/',
                '/:s/i',
                '/:\|/',
                '/\(xD\)/',
                '/\(xP\)/',
                '/\B;D/i',
                '/\B;p/i',
                '/:@/',
                '/\(fail\)/',
                '/\(banghead\)/',
                '/\(fml\)/',
                '/\†lt‡\/3/',
                '/\(zzz\)/',
                '/\(bb\)/',
                '/\(facebook\)/',
                '/\(msn\)/',
                '/\(youtube\)/',
                '/\(sh\)/',
                '/\(skype\)/',
                '/\(twitter\)/',
                '/\(mp3\)/'
                ),
                array('<span title=":P" class="emoticon tongue"></span>',
                '<span title=":D" class="emoticon laugh"></span>',
                '<span title=":)" class="emoticon smile"></span>',
                '<span title=":(" class="emoticon sad"></span>',
                '<span title=":\'(" class="emoticon cry"></span>',
                '<span title="&lt;3" class="emoticon heart"></span>',
                '<span title=":O" class="emoticon surprised"></span>',
                '<span title="(a)" class="emoticon angel"></span>',
                '<span title=":$" class="emoticon blush"></span>',
                '<span title="(cool)" class="emoticon cool"></span>',
                '<span title=";)" class="emoticon wink"></span>',
                '<span title="8|" class="emoticon nerdy"></span>',
                '<span title=":S" class="emoticon worried"></span>',
                '<span title=":|" class="emoticon speechless"></span>',
                '<span title="(xD)" class="emoticon hardLaugh"></span>',
                '<span title="(xP)" class="emoticon hardLaughTongue"></span>',
                '<span title=";D" class="emoticon winkLaugh"></span>',
                '<span title=";P" class="emoticon winkTongue"></span>',
                '<span title=":@" class="emoticon angry"></span>',
                '<span title="(fail)" class="emoticon fail"></span>',
                '<span title="(banghead)" class="emoticon headBang"></span>',
                '<span title="(fml)" class="emoticon fml"></span>',
                '<span title="&lt;/3" class="emoticon brokenHeart"></span>',
                '<span title="(zzz)" class="emoticon sleepy"></span>',
                '<span title="(bb)" class="emoticon blackberry"></span>',
                '<span title="(facebook)" class="emoticon facebook"></span>',
                '<span title="(msn)" class="emoticon msn"></span>',
                '<span title="(youtube)" class="emoticon youtube"></span>',
                '<span title="(sh)" class="emoticon sh"></span>',
                '<span title="(skype)" class="emoticon skype"></span>',
                '<span title="(twitter)" class="emoticon twitter"></span>',
                '<span title="(mp3)" class="emoticon mp3"></span>'), $text);

        $text = str_replace(array("†nbsp‡", "†lt‡", "†gt‡", "†amp‡"), array("&nbsp;", "&lt;", "&gt;", "&amp;"), $text);
        return $text;
    }

    function stripExtraSpace($s) {
        $newstr = "";

        for ($i = 0; $i < strlen($s); $i++) {
            $newstr = $newstr . substr($s, $i, 1);
            if (substr($s, $i, 1) == ' ')
                while (substr($s, $i + 1, 1) == ' ')
                    $i++;
        }

        return $newstr;
    }

    function decode_unicode_url($str) {
        $res = '';

        $i = 0;
        $max = strlen($str) - 6;
        while ($i <= $max) {
            $character = $str[$i];
            if ($character == '%' && $str[$i + 1] == 'u') {
                $value = hexdec(substr($str, $i + 2, 4));
                $i += 6;

                if ($value < 0x0080) // 1 byte: 0xxxxxxx
                    $character = chr($value);
                else if ($value < 0x0800) // 2 bytes: 110xxxxx 10xxxxxx
                    $character =
                            chr((($value & 0x07c0) >> 6) | 0xc0)
                            . chr(($value & 0x3f) | 0x80);
                else // 3 bytes: 1110xxxx 10xxxxxx 10xxxxxx
                    $character =
                            chr((($value & 0xf000) >> 12) | 0xe0)
                            . chr((($value & 0x0fc0) >> 6) | 0x80)
                            . chr(($value & 0x3f) | 0x80);
            }
            else
                $i++;

            $res .= $character;
        }

        return $res . substr($str, $i);
    }

}
