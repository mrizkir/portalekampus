<?php
prado::using ('Application.MainPageM');
class CPIN extends MainPageM {
    public $DataUjian;
	public function onLoad($param) {
		parent::onLoad($param);			
		$this->showPIN=true;
        $this->createObj('Akademik');
		if (!$this->IsPostBack && !$this->IsCallBack) {	
            if (!isset($_SESSION['currentPagePIN'])||$_SESSION['currentPagePIN']['page_name']!='m.spmb.PIN') {
				$_SESSION['currentPagePIN']=array('page_name'=>'m.spmb.PIN','page_num'=>0,'offset'=>0,'limit'=>0,'search'=>false,'display_record'=>'all');												
			}
            $_SESSION['currentPagePIN']['search']=false;
            $this->RepeaterS->PageSize=$this->setup->getSettingValue('default_pagesize');
            
            $tahun_masuk=$this->DMaster->removeIdFromArray($this->DMaster->getListTA(),'none');			
			$this->tbCmbTahunMasuk->DataSource=$tahun_masuk	;					
			$this->tbCmbTahunMasuk->Text=$_SESSION['tahun_masuk'];						
			$this->tbCmbTahunMasuk->dataBind();
            
            $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
            $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
            $this->tbCmbOutputReport->DataBind();
            
            $this->cmbDisplayRecord->Text=$_SESSION['currentPagePIN']['display_record'];
            $this->lblModulHeader->Text=$this->getInfoToolbar();            
            $this->populateData ();	
		}	
	}   
	
	public function changeTbTahunMasuk($sender,$param) {					
		$_SESSION['tahun_masuk']=$this->tbCmbTahunMasuk->Text;
        $_SESSION['currentPagePIN']['passinggrade']=$this->DMaster->getDataPassingGrade($_SESSION['tahun_masuk']);
        $this->lblModulHeader->Text=$this->getInfoToolbar();
		$this->populateData();
	}	
	public function getInfoToolbar() {                
		$tahunmasuk=$this->DMaster->getNamaTA($_SESSION['tahun_masuk']);		
		$text="Tahun Masuk $tahunmasuk";
		return $text;
	}
	public function searchRecord ($sender,$param) {
		$_SESSION['currentPagePIN']['search']=true;
		$this->populateData($_SESSION['currentPagePIN']['search']);
	}	
    public function changeDisplay($sender,$param) {        
        $_SESSION['currentPagePIN']['display_record']=$this->cmbDisplayRecord->Text;
        $this->populateData();
    }
	public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPagePIN']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPagePIN']['search']);
	}		
	public function populateData ($search=false) {	
        $tahun_masuk=$_SESSION['tahun_masuk'];    
        if ($search) {        
            $str = "SELECT pin.no_pin,pin.no_formulir,fp.nama_mhs,fp.no_formulir AS ket FROM pin LEFT JOIN formulir_pendaftaran fp ON (fp.no_formulir=pin.no_formulir) WHERE pin.tahun_masuk=$tahun_masuk";
            $txtsearch=$this->txtKriteria->Text;
            switch ($this->cmbKriteria->Text) {
                case 'no_formulir' :
                    $clausa=" AND fp.no_formulir='$txtsearch'"; 
                break;
                case 'nama_mhs' :
                    $clausa=" AND fp.nama_mhs LIKE '%$txtsearch%'";                    
                break;
            }
            $str="$str $clausa";
            $jumlah_baris=$this->DB->getCountRowsOfTable ("pin LEFT JOIN formulir_pendaftaran fp ON (fp.no_formulir=pin.no_formulir) WHERE tahun_masuk=$tahun_masuk $clausa",'no_pin');		
        }else{
            $str_display='';
            if ($_SESSION['currentPagePIN']['display_record']=='terdaftar'){
                $str_display='AND fp.no_formulir IS NOT NULL';
            }elseif ($_SESSION['currentPagePIN']['display_record']=='belum_terdaftar'){
                $str_display='AND fp.no_formulir IS NULL';
            }
            $str = "SELECT pin.no_pin,pin.no_formulir,fp.nama_mhs,fp.no_formulir AS ket FROM pin LEFT JOIN formulir_pendaftaran fp ON (fp.no_formulir=pin.no_formulir) WHERE pin.tahun_masuk=$tahun_masuk $str_display";
            $jumlah_baris=$this->DB->getCountRowsOfTable ("pin LEFT JOIN formulir_pendaftaran fp ON (fp.no_formulir=pin.no_formulir) WHERE tahun_masuk=$tahun_masuk $str_display",'no_pin');		
        }
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPagePIN']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$offset=$this->RepeaterS->CurrentPageIndex*$this->RepeaterS->PageSize;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$this->RepeaterS->VirtualItemCount) {
			$limit=$this->RepeaterS->VirtualItemCount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=$this->setup->getSettingValue('default_pagesize');$_SESSION['currentPagePIN']['page_num']=0;}
		
		$str = "$str  $str_display ORDER BY pin.no_formulir ASC LIMIT $offset,$limit";
		$this->DB->setFieldTable(array('no_pin','no_formulir','nama_mhs','ket'));
        $r = $this->DB->getRecord($str,$offset+1);
        
        $this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();	
        $this->paginationInfo->Text=$this->getInfoPaging($this->RepeaterS); 
	} 
    public function generatePIN ($sender,$param) {
        if ($this->IsValid) { 
            $tahun_masuk=$_SESSION['tahun_masuk'];
            $max_record=$this->DB->getMaxOfRecord('no_formulir',"pin WHERE tahun_masuk='$tahun_masuk'")+1;		
			$urut=substr($max_record,strlen($tahun_masuk),4);		
			$no_urut=($urut=='')?'0001':$urut;
			$no_urut=$tahun_masuk.$no_urut;                        
            $jumlah=addslashes($this->txtJumlahFormulir->Text);
            $jumlah_formulir=$no_urut+$jumlah;
            if ($jumlah <= 1) {                        
                $no_pin=$no_urut.mt_rand(100000,999999);
                $values="('$no_pin',$no_urut,$tahun_masuk)";
            }else {
                for ($i=$no_urut;$i<$jumlah_formulir;$i++) {                    
                    $no_pin=$i.mt_rand(100000,999999);
                    if ($jumlah_formulir > $i+1) {
                        $values=$values."('$no_pin',$i,$tahun_masuk),";
                    }else {
                        $values=$values."('$no_pin',$i,$tahun_masuk)";
                    }
                }
            }
            $str="INSERT INTO pin (no_pin,no_formulir,tahun_masuk) VALUES $values";
            $this->DB->insertRecord($str);
            $this->redirect('spmb.PIN',true);
        }
    }
    public function printOut($sender,$param) {
        $this->createObj('reportspmb');
        $this->linkOutput->Text='';
        $this->linkOutput->NavigateUrl='#';
        switch ($_SESSION['outputreport']) {
            case  'summarypdf' :
                $messageprintout="Mohon maaf Print out pada mode summary pdf tidak kami support.";                
            break;
            case  'summaryexcel' :
                $messageprintout="Mohon maaf Print out pada mode summary excel tidak kami support.";                
            break;
            case  'excel2007' :
                $messageprintout="";
                $dataReport['tahun_masuk']=$_SESSION['tahun_masuk'];
                $dataReport['pilihan']=$_SESSION['currentPagePIN']['display_record'];
                $dataReport['linkoutput']=$this->linkOutput;
                $this->report->setDataReport($dataReport); 
                $this->report->setMode($_SESSION['outputreport']);
                $this->report->printPIN(); 
            break;
            case  'pdf' :
                $messageprintout="Mohon maaf Print out pada mode pdf belum kami support.";                
            break;
        }
        $this->lblMessagePrintout->Text=$messageprintout;
        $this->lblPrintout->Text='Daftar PIN '.strtoupper($_SESSION['currentPagePIN']['display_record']);
        $this->modalPrintOut->show();
    }
}
?>