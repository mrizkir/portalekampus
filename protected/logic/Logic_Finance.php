<?php
prado::using ('Application.logic.Logic_Mahasiswa');
class Logic_Finance extends Logic_Mahasiswa {			
	public function __construct ($db) {
		parent::__construct ($db);				
	}
	/**
     * digunakan untuk mendapatkan daftar komponen biaya
     */
	public function getListKombi () {
        if ($this->Application->Cache) {            
            $dataitem=$this->Application->Cache->get('listkombi');            
            if (!isset($dataitem['none'])) {                
                $dataitem=$this->getList ('kombi',array('idkombi','nama_kombi'),'idkombi',null,1);			
                $dataitem['none']='Daftar Komponen Biaya';    
                $this->Application->Cache->set('listkombi',$dataitem);
            }
        }else {                        
            $dataitem=$this->getList ('kombi',array('idkombi','nama_kombi'),'idkombi',null,1);			
            $dataitem['none']='Daftar Komponen Biaya';  
        }
        return $dataitem;     		
	}
    /**
	* digunakan untuk mendapatkan biaya pendaftaran berdasarkan tahun, idsmt, dan kelas	
	*/
	public function getBiayaPendaftaran ($tahun,$idsmt,$idkelas) {
        $str = "SELECT biaya FROM kombi_per_ta WHERE idkombi=1 AND tahun=$tahun AND idsmt=$idsmt AND idkelas='$idkelas'";						
		$this->db->setFieldTable(array('biaya'));
		$result=$this->db->getRecord($str);
		if (isset($result[1])) {
			return $result[1]['biaya'];	
		}else {
			return 0;
		}        			
	}
    /**
	* digunakan untuk mendapatkan biaya cuti berdasarkan tahun, idsmt, dan kelas	
	*/
	public function getBiayaCuti ($tahun,$idsmt,$idkelas) {
        $str = "SELECT biaya FROM kombi_per_ta WHERE idkombi=12 AND tahun=$tahun AND idsmt=$idsmt AND idkelas='$idkelas'";						
		$this->db->setFieldTable(array('biaya'));
		$result=$this->db->getRecord($str);
		if (isset($result[1])) {
			return $result[1]['biaya'];	
		}else {
			return 0;
		}        			
	}
	/**
	* digunakan untuk mendapatkan biaya sks berdasarkan tahun, idsmt, dan kelas	
	*/
	public function getBiayaSKS ($tahun,$idkelas) {
        $str = "SELECT biaya FROM kombi_per_ta WHERE idkombi=14 AND tahun=$tahun AND idkelas='$idkelas'";						
		$this->db->setFieldTable(array('biaya'));
		$result=$this->db->getRecord($str);
		if (isset($result[1])) {
			return $result[1]['biaya'];	
		}else {
			return 0;
		}        			
	}
    /**
     * digunakan untuk mendapatkan total biaya mahasiswa [deprecated]
     * @param $status baru atau lama
     * @return jumlah biaya mahasiswa
     */
    public function getTotalBiayaMhs ($status='baru') {        
		$tahun_masuk=$this->DataMHS['tahun_masuk'];
        $semester_masuk=$this->DataMHS['semester_masuk'];
		$kelas=$this->DataMHS['idkelas'];
        switch ($status) {
            case 'lama' :
                $str = "SELECT SUM(biaya) AS jumlah FROM kombi_per_ta WHERE tahun=$tahun_masuk AND idsmt=$semester_masuk AND idkelas='$kelas' AND idkombi != 1 AND idkombi != 12 AND idkombi != 13 AND idkombi != 14 AND (idkombi=2 OR idkombi=3 OR idkombi=7 OR idkombi=9)";
            break;
            case 'baru' :
                if ($this->getDataMhs('perpanjang')==true) {
                    $str = "SELECT SUM(biaya) AS jumlah FROM kombi_per_ta WHERE tahun=$tahun_masuk AND idsmt=$semester_masuk AND idkelas='$kelas' AND idkombi != 1 AND idkombi != 12 AND idkombi != 13 AND idkombi != 14 AND (idkombi=2 OR idkombi=3 OR idkombi=7 OR idkombi=9)";
                }else {
                    $str = "SELECT SUM(biaya) AS jumlah FROM kombi_per_ta WHERE tahun=$tahun_masuk AND idsmt=$semester_masuk AND idkelas='$kelas' AND idkombi != 1 AND idkombi != 12 AND idkombi != 13 AND idkombi != 14";								
                }		
            break;
            case 'sp' :
                $str = "SELECT biaya AS jumlah FROM kombi_per_ta WHERE tahun=$tahun_masuk AND idsmt=$semester_masuk AND idkelas='$kelas' AND idkombi=14";
            break;
        }		
		$this->db->setFieldTable(array('jumlah'));
		$r=$this->db->getRecord($str);	
        
		return $r[1]['jumlah'];
	}    
    /**
     * digunakan untuk mendapatkan total biaya mahasiswa
     * @param $status baru atau lama
     * @return jumlah biaya mahasiswa
     */
    public function getTotalBiayaMhsPeriodePembayaran ($status='baru') {        
		$tahun_masuk=$this->DataMHS['tahun_masuk'];
        $semester_masuk=$this->DataMHS['idsmt'];
		$kelas=$this->DataMHS['idkelas'];
        switch ($status) {
            case 'lama' :
                $str = "SELECT SUM(biaya) AS jumlah FROM kombi_per_ta kpt,kombi k WHERE k.idkombi=kpt.idkombi AND tahun=$tahun_masuk AND idsmt=$semester_masuk AND idkelas='$kelas' AND periode_pembayaran='semesteran'";
            break;
            case 'baru' :
                if ($this->getDataMhs('perpanjang')==true) {
                    $str = "SELECT SUM(biaya) AS jumlah FROM kombi_per_ta kpt,kombi k WHERE k.idkombi=kpt.idkombi AND  tahun=$tahun_masuk AND idsmt=$semester_masuk AND idkelas='$kelas' AND periode_pembayaran!='none'";
                }else {
                    $str = "SELECT SUM(biaya) AS jumlah FROM kombi_per_ta kpt,kombi k WHERE k.idkombi=kpt.idkombi AND  tahun=$tahun_masuk AND idsmt=$semester_masuk AND idkelas='$kelas' AND periode_pembayaran!='none'";								
                }		
            break;
            case 'sp' :
                $str = "SELECT biaya AS jumlah FROM kombi_per_ta WHERE tahun=$tahun_masuk AND idsmt=$semester_masuk AND idkelas='$kelas' AND idkombi=14";
            break;
        }		
		$this->db->setFieldTable(array('jumlah'));
		$r=$this->db->getRecord($str);	
        
		return $r[1]['jumlah'];
	}
	/**
	* digunakan untuk memperoleh jumlah tagihan berdasarkan no_transaksi
	* @return jumlah pembayaran per no_transaksi
	*/
	public function getTotalTagihanByNoTransaksi ($no_transaksi) {
		return $this->db->getSumRowsOfTable('dibayarkan',"transaksi_detail WHERE no_transaksi='$no_transaksi'");
	}
    /**
     * digunakan untuk mendapatkan total pembayaran yang telah dilakukan oleh mahasiswa     
     * @return jumlah pembayaran mahasiswa
     */
    public function getTotalBayarMhs ($tahun_sekarang,$semester_sekarang) {									
		$kjur=$this->DataMHS['kjur'];        
        if ($semester_sekarang == 3) { //semester pendek
            $nim=$this->DataMHS['nim'];	
            $str = "SELECT SUM(dibayarkan) AS jumlah FROM transaksi_sp WHERE tahun=$tahun_sekarang AND idsmt=$semester_sekarang AND kjur=$kjur AND nim='$nim' AND commited=1";											            
        }else {
            $no_formulir=$this->DataMHS['no_formulir'];	
            $str = "SELECT SUM(dibayarkan) AS jumlah FROM v_transaksi WHERE tahun=$tahun_sekarang AND idsmt=$semester_sekarang AND kjur=$kjur AND no_formulir='$no_formulir' AND commited=1";											
        }

		$this->db->setFieldTable(array('jumlah'));
		$r=$this->db->getRecord($str);
		
		return $r[1]['jumlah'];
	}	
    /**
	* untuk mendapatkan lunas pembayaran Formulir
	* @return boolean atau array
	*/
	public function getLunasPembayaranFormulir ($data=false) {
        $no_formulir=$this->DataMHS['no_formulir'];
        $tahun_masuk=$this->DataMHS['tahun_masuk'];
        $semester_masuk=$this->DataMHS['semester_masuk'];
        $idkelas=$this->DataMHS['idkelas'];
        $biaya_pendaftaran=$this->getBiayaPendaftaran($tahun_masuk,$semester_masuk,$idkelas);
        
        $str = "SELECT td.dibayarkan FROM transaksi t,transaksi_detail td WHERE td.no_transaksi=t.no_transaksi AND t.no_formulir='$no_formulir' AND td.idkombi=1";
        $this->db->setFieldTable(array('dibayarkan'));
        $r=$this->db->getRecord($str);
        $bool=isset($r[1])?$biaya_pendaftaran<=$r[1]['dibayarkan']:0;
		if ($data) {
			$data=array();
			$data['total_biaya']=$$biaya_pendaftaran;
			$data['total_bayar']=$r[1]['dibayarkan'];			
			$data['bool']=$bool;
			return $data;
		}else {                        
			return $bool;
        }

	}
    /**
	* untuk mendapatkan lunas pembayaran	
	* @return boolean atau array
	*/
	public function getLunasPembayaran ($tahun_sekarang,$semester_sekarang,$data=false) {        
        if ($this->isMhsBaru($tahun_sekarang,$semester_sekarang)) {            
            $total_biaya=$this->getTotalBiayaMhsPeriodePembayaran();
            $total_bayar_mhs=$this->getTotalBayarMhs ($tahun_sekarang,$semester_sekarang);				
        }elseif($semester_sekarang==3){
            $nim=$this->DataMHS['nim'];	            
            $kjur=$this->DataMHS['kjur']; 
            $total_bayar_mhs=$this->getTotalBayarMhs ($tahun_sekarang,$semester_sekarang);
            $str = "SELECT sks FROM transaksi_sp WHERE nim='$nim' AND tahun=$tahun_sekarang AND idsmt=$semester_sekarang AND kjur='$kjur' AND nim='$nim' AND commited=1";
            $this->db->setFieldTable(array('sks'));
            $r=$this->db->getRecord($str);                         
            $total_biaya=$this->getTotalBiayaMhs('sp') * $r[1]['sks'];		
        }else{            
            $total_biaya=$this->getTotalBiayaMhsPeriodePembayaran('lama');		
            $total_bayar_mhs=$this->getTotalBayarMhs ($tahun_sekarang,$semester_sekarang);				            
        }
        $bool=$total_biaya<=$total_bayar_mhs;
		if ($data) {
			$data=array();
			$data['total_biaya']=$total_biaya;
			$data['total_bayar']=$total_bayar_mhs;			
			$data['bool']=$bool;
			return $data;
		}else {                        
			return $bool;
        }
	}
    /**
	* untuk mendapatkan ambang batas pembayaran	
	* @return boolean atau array
	*/
	public function getTresholdPembayaran ($tahun_sekarang,$semester_sekarang,$data=false) {
		$total_biaya=($this->isMhsBaru($tahun_sekarang,$semester_sekarang))?$this->getTotalBiayaMhsPeriodePembayaran():$this->getTotalBiayaMhsPeriodePembayaran('lama');
		$total_biaya_setengah=$total_biaya/2;		
		$total_bayar_mhs=$this->getTotalBayarMhs ($tahun_sekarang,$semester_sekarang);					
		$bool=$total_biaya_setengah<=$total_bayar_mhs;	
		if ($data) {
			$data=array();
			$data['total_biaya']=$total_biaya;
			$data['total_bayar']=$total_bayar_mhs;
			$data['ambang_pembayaran']=$total_biaya_setengah;
			$data['bool']=$bool;
			return $data;
		}else {
			return $bool;
        }
	}
    /**
     * digunakan untuk mendapaktan kelas pada transaksi
     * @param type $tahun_sekarang
     * @param type $semester_sekarang
     * @return boolean
     */
	public function getKelasFromTransaksi($tahun_sekarang,$semester_sekarang) {
		$no_formulir=$this->DataMHS['no_formulir'];
		$str = "SELECT idkelas FROM transaksi WHERE tahun=$tahun_sekarang AND idsmt=$semester_sekarang AND no_formulir=$no_formulir ORDER BY date_modified DESC LIMIT 0,1";
		$this->db->setFieldTable(array('idkelas'));
		$r=$this->db->getRecord($str);		
		if (isset($r[1]) )
			return $r[1]['idkelas'];
		else
			return false;
	}
    /**
	* untuk mendapatkan jumlah sks SP	
	* @return boolean atau array
	*/
	public function getSKSFromSP ($tahun_sekarang,$semester_sekarang) {		
		$kjur=$this->DataMHS['kjur'];
		$nim=$this->DataMHS['nim'];
		$str = "SELECT jumlah_sks FROM transaksi WHERE nim='$nim' AND idsmt=$semester_sekarang AND tahun=$tahun_sekarang AND kjur=$kjur AND commited=1";		
		$this->db->setFieldTable(array('jumlah_sks'));
		$r=$this->db->getRecord($str);	
        
		if (isset($r[1])) {
			return $r[1]['jumlah_sks'];
        }else{
			return false;
        }
	}
    /**
	* casting ke integer	
	*/
	public function toInteger ($stringNumeric) {
		return str_replace('.','',$stringNumeric);
	}
    /**
	* Untuk mendapatkan uang dalam format rupiah
	* @param angka	
	* @return string dalam rupiah
	*/
	public function toRupiah($angka,$tanpa_rp=true)  {
		if ($angka == '') {
			$angka=0;
		}
		$rupiah='';
		$rp=strlen($angka);
		while ($rp>3){
			$rupiah = ".". substr($angka,-3). $rupiah;
			$s=strlen($angka) - 3;
			$angka=substr($angka,0,$s);
			$rp=strlen($angka);
		}
		if ($tanpa_rp) {
			$rupiah = $angka . $rupiah;
		}else {
			$rupiah = "Rp. " . $angka . $rupiah;
		}
		return $rupiah;
	}
}