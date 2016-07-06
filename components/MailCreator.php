<?php

namespace app\components;

use yii;
use app\models\Mailtempl;
use app\models\TemplatesendForm;
use app\models\Listelement;

use \Swift_Message;
use \Swift_EmbeddedFile;
use yii\swiftmailer\Message;

class MailCreator {

    public static $_sDocRoot = null;

    public static $_fromEmail = 'eventreg@educom.ru';
    /**
     * @param Mailtempl $template
     * @param TemplatesendForm $act
     * @param Listelement $subscriber
     */
    public static function createMail($template, $act, $subscriber) {

        self::setDocRoot();

        $oMessage = Yii::$app->mailer->compose(null, []);

        /** @var Swift_Message $oSwiftMessage */
        $oSwiftMessage = $oMessage->getSwiftMessage();
        $sText = $template->mt_text;

        Yii::info('Initial: ' . $sText);

        $sText = self::prepareBodyFiles($oMessage, $sText);
        Yii::info('After files: ' . $sText);

        $sText = self::prepareUserFields($sText, $subscriber, true);
        Yii::info('After user fields: ' . $sText);

        $sFrom = $subscriber->getFullname();
        if( $sFrom == '' ) {
            $sFrom = trim($subscriber->le_org);
        }

        /*
         * кусок из файла vendor\yiisoft\yii2-swiftmailer\Message.php
                $charset = $message->getCharset();
                $message->setBody(null);
                $message->setContentType(null);
                $message->addPart($oldBody, $oldContentType, $charset);
                $message->addPart($body, $contentType, $charset);
         */

        $oSwiftMessage
            ->setBody(
                $sText,
                'text/html'
            )
//            ->setBody(null)
//            ->setContentType(null)
//            ->addPart(
//                $template->$sText,
//                'text/html'
//            )
            ->addPart(
                self::preparePlainText($sText),
                'text/plain'
            )
            ->setSubject($act->subject)
            ->setFrom([self::$_fromEmail => 'Vic', ])
            ->setTo($sFrom == '' ? $subscriber->le_email : [$subscriber->le_email => $sFrom, ]);

        Yii::info('Finish while');
        return $oMessage;
//        $oSwiftMessage->
//            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
//            ->setTo($subscriber->le_email)
//            ->setSubject('Обращение № от ' . date('d.m.Y'));
    }

    /**
     *
     */
    public static function setDocRoot() {
        if( self::$_sDocRoot === null ) {
            self::$_sDocRoot = Yii::getAlias('@webroot', false);
            if( self::$_sDocRoot === false ) {
                self::$_sDocRoot = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'web';
            }
        }
    }

    /**
     * @param string $sImg
     * @return mixed|string
     */
    public static function prepareImgName($sImg = '') {
        if( strtolower(substr($sImg, 0, 4)) == 'http' ) {
            $sImg = substr($sImg, strpos($sImg, '/', 7));
            $sImg = str_replace('/', DIRECTORY_SEPARATOR, $sImg);
        }

        $sImg = self::$_sDocRoot . $sImg;

        return $sImg;
    }

    /**
     * @param string $sText
     * @param Listelement $subscriber
     * @param bool $bIsHtml
     * @return string
     */
    public static function prepareUserFields($sText, $subscriber, $bIsHtml = true) {
        $aAttrlabel = $subscriber->attributeLabels();

        $n = 0;
        $sReg = '/\\*\\|([^|]*?)\\|\\*/im';
        $aDopFields = [
            'now' => date('d.m.Y H:i'),
            'host' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '',
        ];

        while( preg_match($sReg, $sText, $aMatch, PREG_OFFSET_CAPTURE, $n) ) {
//            echo "field: {$aMatch[1][0]}\n";
            $sValue = '';
            $sField = $aMatch[1][0];
            try {
                $s1 = 'le_' . $sField;
                if( isset($aDopFields[$sField]) ) {
                    Yii::info('Get aDopFields value : ' . $sField . ' = ' . $aDopFields[$sField]);
                    $sValue = $aDopFields[$sField];
                }
                else if( isset($aAttrlabel[$s1]) ) {
                    Yii::info('Get subscriber attribute : ' . $s1 . ' = ' . $subscriber->$s1);
                    $sValue = $subscriber->$s1;
                }
                else {
                    Yii::info('Get subscriber : ' . $sField . ' = ' . $subscriber->$sField);
                    $sValue = $subscriber->$sField;
                }
            }
            catch(\Exception $e) {
                Yii::info('Error find subscriber filed: ' . $aMatch[0][0] . '. Text: ' . $sText);
                $sValue = '';
            }

            $sText = substr($sText, 0, $aMatch[0][1])
                . $sValue
                . substr($sText, $aMatch[0][1] + strlen($aMatch[0][0]));

            $n = $aMatch[0][1] + strlen($sValue);
//            break;
        }

        return $sText;
    }


    /**
     * @param Message $oMessage
     * @param Mailtempl $template
     * @return string
     */
    public static function prepareBodyFiles($oMessage, $sText) {
        /** @var Swift_Message $oSwiftMessage */
        $oSwiftMessage = $oMessage->getSwiftMessage();

        $n = 0;

        Yii::info('Start Text: ' . $sText);
        $sReg = '/<img.+?src=([\'|"])(.*?)\1[^>]*>/im';
        while( preg_match($sReg, $sText, $aMatch, PREG_OFFSET_CAPTURE, $n) ) {
            // $sText = '';
//            Yii::info('Text ' . $sText);
//            Yii::info('Match ' . print_r($aMatch, true));

            $sImg = self::prepareImgName($aMatch[2][0]);
            $sFileId = '';

            if( file_exists($sImg) && is_file($sImg) ) {
                $att = Swift_EmbeddedFile::fromPath($sImg)
                    ->setDisposition('inline');
                $sFileId = $oSwiftMessage->embed($att) ;
                Yii::info('sImg = ' . $sImg . ' sFileId =  ' . $sFileId);
            }
            else {
                Yii::info('Image Error: ' . $sImg . ' is ' . (file_exists($sImg) ? '' : ' not exists') . (is_file($sImg) ? '' : ' not a regular file'));
            }

            $sText = substr($sText, 0, $aMatch[2][1])
                . $sFileId
                . substr($sText, $aMatch[2][1] + strlen($aMatch[2][0]));

            $n = $aMatch[2][1] + strlen($sFileId);
//            break;
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
    public static function preparePlainText($sHtml = '') {
        if (preg_match('~<body[^>]*>(.*?)</body>~is', $sHtml, $match)) {
            $sHtml = $match[1];
        }
        // remove style and script
        $html = preg_replace('~<((style|script))[^>]*>(.*?)</\1>~is', '', $sHtml);
        // strip all HTML tags and decoded HTML entities
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, Yii::$app ? Yii::$app->charset : 'UTF-8');
        // improve whitespace
        $text = preg_replace("~^[ \t]+~m", '', trim($text));
        $text = preg_replace('~\R\R+~mu', "\n\n", $text);
        return $text;
    }
}