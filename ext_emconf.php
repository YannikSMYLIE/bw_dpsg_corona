<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Corona Anwesenheitslisten',
    'description' => 'Erstelle Listen zur Erfassung der Anwesenheiten bei Aktionen während der Corona Zeit.',
    'category' => 'plugin',
    'author' => 'Yannik Börgener',
    'author_email' => 'kontakt@boergener.de',
    'state' => 'beta',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.10 - 8.7.99',
	        'bw_dpsg_nami' => '8.0.0 - 8.9.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
