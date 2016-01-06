<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Pluswerk.' . $_EXTKEY,
    'Showcase',
	array(
		'Start' => 'index,show',
	),
	array(
		'Start' => 'index,show',
	)
);

?>