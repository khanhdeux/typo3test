<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "femanagerextended".
 *
 * Auto generated 27-01-2016 22:30
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'femanagerextended',
	'description' => 'Extend fe_users and femanager with a new field and a new validation',
	'category' => 'plugin',
	'author' => '',
	'author_email' => '',
	'author_company' => '',
	'state' => 'experimental',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '0.1.2',
	'constraints' => 
	array (
		'depends' => 
		array (
			'extbase' => '6.0.0-6.2.99',
			'fluid' => '6.0.0-6.2.99',
			'typo3' => '6.0.0-6.2.99',
			'femanager' => '1.0.0-1.99.99',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
	'clearcacheonload' => false,
);

