<?php
prado::using ('Application.MainPageK');
class CPembayaranCutiSemesterGanjil Extends MainPageK {
	public function onLoad($param) {
		parent::onLoad($param);	
        $this->createObj('Finance');
        $this->showMenuPembayaran=true;
        $this->showPembayaranCutiSemesterGanjil=true;
		if (!$this->IsPostBack&&!$this->IsCallBack) {	
            if (!isset($_SESSION['currentPagePembayaranCutiSemesterGanjil'])||$_SESSION['currentPagePembayaranCutiSemesterGanjil']['page_name']!='k.pembayaran.PembayaranCutiSemesterGanjil') {
				$_SESSION['currentPagePembayaranCutiSemesterGanjil']=array('page_name'=>'k.pembayaran.PembayaranCutiSemesterGanjil','page_num'=>0,'search'=>false,'DataMHS'=>array(),'ta'=>$_SESSION['ta']);												
			}
            $daftar_ps=$this->DMaster->removeIdFromArray($_SESSION['daftar_jurusan'],'none');            
			$this->tbCmbPs->DataSource=$daftar_ps;
			$this->tbCmbPs->Text=$_SESSION['kjur'];			
			$this->tbCmbPs->dataBind();	
           
            $this->tbCmbTA->DataSource=$this->DMaster->getListTA ();
            $this->tbCmbTA->Text=$_SESSION['currentPagePembayaranCutiSemesterGanjil']['ta'];
            $this->tbCmbTA->dataBind();  
            
            $this->setInfoToolbar();
            $this->populateData();

		}	
	}
    
    public function setInfoToolbar() {                
        $kjur=$_SESSION['kjur'];        
		$ps=$_SESSION['daftar_jurusan'][$kjur];
        $ta=$this->DMaster->getNamaTA($_SESSION['ta']);        		
		$this->lblModulHeader->Text="Program Studi $ps T.A $ta";        
	}
	public function changeTbTA ($sender,$param) {				
        $ta=$this->tbCmbTA->Text;
		$_SESSION['currentPagePembayaranCutiSemesterGanjil']['ta']=$ta;    
        $this->setInfoToolbar();
		$this->populateData();
	} 
	public function changeTbPs ($sender,$param) {		
		$_SESSION['kjur']=$this->tbCmbPs->Text;
        $this->setInfoToolbar();
		$this->populateData();
	}
	public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}
    public function Page_Changed ($sender,$param) {
		$_SESSION['currentPagePembayaranCutiSemesterGanjil']['page_num']=$param->NewPageIndex;
		$this->populateData();
	}		
	public function populateData() {	
		$ta=$_SESSION['ta'];
		$kjur=$_SESSION['kjur'];	
		$this->RepeaterS->CurrentPageIndex=$_SESSION['currentPagePembayaranCutiSemesterGanjil']['page_num'];
		$jumlah_baris=$this->DB->getCountRowsOfTable("v_datamhs vdm,transaksi_cuti tc WHERE vdm.nim=tc.nim AND vdm.kjur='$kjur' AND tc.tahun='$ta' AND tc.idsmt='1'");
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$offset=$this->RepeaterS->CurrentPageIndex*$this->RepeaterS->PageSize;
		$limit=$this->RepeaterS->PageSize;
		if (($offset+$limit)>$this->RepeaterS->VirtualItemCount) {
			$limit=$this->RepeaterS->VirtualItemCount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPagePembayaranCutiSemesterGanjil']['page_num']=0;}
		$str = "SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.idkelas,vdm.telp_hp FROM v_datamhs vdm,transaksi_cuti tc WHERE vdm.nim=tc.nim AND vdm.kjur='$kjur' AND tc.tahun='$ta' AND tc.idsmt='1' ORDER BY vdm.nama_mhs ASC LIMIT $offset,$limit";
		$this->DB->setFieldTable(array('nim','nirm','nama_mhs','idkelas','telp_hp'));
		$r=$this->DB->getRecord($str);
		$this->RepeaterS->DataSource=$r;
		$this->RepeaterS->dataBind();
	}
    public function cekNIM ($sender,$param) {		
        $nim=addslashes($param->Value);		
        if ($nim != '') {
            try {
                $str = "SELECT k_status FROM v_datamhs vdm WHERE vdm.nim='$nim'";
                $this->DB->setFieldTable(array('k_status'));
                $r=$this->DB->getRecord($str);
                $datamhs=$r[1];
                if (isset($datamhs[1])) {                                   
                    throw new Exception ("<br /><br />NIM ($nim) tidak terdaftar di Portal, silahkan ganti dengan yang lain.");		
                }
                $this->Finance->setDataMHS(array('nim'=>$nim));
                $datadulang=$this->Finance->getDataDulang(1,$_SESSION['currentPagePembayaranCutiSemesterGanjil']['ta']);
                
                if (isset($datadulang['iddulang'])) {
                    if ($datadulang['k_status']!='C') {
                        $status=$this->DMaster->getNamaStatusMHSByID ($datadulang['k_status']);
                        $ta=$datadulang['tahun'];
                        throw new Exception ("<br /><br />NIM ($nim) sudah daftar ulang di semester Ganjil T.A $ta dengan status $status.");		
                    }
                }
            }catch (Exception $e) {
                $param->IsValid=false;
                $sender->ErrorMessage=$e->getMessage();
            }	
        }	
    }
	public function Go($param,$sender) {
        if ($this->IsValid) {				
            $nim=addslashes($this->txtNIM->Text);
            $this->redirect('pembayaran.DetailPembayaranCutiSemesterGanjil',true,array('id'=>$nim));
        }					
	}
}

?>