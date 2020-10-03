<?php
defined('TYPO3_MODE') || die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']["EXTCONF"]["bwdpsgcamps"]["expenseCategory"]["zuschussParameter"] = [];

call_user_func(
    function($extKey)
	{
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'BoergenerWebdesign.BwDpsgCorona',
            'View',
            [
                'MeetingFrontend' => 'list, show, manage, create, update, addParticipant, removeParticipant',
                'Ajax' => 'removeParticipant'
            ],
            // non-cacheable actions
            [
                'MeetingFrontend' => 'list, show, manage, create, update, addParticipant, removeParticipant',
                'Ajax' => 'removeParticipant'
            ]
        );
    },
    $_EXTKEY
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][\TYPO3\CMS\Scheduler\Task\TableGarbageCollectionTask::class]['options']['tables'] = array(
    'tx_bwdpsgcorona_domain_model_meeting' => array(
        'dateField' => 'tstamp',
        'expirePeriod' => '28'
    )
);
