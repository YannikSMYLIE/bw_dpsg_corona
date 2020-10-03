<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:bw_dpsg_corona/Resources/Private/Language/locallang.xlf:tx_bwdpsgcorona_domain_model_meeting',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'ORDER BY begin_datetime ASC',
		'delete' => 'deleted',
		'searchFields' => 'begin_datetime, end_datetime, name',
        'iconfile' => 'EXT:bw_dpsg_corona/Resources/Public/Icons/Models/tx_bwdpsgcorona_domain_model_meeting.svg'
    ],
    'interface' => [
		'showRecordFieldList' => 'begin_datetime, end_datetime, name',
    ],
    'palettes' => [
	    'datetime' => [
		    'showitem' => 'begin_datetime, end_datetime',
	    ],
    ],
    'types' => [
		'0' => ['showitem' => '
			name,
			--palette--;LLL:EXT:bw_dpsg_corona/Resources/Private/Language/locallang.xlf:tx_bwdpsgcorona_domain_model_meeting.datetime;datetime,
			leader, participants, more_participants
		'],
    ],
    'columns' => [
		'name' => [
			'exclude' => true,
			'label' => 'LLL:EXT:bw_dpsg_corona/Resources/Private/Language/locallang.xlf:tx_bwdpsgcorona_domain_model_meeting.name',
			'config' => [
				'type' => 'input',
				'size' => 30,
				'eval' => 'required,trim'
			],
		],
        'begin_datetime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bw_dpsg_corona/Resources/Private/Language/locallang.xlf:tx_bwdpsgcorona_domain_model_meeting.begin_datetime',
            'config' => [
                'dbType' => 'datetime',
                'type' => 'input',
                'size' => 12,
                'eval' => 'datetime,required',
                'default' => '0000-00-00 00:00:00'
            ],
        ],
        'end_datetime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:bw_dpsg_corona/Resources/Private/Language/locallang.xlf:tx_bwdpsgcorona_domain_model_meeting.end_datetime',
            'config' => [
                'dbType' => 'datetime',
                'type' => 'input',
                'size' => 12,
                'eval' => 'datetime,required',
                'default' => '0000-00-00 00:00:00'
            ],
        ],
        'leader' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:bw_dpsg_corona/Resources/Private/Language/locallang.xlf:tx_bwdpsgcorona_domain_model_meeting.leader',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_bwdpsgnami_domain_model_mitglied',
                'foreign_table_where' => 'AND leiter = 1'
            ],
        ],
        'participants' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:bw_dpsg_corona/Resources/Private/Language/locallang.xlf:tx_bwdpsgcorona_domain_model_meeting.participants',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_bwdpsgnami_domain_model_mitglied',
                'MM' => 'tx_bwdpsgcorona_meeting_participants_mm'
            ],
        ],
        'more_participants' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:bw_dpsg_corona/Resources/Private/Language/locallang.xlf:tx_bwdpsgcorona_domain_model_meeting.more_participants',
            'config' => [
                'type' => 'text',
            ],
        ]
    ],
];
