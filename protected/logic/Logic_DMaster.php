<?php
/**
*
* digunakan untuk memproses master data
*
*/
prado::using ('Application.logic.Logic_Global');
class Logic_DMaster extends Logic_Global {  
    /**
     * informasi dosen
     * @var type array
     */
    public $DataDosen = array();
    public $StatusPendaftaranKonsentrasi = array(0=>'REGISTERED',1=>'APPROVED');
    public $StatusPendaftaranKampusMerdeka = array(0=>'REGISTERED',1=>'APPROVED');
	public function __construct ($db) {
		parent::__construct ($db);	          
	}
    /**
     * digunakan untuk mendapatkan daftar agama
     */
	public function getListAgama () {                             
        $dataitem=$this->getList ("agama WHERE idagama!=0",array('idagama','nama_agama'),'idagama',null,1);			
        $dataitem['none']='Daftar Agama';        
        return $dataitem;     		
	}
    /**
     * digunakan untuk mendapatkan daftar pekerjaan
     */
	public function getListJenisPekerjaan () {                             
        $dataitem=$this->getList ("jenis_pekerjaan WHERE idjp!=0",array('idjp','nama_pekerjaan'),'idjp',null,1);			
        $dataitem['none']='Daftar Jenis Pekerjaan';        
        return $dataitem;     		
	}
    /**
     * digunakan untuk mendapatkan daftar status pendaftaran konsentrasi
     */
    public function getStatusPendaftaranKonsentrasi ($idstatus=null) {
        if ($idstatus == NULL ) {
            return $this->StatusPendaftaranKonsentrasi;
        }else{
            return $this->StatusPendaftaranKonsentrasi[$idstatus];
        }
    }
    /**
     * digunakan untuk mendapatkan daftar status pendaftaran kampus merdeka
     */
    public function getStatusPendaftaranKampusMerdeka ($idstatus=null) {
        if ($idstatus == NULL ) {
            return $this->StatusPendaftaranKampusMerdeka;
        }else{
            return $this->StatusPendaftaranKampusMerdeka[$idstatus];
        }
    }
    /**
     * digunakan untuk mendapatkan daftar Tahun Akademik     
     * @param int $start_tahun tahun awal
     */
    public function getListTA ($start_tahun=null) {
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listta');            
            if (!isset($dataitem['none'])) {
                $dataitem=$this->getList('ta',array('tahun','tahun_akademik'),'tahun',null,1);
                $dataitem['none']='Daftar Tahun Akademik';    
                $this->Application->Cache->set('listta',$dataitem);
            }
        }else {                        
            $dataitem=$this->getList('ta',array('tahun','tahun_akademik'),'tahun',null,1);
            $dataitem['none']='Daftar Tahun Akademik';
        }
        if ($start_tahun !== null) {
            $a=$dataitem;
            $dataitem=array();
            while (list($k,$v)=each($a)) { 
                if ($k >= $start_tahun){
                    $dataitem[$k]=$v;
                }
            }
        }
        return $dataitem;        
	}
    /**
     * digunakan untuk mendapatkan nama tahun akademik
     * @param int $tahun 
     */
    public function getNamaTA ($tahun) {
        if ($this->Application->Cache) {            
            $dataitem=$this->getListTA();
            $nama_item=$dataitem[$tahun];
        }else {                        
            $dataitem=$this->getList("ta WHERE tahun=$tahun",array('tahun_akademik'),'tahun',null,1);
            $nama_item=$dataitem[1]['tahun_akademik'];
        }        
        return $nama_item;        
	}
	/**
     * digunakan untuk mendapatkan daftar kelas
     */
	public function getListKelasPendaftaran () {
        $dataitem['none']='PILIH KELAS';
        $dataitem['A']='REGULER (S1)';
        $dataitem['B']='KARYAWAN (S1)';
        return $dataitem;     		
	}
	/**
     * digunakan untuk mendapatkan daftar kelas
     */
	public function getListKelas () {
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listkelas');            
            if (!isset($dataitem['none'])) {                
                $dataitem=$this->getList ('kelas',array('idkelas','nkelas'),'idkelas',null,1);			
                $dataitem['none']='Daftar Kelas';    
                $this->Application->Cache->set('listkelas',$dataitem);
            }
        }else {                        
            $dataitem=$this->getList ('kelas',array('idkelas','nkelas'),'nkelas',null,1);			
            $dataitem['none']='Daftar Kelas';
        }
        return $dataitem;     		
	}
    /**
     * digunakan untuk mendapatkan nama kelas
     * @param type $idkelas
     * @return type
     */
    public function getNamaKelasByID ($idkelas) {	
        if ($this->Application->Cache) {            
            $dataitem=$this->getListKelas();
            $nama_item=$dataitem[$idkelas];
        }else {
            $dataitem=$this->getList("kelas WHERE idkelas=$idkelas",array('nkelas'),'nkelas');
            $nama_item=$dataitem[1]['nkelas'];                               
        }
        return $nama_item;
    }
    /**
     * digunakan untuk mendapatkan daftar ruang kelas
     */
	public function getRuangKelas () {
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listruangkelas');            
            if (!isset($dataitem['none'])) {                
                $dataitem=$this->getList ('ruangkelas',array('idruangkelas','namaruang','kapasitas'),'namaruang',null,2);			
                $dataitem['none']='Daftar Ruang Kelas';    
                $this->Application->Cache->set('listruangkelas',$dataitem);
            }
        }else {                        
            $dataitem=$this->getList ('ruangkelas',array('idruangkelas','namaruang','kapasitas'),'namaruang',null,2);			
            $dataitem['none']='Daftar Ruang Kelas';
        }
        return $dataitem;     		
	}
    /**
     * digunakan untuk mendapatkan kapasitas ruang kelas
     */
	public function getKapasitasRuangKelas ($idruangkelas) {
        if ($this->Application->Cache) {            
            $ruangkelas=$this->Application->Cache->get('listruangkelas');            
            if (!isset($ruangkelas['none'])) {                
                $ruangkelas=$this->getRuangKelas();
            }
            $result=  explode('-', $ruangkelas[$idruangkelas]);
            $dataitem=$result[1];
        }else {                        
            $str = "SELECT kapasitas FROM ruangkelas WHERE idruangkelas=$idruangkelas";
            $this->db->setFieldTable(array('kapasitas'));
            $result=$this->db->getRecord($str);
            $dataitem=isset($result[1])?$result[1]['kapasitas']:0;  
        }
        return $dataitem;     		
	}
    /**
     * digunakan untuk mendapatkan daftar alias program studi
     */
    public function getListAliasProgramStudi () {
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listaliasprodi');            
            if (!isset($dataitem[1])) { 
                $dataitem=$this->getList ("program_studi ps WHERE ps.kjur!=0",array('kjur','nama_ps_alias'),'nama_ps_alias',null,1);			                
                $this->Application->Cache->set('listaliasprodi',$dataitem);
            }
        }else {                        
            $str = 'SELECT ps.kjur,ps.nama_ps_alias FROM program_studi ps WHERE ps.kjur!=0';
            $this->db->setFieldTable(array('kjur','nama_ps_alias'));
            $dataitem = $this->db->getRecord($str);            
        }
        
        return $dataitem;        
    }       
	 /**
     * digunakan untuk mendapatkan nama ps
     * @param type $kjur
     * @return type
     */
    public function getNamaProgramStudiByID ($kjur,$mode=2) {
		$daftar_prodi=$this->getListProgramStudi($mode);
		return $daftar_prodi[$kjur];
	}
    /**
     * digunakan untuk mendapatkan nama alias ps
     * @param type $kjur
     * @return type
     */
    public function getNamaAliasProgramStudiByID ($kjur) {	
        if ($this->Application->Cache) {            
            $dataitem=$this->getListAliasProgramStudi ();
            $nama_item=$dataitem[$kjur];
        }else {
            $dataitem=$this->getList("nama_ps_alias WHERE kjur=$kjur",array('nama_ps_alias'),'nama_ps_alias');
            $nama_item=$dataitem[1]['nama_ps_alias'];                               
        }
        return $nama_item;
    }
    /**
     * digunakan untuk mendapatkan daftar program studi
     */
    public function getListProgramStudi ($mode=0) {
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listprodi');            
            if (!isset($dataitem[1])) {                
                $str = 'SELECT ps.kjur,ps.kode_epsbed,ps.nama_ps,ps.kjenjang,js.njenjang,konsentrasi FROM program_studi ps,jenjang_studi js WHERE js.kjenjang=ps.kjenjang AND ps.kjur!=0';
                $this->db->setFieldTable(array('kjur','kode_epsbed','nama_ps','njenjang','konsentrasi'));
                $dataitem = $this->db->getRecord($str);                
                $this->Application->Cache->set('listprodi',$dataitem);
            }
        }else {                        
            $str = 'SELECT ps.kjur,ps.kode_epsbed,ps.nama_ps,ps.kjenjang,js.njenjang,konsentrasi FROM program_studi ps,jenjang_studi js WHERE js.kjenjang=ps.kjenjang AND ps.kjur!=0';
            $this->db->setFieldTable(array('kjur','kode_epsbed','nama_ps','njenjang','konsentrasi'));
            $dataitem = $this->db->getRecord($str);            
        }
        $dataprodi=array();        
        switch($mode) {
			case 0 :
				$dataprodi=$dataitem;
			break;
			case 1 :
				$dataprodi['none']='Daftar Program Studi';				
				while (list($k,$v)=each($dataitem)) {	
                    if ($v['konsentrasi'] == '') {
                        $dataprodi[$v['kjur']]=$v['nama_ps'];
                    }else{
                        $dataprodi[$v['kjur']]=$v['nama_ps'] . ' KONS. '.$v['konsentrasi'];
                    }				
				}				
			break;
			case 2 :
                $dataprodi['none']='Daftar Program Studi';				
				while (list($k,$v)=each($dataitem)) {			
                    if ($v['konsentrasi'] == '') {
                        $dataprodi[$v['kjur']]=$v['nama_ps'] . ' ('.$v['njenjang'].')';
                    }else{
                        $dataprodi[$v['kjur']]=$v['nama_ps'] . ' KONS. '.$v['konsentrasi'].' ('.$v['njenjang'].')';
                    }					
				}				
			break;
		}
        return $dataprodi;        
    }       
    /**
	* digunakan untuk menghapus kjur, ini berlaku hanya key-nya adalah kjur	
	*/
	public function removeKjur ($listJur,$kjur) {
		foreach ($listJur as $k=>$v) {
			if ($k != $kjur) {
				$result[$k]=$v;
			}
		}		
		return $result;	
	}
    /**
	* untuk mendapatkan daftar dosen wali	
	* @return daftar dosen wali di dalam array	
	*/	
	public function getListDosenWali () {		        
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listdw');          
            $jumlah_item=is_array($dataitem) ? count($dataitem) : 0;            
            if ($jumlah_item < 1) {                
                $str = "SELECT dw.iddosen_wali,d.nidn,CONCAT(d.gelar_depan,' ',d.nama_dosen,' ',d.gelar_belakang) AS nama_dosen FROM dosen d,dosen_wali dw WHERE d.iddosen=dw.iddosen ORDER BY idjabatan DESC,nama_dosen ASC";
                $this->db->setFieldTable(array('iddosen_wali','nidn','nama_dosen'));			        
                $r = $this->db->getRecord($str);                
                $dataitem['none']='Daftar Dosen Wali';				
				while (list($k,$v)=each($r)) {			
					$dataitem[$v['iddosen_wali']]=$v['nama_dosen'] . ' ['.$v['nidn'].']';	;					
				}	
                $this->Application->Cache->set('listdw',$dataitem);
            }
        }else{
            $str = "SELECT dw.iddosen_wali,d.nidn,CONCAT(d.gelar_depan,' ',d.nama_dosen,' ',d.gelar_belakang) AS nama_dosen FROM dosen d,dosen_wali dw WHERE d.iddosen=dw.iddosen";
            $this->db->setFieldTable(array('iddosen_wali','nidn','nama_dosen'));			        
            $r = $this->db->getRecord($str);                
            $dataitem['none']='Daftar Dosen Wali';				
            while (list($k,$v)=each($r)) {			
                $dataitem[$v['iddosen_wali']]=$v['nama_dosen'] . ' ['.$v['nidn'].']';				
            }	
        }
        return $dataitem;
	}
    /**
	* untuk mendapatkan nama dosen wali	
	* @return nama dosen wali 
	*/	
	public function getNamaDosenWaliByID ($iddosen_wali) {	
        if ($this->Application->Cache) {            
            $dataitem=$this->getListDosenWali();
            $nama_item=$dataitem[$iddosen_wali];
        }else {
            $str = "SELECT nidn,CONCAT(d.gelar_depan,' ',d.nama_dosen,' ',d.gelar_belakang) AS nama_dosen FROM dosen d,dosen_wali dw WHERE d.iddosen=dw.iddosen AND dw.iddosen_wali=$iddosen_wali";
            $this->db->setFieldTable(array('nidn','nama_dosen'));			        
            $r = $this->db->getRecord($str);                            
            $nama_item=isset($r[1])?$r[1]['nama_dosen'] . ' ['.$r[1]['nidn'].']':'';                    
        }
        return $nama_item;
    }
    /**
     * digunakan untuk mendapatkan daftar kecamatan
     * @param $kjur 
     */
    public function getListKonsentrasiProgramStudi ($kjur=null) {
        if ($this->Application->Cache) {            
            $r=$this->Application->Cache->get('listkonsentrasi');            
            if (!isset($r[1])) {            
                $str = "SELECT idkonsentrasi,kjur,nama_konsentrasi FROM konsentrasi";
                $this->db->setFieldTable(array('idkonsentrasi','kjur','nama_konsentrasi'));			        
                $r = $this->db->getRecord($str);                       
                $this->Application->Cache->set('listkonsentrasi',$r);
            }
            if ($kjur === null) {                
                $dataitem=$r;
            }else{
                $dataitem['none']='Daftar Konsentrasi Program Studi';
                while (list($k,$v)=each($r)) {
                    if ($kjur == $v['kjur']) {
                        $dataitem[$v['idkonsentrasi']]=$v['nama_konsentrasi'];
                    }
                }
            }
        }else {                      
            $str_kjur=$kjur === null ?'':" WHERE kjur='$kjur'";
            $dataitem=$this->getList("konsentrasi$str_kjur",array('idkonsentrasi','nama_konsentrasi'),'nama_konsentrasi',null,1);
            $dataitem['none']='Daftar Konsentrasi Program Studi';
        }
        return $dataitem;        
    }
    /**
    * digunakan untuk mendapatkan nama konsentrasi berdasarkan idkonsentrasi
	* @return nama konsentrasi
	*/	
	public function getNamaKonsentrasiByID ($idkonsentrasi,$kjur=null) {
        if ($this->Application->Cache) {            
            $dataitem=$this->getListKonsentrasiProgramStudi($kjur);            
            $nama_item=isset($dataitem[$idkonsentrasi]) ? $dataitem[$idkonsentrasi] :'N.A';
        }else {
            $dataitem=$this->getList("konsentrasi WHERE idkonsentrasi=$idkonsentrasi",array('nama_konsentrasi'),'nama_konsentrasi');
            $nama_item=isset($dataitem[1])?$dataitem[1]['nama_konsentrasi']:'N.A';
        }
        return $nama_item;
    }
    /**
     * digunakan untuk mendapatkan status mahasiswa
     */
    public function getListStatusMHS () {
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('liststatusmhs');            
            if (!isset($dataitem['none'])) {                
                $dataitem=$this->getList ('status_mhs',array('k_status','n_status'),'n_status',null,1);			
                $dataitem['none']='Daftar Status Mahasiswa';    
                $this->Application->Cache->set('liststatusmhs',$dataitem);
            }
        }else {                        
            $dataitem=$this->getList ('status_mhs',array('k_status','n_status'),'n_status',null,1);			
            $dataitem['none']='Daftar Status Mahasiswa';
        }
        return $dataitem;     		
    }
    /**
	* untuk mendapatkan nama status mahasiswa
	* @return nama status
	*/	
	public function getNamaStatusMHSByID ($k_status) {	
        if ($this->Application->Cache) {            
            $dataitem=$this->getListStatusMHS();
            $nama_item=$dataitem[$k_status];
        }else {
            $str = "SELECT n_status FROM status_mhs WHERE k_status=$k_status";
            $this->db->setFieldTable(array('n_status'));			        
            $r = $this->db->getRecord($str);                            
            $nama_item=isset($r[1])?$r[1]['n_status']:'';                    
        }
        return $nama_item;
    }   
    /**
     * digunakan untuk mendapatkan daftar dosen
     */
    public function getDaftarDosen() {
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listdosen');                        
            if (!isset($dataitem['none'])) {
                $str = "SELECT iddosen,CONCAT(gelar_depan,' ',nama_dosen,gelar_belakang) AS nama_dosen,nidn FROM dosen ORDER BY nama_dosen ASC";
                $this->db->setFieldTable (array('iddosen','nama_dosen','nidn'));			
                $r= $this->db->getRecord($str);
                $dataitem['none']='Daftar Dosen';   
                while (list($k,$v)=each($r)) {
                    $dataitem[$v['iddosen']]=$v['nama_dosen']. ' ['.$v['nidn'].']';
                }
                $this->Application->Cache->set('listdosen',$dataitem);
            }else{               
                $str = "SELECT iddosen,CONCAT(gelar_depan,' ',nama_dosen,gelar_belakang) AS nama_dosen,nidn FROM dosen ORDER BY nama_dosen ASC";
                $this->db->setFieldTable (array('iddosen','nama_dosen','nidn'));			
                $r= $this->db->getRecord($str);
                $dataitem['none']='Daftar Dosen';   
                while (list($k,$v)=each($r)) {
                    $dataitem[$v['iddosen']]=$v['nama_dosen']. ' ['.$v['nidn'].']';
                }
            }
        }
        return $dataitem; 
    }
    /**
	* untuk mendapatkan nama dosen pembimbing
	* @return nama dosen
	*/	
	public function getNamaDosenPembimbing ($iddosen) {	
        if ($this->Application->Cache) {            
            $dataitem=$this->getDaftarDosen();
            $nama_dosen=$dataitem[$iddosen];
            $nama_dosen=explode('[',$nama_dosen);
            $nama_item=$nama_dosen[0];
        }else {
            $str = "SELECT nidn,CONCAT(d.gelar_depan,' ',d.nama_dosen,' ',d.gelar_belakang) AS nama_dosen FROM dosen d WHERE iddosen=$iddosen";
            $this->db->setFieldTable(array('nidn','nama_dosen'));			        
            $r = $this->db->getRecord($str);                            
            $nama_item=isset($r[1])?$r[1]['nama_dosen']:'';                    
        }
        return $nama_item;
    }
    /**
     * digunakan untuk mendapatkan data dosen
     * @param type $iddosen
     */
    public function getDataDosen ($iddosen) {
        $datadosen=array();
        $str = "SELECT iddosen,nipy,nidn,nama_dosen,CONCAT(d.gelar_depan,' ',d.nama_dosen,' ',d.gelar_belakang) AS nama_dosen,nama_jabatan,alamat_dosen,telp_hp,email,website FROM dosen d LEFT JOIN jabatan_akademik ja ON (d.idjabatan=ja.idjabatan) WHERE iddosen='$iddosen'";
        $this->db->setFieldTable(array('iddosen','nipy','nidn','nama_dosen','nama_jabatan','alamat_dosen','telp_hp','email','website')); 
		$r=$this->db->getRecord($str);		
        if (isset($r[1])) {
            $datadosen=$r[1];
        }
        $this->DataDosen=$datadosen;
        return $datadosen;
    }
    /**
     * digunakan untuk mendapatkan kelompok pertanyaan
     */
    public function getListKelompokPertanyaan () {
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listkelompokpertanyaan');            
            if (!isset($dataitem['none'])) {                
                $dataitem=$this->getList ("kelompok_pertanyaan WHERE idkategori=1",array('idkelompok_pertanyaan','nama_kelompok'),'(orders+0)',null,1);            
                $dataitem['none']='Daftar Kelompok Pertanyaan';
                $this->Application->Cache->set('listkelompokpertanyaan',$dataitem);
            }
        }else {                        
            $dataitem=$this->getList ("kelompok_pertanyaan WHERE idkategori=1",array('idkelompok_pertanyaan','nama_kelompok'),'nama_kelompok',null,1);            
            $dataitem['none']='Daftar Kelompok Pertanyaan';
        }
        return $dataitem;     		
    }
    /**
     * digunakan untuk mendapatkan passing grade
     * @param type $tahun masuk
     */
    public function getDataPassingGrade ($tahun) {        
        $str = "SELECT kjur,nilai FROM passinggrade WHERE tahun_masuk=$tahun";
        $this->db->setFieldTable(array('kjur','nilai')); 
        $r=$this->db->getRecord($str);
        $dataitem=array();
        if (isset($r[1])) {            
            while (list($k,$v)=each($r)) {
                $dataitem[$v['kjur']]=$v['nilai'];
            }                    
        }    
        return $dataitem;
            
    }
    /**
	* Untuk mendapatkan daftar jenjang
	* @return array daftar jurusan
	*/
	public function getListJenjang () {
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listjenjangstudi');            
            if (!isset($dataitem['none'])) {                
                $dataitem=$this->getList ("jenjang_studi",array('kjenjang','njenjang'),'kjenjang',null,1);            
                $dataitem['none']='Daftar Jenjang Studi';
                $this->Application->Cache->set('listjenjangstudi',$dataitem);
            }
        }else {                        
            $dataitem=$this->getList ("jenjang_studi",array('kjenjang','njenjang'),'kjenjang',null,1);            
            $dataitem['none']='Daftar Jenjang Studi';
        }
        return $dataitem;         
	}
    /**
	* digunakan untuk mendapatkan daftar jabatan akademik
	*
	*/
	public function getListJabfung () {        
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listjabfung');            
            if (!isset($dataitem['none'])) {                  
                $dataitem=$this->getList('jabatan_akademik',array('idjabatan','nama_jabatan','idjabatan'),'idjabatan',null,1);
                $dataitem['none']='Daftar Jabatan Fungsional';
                $this->Application->Cache->set('listjabfung',$dataitem);
            }
        }else {                        
            $this->getList('jabatan_akademik',array('idjabatan','nama_jabatan','idjabatan'),'idjabatan',null,1);
            $dataitem['none']='Daftar Jabatan Fungsional';
        }
        return $dataitem;  
    }
    /**
     * digunakan untuk mendapatkan nama jabatan akademik berdasarkan id
     * @param type $idjabatan
     * @return type
     */
    public function getNamaJabfungByID ($idjabatan) {	
        if ($this->Application->Cache) {            
            $dataitem=$this->getListJabfung();
            $nama_item=$dataitem[$idjabatan];
        }else {
            $dataitem=$this->getList("jabatan_akademik WHERE idjabatan=$idjabatan",array('nama_jabatan'),'nama_jabatan');
            $nama_item=$dataitem[1]['nama_jabatan'];                               
        }
        return $nama_item;
    }
}