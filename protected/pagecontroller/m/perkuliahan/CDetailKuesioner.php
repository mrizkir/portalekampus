<?php
prado::using ('Application.MainPageM');
class CDetailKuesioner extends MainPageM {	
    public $DataKuesioner;
	public function onLoad($param) {
		parent::onLoad($param);	
        $this->showSubMenuAkademikPerkuliahan=true;
        $this->showKuesioner = true;           
        $this->createObj('Kuesioner');
		if (!$this->IsPostBack&&!$this->IsCallback) {	
            if (!isset($_SESSION['currentPageDetailKuesioner'])||$_SESSION['currentPageDetailKuesioner']['page_name']!='m.perkuliahan.DetailKuesioner') {
				$_SESSION['currentPageDetailKuesioner']=array('page_name'=>'m.perkuliahan.DetailKuesioner','page_num'=>0,'DataKuesioner'=>array());
			}             
            $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
            $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
            $this->tbCmbOutputReport->DataBind();
            
            try {			
                $idpengampu_penyelenggaraan=addslashes($this->request['id']);            				
                $str = "SELECT vpp.idpengampu_penyelenggaraan,vpp.nidn,vpp.nama_dosen,vpp.kmatkul,vpp.nmatkul,vpp.sks,vpp.semester,kh.jumlah_mhs,kh.jumlah_soal,kh.total_nilai,kh.skor_terendah,kh.skor_tertinggi,kh.intervals,kh.n_kual,vpp.tahun,vpp.idsmt,vpp.kjur FROM v_pengampu_penyelenggaraan vpp,kuesioner_hasil kh WHERE kh.idpengampu_penyelenggaraan=vpp.idpengampu_penyelenggaraan AND vpp.idpengampu_penyelenggaraan=$idpengampu_penyelenggaraan";
                $this->DB->setFieldTable(array('idpengampu_penyelenggaraan','nidn','nama_dosen','kmatkul','nmatkul','sks','semester','jumlah_mhs','jumlah_soal','total_nilai','skor_terendah','skor_tertinggi','intervals','n_kual','tahun','idsmt','kjur'));
                $r=$this->DB->getRecord($str);	           
                $datakuesioner=$r[1];
                if (!isset($r[1])) {
                    $_SESSION['currentPageDetailKuesioner']['DataKuesioner']=array();
                    throw new Exception("Data Kuesioner dengan ID ($idpengampu_penyelenggaraan) tidak terdaftar.");
                }  
                $datakuesioner['kmatkul']=$this->Kuesioner->getKMatkul($datakuesioner['kmatkul']);
                $_SESSION['currentPageDetailKuesioner']['DataKuesioner']=$datakuesioner;                        
                $this->DataKuesioner=$datakuesioner;            
                
                $this->populateData();	
                $this->setInfoToolbar();    
            }catch (Exception $e) {
                $this->idProcess='view';	
                $this->errorMessage->Text=$e->getMessage();	
            }
		}				
	}    
    public function setInfoToolbar() {        
        $kjur=$_SESSION['currentPageDetailKuesioner']['DataKuesioner']['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
        $ta=$_SESSION['currentPageDetailKuesioner']['DataKuesioner']['tahun'];		
        $semester = $this->setup->getSemester($_SESSION['currentPageDetailKuesioner']['DataKuesioner']['idsmt']);
		$ta='T.A '.$this->DMaster->getNamaTA($_SESSION['ta']);		        
		$this->lblModulHeader->Text="Program Studi $ps $ta Semester $semester";        
	}	
    public function hitungKuesioner ($sender,$param) {
        $idpengampu_penyelenggaraan = $_SESSION['currentPageDetailKuesioner']['DataKuesioner']['idpengampu_penyelenggaraan']; 
        $this->Kuesioner->hitungKuesioner($idpengampu_penyelenggaraan,'update');
        $this->redirect('perkuliahan.DetailKuesioner', true, array('id'=>$idpengampu_penyelenggaraan));
    }
	public function itemCreated ($sender,$param) {
        $item=$param->Item;
		if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem') {		
            if ($item->DataItem['ada']) {
                $item->literalNamaKelompok->Text='<tr class="success">
                                                    <td colspan="9">'.$item->DataItem['nama_kelompok'].'</td></tr>';
            }
        }
    }
	public function populateData() {
        $idpengampu_penyelenggaraan = $_SESSION['currentPageDetailKuesioner']['DataKuesioner']['idpengampu_penyelenggaraan'];         
        $kelompok_pertanyaan=$this->DMaster->getListKelompokPertanyaan();                
        $kelompok_pertanyaan[0]='UNDEFINED';
        unset($kelompok_pertanyaan['none']);        
        $result=array();
        while (list($idkelompok_pertanyaan,$nama_kelompok)=each($kelompok_pertanyaan)) {
            $str = "SELECT kj.idkuesioner,k.idkelompok_pertanyaan,k.pertanyaan,k.`orders`,k.date_added FROM kuesioner k,kuesioner_jawaban kj WHERE kj.idkuesioner=k.idkuesioner AND idkelompok_pertanyaan=$idkelompok_pertanyaan AND kj.idpengampu_penyelenggaraan=$idpengampu_penyelenggaraan";
            $this->DB->setFieldTable(array('idkuesioner','idkelompok_pertanyaan','pertanyaan','orders','date_added'));
            $r=$this->DB->getRecord($str);
            $jumlah_r=count($r);
            if ($jumlah_r > 0) {
                $r[1]['ada']=true;
                $r[1]['nama_kelompok']=$nama_kelompok;
                $idkuesioner=$r[1]['idkuesioner'];        
                $str="SELECT nilai_indikator,SUM(nilai_indikator) AS jumlah FROM kuesioner_jawaban kj,kuesioner_indikator ki WHERE ki.idindikator=kj.idindikator AND kj.idpengampu_penyelenggaraan=$idpengampu_penyelenggaraan AND kj.idkuesioner=$idkuesioner GROUP BY nilai_indikator";
                $this->DB->setFieldTable(array('nilai_indikator','jumlah'));
                $hasil_indikator=$this->DB->getRecord($str);
                $indikator1=0;
                $indikator2=0;
                $indikator3=0;
                $indikator4=0;
                $indikator5=0;
                foreach ($hasil_indikator as $hasil) {
                    switch($hasil['nilai_indikator']) {
                        case 1 :
                            $indikator1=$hasil['jumlah'];
                        break;
                        case 2 :
                            $indikator2=$hasil['jumlah'];
                        break;
                        case 3 :
                            $indikator3=$hasil['jumlah'];
                        break;
                        case 4 :
                            $indikator4=$hasil['jumlah'];
                        break;
                        case 5 :
                            $indikator5=$hasil['jumlah'];
                        break;
                    }
                }
                $r[1]['indikator1']=$indikator1;
                $r[1]['indikator2']=$indikator2;
                $r[1]['indikator3']=$indikator3;
                $r[1]['indikator4']=$indikator4;
                $r[1]['indikator5']=$indikator5;  
                $result[]=$r[1];
                next($r);                
                while (list($k,$v)=each($r)) {
                    $idkuesioner=$v['idkuesioner']; 
                    $str="SELECT nilai_indikator,SUM(nilai_indikator) AS jumlah FROM kuesioner_jawaban kj,kuesioner_indikator ki WHERE ki.idindikator=kj.idindikator AND kj.idpengampu_penyelenggaraan=$idpengampu_penyelenggaraan AND kj.idkuesioner=$idkuesioner GROUP BY nilai_indikator";
                    $this->DB->setFieldTable(array('nilai_indikator','jumlah'));
                    $hasil_indikator=$this->DB->getRecord($str);
                    $indikator1=0;
                    $indikator2=0;
                    $indikator3=0;
                    $indikator4=0;
                    $indikator5=0;
                    foreach ($hasil_indikator as $hasil) {
                        switch($hasil['nilai_indikator']) {
                            case 1 :
                                $indikator1=$hasil['jumlah'];
                            break;
                            case 2 :
                                $indikator2=$hasil['jumlah'];
                            break;
                            case 3 :
                                $indikator3=$hasil['jumlah'];
                            break;
                            case 4 :
                                $indikator4=$hasil['jumlah'];
                            break;
                            case 5 :
                                $indikator5=$hasil['jumlah'];
                            break;
                        }
                    }
                    $v['indikator1']=$indikator1;
                    $v['indikator2']=$indikator2;
                    $v['indikator3']=$indikator3;
                    $v['indikator4']=$indikator4;
                    $v['indikator5']=$indikator5;                    
                    $result[]=$v;
                }                
            }            
        }                
		$this->RepeaterS->DataSource=$result;
		$this->RepeaterS->dataBind();
	}	    
	public function printKRS ($sender,$param) {
//        $this->createObj('reportkrs');
//        $this->linkOutput->Text='';
//        $this->linkOutput->NavigateUrl='#';
//        switch ($_SESSION['outputreport']) {
//            case  'summarypdf' :
//                $messageprintout="Mohon maaf Print out pada mode summary pdf tidak kami support.";                
//            break;
//            case  'summaryexcel' :
//                $messageprintout="Mohon maaf Print out pada mode summary excel tidak kami support.";                
//            break;
//            case  'excel2007' :
//                $messageprintout="Mohon maaf Print out pada mode excel 2007 belum kami support.";                
//            break;
//            case  'pdf' :                
//                $messageprintout='';                
//                $tahun=$_SESSION['ta'];
//                $semester=$_SESSION['semester'];
//                $nama_tahun = $this->DMaster->getNamaTA($tahun);
//                $nama_semester = $this->setup->getSemester($semester);
//
//                $dataReport=$_SESSION['currentPageDetailKuesioner']['DataMHS'];
//                $dataReport['krs']=$_SESSION['currentPageDetailKuesioner']['DataKRS']['krs'];        
//                $dataReport['matakuliah']=$_SESSION['currentPageDetailKuesioner']['DataKRS']['matakuliah'];        
//                $dataReport['nama_tahun']=$nama_tahun;
//                $dataReport['nama_semester']=$nama_semester;        
//                
//                $kaprodi=$this->KRS->getKetuaPRODI($dataReport['kjur']);                  
//                $dataReport['nama_kaprodi']=$kaprodi['nama_dosen'];
//                $dataReport['jabfung_kaprodi']=$kaprodi['nama_jabatan'];
//                $dataReport['nipy_kaprodi']=$kaprodi['nipy'];
//                
//                $dataReport['linkoutput']=$this->linkOutput;                 
//                $this->report->setDataReport($dataReport); 
//                $this->report->setMode($_SESSION['outputreport']);
//                $this->report->printKRS();				
//
//                
//            break;
//        }
//        $this->lblMessagePrintout->Text=$messageprintout;
//        $this->lblPrintout->Text="Kartu Rencana Studi T.A $nama_tahun Semester $nama_semester";
//        $this->modalPrintOut->show();
	}
}

?>