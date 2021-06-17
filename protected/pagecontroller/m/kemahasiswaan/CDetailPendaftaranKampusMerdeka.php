<?php
prado::using ('Application.MainPageM');
class CDetailPendaftaranKampusMerdeka Extends MainPageM {		
	public function onLoad($param) {
		parent::onLoad($param);				
        $this->showSubMenuAkademikKemahasiswaan=true;
        $this->showPendaftaranKampusMerdeka=true;                
        $this->createObj('Nilai');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            $this->lblProdi->Text=$_SESSION['daftar_jurusan'][$_SESSION['kjur']];
            try {
                $nim=$this->request['id'];                
                $str = "SELECT vdm.no_formulir,vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,vdm.tempat_lahir,vdm.tanggal_lahir,vdm.kjur,vdm.nama_ps,vdm.is_merdeka,vdm.idkonsentrasi,k.nama_konsentrasi,vdm.tahun_masuk,iddosen_wali,vdm.idkelas,vdm.k_status FROM v_datamhs vdm LEFT JOIN konsentrasi k ON (vdm.idkonsentrasi=k.idkonsentrasi) WHERE vdm.nim='$nim'";
                $this->DB->setFieldTable(array('no_formulir','nim','nirm','nama_mhs','jk','tempat_lahir','tanggal_lahir','kjur','nama_ps','is_merdeka','idkonsentrasi','nama_konsentrasi','tahun_masuk','iddosen_wali','idkelas','k_status'));
                $r=$this->DB->getRecord($str);
                if (!isset($r[1])) {                                
                    throw new Exception ("Mahasiswa dengan NIM ($nim) tidak terdaftar di Database, silahkan ganti dengan yang lain.");		
                }
                $datamhs=$r[1];
                $datamhs['nama_dosen']=$this->DMaster->getNamaDosenWaliByID ($datamhs['iddosen_wali']);
                $datamhs['nkelas']=$this->DMaster->getNamaKelasByID($datamhs['idkelas']);
                $datamhs['nama_konsentrasi']=($datamhs['idkonsentrasi']==0) ? '-':$datamhs['nama_konsentrasi'];                    
                $datamhs['status']=$this->DMaster->getNamaStatusMHSByID($datamhs['k_status']);
                $_SESSION['currentPagePendaftaranKampusMerdeka']['DataMHS']=$datamhs;                
                
                $str = "SELECT jumlah_sks,tahun,idsmt,status_daftar FROM pendaftaran_kampus_merdeka WHERE nim='$nim'";
                $this->DB->setFieldTable(array('jumlah_sks','tahun','idsmt','status_daftar'));
                $r=$this->DB->getRecord($str);
                if (!isset($r[1])) {                                
                    throw new Exception ("Mahasiswa dengan NIM ($nim) belum memilih mendaftar sebagai mahasiswa merdeka.");		
                }
                if ($r[1]['status_daftar']==1) {                    
                    $this->btnApproved->Enabled=false;
                    $this->btnApproved->CssClass='btn';
                }                
                $this->Nilai->setDataMHS($datamhs);
                $this->Nilai->getTranskripFromKonversidanKRS();
                $this->hiddenJumlahSKS->Value=$this->Nilai->getTotalSKSAdaNilai();                
            } catch (Exception $ex) {
                $this->idProcess='view';	
                $this->errorMessage->Text=$ex->getMessage();
            }
		}	
	}    
    public function getDataMHS($idx) {		        
        return $this->Nilai->getDataMHS($idx);
    }
    public function approved($sender,$param) {
        $nim=$_SESSION['currentPagePendaftaranKampusMerdeka']['DataMHS']['nim'];        
        $this->DB->query('BEGIN');
        $str = "UPDATE pendaftaran_kampus_merdeka SET status_daftar=1 WHERE nim='$nim'";        
        if ($this->DB->updateRecord($str)) {            
            $str = "UPDATE register_mahasiswa SET is_merdeka=1 WHERE nim='$nim'";        
            $this->DB->updateRecord($str);
            $this->DB->query('COMMIT');
            $this->redirect('kemahasiswaan.PendaftaranKampusMerdeka',true);
        }else{
            $this->DB->query('ROLLBACK');
        }
        
    }
}