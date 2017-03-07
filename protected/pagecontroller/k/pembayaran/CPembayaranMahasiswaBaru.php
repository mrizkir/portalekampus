<?php
prado::using ('Application.MainPageK');
class CPembayaranMahasiswaBaru Extends MainPageK {		
	public function onLoad($param) {
		parent::onLoad($param);				
        $this->showMenuPembayaran=true;
        $this->showPembayaranMahasiswaBaru=true;                
        $this->createObj('Finance');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePembayaranMahasiswaBaru'])||$_SESSION['currentPagePembayaranMahasiswaBaru']['page_name']!='k.pembayaran.PembayaranMahasiswaBaru') {
				$_SESSION['currentPagePembayaranMahasiswaBaru']=array('page_name'=>'k.pembayaran.PembayaranMahasiswaBaru','page_num'=>0,'search'=>false,'semester_masuk'=>1,'DataMHS'=>array());												
			}
            $_SESSION['currentPagePembayaranMahasiswaBaru']['search']=false; 
            
            $daftar_ps=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');            
			$this->tbCmbPs->DataSource=$daftar_ps;
			$this->tbCmbPs->Text=$_SESSION['kjur'];			
			$this->tbCmbPs->dataBind();	
            
            $tahun_masuk=$this->DMaster->removeIdFromArray($this->DMaster->getListTA(),'none');			
			$this->tbCmbTahunMasuk->DataSource=$tahun_masuk	;					
			$this->tbCmbTahunMasuk->Text=$_SESSION['tahun_masuk'];						
			$this->tbCmbTahunMasuk->dataBind();
            
            $semester=array('1'=>'GANJIL','2'=>'GENAP');  				
			$this->tbCmbSemesterMasuk->DataSource=$semester;
			$this->tbCmbSemesterMasuk->Text=$_SESSION['currentPagePembayaranMahasiswaBaru']['semester_masuk'];
			$this->tbCmbSemesterMasuk->dataBind();            

            $this->populateData();
            $this->setInfoToolbar();
		}	
	}	
    public function setInfoToolbar() {                
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
        $tahunmasuk=$this->DMaster->getNamaTA($_SESSION['tahun_masuk']);		
        $semester = $this->setup->getSemester($_SESSION['currentPagePembayaranMahasiswaBaru']['semester_masuk']);		
		$this->lblModulHeader->Text="Program Studi $ps T.A $tahunmasuk Semester $semester ";        
	}
    public function changeTbPs ($sender,$param) {		
		$_SESSION['kjur']=$this->tbCmbPs->Text;
        $this->setInfoToolbar();
		$this->populateData();
	}	
    public function changeTbTahunMasuk($sender,$param) {				
		$_SESSION['tahun_masuk']=$this->tbCmbTahunMasuk->Text;
        $this->setInfoToolbar();
		$this->populateData();
	}
    public function changeTbSemesterMasuk ($sender,$param) {		
		$_SESSION['currentPagePembayaranMahasiswaBaru']['semester_masuk']=$this->tbCmbSemesterMasuk->Text;        
        $this->setInfoToolbar();
		$this->populateData();
	}
	public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPagePembayaranMahasiswaBaru']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPagePembayaranMahasiswaBaru']['search']);
	}		
	public function populateData($search=false) {		
		$tahun_masuk=$_SESSION['tahun_masuk'];
		$semester_masuk=$_SESSION['currentPagePembayaranMahasiswaBaru']['semester_masuk'];
		$kjur=$_SESSION['kjur'];		
        if ($search) {
            
        }else{
            $str = "SELECT t.no_transaksi,t.tanggal,t.no_formulir,fp.nama_mhs,commited FROM transaksi t JOIN formulir_pendaftaran fp ON (t.no_formulir=fp.no_formulir) WHERE fp.no_formulir=t.no_formulir AND fp.ta='$tahun_masuk' AND fp.idsmt='$semester_masuk' AND t.kjur=$kjur";
            $jumlah_baris=$this->DB->getCountRowsOfTable("transaksi t,formulir_pendaftaran fp WHERE fp.no_formulir=t.no_formulir AND fp.ta='$tahun_masuk' AND fp.idsmt='$semester_masuk' AND t.kjur=$kjur",'no_transaksi');
        }
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPagePembayaranMahasiswaBaru']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;   
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPagePembayaranMahasiswaBaru']['page_num']=0;}
        $this->DB->setFieldTable(array('no_transaksi','tanggal','no_formulir','nama_mhs','commited'));
        $str = "$str ORDER BY fp.nama_mhs ASC,t.date_added DESC LIMIT $offset,$limit";	
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
	
    public function cekNomorFormulir ($sender,$param) {		
        $noformulir=addslashes($param->Value);		
        if ($noformulir != '') {
            try {               
                $str = "SELECT fp.no_formulir FROM formulir_pendaftaran fp,profiles_mahasiswa pm WHERE fp.no_formulir=pm.no_formulir AND fp.no_formulir='$noformulir'";
                $this->DB->setFieldTable(array('no_formulir'));
                $r=$this->DB->getRecord($str);
                if (!isset($r[1])) {                                
                    throw new Exception ("Nomor Formulir ($noformulir) tidak terdaftar di Portal, silahkan ganti dengan yang lain.");		
                }
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }	
        }	
    }
	public function Go($param,$sender) {	
        if ($this->IsValid) {            
            $no_formulir=addslashes($this->txtNoFormulir->Text);
            $this->redirect('pembayaran.DetailPembayaranMahasiswaBaru',true,array('id'=>$no_formulir));
        }
	}
	
}
?>