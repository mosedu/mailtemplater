<?php

$s = '<p> 2456 4 654 654 654 </p><p>Поля: *|email|*, *|fam|*, *|name|*, *|otch|*, *|org|*, *|fullname|*, *|usergroups|*</p><p><img alt="577b8667880ef.jpg" style="border: 1px solid #000000; margin: 8px;" src="http://mailtemplater.dev/images/577b8667880ef.jpg"></p><p>И тут еще немного:</p><p><img src="http://mailtemplater.dev/images/577bca7319a64.jpg"> <br></p><p><img src="http://mailtemplater.dev/images/577bca7f2a2cf.jpg"></p>';

$n = 0;
$sReg = '/<img[.]+src=([\'|"])([.]*)\1[^>]*>/im';
$sReg = '/<img.+?src=([\'|"])(.*?)\1[^>]*>/im';

$sReg = '/\\*\\|([^|]*?)\\|\\*/im';
// Yii::info('Start Text: ' . $template->mt_text);
if( preg_match($sReg, $s, $aMatch, PREG_OFFSET_CAPTURE, $n) ) {
    print_r($aMatch);
    while( preg_match($sReg, $s, $aMatch, PREG_OFFSET_CAPTURE, $n) ) {
        echo "field: {$aMatch[1][0]}\n";
        $n = $aMatch[0][1] + strlen($aMatch[0][0]);
    }
}
else {
    echo "Not found: {$sReg}\n";
}
