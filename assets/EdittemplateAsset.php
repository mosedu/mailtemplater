<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class EdittemplateAsset extends AssetBundle
{
    public $sourcePath = '@app/assets';

    public $css = [
        'js/edittemplate.css',
    ];

    public $js = [
        'js/edittemplate.js',
    ];

    public $depends = [
        'app\assets\AppAsset',
        'yii\bootstrap\BootstrapThemeAsset',
    ];
}
