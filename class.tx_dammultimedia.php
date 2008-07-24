<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2006 Juraj Sulek (juraj@sulek.sk)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin dam_multimedia.
 *
 * $Id: class.tx_dammultimedia.php,v 0.2.0 2006/01/01 20:00:00 typo3 Exp $
 *
 * @author	Juraj Sulek <juraj@sulek.sk>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   52: class tx_dammultimedia extends tslib_pibase
 *   68:     function getMultimedia($content, $conf)
 *   98:     function getDamSql($field,$defaultField='')
 *  121:     function getDamResult($field,$row,$defaultField='')
 *  143:     function splitParams($oldParams,$damParams)
 *  164:     function getParameter($content, $conf)
 *
 * TOTAL FUNCTIONS: 5
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
	require_once(PATH_tslib."class.tslib_pibase.php");

	class tx_dammultimedia extends tslib_pibase {
		/*
			This function is required while the DAM has a BUG in tx_dam_tceFunc->fetchFileList
			When this bug is repaired this funcion is not more necessary and the ext_localconf.php->typoscript will be:

			......
			tt_content.multimedia.20.file>
			tt_content.multimedia.20.file.cObject=USER
			tt_content.multimedia.20.file.cObject{
				userFunc=tx_dam_tceFunc->fetchFileList
				refField=tx_dammultimedia_multimedia
				additional >
			}
			......

		*/
		function getMultimedia($content, $conf){
			global $TSFE;
			if(method_exists('tx_dam_tceFunc','fetchFileList')){
				$multimedia_ts['cObject.']['userFunc']='tx_dam_tceFunc->fetchFileList';
			}else{
				$multimedia_ts['cObject.']['userFunc']='tx_dam_tsfe->fetchFileList';
			}
			$multimedia_ts['cObject.']['refField']='tx_dammultimedia_multimedia';
			if($conf['additional']=='yes'){
				$multimedia_ts['cObject.']['additional.']['fileList.']['field']='multimedia';
				$multimedia_ts['cObject.']['additional.']['filePath']='uploads/media/';
			}else{
				$multimedia_ts['cObject.']['additional']='>';
			};

			$multimedia_file=$this->cObj->USER($multimedia_ts['cObject.']);
			$multimedia_file=trim($multimedia_file);
			$multimedia_file=trim($multimedia_file,',');
			$multimedia_file=trim($multimedia_file);
			return $multimedia_file;

		}

	/**
	 * Return a string for sql select:
	 *
	 * @param	string		$field: string with database fields separated by ',' (e.g. autor,crdate,height)
	 * @param	string		$defaultField: this will be used if the $field is empty
	 * @return	array		string for select (e.g. 'tx_dam.field1, tx_dam.field2')
	 */
		function getDamSql($field,$defaultField=''){
			$field_sql='';
			$field=trim($field);
			$field=trim($field,',');
			$field=trim($field);
			if($field!=''){
				$field_array=t3lib_div::trimExplode(',',$field);
				foreach($field_array as $fieldFor){
					$field_sql.='tx_dam.'.$fieldFor.' ,';
				};
			};
			if(($defaultField!='')&&($field_sql=='')){$field_sql='tx_dam.'.$defaultField.' ,';};
			return $field_sql;
		}

	/**
	 * Return a value from query result and field definition:
	 *
	 * @param	string		$field: string with database fields separated by ',' (e.g. autor,crdate,height)
	 * @param	array		$row: myslq result
	 * @param	string		$defaultField: this will be used if the $field is empty
	 * @return	array		value from mysql result
	 */
		function getDamResult($field,$row,$defaultField=''){
			$field_return='';
			$string=trim($field);
			$string=trim($field,',');
			$field=trim($field);
			if($field!=''){
				$field_array=t3lib_div::trimExplode(',',$field);
				foreach($field_array as $fieldFor){
					if(trim($row[$fieldFor])!=''){$field_return=trim($row[$fieldFor]);};
				};
			};
			if(($defaultField!='')&&($field_return=='')){$field_return=trim($row[$defaultField]);};
			return $field_return;
		}

	/**
	 * Return a string with dam parameters+user defined parameters:
	 *
	 * @param	string		$oldParams: string width parameters from be field
	 * @param	array		$damParams: params obtained from dam
	 * @return	string		splited params
	 */
		function splitParams($oldParams,$damParams){
			$new_parameter=$damParams;
			$paramsLines = explode(chr(10), $oldParams);
			while(list(,$l)=each($paramsLines))	{
				$parts = explode('=', $l);
				$parameter = strtolower(trim($parts[0]));
				$value = trim($parts[1]);
				if ((string)$value!='')	{
					$new_parameter[$parameter] = $parameter.'='.htmlspecialchars($value);
				};
			};
			return implode($new_parameter,chr(10));
		}

	/**
	 * Returns params
	 *
	 * @param	string		Content input. Not used, ignore.
	 * @param	array		TypoScript configuration
	 * @return	string		params from be field and defined dam fields.
	 */
		function getParameter($content, $conf){
			$userParameter=trim($this->cObj->data['bodytext']);
			$multimediaUid=$this->cObj->data['uid'];
			$defaultHeight='height';
			$defaultWidth='width';
			if($this->cObj->data['tx_dammultimedia_multimedia']==0){return $userParameter;};
			$heightFieldsSQL=$this->getDamSql($conf['damHeight'],$defaultHeight);
			$widthFieldsSQL=$this->getDamSql($conf['damWidth'],$defaultWidth);

			/* select */
			$select = 'tx_dam.uid, '.$heightFieldsSQL.$widthFieldsSQL.' tx_dam.title, tx_dam.file_path, tx_dam.file_name, tx_dam.file_type';
			$whereClause = ' AND tx_dam_mm_ref.tablenames="'.$GLOBALS['TYPO3_DB']->quoteStr('tt_content','tx_dam_mm_ref').'"';
			$orderBy = 'tx_dam_mm_ref.sorting desc';
			$limit=1;
			$res = $GLOBALS['TYPO3_DB']->exec_SELECT_mm_query(
				$select,
				'tx_dam',
				'tx_dam_mm_ref',
				'tt_content',
				'AND tt_content.uid IN ('.$multimediaUid.') '.$whereClause,
				$groupBy,
				$orderBy,
				$limit
			);
			/* select ende */
			$new_parameter=array();
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			$tempHeight=$this->getDamResult($conf['damHeight'],$row,$defaultHeight);
			if(intval($tempHeight!=0)){
				$new_parameter['height']='height='.$tempHeight;
			}
			$tempWidth=$this->getDamResult($conf['damWidth'],$row,$defaultWidth);
			if(intval($tempWidth!=0)){
				$new_parameter['width']='width='.$tempWidth;
			}

			return $this->splitParams($userParameter,$new_parameter);
		}
	}

	if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_multimedia/class.tx_dammultimedia.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_multimedia/class.tx_dammultimedia.php']);
}
?>