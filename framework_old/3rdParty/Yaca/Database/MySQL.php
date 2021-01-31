<?php

require_once 'DBInterface.php';
require_once 'DBGlobal.php';

class MySQL extends DBGlobal implements DBInterface {
	/**
	* digunakan untuk menampung sql string
	*/
	private $sqlString;
	
	/**
	* parameter untuk koneksi	
	*/
	private $paramConn;
	/**
	* digunakan untuk koneksi ke database
	* @param param array (host,dbname,user,password)
	* @return void
	*/
	public function connectDB ($param) { 
		$this->paramConn = $param;
		$this->conn = @ mysql_connect ($param['host'],$param['user'],$param['password']);		
		if ($this->conn) {
			mysql_select_db ($param['dbname'],$this->conn);
		}
	}
	/**
	* digunakan untuk mengeksekusi  perintah sql
	* @param param sqlString
	* @return void
	*/
	public function query ($sqlString) {
		$this->sqlString=$sqlString;		
		if ($result=@ mysql_query($sqlString)) {			
			$this->ResultQuery=$result;
			return $result;
		}else {
			throw new Exception ('Query Failed = '.$sqlString.' ['.mysql_error().']');
		}
	}
	
	
	/**
	* mengambil record dari sebuah tabel dalam bentuk array
	* @param sqlString ini sql string
	* @param offset 
	*
	*/	
	public function getRecord ($sqlString,$offset=1) {				
		if (mysql_num_rows($result=$this->query($sqlString)) >= 1) {
			$ft = $this->getFieldTable('field');			
			$countFieldTable = count ($ft);
			$counter = 1;									
			while ($row=mysql_fetch_array($result)) {			
				$tempRecord['no']=$offset;
				for ($i=0;$i < $countFieldTable;$i++) {
					$tempRecord[$ft[$i]]=trim($row[$ft[$i]]);
				}
				$ListRecord[$counter]=$tempRecord;
				$counter++;			
				$offset++;
			} 		
			$this->sqlString=$sqlString;		
			$this->ListRecord=$ListRecord;									
		}else {
			$this->ListRecord=array();									
		}
		return $this->ListRecord;
	}	
	
	/**
	* Dapatkan jumlah baris dari sebuah tabel
	* @param tableName
	* @return count of rows 
	*/
	public function getCountRowsOfTable ($tableName,$fieldname='*') {
		$str="SELECT COUNT($fieldname) AS jumlah_baris FROM $tableName";				
		$this->sqlString=$str;		
		$this->setFieldTable(array('jumlah_baris'));
		$result=$this->getRecord($str);
		return $result[1]['jumlah_baris'];
	}
	
	/**
	* untuk mengecek apakah sebuah record sudah ada atau belum berdasarkan primary key
	* @param field dari tabel
	* @param tabel nama tabel
	* @param idrecord nilai id record
	* @param $opt diperlakukan seperti apa
	*/
	public function checkRecordIsExist ($field,$table,$idrecord,$opt=null) {
		$bool = false;			
		$this->setFieldTable(array($field) );			
		$str = "SELECT $field FROM $table WHERE $field='$idrecord' $opt";					
		$this->sqlString=$str;		
		$result=$this->getRecord ($str);		
		if (isset($result[1])) {
			$bool = true;		
		}		
		return $bool;
	}
	
	/**
	* digunakan untuk mendapatkan max sebuah nilai
	* @param field dari tabel
	* @param table nama tabel
	* @return Integer
	*/
	public function getMaxOfRecord ($field,$table) {
		$str = "SELECT MAX($field) AS max_record FROM $table";
		$this->sqlString=$str;		
		$this->setFieldTable(array('max_record'));
		$result = $this->getRecord($str);
		if (isset($result[1])) {
			return $result[1]['max_record'];
		}else {
			return false;
		}		
	}
	/**
	* digunakan untuk mendapatkan sum sebuah nilai
	* @param field dari tabel
	* @param table nama tabel
	* @return Integer
	*/
	public function getSumRowsOfTable($field,$table) {
		$str = "SELECT SUM($field) AS sum_record FROM $table";
		$this->sqlString=$str;		
		$this->setFieldTable(array('sum_record'));
		$result = $this->getRecord($str);				
		if (isset($result[1])) {
			$jumlah=$result[1]['sum_record']==''?0:$result[1]['sum_record'];			
		}	
		return $jumlah;
	}	
		
	/**
	* digunakan untuk insert data ke dalam tabel
	* @param param sqlString bila menggunakan Excel2MySQL sqlString menjadi nama tabel
	* @return void
	*/
	public function insertRecord ($sqlString) { 
		$this->sqlString=$sqlString;		
		return $this->query($sqlString);		
	}
	
	/**
	* update record in tabel
	* @return void
	*/
	public function updateRecord ($str) {
		$this->sqlString=$str;
		return $this->query ($str);
	}
	/**
	* digunakan untuk mendapatkan id terakhir
	* @return void
	*/
	public function getLastInsertID () {
		$str = 'SELECT last_insert_id() AS id';
		$this->setFieldTable(array('id'));
		$r=$this->getRecord($str);
		return $r[1]['id'];
	}
	/**
	* delete record
	* @return void
	*/
	public function deleteRecord ($str) {
		$str = "DELETE FROM ".$str;
		$this->sqlString=$str;
		return $this->query ($str);
	}
	public function debugSQLString () {
		return $this->sqlString;
	}
	
	
}
?>