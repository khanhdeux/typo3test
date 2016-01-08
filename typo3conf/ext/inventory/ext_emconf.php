<?php
$EM_CONF[$_EXTKEY] = array(
    'title' => 'Inventory List',
    'description' => 'An extension to manage a stock.',
    'category' => 'plugin',
    'author' => 'Quoc Khanh Nguyen',
    'author_company' => '',
    'author_email' => '',
    'dependencies' => 'extbase,fluid',
    'state' => 'alpha',
    'clearCacheOnLoad' => '1',
    'version' => '0.0.0',
    'constraints' => array(
        'depends' => array(
            'typo3' => '6.2.0-6.2.99',
        ),
        'conflicts' => array(
        ),
        'suggests' => array(
        ),
    ),
);
?>