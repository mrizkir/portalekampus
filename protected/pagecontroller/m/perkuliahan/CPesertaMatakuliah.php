<?php
prado::using ('Application.MainPageM');
class CPesertaMatakuliah extends MainPageM {	
	public function onLoad($param) {		
		parent::onLoad($param);				
         $this->showSubMenuAkademikPerkuliahan=true;
        $this->showPenyelenggaraan=true;
        
        $this->createObj('Akademik');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePesertaMatakuliah'])||$_SESSION['currentPagePesertaMatakuliah']['page_name']!='m.perkuliahan.PesertaMatakuliah') {
				$_SESSION['currentPagePesertaMatakuliah']=array('page_name'=>'m.perkuliahan.PesertaMatakuliah','page_num'=>0,'search'=>false,'InfoMatkul'=>array(),'idkelas'=>'none');
			}  
            $_SESSION['currentPagePesertaMatakuliah']['search']=false;            
            $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
            $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
            $this->tbCmbOutputReport->DataBind();            
            try {                     
                $id=addslashes($this->request['id']);
                $this->RepeaterS->PageSize=$this->setup->getSettingValue('default_pagesize');                
                $this->hiddenid->Value=$id;
                $infomatkul=$this->Demik->getInfoMatkul($id,'penyelenggaraan'); 
                if (!isset($infomatkul['idpenyelenggaraan'])) {                                                
                    throw new Exception ("Kode penyelenggaraan dengan id ($id) tidak terdaftar.");		
                }
                
                $_SESSION['currentPagePesertaMatakuliah']['InfoMatkul']=$infomatkul;                
                $this->tbCmbPs->Enabled=false;
                $this->tbCmbTA->Enabled=false;
                $this->tbCmbSemester->Enabled=false;
                
                $kelas=$this->DMaster->getListKelas();
                $kelas['none']='All';
                $this->cmbKelas->DataSource=$kelas;
                $this->cmbKelas->Text=$_SESSION['currentPagePesertaMatakuliah']['idkelas'];			
                $this->cmbKelas->dataBind();	
                
                $kjur=$_SESSION['kjur'];	
                $this->tbCmbPs->DataSource=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');
                $this->tbCmbPs->Text=$kjur;			
                $this->tbCmbPs->dataBind();	

                $tahun=$_SESSION['ta'];
                $ta=$this->DMaster->removeIdFromArray($this->DMaster->getListTA(),'none');			
                $this->tbCmbTA->DataSource=$ta;					
                $this->tbCmbTA->Text=$tahun;						
                $this->tbCmbTA->dataBind();

                $idsmt=$_SESSION['semester'];
                $semester=$this->DMaster->removeIdFromArray($this->setup->getSemester(),'none');  				
                $this->tbCmbSemester->DataSource=$semester;
                $this->tbCmbSemester->Text=$idsmt;
                $this->tbCmbSemester->dataBind();

                $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
                $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
                $this->tbCmbOutputReport->DataBind();
                
                $this->populateData();
                $this->lblModulHeader->Text=$this->getInfoToolbar();
            } catch (Exception $ex) {
                $this->idProcess='view';        
                
                $this->tbCmbPs->DataSource=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');
                $this->tbCmbPs->Text=$_SESSION['kjur'];			
                $this->tbCmbPs->dataBind();	
                
                $ta=$this->DMaster->removeIdFromArray($this->DMaster->getListTA(),'none');			
                $this->tbCmbTA->DataSource=$ta;					
                $this->tbCmbTA->Text=$_SESSION['ta'];						
                $this->tbCmbTA->dataBind();

                $semester=$this->DMaster->removeIdFromArray($this->setup->getSemester(),'none');  				
                $this->tbCmbSemester->DataSource=$semester;
                $this->tbCmbSemester->Text=$_SESSION['semester'];
                $this->tbCmbSemester->dataBind();
                $this->lblModulHeader->Text=$this->getInfoToolbar();
                $ex->getMessage();
            }
		}		
	}	
    public function getInfoToolbar() {        
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
		$ta=$this->DMaster->getNamaTA($_SESSION['ta']);
		$semester=$this->setup->getSemester($_SESSION['semester']);
		$text="Program Studi $ps TA $ta Semester $semester";
		return $text;
	}
    public function changeTbTA ($sender,$param) {
        $this->idProcess='view';
		$_SESSION['ta']=$this->tbCmbTA->Text;		
        $this->lblModulHeader->Text=$this->getInfoToolbar();
	}	
	public function changeTbSemester ($sender,$param) {
        $this->idProcess='view';
		$_SESSION['semester']=$this->tbCmbSemester->Text;		
        $this->lblModulHeader->Text=$this->getInfoToolbar();		
	}
    public function changeTbPs ($sender,$param) {		
        $this->idProcess='view';
        $_SESSION['kjur']=$this->tbCmbPs->Text;
        $this->lblModulHeader->Text=$this->getInfoToolbar();        
	}
    public function checkKodeMatkul ($sender,$param) {
		$this->idProcess=$sender->getId()=='viewpeserta'?'add':'edit';
        $kmatkul=$param->Value;		
        if ($kmatkul != '') {
            try {   
                $kmatkul=$this->Demik->getIDKurikulum($_SESSION['kjur']).'_'.$kmatkul;                
                $ta=$_SESSION['ta'];
                $idsmt=$_SESSION['semester'];
                $str = "SELECT idpenyelenggaraan FROM penyelenggaraan WHERE kmatkul='$kmatkul' AND  idsmt='$idsmt' AND tahun='$ta'";
                $this->DB->setFieldTable (array('idpenyelenggaraan'));			
                $r= $this->DB->getRecord($str);
                if (isset($r[1])) {                                                    
                    $this->redirect('perkuliahan.PesertaMatakuliah', true, array('id'=>$r[1]['idpenyelenggaraan']));
                }else{
                    throw new Exception ("Kode matakuliah ($kmatkul) tidak diselenggarakan silahkan ganti dengan yang lain.");		
                }                               
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }	
        }	
    }
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}
    public function Page_Changed ($sender,$param) {
		$_SESSION['currentPagePesertaMatakuliah']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPagePesertaMatakuliah']['search']);
	}
    public function searchRecord ($sender,$param) {
		$_SESSION['currentPagePesertaMatakuliah']['search']=true;
		$this->populateData($_SESSION['currentPagePesertaMatakuliah']['search']);
	}
    
    public function changeKelas($sender,$param) {        
        $_SESSION['currentPagePesertaMatakuliah']['idkelas']=$this->cmbKelas->SelectedValue;
        $this->populateData();
    }
    
    public function showPesertaMatkul ($sender,$param) {
        if ($this->IsValid){
            
        }
    }
    public function populateData ($search=false) {      
        $id=$_SESSION['currentPagePesertaMatakuliah']['InfoMatkul']['idpenyelenggaraan'];        
        if ($search) {            
            $str = "SELECT vkm.nim,vdm.nama_mhs,vdm.idkelas,vdm.jk,vdm.tahun_masuk,vkm.batal,vkm.sah FROM v_krsmhs vkm,v_datamhs vdm WHERE vkm.nim=vdm.nim AND idpenyelenggaraan='$id'";
            $txtsearch=$this->txtKriteria->Text;
            switch ($this->cmbKriteria->Text) {                
                case 'nim' :
                    $clausa="AND vdm.nim='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_krsmhs vkm,v_datamhs vdm WHERE vkm.nim=vdm.nim AND idpenyelenggaraan='$id' $clausa",'vdm.nim');
                    $str = "$str $clausa";
                break;
                case 'nirm' :
                    $clausa="AND vdm.nirm='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_krsmhs vkm,v_datamhs vdm WHERE vkm.nim=vdm.nim AND idpenyelenggaraan='$id' $clausa",'vdm.nim');
                    $str = "$str $clausa";
                break;
                case 'nama' :
                    $clausa="AND vdm.nama_mhs LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_krsmhs vkm,v_datamhs vdm WHERE vkm.nim=vdm.nim AND idpenyelenggaraan='$id' $clausa",'vdm.nim');
                    $str = "$str $clausa";
                break;
            }
        }else{ 
            $idkelas=$_SESSION['currentPagePesertaMatakuliah']['idkelas'];
            $str_kelas=($idkelas=='' || $idkelas=='none') ? '' : " AND vdm.idkelas='$idkelas'";
            $str = "SELECT vkm.nim,vdm.nama_mhs,vdm.idkelas,vdm.jk,vdm.tahun_masuk,vkm.batal,vkm.sah FROM v_krsmhs vkm,v_datamhs vdm WHERE vkm.nim=vdm.nim AND idpenyelenggaraan='$id'$str_kelas";            
            
            $jumlah_baris=$this->DB->getCountRowsOfTable("v_krsmhs vkm,v_datamhs vdm WHERE vkm.nim=vdm.nim AND idpenyelenggaraan='$id'$str_kelas",'vdm.nim');
        }		
		$this->RepeaterS->CurrentPageIndex=$_SESSION['currentPagePesertaMatakuliah']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$offset=$this->RepeaterS->CurrentPageIndex*$this->RepeaterS->PageSize;
		$limit=$this->RepeaterS->PageSize;
		if ($offset+$limit>$this->RepeaterS->VirtualItemCount) {
			$limit=$this->RepeaterS->VirtualItemCount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=$this->setup->getSettingValue('default_pagesize');$_SESSION['currentPagePesertaMatakuliah']['page_num']=0;}		
        $str = "$str ORDER BY vdm.nama_mhs ASC LIMIT $offset,$limit";
		$this->DB->setFieldTable(array('nim','nama_mhs','idkelas','jk','tahun_masuk','batal','sah'));	
		$r=$this->DB->getRecord($str,$offset+1);
        $result=array();
        while (list($k,$v)=each($r)) {
            $status='belum disahkan';
            if ($v['sah']==1 && $v['batal']==0) {
                $status='sah';
            }elseif($v['sah']==1 && $v['batal']==1){
                $status='batal';
            }
            $v['status']=$status;
            $result[$k]=$v;
        }
		$this->RepeaterS->DataSource=$result;
		$this->RepeaterS->dataBind();
        
        $this->paginationInfo->Text=$this->getInfoPaging($this->RepeaterS);
	}
    public function printOut($sender,$param) {
         
        $this->createObj('reportakademik');
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
                $dataReport=$_SESSION['currentPagePesertaMatakuliah']['InfoMatkul'];
                $dataReport['nama_tahun'] = $this->DMaster->getNamaTA($dataReport['tahun']);
                $dataReport['nama_semester'] = $this->setup->getSemester($dataReport['idsmt']);               
                $dataReport['idkelas']=$_SESSION['currentPagePesertaMatakuliah']['idkelas'];
                $dataReport['nama_kelas']=  $this->DMaster->getNamaKelasByID($dataReport['idkelas']);
                $dataReport['linkoutput']=$this->linkOutput; 
                $this->report->setDataReport($dataReport); 
                $this->report->setMode($_SESSION['outputreport']);
                $messageprintout="Daftar Peserta Matakuliah : <br/>";
                $this->report->printPesertaMatakuliah($this->DMaster); 
            break;
            case  'pdf' :
                $messageprintout="Mohon maaf Print out pada mode pdf belum kami support.";                
            break;
        }
        $idkelas=$_SESSION['currentPagePesertaMatakuliah']['idkelas'];
        $str_kelas=($idkelas=='' || $idkelas=='none') ? '' : " AND vdm.idkelas='$idkelas'";
        //$label="Daftar Kelas";
        $this->lblMessagePrintout->Text=$messageprintout;
        $this->lblPrintout->Text='Daftar Peserta '. $label=($idkelas=="none") ? 'Semua Kelas' : $this->DMaster->getNamaKelasByID($idkelas) ;
        $this->modalPrintOut->show();
     }
}
?>