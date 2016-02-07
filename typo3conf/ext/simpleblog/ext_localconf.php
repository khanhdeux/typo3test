<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Lobacher.' . $_EXTKEY,
	'Bloglisting',
	array(
		'Blog' => 'list, addForm, add, show, updateForm, update, deleteConfirm, delete, rss',
        'Post' => 'addForm,add,show,updateForm,update,deleteConfirm,delete,ajax,ajaxAddress',
	),
	// non-cacheable actions
	array(
		'Blog' => 'list, addForm, add, show, updateForm, update, deleteConfirm, delete, rss',
        'Post' => 'addForm,add,show,updateForm,update,deleteConfirm,delete,ajax,ajaxAddress',
	)
);

$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    'TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher'
);
$signalSlotDispatcher->connect(
    'TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Backend',
    'afterInsertObject',
    'Lobacher\\Simpleblog\\Service\\SignalService',
    'handleInsertEvent'
);

$signalSlotDispatcher->connect(
    'Lobacher\\Simpleblog\\Controller\\PostController',
    'beforeCommentCreation',
    'Lobacher\\Simpleblog\\Service\\SignalService',
    'handleCommentInsertion'
);