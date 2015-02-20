<?php

return [
    ''                                          => 'backend/index/index',
    '/home'                                     => 'backend/index/index',
    '/translations/rescan'                      => 'backend/translations/rescan',
    '/translations/clear-cache'                 => 'backend/translations/clear-cache',

    // @see http://www.elisdn.ru/blog/62/seo-service-on-yii2-ide-and-modules
    '<_a:(backend|dashbord|admin)>'             => 'backend/index/index',
    '<_a:error>'                                => 'backend/index/<_a>',
    '<_a:(index|login|logout)>'                 => 'backend/index/<_a>',

    '<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w\-]+>'    => '<_m>/<_c>/<_a>',
    '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>'        => '<_m>/<_c>/view',
    '<_m:[\w\-]+>'                              => '<_m>/index/index',
    '<_m:[\w\-]+>/<_a:[\w\-]+>'                 => '<_m>/index/<_a>',
    '<_m:[\w\-]+>/<_c:[\w\-]+>'                 => '<_m>/<_c>/index',

    '<_c:[\w\-]+>'                              => 'backend/<_c>/index',
    '<_c:[\w\-]+>/<_a:[\w\-]+>'                 => 'backend/<_c>/<_a>',
];