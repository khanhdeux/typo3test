<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Lobcher.' . $_EXTKEY,
	'Bloglisting',
    array(
        'Blog' => 'list, addForm, add, show, updateForm, update, deleteConfirm, delete',
    ),
    // non-cacheable actions
    array(
        'Blog' => 'list, addForm, add, show, updateForm, update, deleteConfirm, delete',
    )
);
