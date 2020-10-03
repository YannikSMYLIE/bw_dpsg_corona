<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function($extKey)
    {
        if (TYPO3_MODE === 'BE') {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'BoergenerWebdesign.BwDpsgCorona',
	            'dpsg', // Make module a submodule of 'tools'
                'administration', // Submodule key
                '', // Position
                [   // Actions
	                'Meeting' => 'list, show, create, update, delete',
                ],
                [
                    'access' => 'user,group',
					'icon'   => 'EXT:bw_dpsg_corona/Resources/Public/Icons/Modules/administration.jpg',
                    'labels' => 'LLL:EXT:bw_dpsg_corona/Resources/Private/Language/locallang_administration.xlf',
                ]
            );
        }
    },
    $_EXTKEY
);