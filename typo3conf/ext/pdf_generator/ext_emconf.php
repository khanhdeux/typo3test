<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'PDF Generator',
    'description' => 'PDF-Generator-Extraction',
    'category' => 'plugin',
    'loadOrder' => '',
    'state' => 'stable',
    'version' => '1.0.0',
    'uploadfolder' => 1,
    'createDirs' => 'uploads/pdf_generator',
    'clearCacheOnLoad' => 1,
    'author' => 'Arrabiata',
    'author_email' => 'info@arrabiata.de',
    'author_company' => 'Arrabiata Solutions GmbH',
    'constraints' =>
    array (
        'conflicts' =>
            array (

            ),
        'depends' =>
            array (
                'typo3' => '6.0.0-6.2.99',
            ),
        'suggests' =>
            array (
            ),
    ),
);
