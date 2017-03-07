<?php
prado::using ('Application.MainPageK');
class CPembayaranPiutangSemesterGanjil Extends MainPageK {		
	public function onLoad($param) {
		parent::onLoad($param);				
        $this->showMenuPembayaran=true;
        $this->showPembayaranPiutangSemesterGanjil=true;                
        $this->createObj('Finance');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePembayaranPiutangSemesterGanjil'])||$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['page_name']!='k.pembayaran.PembayaranPiutangSemesterGanjil') {
				$_SESSION['currentPagePembayaranPiutangSemesterGanjil']=array('page_name'=>'k.pembayaran.PembayaranPiutangSemesterGanjil','page_num'=>0,'search'=>false,'ta'=>$this->setup->getSettingValue('default_ta')-1,'semester'=>1,'tahun_masuk'=>$this->setup->getSettingValue('default_ta')-1,'DataMHS'=>array());												
			}
            $_SESSION['currentPagePembayaranPiutangSemesterGanjil']['search']=false; 
            
            $daftar_ps=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');            
			$this->tbCmbPs->DataSource=$daftar_ps;
			$this->tbCmbPs->Text=$_SESSION['kjur'];			
			$this->tbCmbPs->dataBind();	            
           		
			$this->tbCmbTahunMasuk->DataSource=$this->DMaster->getListTA();					
			$this->tbCmbTahunMasuk->Text=$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['tahun_masuk'];						
			$this->tbCmbTahunMasuk->dataBind();
            
            
            $this->tbCmbTA->DataSource=$this->getTA ();
            $this->tbCmbTA->Text=$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['ta'];
            $this->tbCmbTA->dataBind();         
            
            $this->populateData();
            $this->setInfoToolbar();
		}	
	}	
    public function getTA () {
        $dt =$this->DMaster->getListTA();
        $ta=$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['tahun_masuk'];        
        while (list($k,$v)=each ($dt)) {
			if ($k != 'none') {
				if ($k >= $ta) {
					$tahun_akademik[$k]=$v;
				}
			}			
		}        
		return $tahun_akademik;
    }
    public function setInfoToolbar() {                
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
        $ta=$this->DMaster->getNamaTA($_SESSION['currentPagePembayaranPiutangSemesterGanjil']['ta']);        		
		$this->lblModulHeader->Text="Program Studi $ps T.A $ta";        
	}
    public function changeTbPs ($sender,$param) {		
		$_SESSION['kjur']=$this->tbCmbPs->Text;
        $this->redirect('pembayaran.PembayaranPiutangSemesterGanjil',true);
	}	
    public function changeTbTA ($sender,$param) {				
        $ta=$this->tbCmbTA->Text;
        $tahun_masuk=$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['tahun_masuk'];
		$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['ta']=$ta < $tahun_masuk ? $tahun_masuk : $ta;    
		$this->redirect('pembayaran.PembayaranPiutangSemesterGanjil',true);
	}   
    
    public function changeTbTahunMasuk($sender,$param) {   
        $tahun_masuk=$this->tbCmbTahunMasuk->Text;
        $ta=$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['tahun_masuk'];
		$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['ta']=$ta < $tahun_masuk ? $tahun_masuk : $ta;
        $_SESSION['currentPagePembayaranPiutangSemesterGanjil']['tahun_masuk']=$tahun_masuk;
		$this->redirect('pembayaran.PembayaranPiutangSemesterGanjil',true);
	} 
    
    public function changeTbKelas ($sender,$param) {				
		$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['kelas']=$this->tbCmbKelas->Text;
        $this->setInfoToolbar(); 
		$this->populateData();
	}
	public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPagePembayaranPiutangSemesterGanjil']['search']);
	}		
	public function populateData($search=false) {	
        $tahun_masuk=$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['tahun_masuk'];
		$ta=$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['ta'];
		$semester=$_SESSION['currentPagePembayaranPiutangSemesterGanjil']['semester'];
		$kjur=$_SESSION['kjur'];	
        
        $str = "SELECT vdm.no_formulir,vdm.nirm,vdm.nim,vdm.nama_mhs,vdm.telp_hp,vdm.kjur,vdm.tahun_masuk,vdm.semester_masuk,vdm.idkelas AS idkelas_terakhir,d.idkelas AS idkelas_dulang,vdm.k_status AS k_status_terakhir,d.k_status AS k_status_dulang FROM v_datamhs vdm LEFT JOIN (SELECT nim,idkelas,k_status FROM dulang WHERE tahun=$ta AND idsmt=$semester) AS d ON (d.nim=vdm.nim) WHERE vdm.tahun_masuk=$tahun_masuk AND vdm.kjur=$kjur AND d.k_status IS NULL ORDER BY vdm.idkelas DESC,vdm.nama_mhs ASC";        
        $this->DB->setFieldTable(array('no_formulir','nirm','nim','nama_mhs','idkelas_terakhir','telp_hp','kjur','tahun_masuk','semester_masuk','idkelas_terakhir','idkelas_dulang','k_status_terakhir','k_status_dulang'));
        $r = $this->DB->getRecord($str);
        $result=array();
        while (list($k,$v)=each($r)) {
            $nim=$v['nim'];
            $k_status=$v['k_status_dulang'];
            $idkelas=$v['idkelas_dulang'];
            if ($k_status=='') {
                $str = "SELECT idkelas,k_status FROM dulang d1,(SELECT MAX(iddulang) AS iddulang FROM dulang WHERE nim='$nim') AS d2 WHERE d1.iddulang=d2.iddulang";
                $this->DB->setFieldTable(array('idkelas','k_status'));
                $dulang = $this->DB->getRecord($str);
                $idkelas=$dulang[1]['idkelas'];
                $k_status=$dulang[1]['k_status'];
            }
            $this->Finance->setDataMHS(array('no_formulir'=>$v['no_formulir'],'nim'=>$v['nim'],'kjur'=>$v['kjur'],'tahun_masuk'=>$v['tahun_masuk'],'semester_masuk'=>$v['semester_masuk'],'idsmt'=>$semester,'idkelas'=>$idkelas));
            $data=$this->Finance->getLunasPembayaran($ta,$semester,true);            
            if (!$data['bool']) {
                $v['nkelas']=$this->DMaster->getNamaKelasByID($idkelas);
                $v['status']=$this->DMaster->getNamaStatusMHSByID($k_status);
                if ($k_status=='A') {                    
                    $v['status_style']='label-success';
                }else{                    
                    $v['status_style']='label-default';
                }            
                $sisa=$data['total_biaya']-$data['total_bayar'];
                $v['sisa']=$this->Finance->toRupiah($sisa);
                $result[$k]=$v;
            }
        }
        $this->RepeaterS->DataSource=$result;
        $this->RepeaterS->dataBind();       

	}
	public function setDataBound($sender,$param) {				
		$item=$param->Item;
		if ($item->ItemType==='Item' || $item->ItemType==='AlternatingItem') {			
            
		}		
	}	
    public function cekNIM ($sender,$param) {		
        $nim=addslashes($param->Value);		
        if ($nim != '') {
            try {
                $str = "SELECT vdm.tahun_masuk,vdm.semester_masuk FROM v_datamhs vdm WHERE vdm.nim='$nim'";
                $this->DB->setFieldTable(array('tahun_masuk','semester_masuk'));
                $r=$this->DB->getRecord($str);
                $datamhs=$r[1];
                if (!isset($r[1])) {                                   
                    throw new Exception ("NIM ($nim) tidak terdaftar di Portal, silahkan ganti dengan yang lain.");		
                }
                if ($datamhs['tahun_masuk'] == $datamhs['ta'] && $datamhs['semester_masuk']==2) {
                    throw new Exception ("NIM ($nim) adalah seorang Mahasiswa baru, mohon diproses di Pembayaran->Mahasiswa Baru.");
                }
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }	
        }	
    }
	public function Go($sender,$param) {	
        if ($this->IsValid) {            
            $nim=$sender->getId()=='btnGoRepeater'?$this->getDataKeyField($sender, $this->RepeaterS):addslashes($this->txtNIM->Text);
            $this->redirect('pembayaran.DetailPembayaranPiutangSemesterGanjil',true,array('id'=>$nim));
        }
	}
	
}
?>