CREATE TABLE tx_grevman_domain_model_event (
	title varchar(255) NOT NULL DEFAULT '',
	member_groups int(11) unsigned NOT NULL DEFAULT '0',
	registrations int(11) unsigned NOT NULL DEFAULT '0',
	notes int(11) unsigned NOT NULL DEFAULT '0',
	guests int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_grevman_domain_model_group (
	name varchar(255) NOT NULL DEFAULT '',
	events int(11) unsigned NOT NULL DEFAULT '0',
	members int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE fe_users (
	member_groups int(11) unsigned NOT NULL DEFAULT '0',
	registrations int(11) unsigned NOT NULL DEFAULT '0',
	notes int(11) unsigned NOT NULL DEFAULT '0',
	tx_extbase_type varchar(255) DEFAULT '' NOT NULL
);

CREATE TABLE tx_grevman_domain_model_registration (
	event int(11) unsigned DEFAULT '0' NOT NULL,
	member int(11) unsigned DEFAULT '0' NOT NULL,
	status int(11) NOT NULL DEFAULT '0'
);

CREATE TABLE tx_grevman_domain_model_note (
	event int(11) unsigned DEFAULT '0' NOT NULL,
	member int(11) unsigned DEFAULT '0' NOT NULL,
	text text NOT NULL DEFAULT ''
);

CREATE TABLE tx_grevman_domain_model_guest (
	first_name varchar(255) NOT NULL DEFAULT '',
	last_name varchar(255) NOT NULL DEFAULT '',
	phone varchar(255) NOT NULL DEFAULT '',
	email varchar(255) NOT NULL DEFAULT ''
);

CREATE TABLE tx_grevman_event_group_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

CREATE TABLE tx_grevman_event_guest_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

CREATE TABLE tx_grevman_group_member_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

CREATE TABLE tx_grevman_member_group_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
