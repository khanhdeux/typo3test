<?php
defined('TYPO3_MODE') or die();

$GLOBALS['TCA']['fe_users']['columns']['image']['config'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('image');

// Add some fields to FE Users table to show TCA fields definitions
// USAGE: TCA Reference > $TCA array reference > ['columns'][fieldname]['config'] / TYPE: "select"
$temporaryColumns = array (
    'tx_guestbook_options' => array (
        'exclude' => 0,
        'label' => 'Options',
        'config' => array (
            'type' => 'select',
            'items' => array (
                array('1', '1'),
                array('2', '2'),
                array('3', '3'),
            ),
            'size' => 1,
            'maxitems' => 1,
        )
    ),
    'tx_guestbook_special' => array (
        'exclude' => 0,
        'label' => 'Special',
        'config' => array (
            'type' => 'check',
        )
    ),
    'tx_guestbook_tax_id' => array (
        'exclude' => 0,
        'label' => 'Tax ID',
        'config' => array (
            'type' => 'input',
        )
    ),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'fe_users',
    $temporaryColumns
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'fe_users',
    'tx_guestbook_options, tx_guestbook_special, tx_guestbook_tax_id'
);