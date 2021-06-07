<?php

class BaseWS extends TJsonResponse {
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
	/**
	* Object Variable "Database"
	*/
	protected $DB;
	/**
	* Object Variable "Tanggal"	
	*/
	public $TGL;	      
    /**
	* Object Variable "User"	
	*/
	public $Pengguna;  
    /**
	* Object Variable "Logic_DMaster"	
	*/
	public $DMaster;          
    /**
	* Object Variable "Logic_AkaDemik"	
	*/
	public $Demik; 
    /**
	* Object Variable "Logic_KRS"	
	*/
	public $KRS; 
    /**
	* Object Variable "Logic_Kuesioner"	
	*/
	public $Kuesioner;
    /**
	* Object Variable "Logic_Nilai"	
	*/
	public $Nilai; 
    /**
	* Object Variable "Logic_Finance"	
	*/
	public $Finance;
    /**
	* Object Variable "Logic_Forum"	
	*/
	public $Forum;
    /**
	* Object Variable "Logic_Log"	
	*/
	public $Log;	
	/**
     * daftar semester
     * @var array
     */
    protected $semester = array('none'=>' ',1=>'GANJIL',2=>'GENAP',3=>'PENDEK');
	/**
	* Object Payload JSON
	*/
	protected $payload = array('connection'=>-1,'message'=>'INVALID REQUEST');
	public function init($config) {
		parent::init($config);
		//open connection to database	
		$this->linkOpen();	
		//declaring User
		$this->createObj('Pengguna');
	}
	/**
	* digunakan untuk membuka koneksi ke server, dan memilih database
	*
	*/
	private function linkOpen() {
		$this->prepareParameters();
		switch ($this->DbType) {
			case 'postgres' :
				prado::using ('Application.lib.Database.PostgreSQL');
				$this->DB = new PostgreSQL ();
				$config=array("host"=>$this->Host,
							"port"=>$this->DbPort,
							"user"=>$this->UserName,
			 				"password"=>$this->UserPassword,
							"dbname"=>$this->DbName);
			break;
			case 'mysql' :
				prado::using ('Application.lib.Database.MySQL');
				$this->DB = new MySQL ();
				$config=array("host"=>$this->Host,
							"user"=>$this->UserName,
			 				"password"=>$this->UserPassword,
							"dbname"=>$this->DbName);
								
			break;
			default :
				throw new Exception ('No Driver Found.');
		}
		$this->DB->connectDB ($config);
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
     * digunakan untuk membuat berbagai macam object
     */
    public function createObj ($nama_object) {
        switch (strtolower($nama_object)) {
            case 'dmaster' :
            	prado::using('Application.logic.Logic_DMaster');
                $this->DMaster = new Logic_DMaster($this->DB);
            break;                        
            case 'akademik' :
            	prado::using('Application.logic.Logic_Akademik');
                $this->Demik = new Logic_Akademik($this->DB);
            break;    
            case 'krs' :
            	prado::using('Application.logic.Logic_KRS');
                $this->KRS = new Logic_KRS($this->DB);
            break;                        
            case 'kuesioner' :
            	prado::using('Application.logic.Logic_Kuesioner');
                $this->Kuesioner = new Logic_Kuesioner($this->DB);
            break;
            case 'nilai' :
            	prado::using('Application.logic.Logic_Nilai');
                $this->Nilai = new Logic_Nilai($this->DB);
            break;                        
            case 'finance' :
				prado::using('Application.logic.Logic_Finance');
                $this->Finance = new Logic_Finance($this->DB);
            break;  
            case 'pengguna' :
				prado::using('Application.logic.Logic_Users');
                $this->Pengguna = new Logic_Users($this->DB);
            break;
        }
    }  
	/**
	* digunakan	untuk memvalidasi request api
	*/
	public function validate () {
		$headers = getallheaders();
		$data=array();
		if (isset($headers['Username']) && isset($headers['Token'])) {
			$username = addslashes($headers['Username']);
			$token = addslashes($headers['Token']);			
			$ip=explode('.',$_SERVER['REMOTE_ADDR']);		        
			$ipaddress=$ip[0];	       	
			if ($ipaddress == '127' || $ipaddress == '::1') {
				$alamat_ip='127.0.0.1';
			} else {
				$alamat_ip=$_SERVER['REMOTE_ADDR'];
			}
			$str = "SELECT userid,username,token,ipaddress,active FROM user WHERE username='$username' AND token='$token'";
			$this->DB->setFieldTable(array('userid','username','token','ipaddress','active'));
			$r  = $this->DB->getRecord($str);
			if (isset($r[1])) {	
				$data=$r[1];
				$_ip = explode(',',$data['ipaddress']);
				$jumlah_ip=count($_ip);
				$bool=-1;
				for ($i=0;$i<$jumlah_ip;$i+=1) {
					if ($_ip[$i] == $alamat_ip) {
						$bool=1;
					}
				}
				$this->payload['connection'] = $bool;
				if ($bool>0) {
					$this->payload['message']="Username ($username) dan Token ($token) Valid !!!";
				} else {
					$this->payload['status']='11';
					throw new Exception ("Akses dari Alamat IP ($alamat_ip) tidak di ijinkan");						
				}
				
				
			} else {
				$this->payload['status']='11';
				throw new Exception ("Tidak bisa mengeksekusi perintah, karena Username ($username) atau Token ($token) Salah !!!");
			}			
		} else {
			$this->payload['status']='11';
			throw new Exception ("Username atau Token tidak tersedia di header HTTP !!!");			
		}
		$this->Pengguna->setDataUser($data);
	}
	/**
	* generate json content	
	*/
	public function getJsonContent() {		
		return $this->payload;
	}
}