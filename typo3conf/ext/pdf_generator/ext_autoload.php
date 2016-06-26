<?php
$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pdf_generator');
return array(
	'MakePdf' => $extensionPath . 'Resources/Private/Contributed/MakePdf.php',
	'MakePdfMove' => $extensionPath . 'Resources/Private/Contributed/MakePdfMove.php',
	'MakePdfNormal2013' => $extensionPath . 'Resources/Private/Contributed/MakePdfNormal2013.php',
);