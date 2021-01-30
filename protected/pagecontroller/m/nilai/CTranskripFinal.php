<?php
prado::using ('Application.MainPageM');
class CTranskripFinal extends MainPageM {	
	public function onLoad ($param) {
		parent::onLoad($param);		
        $this->showSubMenuAkademikNilai=true;
        $this->showTranskripFinal=true;    
		$this->createObj('Nilai');
        
		if (!$this->IsPostBack&&!$this->IsCallBack) {			

            if (!isset($_SESSION['currentPageTranskripFinal'])||$_SESSION['currentPageTranskripFinal']['page_name']!='m.nilai.TranskripFinal') {					
                $_SESSION['currentPageTranskripFinal']=array('page_name'=>'m.nilai.TranskripFinal','page_num'=>0,'search'=>false,'tanggal_terbit'=>'none');												
            }
            $_SESSION['currentPageTranskripFinal']['search']=false;
            $this->RepeaterS->PageSize=$this->setup->getSettingValue('default_pagesize');

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

            $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
            $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
            $this->tbCmbOutputReport->DataBind();

            $this->lblModulHeader->Text=$this->getInfoToolbar();			
            $this->populateData();

		}
		
	}
    public function changeTbTA ($sender,$param) {
		$_SESSION['ta']=$this->tbCmbTA->Text;		
        $this->lblModulHeader->Text=$this->getInfoToolbar();
		$this->populateData($_SESSION['currentPageTranskripFinal']['search']);
        
	}	
	public function changeTbSemester ($sender,$param) {
		$_SESSION['semester']=$this->tbCmbSemester->Text;		
        $this->lblModulHeader->Text=$this->getInfoToolbar();
		$this->populateData($_SESSION['currentPageTranskripFinal']['search']);
	}	
    public function changeTbPs ($sender,$param) {		
        $_SESSION['kjur']=$this->tbCmbPs->Text;
        $this->lblModulHeader->Text=$this->getInfoToolbar();
        $this->populateData();
	}
    public function getInfoToolbar() {        
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
		$ta=$this->DMaster->getNamaTA($_SESSION['ta']);
		$semester=$this->setup->getSemester($_SESSION['semester']);
		$text="Program Studi $ps TA $ta Semester $semester";
		return $text;
	}
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageTranskripFinal']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageTranskripFinal']['search']);
	}
    public function searchRecord ($sender,$param) {
		$_SESSION['currentPageTranskripFinal']['search']=true;
		$this->populateData($_SESSION['currentPageTranskripFinal']['search']);
	}
	public function populateData($search=false) {							
        $kjur=$_SESSION['kjur'];
        $ta=$_SESSION['ta'];
        $idsmt=$_SESSION['semester'];                
        if ($search) {
            $str = "SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,nomor_transkrip,predikat_kelulusan,tanggal_lulus,vdm.k_status FROM v_datamhs vdm,transkrip_asli ta WHERE ta.nim=vdm.nim";
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {                
                case 'nim' :
                    $clausa="AND ta.nim='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_datamhs vdm,transkrip_asli ta WHERE ta.nim=vdm.nim $clausa",'ta.nim');
                    $str = "$str $clausa";
                break;
                case 'nirm' :
                    $clausa="AND vdm.nirm='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_datamhs vdm,transkrip_asli ta WHERE ta.nim=vdm.nim $clausa",'ta.nim');
                    $str = "$str $clausa";
                break;
                case 'nama' :
                    $clausa="AND vdm.nama_mhs LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_datamhs vdm,transkrip_asli ta WHERE ta.nim=vdm.nim $clausa",'ta.nim');
                    $str = "$str $clausa";
                break;
            }
        }else{
            $str = "SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,nomor_transkrip,predikat_kelulusan,tanggal_lulus,vdm.k_status FROM v_datamhs vdm,transkrip_asli ta WHERE ta.nim=vdm.nim AND vdm.kjur=$kjur AND ta.tahun=$ta AND ta.idsmt=$idsmt";
            $jumlah_baris=$this->DB->getCountRowsOfTable("v_datamhs vdm,transkrip_asli ta WHERE ta.nim=vdm.nim AND vdm.kjur=$kjur AND ta.tahun=$ta AND ta.idsmt=$idsmt",'ta.nim');				
        }        
		$this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageTranskripFinal']['page_num'];		
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$offset=$this->RepeaterS->CurrentPageIndex*$this->RepeaterS->PageSize;
		$limit=$this->RepeaterS->PageSize;
		if ($offset+$limit>$this->RepeaterS->VirtualItemCount) {
			$limit=$this->RepeaterS->VirtualItemCount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageTranskripFinal']['page_num']=0;}
        $str = "$str ORDER BY vdm.nama_mhs ASC LIMIT $offset,$limit";
		$this->DB->setFieldTable(array('nim','nirm','nama_mhs','nomor_transkrip','predikat_kelulusan','tanggal_lulus','k_status'));
		$result=$this->DB->getRecord($str,$offset+1);
		$this->RepeaterS->DataSource=$result;
		$this->RepeaterS->dataBind();
        
        $this->paginationInfo->Text=$this->getInfoPaging($this->RepeaterS);
	}	
	public function setDataBound ($sender,$param) {
		$item=$param->Item;
		if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem') {
			$nim=$item->DataItem['nim'];			
            $this->Nilai->setDataMHS(array('nim'=>$nim));
            $this->Nilai->getTranskrip(false);            
			$item->lblIpk->Text=$this->Nilai->getIPKAdaNilai();
		}	
	}
	public function checkNim ($sender,$param){		
		$nim=$this->txtNim->Text;
		if ($nim != '') {
			try {				
				$this->Nilai->setNim($nim);
				$r = $this->Nilai->getList("register_mahasiswa WHERE nim='$nim'",array('nim','k_status','tahun','idsmt'));
				$this->Nilai->dataMhs=$r[1];				
				if (!$this->Nilai->isNimExist()) throw new AkademikException ($nim,2);	
				if ($this->Application->getModule ('environment')->checkRequirementTranskripFinal) {
					if ($this->Nilai->dataMhs['k_status']!='L')throw new Exception ("Status ($nim) belum lulus.");
					$awal=$r[1]['tahun'].$r[1]['semester'];
					$akhir=$_SESSION['ta'].$_SESSION['semester'];
					$totalsks=$this->DB->getSumRowsOfTable('sks',"v_nilai WHERE (tasmt BETWEEN $awal AND $akhir) AND nim='$nim' AND n_kual !='E'");
					if ($totalsks <144)throw new Exception ("Pada T.A dan semester ini total SKS ($nim) baru $totalsks harus lebih dari atau sama dengan 144");				
				}
			}catch (AkademikException $e) {
				$sender->ErrorMessage=$e->pesanKesalahan();				
				$param->IsValid=false;
			}catch(Exception $e) {			
				$sender->ErrorMessage=$e->getMessage();				
				$param->IsValid=false;
			}			
		}
	}
	public function printOut ($sender,$param) {		
        $this->createObj('reportnilai');
        $this->linkOutput->Text='';
        $this->linkOutput->NavigateUrl='#';
        $bool=true;
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
                        $messageprintout='Transkrip Final : ';
                        $str = "SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.tempat_lahir,vdm.tanggal_lahir,vdm.nama_ps,vdm.k_status,vdm.idkonsentrasi,k.nama_konsentrasi FROM v_datamhs vdm LEFT JOIN konsentrasi k ON (vdm.idkonsentrasi=k.idkonsentrasi) WHERE nim='$nim'";
                        $this->DB->setFieldTable(array('nim','nirm','nama_mhs','tempat_lahir','tanggal_lahir','nama_ps','k_status','idkonsentrasi','nama_konsentrasi'));
                        $r=$this->DB->getRecord($str);				
                        
                        $dataReport = $r[1];                        
                        if ($dataReport['k_status'] == 'L') {
                            $dataReport['nama_pt_alias']=$this->setup->getSettingValue('nama_pt_alias');
                            $dataReport['nama_konsentrasi']=($dataReport['idkonsentrasi']==0) ? '-':$dataReport['nama_konsentrasi'];
                            $dataReport['nama_jabatan_transkrip']=$this->setup->getSettingValue('nama_jabatan_transkrip');
                            $dataReport['nama_penandatangan_transkrip']=$this->setup->getSettingValue('nama_penandatangan_transkrip');
                            $dataReport['jabfung_penandatangan_transkrip']=$this->setup->getSettingValue('jabfung_penandatangan_transkrip');
                            $dataReport['nidn_penandatangan_transkrip']=$this->setup->getSettingValue('nidn_penandatangan_transkrip');

                            //biasayanya sama sehingga menggunakan yang KHS
                            $dataReport['nama_jabatan_khs']=$this->setup->getSettingValue('nama_jabatan_khs');
                            $dataReport['nama_penandatangan_khs']=$this->setup->getSettingValue('nama_penandatangan_khs');
                            $dataReport['jabfung_penandatangan_khs']=$this->setup->getSettingValue('jabfung_penandatangan_khs');
                            $dataReport['nidn_penandatangan_khs']=$this->setup->getSettingValue('nidn_penandatangan_khs');

                            $str = "SELECT nomor_ijazah,nomor_transkrip,predikat_kelulusan,tanggal_lulus,judul_skripsi,iddosen_pembimbing,iddosen_pembimbing2,iddosen_ketua,iddosen_pemket,tahun,idsmt FROM transkrip_asli WHERE nim='$nim'";
                            $this->DB->setFieldTable(array('nomor_ijazah','nomor_transkrip','predikat_kelulusan','tanggal_lulus','judul_skripsi','iddosen_pembimbing','iddosen_pembimbing2','iddosen_ketua','iddosen_pemket','tahun','idsmt'));
                            $datatranskrip=$this->DB->getRecord($str);                            
                            $datatranskrip[1]['nama_pembimbing1']=$this->DMaster->getNamaDosenPembimbing($datatranskrip[1]['iddosen_pembimbing']);
                            $datatranskrip[1]['nama_pembimbing2']=$this->DMaster->getNamaDosenPembimbing($datatranskrip[1]['iddosen_pembimbing2']);            
                            $dataReport['tanggalterbit']=date('Y-m-d');
                            $dataReport['dataTranskrip']=$datatranskrip[1];  
                            $dataReport['linkoutput']=$this->linkOutput; 
                            $this->report->setDataReport($dataReport); 
                            $this->report->setMode($_SESSION['outputreport']);
                            $this->report->printTranskripFinal($this->Nilai,true);				
                        }else{
                            $bool=false;
                            $errormessage="Mahasiswa dengan NIM ($nim) statusnya belum lulus !!!.";
                        }
                    break;
                }
			break;			
            case 'btnPrintTranskripFinalAll' :                 
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
        if ($bool) {
            $this->lblMessagePrintout->Text=$messageprintout;
            $this->lblPrintout->Text='Transkrip Final';
            $this->modalPrintOut->show();
        }else{
            $this->lblContentMessageError->Text=$errormessage;
            $this->modalMessageError->show();
        }
	}   
}