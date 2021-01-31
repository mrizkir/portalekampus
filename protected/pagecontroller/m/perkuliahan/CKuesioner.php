<?php
prado::using ('Application.MainPageM');
class CKuesioner extends MainPageM {	
	public function onLoad($param) {		
		parent::onLoad($param);				
		$this->showSubMenuAkademikPerkuliahan=true;
        $this->showKuesioner=true;        
        $this->createObj('Kuesioner');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageKuesioner'])||$_SESSION['currentPageKuesioner']['page_name']!='m.perkuliahan.Kuesioner') {
				$_SESSION['currentPageKuesioner']=array('page_name'=>'m.perkuliahan.Kuesioner','page_num'=>0,'search'=>false);
			}  
            $_SESSION['currentPageKuesioner']['search']=false;
            $_SESSION['currentPageDetailKuesioner']=array();
            $this->RepeaterS->PageSize=$this->setup->getSettingValue('default_pagesize');

			$this->tbCmbPs->DataSource=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');
            $this->tbCmbPs->Text=$_SESSION['kjur'];			
            $this->tbCmbPs->dataBind();	
            
            $this->tbCmbTA->DataSource=$this->DMaster->removeIdFromArray($this->DMaster->getListTA($this->Pengguna->getDataUser('tahun_masuk')),'none');
			$this->tbCmbTA->Text=$_SESSION['ta'];
			$this->tbCmbTA->dataBind();
            
            $semester=$this->DMaster->removeIdFromArray($this->setup->getSemester(),'none');  				
			$this->tbCmbSemester->DataSource=$semester;
			$this->tbCmbSemester->Text=$_SESSION['semester'];
			$this->tbCmbSemester->dataBind();
            
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
        $ta=$_SESSION['ta'];		
        $semester = $this->setup->getSemester($_SESSION['semester']);
		$ta='T.A '.$this->DMaster->getNamaTA($_SESSION['ta']);		        
		$this->lblModulHeader->Text="Program Studi $ps $ta Semester $semester";
        
	}
	public function changeTbTA ($sender,$param) {				
		$_SESSION['ta']=$this->tbCmbTA->Text;				
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
		$this->populateData();
	}	
	public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageKuesioner']['page_num']=$param->NewPageIndex;
		$this->populateData();
	}
    public function searchRecord ($sender,$param) {
		$_SESSION['currentPageKuesioner']['search']=true;
		$this->populateData($_SESSION['currentPageKuesioner']['search']);
	}
	public function populateData($search=false) {				
		$ta=$_SESSION['ta'];
		$idsmt=$_SESSION['semester'];
		$kjur=$_SESSION['kjur'];		
        if ($search) {            
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) { 
                case 'kmatkul' :
                    $clausa=" AND kmatkul LIKE '%$txtsearch%'";                    
                    $str="SELECT vpp.idpengampu_penyelenggaraan,vpp.idpenyelenggaraan,kmatkul,nmatkul,sks,semester,iddosen,nidn,nama_dosen, 0 AS jumlahmhs FROM v_pengampu_penyelenggaraan vpp WHERE EXISTS (SELECT 1 FROM kuesioner_jawaban WHERE idpengampu_penyelenggaraan=vpp.idpengampu_penyelenggaraan) AND vpp.idsmt='$idsmt' AND vpp.tahun='$ta' AND vpp.kjur='$kjur'$clausa";
                    $jumlah_baris=$this->DB->getCountRowsOfTable("v_pengampu_penyelenggaraan vpp WHERE EXISTS (SELECT 1 FROM kuesioner_jawaban WHERE idpengampu_penyelenggaraan=vpp.idpengampu_penyelenggaraan) AND vpp.idsmt='$idsmt' AND vpp.tahun='$ta' AND vpp.kjur='$kjur'$clausa",'vpp.idpengampu_penyelenggaraan');						
                break;				
                case 'nmatkul':
                    $clausa=" AND nmatkul LIKE '%$txtsearch%'";
                    $str="SELECT vpp.idpengampu_penyelenggaraan,vpp.idpenyelenggaraan,kmatkul,nmatkul,sks,semester,iddosen,nidn,nama_dosen, 0 AS jumlahmhs FROM v_pengampu_penyelenggaraan vpp WHERE EXISTS (SELECT 1 FROM kuesioner_jawaban WHERE idpengampu_penyelenggaraan=vpp.idpengampu_penyelenggaraan) AND vpp.idsmt='$idsmt' AND vpp.tahun='$ta' AND vpp.kjur='$kjur'$clausa";
                    $jumlah_baris=$this->DB->getCountRowsOfTable("v_pengampu_penyelenggaraan vpp WHERE EXISTS (SELECT 1 FROM kuesioner_jawaban WHERE idpengampu_penyelenggaraan=vpp.idpengampu_penyelenggaraan) AND vpp.idsmt='$idsmt' AND vpp.tahun='$ta' AND vpp.kjur='$kjur'$clausa",'vpp.idpengampu_penyelenggaraan');						
                break;
                case 'nama_dosen':
                    $clausa=" AND nama_dosen LIKE '%$txtsearch%'";
                    $str="SELECT vpp.idpengampu_penyelenggaraan,vpp.idpenyelenggaraan,kmatkul,nmatkul,sks,semester,iddosen,nidn,nama_dosen, 0 AS jumlahmhs FROM v_pengampu_penyelenggaraan vpp WHERE EXISTS (SELECT 1 FROM kuesioner_jawaban WHERE idpengampu_penyelenggaraan=vpp.idpengampu_penyelenggaraan) AND vpp.idsmt='$idsmt' AND vpp.tahun='$ta' AND vpp.kjur='$kjur'$clausa";
                    $jumlah_baris=$this->DB->getCountRowsOfTable("v_pengampu_penyelenggaraan vpp WHERE EXISTS (SELECT 1 FROM kuesioner_jawaban WHERE idpengampu_penyelenggaraan=vpp.idpengampu_penyelenggaraan) AND vpp.idsmt='$idsmt' AND vpp.tahun='$ta' AND vpp.kjur='$kjur'$clausa",'vpp.idpengampu_penyelenggaraan');						
                break;                
            }
        }else{            
            $str="SELECT vpp.idpengampu_penyelenggaraan,vpp.idpenyelenggaraan,kmatkul,nmatkul,sks,semester,iddosen,nidn,nama_dosen, 0 AS jumlahmhs FROM v_pengampu_penyelenggaraan vpp WHERE EXISTS (SELECT 1 FROM kuesioner_jawaban WHERE idpengampu_penyelenggaraan=vpp.idpengampu_penyelenggaraan) AND vpp.idsmt='$idsmt' AND vpp.tahun='$ta' AND vpp.kjur='$kjur'";
            $jumlah_baris=$this->DB->getCountRowsOfTable("v_pengampu_penyelenggaraan vpp WHERE EXISTS (SELECT 1 FROM kuesioner_jawaban WHERE idpengampu_penyelenggaraan=vpp.idpengampu_penyelenggaraan) AND vpp.idsmt='$idsmt' AND vpp.tahun='$ta' AND vpp.kjur='$kjur'",'vpp.idpengampu_penyelenggaraan');						
        }
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageKuesioner']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$offset=$this->RepeaterS->CurrentPageIndex*$this->RepeaterS->PageSize;
		$limit=$this->RepeaterS->PageSize;
		if ($offset+$limit>$this->RepeaterS->VirtualItemCount) {
			$limit=$this->RepeaterS->VirtualItemCount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=$this->setup->getSettingValue('default_pagesize');$_SESSION['currentPageKuesioner']['page_num']=0;}
        $str="$str ORDER BY nmatkul ASC LIMIT $offset,$limit";				
		$this->DB->setFieldTable (array('idpengampu_penyelenggaraan','idpenyelenggaraan','kmatkul','nmatkul','sks','semester','iddosen','nidn','nama_dosen','jumlahmhs'));			
		$r=$this->DB->getRecord($str);	
        $result=array();        
        while (list($k,$v)=each($r)) {
            $idpengampu_penyelenggaraan=$v['idpengampu_penyelenggaraan'];                                    
            $str="SELECT n_kual FROM kuesioner_hasil WHERE idpengampu_penyelenggaraan=$idpengampu_penyelenggaraan";				
            $this->DB->setFieldTable (array('n_kual'));			
            $r2=$this->DB->getRecord($str);	
            if (isset($r2[1])) {
                $v['hasil']=$r2[1]['n_kual'];
                $v['commandparameter']='update';
            }else{
                $v['hasil']='N.A';
                $v['commandparameter']='insert';
            }             
            $result[$k]=$v;
        }      
		$this->RepeaterS->DataSource=$result;
		$this->RepeaterS->dataBind();
        
        $this->paginationInfo->Text=$this->getInfoPaging($this->RepeaterS);
	}
    public function hitungKuesioner ($sender,$param) {
        $idpengampu_penyelenggaraan = $this->getDataKeyField($sender,$this->RepeaterS); 
        $this->Kuesioner->hitungKuesioner($idpengampu_penyelenggaraan,$sender->CommandParameter);
        $this->redirect('perkuliahan.Kuesioner', true);
    }
    
    public function printOut ($sender,$param) {		
        $this->linkOutput->Text='';
        $this->linkOutput->NavigateUrl='#';
		switch ($_SESSION['outputreport']) {
            case  'summarypdf' :
                $messageprintout="Mohon maaf Print out pada mode summary pdf tidak kami support.";                
            break;
            case  'summaryexcel' :
                $messageprintout="Mohon maaf Print out pada mode summary excel tidak kami support.";                
            break;
            case  'pdf' :
                $messageprintout="Mohon maaf Print out pada mode pdf belum kami support.";                
            break;
            case  'excel2007' :
                $tahun=$_SESSION['ta'];
                $semester=$_SESSION['semester'];
                $nama_tahun = $this->DMaster->getNamaTA($tahun);
                $nama_semester = $this->setup->getSemester($semester);

                $dataReport['ta']=$tahun;                
                $dataReport['semester']=$semester;
                $dataReport['nama_tahun']=$nama_tahun;
                $dataReport['nama_semester']=$nama_semester; 
                $dataReport['kjur']=$_SESSION['kjur'];
                $dataReport['nama_ps']=$_SESSION['daftar_jurusan'][$_SESSION['kjur']];
                
                $dataReport['linkoutput']=$this->linkOutput; 
                $objKuesioner=$this->getLogic('ReportKuesioner');
                $objKuesioner->setDataReport($dataReport); 
                $objKuesioner->setMode('excel2007');               
                $objKuesioner->printSummaryKuesioner($this->Kuesioner);
                $messageprintout="";
                
            break;
        }        
        $this->lblMessagePrintout->Text=$messageprintout;
        $this->lblPrintout->Text='Hasil Kuesioner';
        $this->modalPrintOut->show();
    }
}