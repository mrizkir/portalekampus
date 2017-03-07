<?php
prado::using ('Application.MainPageK');
class CPembayaranSemesterGenap Extends MainPageK {		
	public function onLoad($param) {
		parent::onLoad($param);				
        $this->showMenuPembayaran=true;
        $this->showPembayaranSemesterGenap=true;                
        $this->createObj('Finance');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePembayaranSemesterGenap'])||$_SESSION['currentPagePembayaranSemesterGenap']['page_name']!='k.pembayaran.PembayaranSemesterGenap') {
				$_SESSION['currentPagePembayaranSemesterGenap']=array('page_name'=>'k.pembayaran.PembayaranSemesterGenap','page_num'=>0,'search'=>false,'ta'=>$this->setup->getSettingValue('default_ta'),'semester'=>2,'kelas'=>'none','DataMHS'=>array());												
			}
            $_SESSION['currentPagePembayaranSemesterGenap']['search']=false; 
            
            $daftar_ps=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');            
			$this->tbCmbPs->DataSource=$daftar_ps;
			$this->tbCmbPs->Text=$_SESSION['kjur'];			
			$this->tbCmbPs->dataBind();	
            
            $ta=$_SESSION['currentPagePembayaranSemesterGenap']['ta'];
            $this->tbCmbTA->DataSource=array($ta=>$this->DMaster->getNamaTA($ta));
            $this->tbCmbTA->Text=$ta;
            $this->tbCmbTA->dataBind();
            
            $kelas=$this->DMaster->getListKelas();
            $kelas['none']='All';
			$this->tbCmbKelas->DataSource=$kelas;
			$this->tbCmbKelas->Text=$_SESSION['currentPagePembayaranSemesterGenap']['kelas'];			
			$this->tbCmbKelas->dataBind();	
            
            $this->populateData();
            $this->setInfoToolbar();
		}	
	}	
    public function setInfoToolbar() {                
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
        $ta=$this->DMaster->getNamaTA($_SESSION['currentPagePembayaranSemesterGenap']['ta']);        		
		$this->lblModulHeader->Text="Program Studi $ps T.A $ta";        
	}
    public function changeTbPs ($sender,$param) {		
		$_SESSION['kjur']=$this->tbCmbPs->Text;
        $this->setInfoToolbar();
		$this->populateData();
	}	
    public function changeTbTA ($sender,$param) {				
		$_SESSION['currentPagePembayaranSemesterGenap']['ta']=$this->tbCmbTA->Text;
        $this->setInfoToolbar();
		$this->populateData();
	}   
    public function changeTbKelas ($sender,$param) {				
		$_SESSION['currentPagePembayaranSemesterGenap']['kelas']=$this->tbCmbKelas->Text;
        $this->setInfoToolbar(); 
		$this->populateData();
	}
	public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPagePembayaranSemesterGenap']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPagePembayaranSemesterGenap']['search']);
	}		
	public function populateData($search=false) {		
		$ta=$_SESSION['currentPagePembayaranSemesterGenap']['ta'];
		$semester=$_SESSION['currentPagePembayaranSemesterGenap']['semester'];
		$kjur=$_SESSION['kjur'];	
        
        $kelas=$_SESSION['currentPagePembayaranSemesterGenap']['kelas'];
        $str_kelas = $kelas == 'none'?'':" AND t.idkelas='$kelas'";
        if ($search) {
            
        }else{
            $str = "SELECT t.no_transaksi,t.tanggal,t.nim,vdm.nama_mhs,commited FROM transaksi t JOIN v_datamhs vdm ON (t.nim=vdm.nim) WHERE t.tahun='$ta' AND t.idsmt='$semester' AND t.kjur=$kjur $str_kelas";
            $jumlah_baris=$this->DB->getCountRowsOfTable(" transaksi t JOIN v_datamhs vdm ON (t.nim=vdm.nim) WHERE t.tahun='$ta' AND t.idsmt='$semester' AND t.kjur=$kjur $str_kelas",'no_transaksi');
        }
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPagePembayaranSemesterGenap']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;   
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPagePembayaranSemesterGenap']['page_num']=0;}
        $this->DB->setFieldTable(array('no_transaksi','tanggal','nim','nama_mhs','commited'));
        $str = "$str ORDER BY vdm.nama_mhs ASC,t.date_added DESC LIMIT $offset,$limit";	
        $r = $this->DB->getRecord($str,$offset+1);	        
        $result=array();		
		while (list($k,$v)=each($r)) {
			$no_transaksi=$v['no_transaksi'];				
			$str2 = "SELECT SUM(dibayarkan) AS dibayarkan FROM v_transaksi t WHERE no_transaksi=$no_transaksi";			
			$this->DB->setFieldTable(array('dibayarkan'));
			$r2=$this->DB->getRecord($str2);				
			$dibayarkan=$r2[1]['dibayarkan'];						
			$v['dibayarkan']=$this->Finance->toRupiah($dibayarkan);													
			$v['tanggal']=$this->TGL->tanggal('d/m/Y',$v['tanggal']);
			$result[$k]=$v;
		}
        $this->RepeaterS->DataSource=$result;
		$this->RepeaterS->dataBind();     
        $this->paginationInfo->Text=$this->getInfoPaging($this->RepeaterS);
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
                $ta=$_SESSION['currentPagePembayaranSemesterGenap']['ta'];                
                if ($datamhs['tahun_masuk'] == $datamhs['ta'] && $datamhs['semester_masuk']==2) {						
                    $_SESSION['currentPagePembayaranSemesterGenap']['DataMHS']=array();
                    throw new Exception ("NIM ($nim) adalah seorang Mahasiswa baru, mohon diproses di Pembayaran->Mahasiswa Baru.");
                }
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }	
        }	
    }
	public function Go($param,$sender) {	
        if ($this->IsValid) {            
            $nim=addslashes($this->txtNIM->Text);
            $this->redirect('pembayaran.DetailPembayaranSemesterGenap',true,array('id'=>$nim));
        }
	}
	
}
?>