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
class ProfileAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
        '//fonts.googleapis.com/css?family=Courgette|Rubik',
        'getorgchart/getorgchart.css',
        '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css',
        'css/site.css?2',
        //'css/dtree.css'
		'css/dtree-new.css'
    ];
    public $js = [
        '//fastcdn.org/FileSaver.js/1.1.20151003/FileSaver.min.js',
        'js/rgbcolor.js',
        'js/StackBlur.js',
        'js/canvg.js',
        'js/blob-stream.js',
        'js/pdfkit.js',
        'js/svgToPdf.js',
        'js/jquery.easing.min.js',
        'js/core.js',
        'getorgchart/getorgchart-src.js',
        //'getorgchart/getorgchart-dist.js',
        //TODO::REMOVE EXTERNAL LINKS

//        '//d3js.org/d3.v3.min.js',
//        '//cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.1/lodash.min.js',
//        'js/dTree-dist.js'

		'//d3js.org/d3.v4.min.js',
        '//cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js',
        'js/dTree-dist-new.js'

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
