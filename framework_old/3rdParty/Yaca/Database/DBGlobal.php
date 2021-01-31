<?php

//Data For Excel

class DBGlobal {	
	/**
	* result connection to db
	*/
	protected $conn;
	
	/**
	* result query
	*/
	protected $ResultQuery;
	
	/**
	* list record
	*/
	protected $ListRecord;
	
	/**
	* field dari tabel
	*/
	protected $FieldTable;
	
	/**
	* field dari Excel
	*/
	protected $FieldExcel;
	
	/**
	* objExce2MySQL
	*/
	protected $objExcel2MySQL;
	
	/**
	*
	* mengeset field untuk excel
	*/
	public function setFieldExcel ($field=array()) {	 		
		$this->FieldExcel= $field;
	}
	
	/**
	*
	* mengeset field table secara manual
	*/
	public function setFieldTable ($field=array()) {	 
		foreach ($field as $k=>$v) {
			$ft[]=array("field"=>$v);			 
		}
		$this->FieldTable = $ft;
	}
	
	/**
	* get field of table
	* @param arg field or type
	*/	
	public function getFieldTable ($arg="all") {
		if (count ($this->FieldTable) != 0 ) {
			$ft = $this->FieldTable;
// 			print_r($ft);			
			if ($arg == "all") {
				return $ft;
			}else {
				foreach ($ft as $value) {
					if ($arg == "type") {
						$arrTable = "";
					}else if ($arg == "field") {
						$arrTable[]=$value[$arg];
					}else {
						throw new Exception ("DBHandler::getFieldTable::arg valid are type and field");
						break;
					}					
				}			
				return $arrTable;
			}
		}else {
			throw new Exception ("First, please set the table name or field of table");
		}
	}
	
	/**
	* Load from excel
	* @param excelFile file excel beserta path-nya
	*/
	public function loadDataFromExcel ($excelFile) {
		if (file_exists($excelFile)) {
			require_once 'Excel2MySQL'.DIRECTORY_SEPARATOR.'excel2mysql.php';
			$this->objExcel2MySQL = new Excel2MySQL ($excelFile);
		}else {
			throw new Exception ($excelFile.' is not valid ....');
		}
	}
	
	/**
	* dapatkan objek dari Excel Reader
	* @param excelFile file excel beserta path-nya
	*/
	public function getObjExcelReader () {
		return $this->objExcel2MySQL;
	}
	/**
	* untuk mendapatkan fields dari field table dalam sintak sql
	*
	*/
	public function getFieldSQL ($fields=null) {
// 		print_r($fields);
		if ($fields === null) {
			$fieldTable=$this->fieldTable;
		}else {
			$fieldTable=$fields;
		}
		$countField = count($fieldTable);
		if ($countField <= 1) {
			$field =$fieldTable[0];
		}else {
			for ($i=0;$i<$countField;$i++) {
				if ($countField > $i+1) {
					$field = $field . $fieldTable[$i] . ',';
				}else {
					$field = $field . $fieldTable[$i];
				}
			}
		}
		return $field;
	}
	
}
?>