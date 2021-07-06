<?php
prado::using ('Application.MainPageM');
class CKRSMerdeka Extends MainPageM {	
	/**
	* total  sks
	*/
	public $totalSks=0;
	/**
	* total  matakuliah
	*/
	public $jumlahMatkul=0;
	/**
	* data krs
	*/
	public $dataKrs;
	
	public function onLoad($param) {		
		parent::onLoad($param);										
		$this->showSubMenuAkademikPerkuliahan=true;
        $this->showKRS = true;   
        $this->createObj('KRS');
        $this->createObj('Nilai');
        $this->createObj('Finance');
        
		if (!$this->IsPostBack&&!$this->IsCallBack) {						
            if (!isset($_SESSION['currentPageKRSMerdeka'])||$_SESSION['currentPageKRSMerdeka']['page_name']!='m.perkuliahan.KRSMerdeka') {					
                $_SESSION['currentPageKRSMerdeka']=array('page_name'=>'m.perkuliahan.KRSMerdeka','page_num'=>0,'mode_krs'=>'sudah','iddosen_wali'=>'none','tahun_masuk'=>'none','DataKRS'=>array(),'DataMHS'=>array());												
            }
            $_SESSION['currentPageKRSMerdeka']['search']=false;
            $this->tbCmbPs->DataSource=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');
            $this->tbCmbPs->Text=$_SESSION['kjur'];			
            $this->tbCmbPs->dataBind();	

            $tahun_masuk=$this->getAngkatan ();			            
            $this->tbCmbTahunMasuk->DataSource=$tahun_masuk	;					
            $this->tbCmbTahunMasuk->Text=$_SESSION['currentPageKRSMerdeka']['tahun_masuk'];						
            $this->tbCmbTahunMasuk->dataBind();

            $this->tbCmbTA->DataSource=$this->DMaster->removeIdFromArray($this->DMaster->getListTA($this->Pengguna->getDataUser('tahun_masuk')),'none');
            $this->tbCmbTA->Text=$_SESSION['ta'];
            $this->tbCmbTA->dataBind();			

            $semester=$this->DMaster->removeIdFromArray($this->setup->getSemester(),'none');  				
            $this->tbCmbSemester->DataSource=$semester;
            $this->tbCmbSemester->Text=$_SESSION['semester'];
            $this->tbCmbSemester->dataBind();

            $this->tbCmbOutputReport->DataSource=$this->setup->getOutputFileType();
            $this->tbCmbOutputReport->Text= $_SESSION['outputreport'];
            $this->tbCmbOutputReport->DataBind();

            $this->tbCmbOutputCompress->DataSource=$this->setup->getOutputCompressType();
            $this->tbCmbOutputCompress->Text= $_SESSION['outputcompress'];
            $this->tbCmbOutputCompress->DataBind();

            $dosen_wali=$this->DMaster->getListDosenWali();				            
            $this->cmbDosenWali->DataSource=$dosen_wali;
            $this->cmbDosenWali->Text=$_SESSION['currentPageKRSMerdeka']['iddosen_wali'];
            $this->cmbDosenWali->dataBind();

            $this->cmbModeKRS->Text=$_SESSION['currentPageKRSMerdeka']['mode_krs'];

            $this->setInfoToolbar();
            $this->populateData();			

		}
	}	
    public function setInfoToolbar() {        
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
        $ta=$this->DMaster->getNamaTA($_SESSION['ta']);		
        $semester = $this->setup->getSemester($_SESSION['semester']);
		$tahunmasuk=$_SESSION['currentPageKRSMerdeka']['tahun_masuk'] == 'none'?'':'Tahun Masuk '.$this->DMaster->getNamaTA($_SESSION['currentPageKRSMerdeka']['tahun_masuk']);		        
		$this->lblModulHeader->Text="Program Studi $ps T.A $ta Semester $semester $tahunmasuk";        
	}
	public function changeTbTA ($sender,$param) {				
		$_SESSION['ta']=$this->tbCmbTA->Text;		        
		$_SESSION['currentPageKRSMerdeka']['tahun_masuk']=$_SESSION['ta'];
		$this->tbCmbTahunMasuk->DataSource=$this->getAngkatan();
		$this->tbCmbTahunMasuk->Text=$_SESSION['currentPageKRSMerdeka']['tahun_masuk'];
		$this->tbCmbTahunMasuk->dataBind();		
        $this->setInfoToolbar();
		$this->populateData();
	}
	public function changeTbTahunMasuk($sender,$param) {				
		$_SESSION['currentPageKRSMerdeka']['tahun_masuk']=$this->tbCmbTahunMasuk->Text;
        $this->setInfoToolbar();
		$this->populateData();
	}
	public function changeTbPs ($sender,$param) {		
		$_SESSION['kjur']=$this->tbCmbPs->Text;
        $this->setInfoToolbar();
		$this->populateData();
	}	
	public function changeTbSemester ($sender,$param) {		
		$_SESSION['semester']=$this->tbCmbSemester->Text;        
        $this->setInfoToolbar();
		$this->populateData();
	}
	public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}	
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageKRSMerdeka']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageKRSMerdeka']['search']);
	}
    public function changeDW($sender,$param){
		$_SESSION['currentPageKRSMerdeka']['iddosen_wali']=$this->cmbDosenWali->Text;
		$_SESSION['currentPageKRSMerdeka']['page_num']=0;		
		$this->populateData();
	}
	public function changeModeKRS ($sender,$param) {
		$_SESSION['currentPageKRSMerdeka']['mode_krs']=$this->cmbModeKRS->Text;
		$_SESSION['currentPageKRSMerdeka']['page_num']=0;
		$this->populateData();
	} 
    public function searchRecord ($sender,$param) {
		$_SESSION['currentPageKRSMerdeka']['search']=true;
		$this->populateData($_SESSION['currentPageKRSMerdeka']['search']);
	}
	public function populateData($search=false) {					
		$ta=$_SESSION['ta'];
		$semester=$_SESSION['semester'];
		$kjur=$_SESSION['kjur'];
		$tahun_masuk=$_SESSION['currentPageKRSMerdeka']['tahun_masuk'];
        $iddosen_wali=$_SESSION['currentPageKRSMerdeka']['iddosen_wali'];
        $str_dw = $iddosen_wali=='none'?'':" AND vdm.iddosen_wali=$iddosen_wali";
        $str_tahun_masuk=$tahun_masuk=='none'?'':" AND vdm.tahun_masuk=$tahun_masuk";   
        $mode_krs=$_SESSION['currentPageKRSMerdeka']['mode_krs'];      
        if ($search) {
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {                
                case 'nim' :
                    $clausa="AND vdm.nim='$txtsearch'";                                        
                break;
                case 'nirm' :
                    $clausa="AND vdm.nirm='$txtsearch'";                    
                break;
                case 'nama' :
                    $clausa="AND vdm.nama_mhs LIKE '%$txtsearch%'";                    
                break;
            }
            switch ($mode_krs)
            {
                case 'belum' :
                    $str="SELECT vdm.nim,vdm.nama_mhs,vdm.jk,vdm.tahun_masuk FROM dulang d,v_datamhs vdm WHERE d.k_status='A' AND d.tahun=$ta AND d.idsmt=$semester AND vdm.nim=d.nim AND vdm.is_merdeka=1 AND d.nim NOT IN (SELECT nim FROM krs WHERE idsmt=$semester AND tahun=$ta AND is_merdeka=1) AND vdm.idkelas!='C' $clausa";                                                
                    $jumlah_baris=$this->DB->getCountRowsOfTable("dulang d,v_datamhs vdm WHERE d.k_status='A' AND d.tahun=$ta AND d.idsmt=$semester AND vdm.nim=d.nim AND vdm.is_merdeka=1 AND d.nim NOT IN (SELECT nim FROM krs WHERE idsmt=$semester AND tahun=$ta AND is_merdeka=1) AND vdm.idkelas!='C' $clausa",'d.nim');		
                    $this->DB->setFieldTable(array('nim','nama_mhs','jk','tahun_masuk'));
                break;
                case 'sudah' :
                    $str = "SELECT k.idkrs,k.tgl_krs,k.nim,vdm.nama_mhs,vdm.jk,vdm.tahun_masuk,k.sah,k.tgl_disahkan FROM krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND vdm.is_merdeka=1 AND k.tahun=$ta AND k.idsmt=$semester AND k.is_merdeka=1 AND vdm.idkelas!='C' $clausa";            
                    $jumlah_baris=$this->DB->getCountRowsOfTable("krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND k.tahun=$ta AND k.idsmt=$semester AND k.is_merdeka=1 AND vdm.idkelas!='C' AND vdm.is_merdeka=1 $clausa",'k.nim');		
                    $this->DB->setFieldTable(array('idkrs','tgl_krs','nim','nama_mhs','jk','tahun_masuk','sah','tgl_disahkan'));
                break;
                case 'belumsah' :
                    $str = "SELECT k.idkrs,k.tgl_krs,k.nim,vdm.nama_mhs,vdm.jk,vdm.tahun_masuk,k.sah,k.tgl_disahkan FROM krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND vdm.is_merdeka=1 AND k.tahun=$ta AND k.idsmt=$semester AND k.is_merdeka=1 AND vdm.idkelas!='C' AND k.sah=0 $clausa";            
                    $jumlah_baris=$this->DB->getCountRowsOfTable("krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND k.tahun=$ta AND k.idsmt=$semester AND vdm.idkelas!='C' AND vdm.is_merdeka=1 AND k.sah=0 AND k.is_merdeka=1 $clausa",'k.nim');		
                    $this->DB->setFieldTable(array('idkrs','tgl_krs','nim','nama_mhs','jk','tahun_masuk','sah','tgl_disahkan'));
                break;
            }
        }else{
            switch ($mode_krs)
            {
                case 'belum' :
                    $str="SELECT vdm.nim,vdm.nama_mhs,vdm.jk,vdm.tahun_masuk FROM dulang d,v_datamhs vdm WHERE d.k_status='A' AND d.tahun=$ta AND d.idsmt=$semester AND vdm.nim=d.nim AND vdm.kjur=$kjur AND vdm.idkelas!='C' AND vdm.is_merdeka=1 AND d.nim NOT IN (SELECT nim FROM krs WHERE idsmt=$semester AND tahun=$ta AND is_merdeka=1)$str_dw $str_tahun_masuk";                                                
                    $jumlah_baris=$this->DB->getCountRowsOfTable("dulang d,v_datamhs vdm WHERE d.k_status='A' AND d.tahun=$ta AND d.idsmt=$semester AND vdm.nim=d.nim AND vdm.kjur=$kjur AND vdm.idkelas!='C' AND d.nim NOT IN (SELECT nim FROM krs WHERE idsmt=$semester AND tahun=$ta AND is_merdeka=1) $str_dw $str_tahun_masuk",'d.nim');		
                    $this->DB->setFieldTable(array('nim','nama_mhs','jk','tahun_masuk'));
                break;
                case 'sudah' :
                    $str = "SELECT k.idkrs,k.tgl_krs,k.nim,vdm.nama_mhs,vdm.jk,vdm.tahun_masuk,k.sah,k.tgl_disahkan FROM krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND vdm.is_merdeka=1 AND k.tahun=$ta AND k.idsmt=$semester AND k.is_merdeka=1 AND vdm.kjur=$kjur AND vdm.idkelas!='C' $str_dw $str_tahun_masuk";            
                    $jumlah_baris=$this->DB->getCountRowsOfTable("krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND k.tahun=$ta AND k.idsmt=$semester AND vdm.kjur=$kjur AND vdm.is_merdeka=1 AND k.is_merdeka=1 AND vdm.idkelas!='C' $str_dw $str_tahun_masuk",'k.nim');		
                    $this->DB->setFieldTable(array('idkrs','tgl_krs','nim','nama_mhs','jk','tahun_masuk','sah','tgl_disahkan'));
                break;
                case 'belumsah' :
                    $str = "SELECT k.idkrs,k.tgl_krs,k.nim,vdm.nama_mhs,vdm.jk,vdm.tahun_masuk,k.sah,k.tgl_disahkan FROM krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND vdm.is_merdeka=1 AND k.tahun=$ta AND k.idsmt=$semester AND k.is_merdeka=1 AND  vdm.kjur=$kjur AND vdm.idkelas!='C' AND k.sah=0 $str_dw $str_tahun_masuk";            
                    $jumlah_baris=$this->DB->getCountRowsOfTable("krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND k.tahun=$ta AND k.idsmt=$semester AND vdm.kjur=$kjur AND vdm.is_merdeka=1 AND vdm.idkelas!='C' AND k.sah=0 AND k.is_merdeka=1 $str_dw $str_tahun_masuk",'k.nim');		
                    $this->DB->setFieldTable(array('idkrs','tgl_krs','nim','nama_mhs','jk','tahun_masuk','sah','tgl_disahkan'));
                break;
            }
           
        }
        $this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageKRSMerdeka']['page_num'];
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$currentPage=$this->RepeaterS->CurrentPageIndex;
		$offset=$currentPage*$this->RepeaterS->PageSize;		
		$itemcount=$this->RepeaterS->VirtualItemCount;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$itemcount) {
			$limit=$itemcount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=$this->setup->getSettingValue('default_pagesize');$_SESSION['currentPageKRSMerdeka']['page_num']=0;}
        $str = "$str ORDER BY vdm.nama_mhs ASC LIMIT $offset,$limit";	
        $r = $this->DB->getRecord($str,$offset+1);	        

        $this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();     
        $this->paginationInfo->Text=$this->getInfoPaging($this->RepeaterS);
	}
	public function setDataBound ($sender,$param) {
		$item=$param->Item;
		if ($item->ItemType==='Item' || $item->ItemType==='AlternatingItem') {			
            $idkrs=$item->DataItem['idkrs'];
            $str = "SELECT COUNT(idkrsmatkul) AS jumlah_matkul,SUM(sks) AS jumlah_sks FROM v_krsmhs WHERE idkrs='$idkrs' AND batal=0";						
            $this->DB->setFieldTable (array('jumlah_matkul','jumlah_sks'));
			$r=$this->DB->getRecord($str);
            $item->literalMatkul->Text=$r[1]['jumlah_matkul'] > 0 ?$r[1]['jumlah_matkul']:0;
            $item->literalSKS->Text=$r[1]['jumlah_sks'] > 0 ?$r[1]['jumlah_sks']:0;
            $trstyle='';
            if ($item->DataItem['sah']) {                 
                $status='<span class="label label-success">sah</span>';	
            }elseif ($_SESSION['currentPageKRSMerdeka']['mode_krs'] == 'belum'){
                $status='-';	
            }else{
                $trstyle=' class="danger"';
                $status='<span class="label label-info">Belum disahkan</span>';	
            }
            $item->literalTRStyle->Text=$trstyle;
            $item->literalStatus->Text=$status;                        
		}
	}
	public function checkNIM ($sender,$param) {
		$nim=addslashes($param->Value);
        try {
            if ($nim != '') {			            
                $datamhs=array('nim'=>$nim);
                $this->KRS->setDataMHS($datamhs);
                $this->KRS->getKRS($_SESSION['ta'],$_SESSION['semester']);                
                if (isset($this->KRS->DataKRS['krs']['idkrs'])) {           
                    $str = "SELECT vdm.no_formulir,vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,vdm.tempat_lahir,vdm.tanggal_lahir,vdm.kjur,vdm.nama_ps,vdm.idkonsentrasi,k.nama_konsentrasi,vdm.tahun_masuk,vdm.semester_masuk,iddosen_wali,vdm.k_status,sm.n_status AS status,vdm.idkelas,ke.nkelas FROM v_datamhs vdm LEFT JOIN konsentrasi k ON (vdm.idkonsentrasi=k.idkonsentrasi) LEFT JOIN status_mhs sm ON (vdm.k_status=sm.k_status) LEFT JOIN kelas ke ON (vdm.idkelas=ke.idkelas) WHERE vdm.nim='$nim'";
                    $this->DB->setFieldTable(array('no_formulir','nim','nirm','nama_mhs','jk','tempat_lahir','tanggal_lahir','kjur','nama_ps','idkonsentrasi','nama_konsentrasi','tahun_masuk','semester_masuk','iddosen_wali','k_status','status','idkelas','nkelas'));
                    $r=$this->DB->getRecord($str);	           
                    if (!isset($r[1])) {
                        throw new Exception ("Mahasiswa Dengan NIM ($nim) tidak terdaftar di Portal.");
                    }
                    $datamhs=$r[1];
                    $datamhs['nama_konsentrasi']=($datamhs['idkonsentrasi']==0) ? '-':$datamhs['nama_konsentrasi'];

                    $nama_dosen=$this->DMaster->getNamaDosenWaliByID($datamhs['iddosen_wali']);				                    
                    $datamhs['nama_dosen']=$nama_dosen;
                    $datamhs['iddata_konversi']=$this->Nilai->isMhsPindahan($nim,true);
                    $_SESSION['currentPageKRSMerdeka']['DataMHS']=$datamhs;
                    
                    $_SESSION['currentPageKRSMerdeka']['DataKRS']=$this->KRS->DataKRS;
                }else{
                    $str = "SELECT vdm.no_formulir,vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,vdm.tempat_lahir,vdm.tanggal_lahir,vdm.kjur,vdm.nama_ps,vdm.idkonsentrasi,k.nama_konsentrasi,vdm.tahun_masuk,vdm.semester_masuk,iddosen_wali,vdm.k_status,sm.n_status AS status,vdm.idkelas,ke.nkelas  FROM v_datamhs vdm LEFT JOIN konsentrasi k ON (vdm.idkonsentrasi=k.idkonsentrasi) LEFT JOIN status_mhs sm ON (vdm.k_status=sm.k_status) LEFT JOIN kelas ke ON (vdm.idkelas=ke.idkelas) WHERE vdm.nim='$nim'";
                    $this->DB->setFieldTable(array('no_formulir','nim','nirm','nama_mhs','jk','tempat_lahir','tanggal_lahir','kjur','nama_ps','idkonsentrasi','nama_konsentrasi','tahun_masuk','semester_masuk','iddosen_wali','k_status','status','idkelas','nkelas'));
                    $r=$this->DB->getRecord($str);	           
                    if (!isset($r[1])) {
                        throw new Exception ("Mahasiswa Dengan NIM ($nim) tidak terdaftar di Portal.");
                    }
                    $datamhs=$r[1];
                    
                    $datamhs['nama_konsentrasi']=($datamhs['idkonsentrasi']==0) ? '-':$datamhs['nama_konsentrasi'];

                    $nama_dosen=$this->DMaster->getNamaDosenWaliByID($datamhs['iddosen_wali']);				                    
                    $datamhs['nama_dosen']=$nama_dosen;
                    $datamhs['iddata_konversi']=$this->Nilai->isMhsPindahan($nim,true);
                    $_SESSION['currentPageKRSMerdeka']['DataMHS']=$datamhs;
                    
                    $idsmt=$_SESSION['semester'];
                    $tahun=$_SESSION['ta'];
                    $datamhs['idsmt']=$idsmt;
                    $this->Finance->setDataMHS($datamhs);
                    if ($tahun >= 2010) {                    
                        if ($idsmt==3) {                        
                            if (!$this->Finance->getSKSFromSP ($tahun,$idsmt))throw new Exception ("Anda tidak bisa mengisi KRS karena belum melakukan pembayaran untuk Semester Pendek");																			
                        }else {
                            $data=$this->Finance->getTresholdPembayaran($tahun,$idsmt,true);				                        
                            if (!$data['bool'])throw new Exception ("Anda tidak bisa mengisi KRS karena baru membayar(".$this->Finance->toRupiah($data['total_bayar'])."), harus minimal setengahnya sebesar (".$this->Finance->toRupiah($data['ambang_pembayaran']).") dari total (".$this->Finance->toRupiah($data['total_biaya']).")");
                        }
                    } 
                    $datadulang=$this->KRS->getDataDulang($idsmt,$tahun);
                    $nama_tahun = $this->DMaster->getNamaTA($tahun);
                    $nama_semester = $this->setup->getSemester($idsmt);
                    if (!isset($datadulang['iddulang']))throw new Exception ("Anda belum melakukan daftar ulang pada T.A $nama_tahun Semester $nama_semester. Silahkan hubungi Prodi (Bukan Keuangan).");
                    $status=$datamhs['k_status'];
                    if ($status== 'K'||$status== 'L'||$status== 'D') throw new Exception ("Status Anda tidak aktif, sehingga tidak bisa mengisi KRS.");						
                    if ($datadulang['k_status'] != 'A')throw new Exception ("Anda pada tahun akademik dan semester sekarang tidak aktif.");									                                        
                    
                    $_SESSION['currentPageKRSMerdeka']['DataKRS']=array();                    
                }                
            }
        }catch(Exception $e) {			
            $sender->ErrorMessage=$e->getMessage();				
            $param->IsValid=false;			
		}
	}
	public function isiKRS ($sender,$param) {
        if ($this->IsValid){                        
            $krs=$_SESSION['currentPageKRSMerdeka']['DataKRS']['krs'];  
            $datamhs= $_SESSION['currentPageKRSMerdeka']['DataMHS'];
            $nim=$datamhs['nim'];
            $this->Nilai->setDataMHS($datamhs);
            $this->Finance->setDataMHS($datamhs);
            if (isset($krs['idkrs']) && $krs['sah']==0) {       
                $idsmt=$krs['idsmt'];
                $tahun=$krs['tahun'];                
                
                $krs['maxSKS']=$idsmt==3?$this->Finance->getSKSFromSP($tahun,$idsmt):$this->Nilai->getMaxSKS($tahun,$idsmt);                
                $krs['ipstasmtbefore']=$this->Nilai->getIPS();                                                   
                                
                $_SESSION['currentPageKRSMerdeka']['DataKRS']['krs']=$krs;
                
                $this->redirect ('perkuliahan.TambahKRSMerdeka',true);
            }elseif(isset($krs['idkrs']) && $krs['sah']==1){
                $idkrs=$krs['idkrs'];
                $this->redirect ('perkuliahan.DetailKRSMerdeka',true,array('id'=>$idkrs));
            }else{
                $idsmt=$_SESSION['semester'];
                $tahun=$_SESSION['ta'];
                
                $tanggal=date('Y-m-d');
                $no_krs=mt_rand();                    
                $tasmt=$tahun.$idsmt;
                
                $str = "INSERT INTO krs SET idkrs=NULL,tgl_krs='$tanggal',no_krs=$no_krs,nim='$nim',idsmt='$idsmt',tahun='$tahun',tasmt='$tasmt',sah=0,tgl_disahkan='0000-00-00'";
                $this->DB->insertRecord($str);					
                $this->KRS->DataKRS['krs'] = array('idkrs'=>$this->DB->getLastInsertID(),
                                                    'tgl_krs'=>$tanggal,
                                                    'no_krs'=>$no_krs,
                                                    'nim'=>$nim,
                                                    'idsmt'=>$idsmt,
                                                    'tahun'=>$tahun,
                                                    'tasmt'=>$tasmt);		   
                
                $this->KRS->DataKRS['krs']['maxSKS']=$idsmt==3?$this->Finance->getSKSFromSP($tahun,$idsmt):$this->Nilai->getMaxSKS($tahun,$idsmt);                
                $this->KRS->DataKRS['krs']['ipstasmtbefore']=$this->Nilai->getIPS();                                                   
                
                $_SESSION['currentPageKRSMerdeka']['DataKRS']=$this->KRS->DataKRS;
                
                $this->redirect ('perkuliahan.TambahKRSMerdeka',true);
            }
        }
    }
    public function printOut ($sender,$param) {
        $this->createObj('reportkrs');
        $this->linkOutput->Text='';
        $this->linkOutput->NavigateUrl='#';
        
        switch ($sender->getId()) {
			case 'btnPrintOutR' :
                $nim = $this->getDataKeyField($sender,$this->RepeaterS);
                $str = "SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.kjur,vdm.nama_ps,vdm.idkonsentrasi,k.nama_konsentrasi,iddosen_wali FROM v_datamhs vdm LEFT JOIN konsentrasi k ON (vdm.idkonsentrasi=k.idkonsentrasi) WHERE vdm.nim='$nim'";
                $this->DB->setFieldTable(array('nim','nirm','nama_mhs','kjur','nama_ps','idkonsentrasi','nama_konsentrasi','iddosen_wali'));
                $r=$this->DB->getRecord($str);	           
                $datamhs=$r[1];
                $nama_dosen=$this->DMaster->getNamaDosenWaliByID($datamhs['iddosen_wali']);				                    
                $datamhs['nama_dosen']=$nama_dosen;
                $this->KRS->setDataMHS($datamhs);
                $this->KRS->getKRS($_SESSION['ta'],$_SESSION['semester']);

                switch ($_SESSION['outputreport']) {
                    case  'summarypdf' :
                        $messageprintout="Mohon maaf Print out pada mode summary pdf tidak kami support.";                
                    break;
                    case  'summaryexcel' :
                        $messageprintout="Mohon maaf Print out pada mode summary excel tidak kami support.";                
                    break;
                    case  'excel2007' :
                        $messageprintout="Mohon maaf Print out pada mode excel 2007 belum kami support.";                
                    break;
                    case  'pdf' :                
                        $messageprintout='';                
                        $tahun=$_SESSION['ta'];
                        $semester=$_SESSION['semester'];
                        $nama_tahun = $this->DMaster->getNamaTA($tahun);
                        $nama_semester = $this->setup->getSemester($semester);

                        $dataReport=$datamhs;
                        $dataReport['krs']=$this->KRS->DataKRS['krs'];        
                        $dataReport['matakuliah']=$this->KRS->DataKRS['matakuliah'];        
                        $dataReport['nama_tahun']=$nama_tahun;
                        $dataReport['nama_semester']=$nama_semester;        

                        $kaprodi=$this->KRS->getKetuaPRODI($dataReport['kjur']);                  
                        $dataReport['nama_kaprodi']=$kaprodi['nama_dosen'];
                        $dataReport['jabfung_kaprodi']=$kaprodi['nama_jabatan'];
                        $dataReport['nipy_kaprodi']=$kaprodi['nipy'];
                        $dataReport['nidn_kaprodi']=$kaprodi['nidn'];

                        $dataReport['linkoutput']=$this->linkOutput;                 
                        $this->report->setDataReport($dataReport); 
                        $this->report->setMode($_SESSION['outputreport']);
                        $this->report->printKRS();				

                    break;
                }
            break;
            case 'btnPrintKRSAll' :
                $repeater=$this->RepeaterS;
                if ($repeater->Items->Count() > 0) {
                    switch ($_SESSION['outputreport']) {
                        case  'summarypdf' :
                            $messageprintout="Mohon maaf Print out pada mode summary pdf belum kami support.";                
                        break;
                        case  'summaryexcel' :
                            $messageprintout="Mohon maaf Print out pada mode summary excel tidak kami support.";                
                        break;
                        case  'excel2007' :
                            $messageprintout="Mohon maaf Print out pada mode excel 2007 belum kami support.";                
                        break;
                        case  'pdf' :               
                            $tahun=$_SESSION['ta'];
                            $semester=$_SESSION['semester'];
                            $nama_tahun = $this->DMaster->getNamaTA($tahun);
                            $nama_semester = $this->setup->getSemester($semester);
                            
                            $kaprodi=$this->KRS->getKetuaPRODI($dataReport['kjur']);                  
                            $dataReport['nama_kaprodi']=$kaprodi['nama_dosen'];
                            $dataReport['jabfung_kaprodi']=$kaprodi['nama_jabatan'];
                            $dataReport['nipy_kaprodi']=$kaprodi['nipy'];
                            $dataReport['nidn_kaprodi']=$kaprodi['nidn'];

                            $currentPage=$repeater->CurrentPageIndex;
                            $offset=$currentPage*$repeater->PageSize;
                            $awal=$offset+1;        
                            $akhir=$repeater->Items->Count()+$offset;                            
                            $dataReport['awal']=$awal;
                            $dataReport['akhir']=$akhir;
                            
                            $dataReport['linkoutput']=$this->linkOutput; 
                            
                            $this->report->setDataReport($dataReport); 
                            $this->report->setMode($_SESSION['outputreport']);
                            
                            $messageprintout="Data Kartu Rencana Studi dari $awal s.d $akhir: <br/>";                            
                            $this->report->printKRSAll($this->DMaster,$repeater);
                        break;
                    }
                }else{
                    $messageprintout="Tidak ada data yang bisa di Cetak.";
                }
            break;
        }
        $this->lblMessagePrintout->Text=$messageprintout;
        $this->lblPrintout->Text="Kartu Rencana Studi T.A $nama_tahun Semester $nama_semester";
        $this->modalPrintOut->show(); 
        
	}    
}