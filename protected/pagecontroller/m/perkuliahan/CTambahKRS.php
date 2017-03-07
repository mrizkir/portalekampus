<?php
prado::using ('Application.MainPageM');
class CTambahKRS extends MainPageM {
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
        $this->showKRS = true;                   
        $this->createObj('KRS');
        $this->createObj('Nilai');
        $this->createObj('Finance');
		if (!$this->IsPostBack&&!$this->IsCallback) {	            
            $this->lblModulHeader->Text=$this->getInfoToolbar();            
            try {	
                $datakrs=$_SESSION['currentPageKRS']['DataKRS'];
                if (isset($datakrs['krs']['idkrs'])){
                    $this->KRS->DataKRS=$datakrs;
                    
                    $idsmt=$datakrs['krs']['idsmt'];
                    $tahun=$datakrs['krs']['tahun'];
                    $datamhs=$_SESSION['currentPageKRS']['DataMHS'];                                            
                    $nim=$datamhs['nim'];                
                    $this->KRS->setDataMHS($datamhs);   

                    $kjur=$datamhs['kjur'];
                    $idkur=$this->KRS->getIDKurikulum($kjur);
                    $str = "SELECT vp.idpenyelenggaraan,vp.kmatkul,vp.nmatkul,vp.sks,vp.semester,vp.iddosen,vp.nidn,vp.nama_dosen FROM v_penyelenggaraan vp WHERE vp.idpenyelenggaraan NOT IN (SELECT km.idpenyelenggaraan FROM krsmatkul km,krs k WHERE km.idkrs=k.idkrs AND k.nim='$nim') AND vp.idsmt='$idsmt' AND vp.tahun='$tahun' AND vp.kjur='$kjur' AND vp.idkur=$idkur ORDER BY vp.semester ASC,vp.nmatkul ASC";
                    $this->DB->setFieldTable (array('idpenyelenggaraan','kmatkul','nmatkul','sks','semester','iddosen','nidn','nama_dosen'));			
                    $daftar_matkul_diselenggarakan=$this->DB->getRecord($str);

                    $idkrs=$this->KRS->DataKRS['krs']['idkrs'];
                    $str = "SELECT idpenyelenggaraan,idkrsmatkul,kmatkul,nmatkul,sks,semester,batal,nidn,nama_dosen FROM v_krsmhs WHERE idkrs=$idkrs ORDER BY semester ASC,kmatkul ASC";
                    $this->DB->setFieldTable(array('idpenyelenggaraan','idkrsmatkul','kmatkul','nmatkul','sks','semester','batal','nidn','nama_dosen'));
                    $matkul=$this->DB->getRecord($str);                
                    $this->RepeaterS->DataSource=$matkul;
                    $this->RepeaterS->dataBind();

                    $this->RepeaterPenyelenggaraan->DataSource=$daftar_matkul_diselenggarakan;
                    $this->RepeaterPenyelenggaraan->dataBind();
                }else{
                    throw new Exception('ID KRS belum ada di session.');
                }                
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
	public function getDataMHS($idx) {		        
        return $this->KRS->getDataMHS($idx);
    }
	public function tambahMatkul($sender,$param) {
		try {		
            $datakrs=$_SESSION['currentPageKRS']['DataKRS']['krs'];
            $datakrs['iddata_konversi']=$this->Pengguna->getDataUser('iddata_konversi');
            $this->KRS->setDataMHS($datakrs);
			$idkrs=$datakrs['idkrs'];
			$str = "SELECT SUM(sks) AS jumlah FROM v_krsmhs WHERE idkrs='$idkrs'";
			$this->DB->setFieldTable(array('jumlah'));
			$r=$this->DB->getRecord($str);
			$jumlah=$r[1]['jumlah']+$sender->CommandParameter;
			$maxSKS=$datakrs['maxSKS'];
			if ($jumlah > $maxSKS) throw new Exception ("Tidak bisa tambah sks lagi. Karena telah melebihi batas anda ($maxSKS)");
			$idpenyelenggaraan=$this->getDataKeyField($sender,$this->RepeaterPenyelenggaraan);
			//check kmatkul syarat apakah lulus		
			$this->KRS->checkMatkulSyaratIDPenyelenggaraan($idpenyelenggaraan);
			if (!$this->DB->checkRecordIsExist('idpenyelenggaraan','krsmatkul',$idpenyelenggaraan,' AND idkrs='.$idkrs)) { 
				$str = "INSERT INTO krsmatkul (idkrsmatkul,idkrs,idpenyelenggaraan,batal) VALUES (NULL,'$idkrs',$idpenyelenggaraan,0)";
				$this->DB->insertRecord($str);			
				$this->redirect ('perkuliahan.TambahKRS',true);
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
		$this->redirect ('perkuliahan.TambahKRS',true);
	}	
	
	public function hitung ($sender,$param) {
		$item=$param->Item;		
		if ($item->ItemType==='Item' || $item->ItemType==='AlternatingItem') {					
			$matkul=$item->DataItem['kmatkul'].'-'.$item->DataItem['nmatkul'];									
			if ($_SESSION['currentPageKRS']['DataKRS']['krs']['sah']&&!$item->DataItem['batal']) {
				$onclick="alert('Tidak bisa menghapus Matakuliah $matkul, karena sudah disahkan oleh Dosen Wali.')";
				$item->btnHapus->Enabled=false;
			}else{
				$onclick="if(!confirm('Anda yakin mau menghapus $matkul')) return false;";			
			}
			$item->btnHapus->Attributes->OnClick=$onclick;
            
			TambahKRS::$totalSKS+=$item->DataItem['sks'];	
			TambahKRS::$jumlahMatkul+=1;	
		}
	}	
	public function closeTambahKRS ($sender,$param) {
        unset($_SESSION['currentPageKRS']);
        $this->redirect ('perkuliahan.KRS',true);
    }
}

?>