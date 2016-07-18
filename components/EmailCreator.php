<?php
/**
 * Created by PhpStorm.
 * User: KozminVA
 * Date: 18.07.2016
 * Time: 15:09
 */

namespace app\components;

use yii;

class EmailCreator {

    const IMAGE_GEG_EXP = '/<img.+?src=([\'|"])(.*?)\1[^>]*>/im'; // регэксп для плучения src картинок
    const LINK_GEG_EXP = '/<a.+?href=([\'|"])(.*?)\1[^>]*>([^<]*?)<\\/a>/im'; // регэксп для плучения href ссылок

    /**
     * @var string текст письма
     */
    public $_sLetter = '';

    /**
     * @var bool текст письма - в html
     */
    public $_isHtml = false;

    /** @var null webroot для проектаЮ даже если запускается из командной строки  */
    public $_sDocRoot = null;

    /**
     * @param string $sLetter
     */
    public function __construct($sLetter = '') {
        $this->setDocRoot();

        $this->setText($sLetter);
    }

    /**
     *
     * Проверка - текст в html ?
     *
     * @param bool $bRefresh обновить ли значение в объекте по
     * @return bool
     */
    public function isHtml($bRefresh = false) {
        $bReturn = $this->_isHtml;
        if( $bRefresh ) {
            $bReturn = false;
            $sPlane = strip_tags($this->_sLetter);
            if( strlen($sPlane) < strlen($this->_sLetter) ) {
                $bReturn = true;
            }
        }
        return $bReturn;
    }

    /**
     *
     * Установка нового текста
     *
     * @param string $sLetter
     */
    public function setText($sLetter = '') {
        $this->_sLetter = $sLetter;
        $this->_isHtml = $this->isHtml(true);
    }

    /**
     *
     * Получение текстов для письма
     *
     * @return array
     */
    public function getText() {
        $sHtml = $this->replaceLinksText($this->_sLetter);
        $sHtml = $this->isHtml() ? $sHtml : htmlspecialchars($sHtml);
        return [
            'html' => $sHtml,
            'plain' => $this->preparePlainText($sHtml),
        ];
    }

    /**
     *
     * Получение картинок из письма
     *
     * @return array
     */
    public function getImages() {
        $aImages = [];
        $sText = $this->_sLetter;

        if( $this->isHtml() ) {
            $sReg = self::IMAGE_GEG_EXP;
            $n = 0;
            while( preg_match($sReg, $sText, $aMatch, PREG_OFFSET_CAPTURE, $n) ) {
//            Yii::info('Text ' . $sText);
//            Yii::info('Match ' . print_r($aMatch, true));
                $sSrc = $aMatch[2][0];
                $sImg = $this->prepareImgName($sSrc);
                $sFileId = '';

                if( file_exists($sImg) && is_file($sImg) ) {
                    Yii::info('sImg = ' . $sImg . ' sFileId =  ' . $sFileId);
                    $aImages[$sSrc] = $sImg;
                }
                else {
                    Yii::info('Image Error: ' . $sImg . ' is ' . (file_exists($sImg) ? '' : ' not exists') . (is_file($sImg) ? '' : ' not a regular file'));
                    $aImages[$sSrc] = '';
                }

                $sText = substr($sText, 0, $aMatch[2][1])
                    . $sFileId
                    . substr($sText, $aMatch[2][1] + strlen($aMatch[2][0]));

                $n = $aMatch[2][1] + strlen($sFileId);
            }
        }
        return $aImages;
    }

    /**
     *
     * Замена src картинок в письме на другие
     *
     * @param array $aImages массив, где ключ - оригинальный src картинки, а значение - id вставленного в письмо файла
     */
    public function replaceImages($aImages = []) {
        $sText = $this->_sLetter;

        if( $this->isHtml() ) {
            $sReg = self::IMAGE_GEG_EXP;
            $n = 0;
            while( preg_match($sReg, $sText, $aMatch, PREG_OFFSET_CAPTURE, $n) ) {
                $sSrc = $aMatch[2][0];
                $sFileId = isset($aImages[$sSrc]) ? $aImages[$sSrc] : '';

                $sText = substr($sText, 0, $aMatch[2][1])
                    . $sFileId
                    . substr($sText, $aMatch[2][1] + strlen($aMatch[2][0]));

                $n = $aMatch[2][1] + strlen($sFileId);
            }
        }

        $this->_sLetter = $sText;
    }

    /**
     *
     * Получение ссылок из письма
     *
     * @return array
     */
    public function getLinks() {
        $aLinks = [];
        $sText = $this->_sLetter;

        if( $this->isHtml() ) {
            $sReg = self::LINK_GEG_EXP;
            $n = 0;
            while( preg_match($sReg, $sText, $aMatch, PREG_OFFSET_CAPTURE, $n) ) {
//            Yii::info('Text ' . $sText);
//            Yii::info('Match ' . print_r($aMatch, true));

                $sSrc = $aMatch[2][0];
                $aLinks[$sSrc] = $sSrc;

                $n = $aMatch[2][1] + strlen($sSrc);
            }

        }
        return $aLinks;
    }

    /**
     *
     * Замена ссылок в письме
     *
     * @param array $aLinks массив, где ключ - оригинальный href ссылки, а значение - новое значение href
     *
     */
    public function replaceLinks($aLinks = []) {
        $sText = $this->_sLetter;

        if( $this->isHtml() ) {
            $sReg = self::LINK_GEG_EXP;
            $n = 0;
            while( preg_match($sReg, $sText, $aMatch, PREG_OFFSET_CAPTURE, $n) ) {
//            Yii::info('Text ' . $sText);
//            Yii::info('Match ' . print_r($aMatch, true));
                $sSrc = $aMatch[2][0];
                $sNewLink = isset($aLinks[$sSrc]) ? $aLinks[$sSrc] : $sSrc;

                $sText = substr($sText, 0, $aMatch[2][1])
                    . $sNewLink
                    . substr($sText, $aMatch[2][1] + strlen($aMatch[2][0]));

                $n = $aMatch[2][1] + strlen($sNewLink);
            }

        }
        $this->_sLetter = $sText;
    }

    /**
     *
     * Замена текста ссылок в письме, чтобы в тексте ссылки присутствовал адрес перехода
     *
     * @return string
     *
     */
    public function replaceLinksText($sText = '') {
        if( $this->isHtml() ) {
            $sReg = self::LINK_GEG_EXP;
            $n = 0;
            while( preg_match($sReg, $sText, $aMatch, PREG_OFFSET_CAPTURE, $n) ) {
                $sSrc = $aMatch[2][0];
                $sLinkText = $aMatch[3][0];

                if( strpos($sLinkText, $sSrc) === false ) {
                    $sLinkText = $sLinkText . ' (' . $sSrc . ')';
                }

                Yii::info('replaceLinksText(): ' . $sSrc . ' -> ' . $sLinkText);

                $sText = substr($sText, 0, $aMatch[3][1])
                    . $sLinkText
                    . substr($sText, $aMatch[3][1] + strlen($aMatch[3][0]));

                $n = $aMatch[3][1] + strlen($sLinkText);
            }
        }
        return $sText;
    }



    /**
     *
     * Кусок из файла vendor\yiisoft\yii2\mail\BaseMailer.php
     *
     * @param string $sHtml
     * @return mixed|string
     */
    public function preparePlainText($sHtml = null) {
        if( $sHtml === null ) {
            $sHtml = $this->_sLetter;
        }

        $bHtml = (strlen($sHtml) !== strlen(strip_tags($sHtml)));

        if( $bHtml ) {
            if( preg_match('~<body[^>]*>(.*?)</body>~is', $sHtml, $match) ) {
                $sHtml = $match[1];
            }

            // remove style and script
            $html = preg_replace('~<((style|script))[^>]*>(.*?)</\1>~is', '', $sHtml);

            $html = $this->replaceLinksText($html);

            // strip all HTML tags and decoded HTML entities
            $text = strip_tags($html);
            $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, Yii::$app ? Yii::$app->charset : 'UTF-8');
        }
        else {
            $text = $sHtml;
        }

//        // improve whitespace
        $text = preg_replace("~^[ \t]+~m", '', trim($text));
        $text = preg_replace('~\R\R+~mu', "\n\n", $text);
        $text = preg_replace('/^\h*\v+/m', '', $text);
        return $text;
    }


    /**
     *
     */
    public function setDocRoot() {
        if( $this->_sDocRoot === null ) {
            $this->_sDocRoot = Yii::getAlias('@webroot', false);
            if( $this->_sDocRoot === false ) {
                $this->_sDocRoot = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'web';
            }
        }
    }

    /**
     * @param string $sImg
     * @return mixed|string
     */
    public function prepareImgName($sImg = '') {
        if( strtolower(substr($sImg, 0, 4)) == 'http' ) {
            $sImg = substr($sImg, strpos($sImg, '/', 7));
            $sImg = str_replace('/', DIRECTORY_SEPARATOR, $sImg);
        }

        $sImg = $this->_sDocRoot . $sImg;

        return $sImg;
    }

}