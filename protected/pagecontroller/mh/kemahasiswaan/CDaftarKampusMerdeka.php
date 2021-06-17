<?php
prado::using ('Application.MainPageMHS');
class CDaftarKampusMerdeka extends MainPageMHS {
	public function onLoad($param) {		
		parent::onLoad($param);		                    
        $this->showDaftarKampusMerdeka=true;    
        $this->createObj('Nilai');
        
		if (!$this->IsPostBack&&!$this->IsCallBack) {              
            if (!isset($_SESSION['currentPageDaftarKampusMerdeka'])||$_SESSION['currentPageDaftarKampusMerdeka']['page_name']!='mh.akademik.DaftarKampusMerdeka') {
				$_SESSION['currentPageDaftarKampusMerdeka']=array('page_name'=>'mh.akademik.DaftarKampusMerdeka','page_num'=>0,'search'=>false);												
			}    
            $this->Nilai->setDataMHS($this->Pengguna->getDataUser()); 
            $this->Nilai->getTranskripFromKonversidanKRS();
            // $this->hiddenJumlahSKS->Value=$this->Nilai->getTotalSKSAdaNilai();            
            $this->hiddenJumlahSKS->Value=60;            
            $this->populateData();
		}        
	}   
    public function populateData () {
        $nim=$this->Pengguna->getDataUser('nim');
        $str = "SELECT jumlah_sks,tahun,idsmt,status_daftar FROM pendaftaran_kampus_merdeka WHERE nim='$nim'";
        $this->DB->setFieldTable(array('jumlah_sks','tahun','idsmt','status_daftar'));
        $r=$this->DB->getRecord($str);
        
        if (isset($r[1])){            
            $this->btnDaftarKampusMerdeka->Enabled=false;            
        }
    }
    public function mendaftarKampusMerdeka ($sender,$param) {
        if ($this->IsValid) {
            $nim=$this->Pengguna->getDataUser('nim');
            $kjur=$this->Pengguna->getDataUser('kjur');
            $jumlah_sks=$this->hiddenJumlahSKS->Value;            
            $ta=$this->setup->getSettingValue('default_ta');
            $semester=$this->setup->getSettingValue('default_semester');
            try {
                $str = "INSERT INTO pendaftaran_kampus_merdeka (nim,kjur,jumlah_sks,tahun,idsmt,tanggal_daftar,status_daftar) VALUES ('$nim',$kjur,$jumlah_sks,$ta,$semester,NOW(),0)";
                $this->DB->insertRecord($str);
                
                $this->redirect('kemahasiswaan.DaftarKampusMerdeka',true);
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
    }
}