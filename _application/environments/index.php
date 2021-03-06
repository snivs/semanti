<?php

$themes = ['default', 'cerulean', 'slate', 'spacelab'];
$paths = [
    '/_application/backend/runtime',
    '/_application/frontend/runtime',
    '/_application/console/runtime',
    '/web/assets',
    '/web/backend/assets',
];
foreach ($themes as $theme) {
    $paths[] = '/web/themes/'.$theme.'/compiled';
    $paths[] = '/web/themes/'.$theme.'/.sass-cache';
    $paths[] = '/web/backend/themes/'.$theme.'/compiled';
    $paths[] = '/web/backend/themes/'.$theme.'/.sass-cache';
}

/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of directories that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => $paths,
        'setExecutable' => [
            '_application/yii',
        ],
        'setCookieValidationKey' => [
            '_application/backend/config/main-local.php',
            '_application/frontend/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => $paths,
        'setExecutable' => [
            '_application/yii',
            '_application/frontend/runtime/mail',
        ],
        'setCookieValidationKey' => [
            '_application/backend/config/main-local.php',
            '_application/frontend/config/main-local.php',
        ],
    ],
    'Staging' => [
        'path' => 'staging',
        'setWritable' => $paths,
        'setExecutable' => [
            '_application/yii',
        ],
        'setCookieValidationKey' => [
            '_application/backend/config/main-local.php',
            '_application/frontend/config/main-local.php',
        ],
    ],
];
