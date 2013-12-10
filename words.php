<?php
define( "UTF8_CHINESE_PATTERN", "/[\x{4e00}-\x{9fff}\x{f900}-\x{faff}]/u" );
define( "UTF8_SYMBOL_PATTERN", "/[\x{ff00}-\x{ffef}\x{2000}-\x{206F}]/u" );


// count only chinese words
function str_utf8_chinese_word_count($str = ""){
    $str = preg_replace(UTF8_SYMBOL_PATTERN, "", $str);
    return preg_match_all(UTF8_CHINESE_PATTERN, $str, $textrr);
}
// count both chinese and english
function str_utf8_mix_word_count($str = ""){
    $str = preg_replace(UTF8_SYMBOL_PATTERN, "", $str);
    return preg_match_all(UTF8_CHINESE_PATTERN, $str) + str_word_count(preg_replace(UTF8_CHINESE_PATTERN, "", $str));
}

// convert a string to hex-coding form
function binhex($str) {
    $hex = "";
    $i = 0;
    do {
        $hex .= sprintf("%02x", ord($str{$i}));
        $i++;
    } while ($i < strlen($str));
    return $hex;
}

echo str_utf8_mix_word_count("go tex，‘, 账号飞撒");exit;
$text = $_REQUEST["text"] ? $_REQUEST["text"] : "";
echo "Text: " . $text . "<br />";
echo "Hex : " . ($text ? binhex($text) : "") . "<br />";
// use one of the following two lines according to the page encoding
echo "Word count: " . str_utf8_mix_word_count($text);
?>

<form action="words.php">
<input type="text" name="text" id="text" value="<?=$text?>"/>
<input type="submit" />
</form>
菜老板
那月薪方面没问题咯？14k？