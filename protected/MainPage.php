<?php

class MainPage extends TPage {   
	/**
	* id process
	*/
	public $idProcess;	
	/**
	* Object Variable "Database"
	*
	*/
	public $DB;		
	/**
	* Object Variable "Setup"
	*
	*/
	public $setup;		
	/**
	* Object Variable "Tanggal"	
	*/
	public $TGL;	  
    /**
	* Object Variable "Report"	
	*/
	public $report;
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
     * show sidebar menu
     */
    public $showSideBarMenu=true;
    /**     
     * show page dashboard
     */
    public $showDashboard=false;  
    /**     
     * show sub menu [datamaster]
     */
    public $showDMaster=false;
    /**     
     * show page pengumuman
     */
    public $showPengumuman=false;
    /**     
     * show page forum diskusi
     */
    public $showForumDiskusi=false;  
    /**     
     * show menu Akademik
     */
    public $showMenuAkademik=false;
    /**     
     * show sub menu [akademik kemahasiswaan]
     */
    public $showSubMenuAkademikKemahasiswaan=false;
    /**     
     * show sub page [akademik kemahasiswaan]
     */
    public $showProfilMahasiswa=false;
	/**
     * show page daftar mahasiswa [akademik kemahasiswaan]
     */
    public $showDaftarMahasiswa=false;
    /**     
     * show sub menu akademik daftar ulang [akademik daftar ulang]
     */
    public $showSubMenuAkademikDulang=false;
    /**     
     * show sub menu akademik daftar ulang [akademik daftar ulang mahasiswa lama]
     */
    public $showDulangMHSLama=false;
    /**     
     * show sub menu akademik perkuliahan
     */
    public $showSubMenuAkademikPerkuliahan=false;
    /**     
     * show page pembagian kelas [perkuliahan]
     */
    public $showPembagianKelas=false; 
    /**     
     * show page Jadwal Perkuliahan [perkuliahan]
     */
    public $showJadwalPerkuliahan=false;
    /**     
     * show page KRS [perkuliahan]
     */
    public $showKRS=false;
    /**     
     * show page KRS Merdeka [perkuliahan]
     */
    public $showKRSMerdeka=false;
    /**     
     * show page Kartu Ujian Mahasiswa [perkuliahan]
     */
    public $showKUM=false;
    /**     
     * show page kuesioner [perkuliahan]
     */
    public $showKuesioner=false;
    /**     
     * show sub menu [akademik nilai]
     */
    public $showSubMenuAkademikNilai=false;
    /**     
     * show page KHS [akademik nilai]
     */
    public $showKHS=false;
    /**     
     * show page KHS Ekstension [akademik nilai]
     */
    public $showKHSEkstension=false;
    /**     
     * show page DPNA [akademik nilai]
     */
    public $showDPNA=false;
    /**     
     * show page Evaluasi Hasil Belajar [akademik nilai]
     */
    public $showEvaluasiHasilBelajar=false;
    /**     
     * show page transkrip sementara [akademik nilai]
     */
    public $showTranskripKurikulum=false;
    /**     
     * show page transkrip KRS [akademik nilai]
     */
    public $showTranskripKRS=false;
    /**     
     * show sub menu [report]
     */
    public $showReport=false;
    /**     
     * show profiles [settings]
     */
    public $showProfiles=false;    
    /**
     * untuk menampilkan forum diskusi home [forumdiskusi]
     * @var boolean
     */
    public $showForumDiskusiHome=false;
    /**
     * untuk menampilkan forum diskusi baru [forumdiskusi]
     * @var boolean
     */
    public $showForumDiskusiBaru=false;
    
	public function OnPreInit ($param) {	
		parent::onPreInit ($param);
		//instantiasi database		
		$this->DB = $this->Application->getModule ('db')->getLink();		
        //instantiasi fungsi setup
        $this->setup = $this->getLogic('Setup');                        
        //instantiasi fungsi setup
        $this->DMaster = $this->getLogic('DMaster');                        
        //instantiasi user
		$this->Pengguna = $this->getLogic('Users');
        //setting templates dan theme yang aktif        
        $theme=isset($_SESSION['theme'])?$_SESSION['theme']:'default';
        $this->Theme=$theme;
        $this->MasterClass="Application.layouts.$theme.MainTemplate";				
	}
	public function onLoad ($param) {		
		parent::onLoad($param);						            
		//instantiasi fungsi tanggal
		$this->TGL = $this->getLogic ('Penanggalan');        
	}
	/**
	* mendapatkan lo object
	* @return obj	
	*/
	public function getLogic ($_class=null) {
		if ($_class === null)
			return $this->Application->getModule ('logic');
		else 
			return $this->Application->getModule ('logic')->getInstanceOfClass($_class);	
	}
	/**
	* id proses tambah, delete, update,show
	*/
	protected function setIdProcess ($sender,$param) {		
		$this->idProcess=$sender->getId();
	}
	
	/**
	* add panel
	* @return boolean
	*/
	public function getAddProcess ($disabletoolbars=true) {
		if ($this->idProcess == 'add') {			
			if ($disabletoolbars)$this->disableToolbars();
			return true;
		}else {
			return false;
		}
	}
	
	/**
	* edit panel
	* @return boolean
	*/
	public function getEditProcess ($disabletoolbars=true) {
		if ($this->idProcess == 'edit') {			
			if ($disabletoolbars)$this->disableToolbars();
			return true;
		}else {
			return false;
		}

	}
	
	/**
	* view panel
	* @return boolean
	*/
	public function getViewProcess ($disabletoolbars=true) {
		if ($this->idProcess == 'view') {
			if ($disabletoolbars)$this->disableToolbars();			
			return true;
		}else {
			return false;
		}

	}
	
	/**
	* default panel
	* @return boolean
	*/
	public function getDefaultProcess () {
		if ($this->idProcess == 'add' || $this->idProcess == 'edit'|| $this->idProcess == 'view') {
			return false;
		}else {
			return true;
		}
	}	
	/**
	* digunakan untuk mendapatkan sebuah data key dari repeater
	* @return data key
	*/
	protected function getDataKeyField($sender,$repeater) {
		$item=$sender->getNamingContainer();
		return $repeater->DataKeys[$item->getItemIndex()];
	}    
    /**
	* Redirect
	*/
	public function redirect ($page,$automaticpage=false,$param=array()) {
		$this->Response->Redirect($this->constructUrl($page,$automaticpage,$param));	
	}	 
    /**
     * digunakan untuk mendapatkan angkatan
     * @param type $tanpanone
     * @return type
     */
	public function getAngkatan ($tanpanone=true) {
		$dt =$this->DMaster->getListTA();		        
		$ta=$_SESSION['ta'];		
		$tahun_akademik=$tanpanone==true?array('none'=>'All'):array();
		while (list($k,$v)=each ($dt)) {
			if ($k != 'none') {
				if ($k <= $ta) {
					$tahun_akademik[$k]=$v;
				}
			}			
		}        
        $_SESSION['tahun_masuk']=$_SESSION['tahun_masuk'] < $ta ?  $_SESSION['tahun_masuk']:$ta;
		return $tahun_akademik;
	}
    /**
     * digunakan untuk membuat url
     */
    public function constructUrl($page,$automaticpage=false,$param=array()) {              
        $url=$page;
        if ($automaticpage) {
            $this->Pengguna = $this->getLogic('Users');
            $tipeuser=$this->Pengguna->getTipeUser(); 
            $theme=$_SESSION['theme'];
            $url="$tipeuser.$theme.$url";
        }        
        return $this->Service->constructUrL($url,$param);
    }
    /**
     * digunakan untuk mendapatkan informasi paging
     */
    public function getInfoPaging ($repeater) {
        $str='';
        if ($repeater->Items->Count() > 0) {
            $jumlah_baris=$repeater->VirtualItemCount;
            $currentPage=$repeater->CurrentPageIndex;
            $offset=$currentPage*$repeater->PageSize;
            $awal=$offset+1;        
            $akhir=$repeater->Items->Count()+$offset;
            $str="Menampilkan $awal hingga $akhir dari $jumlah_baris";        
        }
        return $str;
    }
    /**
     * digunakan untuk merubah outputreport
     * @param type $sender
     * @param type $param
     */
    public function changeOutputReport ($sender,$param) {
        if ($this->IsValid) {
            $_SESSION['outputreport']=$this->tbCmbOutputReport->Text;
        }
    }
    /**
     * digunakan untuk mengganti tipe kompresi output
     * @param type $sender
     * @param type $param
     */
    public function changeOutputCompress ($sender,$param) {
        if ($this->IsValid) {
            $_SESSION['outputcompress']=$this->tbCmbOutputCompress->Text;
        }
    }
    /**
     * digunakan untuk membuat berbagai macam object
     */
    public function createObj ($nama_object) {
        switch (strtolower($nama_object)) {
            case 'dmaster' :
                $this->DMaster = $this->getLogic('DMaster');
            break;                        
            case 'akademik' :
                $this->Demik = $this->getLogic('Akademik');
            break;    
            case 'krs' :
                $this->KRS = $this->getLogic('KRS');
            break;                        
            case 'kuesioner' :
                $this->Kuesioner = $this->getLogic('Kuesioner');
            break;
            case 'nilai' :
                $this->Nilai = $this->getLogic('Nilai');
            break;                        
            case 'finance' :
                $this->Finance = $this->getLogic('Finance');
            break;                        
            case 'report' :
                $this->report = $this->getLogic('Report');
            break;
            case 'reportspmb' :
                $this->report = $this->getLogic('ReportSPMB');
            break;
            case 'reportakademik' :
                $this->report = $this->getLogic('ReportAkademik');
            break;
            case 'reportkrs' :
                $this->report = $this->getLogic('ReportKRS');
            break;
            case 'reportnilai' :
                $this->report = $this->getLogic('ReportNilai');
            break;
            case 'reportfinance' :
                $this->report = $this->getLogic('ReportFinance');
            break;
            case 'forum' :
                $this->Forum = $this->getLogic('Forum');                
            break; 
            case 'log' :
                $this->Log = $this->getLogic('Log');
                $this->Log->getIdLogMaster();
            break;        
        }
    }    
}