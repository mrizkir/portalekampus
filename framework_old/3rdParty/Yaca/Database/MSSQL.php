<?php

require_once 'DBInterface.php';
require_once 'DBGlobal.php';

class MSSQL extends DBGlobal implements DBInterface {
	
	/**
	* digunakan untuk koneksi ke database
	* @param param array (host,dbname,user,password)
	* @return void
	*/
	public function connectDB ($param) { 
		$this->conn = @ mssql_connect ($param['host'],$param['user'],$param['password']);		
		if ($this->conn) {
			mssql_select_db ($param['dbname'],$this->conn);
		}
	}
	/**
	* digunakan untuk mengeksekusi  perintah sql
	* @param param sqlString
	* @return void
	*/
	public function query ($sqlString) {
		if ($result=@ mssql_query($sqlString)) {
			$this->ResultQuery=$result;
			return $result;
		}else {
			throw new Exception ('Query Failed = '.$sqlString);
		}
	}
	
	
	/**
	* mengambil record dari sebuah tabel dalam bentuk array
	* @param sqlString ini sql string
	* @param offset 
	*
	*/	
	public function getRecord ($sqlString,$offset=1) {		
// 		echo $sqlString;				
			
		if (mssql_num_rows($result=$this->query($sqlString)) >= 1) {
			if ($offset  == '') 
				$offset = 1;
			
			$ft = $this->getFieldTable("field");
// 			print_r($ft);
			$countFieldTable = count ($ft);
			$counter = 1;									
			while ($row=mssql_fetch_array($result)) {
 				//echo $row . "<br>";
				$tempRecord['no']=$offset;
				for ($i=0;$i < $countFieldTable;$i++) {
					$tempRecord[$ft[$i]]=trim($row[$ft[$i]]);
				}
				$ListRecord[$counter]=$tempRecord;
				$counter++;			
				$offset++;
			}		
// 	 		print_r($ListRecord);		
			$this->ListRecord=$ListRecord;									
		}else {
			$this->ListRecord=array();									
		}
		return $this->ListRecord;
	}
	
	/**
	* digunakan untuk insert data ke dalam tabel
	* @param param sqlString
	* @return void
	*/
	public function insertRecord ($sqlString) { 
		return $this->query($sqlString);
	}
	
	/**
	* Dapatkan jumlah baris dari sebuah tabel
	* @param tableName
	* @return count of rows 
	*/
	public function getCountRowsOfTable ($tableName) {
		$str="SELECT COUNT(*) AS jumlah_baris FROM $tableName";
		$this->setFieldTable(array('jumlah_baris'));
		$result=$this->getRecord($str);
// 		echo $result[1]['jumlah_baris'];
		return $result[1]['jumlah_baris'];
	}
	
	/**
	* untuk mengecek apakah sebuah record sudah ada atau belum berdasarkan primary key
	* @param field dari tabel
	* @param tabel nama tabel
	* @param idrecord nilai id record
	* @param $opt diperlakukan seperti apa
	*/
	public function checkRecordIsExist ($field,$table,$idrecord,$opt="integer") {
		$bool = false;
		if ($idrecord != "") {
			$this->setFieldTable(array($field) );
			if ($opt == "string") {
				$idrecord="'".$idrecord."'";
			}
			$str = "SELECT $field FROM $table WHERE $field='$idrecord'";
// 			echo $str . "<br>";
			$result=$this->getRecord ($str);
		
			if (isset($result[1])) 
				$bool = true;			
		}
		return $bool;
	}
	
	
	/**
	* update record in tabel
	* @return void
	*/
	public function updateRecord ($str) {
		return $this->query ($str);
	}
	
	/**
	* delete record
	* @return void
	*/
	public function deleteRecord ($str) {
		$str = "DELETE FROM ".$str;
		return $this->query ($str);
	}
}
?>
