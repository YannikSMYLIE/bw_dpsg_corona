#
# Table structure for table 'tx_bwdpsgcorona_domain_model_meeting'
#
CREATE TABLE tx_bwdpsgcorona_domain_model_meeting (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,

    begin_datetime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    end_datetime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    name varchar(255) DEFAULT '' NOT NULL,

    leader int(11) unsigned DEFAULT '0' NOT NULL,
    participants int(11) unsigned DEFAULT '0' NOT NULL,
    more_participants TEXT DEFAULT '' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,

    t3ver_oid int(11) DEFAULT '0' NOT NULL,
    t3ver_id int(11) DEFAULT '0' NOT NULL,
    t3ver_wsid int(11) DEFAULT '0' NOT NULL,
    t3ver_label varchar(255) DEFAULT '' NOT NULL,
    t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
    t3ver_stage int(11) DEFAULT '0' NOT NULL,
    t3ver_count int(11) DEFAULT '0' NOT NULL,
    t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
    t3ver_move_id int(11) DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid),
    KEY t3ver_oid (t3ver_oid,t3ver_wsid),
);

#
# Table structure for table 'tx_bwdpsgcorona_meeting_participants_mm'
#
CREATE TABLE tx_bwdpsgcorona_meeting_participants_mm (
    uid_local int(11) unsigned DEFAULT '0' NOT NULL,
    uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    sorting int(11) unsigned DEFAULT '0' NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,
    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);