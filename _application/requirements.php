<?php
/**
 * Application requirement checker script.
 *
 * In order to run this script use the following console command:
 * php requirements.php
 *
 * In order to run this script from the web, you should copy it to the web root.
 * If you are using Linux you can create a hard link instead,
 * using the following command: ln requirements.php ../requirements.php
 */

// you may need to adjust this path to the correct Yii framework path
$frameworkPath = dirname(dirname(__FILE__)) . '/vendor/yiisoft/yii2';

if (!is_dir($frameworkPath)) {
    echo '<h1>Error</h1>';
    echo '<p><strong>The path to yii framework seems to be incorrect.</strong></p>';
    echo '<p>You need to install Yii framework via composer or adjust the framework path in file <abbr title="' . __FILE__ . '">' . basename(__FILE__) . '</abbr>.</p>';
    echo '<p>Please refer to the <abbr title="' . dirname(dirname(__FILE__)) . '/README.md">README</abbr> on how to install Yii.</p>';
}

require_once($frameworkPath . '/requirements/YiiRequirementChecker.php');
$requirementsChecker = new YiiRequirementChecker();

/**
 * Adjust requirements according to your application specifics.
 *
 * @var array
 */
$requirements = [
    // Database :
    [
        'name' => 'PDO extension',
        'mandatory' => true,
        'condition' => extension_loaded('pdo'),
        'by' => 'All DB-related classes',
    ],
    [
        'name' => 'PDO SQLite extension',
        'mandatory' => false,
        'condition' => extension_loaded('pdo_sqlite'),
        'by' => 'All DB-related classes',
        'memo' => 'Required for SQLite database.',
    ],
    [
        'name' => 'PDO MySQL extension',
        'mandatory' => false,
        'condition' => extension_loaded('pdo_mysql'),
        'by' => 'All DB-related classes',
        'memo' => 'Required for MySQL database.',
    ],
    [
        'name' => 'PDO PostgreSQL extension',
        'mandatory' => false,
        'condition' => extension_loaded('pdo_pgsql'),
        'by' => 'All DB-related classes',
        'memo' => 'Required for PostgreSQL database.',
    ],
    // Cache :
    [
        'name' => 'Memcache extension',
        'mandatory' => false,
        'condition' => extension_loaded('memcache') || extension_loaded('memcached'),
        'by' => '<a href="http://www.yiiframework.com/doc-2.0/yii-caching-memcache.html">MemCache</a>',
        'memo' => extension_loaded('memcached') ? 'To use memcached set <a href="http://www.yiiframework.com/doc-2.0/yii-caching-memcache.html#$useMemcached-detail">MemCache::useMemcached</a> to <code>true</code>.' : ''
    ],
    [
        'name' => 'APC extension',
        'mandatory' => false,
        'condition' => extension_loaded('apc'),
        'by' => '<a href="http://www.yiiframework.com/doc-2.0/yii-caching-apccache.html">ApcCache</a>',
    ],
    // PHP ini :
    'phpExposePhp' => [
        'name' => 'Expose PHP',
        'mandatory' => false,
        'condition' => $requirementsChecker->checkPhpIniOff("expose_php"),
        'by' => 'Security reasons',
        'memo' => '"expose_php" should be disabled at php.ini',
    ],
    'phpAllowUrlInclude' => [
        'name' => 'PHP allow url include',
        'mandatory' => false,
        'condition' => $requirementsChecker->checkPhpIniOff("allow_url_include"),
        'by' => 'Security reasons',
        'memo' => '"allow_url_include" should be disabled at php.ini',
    ],
    'phpSmtp' => [
        'name' => 'PHP mail SMTP',
        'mandatory' => false,
        'condition' => strlen(ini_get('SMTP')) > 0,
        'by' => 'Email sending',
        'memo' => 'PHP mail SMTP server required',
    ],
];
$requirementsChecker->checkYii()->check($requirements)->render();
