<?php
prado::using ('Application.logic.Logic_Akademik');
class Logic_KRS extends Logic_Akademik {
    /**
	* Data KRS
	*/
	public $DataKRS;
	public function __construct ($db) {
		parent::__construct ($db);			
	}
    /**
	* untuk mendapatkan matakuliah syarat	
	* @param $idpenyelenggaraan
	* @return array
	*/
	public function getSyaratKMatkulIDPenyelenggaraan ($idpenyelenggaraan) {		
		$str = "SELECT m2.kmatkul,m2.nmatkul,m2.sks,m2.semester,m2.sks_tatap_muka,m2.sks_praktikum,m2.sks_praktik_lapangan,m2.minimal_nilai FROM penyelenggaraan p,matakuliah m2,matakuliah_syarat ms WHERE ms.kmatkul=p.kmatkul AND m2.kmatkul=ms.kmatkul_syarat AND p.idpenyelenggaraan=$idpenyelenggaraan ORDER BY m2.nmatkul ASC";				
		$this->db->setFieldTable(array('kmatkul','nmatkul','sks','semester','minimal_nilai'));
		$r = $this->db->getRecord($str);
		return $r;
	}	
    /**
     * Digunakan untuk mengecek matakuliah syarat berdasarkan idpenyelenggaraan	
	*/
	public function checkMatkulSyaratIDPenyelenggaraan ($idpenyelenggaraan) {        
		$nim=$this->DataMHS['nim'];
		$idkelas=$this->DataMHS['idkelas'];
		$matkul_syarat=$idkelas == 'C'?array():$this->getSyaratKMatkulIDPenyelenggaraan($idpenyelenggaraan);		
		if (isset($matkul_syarat[1])) {			            
			$iddata_konversi=$this->DataMHS['iddata_konversi'];
            $str_krs = "SELECT kmatkul FROM v_krsmhs WHERE batal=0 AND sah=1 AND nim='$nim' AND kmatkul ";
			$str_nilai = "SELECT MIN(n_kual) AS n_kual FROM v_nilai WHERE nim='$nim' AND kmatkul ";
			while (list($k,$v)=each($matkul_syarat)) {
				$kmatkul=$this->getKMatkul($v['kmatkul']);
				$nmatkul=$v['nmatkul'];
				$semester=$v['semester'];
				$minimal_nilai=$v['minimal_nilai'];
				//apakah mahasiswa minimal telah mengambil matakuliah syarat di krs-nya                
				if ($minimal_nilai =='0' || $minimal_nilai =='') {
					$str = $str_krs." LIKE '%$kmatkul%'";
					$this->db->setFieldTable (array('kmatkul'));
					$r=$this->db->getRecord($str);                    
					if (!isset($r[1])) {
                        //cek di Konversian                        
                        $str = "SELECT iddata_konversi FROM v_konversi2 WHERE iddata_konversi='$iddata_konversi' AND kmatkul LIKE '%$kmatkul%'";
                        $this->db->setFieldTable(array('iddata_konversi'));
                        $re=$this->db->getRecord($str);
                        if (!isset($re[1])) {                            
                            throw new Exception ("Matakuliah prasyarat ($kmatkul - $nmatkul di semester $semester ) belum di ambil. <br />Harap di Kontrak terlebih dahulu ");
                            break;
                        }                        
                        
					}
				}else {
					$str = $str_nilai . " LIKE '%$kmatkul%'";
					$this->db->setFieldTable (array('n_kual'));
					$r=$this->db->getRecord($str);			                    
					if (isset($r[1]) && $r[1]['n_kual']!= '') {
						$n_kual_=ord($r[1]['n_kual']);						
						if ($n_kual_ > ord($minimal_nilai)) {
							throw new Exception ("Nilai Matakuliah prasyarat ($kmatkul - $nmatkul di semester $semester minimal nilai $minimal_nilai) Sedangkan Nilai Anda '".$r[1]['n_kual']."'<br />Harap di Kontrak kembali matakuliah prasyarat tsb.");
							break;
						}
                    }elseif($this->db->checkRecordIsExist('iddata_konversi','v_konversi2',$iddata_konversi," AND kmatkul LIKE '%$kmatkul%'")){
                        $str = "SELECT n_kual FROM v_konversi2 WHERE iddata_konversi='$iddata_konversi' AND kmatkul LIKE '%$kmatkul%'";
                        $this->db->setFieldTable(array('n_kual'));
                        $re=$this->db->getRecord($str);
                        $n_kual=$re[1]['n_kual'];
                        if ($n_kual_ > ord($minimal_nilai)) {
							throw new Exception ("Nilai Matakuliah prasyarat ($kmatkul - $nmatkul di semester $semester minimal nilai $minimal_nilai) Sedangkan Nilai Anda '".$r[1]['n_kual']."'<br />Harap di Kontrak kembali matakuliah prasyarat tsb.");
							break;
						}
					}else {
						throw new Exception ("Matakuliah prasyarat ($kmatkul - $nmatkul di semester $semester ) belum di ambil atau Nilai belum di Inputkan.<br /> Cek KHS Anda setelah itu hubungi PRODI.");
						break;
					}
				}
			}
		}
		return true;			
	}
    /**
     * digunakan untuk mendapatkan Data KRS Mahasiswa
     * @param type $tahun
     * @param type $idsmt
     * @return array data krs
     */
	public function getDataKRS ($tahun,$idsmt) {	
        $nim=$this->DataMHS['nim'];        
        $str = "SELECT idkrs,nim,tgl_krs,no_krs,sah,tgl_disahkan,tahun,idsmt,tasmt FROM krs WHERE idsmt=$idsmt AND tahun=$tahun AND nim='$nim'";
        $this->db->setFieldTable(array('idkrs','nim','tgl_krs','no_krs','sah','tgl_disahkan','tahun','idsmt','tasmt'));
        $r=$this->db->getRecord($str);		      
        if (isset($r[1])) {
            $this->DataKRS=$r[1];
        }
        return $this->DataKRS;
	}    
    /**
     * digunakan untuk mendapatkan KRS Mahasiswa
     * @param type $tahun
     * @param type $idsmt
     * @return array data krs dan daftar matakuliah
     */
	public function getKRS ($tahun,$idsmt) {	        
        $nim=$this->DataMHS['nim'];        
        $str = "SELECT idkrs,nim,tgl_krs,no_krs,is_merdeka,sah,tgl_disahkan,tahun,idsmt,tasmt FROM krs WHERE idsmt=$idsmt AND tahun=$tahun AND nim='$nim'";
        $this->db->setFieldTable(array('idkrs','nim','tgl_krs','no_krs','is_merdeka','sah','tgl_disahkan','tahun','idsmt','tasmt'));
        $r=$this->db->getRecord($str);		
        $data=array('krs'=>array(),'matakuliah'=>array());
        if (isset($r[1])) {
            $jumlah_matkul=0;
            $jumlah_sah=0;
            $jumlah_batal=0;
            $data['krs']=$r[1];						
            $str = "SELECT idpenyelenggaraan,idkrsmatkul,kmatkul,nmatkul,sks,semester,batal,nidn,nama_dosen FROM v_krsmhs WHERE idkrs='".$r[1]['idkrs']."' ORDER BY semester ASC,kmatkul ASC";
            $this->db->setFieldTable(array('idpenyelenggaraan','idkrsmatkul','kmatkul','nmatkul','sks','semester','batal','nidn','nama_dosen'));
            $r=$this->db->getRecord($str);
            if (isset($r[1])) {
                while (list($k,$v)=each ($r)) {
                    $v['kmatkul']=$this->getKMatkul($v['kmatkul']);
                    $jumlah_matkul+=1;
                    if ($v['batal'] == 0) {
                        $jumlah_sah+=1;
                    }else{
                        $jumlah_batal+=1;
                    }
                    $result[$k]=$v;
                }
                $data['matakuliah']=$result;					
            }
            $data['krs']['jumlah_matkul']=$jumlah_matkul;
            $data['krs']['jumlah_sah']=$jumlah_sah;
            $data['krs']['jumlah_batal']=$jumlah_batal;
        }          
        $this->DataKRS=$data;
        return $data;
	}    
    /**
     * digunakan untuk mendapatkan Daftar Matakuliah KRS Mahasiswa
     * @param type $tahun
     * @param type $idsmt
     * @return array data daftar matakuliah KRS
     */
	public function getDetailKRS ($idkrs) {	        
        $str = "SELECT idpenyelenggaraan,idkrsmatkul,kmatkul,nmatkul,sks,semester,batal,nidn,nama_dosen FROM v_krsmhs WHERE idkrs=$idkrs ORDER BY semester ASC,kmatkul ASC";
        $this->db->setFieldTable(array('idpenyelenggaraan','idkrsmatkul','kmatkul','nmatkul','sks','semester','batal','nidn','nama_dosen'));
        $r=$this->db->getRecord($str);
        $result=array();
        
        while (list($k,$v)=each ($r)) {
            $v['kmatkul']=$this->getKMatkul($v['kmatkul']);
            $result[$k]=$v;
        }           
        
        return $result;
	}  
    /**
     * method untuk mensahkan krs mahasiswa
     * @param type $idkrs
     */
	public function sahkanKRS ($idkrs) {
        $tgl=date('Y-m-d');
        $jumlah_matkul = $this->db->getCountRowsOfTable("krsmatkul WHERE idkrs='$idkrs'",'idkrsmatkul');
        if ($jumlah_matkul > 0)
        {
            $str = "UPDATE krs SET sah=1,tgl_disahkan='$tgl' WHERE idkrs='$idkrs'";
		    $this->db->updateRecord ($str);
        }
        else
        {
            throw new Exception ('Belum bisa disahkan karena matakuliah tidak ada !!!');
        }
	}
    /**
     * method untuk membatalkan krs mahasiswa
     * @param type $idkrs
     */
    public function batalkanKRS ($idkrs) {
        $str = "UPDATE krs SET sah=0 WHERE idkrs='$idkrs'";
        $this->db->updateRecord ($str);             
    }
}