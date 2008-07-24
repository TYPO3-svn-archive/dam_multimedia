<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$GLOBALS['T3_VAR']['ext']['dam_multimedia']['setup'] = unserialize($_EXTCONF);

$tempAdditional='additional = none';
$tempAdditional2 = '';
if ($GLOBALS['T3_VAR']['ext']['dam_multimedia']['setup']['ctype_multimedia_add_orig_field']) {
	$tempAdditional='additional = yes';	
	$tempAdditional2 = 'multimedia,';
};
$tempGetWH='';
if ($GLOBALS['T3_VAR']['ext']['dam_multimedia']['setup']['enable_get_wh']) {
	$tempGetWH='tt_content.multimedia.20.params >
		tt_content.multimedia.20.params.cObject=USER
		tt_content.multimedia.20.params.cObject{
			userFunc=tx_dammultimedia->getParameter
			damWidth=vpixels,width
			damHeight=hpixels,height
		}';
};

t3lib_extMgm::addTypoScript($_EXTKEY,'setup','
		includeLibs.tx_dam_multimedia_1 = EXT:dam/lib/class.tx_dam_tcefunc.php
		includeLibs.tx_dam_multimedia_2 = EXT:dam/lib/class.tx_dam_tcefunc.php
		includeLibs.tx_dam_multimedia_3 = EXT:dam_multimedia/class.tx_dammultimedia.php

		tt_content.multimedia.20.file>
		tt_content.multimedia.20.file.cObject=USER
		tt_content.multimedia.20.file.cObject{
			userFunc=tx_dammultimedia->getMultimedia
			'.$tempAdditional.'
		}
		tt_content.multimedia.20.file.listNum=last
		tt_content.multimedia.20.stdWrap.editIcons = tt_content:'.$tempAdditional2.'tx_dammultimedia_multimedia, bodytext

		'.$tempGetWH.'
	',43);
?>