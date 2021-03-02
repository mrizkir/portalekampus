<?php
prado::using ('Application.MainPageF');
class Pendaftaran extends MainPageF {
	public function onLoad($param) {		
		parent::onLoad($param);	         
		$this->createObj('Dmaster');
		if (!$this->IsPostBack&&!$this->IsCallBack) { 
		    
		    $this->lblModulHeader->Text=$this->setup->getSettingValue('default_tahun_pendaftaran');
		    $daftar_prodi=$this->DMaster->getListProgramStudi(2);
		    $daftar_prodi['none']='PILIH PROGRAM STUDI 1';
		    $this->cmbAddKjur1->DataSource=$daftar_prodi;
		    $this->cmbAddKjur1->dataBind();
		    $this->cmbAddKjur2->Enabled=false;
		    
		    $daftar_kelas=$this->DMaster->getListKelasPendaftaran();		    			
		    $this->cmbAddKelas->DataSource=$daftar_kelas;
		    $this->cmbAddKelas->DataBind();
		}
	}    
	public function changePs($sender,$param) {
        if ($sender->Text == 'none') {
            $this->cmbAddKjur2->Enabled=false;
            $this->cmbAddKjur2->Text='none';
        }else{
            $daftar_prodi=$this->DMaster->getListProgramStudi(2);
            $daftar_prodi['none']='PILIH PROGRAM STUDI 2';
            $jurusan=$this->DMaster->removeKjur($daftar_prodi,$sender->Text);
            $this->cmbAddKjur2->Enabled=true;       
            $this->cmbAddKjur2->DataSource=$jurusan;
            $this->cmbAddKjur2->dataBind();
        }	    
	}
	public function checkEmail ($sender,$param) {
	    $id=$sender->getId ();
	    $this->idProcess = ($id=='editEmail')?'edit':'add';
	    $email_mhs=addslashes($param->Value);
	    try {
	        if ($email_mhs != '') {
                if ($this->DB->checkRecordIsExist('email','profiles_mahasiswa',$email_mhs)) {
                    throw new Exception ("Email ($email_mhs) sudah tidak tersedia. Silahkan ganti dengan yang lain.");
                }
                if ($this->DB->checkRecordIsExist('email','formulir_pendaftaran_temp',$email_mhs)) {
                    throw new Exception ("Email ($email_mhs) sudah tidak tersedia. Silahkan ganti dengan yang lain.");
                }
	        }
	    }catch (Exception $e) {
	        $param->IsValid=false;
	        $sender->ErrorMessage=$e->getMessage();
	    }
	}
	public function saveData ($sender,$param) {
	    if ($this->IsValid) {
	        $nama_mhs=addslashes(strtoupper(trim($this->txtAddNamaMhs->Text)));
	        $tempat_lahir=addslashes(strtoupper(trim($this->txtAddTempatLahir->Text)));
	        $tgl_lahir=date ('Y-m-d',$this->txtAddTanggalLahir->TimeStamp);
	        $jk=$this->rdAddPria->Checked===true?'L':'P';
	        $telp_hp=addslashes($this->txtAddNoTelpHP->Text);
	        $email=addslashes($this->txtAddEmail->Text);
	        $kjur1=$this->cmbAddKjur1->Text;
			$kjur2=($this->cmbAddKjur2->Text)>0?$this->cmbAddKjur1->Text:0;	        
	        $idkelas=$this->cmbAddKelas->Text;
	        $data=$this->Pengguna->createHashPassword($this->txtPassword1->Text);
	        $salt=$data['salt'];
	        $password=$data['password']; 
	        $ta=$this->setup->getSettingValue('default_tahun_pendaftaran');
	        $idsmt=1;
	        $no_pendaftaran=$ta.mt_rand(1000,9999).$idsmt;
	        $str  = "INSERT INTO formulir_pendaftaran_temp SET no_pendaftaran='$no_pendaftaran',no_formulir=0,nama_mhs='$nama_mhs',tempat_lahir='$tempat_lahir',tanggal_lahir='$tgl_lahir',jk='$jk',email='$email',telp_hp='$telp_hp',kjur1='$kjur1',kjur2='$kjur2',idkelas='$idkelas',ta='$ta',idsmt='$idsmt',salt='$salt',userpassword='$password',waktu_mendaftar=NOW(),file_bukti_bayar=''";
	        $this->DB->insertRecord($str);
	        
	        $this->redirect('PendaftaranSuccess',false,array('id'=>$no_pendaftaran));
	    }
	}
}