<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Vendor.' . $_EXTKEY,
	'Commentlisting',
	array(
		'Comment' => 'list,ajax',
		
	),
	// non-cacheable actions
	array(
		'Comment' => 'list,ajax',
		
	)
);
