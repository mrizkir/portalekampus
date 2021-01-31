<?php
/**
* Ini file digunakan untuk koneksi ke database
*
* @author Mochammad Rizki Romdoni <m_rizki_r>@yahoo.com
* @date start Senin 29 Desember 2008 / 01 Muharram 1430 H Modified ...
* 
*/
class DBFactory extends TModule {
	/**
	* link
	*/
	private $Link;
	
	/**
	* host database
	*/
	private $Host;
	
	/**
	* Port Server
	*/
	private $DbPort;
	
	/**
	* user database
	*/
	private $UserName;
	
	/**
	* user password
	*/
	private $UserPassword;
	
	/**
	* db name
	*/
	private $DbName;
	
	/**
	* Tipe Database => Postgres, MySQL, dll
	*/
	private $DbType;
	
	public function init ($config) {
		$this->linkOpen();	
	}
	
	/**
	* digunakan untuk membuka koneksi ke server, dan memilih database
	*
	*/
	private function linkOpen() {
		$this->prepareParameters();
		switch ($this->DbType) {
			case 'postgres' :
				prado::using ('System.3rdParty.Yaca.Database.PostgreSQL');
				$this->Link = new PostgreSQL ();
				$config=array("host"=>$this->Host,
							"port"=>$this->DbPort,
							"user"=>$this->UserName,
			 				"password"=>$this->UserPassword,
							"dbname"=>$this->DbName);
			break;
			case 'mysql' :
				prado::using ('System.3rdParty.Yaca.Database.MySQL');
				$this->Link = new MySQL ();
				$config=array("host"=>$this->Host,
							"user"=>$this->UserName,
			 				"password"=>$this->UserPassword,
							"dbname"=>$this->DbName);
								
			break;
			default :
				throw new Exception ('No Driver Found.');
		}
		$this->Link->connectDB ($config);
	}
	
	/**
	* menyiapkan beberapa paramaters
	*
	*/
	private function prepareParameters () {
		$db=$this->Application->getParameters ();		
		$this->Host = $db['db_host'];
		$this->UserName=$db['db_username'];
		$this->UserPassword=$db['db_userpassword'];
		$this->DbName=$db['db_name'];
		$this->DbType=$db['db_type'];
		$this->DbPort=$db['db_port'];			
	}
	
	/**
	* mendapatkna link dari tiap koneksi
	*
	*/
	public function getLink () {
		return $this->Link;
	}
	
	
}
?>