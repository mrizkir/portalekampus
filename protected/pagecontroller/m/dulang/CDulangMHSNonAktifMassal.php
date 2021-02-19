<?php
prado::using ('Application.MainPageM');
class CDulangMHSNonAktifMassal Extends MainPageM {		
	public function onLoad($param) {
		parent::onLoad($param);				
        $this->showSubMenuAkademikDulang=true;
        $this->showDulangMHSNonAktif=true;     
        $this->createObj('Akademik');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageDulangMHSNonAktifMassal'])||$_SESSION['currentPageDulangMHSNonAktifMassal']['page_name']!='m.dulang.DulangMHSNonAktif') {
				$_SESSION['currentPageDulangMHSNonAktifMassal']=array('page_name'=>'m.dulang.DulangMHSNonAktif','page_num'=>0,'search'=>false,'tahun_masuk'=>$this->setup->getSettingValue('default_ta'),'ta'=>$_SESSION['ta'],'semester'=>$_SESSION['semester']);												
			}
            $_SESSION['currentPageDulangMHSNonAktifMassal']['search']=false;
            
            $this->tbCmbPs->DataSource=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');
            $this->tbCmbPs->Text=$_SESSION['kjur'];			
            $this->tbCmbPs->dataBind();	

            $tahun_masuk=$this->getAngkatan (false);			            
            $this->tbCmbTahunMasuk->DataSource=$tahun_masuk	;					
            $this->tbCmbTahunMasuk->Text=$_SESSION['currentPageDulangMHSNonAktifMassal']['tahun_masuk'];						
            $this->tbCmbTahunMasuk->dataBind();
            $this->labelTahunNonAktif->Text=$this->DMaster->getNamaTA($_SESSION['tahun_masuk']);
            $this->hiddentahunmasukmass->Value=$_SESSION['tahun_masuk'];
            
            $this->tbCmbTA->DataSource=$this->DMaster->removeIdFromArray($this->DMaster->getListTA($this->Pengguna->getDataUser('tahun_masuk')),'none');
            $this->tbCmbTA->Text=$_SESSION['ta'];
            $this->tbCmbTA->dataBind();			
            $this->hiddentamass->Value=$_SESSION['ta'];
            
            $semester=$this->DMaster->removeIdFromArray($this->setup->getSemester(),'none');  				
            $this->tbCmbSemester->DataSource=$semester;
            $this->tbCmbSemester->Text=$_SESSION['semester'];
            $this->tbCmbSemester->dataBind();
            $this->hiddenidsmtmass->Value=$_SESSION['semester'];

            $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
            $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
            $this->tbCmbOutputReport->DataBind();

            $this->tbCmbOutputCompress->DataSource=$this->setup->getOutputCompressType();
            $this->tbCmbOutputCompress->Text= $_SESSION['outputcompress'];
            $this->tbCmbOutputCompress->DataBind();

            $this->populateData();
            $this->setInfoToolbar();
		}	
	}
    public function setInfoToolbar() {        
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
        $ta=$this->DMaster->getNamaTA($_SESSION['ta']);		
        $semester = $this->setup->getSemester($_SESSION['semester']);
		$tahunmasuk=$_SESSION['currentPageDulangMHSNonAktifMassal']['tahun_masuk'] == 'none'?'':'Tahun Masuk '.$this->DMaster->getNamaTA($_SESSION['currentPageDulangMHSNonAktifMassal']['tahun_masuk']);		        
		$this->lblModulHeader->Text="Program Studi $ps T.A $ta Semester $semester $tahunmasuk";        
	}
    public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageDulangMHSNonAktifMassal']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageDulangMHSNonAktifMassal']['search']);
	}
	public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}
	public function changeTbTA ($sender,$param) {				
		$_SESSION['ta']=$this->tbCmbTA->Text;		
        $_SESSION['tahun_masuk']=$_SESSION['ta'];    
		$_SESSION['currentPageDulangMHSNonAktifMassal']['tahun_masuk']=$_SESSION['ta'];
		$this->tbCmbTahunMasuk->DataSource=$this->getAngkatan();
		$this->tbCmbTahunMasuk->Text=$_SESSION['currentPageDulangMHSNonAktifMassal']['tahun_masuk'];
		$this->tbCmbTahunMasuk->dataBind();		
        $this->hiddentamass->Value=$_SESSION['ta'];
        $this->hiddentahunmasukmass->Value=$_SESSION['tahun_masuk'];
        $this->setInfoToolbar();
        $this->labelTahunNonAktif->Text=$this->DMaster->getNamaTA($_SESSION['tahun_masuk']);
		$this->populateData();
	}
	public function changeTbTahunMasuk($sender,$param) {				
		$_SESSION['currentPageDulangMHSNonAktifMassal']['tahun_masuk']=$this->tbCmbTahunMasuk->Text;
        $this->labelTahunNonAktif->Text=$this->DMaster->getNamaTA($_SESSION['tahun_masuk']);
        $this->hiddentahunmasukmass->Value=$_SESSION['tahun_masuk'];
        $this->setInfoToolbar();
		$this->populateData();
	}
	public function changeTbPs ($sender,$param) {		
		$_SESSION['kjur']=$this->tbCmbPs->Text;
        $this->setInfoToolbar();
		$this->populateData();
	}	
	public function changeTbSemester ($sender,$param) {		
		$_SESSION['semester']=$this->tbCmbSemester->Text;        
        $this->setInfoToolbar();
        $this->hiddenidsmtmass->Value=$_SESSION['semester'];
		$this->populateData();
	}
    public function searchRecord ($sender,$param){
        $_SESSION['currentPageDulangMHSNonAktifMassal']['search']=true;
        $this->populateData($_SESSION['currentPageDulangMHSNonAktifMassal']['search']);
    }
    public function populateData($search=false) {
        $ta=$_SESSION['currentPageDulangMHSNonAktifMassal']['ta'];
		$idsmt=$_SESSION['currentPageDulangMHSNonAktifMassal']['semester'];
		$kjur=$_SESSION['kjur'];
		$tahun_masuk=$_SESSION['currentPageDulangMHSNonAktifMassal']['tahun_masuk'];
           
        if ($search) {
            $str = "SELECT vdm.no_formulir,vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.tahun_masuk,vdm.k_status,vdm.idkelas,vdm.iddosen_wali FROM v_datamhs vdm WHERE vdm.nim NOT IN (SELECT nim FROM dulang WHERE idsmt=$idsmt AND tahun=$ta) AND vdm.kjur=$kjur AND vdm.k_status != 'K' AND vdm.k_status!='L' AND vdm.k_status!='D' AND vdm.tahun_masuk=$tahun_masuk";
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {
                case 'no_formulir' :
                    $clausa="AND vdm.no_formulir='$txtsearch'";
                    $str = "$str $clausa";
                break;
                case 'nim' :
                    $clausa="AND d.nim='$txtsearch'";
                    $str = "$str $clausa";
                break;
                case 'nirm' :
                    $clausa="AND vdm.nirm='$txtsearch'";
                    $str = "$str $clausa";
                break;
            }
        }else{
            $str = "SELECT vdm.no_formulir,vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.tahun_masuk,vdm.k_status,vdm.idkelas,vdm.iddosen_wali FROM v_datamhs vdm WHERE vdm.nim NOT IN (SELECT nim FROM dulang WHERE idsmt=$idsmt AND tahun=$ta) AND vdm.kjur=$kjur AND vdm.k_status != 'K' AND vdm.k_status!='L' AND vdm.k_status!='D' AND vdm.tahun_masuk=$tahun_masuk";
        }			        
		$this->DB->setFieldTable(array('no_formulir','nim','nirm','nama_mhs','tahun_masuk','k_status','idkelas','iddosen_wali','idkelas'));
		$result=$this->DB->getRecord("$str ORDER BY vdm.nama_mhs ASC LIMIT 10");
		$this->RepeaterS->DataSource=$result;
		$this->RepeaterS->dataBind();
                
	}
    public function GoMass ($param,$sender) {	
        if ($this->Page->isValid) {            
            $ta=addslashes($this->hiddentamass->value);
            $idsmt=addslashes($this->hiddenidsmtmass->value);
            $this->DB->query ('BEGIN');
            $status=$this->cmbAddStatus->Text;
            foreach ($this->RepeaterS->Items as $inputan) {
                $item=$inputan->hiddenidkelas->getNamingContainer();
                $nim=$this->RepeaterS->DataKeys[$item->getItemIndex()];
                $this->Demik->setDataMHS(array('nim'=>$nim));
                $datadulang=$this->Demik->getDataDulang ($idsmt,$ta);
                if (!isset($datadulang['iddulang'])) {					
                    $this->DB->query ('BEGIN');						
                    $idkelas=$inputan->hiddenidkelas->Value;;
                    $status_sebelumnya=$inputan->hiddenstatus->Value;
                    $tasmt=$ta.$idsmt;
                    $str = "INSERT INTO dulang (iddulang,nim,tahun,idsmt,tasmt,tanggal,idkelas,status_sebelumnya,k_status) VALUES (NULL,'$nim','$ta','$idsmt','$tasmt',NOW(),'$idkelas','$status_sebelumnya','N')";														
                    if ($this->DB->insertRecord($str)) {
                        if ($status == 1) {
                            $str = "UPDATE register_mahasiswa SET k_status='N' WHERE nim='$nim'";			
                            $this->DB->updateRecord($str);   
                        }                       
                        $this->DB->deleteRecord("transaksi_cuti WHERE nim='$nim' AND tahun='$ta' AND idsmt='$idsmt' AND commited=0");
                        $this->DB->query('COMMIT');				
                    }else {
                        $this->DB->query('ROLLBACK');
                    }
                }		
			}
            $this->redirect('dulang.DulangMHSNonAktifMassal',true);
        }
	}
    
}