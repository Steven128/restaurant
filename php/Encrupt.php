<?php

class Encrupt
{
    public function escape($string, $in_encoding = 'UTF-8', $out_encoding = 'UCS-2')
    {
        $return = '';
        if (function_exists('mb_get_info')) {
            for ($x = 0; $x < mb_strlen($string, $in_encoding); $x++) {
                $str = mb_substr($string, $x, 1, $in_encoding);
                if (strlen($str) > 1) { // 多字节字符
                    $return .= '%u' . strtoupper(bin2hex(mb_convert_encoding($str, $out_encoding, $in_encoding)));
                } else {
                    $return .= '%' . strtoupper(bin2hex($str));
                }
            }
        }
        return $return;
    }

    public function unescape($str)
    {
        $ret = '';
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            if ($str[$i] == '%' && $str[$i + 1] == 'u') {
                $val = hexdec(substr($str, $i + 2, 4));
                if ($val < 0x7f) {
                    $ret .= chr($val);
                } else
                if ($val < 0x800) {
                    $ret .= chr(0xc0 | ($val >> 6)) .
                    chr(0x80 | ($val & 0x3f));
                } else {
                    $ret .= chr(0xe0 | ($val >> 12)) .
                    chr(0x80 | (($val >> 6) & 0x3f)) .
                    chr(0x80 | ($val & 0x3f));
                }

                $i += 5;
            } else
            if ($str[$i] == '%') {
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            } else {
                $ret .= $str[$i];
            }

        }
        return $ret;
    }
    public function charCodeAt($str, $index)
    {
        $char = mb_substr($str, $index, 1, 'UTF-8');

        if (mb_check_encoding($char, 'UTF-8')) {
            $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
            return hexdec(bin2hex($ret));
        } else {
            return null;
        }
    }
    public function charAt($str, $pos)
    {
        return (mb_substr($str, $pos, 1) !== false) ? mb_substr($str, $pos, 1) : "";
    }
    public function encrupt($str, $pwd)
    {
        if ($str == "") {
            return "";
        }
        $str = escape($str);
        if (!$pwd || $pwd == "") {$pwd = "restaurant";}
        $pwd = escape($pwd);
        if ($pwd == null || $pwd . length <= 0) {
            return null;
        }
        $prand = "";
        for ($I = 0; $I < mb_strlen($pwd); $I++) {
            $prand += strval(charCodeAt($pwd, $I));
        }
        $sPos = floor(mb_strlen($prand) / 5);
        $mult = intval(charAt($prand, $sPos) + charAt($prand, $sPos * 2) + charAt($prand, $sPos * 3) + charAt($prand, $sPos * 4) + charAt($prand, $sPos * 5));
        $incr = ceil(mb_strlen($pwd) / 2);
        $modu = pow(2, 31) - 1;
        if ($mult < 2) {
            return null;
        }
        $salt = round(rand(100000000, 999999999) * 1000000000) % 100000000;
        $prand += $salt;
        while (mb_strlen($prand) > 10) {
            $prand = strval(intval(substr($prand, 0, 10)) + intval(substr($prand, 10, mb_strlen($prand))));
        }
        $prand = ($mult * $prand + $incr) % $modu;
        $enc_chr = "";
        $enc_str = "";
        for ($I = 0; $I < mb_strlen($str); $I++) {
            $enc_chr = intval(charCodeAt($str,$I) ^ floor(($prand / $modu) * 255));
            if ($enc_chr < 16) {
                $enc_str += "0" + $enc_chr.toString(16);
            } else
                $enc_str += $enc_chr.toString(16);
            $prand = ($mult * $prand + $incr) % $modu;
        }
    }
}
