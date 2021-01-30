<?php
prado::using ('Application.logic.Logic_Akademik');
class Logic_Nilai extends Logic_Akademik {
	/**
	* propery untuk rentang nilai
	*/
	private $RentangNilai = array ('A'=>'A','B'=>'B','C'=>'C','D'=>'D','E'=>'E');	
	/**
	* property untuk angka mutu
	*/
	private $AngkaMutu=array('A'=>4,'B'=>3,'C'=>2,'D'=>1,'E'=>0); 		
    /**
	* Property untuk menyimpan data nilai dari hasil getTranskrip (),getKHS ()
	*/
	private $DataNilai = array();	
    
	public function __construct ($db) {
		parent::__construct ($db);			
	} 
    /**
     * digunakan untuk mendapatkan rentang nilai kualitatif
     */
    public function getRentangNilaiNKual($id=null){
        if ($id==NULL) {
            return $this->RentangNilai;
        }else{
            return $this->RentangNilai[$id];
        }
    }
    /**
     * digunakan untuk mendapatkan rentang nilai kuanitatif
     */
    public function getRentangNilaiNKuan($n_kuan){
        $n_kual='E';
        if ($n_kuan >= 85) {
            $n_kual = 'A';
        }elseif ($n_kuan >= 70 && $n_kuan < 85) {
            $n_kual = 'B';
        }elseif ($n_kuan >= 55 && $n_kuan < 70) {
            $n_kual = 'C';
        }elseif ($n_kuan >= 45 && $n_kuan < 55) {
            $n_kual = 'D';
        }
        return $n_kual;
    }
    /**
     * digunakan untuk mendapatkan data nilai
     * @param type $idx nomor index array
     * @param type $field nama field dari array
     * @return type array atau string
     */
    public function getDataNilai($idx=null,$field=null) {
        if ($idx == null)
            return $this->DataNilai;
        else
            return $this->DataNilai[$idx][$field];
    }
    /**
     * digunakan untuk mendapatkan angka mutu nilai
     * @param type $n_kual
     */
    public function getAngkaMutu ($n_kual) {
        return isset($this->AngkaMutu[$n_kual])?$this->AngkaMutu[$n_kual]:0;
    }   
    /**
     * Digunakan untuk mendapatkan jumlah sks untuk semester selanjutnya	
     * @param float $ips Indeks Prestasi Semester
	*/
	public function getSKSNextSemester ($ips) {					
		$ip=floatval($ips);
		if ($ip >= 3) {
			$sks=24; 
		}elseif ($ip >= 2.5 && $ip < 3) {
			$sks=21;
		}elseif ($ip >= 2 && $ip < 2.5) {
			$sks=18;
		}elseif ($ip >= 1.5 && $ip < 2) {
				$sks=15;
		}else {
			$sks=12;	
		}
		return $sks;		
	}
    /**
     * digunakan untuk mendapatkan nilai konversi
     * @param type $idkur id kurikulum yang berlaku
     * @return type
     */
	public function getNilaiKonversi($iddata_konversi,$idkur) {							
		$str = "SELECT idnilai_konversi,kmatkul,kmatkul_asal,matkul_asal,sks_asal,n_kual FROM v_konversi2 WHERE iddata_konversi='$iddata_konversi'";
		$this->db->setFieldTable(array('idnilai_konversi','kmatkul_asal','kmatkul','matkul_asal','sks_asal','n_kual'));
		$result=$this->db->getRecord($str);
        
        $str = "SELECT kmatkul,nmatkul,sks,semester FROM matakuliah WHERE idkur=$idkur ORDER BY (semester+0),kmatkul ASC";
		$this->db->setFieldTable (array('kmatkul','nmatkul','sks','semester'));
		$listMatkul = $this->db->getRecord($str);		
		$i=1;
		$matkul_nilai=array();
		while (list($k,$v)=each($listMatkul)) {			
			$kmatkul=$v['kmatkul'];
			$matkul_nilai[$i]['no']=$i;
			$matkul_nilai[$i]['kmatkul']=$v['kmatkul'];
			$matkul_nilai[$i]['nmatkul']=$v['nmatkul'];
			$matkul_nilai[$i]['sks']=$v['sks'];			
            $matkul_nilai[$i]['semester']=$v['semester'];
            $kmatkul_asal='';
			$matkul_asal='';
			$sks_asal='';
			$n_kual='';
			$idnilai_konversi='';
			foreach ($result as $m=>$n) {	
				if ($n['kmatkul']==$kmatkul) {		
					$kmatkul_asal=$n['kmatkul_asal'];			
					$matkul_asal=$n['matkul_asal'];
					$sks_asal=$n['sks_asal'];
					$n_kual=$n['n_kual'];
					$idnilai_konversi=$n['idnilai_konversi'];
					break;
				}
			}
			$matkul_nilai[$i]['kmatkul_asal']=$kmatkul_asal;
			$matkul_nilai[$i]['matkul_asal']=$matkul_asal;
			$matkul_nilai[$i]['sks_asal']=$sks_asal;
			$matkul_nilai[$i]['idnilai_konversi']=$idnilai_konversi;
			$matkul_nilai[$i]['n_kual']=$n_kual;			
			$i++;
		}		
		return $matkul_nilai;	
	}
    /**	
	* digunakan untuk mendapatkan nilai transkrip mahasiswa sesuai dengan kurikulum
	* @param $temp boolean
    * @param $cek_isi_kuesioner boolean
    * @version 1.0
	*/
	public function getTranskrip ($temp=true,$cek_isikuesioner=false) {				
		$nim=$this->DataMHS['nim'];        
        $result=array();
		if ($temp) {			
            $idkonsentrasi=$this->DataMHS['idkonsentrasi'];
			$iddata_konversi=$this->DataMHS['iddata_konversi'];				
			$idkur=$this->getIDKurikulum($this->DataMHS['kjur']);	            
			$str="SELECT m.kmatkul,m.nmatkul,m.sks,m.semester,m.idkonsentrasi,k.nama_konsentrasi,m.ispilihan,m.islintas_prodi FROM matakuliah m LEFT JOIN konsentrasi k ON (m.idkonsentrasi=k.idkonsentrasi) WHERE idkur=$idkur AND aktif=1 ORDER BY (semester+0),kmatkul ASC";
			$this->db->setFieldTable(array('kmatkul','nmatkul','sks','semester','idkonsentrasi','nama_konsentrasi','ispilihan','islintas_prodi'));
			$r=$this->db->getRecord($str);			            
			$str = "SELECT n_kual,telah_isi_kuesioner,tahun FROM v_nilai WHERE nim='$nim' AND kmatkul=";
			$str_konversi = "SELECT n_kual FROM v_konversi2 WHERE iddata_konversi='$iddata_konversi' AND kmatkul=";			            
			while (list($k,$v)=each($r)) {	
				$kmatkul=$v['kmatkul'];
				$str2 = $str . "'$kmatkul' ORDER BY n_kual ASC LIMIT 1";                     
                $this->db->setFieldTable (array('n_kual','telah_isi_kuesioner','tahun'));
				$r_nilai=$this->db->getRecord($str2); 	                
				$am=0;
				$m=0;
				$hm='-';		
				$keterangan='';
				if (isset($r_nilai[1])) {
					$hm_biasa=strtoupper($r_nilai[1]['n_kual']);  									
					$hm=$hm_biasa;		
                    if (($cek_isikuesioner==true) && ($r_nilai[1]['telah_isi_kuesioner']==0) && ($r_nilai[1]['tahun'] >= 2015)) {
                        $hm_biasa='';  									
                        $hm=$hm_biasa;		                        
                        $keterangan='BELUM ISI KUESIONER';
                    }
					if ($iddata_konversi) {					
                        $this->db->setFieldTable (array('n_kual'));
						$r_konversi=$this->db->getRecord($str_konversi . "'$kmatkul'"); 				
						if (isset($r_konversi[1])) {
							$hm_konversi=ord(strtoupper($r_konversi[1]['n_kual']));						
							$hm_biasa=ord($hm_biasa);						
							if ($hm_biasa== 0)
								$hm=chr($hm_konversi);				
							elseif ($hm_konversi>$hm_biasa)
								$hm=chr($hm_biasa);						
							else 
								$hm=chr($hm_konversi);										
						}
					}
					$am=$this->AngkaMutu[$hm];
					$m=$am*$v['sks'];				
				}elseif ($iddata_konversi) {
                    $this->db->setFieldTable (array('n_kual'));
					$r_konversi=$this->db->getRecord($str_konversi . "'$kmatkul'");
					if (isset($r_konversi[1])) {
						$hm=$r_konversi[1]['n_kual'];			
						$am=$this->AngkaMutu[$hm];
						$m=$am*$v['sks'];	
					}			
				}	
				$v['n_kual']=($hm=='')?'-':$hm;
				$v['am']=$am;
				$v['m']=$m;	                               
                if ($v['idkonsentrasi'] == 0) {
                    if($v['islintas_prodi'] == 1){
                        $v['keterangan']='Matkul Lintas Prodi '.$keterangan;
                        $result[$k]=$v;					
                    }elseif($v['ispilihan'] == 1) {
                        $v['keterangan']='Matkul Pilihan '.$keterangan;
                        $result[$k]=$v;					
                    }else {
                        $v['keterangan']='- '.$keterangan;
                        $result[$k]=$v;					
                    }
                }elseif($v['idkonsentrasi'] == $idkonsentrasi){
                    $v['keterangan']='Matkul Konsentrasi '.$keterangan;
                    $result[$k]=$v;					
                }
			}			
		}else{
			$str = "SELECT idtranskrip_detail,kmatkul,nmatkul,sks,semester,n_kual FROM transkrip_asli_detail WHERE nim='$nim' ORDER BY (semester+0),kmatkul ASC";
			$this->db->setFieldTable(array('idtranskrip_detail','kmatkul','nmatkul','sks','semester','n_kual'));
			$result=$this->db->getRecord($str);						
		}
		$this->DataNilai=$result;
		return $result;
	}  
    /**	
	* digunakan untuk mendapatkan nilai transkrip mahasiswa nilai sementara	sesuai dengan kurikulum
    * @param cek_isikuesioner bool
    * @version 1.0
	*/
	public function getTranskripNilaiKurikulum ($cek_isikuesioner=false) {
        $nim=$this->DataMHS['nim'];
        $idkonsentrasi=$this->DataMHS['idkonsentrasi'];        
		
        $iddata_konversi=$this->DataMHS['iddata_konversi'];				
        $idkur=$this->getIDKurikulum($this->DataMHS['kjur']);	            
        $str="SELECT m.kmatkul,m.nmatkul,m.sks,m.semester,m.idkonsentrasi,k.nama_konsentrasi,m.ispilihan,m.islintas_prodi,'' AS keterangan FROM matakuliah m LEFT JOIN konsentrasi k ON (m.idkonsentrasi=k.idkonsentrasi) WHERE idkur=$idkur AND aktif=1 ORDER BY (semester+0),kmatkul ASC";
        $this->db->setFieldTable(array('kmatkul','nmatkul','sks','semester','idkonsentrasi','nama_konsentrasi','ispilihan','islintas_prodi','keterangan'));
        $r=$this->db->getRecord($str);			            
        $str = "SELECT n_kual,telah_isi_kuesioner,tahun FROM v_nilai WHERE nim='$nim' AND kmatkul=";
        $str_konversi = "SELECT n_kual,tahun FROM v_konversi2 WHERE iddata_konversi='$iddata_konversi' AND kmatkul=";        
        $result=array();
        while (list($k,$v)=each($r)) {	
            $kmatkul=$v['kmatkul'];
            $str2 = $str . "'$kmatkul' ORDER BY n_kual ASC LIMIT 1";
            $this->db->setFieldTable (array('n_kual','telah_isi_kuesioner','tahun'));
            $r_nilai=$this->db->getRecord($str2); 	                
            $am=0;
            $m=0;
            $hm='-';		
            if (isset($r_nilai[1])) {
                $hm_biasa=strtoupper($r_nilai[1]['n_kual']);  									
                $hm=$hm_biasa;		
                if (($cek_isikuesioner==true) && ($r_nilai[1]['telah_isi_kuesioner']==0) && ($r_nilai[1]['tahun'] >= 2015)) {
                    $hm_biasa='';  									
                    $hm=$hm_biasa;		                    
                    $v['keterangan']='BELUM ISI KUESIONER';
                }
                if ($iddata_konversi > 0) {		
                    $this->db->setFieldTable (array('n_kual'));			
                    $r_konversi=$this->db->getRecord($str_konversi . "'$kmatkul'"); 				
                    if (isset($r_konversi[1])) {
                        $hm_konversi=ord(strtoupper($r_konversi[1]['n_kual']));						
                        $hm_biasa=ord($hm_biasa);						
                        if ($hm_biasa== 0)
                            $hm=chr($hm_konversi);				
                        elseif ($hm_konversi>$hm_biasa)
                            $hm=chr($hm_biasa);						
                        else 
                            $hm=chr($hm_konversi);										
                    }
                }
                $am=$this->AngkaMutu[$hm];
                $m=$am*$v['sks'];				
            }elseif ($iddata_konversi > 0) {
                $this->db->setFieldTable (array('n_kual'));
                $r_konversi=$this->db->getRecord($str_konversi . "'$kmatkul'");
                if (isset($r_konversi[1])) {
                    $hm=$r_konversi[1]['n_kual'];			
                    $am=$this->AngkaMutu[$hm];
                    $m=$am*$v['sks'];                    
                }			
            }	
            $v['n_kual']=($hm=='')?'-':$hm;
            $v['am']=$am;
            $v['m']=$m;		                        
            if ($v['idkonsentrasi'] == 0) {               
                if($v['islintas_prodi'] == 1){
                    if ($v['n_kual']!='-') {
                        $v['keterangan']='Matkul Lintas Prodi '.$v['keterangan'];
                        $result[$k]=$v;					
                    }
                }elseif(($v['ispilihan'] == 1)) {
                    if ($v['n_kual']!='-') {
                        $v['keterangan']='Matkul Pilihan '.$v['keterangan'];
                        $result[$k]=$v;					
                    }
                }elseif ($v['islintas_prodi'] == 0) {
                    $v['keterangan']='- '.$v['keterangan'];
                    $result[$k]=$v;					
                }
            }elseif(($v['idkonsentrasi'] == $idkonsentrasi) && $v['n_kual']!='-'){
                $v['keterangan']='Matkul Konsentrasi '.$v['keterangan'];
                $result[$k]=$v;					
            }
        }		
		
		$this->DataNilai=$result;
		return $result;
    }
    /**
     * digunakan untuk mendapatkan nilai transkrip mahasiswa dari KRS     
     * @return array daftar nilai
     * @param cek_isikuesioner bool
     */
    public function getTranskripFromKRS($cek_isikuesioner=false) {        
		$nim=$this->DataMHS['nim'];		
        $str = "SELECT vnk.kmatkul,vnk.nmatkul,vnk.sks,semester,IF(char_length(COALESCE(vnk2.n_kual,''))>0,vnk2.n_kual,'-') AS n_kual,telah_isi_kuesioner,vnk.tahun FROM v_nilai_khs vnk,(SELECT idkrsmatkul,MIN(n_kual) AS n_kual FROM `v_nilai_khs` WHERE nim='$nim' AND n_kual IS NOT NULL GROUP BY kmatkul ORDER BY (semester+0), kmatkul ASC) AS vnk2 WHERE vnk.idkrsmatkul=vnk2.idkrsmatkul";        
		$this->db->setFieldTable(array('kmatkul','nmatkul','sks','semester','n_kual','telah_isi_kuesioner','tahun'));
        
		$r=$this->db->getRecord($str);        

        $result=array();
        while (list($k,$v)=each($r)) {	
            if ($v['tahun']>=2015){                
                if ($cek_isikuesioner) {
                    if ($v['telah_isi_kuesioner']==0) {                    
                        $v['sks']=0;           
                        $v['n_kual']='-';
                        $v['am']=0;
                        $v['m']=0;  
                        $v['keterangan']='ISI KUESIONER DI KHS';                                    
                    }else{
                        $am=$this->AngkaMutu[$v['n_kual']];
                        $m=$am*$v['sks'];
                        $v['am']=$am;
                        $v['m']=$m;	                
                        $v['keterangan']='-';                
                    }
                }else{
                    $am=$this->AngkaMutu[$v['n_kual']];
                    $m=$am*$v['sks'];
                    $v['am']=$am;
                    $v['m']=$m;	                
                    $v['keterangan']='-';                
                }
            }else{
                $am=$this->AngkaMutu[$v['n_kual']];
                $m=$am*$v['sks'];
                $v['am']=$am;
                $v['m']=$m;	                
                $v['keterangan']='-'; 
            }            
            $result[$k]=$v;
        }
        $this->DataNilai=$result;        
        return $this->DataNilai;
    } 
    /**
     * digunakan untuk mendapatkan nilai transkrip mahasiswa dari KRS dan konversi     
     * @return array daftar nilai
     * @param cek_isikuesioner bool
     */
    public function getTranskripFromKonversidanKRS($cek_isikuesioner=false) {        
		$nim=$this->DataMHS['nim'];	
        $iddata_konversi=$this->DataMHS['iddata_konversi'];
        $str = "SELECT vnk.kmatkul,vnk.nmatkul,vnk.sks,semester,IF(char_length(COALESCE(vnk2.n_kual,''))>0,vnk2.n_kual,'-') AS n_kual,telah_isi_kuesioner,vnk.tahun FROM v_nilai_khs vnk,(SELECT idkrsmatkul,MIN(n_kual) AS n_kual FROM `v_nilai_khs` WHERE nim='$nim' AND n_kual IS NOT NULL GROUP BY kmatkul ORDER BY (semester+0), kmatkul ASC) AS vnk2 WHERE vnk.idkrsmatkul=vnk2.idkrsmatkul";        
		$this->db->setFieldTable(array('kmatkul','nmatkul','sks','semester','n_kual','telah_isi_kuesioner','tahun'));
        
		$r=$this->db->getRecord($str);        

        $result=array();
        while (list($k,$v)=each($r)) {	
            if ($v['tahun']>=2015){                
                if ($cek_isikuesioner) {
                    if ($v['telah_isi_kuesioner']==0) {                    
                        $v['sks']=0;           
                        $v['n_kual']='-';
                        $v['am']=0;
                        $v['m']=0;  
                        $v['keterangan']='ISI KUESIONER DI KHS';                                    
                    }else{
                        $am=$this->AngkaMutu[$v['n_kual']];
                        $m=$am*$v['sks'];
                        $v['am']=$am;
                        $v['m']=$m;	                
                        $v['keterangan']='-';                
                    }
                }else{
                    $am=$this->AngkaMutu[$v['n_kual']];
                    $m=$am*$v['sks'];
                    $v['am']=$am;
                    $v['m']=$m;	                
                    $v['keterangan']='-';                
                }
            }else{
                $am=$this->AngkaMutu[$v['n_kual']];
                $m=$am*$v['sks'];
                $v['am']=$am;
                $v['m']=$m;	                
                $v['keterangan']='-'; 
            }            
            $result[$v['kmatkul']]=$v;
        }
        if ($iddata_konversi > 0) {
            $str_konversi = "SELECT kmatkul,nmatkul,sks,semester,n_kual,tahun FROM v_konversi2 WHERE iddata_konversi='$iddata_konversi'";
            $this->db->setFieldTable(array('kmatkul','nmatkul','sks','semester','n_kual','tahun'));
            $r_nilai=$this->db->getRecord($str_konversi);
            while (list($k,$v)=each($r_nilai)) {
                if (isset($result[$v['kmatkul']])) {
                    $hm_konversi=ord(strtoupper($v['n_kual']));						
                    $hm_biasa=ord($result[$v['kmatkul']]['n_kual']);						
                    if ($hm_konversi<$hm_biasa) {
                        $v['n_kual']=chr($hm_konversi);
                        $am=$this->AngkaMutu[$v['n_kual']];
                        $m=$am*$v['sks'];
                        $v['am']=$am;
                        $v['m']=$m;	  
                        $v['keterangan']='NILAI KONVERSI';
                        $result[$v['kmatkul']]=$v;
                    }
                }else{
                    $am=$this->AngkaMutu[$v['n_kual']];
                    $m=$am*$v['sks'];
                    $v['am']=$am;
                    $v['m']=$m;	  
                    $v['keterangan']='NILAI KONVERSI';
                    $result[$v['kmatkul']]=$v;
                }
            }
        }
        
        $this->DataNilai=$result;        
        return $this->DataNilai;
    }
    /**
     * digunakan untuk mendapatkan nilai KHS
     * @param type $ta
     * @param type $idsmt
     * @param type $cek_isikuesioner
     * @return array daftar nilai KHS
     */
	public function getKHS ($ta,$idsmt,$cek_isikuesioner=false) {
        $nim=$this->DataMHS['nim'];
		$str = "SELECT idkrsmatkul,idsmt,tahun,kmatkul,nmatkul,sks,n_kual,nidn,nama_dosen,telah_isi_kuesioner FROM v_nilai_khs WHERE nim='$nim' AND idsmt=$idsmt AND tahun=$ta AND aktif=1";
		$this->db->setFieldTable(array('idkrsmatkul','idsmt','tahun','kmatkul','nmatkul','sks','n_kual','nidn','nama_dosen','telah_isi_kuesioner'));
		$r=$this->db->getRecord($str);		
		$result=array();
        if ($cek_isikuesioner) {
            while (list($a,$b)=each($r)) {
                $b['kmatkul']=$this->getKMatkul($b['kmatkul']);
                $hm=$b['n_kual'];
                if ($b['tahun']>=2015){
                    if ($b['telah_isi_kuesioner']) {  
                        if ($hm == '') {
                            $am=0;
                            $m=0;
                            $hm='-';
                        }else {
                            $am=$this->AngkaMutu[$hm];
                            $m=$am*$b['sks'];
                        }			
                        $b['am']=$am;
                        $b['m']=$m;
                        $b['bool_isi_kuesioner']=false;
                    }else{                    
                        $b['keterangan']=$hm == ''?'BELUM ISI NILAI':'BELUM ISI KUESIONER';
                        $b['bool_isi_kuesioner']=$hm == ''?false:true;
                        $b['n_kual']='';
                        $b['am']=0;
                        $b['m']=0;                
                    }
                    $result[$a]=$b;
                }else{                                
                    if ($hm == '') {
                        $am=0;
                        $m=0;
                        $hm='-';
                    }else {
                        $am=$this->AngkaMutu[$hm];
                        $m=$am*$b['sks'];
                    }	                   
                    if ($b['telah_isi_kuesioner']) {
                        $b['keterangan']='-';
                    }else{
                        $b['keterangan']=$hm == '-'?'BELUM ISI NILAI':'BELUM ISI KUESIONER';
                    }
                    $b['am']=$am;
                    $b['m']=$m;

                    $result[$a]=$b;
                }			
            }
        }else{
            while (list($a,$b)=each($r)) {
                $b['kmatkul']=$this->getKMatkul($b['kmatkul']);
                $hm=$b['n_kual'];                
                if ($hm == '') {
                    $am=0;
                    $m=0;
                    $hm='-';
                }else {
                    $am=$this->AngkaMutu[$hm];
                    $m=$am*$b['sks'];
                }			
                $b['am']=$am;
                $b['m']=$m;
                if ($b['telah_isi_kuesioner']) {
                    $b['keterangan']='-';
                }else{
                    $b['keterangan']=$hm == '-'?'BELUM ISI NILAI':'BELUM ISI KUESIONER';
                }
                $result[$a]=$b;                    
            }
        }
		$this->DataNilai=$result;		
		return $result;
	}
    /**
     * Digunakan untuk mendapatkan khs sebelum semester sekarang	
     * @param type $ta
     * @param type $idsmt
     * @param type $cek_isikuesioner
     * @return array daftar nilai KHS
	*/
	public function getKHSBeforeCurrentSemester ($ta,$idsmt,$cek_isikuesioner=false) {
		$nim=$this->DataMHS['nim'];
        $current_tasmt=$ta.$idsmt;
		$str = ($this->getDataMHS('tahun_masuk')==$ta&&$this->getDataMHS('semester_masuk')==$idsmt)?"SELECT MAX(tasmt) AS tasmt FROM krs WHERE nim='$nim' AND tasmt <= $current_tasmt AND idsmt!=3":"SELECT MAX(tasmt) AS tasmt FROM krs WHERE nim='$nim' AND tasmt < $current_tasmt AND idsmt!=3";
		$this->db->setFieldTable(array('tasmt'));        
		$r=$this->db->getRecord($str);		
		if (!empty($r[1]['tasmt'])) {
            $tasmt=$r[1]['tasmt'];
			$str = "SELECT idkrs,tahun,idsmt FROM krs WHERE nim='$nim' AND tasmt=$tasmt AND idsmt!=3";
			$this->db->setFieldTable(array('idkrs','tahun','idsmt'));
			$r=$this->db->getRecord($str);			            
            $this->getKHS($r[1]['tahun'],$r[1]['idsmt']);                        
		}
		return $this->DataNilai;
	}
    /**
     * digunakan untuk mengetahui jumlah SKS dan NM semester yang lalu
     * @param type $tahun_sekarang
     * @param type $semester_sekarang
     * @return type array
     */
	public function getKumulatifSksDanNmSemesterLalu($tahun_sekarang,$semester_sekarang) {
		$ta_smt=$tahun_sekarang.$semester_sekarang;
		$nim=$this->DataMHS['nim'];
		
        $str = "SELECT n_kual,sks FROM v_nilai_khs vnk,(SELECT idkrsmatkul,MIN(n_kual) FROM `v_nilai_khs` WHERE nim='$nim' AND tasmt < $ta_smt GROUP BY kmatkul ORDER BY kmatkul ASC) AS vnk2 WHERE vnk.idkrsmatkul=vnk2.idkrsmatkul";
		$this->db->setFieldTable(array('sks','n_kual'));
		$result=$this->db->getRecord($str);
		
		$nilai=array('total_sks'=>0,'total_nm'=>0);
		if (isset($result[1])) {
            $total_sks=0;
            $total_m=0;
			while (list($k,$v)=each($result ) ) {                
                $total_sks=$total_sks+$v['sks'];                                                                
                $angka_m=isset($this->AngkaMutu[$v['n_kual']])?$this->AngkaMutu[$v['n_kual']]:0;
                $total_m=$total_m+($angka_m*$v['sks']);                
			}
			$nilai['total_sks']=$total_sks;
			$nilai['total_nm']=$total_m;
		}
		return $nilai;
	}
    /**
     * digunakan untuk mengetahui IPK sampai dengan tahun dan semester tertentu
     * @param int $tahun_sekarang
     * @param int $semester_sekarang
     * @return float ipk
     */
    public function getIPKSampaiTASemester($tahun_sekarang,$semester_sekarang,$mode=null) {
        $ta_smt=$tahun_sekarang.$semester_sekarang;
		$nim=$this->DataMHS['nim'];
		
        $str = "SELECT n_kual,sks FROM v_nilai_khs vnk,(SELECT idkrsmatkul,MIN(n_kual),telah_isi_kuesioner FROM `v_nilai_khs` WHERE nim='$nim' AND tasmt <= $ta_smt GROUP BY kmatkul ORDER BY kmatkul ASC) AS vnk2 WHERE vnk.idkrsmatkul=vnk2.idkrsmatkul";        
		$this->db->setFieldTable(array('sks','n_kual'));
		$this->DataNilai=$this->db->getRecord($str);
        
        if ($mode == null) {
            return $this->getIPSAdaNilai();        
        }else{
            $data=array('ipk'=>'0.00','sks'=>0);
            switch ($mode) {
                case 'ipksks' :
                    $data['ipk']=$this->getIPSAdaNilai();
                    $data['sks']=$this->getTotalSKS();                    
                break;
                case 'ipksksnm' :
                    $data['ipk']=$this->getIPSAdaNilai();
                    $data['sks']=$this->getTotalSKS();   
                    $data['nm']=$this->getTotalM();
                break;
            }            
            return $data;
        }
        
    }
    /**
	* dapatkan total matakuliah yang ada pada Data Nilai;	
	*/
	public function getTotalMatkul ()  {		
		$totalMatkul=0;
        $dn=$this->DataNilai;
		if (count($dn) > 0) {								
			while (list($a,$b)=each($dn)) {											
                $totalMatkul+=1;					
			}			
		}	
		return $totalMatkul;
	}
    /**
	* dapatkan total matakuliah yang ada pada Data Nilai yang ada nilai;	
	*/
	public function getTotalMatkulAdaNilai ()  {		
		$totalMatkul=0;
        $dn=$this->DataNilai;
		if (count($dn) > 0) {								
			while (list($a,$b)=each($dn)) {							
				if ($b['n_kual']!='-' && $b['n_kual']!='') {					
					$totalMatkul+=1;					
				}			
			}			
		}	
		return $totalMatkul;
	}
    /**
     * digunakan untuk mendapatkan seluruh total sks 
     * @return total nilai
     */
	public function getTotalSKS ()  {
		$totalSks=0;		
        $dn=$this->DataNilai;
		if (count($dn) > 0) {													
			while (list($a,$b)=each($dn)) {						
                $totalSks+=$b['sks'];												
			}			
		}
		return $totalSks;
	}
    /**
	* dapatkan total sks MHS yang ada nilainya.
	* @version 1.0
	*/
	public function getTotalSKSAdaNilai ()  {
		$totalSks=0;		
        $dn=$this->DataNilai;
		if (count($dn) > 0) {													
			while (list($a,$b)=each($dn)) {		
				if ($b['n_kual']!='-' && $b['n_kual']!='') {					
					$totalSks+=$b['sks'];
				}							
			}			
		}          
		return $totalSks;
	}
    /**
	* total seluruh M (sks * angka mutu)
	* 
	* @return integer
	*/
	public function getTotalM () {
		$countM=0;
		$dn=$this->DataNilai;
		if (count($dn) > 0) {
			while (list($a,$b)=each($dn)) {						
				if ($b['n_kual'] != "" &&$b['n_kual'] != "-") {				             				
					$m = (intval($b['sks'])) * $this->AngkaMutu[$b['n_kual']]; 
					$countM = $countM+$m;
				}        
			}			
		}
		return $countM;		
	}    
    /**
     * digunakan untuk mendapatkan Indeks Prestasi Sementara (IPS)
     * @return IPS
     */
    public function getIPS() {				
        $totalSks=0;
        $dn=$this->DataNilai;
        $countM=0;
        while (list($k,$v)=each($dn)) {
            $sks=$v['sks'];				        				            
            $totalSks += $sks;      
            $n_kual = isset($this->AngkaMutu[$v['n_kual']])?$this->AngkaMutu[$v['n_kual']]:0;
            $m = (intval($sks)) * $n_kual; 
            $countM = $countM+$m;            
        }			
        if ($ips=@ bcdiv($countM,$totalSks,2) ) {							
            return $ips;
        }else {
            return '0.00';
        }
	}
    /**
     * digunakan untuk mendapatkan Indeks Prestasi Sementara (IPS) yang ada nilai-nya     
     * @return IPS
     */
    public function getIPSAdaNilai() {				
        $totalSks=0;
        $dn=$this->DataNilai;
        $countM=0;
        while (list($k,$v)=each($dn)) {
            $sks=$v['sks'];				        				
            if ($v['n_kual'] != '' && $v['n_kual'] != '-') {                            					
                $totalSks += $sks;      
                $m = (intval($sks)) * $this->AngkaMutu[$v['n_kual']]; 
                $countM = $countM+$m;
            }        
        }			
        if ($ips=@ bcdiv($countM,$totalSks,2) ) {							
            return $ips;
        }else {
            return '0.00';
        }				
	}
    /**
     * digunakan untuk mendapatkan Indeks Prestasi Kumulatif (IPK) yang ada nilai-nya     
     * @return IPK
     */
    public function getIPKAdaNilai() {				
        $totalSks=0;
        $dn=$this->DataNilai;
        $countM=0;
        while (list($k,$v)=each($dn)) {
            $sks=$v['sks'];				        				
            if ($v['n_kual'] != '' && $v['n_kual'] != '-') {                            					
                $totalSks += $sks;      
                $m = (intval($sks)) * $this->AngkaMutu[$v['n_kual']]; 
                $countM = $countM+$m;
            }        
        }			
        if ($ipk=@ bcdiv($countM,$totalSks,2) ) {							
            return $ipk;
        }else {
            return '0.00';
        }
	}
    
    /**
     * digunakan untuk mendapatkan max sks pada semester
     * @param type $ta
     * @param type $idsmt
     * @return type
     */
	public function getMaxSKS ($ta,$idsmt) {		
		$ta_masuk=$this->DataMHS['tahun_masuk'];
		$semester_masuk=$this->DataMHS['semester_masuk'];		        
		if ($ta==$ta_masuk && $idsmt==$semester_masuk) {
            $setup=$this->getLogic('Setup');
			$jumlahsks=$setup->getSettingValue('jumlah_sks_krs_mhs_baru');
		}else {				
			$this->getKHSBeforeCurrentSemester($ta,$idsmt);			
			$jumlahsks=$this->getSKSNextSemester($this->getIPS());				
		}
        return $jumlahsks;
	}    
}