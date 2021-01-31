<?php
prado::using ('Application.MainPageDW');
class CDetailTranskripKurikulum extends MainPageDW {		
	public function onLoad($param) {
		parent::onLoad($param);							
		$this->showSubMenuAkademikNilai=true;
        $this->showTranskripKurikulum=true;    
        $this->createObj('Nilai');
        
		if (!$this->IsPostback&&!$this->IsCallback) {
            if (!isset($_SESSION['currentPageDetailTranskripKurikulum'])||$_SESSION['currentPageDetailTranskripKurikulum']['page_name']!='dw.nilai.DetailTranskripKurikulum') {
				$_SESSION['currentPageDetailTranskripKurikulum']=array('page_name'=>'dw.nilai.DetailTranskripKurikulum','page_num'=>0,'search'=>false,'DataMHS'=>array());												                                               
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
                $_SESSION['currentPageDetailTranskripKurikulum']['DataMHS']=array();
                throw new Exception("Mahasiswa dengan NIM ($nim) tidak terdaftar.");
            }
            $datamhs=$r[1];
            if ($datamhs['iddosen_wali']!=$this->iddosen_wali){
                $_SESSION['currentPageDetailTranskripKurikulum']['DataMHS']=array();
                throw new Exception("Mahasiswa dengan NIM ($nim) dimiliki oleh Mahasiswa diluar perwalian Anda.");
            }
            $datamhs['nama_dosen']=$this->DMaster->getNamaDosenWaliByID ($datamhs['iddosen_wali']);
            $datamhs['nkelas']=$this->DMaster->getNamaKelasByID($datamhs['idkelas']);
            $datamhs['nama_konsentrasi']=($datamhs['idkonsentrasi']==0) ? '-':$datamhs['nama_konsentrasi'];                    
            $datamhs['status']=$this->DMaster->getNamaStatusMHSByID($datamhs['k_status']);
            $datamhs['iddata_konversi']=$this->Nilai->isMhsPindahan($nim,true);
            $_SESSION['currentPageDetailTranskripKurikulum']['DataMHS']=$datamhs;
            $this->Nilai->setDataMHS($datamhs);
            $transkrip = $this->Nilai->getTranskrip();            
            
            $this->RepeaterS->DataSource=$transkrip;
            $this->RepeaterS->dataBind();		
        } catch (Exception $ex) {
            $this->idProcess='view';	
			$this->errorMessage->Text=$ex->getMessage();
        }        
	}
	public function printOut ($sender,$param) {	
        $this->createObj('reportnilai');             		
        $dataReport=$_SESSION['currentPageDetailTranskripKurikulum']['DataMHS'];  
        $dataReport['nama_pt_alias']=$this->setup->getSettingValue('nama_pt_alias');
        $dataReport['nama_jabatan_transkrip']=$this->setup->getSettingValue('nama_jabatan_transkrip');
        $dataReport['nama_penandatangan_transkrip']=$this->setup->getSettingValue('nama_penandatangan_transkrip');
        $dataReport['jabfung_penandatangan_transkrip']=$this->setup->getSettingValue('jabfung_penandatangan_transkrip');
        $dataReport['nipy_penandatangan_transkrip']=$this->setup->getSettingValue('nipy_penandatangan_transkrip');
        $dataReport['nidn_penandatangan_transkrip']=$this->setup->getSettingValue('nidn_penandatangan_transkrip');
        
        $dataReport['linkoutput']=$this->linkOutput; 
        $this->report->setDataReport($dataReport); 
        $this->report->setMode($_SESSION['outputreport']);
		$this->report->printTranskripKurikulum($this->Nilai,true);				
        
        $this->lblPrintout->Text='Transkrip Kurikulum';
        $this->modalPrintOut->show();
	}
}