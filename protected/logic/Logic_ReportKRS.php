<?php
prado::using ('Application.logic.Logic_Report');
class Logic_ReportKRS extends Logic_Report {	    
	public function __construct ($db) {
		parent::__construct ($db);	        
	}  
    /**
     * digunakan untuk printout KRS
     * @param type $objKRS
     */
    public function printKRS () {
        $nim=$this->dataReport['nim'];
        $nama_tahun=$this->dataReport['nama_tahun'];
        $nama_semester=$this->dataReport['nama_semester'];
        
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :                
//                $this->printOut("krs_$nim");
            break;
            case 'pdf' :
                $rpt=$this->rpt;
                $rpt->setTitle('KRS Mahasiswa');
				$rpt->setSubject('KRS Mahasiswa');
                $rpt->AddPage();
				$this->setHeaderPT();
                
                $row=$this->currentRow;
                $row+=6;
                $rpt->SetFont ('helvetica','B',12);	
                $rpt->setXY(3,$row);			
                $kartu='KARTU RENCANA STUDI (KRS)';
                $rpt->Cell(0,$row,$kartu,0,0,'C');

                $row+=6;
                $rpt->SetFont ('helvetica','B',8);	
                $rpt->setXY(3,$row);			
                $rpt->Cell(0,$row,'Nama Mahasiswa (L/P)');
                $rpt->SetFont ('helvetica','',8);
                $rpt->setXY(38,$row);			
                $rpt->Cell(0,$row,': '.$this->dataReport['nama_mhs']);
                $rpt->SetFont ('helvetica','B',8);	
                $rpt->setXY(105,$row);			
                $rpt->Cell(0,$row,'P.S / Jenjang');
                $rpt->SetFont ('helvetica','',8);
                $rpt->setXY(130,$row);			
                $rpt->Cell(0,$row,': '.$this->dataReport['nama_ps'].' / S-1');
                $row+=3;
                $rpt->setXY(3,$row);			
                $rpt->SetFont ('helvetica','B',8);	
                $rpt->Cell(0,$row,'Penasihat Akademik');
                $rpt->SetFont ('helvetica','',8);
                $rpt->setXY(38,$row);			
                $rpt->Cell(0,$row,': '.$this->dataReport['nama_dosen']);				
                $rpt->SetFont ('helvetica','B',8);	
                $rpt->setXY(105,$row);			
                $rpt->Cell(0,$row,'NIM');
                $rpt->SetFont ('helvetica','',8);
                $rpt->setXY(130,$row);			
                $rpt->Cell(0,$row,': '.$this->dataReport['nim']);
                $row+=3;
                $rpt->setXY(3,$row);			
                $rpt->SetFont ('helvetica','B',8);	
                $rpt->Cell(0,$row,'Semester/TA');				
                $rpt->SetFont ('helvetica','',8);
                $rpt->setXY(38,$row);			
                $rpt->Cell(0,$row,': '.$nama_semester.' / '.$nama_tahun);				
                $rpt->SetFont ('helvetica','B',8);	
                $rpt->setXY(105,$row);			
                $rpt->Cell(0,$row,'NIRM');
                $rpt->SetFont ('helvetica','',8);
                $rpt->setXY(130,$row);			
                $rpt->Cell(0,$row,': '.$this->dataReport['nirm']);	

                $row+=20;
                $rpt->SetFont ('helvetica','B',8);
                $rpt->setXY(3,$row);			
                $rpt->Cell(8, 5, 'NO', 1, 0, 'C');				
                $rpt->Cell(15, 5, 'KODE', 1, 0, 'C');								
                $rpt->Cell(90, 5, 'MATAKULIAH', 1, 0, 'C');							
                $rpt->Cell(8, 5, 'SKS', 1, 0, 'C');				
                $rpt->Cell(8, 5, 'SMT', 1, 0, 'C');				
                $rpt->Cell(60, 5, 'NAMA DOSEN', 1, 0, 'C');										


                $totalSks=0;
                $row+=5;				
                $rpt->SetFont ('helvetica','',8);
                $daftarmatkul=$this->dataReport['matakuliah'];
                print_R($daftarmatkul);
                foreach ($daftarmatkul as $v) {
                    if ($v['batal'] == 0) {
                        $rpt->setXY(3,$row);	
                        $rpt->Cell(8, 5, $v['no'], 1, 0, 'C');				
                        $rpt->Cell(15, 5, $v['kmatkul'], 1, 0, 'C');								
                        $rpt->Cell(90, 5, $v['nmatkul'], 1, 0, 'L');							
                        $rpt->Cell(8, 5, $v['sks'], 1, 0, 'C');				
                        $rpt->Cell(8, 5, $v['semester'], 1, 0, 'C');				
                        $rpt->Cell(60, 5, $v['nama_dosen'], 1, 0,'L');											
                        $totalSks+=$v['sks'];
                        $row+=5;
                    }
                }
                $rpt->SetFont ('helvetica','B',8);
                $rpt->setXY(3,$row);							
                $rpt->Cell(113, 5, 'Jumlah SKS',0,0,'C');
                $rpt->Cell(8, 5, $totalSks,0,0,'C');

                $row+=5;				
                $rpt->setXY(3,$row);			

                $rpt->Cell(60, 5, 'Ketua Program Studi',0,0,'C');
                $rpt->Cell(60, 10, 'Penasehat Akademik',0,0,'C');				

                $tanggal=$this->tgl->tanggal('l, j F Y');				
                $rpt->Cell(80, 5, "Tanjungpinang, $tanggal",0,0,'C');


                $row+=5;				
                $rpt->setXY(3,$row);			
                $rpt->Cell(60, 5, $this->dataReport['nama_ps'],0,0,'C');
                $rpt->Cell(60, 5, '',0,0,'C');												
                $rpt->Cell(80, 5, 'Mahasiswa',0,0,'C');												
//
                $row+=20;
                $rpt->setXY(3,$row);			
                $rpt->Cell(60, 5, $this->dataReport['nama_kaprodi'],0,0,'C');				
                $rpt->Cell(60, 5, $this->dataReport['nama_dosen'],0,0,'C');				
                $rpt->Cell(80, 5, $this->dataReport['nama_mhs'],0,0,'C');
                $row+=5;
                $rpt->setXY(3,$row);			
                $rpt->Cell(60, 5, $this->dataReport['jabfung_kaprodi']. ' NIPY : '.$this->dataReport['nipy_kaprodi'],0,0,'C');
                

                $this->printOut("krs_$nim");
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Kartu Rencana Studi T.A $nama_tahun Semester $nama_semester");
    }
    /**
     * digunakan untuk memprint Kartu ujian mahasiswa
     * @param type $jenisujian UTS atau UAS
     * @param type $dataidkrs 
     * @param type $objKRS objek KRS
     * @param type $objDMaster objek data master
     */
    public function printKUM ($jenisujian,$dataidkrs,$objKRS,$objDMaster) {
        
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :                
//                $this->printOut("kum_$nim");
            break;
            case 'summarypdf' :
                $this->setMode('pdf');
                $rpt=$this->rpt;
                
                $rpt->setTitle('Kartu Ujian Mahasiswa');
				$rpt->setSubject('Kartu Ujian Mahasiswa');
                
                while (list($idkrs,$value)=each($dataidkrs)) {                    
                    $rpt->AddPage();
                    $this->setHeaderPT();
                    
                    $row=$this->currentRow;
                    $row+=6;
                    $rpt->SetFont ('helvetica','B',12);	
                    $rpt->setXY(3,$row);			
                    $kartu=($jenisujian=='uts')?'KARTU UJIAN TENGAH SEMESTER (UTS)':'KARTU UJIAN AKHIR SEMESTER (UAS)';
                    $rpt->Cell(0,$row,$kartu,0,0,'C');
                    
                    $str = "SELECT krs.idkrs,vdm.no_formulir,vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,vdm.tempat_lahir,vdm.tanggal_lahir,vdm.kjur,vdm.nama_ps,vdm.idkonsentrasi,k.nama_konsentrasi,vdm.tahun_masuk,vdm.semester_masuk,iddosen_wali,d.idkelas,d.k_status,krs.idsmt,krs.tahun,krs.tasmt,krs.sah FROM krs JOIN dulang d ON (d.nim=krs.nim) LEFT JOIN v_datamhs vdm ON (krs.nim=vdm.nim) LEFT JOIN konsentrasi k ON (vdm.idkonsentrasi=k.idkonsentrasi) WHERE krs.idkrs='$idkrs'";
                    $this->db->setFieldTable(array('idkrs','no_formulir','nim','nirm','nama_mhs','jk','tempat_lahir','tanggal_lahir','kjur','nama_ps','idkonsentrasi','nama_konsentrasi','tahun_masuk','semester_masuk','iddosen_wali','idkelas','k_status','idsmt','tahun','tasmt','sah'));
                    $r=$this->db->getRecord($str);	           
                    $dataReport=$r[1];

                    $dataReport['nama_ps']=$_SESSION['daftar_jurusan'][$dataReport['kjur']];                
                    $nama_tahun = $objDMaster->getNamaTA($dataReport['tahun']);   
                    $nama_semester = $this->setup->getSemester($dataReport['idsmt']);
                    $dataReport['nama_tahun']=$nama_tahun; 
                    $dataReport['nama_semester']=$nama_semester;

                    $nama_dosen=$objDMaster->getNamaDosenWaliByID($dataReport['iddosen_wali']);				                    
                    $dataReport['nama_dosen']=$nama_dosen;

                    $kaprodi=$objKRS->getKetuaPRODI($dataReport['kjur']);
                    $dataReport['nama_kaprodi']=$kaprodi['nama_dosen'];
                    $dataReport['jabfung_kaprodi']=$kaprodi['nama_jabatan'];
                    $dataReport['nidn_kaprodi']=$kaprodi['nidn'];
                    $row+=6;
                    $rpt->SetFont ('helvetica','B',8);	
                    $rpt->setXY(3,$row);			
                    $rpt->Cell(0,$row,'Nama Mahasiswa (L/P)');
                    $rpt->SetFont ('helvetica','',8);
                    $rpt->setXY(38,$row);			
                    $rpt->Cell(0,$row,': '.$dataReport['nama_mhs'].' ('.$dataReport['jk'].')');
                    $rpt->SetFont ('helvetica','B',8);	
                    $rpt->setXY(105,$row);			
                    $rpt->Cell(0,$row,'P.S / Jenjang');
                    $rpt->SetFont ('helvetica','',8);
                    $rpt->setXY(130,$row);			
                    $rpt->Cell(0,$row,': '.$dataReport['nama_ps'].' / S-1');
                    $row+=3;
                    $rpt->setXY(3,$row);			
                    $rpt->SetFont ('helvetica','B',8);	
                    $rpt->Cell(0,$row,'Penasihat Akademik');
                    $rpt->SetFont ('helvetica','',8);
                    $rpt->setXY(38,$row);			
                    $rpt->Cell(0,$row,': '.$dataReport['nama_dosen']);				
                    $rpt->SetFont ('helvetica','B',8);	
                    $rpt->setXY(105,$row);			
                    $rpt->Cell(0,$row,'NIM');
                    $rpt->SetFont ('helvetica','',8);
                    $rpt->setXY(130,$row);			
                    $rpt->Cell(0,$row,': '.$dataReport['nim']);
                    $row+=3;
                    $rpt->setXY(3,$row);			
                    $rpt->SetFont ('helvetica','B',8);	
                    $rpt->Cell(0,$row,'Semester/TA');				
                    $rpt->SetFont ('helvetica','',8);
                    $rpt->setXY(38,$row);			
                    $rpt->Cell(0,$row,": $nama_semester / $nama_tahun");				
                    $rpt->SetFont ('helvetica','B',8);	
                    $rpt->setXY(105,$row);			
                    $rpt->Cell(0,$row,'NIRM');
                    $rpt->SetFont ('helvetica','',8);
                    $rpt->setXY(130,$row);			
                    $rpt->Cell(0,$row,': '.$dataReport['nirm']);			

                    $row+=20;
                    $rpt->SetFont ('helvetica','B',8);
                    $rpt->setXY(3,$row);			
                    $rpt->Cell(8, 5, 'NO', 1, 0, 'C');				
                    $rpt->Cell(15, 5, 'KODE', 1, 0, 'C');								
                    $rpt->Cell(70, 5, 'MATAKULIAH', 1, 0, 'C');							
                    $rpt->Cell(8, 5, 'SKS', 1, 0, 'C');				
                    $rpt->Cell(8, 5, 'SMT', 1, 0, 'C');				
                    $rpt->Cell(60, 5, 'PENGAWAS', 1, 0, 'C');						
                    $rpt->Cell(15, 5, 'TGL', 1, 0, 'C');
                    $rpt->Cell(15, 5, 'TTD', 1, 0, 'C');

                    $daftar_matkul=$objKRS->getDetailKRS($idkrs);
                    $totalSks=0;
                    $row+=5;				
                    $rpt->SetFont ('helvetica','',8);
                    while (list($k,$v)=each($daftar_matkul)) {
                        $rpt->setXY(3,$row);	
                        $rpt->Cell(8, 5, $v['no'], 1, 0, 'C');				
                        $rpt->Cell(15, 5, $v['kmatkul'], 1, 0, 'C');		
                        $flag='';
                        if ($jenisujian=='uas') {
                            $idkrsmatkul=$v['idkrsmatkul'];
                            $str = "kbm_detail WHERE idkrsmatkul='$idkrsmatkul' AND kehadiran='hadir'";										
                            $flag=' *';
                            if ($totalpertemuan=$this->db->getCountRowsOfTable($str,'idkrsmatkul')>=1) {
                                $minimal=round(($totalpertemuan/14)*100);													
                                $flag=$minimal<75?'*':'';
                            }
                        }					
                        $rpt->Cell(70, 5, $v['nmatkul'].$flag , 1, 0, 'L');							
                        $rpt->Cell(8, 5, $v['sks'], 1, 0, 'C');				
                        $rpt->Cell(8, 5, $v['semester'], 1, 0, 'C');				
                        $rpt->Cell(60, 5, $v['nama_dosen'], 1, 0, 'L');						
                        $rpt->Cell(15, 5, '', 1, 0, 'C');
                        $rpt->Cell(15, 5, '', 1, 0, 'C');					
                        $totalSks+=$v['sks'];
                        $row+=5;
                    }
                    $row+=3;
                    $rpt->setXY(3,$row);	
                    $rpt->SetFont ('helvetica','',6);
                    $rpt->Cell(70, 5, 'Catatan : Tanda "*" memiliki arti absensi Mahasiswa kurang dari 75%.' , 0, 0, 'L');	

                    $row+=5;
                    $rpt->SetFont ('helvetica','B',8);
                    $rpt->setXY(120,$row);			
                    $rpt->Cell(80, 5, 'Mengetahui,',0,0,'L');				

                    $row+=5;
                    $rpt->setXY(120,$row);			
                    $tanggal=$this->tgl->tanggal('j F Y');				
                    $rpt->Cell(80, 5, "Tanjungpinang, $tanggal",0,0,'L');

                    $row+=5;				
                    $rpt->setXY(120,$row);			
                    $rpt->Cell(80, 5, 'Ketua Program Studi '.$dataReport['nama_ps'],0,0,'L');								

                    $row+=20;				
                    $rpt->setXY(120,$row);			
                    $rpt->Cell(80, 5, $dataReport['nama_kaprodi'],0,0,'L');

                    $row+=5;							
                    $rpt->setXY(120,$row);
                    $nama_jabatan=$dataReport['jabfung_kaprodi'];
                    $nidn=$dataReport['nidn_kaprodi'];
                    $rpt->Cell(80, 5, "$nama_jabatan NIDN : $nidn",0,0,'L');
                    
                }
                $this->printOut("kum");
            break;
            case 'pdf' :
                $rpt=$this->rpt;
                $rpt->AddPage();
                $this->setHeaderPT();
                 
                $nim=$this->dataReport['nim'];
                $nama_tahun=$this->dataReport['nama_tahun'];
                $nama_semester=$this->dataReport['nama_semester'];
        
                $rpt->setTitle('Kartu Ujian Mahasiswa');
				$rpt->setSubject('Kartu Ujian Mahasiswa');
                
                
                $row=$this->currentRow;
				$row+=6;
				$rpt->SetFont ('helvetica','B',12);	
				$rpt->setXY(3,$row);			
                $kartu=($jenisujian=='uts')?'KARTU UJIAN TENGAH SEMESTER (UTS)':'KARTU UJIAN AKHIR SEMESTER (UAS)';
				$rpt->Cell(0,$row,$kartu,0,0,'C');
                
				$row+=6;
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(3,$row);			
				$rpt->Cell(0,$row,'Nama Mahasiswa (L/P)');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$this->dataReport['nama_mhs'].' ('.$this->dataReport['jk'].')');
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'P.S / Jenjang');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': '.$this->dataReport['nama_ps'].' / S-1');
				$row+=3;
				$rpt->setXY(3,$row);			
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->Cell(0,$row,'Penasihat Akademik');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$this->dataReport['nama_dosen']);				
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'NIM');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,": $nim");
				$row+=3;
				$rpt->setXY(3,$row);			
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->Cell(0,$row,'Semester/TA');				
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,": $nama_semester / $nama_tahun");				
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'NIRM');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': '.$this->dataReport['nirm']);			
				
                $row+=20;
				$rpt->SetFont ('helvetica','B',8);
				$rpt->setXY(3,$row);			
				$rpt->Cell(8, 5, 'NO', 1, 0, 'C');				
				$rpt->Cell(15, 5, 'KODE', 1, 0, 'C');								
				$rpt->Cell(70, 5, 'MATAKULIAH', 1, 0, 'C');							
				$rpt->Cell(8, 5, 'SKS', 1, 0, 'C');				
				$rpt->Cell(8, 5, 'SMT', 1, 0, 'C');				
				$rpt->Cell(60, 5, 'PENGAWAS', 1, 0, 'C');						
				$rpt->Cell(15, 5, 'TGL', 1, 0, 'C');
				$rpt->Cell(15, 5, 'TTD', 1, 0, 'C');
                
                $daftar_matkul=$objKRS->getDetailKRS($this->dataReport['idkrs']);
				$totalSks=0;
				$row+=5;				
				$rpt->SetFont ('helvetica','',8);
                while (list($k,$v)=each($daftar_matkul)) {
                    $rpt->setXY(3,$row);	
					$rpt->Cell(8, 5, $v['no'], 1, 0, 'C');				
					$rpt->Cell(15, 5, $v['kmatkul'], 1, 0, 'C');		
					$flag='';
					if ($jenisujian=='uas') {
						$idkrsmatkul=$v['idkrsmatkul'];
						$str = "kbm_detail WHERE idkrsmatkul='$idkrsmatkul' AND kehadiran='hadir'";										
						$flag=' *';
						if ($totalpertemuan=$this->db->getCountRowsOfTable($str,'idkrsmatkul')>=1) {
							$minimal=round(($totalpertemuan/14)*100);													
							$flag=$minimal<75?'*':'';
						}
					}					
					$rpt->Cell(70, 5, $v['nmatkul'].$flag , 1, 0, 'L');							
					$rpt->Cell(8, 5, $v['sks'], 1, 0, 'C');				
					$rpt->Cell(8, 5, $v['semester'], 1, 0, 'C');				
					$rpt->Cell(60, 5, $v['nama_dosen'], 1, 0, 'L');						
					$rpt->Cell(15, 5, '', 1, 0, 'C');
					$rpt->Cell(15, 5, '', 1, 0, 'C');					
					$totalSks+=$v['sks'];
					$row+=5;
				}
                $row+=3;
				$rpt->setXY(3,$row);	
				$rpt->SetFont ('helvetica','',6);
				$rpt->Cell(70, 5, 'Catatan : Tanda "*" memiliki arti absensi Mahasiswa kurang dari 75%.' , 0, 0, 'L');	
				
				$row+=5;
				$rpt->SetFont ('helvetica','B',8);
				$rpt->setXY(120,$row);			
				$rpt->Cell(80, 5, 'Mengetahui,',0,0,'L');				
				
				$row+=5;
				$rpt->setXY(120,$row);			
				$tanggal=$this->tgl->tanggal('j F Y');				
				$rpt->Cell(80, 5, "Tanjungpinang, $tanggal",0,0,'L');
				
				$row+=5;				
				$rpt->setXY(120,$row);			
				$rpt->Cell(80, 5, 'Ketua Program Studi '.$this->dataReport['nama_ps'],0,0,'L');								
				
				$row+=20;				
				$rpt->setXY(120,$row);			
				$rpt->Cell(80, 5, $this->dataReport['nama_kaprodi'],0,0,'L');
				
				$row+=5;							
				$rpt->setXY(120,$row);
                $nama_jabatan=$this->dataReport['jabfung_kaprodi'];
                $nidn=$this->dataReport['nidn_kaprodi'];
				$rpt->Cell(80, 5, "$nama_jabatan NIDN : $nidn",0,0,'L');	
                $this->printOut("kum_$nim");
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Kartu Ujian Mahasiswa T.A $nama_tahun Semester $nama_semester");
    }
}
?>