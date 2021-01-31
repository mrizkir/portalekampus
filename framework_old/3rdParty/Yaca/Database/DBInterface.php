<?php


interface DBInterface {	
	
	/**
	* digunakan untuk koneksi ke database
	*
	*/
	public function connectDB ($param);
	
	/**
	* digunakan untuk mengeksekusi perintah sql
	*
	*/
	public function query ($sqlString);
	
	/**
	* digunakan untuk mendapatkan record
	*
	*/
	public function getRecord ($sqlString);
	
	/**
	* digunakan untuk mendapatkan jumlah baris dari sebuah tabel
	*
	*/
	public function getCountRowsOfTable ($tableName,$fieldname='*');
	
	/**
	* digunakan mengecek apakah sebuah nilai terdapat di dalam tabel
	*
	*/
	public function checkRecordIsExist ($field,$table,$idrecord,$opt="integer");
	
	/**
	* digunakan untuk mendapatkan max sebuah nilai
	*
	*/
	public function getMaxOfRecord ($field,$table);
	
	/**
	* digunakan untuk mendapatkan jumlah nilai dari field pada sebuah tabel
	*
	*/
	public function getSumRowsOfTable ($field,$tableName);
	
	/**
	* digunakan untuk insert data ke tabel
	*
	*/
	public function insertRecord ($sqlString);
	
	/**
	* digunakan untuk update record di tabel
	*/
	public function updateRecord ($sqlString);

	/**
	* digunakan untuk delete record di tabel
	*/
	public function deleteRecord ($sqlString);
}
?>