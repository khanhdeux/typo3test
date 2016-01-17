<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Lobacher.' . $_EXTKEY,
    'Bloglisting',
    'Simpleblog - Bloglisting'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Simple Blog Extension');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_simpleblog_domain_model_blog', 'EXT:simpleblog/Resources/Private/Language/locallang_csh_tx_simpleblog_domain_model_blog.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_simpleblog_domain_model_blog');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_simpleblog_domain_model_post', 'EXT:simpleblog/Resources/Private/Language/locallang_csh_tx_simpleblog_domain_model_post.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_simpleblog_domain_model_post');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_simpleblog_domain_model_comment', 'EXT:simpleblog/Resources/Private/Language/locallang_csh_tx_simpleblog_domain_model_comment.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_simpleblog_domain_model_comment');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_simpleblog_domain_model_author', 'EXT:simpleblog/Resources/Private/Language/locallang_csh_tx_simpleblog_domain_model_author.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_simpleblog_domain_model_author');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_simpleblog_domain_model_tag', 'EXT:simpleblog/Resources/Private/Language/locallang_csh_tx_simpleblog_domain_model_tag.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_simpleblog_domain_model_tag');

$TCA['fe_users']['ctrl']['label'] = 'name';

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Lobacher.' . $_EXTKEY,
        'system',
        'SimpleblogAdmin',
        'top',
        array(
            'Comment' => 'list,delete,test'
        ),
        array(
            'access' => 'admin',
            'icon' => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
            'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xlf'
        )
    );
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    '_MOD_system_SimpleblogSimpleblogadmin',
    'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh.xlf'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Lobacher\\Simpleblog\\Command\\FileCommandController'] = array(
    'extension' => $_EXTKEY,
    'title' => 'Filecenter Delete Files',
    'description' => 'Deletes Files in Filecenter after the defined period of time',
    'additionalFields' => ''
);