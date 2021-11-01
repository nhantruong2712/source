<?

class hash
{
    public static function encrypt($string, $key)
    {
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }

        return base64_encode($result);
    }

    public static function decrypt($string, $key)
    {
        $result = '';
        $string = base64_decode($string);

        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }

        return $result;
    }
    public static function convert($str, $ky = '')
    {
        if ($ky == '')
            return $str;
        $ky = str_replace(chr(32), '', $ky);
        if (strlen($ky) < 8)
            exit('key error');
        $kl = strlen($ky) < 32 ? strlen($ky) : 32;
        $k = array();
        for ($i = 0; $i < $kl; $i++) {
            $k[$i] = ord($ky{$i}) & 0x1F;
        }
        $j = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $e = ord($str{$i});
            $str{$i} = $e & 0xE0 ? chr($e ^ $k[$j]) : chr($e);
            $j++;
            $j = $j == $kl ? 0 : $j;
        }
        return $str;
    }

    public static function enc($str, $ky = '')
    {
        return base64_encode(self::convert($str, $ky));
    }

    public static function dec($str, $ky = '')
    {
        return self::convert(base64_decode($str), $ky);
    }

    public static function md5($str, $len = 10, $upper = true)
    {
        $ret = substr(md5($str), 0, $len);
        return $upper ? strtoupper($ret) : $ret;
    }

    public static function rc4($str, $key)
    {
        $s = array();
        for ($i = 0; $i < 256; $i++) {
            $s[$i] = $i;
        }
        $j = 0;
        for ($i = 0; $i < 256; $i++) {
            $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
        }
        $i = 0;
        $j = 0;
        $res = '';
        for ($y = 0; $y < strlen($str); $y++) {
            $i = ($i + 1) % 256;
            $j = ($j + $s[$i]) % 256;
            $x = $s[$i];
            $s[$i] = $s[$j];
            $s[$j] = $x;
            $res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
        }
        return $res;
    }

    public static function xorenc($str, $key)
    {
        $length = strlen($key);
        for ($i = 0; $i < strlen($str); $i++) {
            $pos = $i % $length;
            $r = ord($str[$i]) ^ ord($key[$pos]);
            $str[$i] = chr($r);
        }
        return $str;
    }

    public static function enc2($str, $ky = '')
    {
        return base64_encode(self::rc4($str, $ky));
    }

    public static function dec2($str, $ky = '')
    {
        return self::rc4(base64_decode($str), $ky);
    }

    public static function enc3($str, $ky = '')
    {
        $t = unpack('H*', self::rc4($str, $ky));
        return $t[1];
    }

    public static function dec3($str, $ky = '')
    {
        return self::rc4(pack('H*', $str), $ky);
    }

    public static function enc4($str, $ky = '')
    {
        $t = unpack('H*', self::convert($str, $ky));
        return $t[1];
    }

    public static function dec4($str, $ky = '')
    {
        return self::convert(pack('H*', $str), $ky);
    }
}
