<?php
prado::using ('Application.MainPageM');
class CDetailDulangMHSNonAktif Extends MainPageM {		
	public function onLoad($param) {
		parent::onLoad($param);				
        $this->showSubMenuAkademikDulang=true;
        $this->showDulangMHSNonAktif=true;                
        $this->createObj('Nilai');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            try {
                if (isset($_SESSION['currentPageDulangMHSNonAktif']['DataMHS']['no_formulir'])) {
                    $datamhs=$_SESSION['currentPageDulangMHSNonAktif']['DataMHS'];
                    $this->Nilai->setDataMHS($datamhs);
                    
                    $this->cmbAddTANonAktif->DataSource=array($_SESSION['ta']=>$this->DMaster->getNamaTA($_SESSION['ta']));
                    $this->cmbAddTANonAktif->Text=$_SESSION['ta'];
                    $this->cmbAddTANonAktif->dataBind();
                     				
                    $this->cmbAddSMTNonAktif->DataSource=array($_SESSION['semester']=>$this->setup->getSemester($_SESSION['semester']));
                    $this->cmbAddSMTNonAktif->Text=$_SESSION['semester'];
                    $this->cmbAddSMTNonAktif->dataBind();
                    
                    $this->Nilai->getTranskripFromKRS ();
                    $jumlah_sks=$this->Nilai->getTotalSKSAdaNilai();
                    $iddata_konversi=$datamhs['iddata_konversi'];
                    if ($iddata_konversi > 0) {
                        $jumlah_sks+=$this->DB->getSumRowsOfTable ('sks',"v_konversi2 WHERE iddata_konversi=$iddata_konversi");
                    }
                    $this->literalJumlahSKS->Text=$jumlah_sks;
                    
                    $this->cmbAddDosenWali->DataSource=$this->DMaster->getListDosenWali();
                    $this->cmbAddDosenWali->Text=$datamhs['iddosen_wali'];
                    $this->cmbAddDosenWali->dataBind();	           
                    
                    $this->setInfoToolbar();
                }else{
                    throw new Exception("Data Mahasiswa belum ada di session.");
                }
            } catch (Exception $ex) {
                $this->idProcess='view';	
                $this->errorMessage->Text=$ex->getMessage();
            }
        }	
        $this->Nilai->setDataMHS($_SESSION['currentPageDulangMHSNonAktif']['DataMHS']);
	}
    public function getDataMHS($idx) {		        
        return $this->Nilai->getDataMHS($idx);
    }  
    public function setInfoToolbar() {        
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
        $ta=$this->DMaster->getNamaTA($_SESSION['ta']);		
        $semester = $this->setup->getSemester($_SESSION['semester']);
		$this->lblModulHeader->Text="Program Studi $ps T.A $ta Semester $semester";        
	}
    public function saveData ($sender,$param) {		
		if ($this->IsValid) {	
            $datamhs=$_SESSION['currentPageDulangMHSNonAktif']['DataMHS'];						
			$ta=$this->cmbAddTANonAktif->Text;							
			$semester=$this->cmbAddSMTNonAktif->Text;

            $_SESSION['currentPageDulangMHSNonAktif']['tahun_masuk']=$datamhs['tahun_masuk'];
            $nim=  $datamhs['nim'];           
			$kelas=$datamhs['idkelas'];
            $iddosen_wali=$this->cmbAddDosenWali->Text;
            
			$this->DB->query ('BEGIN');
            $status_sebelumnnya=$datamhs['k_status'];
            $tasmt=$ta.$semester;
			$str = "INSERT INTO dulang (iddulang,nim,tahun,idsmt,tasmt,tanggal,idkelas,status_sebelumnya,k_status) VALUES (NULL,'$nim','$ta','$semester','$tasmt',NOW(),'$kelas','$status_sebelumnnya','N')";
			if ($this->DB->insertRecord($str)) {
                if ($this->cmbAddStatus->Text == 1){
                    $str = "UPDATE register_mahasiswa SET iddosen_wali='$iddosen_wali',k_status='N' WHERE nim='$nim'";			
                    $this->DB->updateRecord($str);                    
                }
                
                $this->DB->deleteRecord("transaksi_cuti WHERE nim='$nim' AND tahun='$ta' AND idsmt='$semester' AND commited=0");

				$this->DB->query('COMMIT');
                unset($_SESSION['currentPageDulangMHSNonAktif']['DataMHS']);
                $this->redirect('dulang.DulangMHSNonAktif',true);
			}else {
				$this->DB->query('ROLLBACK');
			}
		}
	}
    public function closeDetailDulang ($sender,$param) {
        unset($_SESSION['currentPageDulangMHSNonAktif']['DataMHS']);
        $this->redirect('dulang.DulangMHSNonAktif',true);
    }
}