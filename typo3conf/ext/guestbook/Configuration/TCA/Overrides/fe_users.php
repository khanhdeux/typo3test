<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TCA']['fe_users']['columns']['image']['config'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('image');