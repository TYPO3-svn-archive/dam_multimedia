<?php

########################################################################
# Extension Manager/Repository config file for ext: "dam_multimedia"
#
# Auto generated 24-07-2008 14:41
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Multimedia DAM usage',
	'description' => 'Modify the content type "Multimedia" for usage of the DAM.',
	'category' => 'fe',
	'shy' => 0,
	'version' => '0.2.4',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Juraj Sulek',
	'author_email' => 'juraj@sulek.sk',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'dam' => '',
			'php' => '3.0.0-0.0.0',
			'typo3' => '3.5.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:10:{s:9:"ChangeLog";s:4:"9fa6";s:26:"class.tx_dammultimedia.php";s:4:"1d12";s:21:"ext_conf_template.txt";s:4:"cc5d";s:12:"ext_icon.gif";s:4:"010a";s:17:"ext_localconf.php";s:4:"d8ec";s:14:"ext_tables.php";s:4:"06e6";s:14:"ext_tables.sql";s:4:"11b6";s:14:"doc/manual.sxw";s:4:"38b7";s:19:"doc/wizard_form.dat";s:4:"8f6d";s:20:"doc/wizard_form.html";s:4:"3462";}',
);

?>