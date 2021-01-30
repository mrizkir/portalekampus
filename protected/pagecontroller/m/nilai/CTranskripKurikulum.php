<?php
prado::using ('Application.MainPageM');
class CTranskripKurikulum extends MainPageM {	
	public function onLoad ($param) {
		parent::onLoad($param);		
        $this->showSubMenuAkademikNilai=true;
        $this->showTranskripKurikulum=true;    
		$this->createObj('Nilai');
                
		if (!$this->IsPostBack&&!$this->IsCallBack) {			
            if (!isset($_SESSION['currentPageTranskripKurikulum'])||$_SESSION['currentPageTranskripKurikulum']['page_name']!='m.nilai.TranskripKurikulum') {
				$_SESSION['currentPageTranskripKurikulum']=array('page_name'=>'m.nilai.TranskripKurikulum','page_num'=>0,'search'=>false,'tahun_masuk'=>$_SESSION['ta']);
			}          
            $_SESSION['currentPageTranskripKurikulum']['search']=false;
            $this->RepeaterS->PageSize=$this->setup->getSettingValue('default_pagesize');
            
            $this->tbCmbPs->DataSource=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');
			$this->tbCmbPs->Text=$_SESSION['kjur'];			
			$this->tbCmbPs->dataBind();				
            
            $tahun_masuk=$this->DMaster->removeIdFromArray($this->DMaster->getListTA(),'none');			            
			$this->tbCmbTahunMasuk->DataSource=$tahun_masuk	;					
			$this->tbCmbTahunMasuk->Text=$_SESSION['currentPageTranskripKurikulum']['tahun_masuk'];						
			$this->tbCmbTahunMasuk->dataBind();
            
            $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
            $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
            $this->tbCmbOutputReport->DataBind();
            
            $this->tbCmbOutputCompress->DataSource=$this->setup->getOutputCompressType();
            $this->tbCmbOutputCompress->Text= $_SESSION['outputcompress'];
            $this->tbCmbOutputCompress->DataBind();
            
            $this->lblModulHeader->Text=$this->getInfoToolbar();
            $this->populateData();			
		}		
	}
    public function changeTbPs ($sender,$param) {		
		$_SESSION['kjur']=$this->tbCmbPs->Text;        
        $this->lblModulHeader->Text=$this->getInfoToolbar();
		$this->populateData();
	}
	public function changeTbTahunMasuk($sender,$param) {    				
		$_SESSION['currentPageTranskripKurikulum']['tahun_masuk']=$this->tbCmbTahunMasuk->Text;		        
        $this->lblModulHeader->Text=$this->getInfoToolbar();
		$this->populateData();
	}
    public function getInfoToolbar() {        
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
		$tahunmasuk=$this->DMaster->getNamaTA($_SESSION['currentPageTranskripKurikulum']['tahun_masuk']);		
		$text="Program Studi $ps Tahun Masuk $tahunmasuk";
		return $text;
	}
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageTranskripKurikulum']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageTranskripKurikulum']['search']);
	}
    public function searchRecord ($sender,$param) {
		$_SESSION['currentPageTranskripKurikulum']['search']=true;
		$this->populateData($_SESSION['currentPageTranskripKurikulum']['search']);
	}
	public function populateData($search=false) {			
        $kjur=$_SESSION['kjur'];      
        $tahun_masuk=$_SESSION['currentPageTranskripKurikulum']['tahun_masuk'];        
        if ($search) {
            $str = "SELECT nim,nama_mhs,jk,kjur,tahun_masuk,idkonsentrasi FROM v_datamhs";			
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {                
                case 'nim' :
                    $clausa="WHERE nim='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_datamhs $clausa",'nim');
                    $str = "$str $clausa";
                break;
                case 'nirm' :
                    $clausa="WHERE nirm='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_datamhs $clausa",'nim');
                    $str = "$str $clausa";
                break;
                case 'nama' :
                    $clausa="WHERE nama_mhs LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_datamhs $clausa",'nim');
                    $str = "$str $clausa";
                break;
            }
        }else{                        
            $jumlah_baris=$this->DB->getCountRowsOfTable("v_datamhs WHERE kjur=$kjur AND tahun_masuk=$tahun_masuk",'nim');		
            $str = "SELECT nim,nama_mhs,jk,kjur,tahun_masuk,idkonsentrasi FROM v_datamhs WHERE kjur=$kjur AND tahun_masuk=$tahun_masuk";			
        }		
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageTranskripKurikulum']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=$this->setup->getSettingValue('default_pagesize');$_SESSION['currentPageTranskripKurikulum']['page_num']=0;}
        $str = "$str ORDER BY nama_mhs ASC LIMIT $offset,$limit";				
        $this->DB->setFieldTable(array('nim','nama_mhs','jk','kjur','idkonsentrasi','tahun_masuk'));
		$r = $this->DB->getRecord($str,$offset+1);	
        $result = array();
        while (list($k,$v)=each($r)) {
            $nim=$v['nim'];
            $dataMHS['nim']=$nim;
            $dataMHS['tahun_masuk']=$v['tahun_masuk'];
            $dataMHS['kjur']=$v['kjur'];
            $dataMHS['iddata_konversi']=$this->Nilai->isMhsPindahan($nim,true);
            $dataMHS['idkonsentrasi']=$v['idkonsentrasi'];
            $this->Nilai->setDataMHS($dataMHS);
            $v['konsentrasi']=strtoupper($this->DMaster->getNamaKonsentrasiByID($v['idkonsentrasi'],$v['kjur']));
            $this->Nilai->getTranskripNilaiKurikulum();
            $v['matkul']=$this->Nilai->getTotalMatkulAdaNilai();
            $v['sks']=$this->Nilai->getTotalSKSAdaNilai();
            $v['ips']=$this->Nilai->getIPSAdaNilai();            
            $result[$k]=$v;
        }
        $this->RepeaterS->DataSource=$result;
		$this->RepeaterS->dataBind();     
        $this->paginationInfo->Text=$this->getInfoPaging($this->RepeaterS);

	}		
	public function printOut ($sender,$param) {		
        $this->createObj('reportnilai');
        $this->linkOutput->Text='';
        $this->linkOutput->NavigateUrl='#';
		switch ($sender->getId()) {
			case 'btnPrintOutR' :
                $nim = $this->getDataKeyField($sender,$this->RepeaterS);				
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
                    
                        $str = "SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.tempat_lahir,vdm.tanggal_lahir,vdm.kjur,vdm.nama_ps,vdm.idkonsentrasi,k.nama_konsentrasi,vdm.tahun_masuk FROM v_datamhs vdm LEFT JOIN konsentrasi k ON (vdm.idkonsentrasi=k.idkonsentrasi) WHERE nim='$nim'";
                        $this->DB->setFieldTable(array('no_formulir','nim','nirm','nama_mhs','tempat_lahir','tanggal_lahir','kjur','nama_ps','idkonsentrasi','nama_konsentrasi','tahun_masuk'));
                        $r=$this->DB->getRecord($str);					

                        $dataReport = $r[1];                    
                        $dataReport['nama_konsentrasi']=($dataReport['idkonsentrasi']==0) ? '-':$dataReport['nama_konsentrasi'];                    
                        $dataReport['iddata_konversi']=$this->Nilai->isMhsPindahan($nim,true);
                        $dataReport['nama_pt_alias']=$this->setup->getSettingValue('nama_pt_alias');
                        $dataReport['nama_jabatan_transkrip']=$this->setup->getSettingValue('nama_jabatan_transkrip');
                        $dataReport['nama_penandatangan_transkrip']=$this->setup->getSettingValue('nama_penandatangan_transkrip');
                        $dataReport['jabfung_penandatangan_transkrip']=$this->setup->getSettingValue('jabfung_penandatangan_transkrip');
                        $dataReport['nipy_penandatangan_transkrip']=$this->setup->getSettingValue('nipy_penandatangan_transkrip');
                        $dataReport['cek_isikuesioner']=false;
                        $dataReport['linkoutput']=$this->linkOutput; 
                        $this->report->setDataReport($dataReport); 
                        $this->report->setMode($_SESSION['outputreport']);

                        $messageprintout="Transkrip Kurikulum $nim : <br/>";
                        $this->report->printTranskripKurikulum($this->Nilai,true);				
                    break;
                }                
			break;			
            case 'btnPrintTranskripKurikulumAll' :
                 switch ($_SESSION['outputreport']) {
                    case  'summarypdf' :
                        $messageprintout="Mohon maaf Print out pada mode summary pdf belum kami support.";                
                    break;
                    case  'summaryexcel' :
                        $messageprintout="Mohon maaf Print out pada mode summary excel belum kami support.";                
                    break;
                    case  'excel2007' :
                        $messageprintout="Mohon maaf Print out pada mode excel 2007 tidak kami support.";                
                    break;
                    case 'pdf' :
                        $messageprintout="Mohon maaf Print out pada mode pdf tidak kami support.";                                            
                    break;
                }
            break;
		}		
        $this->lblMessagePrintout->Text=$messageprintout;
        $this->lblPrintout->Text='Transkrip Kurikulum';
        $this->modalPrintOut->show();
	}
}