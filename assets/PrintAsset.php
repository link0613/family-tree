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
class PrintAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'getorgchart/getorgchart.css',
        'css/site.css?2',

    ];
    public $js = [
        '//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js',
        '//d3js.org/d3.v3.min.js',
        'getorgchart/getorgchart-src.js',

    ];
    public $depends = [
    ];
}
