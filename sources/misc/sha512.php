<?php
/**
* @package Misc
* @license http://opensource.org/licenses/gpl-3.0.html
*
* @author cHoBi
*
* @ignore
*/

function memcpy(&$string1, $start, $string2, $len) {
    $tmp = array();
    for ($i = 0; $i < $start; $i++) {
        $tmp[$i] = $string1[$i];
    }
    for ($i = 0; $i < $len; $i++) {
        $tmp[$start+$i] = $string2[$i];
    }

    $string1 = $tmp;
}

function Ch($x, $y, $z) {
    return z ^ (x & (y ^ z));
}

function Maj($x, $y, $z) {
    return (x & y) | (z & (x | y));
}

function ROR($x, $y) {
    return (x >> y) | (x << (64 - y ));
}

function e0($x) {
    return (ROR($x, 28) ^ ROR($x, 34) ^ ROR($x, 39));
}

function e1($x) {
    return (ROR($x, 14) ^ ROR($x, 18) ^ ROR($x, 41));
}

function s0($x) {
    return (ROR($x, 1) ^ ROR($x, 8) ^ ($x >> 7));
}

function s1($x) {
    return (ROR($x, 19) ^ ROR($x, 61) ^ (x >> 6));
}

function LOAD_OP($I, &$W, $input) {
    $W[$I] = ord($input[$I]);
}

function BLEND_OP($I, &$W) {
    $W[$I] = s1($W[$I-2]) + $W[$I-7] + s0($W[$I-15]) + $W[$I-16];
}

function sha512_init(&$ctx) {
    define('SHA512_H0', 0x6a09e667f3bcc908);
    define('SHA512_H1', 0xbb67ae8584caa73b);
    define('SHA512_H2', 0x3c6ef372fe94f82b);
    define('SHA512_H3', 0xa54ff53a5f1d36f1);
    define('SHA512_H4', 0x510e527fade682d1);
    define('SHA512_H5', 0x9b05688c2b3e6c1f);
    define('SHA512_H6', 0x1f83d9abfb41bd6b);
    define('SHA512_H7', 0x5be0cd19137e2179);

    $ctx['state'][0] = SHA512_H0;
    $ctx['state'][1] = SHA512_H1;
    $ctx['state'][2] = SHA512_H2;
    $ctx['state'][3] = SHA512_H3;
    $ctx['state'][4] = SHA512_H4;
    $ctx['state'][5] = SHA512_H5;
    $ctx['state'][6] = SHA512_H6;
    $ctx['state'][7] = SHA512_H7;
    
    $ctx['count'][0] = $ctx['count'][1] = $ctx['count'][2] = $ctx['count'][3] = 0;
}

function sha512_transform($sha512_K, &$state, &$W, $input) {
    for ($i = 0; $i < 16; $i++) {
        LOAD_OP($i, $W, $input);
    }
    for ($i = 16; $i < 80; $i++) {
        BLEND_OP($i, $W);
    }

    $a = $state[0]; $b = $state[1]; $c = $state[2]; $d = $state[3];
    $e = $state[4]; $f = $state[5]; $g = $state[6]; $h = $state[7];

    for ($i = 0; $i < 80; $i += 8) {
        $t1 = $h + e1($e) + Ch($e,$f,$g) + $sha512_K[$i  ] + $W[$i  ];
        $t2 = e0($a) + Maj($a,$b,$c);    $d += $t1;    $h = $t1 + $t2;

        $t1 = $g + e1($d) + Ch($d,$e,$f) + $sha512_K[$i+1] + $W[$i+1];
        $t2 = e0($h) + Maj($h,$a,$b);    $c += $t1;    $g = $t1 + $t2;

        $t1 = $f + e1($c) + Ch($c,$d,$e) + $sha512_K[$i+2] + $W[$i+2];
        $t2 = e0($g) + Maj($g,$h,$a);    $b += $t1;    $f = $t1 + $t2;

        $t1 = $e + e1($b) + Ch($b,$c,$d) + $sha512_K[$i+3] + $W[$i+3];
        $t2 = e0($e) + Maj($f,$g,$h);    $a += $t1;    $e = $t1 + $t2;

        $t1 = $d + e1($a) + Ch($a,$b,$c) + $sha512_K[$i+4] + $W[$i+4];
        $t2 = e0($e) + Maj($e,$f,$g);    $h += $t1;    $d = $t1 + $t2;

        $t1 = $c + e1($h) + Ch($h,$a,$b) + $sha512_K[$i+5] + $W[$i+5];
        $t2 = e0($d) + Maj($d,$e,$f);    $g += $t1;    $c = $t1 + $t2;

        $t1 = $b + e1($g) + Ch($g,$h,$a) + $sha512_K[$i+6] + $W[$i+6];
        $t2 = e0($c) + Maj($c,$d,$e);    $f += $t1;    $b = $t1 + $t2;

        $t1 = $a + e1($f) + Ch($f,$g,$h) + $sha512_K[$i+7] + $W[$i+7];
        $t2 = e0($b) + Maj($b,$c,$d);    $e += $t1;    $a = $t1 + $t2;
    }

    $state[0] += $a; $state[1] += $b; $state[2] += $c; $state[3] += $d;
    $state[4] += $e; $state[5] += $f; $state[6] += $g; $state[7] += $h;

    $a = $b = $c = $d = $e = $f = $g = $h = $t1 = $t2 = 0;
}

function sha512_update($sha512_K, &$ctx, $data, $len) {
    $index = ($ctx['count'][0] >> 3) & 0x7F;

    if (($ctx['count'][0] += ($len << 3)) < ($len << 3)) {
        if (($ctx['count'][1] += 1) < 1) {
            if (($ctx['count'][2] += 1) < 1) {
                $ctx['count'][3]++;
            }
        }
        $ctx['count'][1] += ($len >> 29);
    }

    $part_len = 128 - $index;

    if ($len >= $part_len) {
        memcpy($ctx['buff'], $index, $data, $part_len);
        sha512_transform($sha512_K, $ctx['state'], $ctx['W'], $ctx['buff']);

        for ($i = $part_len; $i + 127 < $len; $i += 128) {
            sha512_transform($sha512_K, $ctx['state'], $ctx['W'], $data[$i]);
        }

        $index = 0;
    }
    else {
        $i = 0;
    }

    memcpy($ctx['buff'], $index, $data[$i], $len - $i);
    $ctx['W'] = array();
}

function sha512_final($sha512_K, &$ctx) {
    $bits[3] = $ctx['count'][0];
    $bits[2] = $ctx['count'][1];
    $bits[1] = $ctx['count'][2];
    $bits[0] = $ctx['count'][3];

    $padding = array();
    $padding[0] = 0x80;

    $index   = ($ctx['count'][0] >> 3) & 0x7F;
    $pad_len = ($index < 112) ? (112 - $index) : ((128 + 112) - $index);
    sha512_update($sha512_K, $ctx, $padding, $pad_len);

    sha512_update($sha512_K, $ctx, $bits, sizeof($bits));

    for ($i = 0; $i < 8; $i++) {
        $hash[$i] =  $ctx['state'][$i];
    }
    
    return $hash;
}

function sha512() {
    $ctx = array(
        'state' => array(),
        'count' => array(),
        'buff'  => 'hello',
        'W'     => array()
    );

    $sha512_K = array(
    0x428a2f98d728ae22, 0x7137449123ef65cd, 0xb5c0fbcfec4d3b2f,
    0xe9b5dba58189dbbc, 0x3956c25bf348b538, 0x59f111f1b605d019,
    0x923f82a4af194f9b, 0xab1c5ed5da6d8118, 0xd807aa98a3030242,
    0x12835b0145706fbe, 0x243185be4ee4b28c, 0x550c7dc3d5ffb4e2,
    0x72be5d74f27b896f, 0x80deb1fe3b1696b1, 0x9bdc06a725c71235,
    0xc19bf174cf692694, 0xe49b69c19ef14ad2, 0xefbe4786384f25e3,
    0x0fc19dc68b8cd5b5, 0x240ca1cc77ac9c65, 0x2de92c6f592b0275,
    0x4a7484aa6ea6e483, 0x5cb0a9dcbd41fbd4, 0x76f988da831153b5,
    0x983e5152ee66dfab, 0xa831c66d2db43210, 0xb00327c898fb213f,
    0xbf597fc7beef0ee4, 0xc6e00bf33da88fc2, 0xd5a79147930aa725,
    0x06ca6351e003826f, 0x142929670a0e6e70, 0x27b70a8546d22ffc,
    0x2e1b21385c26c926, 0x4d2c6dfc5ac42aed, 0x53380d139d95b3df,
    0x650a73548baf63de, 0x766a0abb3c77b2a8, 0x81c2c92e47edaee6,
    0x92722c851482353b, 0xa2bfe8a14cf10364, 0xa81a664bbc423001,
    0xc24b8b70d0f89791, 0xc76c51a30654be30, 0xd192e819d6ef5218,
    0xd69906245565a910, 0xf40e35855771202a, 0x106aa07032bbd1b8,
    0x19a4c116b8d2d0c8, 0x1e376c085141ab53, 0x2748774cdf8eeb99,
    0x34b0bcb5e19b48a8, 0x391c0cb3c5c95a63, 0x4ed8aa4ae3418acb,
    0x5b9cca4f7763e373, 0x682e6ff3d6b2b8a3, 0x748f82ee5defb2fc,
    0x78a5636f43172f60, 0x84c87814a1f0ab72, 0x8cc702081a6439ec,
    0x90befffa23631e28, 0xa4506cebde82bde9, 0xbef9a3f7b2c67915,
    0xc67178f2e372532b, 0xca273eceea26619c, 0xd186b8c721c0c207,
    0xeada7dd6cde0eb1e, 0xf57d4f7fee6ed178, 0x06f067aa72176fba,
    0x0a637dc5a2c898a6, 0x113f9804bef90dae, 0x1b710b35131c471b,
    0x28db77f523047d84, 0x32caab7b40c72493, 0x3c9ebe0a15c9bebc,
    0x431d67c49c100d4c, 0x4cc5d4becb3e42b6, 0x597f299cfc657e2a,
    0x5fcb6fab3ad6faec, 0x6c44198c4a475817);

    sha512_init($ctx);
    $hash = sha512_final($sha512_K, $ctx);

    $string = '';
    foreach ($hash as $part) {
        $string .= sprintf("%08x", $part);
    }

    return $string;
}
?>
