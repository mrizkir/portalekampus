<?php
prado::using ('Application.MainPageM');
class CDetailTranskripFinal extends MainPageM {		
	public function onLoad($param) {
		parent::onLoad($param);				
		$this->showSubMenuAkademikNilai=true;
        $this->showTranskripFinal=true;    
        $this->createObj('Nilai');
        
		if (!$this->IsPostback&&!$this->IsCallback) {
            if (!isset($_SESSION['currentPageDetailTranskripFinal'])||$_SESSION['currentPageDetailTranskripFinal']['page_name']!='m.nilai.DetailTranskripFinal') {
				$_SESSION['currentPageDetailTranskripFinal']=array('page_name'=>'m.nilai.DetailTranskripFinal','page_num'=>0,'search'=>false,'DataMHS'=>array(),'DataTranskrip');
			}  
            $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
            $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
            $this->tbCmbOutputReport->DataBind();
			$this->populateData();	
		}
	}
    public function getDataMHS($idx) {		        
        return $this->Nilai->getDataMHS($idx);
    }
	protected function populateData() {		
        try {
            $nim=addslashes($this->request['id']);            				
            $str = "SELECT vdm.no_formulir,vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,vdm.tempat_lahir,vdm.tanggal_lahir,vdm.kjur,vdm.nama_ps,vdm.idkonsentrasi,k.nama_konsentrasi,vdm.tahun_masuk,iddosen_wali,idkelas,k_status FROM v_datamhs vdm LEFT JOIN konsentrasi k ON (vdm.idkonsentrasi=k.idkonsentrasi) WHERE nim='$nim'";
            $this->DB->setFieldTable(array('no_formulir','nim','nirm','nama_mhs','jk','tempat_lahir','tanggal_lahir','kjur','nama_ps','idkonsentrasi','nama_konsentrasi','tahun_masuk','iddosen_wali','idkelas','k_status'));
            $r=$this->DB->getRecord($str);				
            
            if (!isset($r[1])) {
                $_SESSION['currentPageDetailTranskripFinal']['DataMHS']=array();
                $_SESSION['currentPageDetailTranskripFinal']['DataTranskrip']=array();
                throw new Exception("Mahasiswa dengan NIM ($nim) tidak terdaftar.");
            }
            $datamhs=$r[1];
            $datamhs['nama_dosen']=$this->DMaster->getNamaDosenWaliByID ($datamhs['iddosen_wali']);
            $datamhs['nkelas']=$this->DMaster->getNamaKelasByID($datamhs['idkelas']);
            $datamhs['nama_konsentrasi']=($datamhs['idkonsentrasi']==0) ? '-':$datamhs['nama_konsentrasi'];                    
            $datamhs['status']=$this->DMaster->getNamaStatusMHSByID($datamhs['k_status']);
            $datamhs['iddata_konversi']=$this->Nilai->isMhsPindahan($nim,true);
            $this->Nilai->setDataMHS($datamhs);
            $_SESSION['currentPageDetailTranskripFinal']['DataMHS']=$datamhs;
            
            $str = "SELECT nomor_ijazah,nomor_transkrip,predikat_kelulusan,tanggal_lulus,judul_skripsi,iddosen_pembimbing,iddosen_pembimbing2,iddosen_ketua,iddosen_pemket,tahun,idsmt FROM transkrip_asli WHERE nim='$nim'";
            $this->DB->setFieldTable(array('nomor_ijazah','nomor_transkrip','predikat_kelulusan','tanggal_lulus','judul_skripsi','iddosen_pembimbing','iddosen_pembimbing2','iddosen_ketua','iddosen_pemket','tahun','idsmt'));
            $datatranskrip=$this->DB->getRecord($str);
            if (!isset($datatranskrip[1]) ) {
                $_SESSION['currentPageDetailTranskripFinal']['DataMHS']=array();
                $_SESSION['currentPageDetailTranskripFinal']['DataTranskrip']=array();
                throw new Exception("Mahasiswa dengan NIM ($nim) tidak terdaftar di Transkrip Final.");
            }
            $datatranskrip[1]['nama_pembimbing1']=$this->DMaster->getNamaDosenPembimbing($datatranskrip[1]['iddosen_pembimbing']);
            $datatranskrip[1]['nama_pembimbing2']=$this->DMaster->getNamaDosenPembimbing($datatranskrip[1]['iddosen_pembimbing2']);            
            $_SESSION['currentPageDetailTranskripFinal']['DataTranskrip']=$datatranskrip[1];
            
            $this->hiddennomortranskrip->Value=$datatranskrip[1]['nomor_transkrip'];			
			$this->txtEditNomorTranskrip->Text=$datatranskrip[1]['nomor_transkrip'];			
			$this->cmbEditPredikatKelulusan->Text=$datatranskrip[1]['predikat_kelulusan'];			
			$this->txtEditTanggalLulus->Text=$this->TGL->tanggal('d-m-Y',$datatranskrip[1]['tanggal_lulus']);
            $daftar_dosen=$this->DMaster->removeIdFromArray($this->DMaster->getDaftarDosen(),'none');
            $this->cmbEditDosenPembimbing->DataSource=$daftar_dosen;
            $this->cmbEditDosenPembimbing->dataBind();            
			$this->cmbEditDosenPembimbing->Text=$datatranskrip[1]['iddosen_pembimbing'];
            
            $this->cmbEditDosenPembimbing2->DataSource=$daftar_dosen;
            $this->cmbEditDosenPembimbing2->dataBind();
			$this->cmbEditDosenPembimbing2->Text=$datatranskrip[1]['iddosen_pembimbing2'];												
            
			$this->txtEditJuduluSkripsi->Text=$datatranskrip[1]['judul_skripsi'];						            
            
            $transkrip = $this->Nilai->getTranskrip(false);            
            
            $this->RepeaterS->DataSource=$transkrip;
            $this->RepeaterS->dataBind();		
            
        } catch (Exception $ex) {
            $this->idProcess='view';	
			$this->errorMessage->Text=$ex->getMessage();
        }        
	}
	public function printOut ($sender,$param) {	
        $this->createObj('reportnilai');          
        $this->linkOutput->Text='';
        $this->linkOutput->NavigateUrl='#';   
        
        $dataReport=$_SESSION['currentPageDetailTranskripFinal']['DataMHS']; 
        $nim=$dataReport['nim'];
        if ($dataReport['k_status'] == 'L') {
            switch ($_SESSION['outputreport']) {
                case  'summarypdf' :
                    $messageprintout="Mohon maaf Print out pada mode summary pdf tidak kami support.";                
                break;
                case  'summaryexcel' :
                    $messageprintout="Mohon maaf Print out pada mode summary excel tidak kami support.";                
                break;
                case  'excel2007' :
                    $messageprintout="Mohon maaf Print out pada mode excel 2007 belum kami support.";                
                break;
                case 'pdf' :                    
                    $messageprintout='Transkrip Final : ';                     
                    $dataReport['nama_pt_alias']=$this->setup->getSettingValue('nama_pt_alias');
                    $dataReport['nama_jabatan_transkrip']=$this->setup->getSettingValue('nama_jabatan_transkrip');
                    $dataReport['nama_penandatangan_transkrip']=$this->setup->getSettingValue('nama_penandatangan_transkrip');
                    $dataReport['jabfung_penandatangan_transkrip']=$this->setup->getSettingValue('jabfung_penandatangan_transkrip');
                    $dataReport['nidn_penandatangan_transkrip']=$this->setup->getSettingValue('nidn_penandatangan_transkrip');

                    //biasayanya sama sehingga menggunakan yang KHS
                    $dataReport['nama_jabatan_khs']=$this->setup->getSettingValue('nama_jabatan_khs');
                    $dataReport['nama_penandatangan_khs']=$this->setup->getSettingValue('nama_penandatangan_khs');
                    $dataReport['jabfung_penandatangan_khs']=$this->setup->getSettingValue('jabfung_penandatangan_khs');
                    $dataReport['nidn_penandatangan_khs']=$this->setup->getSettingValue('nidn_penandatangan_khs');
                    $dataReport['tanggalterbit']=date ('Y-m-d',$this->txtViewTanggalTerbit->TimeStamp);
                    $dataReport['dataTranskrip']=$_SESSION['currentPageDetailTranskripFinal']['DataTranskrip'];  
                    $dataReport['linkoutput']=$this->linkOutput; 
                    $this->report->setDataReport($dataReport); 
                    $this->report->setMode($_SESSION['outputreport']);
                    $this->report->printTranskripFinal($this->Nilai,true);				
                break;
            }
            $this->lblMessagePrintout->Text=$messageprintout;
            $this->lblPrintout->Text='Transkrip Final';
            $this->modalPrintOut->show();
        }else{
            $this->lblContentMessageError->Text="Mahasiswa dengan NIM ($nim) statusnya belum lulus !!!.";
            $this->modalMessageError->show();
        }
	}    
}