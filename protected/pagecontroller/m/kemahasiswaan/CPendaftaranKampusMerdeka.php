<?php
prado::using ('Application.MainPageM');
class CPendaftaranKampusMerdeka extends MainPageM {
	public function onLoad($param) {		
		parent::onLoad($param);						
        $this->showSubMenuAkademikKemahasiswaan=true;
        $this->showPendaftaranKampusMerdeka=true;
                        
		if (!$this->IsPostBack&&!$this->IsCallBack) {	
            if (!isset($_SESSION['currentPagePendaftaranKampusMerdeka'])||$_SESSION['currentPagePendaftaranKampusMerdeka']['page_name']!='m.kemahasiswaan.PendaftaranKampusMerdeka') {
				$_SESSION['currentPagePendaftaranKampusMerdeka']=array('page_name'=>'m.kemahasiswaan.PendaftaranKampusMerdeka','page_num'=>0,'search'=>false,'DataMHS'=>array());												
			}
            $_SESSION['currentPagePendaftaranKampusMerdeka']['search']=false;
            $this->RepeaterS->PageSize=$this->setup->getSettingValue('default_pagesize');
            
			$this->tbCmbPs->DataSource=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');
			$this->tbCmbPs->Text=$_SESSION['kjur'];
			$this->tbCmbPs->Enabled=true;
			$this->tbCmbPs->dataBind();	
			$this->lblProdi->Text=$_SESSION['daftar_jurusan'][$_SESSION['kjur']];
            
            $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
            $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
            $this->tbCmbOutputReport->DataBind();
            
			$this->populateData();
		}		
	}
    public function changeTbPs ($sender,$param) {		
		$_SESSION['kjur']=$this->tbCmbPs->Text;
        $this->lblProdi->Text=$_SESSION['daftar_jurusan'][$_SESSION['kjur']];        
		$this->populateData();
	}	
	public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}    
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPagePendaftaranKampusMerdeka']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPagePendaftaranKampusMerdeka']['search']);
	}		
    public function searchRecord ($sender,$param) {
		$_SESSION['currentPagePendaftaranKampusMerdeka']['search']=true;
        $this->populateData($_SESSION['currentPagePendaftaranKampusMerdeka']['search']);
	}    
    public function itemCreated ($sender,$param) {
        $item=$param->Item;
		if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem') {         
            $item->lblStatusPendaftaran->Text=$this->DMaster->getStatusPendaftaranKampusMerdeka($item->DataItem['status_daftar']);
            switch ($item->DataItem['status_daftar']) {
                case 0 :
                    $cssclass='label label-info';
                break;
                case 1 :
                    $cssclass='label label-success';
                    $item->btnRepeaterApproved->Enabled=false;                                        
                    $item->btnRepeaterApproved->CssClass="table-link default";
                break;
            }
            $item->lblStatusPendaftaran->CssClass=$cssclass;
        }
    }
	public function populateData ($search=false) {			
        $kjur=$_SESSION['kjur'];        
        if ($search) {
            $str = "SELECT vdm.nim,vdm.nama_mhs,pkm.jumlah_sks,pkm.kjur,,pkm.status_daftar FROM v_datamhs vdm,pendaftaran_kampus_merdeka pkm WHERE pkm.nim=vdm.nim";			
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {                
                case 'nim' :
                    $clausa=" AND vdm.nim='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_datamhs vdm,pendaftaran_kampus_merdeka pkm WHERE pkm.nim=vdm.nim $clausa",'vdm.nim');
                    $str = "$str $clausa";
                break;                
                case 'nama' :
                    $clausa=" AND vdm.nama_mhs LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_datamhs vdm,pendaftaran_kampus_merdeka pkm WHERE pkm.nim=vdm.nim $clausa",'vdm.nim');
                    $str = "$str $clausa";
                break;
            }
        }else{           
            $jumlah_baris=$this->DB->getCountRowsOfTable("pendaftaran_kampus_merdeka pkm WHERE kjur=$kjur",'nim');		            
            $str = "SELECT vdm.nim,vdm.nama_mhs,pkm.jumlah_sks,pkm.kjur,pkm.status_daftar FROM v_datamhs vdm,pendaftaran_kampus_merdeka pkm WHERE pkm.nim=vdm.nim AND pkm.kjur='$kjur'";			
        }		
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPagePendaftaranKampusMerdeka']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=$this->setup->getSettingValue('default_pagesize');$_SESSION['currentPagePendaftaranKampusMerdeka']['page_num']=0;}
        $str = "$str ORDER BY status_daftar ASC,tanggal_daftar DESC LIMIT $offset,$limit";				
        $this->DB->setFieldTable(array('nim','nama_mhs','jumlah_sks','kjur','status_daftar'));
		$r = $this->DB->getRecord($str,$offset+1);	        
        
        $this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();     
        $this->paginationInfo->Text=$this->getInfoPaging($this->RepeaterS);
    }     
    public function approvedFromRepeater($sender,$param) {
        $nim=$this->getDataKeyField($sender,$this->RepeaterS);
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