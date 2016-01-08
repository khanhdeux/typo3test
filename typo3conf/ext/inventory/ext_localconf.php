<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Khanhdeux.' . $_EXTKEY,
    'List',
    array(
        'Inventory' => 'list',
    ),
    // non-cacheable actions
    array(
        'Inventory' => 'list',
    )
);
