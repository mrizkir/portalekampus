<?php
prado::using ('Application.MainPageDW');
class CDetailPKRS extends MainPageDW {		
	/**
	* total SKS
	*/
	public static $totalSKS=0;	
    /**
	* total SKS Batal
	*/
	public static $totalSKSBatal=0;	
	/**
	* total Matakuliah
	*/
	public static $jumlahMatkul=0;	
    /**
	* total Matakuliah Batal
	*/
	public static $jumlahMatkulBatal=0;	
	public function onLoad($param) {
		parent::onLoad($param);	
        $this->showSubMenuAkademikPerkuliahan=true;
        $this->showPKRS = true;
		$this->createObj('KRS');
			
		if (!$this->IsPostBack&&!$this->IsCallback) {
            $this->setInfoToolbar();
            $this->populateData();	
		}	
	}
    public function getDataMHS($idx) {  
        if (isset($_SESSION['currentPagePKRS']['DataMHS'][$idx])) {
            return $_SESSION['currentPagePKRS']['DataMHS'][$idx];
        }
    }
	public function setInfoToolbar() {   
        $ta=$this->DMaster->getNamaTA($_SESSION['ta']);		
        $semester = $this->setup->getSemester($_SESSION['semester']);		
		$this->lblModulHeader->Text="T.A $ta Semester $semester";        
	}	
	private function populateData ($search=false) {
        try {			
            $idkrs=addslashes($this->request['id']);
            $datamhs=$_SESSION['currentPagePKRS']['DataMHS'];
            $datakrs=$_SESSION['currentPagePKRS']['DataKRS']['krs'];
            $this->Page->KRS->DataKRS['krs']=$datakrs;
            if (!isset($datakrs['idkrs'])) {
                throw new Exception ('Mohon kembali ke halaman <a href="'.$this->constructUrl('perkuliahan.PKRS',true).'">ini</a>');
            }
            if ($datakrs['idkrs'] != $idkrs) {
                throw new Exception ('Mohon kembali ke halaman <a href="'.$this->constructUrl('perkuliahan.PKRS',true).'">ini</a>');
            }
            $this->KRS->setDataMHS($datamhs);
            $detailkrs=$this->KRS->getDetailKRS($idkrs);
            $this->RepeaterS->DataSource=$detailkrs;
            $this->RepeaterS->dataBind();
        }catch (Exception $e) {
            $this->idProcess='view';	
			$this->errorMessage->Text=$e->getMessage();	
        }
	}
    public function itemCreated ($sender,$param) {
        $item=$param->Item;
        if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem') { 
            if ($item->DataItem['batal']) {
                CDetailPKRS::$totalSKSBatal+=$item->DataItem['sks'];
                CDetailPKRS::$jumlahMatkulBatal+=1;
				$item->btnToggleStatusMatkul->Text='Sahkan';
				$item->btnToggleStatusMatkul->Attributes->onclick="if(!confirm('Anda ingin mensahkan Matakuliah mahasiswa ini ?')) return false;";					
                $item->btnToggleStatusMatkul->CssClass='table-link'; 
                $item->btnToggleStatusMatkul->Attributes->Title='Sahkan Matakuliah';
                $item->btnToggleStatusMatkul->Text='<span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                        <i class="fa fa-check fa-stack-1x fa-inverse"></i>
                                                    </span>';
            }else {
                CDetailPKRS::$totalSKS+=$item->DataItem['sks'];
                CDetailPKRS::$jumlahMatkul+=1;
				$item->btnToggleStatusMatkul->Attributes->onclick="if(!confirm('Anda ingin membatalkan Matakuliah mahasiswa ini ?')) return false;";						
                $item->btnToggleStatusMatkul->CssClass='table-link danger';
                $item->btnToggleStatusMatkul->Attributes->Title='Batalkan Matakuliah';
                $item->btnToggleStatusMatkul->Text='<span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                        <i class="fa fa-times-circle-o fa-stack-1x fa-inverse"></i>
                                                    </span>';
			}
        }
    }	
    public function toggleStatusMatkul ($sender,$param){
		$datakrs=$_SESSION['currentPagePKRS']['DataKRS']['krs'];
        $idkrs=$datakrs['idkrs'];
        $nim=$datakrs['nim'];
		$idkrsmatkul=$this->getDataKeyField($sender,$this->RepeaterS);		
		$id=explode('_',$sender->CommandParameter);
		$idpenyelenggaraan=$id[1];	
		if ($id[0]==1) {			
			try {
				$str = "SELECT SUM(sks) AS jumlah FROM v_krsmhs WHERE idkrs='$idkrs' AND batal=0";
				$this->DB->setFieldTable(array('jumlah'));
				$r=$this->DB->getRecord($str);	
				$jumlah=$r[1]['jumlah']+$id[2];				
				$maks_sks=$datakrs['maxSKS'];
				if ($jumlah > $maks_sks) {
                    throw new Exception ('Matakuliah, tidak bisa disahkan. Karena telah melebihi batas anda');
                }
                $str = "UPDATE krsmatkul SET batal=0 WHERE idkrsmatkul=$idkrsmatkul";
				$this->DB->updateRecord($str);                
                $this->DB->insertRecord("INSERT INTO pkrs SET nim='$nim',idpenyelenggaraan=$idpenyelenggaraan,tambah=0,hapus=1,batal=0,sah=1,tanggal=NOW()");										
				$this->redirect('perkuliahan.DetailPKRS',true,array('id'=>$idkrs));	
			} catch (Exception $e) {
				$this->modalMessageError->show();
                $this->lblContentMessageError->Text=$e->getMessage();						
			}
		}elseif ($id[0]==0) {		
			$str = "UPDATE krsmatkul SET batal=1 WHERE idkrsmatkul=$idkrsmatkul";			
			$this->DB->updateRecord($str);
            $this->DB->insertRecord("INSERT INTO pkrs SET nim='$nim',idpenyelenggaraan=$idpenyelenggaraan,tambah=0,hapus=0,batal=1,sah=0,tanggal=NOW()");										
			$this->redirect('perkuliahan.DetailPKRS',true,array('id'=>$idkrs));	
		}
		
	}    
	public function hapusMatkul ($sender,$param) {		
		$idkrsmatkul=$this->getDataKeyField($sender,$this->RepeaterS);
        $id=explode('_',$sender->CommandParameter);				
		$idpenyelenggaraan=$id[1];		
        $datakrs=$_SESSION['currentPagePKRS']['DataKRS']['krs'];
        $nim=$datakrs['nim'];
		$this->DB->query ('BEGIN');		
		if ($this->DB->deleteRecord("krsmatkul WHERE idkrsmatkul='$idkrsmatkul'")) {
			$this->DB->deleteRecord("kelas_mhs_detail WHERE idkrsmatkul='$idkrsmatkul'");
            $this->DB->insertRecord("INSERT INTO pkrs SET nim='$nim',idpenyelenggaraan=$idpenyelenggaraan,tambah=0,hapus=1,batal=0,sah=0,tanggal=NOW()");										
			$this->DB->query ('COMMIT');
		}else {
			$this->DB->query ('ROLLBACK');
		}		
		$this->redirect('perkuliahan.DetailPKRS',true,array('id'=>$datakrs['idkrs']));
	}
    public function tambahKRS ($sender,$param) {        
        $this->redirect ('perkuliahan.TambahPKRS',true);
    }
    
	public function closeDetailKRS ($sender,$param) { 
        unset($_SESSION['currentPagePKRS']);
        $this->redirect ('perkuliahan.PKRS',true);
    }
	public function printKRS ($sender,$param) {
        $this->createObj('reportkrs');
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
                $messageprintout="Mohon maaf Print out pada mode excel 2007 belum kami support.";                
            break;
            case  'pdf' :                
                $messageprintout='';                
                $tahun=$_SESSION['ta'];
                $semester=$_SESSION['semester'];
                $nama_tahun = $this->DMaster->getNamaTA($tahun);
                $nama_semester = $this->setup->getSemester($semester);

                $dataReport=$_SESSION['currentPagePKRS']['DataMHS'];
                $dataReport['krs']=$_SESSION['currentPagePKRS']['DataKRS']['krs'];        
                $dataReport['matakuliah']=$_SESSION['currentPagePKRS']['DataKRS']['matakuliah'];        
                $dataReport['nama_tahun']=$nama_tahun;
                $dataReport['nama_semester']=$nama_semester;        
                
                $kaprodi=$this->KRS->getKetuaPRODI($dataReport['kjur']);                  
                $dataReport['nama_kaprodi']=$kaprodi['nama_dosen'];
                $dataReport['jabfung_kaprodi']=$kaprodi['nama_jabatan'];
                $dataReport['nipy_kaprodi']=$kaprodi['nipy'];
                $dataReport['nidn_kaprodi']=$kaprodi['nidn'];
                
                $dataReport['linkoutput']=$this->linkOutput;                 
                $this->report->setDataReport($dataReport); 
                $this->report->setMode($_SESSION['outputreport']);
                $this->report->printKRS();				

                
            break;
        }
        $this->lblMessagePrintout->Text=$messageprintout;
        $this->lblPrintout->Text="Kartu Rencana Studi T.A $nama_tahun Semester $nama_semester";
        $this->modalPrintOut->show();
	}
}