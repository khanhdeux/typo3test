<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Pluswerk.' .$_EXTKEY,
    'Showcase',
    'Empty Extbase/Fluid Container'
);

?>