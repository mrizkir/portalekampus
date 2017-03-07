<?php
prado::using ('Application.MainPageK');
class CDetailPembayaranCutiSemesterGenap Extends MainPageK {	
    public static $TotalSudahBayar=0;
    public static $KewajibanMahasiswa=0;
	public function onLoad($param) {
		parent::onLoad($param);		
        $this->createObj('Finance');
        $this->showMenuPembayaran=true;
        $this->showPembayaranCutiSemesterGenap=true;
		if (!$this->IsPostBack&&!$this->IsCallBack) {	
            if (!isset($_SESSION['currentPagePembayaranCutiSemesterGenap'])||$_SESSION['currentPagePembayaranCutiSemesterGenap']['page_name']!='k.pembayaran.PembayaranCutiSemesterGenap') {
				$_SESSION['currentPagePembayaranCutiSemesterGenap']=array('page_name'=>'k.pembayaran.PembayaranCutiSemesterGenap','page_num'=>0,'search'=>false,'DataMHS'=>array(),'ta'=>$_SESSION['ta']);												
			}
             try {
                $nim=addslashes($this->request['id']);
                
                $str = "SELECT vdm.no_formulir,vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,vdm.tempat_lahir,vdm.tanggal_lahir,vdm.kjur,vdm.nama_ps,vdm.idkonsentrasi,k.nama_konsentrasi,vdm.tahun_masuk,vdm.semester_masuk,vdm.iddosen_wali,vdm.idkelas,vdm.k_status,sm.n_status AS status FROM v_datamhs vdm LEFT JOIN konsentrasi k ON (vdm.idkonsentrasi=k.idkonsentrasi) LEFT JOIN status_mhs sm ON (vdm.k_status=sm.k_status) WHERE vdm.nim='$nim'";
                $this->DB->setFieldTable(array('no_formulir','nim','nirm','nama_mhs','jk','tempat_lahir','tanggal_lahir','kjur','nama_ps','idkonsentrasi','nama_konsentrasi','tahun_masuk','semester_masuk','iddosen_wali','idkelas','k_status','status'));
                $r=$this->DB->getRecord($str);	           
                $datamhs=$r[1];
                
                if (!isset($r[1])) {
                    throw new Exception ("NIM ($nim) tidak terdaftar di Portal, silahkan ganti dengan yang lain.");
                } 
                
                $datamhs['idsmt']=2;
                $datamhs['ta']=$_SESSION['currentPagePembayaranCutiSemesterGenap']['ta'];
                
                $this->Finance->setDataMHS($datamhs);
                $datadulang=$this->Finance->getDataDulang(2,$datamhs['ta']);
                
                if (isset($datadulang['iddulang'])) {
                    if ($datadulang['k_status']!='C') {
                        $status=$this->DMaster->getNamaStatusMHSByID ($datadulang['k_status']);
                        $ta=$datadulang['tahun'];
                        throw new Exception ("NIM ($nim) sudah daftar ulang di semester Genap T.A $ta dengan status $status.");		
                    }
                }
                
                $datamhs['iddata_konversi']=$this->Finance->isMhsPindahan($datamhs['nim'],true);            
                
                $kelas=$this->Finance->getKelasMhs();                
                $datamhs['nkelas']=($kelas['nkelas']=='')?'Belum ada':$kelas['nkelas'];			                    
                $datamhs['nama_konsentrasi']=($datamhs['idkonsentrasi']==0) ? '-':$datamhs['nama_konsentrasi'];

                $nama_dosen=$this->DMaster->getNamaDosenWaliByID($datamhs['iddosen_wali']);				                    
                $datamhs['nama_dosen']=$nama_dosen;
                
                $this->Finance->setDataMHS($datamhs);
                CDetailPembayaranCutiSemesterGenap::$KewajibanMahasiswa=$this->Finance->getBiayaCuti($datamhs['tahun_masuk'],$datamhs['semester_masuk'],$datamhs['idkelas']);
                $_SESSION['currentPagePembayaranCutiSemesterGenap']['DataMHS']=$datamhs;    
                
                $this->populateTransaksi();
                
                if ($this->ListTransactionRepeater->Items->Count() > 0) {
                    $this->txtAddNomorFaktur->Enabled=false;
                    $this->cmbAddTanggalFaktur->Enabled=false;
                    $this->btnSave->Enabled=false;
                   
                }
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
        $datamhs=$_SESSION['currentPagePembayaranCutiSemesterGenap']['DataMHS'];
        $nim=$datamhs['nim'];
        $tahun=$datamhs['ta'];
        $idsmt=2;
        
        $str = "SELECT no_transaksi,no_faktur,tanggal,date_added,dibayarkan,commited FROM transaksi_cuti WHERE tahun=$tahun AND idsmt=$idsmt AND nim='$nim'";
        $this->DB->setFieldTable(array('no_transaksi','no_faktur','tanggal','date_added','dibayarkan','commited'));
        $result=$this->DB->getRecord($str);	
        
        $this->ListTransactionRepeater->DataSource=$result;
        $this->ListTransactionRepeater->dataBind();     
    }
    public function dataBoundListTransactionRepeater ($sender,$param) {
		$item=$param->Item;
		if ($item->ItemType==='Item' || $item->ItemType==='AlternatingItem') {			
			if ($item->DataItem['commited']) {
                $item->btnDeleteFromRepeater->Enabled=false;
                $item->btnCommitFromRepeater->Enabled=false;
			}else{
                $item->btnDeleteFromRepeater->Attributes->onclick="if(!confirm('Apakah Anda ingin menghapus Transaksi ini?')) return false;";
            }
            CDetailPembayaranCutiSemesterGenap::$TotalSudahBayar+=$item->DataItem['dibayarkan'];
		}
	}
    public function checkNomorFaktur ($sender,$param) {
		$this->idProcess=$sender->getId()=='addNomorFaktur'?'add':'edit';
        $no_faktur=$param->Value;		
        if ($no_faktur != '') {
            try {
                if ($this->DB->checkRecordIsExist('no_faktur','transaksi',$no_faktur)) {                                
                    throw new Exception ("Nomor Faktur dari ($no_faktur) sudah tidak tersedia silahkan ganti dengan yang lain.");		
                }
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }	
        }	
    }
    public function saveData ($sender,$param) {
		if ($this->Page->isValid) {	
            $userid=$this->Pengguna->getDataUser('userid');
            $datamhs=$_SESSION['currentPagePembayaranCutiSemesterGenap']['DataMHS'];
            $tahun=$datamhs['ta'];
            $nim=$datamhs['nim'];
            
            $no_faktur=addslashes($this->txtAddNomorFaktur->Text);            
            $tanggal=date('Y-m-d',$this->cmbAddTanggalFaktur->TimeStamp);
            
            $this->Finance->setDataMHS($datamhs);
            $dibayarkan=$this->Finance->getBiayaCuti($datamhs['tahun_masuk'],$datamhs['semester_masuk'],$datamhs['idkelas']);
                
            $str = "INSERT INTO transaksi_cuti SET no_faktur='$no_faktur',tahun=$tahun,idsmt=2,nim='$nim',dibayarkan=$dibayarkan,tanggal='$tanggal',date_added=NOW(),date_modified=NOW(),userid=$userid";
            $this->DB->insertRecord($str);
            
            $this->redirect('pembayaran.DetailPembayaranCutiSemesterGenap',true,array('id'=>$nim));
        }
    }
    public function commitData ($sender,$param) {
		if ($this->Page->isValid) {	
            $no_transaksi=$this->getDataKeyField($sender,$this->ListTransactionRepeater);
            $datamhs=$_SESSION['currentPagePembayaranCutiSemesterGenap']['DataMHS'];
            $nim=$datamhs['nim'];
            $this->DB->query('BEGIN');
            $str = "UPDATE transaksi_cuti SET commited=1,date_modified=NOW() WHERE no_transaksi=$no_transaksi";
            $this->DB->updateRecord($str);
            
            $datadulang=$this->Finance->getDataDulang($datamhs['ta'],$datamhs['idsmt']);
            if (!isset($datadulang['iddulang'])) {
                $this->Finance->setDataMHS($datamhs);	
                $kelas=$datamhs['idkelas'];
                $k_status=$datamhs['k_status'];
                $ta=$datamhs['ta'];
                $idsmt=$datamhs['idsmt'];
                
                $str = "SELECT tanggal FROM transaksi_cuti WHERE no_transaksi=$no_transaksi";
                $this->DB->setFieldTable(array('no_transaksi','no_faktur','tanggal','date_added','dibayarkan','commited'));
                $result=$this->DB->getRecord($str);	
                $tanggal=$result[1]['tanggal'];
                
                $str = "INSERT INTO dulang (iddulang,nim,tahun,idsmt,tanggal,idkelas,status_sebelumnya,k_status) VALUES (NULL,'$nim','$ta','$idsmt','$tanggal','$kelas','$k_status','C')";
                $this->DB->insertRecord($str);

                $str = "UPDATE register_mahasiswa SET k_status='C' WHERE nim='$nim'";
                $this->DB->updateRecord($str);
            }
            $this->DB->query('COMMIT');
            $this->redirect('pembayaran.DetailPembayaranCutiSemesterGenap',true,array('id'=>$nim));
        }
    }
    public function deleteRecord ($sender,$param) {	
        $datamhs=$_SESSION['currentPagePembayaranCutiSemesterGenap']['DataMHS']; 
        $nim=$datamhs['nim'];
		$no_transaksi=$this->getDataKeyField($sender,$this->ListTransactionRepeater);		
		$this->DB->deleteRecord("transaksi_cuti WHERE no_transaksi='$no_transaksi'");		
		$this->redirect('pembayaran.DetailPembayaranCutiSemesterGenap',true,array('id'=>$nim));
	}	
    public function closeTransaction ($sender,$param) {
        unset($_SESSION['currentPagePembayaranCutiSemesterGenap']['DataMHS']);
        $this->redirect('pembayaran.PembayaranCutiSemesterGenap',true);
    }
}

?>