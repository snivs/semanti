<?php

namespace common\assets;

class AppCommonAsset extends AssetBundle
{
    public $sourcePath = '@common/web';
    public $css = [
        'scss/basemod-classes.scss',
        'scss/common.scss',
    ];
    public $js = [
        'js/common.js',
    ];
    public $depends = [
        'common\assets\AppSpoilersAsset',
        'common\assets\AppChosenSelectAsset',
        'common\assets\AppAjaxButtonsAsset',

        'rmrevin\yii\fontawesome\AssetBundle',
        'common\assets\JqueryCookieAsset',
        'common\assets\JqueryFormAsset',
        'common\assets\FancyboxAsset',
    ];
}
