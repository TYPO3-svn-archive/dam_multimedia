<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$tempSetup = $GLOBALS['T3_VAR']['ext']['dam_multimedia']['setup'];
$defaultMaxSize=10485760;
$tempColumns = array (
	'tx_dammultimedia_multimedia' => txdam_getMediaTCA('media_field', 'tx_dammultimedia_multimedia')
);

t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content',$tempColumns,1);



if ($tempSetup['ctype_multimedia_add_orig_field']) {
	t3lib_extMgm::addToAllTCAtypes('tt_content','tx_dammultimedia_multimedia','multimedia','after:multimedia');
} else {
	$TCA['tt_content']['types']['multimedia']['showitem'] = str_replace(', multimedia;', ', tx_dammultimedia_multimedia;', $TCA['tt_content']['types']['multimedia']['showitem']);
}

$TCA['tt_content']['columns']['tx_dammultimedia_multimedia']['config']['allowed_types'] = $TCA['tt_content']['columns']['multimedia']['config']['allowed'];
$TCA['tt_content']['columns']['tx_dammultimedia_multimedia']['config']['size']=($TCA['tt_content']['columns']['multimedia']['config']['size'] < 2)?2:$TCA['tt_content']['columns']['multimedia']['config']['size'];
$TCA['tt_content']['columns']['tx_dammultimedia_multimedia']['config']['maxitems']=$TCA['tt_content']['columns']['multimedia']['config']['maxitems'];
$TCA['tt_content']['columns']['tx_dammultimedia_multimedia']['config']['minitems']=$TCA['tt_content']['columns']['multimedia']['config']['minitems'];
if($TCA['tt_content']['columns']['multimedia']['config']['max_size']>$defaultMaxSize){
	$TCA['tt_content']['columns']['tx_dammultimedia_multimedia']['config']['max_size']=$TCA['tt_content']['columns']['multimedia']['config']['max_size'];
}else{
	$TCA['tt_content']['columns']['tx_dammultimedia_multimedia']['config']['max_size']=$defaultMaxSize;
};
$TCA['tt_content']['columns']['tx_dammultimedia_multimedia']['config']['disallowed_types'] = '';

?>