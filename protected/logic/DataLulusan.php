<?php
prado::using ('Application.MainPageF');
class DataLulusan extends MainPageF {
	public function onLoad($param) {		
		parent::onLoad($param);	    
        $this->createObj('Nilai');
		if (!$this->IsPostBack&&!$this->IsCallBack) {
            if (!isset($_SESSION['currentPageDataLulusan'])||$_SESSION['currentPageDataLulusan']['page_name']!='DataLulusan') {					
                $_SESSION['currentPageDataLulusan']=array('page_name'=>'DataLulusan','page_num'=>0,'search'=>false,'tanggal_terbit'=>'none','DataMHS'=>array(),'DataNilai'=>array());												
            }
            $_SESSION['currentPageDataLulusan']['search']=false;
            $this->RepeaterS->PageSize=$this->setup->getSettingValue('default_pagesize');
            
            $this->populateData();
        }
	}
    public function renderCallback ($sender,$param) {
		$this->RepeaterS->render($param->NewWriter);	
	}
	public function Page_Changed ($sender,$param) {
		$_SESSION['currentPageDataLulusan']['page_num']=$param->NewPageIndex;
		$this->populateData($_SESSION['currentPageDataLulusan']['search']);
	}
    public function searchRecord ($sender,$param) {
		$_SESSION['currentPageDataLulusan']['search']=true;
		$this->populateData($_SESSION['currentPageDataLulusan']['search']);
	}
    public function populateData($search=false) {	     
        if ($search) {
            $str = "SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,nomor_transkrip,predikat_kelulusan,tanggal_lulus,ta.judul_skripsi,CONCAT(ta.tahun,'',ta.idsmt) AS tasmt FROM v_datamhs vdm,transkrip_asli ta WHERE ta.nim=vdm.nim";
            $txtsearch=addslashes($this->txtKriteria->Text);
            switch ($this->cmbKriteria->Text) {                
                case 'nim' :
                    $clausa="AND ta.nim='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_datamhs vdm,transkrip_asli ta WHERE ta.nim=vdm.nim AND vdm.k_status='L' $clausa",'ta.nim');
                    $str = "$str $clausa";
                break;
                case 'nirm' :
                    $clausa="AND vdm.nirm='$txtsearch'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_datamhs vdm,transkrip_asli ta WHERE ta.nim=vdm.nim AND vdm.k_status='L' $clausa",'ta.nim');
                    $str = "$str $clausa";
                break;
                case 'nama' :
                    $clausa="AND vdm.nama_mhs LIKE '%$txtsearch%'";
                    $jumlah_baris=$this->DB->getCountRowsOfTable ("v_datamhs vdm,transkrip_asli ta WHERE ta.nim=vdm.nim AND vdm.k_status='L' $clausa",'ta.nim');
                    $str = "$str $clausa";
                break;
            }
        }else{
            $str = "SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,nomor_transkrip,predikat_kelulusan,tanggal_lulus,ta.judul_skripsi,CONCAT(ta.tahun,'',ta.idsmt) AS tasmt FROM v_datamhs vdm,transkrip_asli ta WHERE ta.nim=vdm.nim AND vdm.k_status='L'";
            $jumlah_baris=$this->DB->getCountRowsOfTable("v_datamhs vdm,transkrip_asli ta WHERE ta.nim=vdm.nim AND vdm.k_status='L'",'ta.nim');				
        }        
		$this->RepeaterS->CurrentPageIndex=$_SESSION['currentPageDataLulusan']['page_num'];		
		$this->RepeaterS->VirtualItemCount=$jumlah_baris;
		$offset=$this->RepeaterS->CurrentPageIndex*$this->RepeaterS->PageSize;
		$limit=$this->RepeaterS->PageSize;
		if ($offset+$limit>$this->RepeaterS->VirtualItemCount) {
			$limit=$this->RepeaterS->VirtualItemCount-$offset;
		}
		if ($limit < 0) {$offset=0;$limit=10;$_SESSION['currentPageDataLulusan']['page_num']=0;}
        $str = "$str ORDER BY ta.tahun DESC,ta.idsmt ASC,vdm.nama_mhs ASC LIMIT $offset,$limit";
		$this->DB->setFieldTable(array('nim','nirm','nama_mhs','nomor_transkrip','predikat_kelulusan','tanggal_lulus','judul_skripsi','tasmt'));
		$result=$this->DB->getRecord($str,$offset+1);
		$this->RepeaterS->DataSource=$result;
		$this->RepeaterS->dataBind();
        
        $this->paginationInfo->Text=$this->getInfoPaging($this->RepeaterS);
	}	
	public function setDataBound ($sender,$param) {
		$item=$param->Item;
		if ($item->ItemType === 'Item' || $item->ItemType === 'AlternatingItem') {
			$nim=$item->DataItem['nim'];			
            $this->Nilai->setDataMHS(array('nim'=>$nim));
            $this->Nilai->getTranskrip(false);            
			$item->lblIpk->Text=$this->Nilai->getIPKAdaNilai();
		}	
	}
}