<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "femanager".
 *
 * Auto generated 27-01-2016 22:30
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'femanager',
	'description' => '
		TYPO3 Frontend User Registration and Management based on
		Extbase and Fluid with Namespaces and a lot of features and
		extension possibilities. Extension basicly works like sr_feuser_register
	',
	'category' => 'plugin',
	'author' => 'Alex Kellner',
	'author_email' => 'alexander.kellner@in2code.de',
	'author_company' => 'in2code.de - Wir leben TYPO3',
	'state' => 'stable',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '1.5.2',
	'constraints' => 
	array (
		'depends' => 
		array (
			'extbase' => '6.2.0-7.99.99',
			'fluid' => '6.2.0-7.99.99',
			'typo3' => '6.2.0-7.99.99',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
			'sr_freecap' => '2.0.4-2.99.99',
			'static_info_tables' => '6.0.0-6.99.99',
		),
	),
	'clearcacheonload' => false,
);

