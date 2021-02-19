<?php
prado::using ('Application.MainPageMHS');
class CPembayaranMahasiswaBaru Extends MainPageMHS {
    public static $TotalSudahBayar=0;
    public static $KewajibanMahasiswa=0;
	public function onLoad($param) {
		parent::onLoad($param);				        
        $this->showPembayaranMahasiswaBaru=true;                
        $this->createObj('Finance');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPagePembayaranMahasiswaBaru'])||$_SESSION['currentPagePembayaranMahasiswaBaru']['page_name']!='mh.pembayaran.PembayaranMahasiswaBaru') {
				$_SESSION['currentPagePembayaranMahasiswaBaru']=array('page_name'=>'mh.pembayaran.PembayaranMahasiswaBaru','page_num'=>0,'search'=>false,'kelas'=>'none','tahun_masuk'=>1,'semester_masuk'=>1,'DataMHS'=>array());												
			}        
            try {
                $datamhs = $this->Pengguna->getDataUser();                
                $no_formulir=$datamhs['no_formulir'];
                $str = "SELECT fp.no_formulir,fp.nama_mhs,fp.tempat_lahir,fp.tanggal_lahir,fp.jk,fp.alamat_rumah,fp.telp_rumah,fp.telp_kantor,fp.telp_hp,pm.email,fp.kjur1,fp.kjur2,idkelas,fp.ta AS tahun_masuk,fp.idsmt AS semester_masuk,pm.photo_profile FROM formulir_pendaftaran fp,profiles_mahasiswa pm WHERE fp.no_formulir=pm.no_formulir AND fp.no_formulir='$no_formulir'";
                $this->DB->setFieldTable(array('no_formulir','nama_mhs','tempat_lahir','tanggal_lahir','jk','alamat_rumah','telp_rumah','telp_kantor','telp_hp','email','kjur1','kjur2','idkelas','tahun_masuk','semester_masuk','photo_profile'));
                $r=$this->DB->getRecord($str);
                if (!isset($r[1])) {                                
                    throw new Exception ("Calon Mahasiswa dengan Nomor Formulir ($no_formulir) tidak terdaftar di Database, silahkan ganti dengan yang lain.");		
                }
                $datamhs=$r[1];                
                $datamhs['idsmt']=$datamhs['semester_masuk'];                
                $this->Finance->setDataMHS($datamhs);
                if (!$spmb=$this->Finance->isLulusSPMB(true)) {
                    throw new Exception ("Calon Mahasiswa dengan Nomor Formulir ($no_formulir) tidak lulus dalam SPMB.");		
                }
                $datamhs['nama_ps1']=$_SESSION['daftar_jurusan'][$datamhs['kjur1']];
                $datamhs['nama_ps2']=$datamhs['kjur2'] == '' || $datamhs['kjur2'] == 0 ?'N.A' : $_SESSION['daftar_jurusan'][$datamhs['kjur2']];
                if ($spmb['kjur']==$datamhs['kjur1']) {
                    $datamhs['diterima_ps1']='<span class="label label-flat border-info text-info-600">DITERIMA</span>';
                    $datamhs['diterima_ps2']='<span class="label label-flat border-info text-warning-600">TIDAK DITERIMA</span>';
                }else{
                    $datamhs['diterima_ps2']='<span class="label label-flat border-info text-info-600">DITERIMA</span>';
                    $datamhs['diterima_ps1']='<span class="label label-flat border-info text-warning-600">TIDAK DITERIMA</span>';
                }

                $datamhs['kjur']=$spmb['kjur'];
                $datamhs['nkelas']=$this->DMaster->getNamaKelasByID($datamhs['idkelas']);
                $datamhs['perpanjang']=false;
                $this->Finance->setDataMHS($datamhs);                
                $datamhs['no_transaksi']=isset($_SESSION['currentPagePembayaranMahasiswaBaru']['DataMHS']['no_transaksi']) ? $_SESSION['currentPagePembayaranMahasiswaBaru']['DataMHS']['no_transaksi'] : 'none';                
                $_SESSION['currentPagePembayaranMahasiswaBaru']['tahun_masuk']=$datamhs['tahun_masuk'];
                $_SESSION['currentPagePembayaranMahasiswaBaru']['semester_masuk']=$datamhs['semester_masuk'];
                $_SESSION['currentPagePembayaranMahasiswaBaru']['DataMHS']=$datamhs;                
                CPembayaranMahasiswaBaru::$KewajibanMahasiswa=$this->Finance->getTotalBiayaMhsPeriodePembayaran ();
                $this->populateTransaksi();
            }catch (Exception $ex) {
                $this->idProcess='view';	
                $this->errorMessage->Text=$ex->getMessage();
            }      
		}	
	}
    public function getDataMHS($idx) {              
        if (isset($_SESSION['currentPagePembayaranMahasiswaBaru']['DataMHS']['no_formulir'])) {
            return $_SESSION['currentPagePembayaranMahasiswaBaru']['DataMHS'][$idx];
        }        
    }
    public function populateTransaksi() {
        $datamhs=$_SESSION['currentPagePembayaranMahasiswaBaru']['DataMHS'];
        $no_formulir=$datamhs['no_formulir'];
        $tahun=$datamhs['tahun_masuk'];
        $idsmt=$datamhs['semester_masuk'];
        $kjur=$datamhs['kjur'];
        $str = "SELECT no_transaksi,no_faktur,tanggal,commited,date_added FROM transaksi WHERE tahun='$tahun' AND idsmt='$idsmt' AND no_formulir='$no_formulir' AND kjur='$kjur'";
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
            CPembayaranMahasiswaBaru::$TotalSudahBayar+=$item->DataItem['total'];
		}
	}	
	public function addTransaction ($sender,$param) {
        $datamhs=$_SESSION['currentPagePembayaranMahasiswaBaru']['DataMHS'];        
        if ($datamhs['no_transaksi'] == 'none') {
            $no_formulir=$datamhs['no_formulir'];
            $ta=$datamhs['tahun_masuk'];                        
            $idsmt=$datamhs['semester_masuk'];
            $this->Finance->setDataMHS($datamhs);
            if ($this->Finance->getTotalBiayaMhsPeriodePembayaran()<=0) {
                $nama_semester=$this->setup->getSemester($idsmt);
                $this->lblContentMessageError->Text="Tidak bisa menambah Transaksi baru karena komponen biaya di Tahun Masuk $ta semester $nama_semester belum disetting.";
                $this->modalMessageError->show();
            }elseif ($this->Finance->getLunasPembayaran($ta,$idsmt)) {
                $this->lblContentMessageError->Text='Tidak bisa menambah Transaksi baru karena sudah lunas.';
                $this->modalMessageError->show();
            }elseif ($this->DB->checkRecordIsExist('no_formulir','transaksi',$no_formulir," AND tahun='$ta' AND idsmt='$idsmt' AND commited=0")) {
                $this->lblContentMessageError->Text='Tidak bisa menambah Transaksi baru karena ada transaksi yang belum di Commit.';
                $this->modalMessageError->show();
            }else{
                $no_transaksi='10'.$ta.$idsmt.mt_rand(10000,99999);
                $no_faktur=$ta.$no_transaksi;
                $ps=$datamhs['kjur'];                
                $idkelas=$datamhs['idkelas'];
                $userid=$this->Pengguna->getDataUser('userid');

                $this->DB->query ('BEGIN');
                $str = "INSERT INTO transaksi SET no_transaksi=$no_transaksi,no_faktur='$no_faktur',kjur='$ps',tahun='$ta',idsmt='$idsmt',idkelas='$idkelas',no_formulir='$no_formulir',nim=0,tanggal=NOW(),jumlah_sks=0,disc=0,userid='$userid',date_added=NOW(),date_modified=NOW()";                
                if ($this->DB->insertRecord($str)) {
                    $str = "SELECT idkombi,SUM(dibayarkan) AS sudah_dibayar FROM v_transaksi WHERE no_formulir=$no_formulir AND tahun=$ta AND idsmt=$idsmt AND commited=1 GROUP BY idkombi ORDER BY idkombi+1 ASC";
                    $this->DB->setFieldTable(array('idkombi','sudah_dibayar'));
                    $d=$this->DB->getRecord($str);

                    $sudah_dibayarkan=array();
                    while (list($o,$p)=each($d)) {            
                        $sudah_dibayarkan[$p['idkombi']]=$p['sudah_dibayar'];
                    }
                    $str = "SELECT k.idkombi,kpt.biaya FROM kombi_per_ta kpt,kombi k WHERE  k.idkombi=kpt.idkombi AND tahun=$ta AND idsmt=$idsmt AND kpt.idkelas='$idkelas' AND (periode_pembayaran='sekali' OR periode_pembayaran='semesteran') ORDER BY periode_pembayaran,nama_kombi ASC";
                    $this->DB->setFieldTable(array('idkombi','biaya'));
                    $r=$this->DB->getRecord($str);

                    while (list($k,$v)=each($r)) {
                        $biaya=$v['biaya'];
                        $idkombi=$v['idkombi'];
                        $sudah_dibayar=isset($sudah_dibayarkan[$idkombi])?$sudah_dibayarkan[$idkombi]:0;
                        $sisa_bayar=$biaya-$sudah_dibayar;
                        $str = "INSERT INTO transaksi_detail SET idtransaksi_detail=NULL,no_transaksi=$no_transaksi,idkombi=$idkombi,dibayarkan=$sisa_bayar,jumlah_sks=0";
                        $this->DB->insertRecord($str);
                    }
                    
                    $this->DB->query('COMMIT');
                    $_SESSION['currentPagePembayaranMahasiswaBaru']['DataMHS']['no_transaksi']=$no_transaksi;            
                    $this->redirect('pembayaran.TransaksiPembayaranMahasiswaBaru',true);        
                }else{
                    $this->DB->query('ROLLBACK');
                }           
            }
        }else{            
            $this->redirect('pembayaran.TransaksiPembayaranMahasiswaBaru',true); 
        }
	}
    public function editRecord ($sender,$param) {	        
        $datamhs=$_SESSION['currentPagePembayaranMahasiswaBaru']['DataMHS'];    
        if ($datamhs['no_transaksi'] == 'none') {
            $no_transaksi=$this->getDataKeyField($sender,$this->ListTransactionRepeater);		
            $_SESSION['currentPagePembayaranMahasiswaBaru']['DataMHS']['no_transaksi']=$no_transaksi;
        }	
		$this->redirect('pembayaran.TransaksiPembayaranMahasiswaBaru',true);
	}	
	public function deleteRecord ($sender,$param) {	
        $datamhs=$_SESSION['currentPagePembayaranMahasiswaBaru']['DataMHS']; 
        $no_formulir=$datamhs['no_formulir'];
		$no_transaksi=$this->getDataKeyField($sender,$this->ListTransactionRepeater);		
		$this->DB->deleteRecord("transaksi WHERE no_transaksi='$no_transaksi'");		
		$this->redirect('pembayaran.DetailPembayaranMahasiswaBaru',true,array('id'=>$no_formulir));
	}		
    public function closeDetail ($sender,$param) {
        unset($_SESSION['currentPagePembayaranMahasiswaBaru']['DataMHS']);
        $this->redirect('pembayaran.PembayaranMahasiswaBaru',true);
    }
}