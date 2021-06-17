<?php
prado::using ('Application.MainPageMHS');
class CTambahKRSMerdeka extends MainPageMHS {
	/**
	* total SKS
	*/
	static $totalSKS=0;
	
	/**
	* jumlah matakuliah
	*/
	static $jumlahMatkul=0;
	
	/**
	* tahun dan semester sebelum
	*/
	public $ta_smt_sebelum;
	
	/**
	* keterangan IP
	*/
	public $ketip='IP';
	public function onLoad($param) {
		parent::onLoad($param);	
        $this->showSubMenuAkademikPerkuliahan=true;
        $this->showKRSMerdeka = true;                   
        $this->createObj('KRS');
        $this->createObj('Nilai');
        $this->createObj('Finance');
		if (!$this->IsPostBack&&!$this->IsCallback) {	            
            $this->lblModulHeader->Text=$this->getInfoToolbar();            
            try {			                
                $datamhs=$this->Pengguna->getDataUser();    
                if ($datamhs['is_merdeka'] == 0)
                {
                    throw new Exception ("Saudara belum terdaftar sebagai mahasiswa Merdeka.");
                }
                $this->KRS->DataKRS=$_SESSION['currentPageKRSMerdeka']['DataKRS'];
                $idsmt=$_SESSION['semester'];
                $tahun=$_SESSION['ta'];
                
                $datamhs['idsmt']=$idsmt;
                $nim=$datamhs['nim'];
                
                $this->KRS->setDataMHS($datamhs);
                $this->Finance->setDataMHS($datamhs);
                
                if ($idsmt==3) { 
                    $jumlah_sks_sp=$this->Finance->getSKSFromSP($tahun,$idsmt);
                    if (!($jumlah_sks_sp > 0)) {
                        throw new Exception ("Anda tidak bisa mengisi KRS karena jumlah SKS hanya 0 atau transaksi pembayaran tidak tercatat di sistem.");																			
                    }
                }else {
                    $data=$this->Finance->getTresholdPembayaran($tahun,$idsmt,true);				                        
                    if (!$data['bool'])throw new Exception ("Anda tidak bisa mengisi KRS karena baru membayar(".$this->Finance->toRupiah($data['total_bayar'])."), harus minimal setengahnya sebesar (".$this->Finance->toRupiah($data['ambang_pembayaran']).") dari total (".$this->Finance->toRupiah($data['total_biaya']).")");
                }
                              
                $datadulang=$this->KRS->getDataDulang($idsmt,$tahun);
                $nama_tahun = $this->DMaster->getNamaTA($tahun);
                $nama_semester = $this->setup->getSemester($idsmt);
                if (!isset($datadulang['iddulang']))throw new Exception ("Anda belum melakukan daftar ulang pada T.A $nama_tahun Semester $nama_semester. Silahkan hubungi Prodi (Bukan Keuangan).");
                $status=$datamhs['k_status'];
                if ($status== 'K'||$status== 'L'||$status== 'D') throw new Exception ("Status Anda tidak aktif, sehingga tidak bisa mengisi KRS.");						
                if ($datadulang['k_status'] != 'A')throw new Exception ("Anda pada tahun akademik dan semester sekarang tidak aktif.");									
                                               
                #Membuat KRS BARU
                if (isset($this->KRS->DataKRS['krs']['idkrs'])) {
                	if ($this->KRS->DataKRS['krs']['sah'])throw new Exception ('Tidak bisa tambah KRS, karena sudah disahkan oleh Dosen Wali.');
                }else{
                    $tanggal=date('Y-m-d');
                    $no_krs=mt_rand();                    
                    $tasmt=$tahun.$idsmt;
                    $str = "INSERT INTO krs SET idkrs=NULL,tgl_krs='$tanggal',no_krs=$no_krs,nim='$nim',idsmt='$idsmt',tahun='$tahun',tasmt='$tasmt',is_merdeka=1,sah=0,tgl_disahkan='0000-00-00'";
                    $this->DB->insertRecord($str);					
                    $this->KRS->DataKRS['krs'] = array('idkrs'=>$this->DB->getLastInsertID(),
                                     'tgl_krs'=>$tanggal,
                                     'no_krs'=>$no_krs,
                                     'sah'=>0,
                                     'nim'=>$nim,
                                     'idsmt'=>$idsmt,
                                     'tahun'=>$tahun,
                                     'tasmt'=>$tasmt);		                    
                }                                
                $this->Nilai->setDataMHS($datamhs);
                $datadulangbefore=$this->Nilai->getDataDulangBeforeCurrentSemester($idsmt,$tahun);
                if ($datadulangbefore['k_status']=='C') {
                    $this->KRS->DataKRS['krs']['maxSKS']=$idsmt==3?$jumlah_sks_sp:$this->setup->getSettingValue('jumlah_sks_krs_setelah_cuti');                
                    $this->KRS->DataKRS['krs']['ipstasmtbefore']='N.A (Status Cuti)';
                }else{
                    $this->Nilai->getMaxSKS($tahun,$idsmt);
                    $this->KRS->DataKRS['krs']['maxSKS']=$idsmt==3?$jumlah_sks_sp:20;                
                    $this->KRS->DataKRS['krs']['ipstasmtbefore']=$this->Nilai->getIPS();
                }                                                
                
                $_SESSION['currentPageKRSMerdeka']['DataKRS']=$this->KRS->DataKRS;
                $kjur=$datamhs['kjur'];                
                $str = "SELECT vp.idpenyelenggaraan,vp.kmatkul,vp.nmatkul,vp.sks,vp.semester,vp.iddosen,vp.nidn,vp.nama_dosen,vp.kjur FROM v_penyelenggaraan vp WHERE vp.idpenyelenggaraan NOT IN (SELECT km.idpenyelenggaraan FROM krsmatkul km,krs k WHERE km.idkrs=k.idkrs AND k.nim='$nim') AND vp.idsmt='$idsmt' AND vp.tahun='$tahun' ORDER BY vp.kjur ASC,vp.semester ASC,vp.nmatkul ASC";
                $this->DB->setFieldTable (array('idpenyelenggaraan','kmatkul','nmatkul','sks','semester','iddosen','nidn','nama_dosen','kjur'));			
                $daftar_matkul_diselenggarakan=$this->DB->getRecord($str);

                $idkrs=$this->KRS->DataKRS['krs']['idkrs'];
                $str = "SELECT vkm.idpenyelenggaraan,vkm.idkrsmatkul,vkm.kmatkul,vkm.nmatkul,vkm.sks,vkm.semester,vkm.batal,vkm.nidn,vkm.nama_dosen,p.kjur FROM v_krsmhs vkm JOIN penyelenggaraan p ON vkm.idpenyelenggaraan=p.idpenyelenggaraan WHERE idkrs=$idkrs ORDER BY semester ASC,kmatkul ASC";
                $this->DB->setFieldTable(array('idpenyelenggaraan','idkrsmatkul','kmatkul','nmatkul','sks','semester','batal','nidn','nama_dosen','kjur'));
                $matkul=$this->DB->getRecord($str);                
                $this->RepeaterS->DataSource=$matkul;
                $this->RepeaterS->dataBind();
                
                $this->RepeaterPenyelenggaraan->DataSource=$daftar_matkul_diselenggarakan;
                $this->RepeaterPenyelenggaraan->dataBind();
            }catch (Exception $e) {
                $this->idProcess='view';	
                $this->errorMessage->Text=$e->getMessage();	
            }
		}				
	}
    public function getInfoToolbar() {                
		$ta=$this->DMaster->getNamaTA($_SESSION['ta']);
		$semester=$this->setup->getSemester($_SESSION['semester']);
		$text="TA $ta Semester $semester";
		return $text;
	}		
	
	public function tambahMatkul($sender,$param) {
		try {		
            $datakrs=$_SESSION['currentPageKRSMerdeka']['DataKRS']['krs'];
            $datakrs['iddata_konversi']=$this->Pengguna->getDataUser('iddata_konversi');
            $datakrs['idkelas']=$this->Pengguna->getDataUser('idkelas');
            $this->KRS->setDataMHS($datakrs);
			$idkrs=$datakrs['idkrs'];
			$str = "SELECT SUM(sks) AS jumlah FROM v_krsmhs WHERE idkrs='$idkrs'";
			$this->DB->setFieldTable(array('jumlah'));
			$r=$this->DB->getRecord($str);
			$jumlah=$r[1]['jumlah']+$sender->CommandParameter;
			$maxSKS=$datakrs['maxSKS'];
			if ($jumlah > $maxSKS) throw new Exception ("Tidak bisa tambah sks lagi. Karena telah melebihi batas anda ($maxSKS)");
			$idpenyelenggaraan=$this->getDataKeyField($sender,$this->RepeaterPenyelenggaraan);			
			if (!$this->DB->checkRecordIsExist('idpenyelenggaraan','krsmatkul',$idpenyelenggaraan,' AND idkrs='.$idkrs)) { 
				$str = "INSERT INTO krsmatkul (idkrsmatkul,idkrs,idpenyelenggaraan,batal) VALUES (NULL,'$idkrs',$idpenyelenggaraan,0)";
				$this->DB->insertRecord($str);			
				$this->redirect ('perkuliahan.TambahKRSMerdeka',true);
			}
		}catch (Exception $e) {
            $this->modalMessageError->show();
			$this->lblContentMessageError->Text=$e->getMessage();					
		}		
	}
	public function hapusMatkul ($sender,$param) {		
		$idkrsmatkul=$this->getDataKeyField($sender,$this->RepeaterS);			
		$this->DB->query ('BEGIN');			
		if ($this->DB->deleteRecord("krsmatkul WHERE idkrsmatkul='$idkrsmatkul'")) {
			$this->DB->deleteRecord("kelas_mhs_detail WHERE idkrsmatkul='$idkrsmatkul'");							
			$this->DB->query ('COMMIT');
		}else {
			$this->DB->query ('ROLLBACK');
		}	
		$this->redirect ('perkuliahan.TambahKRSMerdeka',true);
	}	
	
	public function hitung ($sender,$param) {
		$item=$param->Item;		
		if ($item->ItemType==='Item' || $item->ItemType==='AlternatingItem') {					
			$matkul=$item->DataItem['kmatkul'].'-'.$item->DataItem['nmatkul'];									
			if ($_SESSION['currentPageKRSMerdeka']['DataKRS']['krs']['sah']&&!$item->DataItem['batal']) {
				$onclick="alert('Tidak bisa menghapus Matakuliah $matkul, karena sudah disahkan oleh Dosen Wali.')";
				$item->btnHapus->Enabled=false;
			}else{
				$onclick="if(!confirm('Anda yakin mau menghapus $matkul')) return false;";			
			}
			$item->btnHapus->Attributes->OnClick=$onclick;
			TambahKRSMerdeka::$totalSKS+=$item->DataItem['sks'];	
			TambahKRSMerdeka::$jumlahMatkul+=1;	
		}
	}	
	
}