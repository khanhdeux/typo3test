<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
	$_EXTKEY,
	'pdfgenerator',
	'tx_pdf_generator',
	array (
		'title' => 'PdfGenerator for TYPO3',
		'description' => '',
		'subtype' => '',
		'available' => TRUE,
		'priority' => 50,
		'quality' => 50,
		'os' => '',
		'exec' => '',
		'classFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Service/PdfGenerator.php',
		'className' => '\Arrabiata\PdfGenerator\Service\PdfGenerator',
	)
);