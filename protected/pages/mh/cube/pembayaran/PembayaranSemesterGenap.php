<?php
prado::using ('Application.pagecontroller.mh.pembayaran.CPembayaranSemesterGenap');
class PembayaranSemesterGenap Extends CPembayaranSemesterGenap {		
	public function onLoad($param) {
		parent::onLoad($param);							
    }
    public function checkPembayaranSemesterLalu () { 
        $datamhs=$this->Pengguna->getDataUser();  
		$tahun_masuk=$datamhs['tahun_masuk'];
		$semester_masuk=$datamhs['semester_masuk'];
		$ta=$_SESSION['currentPagePembayaranSemesterGenap']['ta'];			
		if ($tahun_masuk == $ta && $semester_masuk==2) {	
		    return true;
		}else{
			$ta=($ta == $tahun_masuk)?$tahun_masuk:$ta;																		
			$this->Finance->setDataMHS(array('no_formulir'=>$datamhs['no_formulir']));
			$idkelas=$this->Finance->getKelasFromTransaksi($ta,1);
			$datamhs['idkelas']=$idkelas===false?$datamhs['idkelas']:$idkelas;            
			if ($idkelas!='C') {				
				$this->Finance->setDataMHS(array('no_formulir'=>$datamhs['no_formulir'],'nim'=>$datamhs['nim'],'idkelas'=>$datamhs['idkelas'],'tahun_masuk'=>$tahun_masuk,'idsmt'=>1,'perpanjang'=>$datamhs['perpanjang']));
				if ($tahun_masuk==$ta&&$semester_masuk==1)
				{
					$totalbiaya=$this->Finance->getTotalBiayaMhsPeriodePembayaran ();					
				}
				else
				{
					$totalbiaya=$this->Finance->getTotalBiayaMhsPeriodePembayaran ('lama');
				}			 	
				$this->Finance->setDataMHS($datamhs);
				$totalbayar=$this->Finance->getTotalBayarMhs($ta,1);				
                $sisa=$totalbiaya-$totalbayar;                
                $datadulang=$this->Finance->getDataDulang(1,$ta);
                if ($sisa>0 && $datadulang['k_status'] != 'C') {
					$sisa=$this->Finance->toRupiah($sisa);
					$tasmt="T.A ".$this->DMaster->getNamaTA($ta).' semester '.$this->setup->getSemester(1);
					throw new Exception ('Mahasiswa a.n '.$datamhs['nama_mhs']." Memiliki tunggakan sebesar ($sisa) pada $tasmt, harap untuk dilunasi terlebih dahulu.");
				}
			}
		}
	}
}