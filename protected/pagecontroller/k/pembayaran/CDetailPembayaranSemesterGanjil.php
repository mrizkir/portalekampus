<?php
prado::using ('Application.MainPageK');
class CDetailPembayaranSemesterGanjil Extends MainPageK {
    public static $TotalSudahBayar=0;
    public static $KewajibanMahasiswa=0;
	public function onLoad($param) {
		parent::onLoad($param);				
        $this->showMenuPembayaran=true;
        $this->showPembayaranSemesterGanjil=true;                
        $this->createObj('Finance');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePembayaranSemesterGanjil'])||$_SESSION['currentPagePembayaranSemesterGanjil']['page_name']!='k.pembayaran.PembayaranSemesterGanjil') {
				$_SESSION['currentPagePembayaranSemesterGanjil']=array('page_name'=>'k.pembayaran.PembayaranSemesterGanjil','page_num'=>0,'search'=>false,'kelas'=>'none','tahun_masuk'=>$_SESSION['tahun_masuk'],'semester'=>$_SESSION['semester'],'DataMHS'=>array());												
			}        
            try {
                $nim=addslashes($this->request['id']);                           				
                $str = "SELECT vdm.no_formulir,vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,vdm.tempat_lahir,vdm.tanggal_lahir,vdm.kjur,vdm.nama_ps,vdm.idkonsentrasi,k.nama_konsentrasi,vdm.tahun_masuk,vdm.semester_masuk,vdm.iddosen_wali,vdm.idkelas,vdm.k_status,sm.n_status AS status FROM v_datamhs vdm LEFT JOIN konsentrasi k ON (vdm.idkonsentrasi=k.idkonsentrasi) LEFT JOIN status_mhs sm ON (vdm.k_status=sm.k_status) WHERE vdm.nim='$nim'";
                $this->DB->setFieldTable(array('no_formulir','nim','nirm','nama_mhs','jk','tempat_lahir','tanggal_lahir','kjur','nama_ps','idkonsentrasi','nama_konsentrasi','tahun_masuk','semester_masuk','iddosen_wali','idkelas','k_status','status'));
                $r=$this->DB->getRecord($str);	           
                $datamhs=$r[1];
                $datamhs['idsmt']=$_SESSION['currentPagePembayaranSemesterGanjil']['semester'];
                $datamhs['ta']=$_SESSION['currentPagePembayaranSemesterGanjil']['ta'];             
                if (!isset($r[1])) {
                    $_SESSION['currentPagePembayaranSemesterGanjil']['DataMHS']=array();
                    throw new Exception ("NIM ($nim) tidak terdaftar di Portal, silahkan ganti dengan yang lain.");
                }      
                if ($datamhs['tahun_masuk'] == $datamhs['ta'] && $datamhs['semester_masuk']==1) {						
                    $_SESSION['currentPagePembayaranSemesterGanjil']['DataMHS']=array();
                    throw new Exception ("NIM ($nim) adalah seorang Mahasiswa baru, mohon diproses di Pembayaran->Mahasiswa Baru.");
                }
                $this->Finance->setDataMHS($datamhs);                
                $datamhs['iddata_konversi']=$this->Finance->isMhsPindahan($datamhs['nim'],true);            
                
                $kelas=$this->Finance->getKelasMhs();                
                $datamhs['nkelas']=($kelas['nkelas']=='')?'Belum ada':$kelas['nkelas'];			                    
                $datamhs['nama_konsentrasi']=($datamhs['idkonsentrasi']==0) ? '-':$datamhs['nama_konsentrasi'];

                $nama_dosen=$this->DMaster->getNamaDosenWaliByID($datamhs['iddosen_wali']);				                    
                $datamhs['nama_dosen']=$nama_dosen;
                $datamhs['no_transaksi']=isset($_SESSION['currentPagePembayaranSemesterGanjil']['DataMHS']['no_transaksi']) ? $_SESSION['currentPagePembayaranSemesterGanjil']['DataMHS']['no_transaksi'] : 'none';
                $_SESSION['currentPagePembayaranSemesterGanjil']['DataMHS']=$datamhs;         
                $this->checkPembayaranSemesterLalu ();
                $this->Finance->setDataMHS($datamhs);
                CDetailPembayaranSemesterGanjil::$KewajibanMahasiswa=$this->Finance->getTotalBiayaMhsPeriodePembayaran ('lama');
                $this->populateTransaksi();
            }catch (Exception $ex) {
                $this->idProcess='view';	
                $this->errorMessage->Text=$ex->getMessage();
            }      
		}	
	}    
    public function getDataMHS($idx) {		        
        return $this->Finance->getDataMHS($idx);
    }
    public function populateTransaksi() {
        $datamhs=$_SESSION['currentPagePembayaranSemesterGanjil']['DataMHS'];
        $nim=$datamhs['nim'];
        $tahun=$datamhs['ta'];
        $idsmt=$_SESSION['currentPagePembayaranSemesterGanjil']['semester'];
        $kjur=$datamhs['kjur'];
        $str = "SELECT no_transaksi,no_faktur,tanggal,commited,date_added FROM transaksi WHERE tahun='$tahun' AND idsmt='$idsmt' AND nim='$nim' AND kjur='$kjur'";
        $this->DB->setFieldTable(array('no_transaksi','no_faktur','tanggal','commited','date_added'));
        $r=$this->DB->getRecord($str);
        $result=array();
        while (list($k,$v)=each($r)) {
            $no_transaksi=$v['no_transaksi'];
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
            CDetailPembayaranSemesterGanjil::$TotalSudahBayar+=$item->DataItem['total'];
		}
	}	
	public function addTransaction ($sender,$param) {
        $datamhs=$_SESSION['currentPagePembayaranSemesterGanjil']['DataMHS'];    
        $this->Finance->setDataMHS($datamhs);
        if ($datamhs['no_transaksi'] == 'none') {
            $no_formulir=$datamhs['no_formulir'];
            $nim=$datamhs['nim'];
            $ta=$datamhs['ta'];    
            $tahun_masuk=$datamhs['tahun_masuk'];
            $idsmt=$_SESSION['currentPagePembayaranSemesterGanjil']['semester'];
            if ($this->Finance->getLunasPembayaran($ta,$idsmt)) {
                $this->lblContentMessageError->Text='Tidak bisa menambah Transaksi baru karena sudah lunas.';
                $this->modalMessageError->show();
            }elseif($this->DB->checkRecordIsExist('nim','transaksi',$nim," AND tahun='$ta' AND idsmt='$idsmt' AND commited=0")) {
                $this->lblContentMessageError->Text='Tidak bisa menambah Transaksi baru karena ada transaksi yang belum di Commit.';
                $this->modalMessageError->show();
            }else{
                $no_transaksi=$this->DB->getMaxOfRecord('no_transaksi','transaksi')+1;
                $no_faktur=$ta.$no_transaksi;
                $ps=$datamhs['kjur'];                
                $idkelas=$datamhs['idkelas'];
                $userid=$this->Pengguna->getDataUser('userid');

                $this->DB->query ('BEGIN');
                $str = "INSERT INTO transaksi (no_transaksi,no_faktur,kjur,tahun,idsmt,idkelas,no_formulir,nim,tanggal,userid,date_added,date_modified) VALUES ($no_transaksi,'$no_faktur','$ps','$ta','$idsmt','$idkelas','$no_formulir','$nim',NOW(),'$userid',NOW(),NOW())";					
                if ($this->DB->insertRecord($str)) {
                    $str = "SELECT idkombi,SUM(dibayarkan) AS sudah_dibayar FROM v_transaksi WHERE nim=$nim AND tahun=$ta AND idsmt=$idsmt AND commited=1 GROUP BY idkombi ORDER BY idkombi+1 ASC";
                    $this->DB->setFieldTable(array('idkombi','sudah_dibayar'));
                    $d=$this->DB->getRecord($str);

                    $sudah_dibayarkan=array();
                    while (list($o,$p)=each($d)) {            
                        $sudah_dibayarkan[$p['idkombi']]=$p['sudah_dibayar'];
                    }
                    $str = "SELECT k.idkombi,kpt.biaya FROM kombi_per_ta kpt,kombi k WHERE  k.idkombi=kpt.idkombi AND tahun=$tahun_masuk AND kpt.idkelas='$idkelas' AND idsmt=$idsmt AND periode_pembayaran='semesteran' ORDER BY periode_pembayaran,nama_kombi ASC";
                    $this->DB->setFieldTable(array('idkombi','biaya'));
                    $r=$this->DB->getRecord($str);

                    while (list($k,$v)=each($r)) {
                        $biaya=$v['biaya'];
                        $idkombi=$v['idkombi'];
                        $sisa_bayar=$biaya-$sudah_dibayarkan[$idkombi];
                        $str = "INSERT INTO transaksi_detail (idtransaksi_detail,no_transaksi,idkombi,dibayarkan) VALUES(NULL,$no_transaksi,$idkombi,$sisa_bayar)";
                        $this->DB->insertRecord($str);
                    }                   
                    $this->DB->query('COMMIT');
                    $_SESSION['currentPagePembayaranSemesterGanjil']['DataMHS']['no_transaksi']=$no_transaksi;            
                    $this->redirect('pembayaran.TransaksiPembayaranSemesterGanjil',true);        
                }else{
                    $this->DB->query('ROLLBACK');
                }           
            }
        }else{            
            $this->redirect('pembayaran.TransaksiPembayaranSemesterGanjil',true); 
        }
	}
    public function editRecord ($sender,$param) {	        
        $datamhs=$_SESSION['currentPagePembayaranSemesterGanjil']['DataMHS'];    
        if ($datamhs['no_transaksi'] == 'none') {
            $no_transaksi=$this->getDataKeyField($sender,$this->ListTransactionRepeater);		
            $_SESSION['currentPagePembayaranSemesterGanjil']['DataMHS']['no_transaksi']=$no_transaksi;
        }	
		$this->redirect('pembayaran.TransaksiPembayaranSemesterGanjil',true);
	}	
	public function deleteRecord ($sender,$param) {	
        $datamhs=$_SESSION['currentPagePembayaranSemesterGanjil']['DataMHS']; 
        $nim=$datamhs['nim'];
		$no_transaksi=$this->getDataKeyField($sender,$this->ListTransactionRepeater);		
		$this->DB->deleteRecord("transaksi WHERE no_transaksi='$no_transaksi'");		
		$this->redirect('pembayaran.DetailPembayaranSemesterGanjil',true,array('id'=>$nim));
	}		
}
