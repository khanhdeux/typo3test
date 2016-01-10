<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'Vendor.' . $_EXTKEY,
	'Commentlisting',
	'Guestbook - Commentlisting'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Guest Book');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_guestbook_domain_model_author', 'EXT:guestbook/Resources/Private/Language/locallang_csh_tx_guestbook_domain_model_author.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_guestbook_domain_model_author');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_guestbook_domain_model_comment', 'EXT:guestbook/Resources/Private/Language/locallang_csh_tx_guestbook_domain_model_comment.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_guestbook_domain_model_comment');
