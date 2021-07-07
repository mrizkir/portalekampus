<?php
prado::using ('Application.MainPageMHS');
class CPembayaranSemesterPendek Extends MainPageMHS {
    public static $TotalSudahBayar=0;
	public function onLoad($param) {
		parent::onLoad($param);				
        $this->showPembayaranSemesterPendek=true;                
        $this->createObj('Finance');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePembayaranSemesterPendek'])||$_SESSION['currentPagePembayaranSemesterPendek']['page_name']!='mh.pembayaran.PembayaranSemesterPendek') {
				$_SESSION['currentPagePembayaranSemesterPendek']=array('page_name'=>'mh.pembayaran.PembayaranSemesterPendek','page_num'=>0,'ta'=>$_SESSION['ta'],'no_transaksi'=>'none');
            }        
            $this->setInfoToolbar();
			$this->tbCmbTA->DataSource=$this->DMaster->removeIdFromArray($this->DMaster->getListTA($this->Pengguna->getDataUser('tahun_masuk')),'none');;
            $this->tbCmbTA->Text=$_SESSION['currentPagePembayaranSemesterPendek']['ta'];
            $this->tbCmbTA->dataBind();
            try {                
                $datamhs = $this->Pengguna->getDataUser();
                $datamhs['idsmt']=3;
                $datamhs['ta']=$_SESSION['currentPagePembayaranSemesterPendek']['ta'];                
               
                if ($datamhs['tahun_masuk'] == $datamhs['ta'] && $datamhs['semester_masuk']==2) {				
                    $nim=$datamhs['nim'];		
                    throw new Exception ("NIM ($nim) adalah seorang Mahasiswa baru, mohon diproses di Pembayaran->Mahasiswa Baru.");
                }
                $this->checkPembayaranSemesterLalu ();                
                $this->populateTransaksi();
            }catch (Exception $ex) {
                $this->idProcess='view';
                $this->errorMessage->Text=$ex->getMessage();
            }      
		}	
    }
    public function setInfoToolbar() {        
        $ta=$this->DMaster->getNamaTA($_SESSION['currentPagePembayaranSemesterPendek']['ta']);        		
		$this->labelModuleHeader->Text="T.A $ta";        
    } 
    public function changeTbTA ($sender,$param) {				
		$_SESSION['currentPagePembayaranSemesterPendek']['ta']=$this->tbCmbTA->Text;
		$this->redirect('pembayaran.PembayaranSemesterPendek',true); 
	}	
    public function populateTransaksi() {
        $datamhs = $this->Pengguna->getDataUser();
        $nim=$datamhs['nim'];
        $tahun= $_SESSION['currentPagePembayaranSemesterPendek']['ta'];
        $idsmt=3;
        $kjur=$datamhs['kjur'];
		$tahun_masuk=$datamhs['tahun_masuk'];
		$idkelas=$datamhs['idkelas'];
        $str = "SELECT no_transaksi,no_faktur,tanggal,jumlah_sks,commited,date_added FROM transaksi WHERE tahun='$tahun' AND idsmt='$idsmt' AND nim='$nim' AND kjur='$kjur'";
        $this->DB->setFieldTable(array('no_transaksi','no_faktur','tanggal','jumlah_sks','commited','date_added'));
        $r=$this->DB->getRecord($str);
        $result=array();
		
		$biaya_sks=$this->Finance->getBiayaSKS ($tahun_masuk,$idkelas);
        while (list($k,$v)=each($r)) {
            $no_transaksi=$v['no_transaksi'];
			$v['biaya_sks']=$biaya_sks;
            $v['total']=$this->DB->getSumRowsOfTable('dibayarkan',"transaksi_detail WHERE no_transaksi=$no_transaksi");
            $result[$k]=$v;
        }
        $this->ListTransactionRepeater->DataSource=$result;
        $this->ListTransactionRepeater->dataBind();        
    }
	public function dataBoundListTransactionRepeater ($sender,$param) {
		$item=$param->Item;
		if ($item->ItemType==='Item' || $item->ItemType==='AlternatingItem') {			
			if ($item->DataItem['commited']) {
                $item->btnDeleteFromRepeater->Enabled=false;				
                $item->btnEditFromRepeater->Enabled=false;				
			}else{
                $item->btnDeleteFromRepeater->Attributes->onclick="if(!confirm('Apakah Anda ingin menghapus Transaksi ini?')) return false;";
            }
            CPembayaranSemesterPendek::$TotalSudahBayar+=$item->DataItem['total'];
		}
	}	
	public function addTransaction ($sender,$param) {
        if ($this->ListTransactionRepeater->Items->Count() > 0 ) {
            $this->lblContentMessageError->Text='Tidak bisa menambah Transaksi baru karena sudah ada transaksi (Khusus Semester Pendek Transaksi hanya sekali).';
            $this->modalMessageError->show();
        }else{
            $datamhs = $this->Pengguna->getDataUser();
            $this->Finance->setDataMHS($datamhs);
            if ( $_SESSION['currentPagePembayaranSemesterPendek']['no_transaksi'] == 'none') {
                $no_formulir=$datamhs['no_formulir'];
                $nim=$datamhs['nim'];
                $ta=$_SESSION['currentPagePembayaranSemesterPendek']['ta'];    
                $idsmt=3;
                if($this->DB->checkRecordIsExist('nim','transaksi',$nim," AND tahun='$ta' AND idsmt='$idsmt' AND commited=0")) {
                    $this->lblContentMessageError->Text='Tidak bisa menambah Transaksi baru karena ada transaksi yang belum di Commit.';
                    $this->modalMessageError->show();
                }else{
                    $no_transaksi='10'.$ta.$idsmt.mt_rand(10000,99999);
                    $no_faktur=$ta.$no_transaksi;
                    $ps=$datamhs['kjur'];                
                    $idkelas=$datamhs['idkelas'];
                    $userid=$this->Pengguna->getDataUser('userid');

                    $this->DB->query ('BEGIN');
                    $str = "INSERT INTO transaksi SET no_transaksi=$no_transaksi,no_faktur='$no_faktur',kjur='$ps',tahun='$ta',idsmt='$idsmt',idkelas='$idkelas',no_formulir='$no_formulir',nim='$nim',jumlah_sks=9,disc=0,tanggal=NOW(),userid='$userid',date_added=NOW(),date_modified=NOW()";					
                    if ($this->DB->insertRecord($str)) {
                        $str = "INSERT INTO transaksi_detail (idtransaksi_detail,no_transaksi,idkombi,dibayarkan,jumlah_sks) VALUES(NULL,$no_transaksi,14,1200000,9)";
                        $this->DB->insertRecord($str);

                        $this->DB->query('COMMIT');                    
                        $_SESSION['currentPagePembayaranSemesterPendek']['no_transaksi']=$no_transaksi;            
                        $this->redirect('pembayaran.TransaksiPembayaranSemesterPendek',true);        
                    }else{
                        $this->DB->query('ROLLBACK');
                    }           
                }
            }else{            
                $this->redirect('pembayaran.TransaksiPembayaranSemesterPendek',true); 
            }
        }
	}
    public function editRecord ($sender,$param) {	        
        $datamhs = $this->Pengguna->getDataUser();
        if ($_SESSION['currentPagePembayaranSemesterPendek']['no_transaksi'] == 'none') {
            $no_transaksi=$this->getDataKeyField($sender,$this->ListTransactionRepeater);		
            $_SESSION['currentPagePembayaranSemesterPendek']['no_transaksi']=$no_transaksi;
        }	
		$this->redirect('pembayaran.TransaksiPembayaranSemesterPendek',true);
	}	
	public function deleteRecord ($sender,$param) {	
        $datamhs = $this->Pengguna->getDataUser();
        $nim=$datamhs['nim'];
		$no_transaksi=$this->getDataKeyField($sender,$this->ListTransactionRepeater);		
		$this->DB->deleteRecord("transaksi WHERE no_transaksi='$no_transaksi'");		
		$this->redirect('pembayaran.PembayaranSemesterPendek',true);
	}		
    public function closeDetail ($sender,$param) {
        $_SESSION['currentPagePembayaranSemesterPendek']['no_transaksi']='none';
        $this->redirect('pembayaran.PembayaranSemesterPendek',true);
    }
}