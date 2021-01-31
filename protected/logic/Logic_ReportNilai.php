<?php
prado::using ('Application.logic.Logic_Report');
class Logic_ReportNilai extends Logic_Report {	    
	public function __construct ($db) {
		parent::__construct ($db);	        
	}    
    /**
     * digunakan untuk memprint KHS
     * @param type $objNilai object
     */
    public function printKHS ($objNilai,$withsignature=false) {
        $nim=$this->dataReport['nim'];
        $ta=$this->dataReport['ta'];
        $semester=$this->dataReport['semester'];
        $nama_tahun=$this->dataReport['nama_tahun'];
        $nama_semester=$this->dataReport['nama_semester'];
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :                
//                $this->printOut("khs_$nim");
            break;
            case 'pdf' :
                $rpt=$this->rpt;
                $rpt->setTitle('KHS Mahasiswa');
				$rpt->setSubject('KHS Mahasiswa');
                $rpt->AddPage();
				$this->setHeaderPT();
                
                $row=$this->currentRow;
				$row+=6;
				$rpt->SetFont ('helvetica','B',12);	
				$rpt->setXY(3,$row);			
				$rpt->Cell(0,$row,'KARTU HASIL STUDI (KHS)',0,0,'C');
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
				$rpt->Cell(0,$row,": $nama_semester / $ta");				
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'NIRM');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': '.$this->dataReport['nirm']);			
				
				$row+=20;
				$rpt->SetFont ('helvetica','B',8);
				$rpt->setXY(3,$row);			
				$rpt->Cell(13, 5, 'NO', 1, 0, 'C');				
				$rpt->Cell(15, 5, 'KODE', 1, 0, 'C');								
				$rpt->Cell(80, 5, 'MATAKULIAH', 1, 0, 'C');							
				$rpt->Cell(10, 5, 'HM', 1, 0, 'C');				
				$rpt->Cell(10, 5, 'SKS', 1, 0, 'C');				
				$rpt->Cell(10, 5, 'NM', 1, 0, 'C');						
				$rpt->Cell(47, 5, 'KETERANGAN', 1, 0, 'C');
                $objNilai->setDataMHS(array('nim'=>$nim));
				$dn=$objNilai->getKHS($ta,$semester);				
				$totalSks=0;
				$totalNm=0;
				$row+=5;
				$rpt->setXY(3,$row);			
				$rpt->SetFont ('helvetica','',8);
                while (list($k,$v)=each($dn)) {
					$rpt->setXY(3,$row);			
					$rpt->Cell(13, 5, $v['no'], 1, 0, 'C');							
					$rpt->Cell(15, 5, $v['kmatkul'], 1, 0, 'C');		
					$rpt->Cell(80, 5, $v['nmatkul'], 1, 0, 'L');				
					$n_kual=$v['n_kual']==''?'-':$v['n_kual'];
					$sks=$v['sks'];
					$m=$v['m'];										
					$rpt->Cell(10, 5, $n_kual, 1, 0, 'C');					
					$rpt->Cell(10, 5, $sks, 1, 0, 'C');					
					$rpt->Cell(10, 5, $m, 1, 0, 'C');					
					$rpt->Cell(47, 5, '', 1, 0, 'C');													
					$totalSks+=$sks;				
					$totalNm+=$m;					
					$row+=5;
				}
                $rpt->setXY(31,$row);			
				$rpt->Cell(90, 5, 'Jumlah Kredit',0);											
				$rpt->Cell(10, 5, $totalSks, 1, 0, 'C');			
				$rpt->Cell(10, 5, $totalNm, 1, 0, 'C');			
				$ip=@ bcdiv($totalNm,$totalSks,2);
				$rpt->Cell(47, 5, "IPS : $ip", 1, 0, 'C');
                
                $row+=5;
				$nilaisemesterlalu=$objNilai->getKumulatifSksDanNmSemesterLalu($ta,$semester);				
				$rpt->setXY(31,$row);			
				$rpt->Cell(90, 5, 'Jumlah Kumulatif Semester Lalu (JKSL)');												
				$rpt->Cell(10, 5, $nilaisemesterlalu['total_sks'], 1, 0, 'C');				
				$rpt->Cell(10, 5, $nilaisemesterlalu['total_nm'], 1, 0, 'C');	
				$rpt->Cell(47, 5, '', 1, 0, 'C');
                
                
                $nilaisemestersekarang=$objNilai->getIPKSampaiTASemester($ta,$semester,'ipksksnm');
                $row+=5;
				$rpt->setXY(31,$row);			
				$rpt->Cell(90, 5, 'Jumlah Kumulatif Semester Ini (JKSI)');												
                
				$rpt->Cell(10, 5, $nilaisemestersekarang['sks'], 1, 0, 'C');
				$rpt->Cell(10, 5, $nilaisemestersekarang['nm'], 1, 0, 'C');
							
				$ipk=$nilaisemestersekarang['ipk'];                
				$rpt->Cell(47, 5, "IPK : $ipk", 1, 0, 'C');
                
                $row+=5;
				$nextsemeserandta=$objNilai->getNextSemesterAndTa ($ta,$semester);							
				$rpt->setXY(31,$row);			
				$rpt->Cell(90, 5, 'Jumlah Maksimum Kredit yang dapat diambil');												
				$rpt->Cell(67, 8, $this->setup->getSksNextSemester($ip), 1, 0, 'C');
				
				$row+=5;
				$rpt->setXY(31,$row);										
				$rpt->Cell(90, 5, 'Pada semester '.$nextsemeserandta['semester'].' Tahun Akademik '.$nextsemeserandta['ta']);								
                
                if ($withsignature) {
                    $row+=5;
                    $rpt->SetFont ('helvetica','B',8);
                    $rpt->setXY(31,$row);			
                    $rpt->Cell(60, 5, 'Mengetahui',0,0,'C');				

                    $tanggal=$this->tgl->tanggal('l, j F Y');				
                    $rpt->Cell(80, 5, $this->setup->getSettingValue('kota_pt').", $tanggal",0,0,'C');		

                    $row+=5;
                    $rpt->setXY(31,$row);			
                    $rpt->Cell(60, 5, 'A.n. Ketua '.$this->dataReport['nama_pt_alias'],0,0,'C');			
                    $rpt->Cell(80, 5, "Ketua Program Studi",0,0,'C');		

                    $row+=5;
                    $rpt->setXY(31,$row);			
                    $rpt->Cell(60, 5, $this->dataReport['nama_jabatan_khs'],0,0,'C');				
                                        
                    $nama_ps=$this->dataReport['nama_ps'];			
                    $rpt->Cell(80, 5, $nama_ps,0,0,'C');		

                    $row+=20;                    
                    $rpt->setXY(31,$row);			                    
                    $rpt->Cell(60, 5,$this->dataReport['nama_penandatangan_khs'],0,0,'C');

                    $rpt->Cell(80, 5,$this->dataReport['nama_kaprodi'],0,0,'C');

                    $row+=5;
                    $rpt->setXY(31,$row);			
                    $nama_jabatan=strtoupper($this->dataReport['jabfung_penandatangan_khs']);
                    $nidn=$this->dataReport['nidn_penandatangan_khs'];
                    $rpt->Cell(60, 5, "$nama_jabatan NIDN : $nidn",0,0,'C');
                    $rpt->Cell(80, 5, strtoupper($this->dataReport['jabfung_kaprodi']). ' NIDN : '.$this->dataReport['nidn_kaprodi'],0,0,'C');
                }
                $this->printOut("khs_$nim");
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Kartu Hasil Studi T.A $nama_tahun Semester $nama_semester");
    }
    /**
     * digunakan untuk memprint seluruh KHS dalam Semester dan T.A
     * @param type $objNilai object
     */
    public function printKHSAll ($objNilai,$objDMaster,$repeater,$withsignature=false) {        
        $awal=$this->dataReport['awal'];
        $akhir=$this->dataReport['akhir'];
        $ta=$this->dataReport['ta'];
        $semester=$this->dataReport['semester'];
        $nama_tahun=$this->dataReport['nama_tahun'];
        $nama_semester=$this->dataReport['nama_semester'];
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :                
//                $this->printOut("khs_$nim");
            break;
            case 'pdf' :
                $rpt=$this->rpt;
                $rpt->setTitle('KHS Mahasiswa');
				$rpt->setSubject('KHS Mahasiswa');
                foreach ($repeater->Items as $inputan) {	
                    if ($inputan->btnPrintOutR->Enabled) {
                        $item=$inputan->btnPrintOutR->getNamingContainer();
                        $idkrs=$repeater->DataKeys[$item->getItemIndex()];                        
                        
                        $str = "SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,vdm.kjur,vdm.nama_ps,vdm.idkonsentrasi,k.nama_konsentrasi,iddosen_wali FROM krs LEFT JOIN v_datamhs vdm ON (krs.nim=vdm.nim) LEFT JOIN konsentrasi k ON (vdm.idkonsentrasi=k.idkonsentrasi) WHERE krs.idkrs=$idkrs";
                        $this->db->setFieldTable(array('nim','nirm','nama_mhs','jk','kjur','nama_ps','idkonsentrasi','nama_konsentrasi','iddosen_wali'));
                        $r=$this->db->getRecord($str);	           
                        $dataMhs=$r[1];
                        $nim=$dataMhs['nim'];
                                                
                        $rpt->AddPage();
                        $this->setHeaderPT();
                                                                
                        $row=$this->currentRow;
                        $row+=6;
                        $rpt->SetFont ('helvetica','B',12);	
                        $rpt->setXY(3,$row);			
                        $rpt->Cell(0,$row,'KARTU HASIL STUDI (KHS)',0,0,'C');
                        $row+=6;
                        $rpt->SetFont ('helvetica','B',8);	
                        $rpt->setXY(3,$row);			
                        $rpt->Cell(0,$row,'Nama Mahasiswa (L/P)');
                        $rpt->SetFont ('helvetica','',8);
                        $rpt->setXY(38,$row);			
                        $rpt->Cell(0,$row,': '.$dataMhs['nama_mhs'].' ('.$dataMhs['jk'].')');
                        $rpt->SetFont ('helvetica','B',8);	
                        $rpt->setXY(105,$row);			
                        $rpt->Cell(0,$row,'P.S / Jenjang');
                        $rpt->SetFont ('helvetica','',8);
                        $rpt->setXY(130,$row);			
                        $rpt->Cell(0,$row,': '.$dataMhs['nama_ps'].' / S-1');
                        $row+=3;
                        $rpt->setXY(3,$row);			
                        $rpt->SetFont ('helvetica','B',8);	
                        $rpt->Cell(0,$row,'Penasihat Akademik');
                        $rpt->SetFont ('helvetica','',8);
                        $rpt->setXY(38,$row);			
                        $nama_dosen=$objDMaster->getNamaDosenWaliByID ($dataMhs['iddosen_wali']);
                        $rpt->Cell(0,$row,": $nama_dosen");				
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
                        $rpt->Cell(0,$row,": $nama_semester / $ta");				
                        $rpt->SetFont ('helvetica','B',8);	
                        $rpt->setXY(105,$row);			
                        $rpt->Cell(0,$row,'NIRM');
                        $rpt->SetFont ('helvetica','',8);
                        $rpt->setXY(130,$row);			
                        $rpt->Cell(0,$row,': '.$dataMhs['nirm']);			

                        $row+=20;
                        $rpt->SetFont ('helvetica','B',8);
                        $rpt->setXY(3,$row);			
                        $rpt->Cell(13, 5, 'NO', 1, 0, 'C');				
                        $rpt->Cell(15, 5, 'KODE', 1, 0, 'C');								
                        $rpt->Cell(80, 5, 'MATAKULIAH', 1, 0, 'C');							
                        $rpt->Cell(10, 5, 'HM', 1, 0, 'C');				
                        $rpt->Cell(10, 5, 'SKS', 1, 0, 'C');				
                        $rpt->Cell(10, 5, 'NM', 1, 0, 'C');						
                        $rpt->Cell(47, 5, 'KETERANGAN', 1, 0, 'C');
                        $objNilai->setDataMHS(array('nim'=>$nim));
                        $dn=$objNilai->getKHS($ta,$semester);				
                        $totalSks=0;
                        $totalNm=0;
                        $row+=5;
                        $rpt->setXY(3,$row);			
                        $rpt->SetFont ('helvetica','',8);
                        while (list($k,$v)=each($dn)) {
                            $rpt->setXY(3,$row);			
                            $rpt->Cell(13, 5, $v['no'], 1, 0, 'C');							
                            $rpt->Cell(15, 5, $v['kmatkul'], 1, 0, 'C');		
                            $rpt->Cell(80, 5, $v['nmatkul'], 1, 0, 'L');				
                            $n_kual=$v['n_kual']==''?'-':$v['n_kual'];
                            $sks=$v['sks'];
                            $m=$v['m'];										
                            $rpt->Cell(10, 5, $n_kual, 1, 0, 'C');					
                            $rpt->Cell(10, 5, $sks, 1, 0, 'C');					
                            $rpt->Cell(10, 5, $m, 1, 0, 'C');					
                            $rpt->Cell(47, 5, '', 1, 0, 'C');													
                            $totalSks+=$sks;				
                            $totalNm+=$m;					
                            $row+=5;
                        }
                        $rpt->setXY(31,$row);			
                        $rpt->Cell(90, 5, 'Jumlah Kredit',0);											
                        $rpt->Cell(10, 5, $totalSks, 1, 0, 'C');			
                        $rpt->Cell(10, 5, $totalNm, 1, 0, 'C');			
                        $ip=@ bcdiv($totalNm,$totalSks,2);
                        $rpt->Cell(47, 5, "IPS : $ip", 1, 0, 'C');

                        $row+=5;
                        $nilaisemesterlalu=$objNilai->getKumulatifSksDanNmSemesterLalu($ta,$semester);				
                        $rpt->setXY(31,$row);			
                        $rpt->Cell(90, 5, 'Jumlah Kumulatif Semester Lalu (JKSL)');												
                        $rpt->Cell(10, 5, $nilaisemesterlalu['total_sks'], 1, 0, 'C');				
                        $rpt->Cell(10, 5, $nilaisemesterlalu['total_nm'], 1, 0, 'C');	
                        $rpt->Cell(47, 5, '', 1, 0, 'C');


                        $nilaisemestersekarang=$objNilai->getIPKSampaiTASemester($ta,$semester,'ipksksnm');
                        $row+=5;
                        $rpt->setXY(31,$row);			
                        $rpt->Cell(90, 5, 'Jumlah Kumulatif Semester Ini (JKSI)');												

                        $rpt->Cell(10, 5, $nilaisemestersekarang['sks'], 1, 0, 'C');
                        $rpt->Cell(10, 5, $nilaisemestersekarang['nm'], 1, 0, 'C');

                        $ipk=$nilaisemestersekarang['ipk'];                
                        $rpt->Cell(47, 5, "IPK : $ipk", 1, 0, 'C');

                        $row+=5;
                        $nextsemeserandta=$objNilai->getNextSemesterAndTa ($ta,$semester);							
                        $rpt->setXY(31,$row);			
                        $rpt->Cell(90, 5, 'Jumlah Maksimum Kredit yang dapat diambil');												
                        $rpt->Cell(67, 8, $this->setup->getSksNextSemester($ip), 1, 0, 'C');

                        $row+=5;
                        $rpt->setXY(31,$row);										
                        $rpt->Cell(90, 5, 'Pada semester '.$nextsemeserandta['semester'].' Tahun Akademik '.$nextsemeserandta['ta']);								

                        if ($withsignature) {
                            $row+=5;
                            $rpt->SetFont ('helvetica','B',8);
                            $rpt->setXY(31,$row);			
                            $rpt->Cell(60, 5, 'Mengetahui',0,0,'C');				

                            $tanggal=$this->tgl->tanggal('l, j F Y');				
                            $rpt->Cell(80, 5, $this->setup->getSettingValue('kota_pt').", $tanggal",0,0,'C');		

                            $row+=5;
                            $rpt->setXY(31,$row);			
                            $rpt->Cell(60, 5, 'A.n. Ketua '.$this->dataReport['nama_pt_alias'],0,0,'C');			
                            $rpt->Cell(80, 5, "Ketua Program Studi",0,0,'C');		

                            $row+=5;
                            $rpt->setXY(31,$row);			
                            $rpt->Cell(60, 5, $this->dataReport['nama_jabatan_khs'],0,0,'C');				

                            $nama_ps=$this->dataReport['nama_ps'];			
                            $rpt->Cell(80, 5, $nama_ps,0,0,'C');		

                            $row+=20;                    
                            $rpt->setXY(31,$row);			                    
                            $rpt->Cell(60, 5,$this->dataReport['nama_penandatangan_khs'],0,0,'C');

                            $rpt->Cell(80, 5,$this->dataReport['nama_kaprodi'],0,0,'C');

                            $row+=5;
                            $rpt->setXY(31,$row);			
                            $nama_jabatan=strtoupper($this->dataReport['jabfung_penandatangan_khs']);
                            $nidn=$this->dataReport['nidn_penandatangan_khs'];
                            $rpt->Cell(60, 5, "$nama_jabatan NIDN : $nidn",0,0,'C');
                            $rpt->Cell(80, 5, strtoupper($this->dataReport['jabfung_kaprodi']). ' NIDN : '.$this->dataReport['nidn_kaprodi'],0,0,'C');
                        }
                    }                    
                }
                $this->printOut('seluruh_khs_dari_'.$awal.'_'.$akhir);
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Kartu Hasil Studi T.A $nama_tahun Semester $nama_semester");
    }
    /**
     * digunakan untuk memprint KHS
     * @param type $objNilai object
     * @param type $objDMaster object
     */
    public function printSummaryKHS ($objNilai,$objDMaster,$withsignature=false) {
        $ta=$this->dataReport['ta'];
        $tahun_masuk=$this->dataReport['tahun_masuk'];
        $semester=$this->dataReport['semester'];
        $kjur=$this->dataReport['kjur'];
        $nama_tahun=$this->dataReport['nama_tahun'];
        $nama_semester=$this->dataReport['nama_semester'];
        $nama_ps = $this->dataReport['nama_ps'];
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :          
                $this->setHeaderPT('L'); 
                $sheet=$this->rpt->getActiveSheet();
                $this->rpt->getDefaultStyle()->getFont()->setName('Arial');                
                $this->rpt->getDefaultStyle()->getFont()->setSize('9');                                    
                
                $sheet->mergeCells("A7:L7");
                $sheet->getRowDimension(7)->setRowHeight(20);
                $sheet->setCellValue("A7","SUMMARY KHS T.A $nama_tahun SEMESTER $nama_semester");                                
                
                $sheet->mergeCells("A8:L8");
                $sheet->setCellValue("A8","PROGRAM STUDI $nama_ps");                                
                $sheet->getRowDimension(8)->setRowHeight(20);
                $styleArray=array(
								'font' => array('bold' => true,
                                                'size' => 16),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
							);
                $sheet->getStyle("A7:L8")->applyFromArray($styleArray);
                
                $sheet->getRowDimension(10)->setRowHeight(25);              
                
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(35);
                $sheet->getColumnDimension('E')->setWidth(10);
                $sheet->getColumnDimension('I')->setWidth(10);
                $sheet->getColumnDimension('G')->setWidth(10);
                $sheet->getColumnDimension('H')->setWidth(10);
                $sheet->getColumnDimension('I')->setWidth(15);
                $sheet->getColumnDimension('J')->setWidth(15);
                $sheet->getColumnDimension('K')->setWidth(14);
                $sheet->getColumnDimension('L')->setWidth(18);
                                
                $sheet->setCellValue('A10','NO');				
                $sheet->setCellValue('B10','NIM');
                $sheet->setCellValue('C10','NIRM');				                        
                $sheet->setCellValue('D10','NAMA');				
                $sheet->setCellValue('E10','JK');				
                $sheet->setCellValue('F10','ANGK.');				
                $sheet->setCellValue('G10','IPS');				
                $sheet->setCellValue('H10','IPK');				
                $sheet->setCellValue('I10','SKS SEMESTER');				
                $sheet->setCellValue('J10','SKS TOTAL');	
                $sheet->setCellValue('K10','SKS KONVERSI DI AKUI');
                $sheet->setCellValue('L10','KELAS');
                
                $styleArray=array(								
                                    'font' => array('bold' => true),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                       'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                                );																					 
                $sheet->getStyle("A10:L10")->applyFromArray($styleArray);
                $sheet->getStyle("A10:L10")->getAlignment()->setWrapText(true);
                
                $str_tahun_masuk=$tahun_masuk == 'none' ?'':"AND vdm.tahun_masuk=$tahun_masuk";
                $str = "SELECT k.idkrs,k.tgl_krs,k.nim,nirm,vdm.nama_mhs,vdm.jk,vdm.kjur,vdm.idkelas,vdm.tahun_masuk,vdm.semester_masuk,dk.iddata_konversi FROM krs k JOIN v_datamhs vdm ON (k.nim=vdm.nim) LEFT JOIN data_konversi dk ON (dk.nim=vdm.nim) WHERE tahun='$ta' AND idsmt='$semester' AND kjur=$kjur AND k.sah=1 $str_tahun_masuk ORDER BY vdm.tahun_masuk ASC,vdm.idkelas ASC,vdm.nama_mhs ASC";
                $this->db->setFieldTable(array('idkrs','tgl_krs','nim','nirm','nama_mhs','jk','kjur','idkelas','tahun_masuk','semester_masuk','iddata_konversi'));
                $r=$this->db->getRecord($str);
                $row=11;                
                while (list($k,$v)=each($r)) {
                    $nim=$v['nim'];						
                    $objNilai->setDataMHS(array('nim'=>$nim));
                    $objNilai->getKHS($_SESSION['ta'],$_SESSION['semester']);
                    $ip=$objNilai->getIPS ();
                    $sks=$objNilai->getTotalSKS ();                
                    $dataipk=$objNilai->getIPKSampaiTASemester($ta,$semester,'ipksks');	                
                
                    $sheet->setCellValue("A$row",$v['no']);				                    
                    $sheet->setCellValueExplicit("B$row",$v['nim'],PHPExcel_Cell_DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit("C$row",$v['nirm'],PHPExcel_Cell_DataType::TYPE_STRING);	                        
                    $sheet->setCellValue("D$row",$v['nama_mhs']);				
                    $sheet->setCellValue("E$row",$v['jk']);				
                    $sheet->setCellValue("F$row",$v['tahun_masuk']);				
                    $sheet->setCellValue("G$row",$ip);				
                    $sheet->setCellValue("H$row",$dataipk['ipk']);				
                    $sheet->setCellValue("I$row",$sks);				
                    $sheet->setCellValue("J$row",$dataipk['sks']);
                    $iddata_konversi = $v['iddata_konversi'];
                    $jumlah_sks=0;
                    if ($iddata_konversi > 0) {
                        $jumlah_sks=$this->db->getSumRowsOfTable ('sks',"v_konversi2 WHERE iddata_konversi=$iddata_konversi");
                    }
                    $sheet->setCellValue("K$row",$jumlah_sks);
                    $sheet->setCellValue("L$row",$objDMaster->getNamaKelasByID($v['idkelas']));
                    $row+=1;
                }
                $row-=1;
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                                       'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                                );																					 
                $sheet->getStyle("A11:L$row")->applyFromArray($styleArray);
                $sheet->getStyle("A11:L$row")->getAlignment()->setWrapText(true);
                
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                );
                
                $sheet->getStyle("A11:C$row")->applyFromArray($styleArray);
                $sheet->getStyle("E11:L$row")->applyFromArray($styleArray);
                
                if ($withsignature) {
                    $row+=3;
                    $row_awal=$row;
                    $sheet->mergeCells("C$row:D$row");                    
                    $sheet->setCellValue("C$row",'Mengetahui');				                    
                    
                    $sheet->mergeCells("F$row:I$row");       
                    $tanggal=$this->tgl->tanggal('l, j F Y');		
                    $sheet->setCellValue("F$row",$this->setup->getSettingValue('kota_pt').", $tanggal");				                    
                    
                    $row+=1;
                    $sheet->mergeCells("C$row:D$row");      
                    $sheet->setCellValue("C$row",'A.n. Ketua '.$this->dataReport['nama_pt_alias']);				                    
                    $sheet->mergeCells("F$row:I$row");                           
                    $sheet->setCellValue("F$row",'Ketua Program Studi');				                    
                    
                    $row+=1;
                    $sheet->mergeCells("C$row:D$row");      
                    $sheet->setCellValue("C$row",$this->dataReport['nama_jabatan_khs']);				                    
                    $sheet->mergeCells("F$row:I$row");                           
                    $sheet->setCellValue("F$row",$nama_ps);
                    
                    $row+=5;
                    $sheet->mergeCells("C$row:D$row");                    
                    $sheet->setCellValue("C$row",$this->dataReport['nama_penandatangan_khs']);
                    $sheet->mergeCells("F$row:I$row");                           
                    $sheet->setCellValue("F$row",$this->dataReport['nama_kaprodi']);
                    
                    $row+=1;
                    $sheet->mergeCells("C$row:D$row");                    
                    $nama_jabatan=strtoupper($this->dataReport['jabfung_penandatangan_khs']);
                    $nidn=$this->dataReport['nidn_penandatangan_khs'];
                    $sheet->setCellValue("C$row","$nama_jabatan NIDN : $nidn");
                    $sheet->mergeCells("F$row:I$row");                           
                    $sheet->setCellValue("F$row",strtoupper($this->dataReport['jabfung_kaprodi']). ' NIDN : '.$this->dataReport['nidn_kaprodi']);
                    
                    $styleArray=array(								
                                    'font' => array('bold' => true),                                    
                                );																					 
                    $sheet->getStyle("A$row_awal:L$row")->applyFromArray($styleArray);
                    $sheet->getStyle("A$row_awal:L$row")->getAlignment()->setWrapText(true);
                }
                
                $this->printOut("summarykhs");
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Summary KHS T.A $nama_tahun Semester $nama_semester");
    }
    /**
     * digunakan untuk memprint Transkrip Kurikulum
     * @param type $objNilai object
     */
    public function printTranskripKurikulum ($objNilai,$withsignature=false) {
        $biodata=$this->dataReport;          
        $nim=$biodata['nim'];
        $objNilai->setDataMHS($biodata);
        $smt=Logic_Akademik::$SemesterMatakuliahRomawi;
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :                
//                $this->printOut("khs_$nim");
            break;
            case 'pdf' :
                $rpt=$this->rpt;
                $rpt->setTitle('Transkriprip Nilai Kurikulum');
				$rpt->setSubject('Transkrip Nilai Kurikulum');
                $rpt->AddPage();
				$this->setHeaderPT();
                
                $row=$this->currentRow;
				$row+=6;
                $rpt->SetFont ('helvetica','B',12);	
				$rpt->setXY(3,$row);			
				$rpt->Cell(0,$row,'TRANSKRIP NILAI KURIKULUM',0,0,'C');
                $row+=6;
                $rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(3,$row);			
				$rpt->Cell(0,$row,'Nama Mahasiswa');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nama_mhs']);
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'Program Studi');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nama_ps']);
				$row+=3;
				$rpt->setXY(3,$row);			
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->Cell(0,$row,'Tempat, tanggal lahir');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$biodata['tempat_lahir'].', '.$this->tgl->tanggal('j F Y',$biodata['tanggal_lahir']));				
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'Konsentrasi');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nama_konsentrasi']);
				$row+=3;
				$rpt->setXY(3,$row);			
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->Cell(0,$row,'NIM');				
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nim']);				
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'Jenjang');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': Starta 1 (satu)');		
				$row+=3;
				$rpt->setXY(3,$row);			
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->Cell(0,$row,'NIRM');				
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nirm']);				
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
                $rpt->Cell(0,$row,'Angk. Th. Akad');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': '.$biodata['tahun_masuk']);			
				
				//field of column ganjil
				$row+=20;
				$rpt->setXY(3,$row);			
				$rpt->Cell(7,8,'SMT',1,0,'C');				
				$rpt->Cell(6,8,'NO',1,0,'C');
				$rpt->Cell(11,4,'KODE',1,0,'C');
				$rpt->Cell(54,8,'MATA KULIAH',1,0,'C');
				$rpt->Cell(8,8,'SKS',1,0,'C');
				$rpt->Cell(8,8,'NM',1,0,'C');
				$rpt->Cell(8,8,'AM',1,0,'C');
				$rpt->Cell(1,8,'');				
				//field of column genap
				$rpt->Cell(7,8,'SMT',1,0,'C');		
				$rpt->Cell(6,8,'NO',1,0,'C');
				$rpt->Cell(11,4,'KODE',1,0,'C');
				$rpt->Cell(54,8,'MATA KULIAH',1,0,'C');
				$rpt->Cell(8,8,'SKS',1,0,'C');
				$rpt->Cell(8,8,'NM',1,0,'C');
				$rpt->Cell(8,8,'AM',1,0,'C');
				$row+=4;
				$rpt->setXY(16,$row);
				$rpt->Cell(11,4,'MK',1,0,'C');
				$rpt->setXY(119,$row);
				$rpt->Cell(11,4,'MK',1,0,'C');
				
				$n=$objNilai->getTranskripNilaiKurikulum($this->dataReport['cek_isikuesioner']);
				$totalSks=0;
				$totalM=0;
				$row+=4;
				$row_ganjil=$row;
				$row_genap = $row;
				$rpt->setXY(3,$row);	
				$rpt->SetFont ('helvetica','',6);
				$tambah_ganjil_row=false;		
				$tambah_genap_row=false;		
                for ($i = 1; $i <= 8; $i++) {
					$no_semester=1;
					if ($i%2==0) {//genap
						$tambah_genap_row=true;
						$genap_total_m=0;
						$genap_total_sks=0;		
						foreach ($n as $v) {	
							if ($v['semester']==$i) {
								$n_kual=$v['n_kual'];
								$sks=$v['sks'];
								$m=($n_kual=='-')?'-':$v['m'];
								$rpt->setXY(106,$row_genap);	
								$rpt->Cell(7,4,$smt[$i],1,0,'C');	
								$rpt->Cell(6,4,$no_semester,1,0,'C');	
								$rpt->Cell(11,4,$objNilai->getKMatkul($v['kmatkul']),1,0,'C');
								$rpt->Cell(54,4,$v['nmatkul'],1,0,'L');		
								$rpt->Cell(8,4,$sks,1,0,'C');
								$rpt->Cell(8,4,$n_kual,1,0,'C');
								$rpt->Cell(8,4,$m,1,0,'C');
								$genap_total_sks += $sks;
								if ($n_kual!='-') {									
									$genap_total_m += $m;
									$totalSks+=$sks;
									$totalM+=$m;									
								}
								$row_genap+=4;
								$no_semester++;
							}
						}
						$ipk_genap=@ bcdiv($totalM,$totalSks,2);
						$ipk_genap=$ipk_genap==''?'0.00':$ipk_genap;
					}else {//ganjil
						$tambah_ganjil_row=true;
						$ganjil_total_m=0;
						$ganjil_total_sks=0;
						foreach ($n as $s) {
							if ($s['semester']==$i) {
								$n_kual=$s['n_kual'];
								$sks=$s['sks'];
								$m=($n_kual=='-')?'-':$s['m']; 								
								$rpt->setXY(3,$row_ganjil);	
								$rpt->Cell(7,4,$smt[$i],1,0,'C');	
								$rpt->Cell(6,4,$no_semester,1,0,'C');	
								$rpt->Cell(11,4,$objNilai->getKMatkul($s['kmatkul']),1,0,'C');
								$rpt->Cell(54,4,$s['nmatkul'],1,0,'L');		
								$rpt->Cell(8,4,$sks,1,0,'C');
								$rpt->Cell(8,4,$n_kual,1,0,'C');
								$rpt->Cell(8,4,$m,1,0,'C');								
								$ganjil_total_sks += $sks;
								if ($n_kual != '-') {									
									$ganjil_total_m += $m;									
									$totalSks+=$sks;
									$totalM+=$m;									
								}
								$row_ganjil+=4;
								$no_semester++;
							}
						}
						$ipk_ganjil=@ bcdiv($totalM,$totalSks,2);
						$ipk_ganjil=$ipk_ganjil==''?'0.00':$ipk_ganjil;
					}
					if ($tambah_ganjil_row && $tambah_genap_row) {	
						$tambah_ganjil_row=false;
						$tambah_genap_row=false;						
						if ($row_ganjil < $row_genap){ // berarti tambah row yang ganjil
							$sisa=$row_ganjil + ($row_genap-$row_ganjil);
							for ($c=$row_ganjil;$c <= $row_genap;$c+=4) {
								$rpt->setXY(3,$c);
								$rpt->Cell(102,4,'',1,0);
							}
							$row_ganjil=$sisa;
						}else{ // berarti tambah row yang genap
							$sisa=$row_genap + ($row_ganjil-$row_genap);						
							for ($c=$row_genap;$c < $row_ganjil;$c+=4) {
								$rpt->setXY(106,$c);
								$rpt->Cell(102,4,'',1,0);
							}
							$row_genap=$sisa;
						}		
						//ganjil
						$rpt->setXY(16,$row_ganjil);	
						$rpt->Cell(65,4,'Jumlah',1,0,'L');						
						$rpt->Cell(8,4,$ganjil_total_sks,1,0,'C');
						$rpt->Cell(8,4,'',1,0,'L');						
						$rpt->Cell(8,4,$ganjil_total_m,1,0,'C');
						$row_ganjil+=4;
						$rpt->setXY(16,$row_ganjil);	
						$rpt->Cell(81,4,'Indeks Prestasi Semester',1,0,'L');
						$ip=@ bcdiv($ganjil_total_m,$ganjil_total_sks,2);												
						$rpt->Cell(8,4,$ip,1,0,'C');
						$row_ganjil+=4;
						$rpt->setXY(16,$row_ganjil);	
						$rpt->Cell(81,4,'Indeks Prestasi Kumulatif',1,0,'L');		
						$ipk_ganjil=$ip == '0.00'?'0.00':$ipk_ganjil;
						$rpt->Cell(8,4,$ipk_ganjil,1,0,'C');
						$row_ganjil+=4;	
						$rpt->setXY(16,$row_ganjil);
						$rpt->Cell(8,4,' ',0,0,'C');						
						$row_ganjil+=1;		
						//genap			
						$rpt->setXY(119,$row_genap);	
						$rpt->Cell(65,4,'Jumlah',1,0,'L');						
						$rpt->Cell(8,4,$genap_total_sks,1,0,'C');
						$rpt->Cell(8,4,'',1,0,'L');
						$rpt->Cell(8,4,$genap_total_m,1,0,'C');
						$row_genap+=4;
						$rpt->setXY(119,$row_genap);	
						$rpt->Cell(81,4,'Indeks Prestasi Semester',1,0,'L');
						$ip=@ bcdiv($genap_total_m,$genap_total_sks,2);									
						$rpt->Cell(8,4,$ip,1,0,'C');
						$row_genap+=4;
						$rpt->setXY(119,$row_genap);	
						$rpt->Cell(81,4,'Indeks Prestasi Kumulatif',1,0,'L');								
						$ipk_genap=$ip == '0.00'?'0.00':$ipk_genap;
						$rpt->Cell(8,4,$ipk_genap,1,0,'C');
						$row_genap+=4;
						$rpt->setXY(119,$row_genap);	
						$rpt->Cell(8,4,' ',0,0,'C');
						$row_genap+=1;						
					}
				}
                $rpt->SetFont ('helvetica','B',6);
				$row=$row_genap+4;
				$rpt->setXY(105,$row);	
				$rpt->Cell(65,4,'Total Kredit Kumulatif',0,0,'L');
				$rpt->Cell(8,4,$totalSks,0,0,'C');
				$row+=4;
				$rpt->setXY(105,$row);	
				$rpt->Cell(65,4,'Jumlah Nilai Kumulatif',0,0,'L');
				$rpt->Cell(8,4,$totalM,0,0,'C');																
				$row+=4;
				$rpt->setXY(105,$row);	
				$rpt->Cell(65,4,'Indeks Prestasi Kumulatif',0,0,'L');
				$ipk=@ bcdiv($totalM,$totalSks,2);
				$ipk=$ipk==''?'0.00':$ipk;
				$rpt->Cell(8,4,$ipk,0,0,'C');																
                if ($withsignature) {
                    $row+=8;
                    $rpt->setXY(105,$row);	
                    $rpt->Cell(65,4,$this->setup->getSettingValue('kota_pt').', '.$this->tgl->tanggal('j F Y'),0,0,'L');
                    $row+=4;
                    $rpt->setXY(105,$row);	
                    $rpt->Cell(65,4,$this->dataReport['nama_jabatan_transkrip'],0,0,'L');                    
                    $row+=14;				
                    $rpt->setXY(105,$row);	
                    $rpt->Cell(65,4,$this->dataReport['nama_penandatangan_transkrip'],0,0,'L');
                    $row+=4;				
                    $rpt->setXY(105,$row);	
                    $rpt->Cell(65,4,strtoupper($this->dataReport['jabfung_penandatangan_transkrip']). ' NIDN '.$this->dataReport['nidn_penandatangan_transkrip'],0,0,'L');
                }
                $this->printOut("transkripkurikulum_$nim");
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Transkrip Kurikulum");
    }
    /**
     * digunakan untuk memprint Transkrip Kurikulum All
     * @param type $objNilai object
     */
    public function printTranskripKurikulumAll ($objNilai,$withsignature=false,$outputcompress,$level=0) {
        $biodata=$this->dataReport;          
        $nim=$biodata['nim'];
        $objNilai->setDataMHS($biodata);
        $smt=Logic_Akademik::$SemesterMatakuliahRomawi;
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :                
//                $this->printOut("khs_$nim");
            break;
            case 'pdf' :
                $rpt=$this->rpt;
                $rpt->setTitle('Transkriprip Nilai Kurikulum');
				$rpt->setSubject('Transkriprip Nilai Kurikulum');
                $rpt->AddPage();
				$this->setHeaderPT();
                
                $row=$this->currentRow;
				$row+=6;
                $rpt->SetFont ('helvetica','B',12);	
				$rpt->setXY(3,$row);			
				$rpt->Cell(0,$row,'TRANSKRIP NILAI KURIKULUM',0,0,'C');
                $row+=6;
                $rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(3,$row);			
				$rpt->Cell(0,$row,'Nama Mahasiswa');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nama_mhs']);
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'Program Studi');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nama_ps']);
				$row+=3;
				$rpt->setXY(3,$row);			
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->Cell(0,$row,'Tempat, tanggal lahir');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$biodata['tempat_lahir'].', '.$this->tgl->tanggal('j F Y',$biodata['tanggal_lahir']));				
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'Konsentrasi');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nama_konsentrasi']);
				$row+=3;
				$rpt->setXY(3,$row);			
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->Cell(0,$row,'NIM');				
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nim']);				
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'Jenjang');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': Starta 1 (satu)');		
				$row+=3;
				$rpt->setXY(3,$row);			
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->Cell(0,$row,'NIRM');				
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nirm']);				
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
                $rpt->Cell(0,$row,'Angk. Th. Akad');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': '.$biodata['tahun_masuk']);			
				
				//field of column ganjil
				$row+=20;
				$rpt->setXY(3,$row);			
				$rpt->Cell(7,8,'SMT',1,0,'C');				
				$rpt->Cell(6,8,'NO',1,0,'C');
				$rpt->Cell(11,4,'KODE',1,0,'C');
				$rpt->Cell(54,8,'MATA KULIAH',1,0,'C');
				$rpt->Cell(8,8,'SKS',1,0,'C');
				$rpt->Cell(8,8,'NM',1,0,'C');
				$rpt->Cell(8,8,'AM',1,0,'C');
				$rpt->Cell(1,8,'');				
				//field of column genap
				$rpt->Cell(7,8,'SMT',1,0,'C');		
				$rpt->Cell(6,8,'NO',1,0,'C');
				$rpt->Cell(11,4,'KODE',1,0,'C');
				$rpt->Cell(54,8,'MATA KULIAH',1,0,'C');
				$rpt->Cell(8,8,'SKS',1,0,'C');
				$rpt->Cell(8,8,'NM',1,0,'C');
				$rpt->Cell(8,8,'AM',1,0,'C');
				$row+=4;
				$rpt->setXY(16,$row);
				$rpt->Cell(11,4,'MK',1,0,'C');
				$rpt->setXY(119,$row);
				$rpt->Cell(11,4,'MK',1,0,'C');
				
				$n=$objNilai->getTranskripNilaiKurikulum();
				$totalSks=0;
				$totalM=0;
				$row+=4;
				$row_ganjil=$row;
				$row_genap = $row;
				$rpt->setXY(3,$row);	
				$rpt->SetFont ('helvetica','',6);
				$tambah_ganjil_row=false;		
				$tambah_genap_row=false;		
                for ($i = 1; $i <= 8; $i++) {
					$no_semester=1;
					if ($i%2==0) {//genap
						$tambah_genap_row=true;
						$genap_total_m=0;
						$genap_total_sks=0;		
						foreach ($n as $v) {	
							if ($v['semester']==$i) {
								$n_kual=$v['n_kual'];
								$sks=$v['sks'];
								$m=($n_kual=='-')?'-':$v['m'];
								$rpt->setXY(106,$row_genap);	
								$rpt->Cell(7,4,$smt[$i],1,0,'C');	
								$rpt->Cell(6,4,$no_semester,1,0,'C');	
								$rpt->Cell(11,4,$objNilai->getKMatkul($v['kmatkul']),1,0,'C');
								$rpt->Cell(54,4,$v['nmatkul'],1,0,'L');		
								$rpt->Cell(8,4,$sks,1,0,'C');
								$rpt->Cell(8,4,$n_kual,1,0,'C');
								$rpt->Cell(8,4,$m,1,0,'C');
								$genap_total_sks += $sks;
								if ($n_kual!='-') {									
									$genap_total_m += $m;
									$totalSks+=$sks;
									$totalM+=$m;									
								}
								$row_genap+=4;
								$no_semester++;
							}
						}
						$ipk_genap=@ bcdiv($totalM,$totalSks,2);
						$ipk_genap=$ipk_genap==''?'0.00':$ipk_genap;
					}else {//ganjil
						$tambah_ganjil_row=true;
						$ganjil_total_m=0;
						$ganjil_total_sks=0;
						foreach ($n as $s) {
							if ($s['semester']==$i) {
								$n_kual=$s['n_kual'];
								$sks=$s['sks'];
								$m=($n_kual=='-')?'-':$s['m']; 								
								$rpt->setXY(3,$row_ganjil);	
								$rpt->Cell(7,4,$smt[$i],1,0,'C');	
								$rpt->Cell(6,4,$no_semester,1,0,'C');	
								$rpt->Cell(11,4,$objNilai->getKMatkul($s['kmatkul']),1,0,'C');
								$rpt->Cell(54,4,$s['nmatkul'],1,0,'L');		
								$rpt->Cell(8,4,$sks,1,0,'C');
								$rpt->Cell(8,4,$n_kual,1,0,'C');
								$rpt->Cell(8,4,$m,1,0,'C');								
								$ganjil_total_sks += $sks;
								if ($n_kual != '-') {									
									$ganjil_total_m += $m;									
									$totalSks+=$sks;
									$totalM+=$m;									
								}
								$row_ganjil+=4;
								$no_semester++;
							}
						}
						$ipk_ganjil=@ bcdiv($totalM,$totalSks,2);
						$ipk_ganjil=$ipk_ganjil==''?'0.00':$ipk_ganjil;
					}
					if ($tambah_ganjil_row && $tambah_genap_row) {	
						$tambah_ganjil_row=false;
						$tambah_genap_row=false;						
						if ($row_ganjil < $row_genap){ // berarti tambah row yang ganjil
							$sisa=$row_ganjil + ($row_genap-$row_ganjil);
							for ($c=$row_ganjil;$c <= $row_genap;$c+=4) {
								$rpt->setXY(3,$c);
								$rpt->Cell(102,4,'',1,0);
							}
							$row_ganjil=$sisa;
						}else{ // berarti tambah row yang genap
							$sisa=$row_genap + ($row_ganjil-$row_genap);						
							for ($c=$row_genap;$c < $row_ganjil;$c+=4) {
								$rpt->setXY(106,$c);
								$rpt->Cell(102,4,'',1,0);
							}
							$row_genap=$sisa;
						}		
						//ganjil
						$rpt->setXY(16,$row_ganjil);	
						$rpt->Cell(65,4,'Jumlah',1,0,'L');						
						$rpt->Cell(8,4,$ganjil_total_sks,1,0,'C');
						$rpt->Cell(8,4,'',1,0,'L');						
						$rpt->Cell(8,4,$ganjil_total_m,1,0,'C');
						$row_ganjil+=4;
						$rpt->setXY(16,$row_ganjil);	
						$rpt->Cell(81,4,'Indeks Prestasi Semester',1,0,'L');
						$ip=@ bcdiv($ganjil_total_m,$ganjil_total_sks,2);												
						$rpt->Cell(8,4,$ip,1,0,'C');
						$row_ganjil+=4;
						$rpt->setXY(16,$row_ganjil);	
						$rpt->Cell(81,4,'Indeks Prestasi Kumulatif',1,0,'L');		
						$ipk_ganjil=$ip == '0.00'?'0.00':$ipk_ganjil;
						$rpt->Cell(8,4,$ipk_ganjil,1,0,'C');
						$row_ganjil+=4;	
						$rpt->setXY(16,$row_ganjil);
						$rpt->Cell(8,4,' ',0,0,'C');						
						$row_ganjil+=1;		
						//genap			
						$rpt->setXY(119,$row_genap);	
						$rpt->Cell(65,4,'Jumlah',1,0,'L');						
						$rpt->Cell(8,4,$genap_total_sks,1,0,'C');
						$rpt->Cell(8,4,'',1,0,'L');
						$rpt->Cell(8,4,$genap_total_m,1,0,'C');
						$row_genap+=4;
						$rpt->setXY(119,$row_genap);	
						$rpt->Cell(81,4,'Indeks Prestasi Semester',1,0,'L');
						$ip=@ bcdiv($genap_total_m,$genap_total_sks,2);									
						$rpt->Cell(8,4,$ip,1,0,'C');
						$row_genap+=4;
						$rpt->setXY(119,$row_genap);	
						$rpt->Cell(81,4,'Indeks Prestasi Kumulatif',1,0,'L');								
						$ipk_genap=$ip == '0.00'?'0.00':$ipk_genap;
						$rpt->Cell(8,4,$ipk_genap,1,0,'C');
						$row_genap+=4;
						$rpt->setXY(119,$row_genap);	
						$rpt->Cell(8,4,' ',0,0,'C');
						$row_genap+=1;						
					}
				}
                $rpt->SetFont ('helvetica','B',6);
				$row=$row_genap+4;
				$rpt->setXY(105,$row);	
				$rpt->Cell(65,4,'Total Kredit Kumulatif',0,0,'L');
				$rpt->Cell(8,4,$totalSks,0,0,'C');
				$row+=4;
				$rpt->setXY(105,$row);	
				$rpt->Cell(65,4,'Jumlah Nilai Kumulatif',0,0,'L');
				$rpt->Cell(8,4,$totalM,0,0,'C');																
				$row+=4;
				$rpt->setXY(105,$row);	
				$rpt->Cell(65,4,'Indeks Prestasi Kumulatif',0,0,'L');
				$ipk=@ bcdiv($totalM,$totalSks,2);
				$ipk=$ipk==''?'0.00':$ipk;
				$rpt->Cell(8,4,$ipk,0,0,'C');																
                if ($withsignature) {
                    $row+=8;
                    $rpt->setXY(105,$row);	
                    $rpt->Cell(65,4,$this->setup->getSettingValue('kota_pt').', '.$this->tgl->tanggal('j F Y'),0,0,'L');
                    $row+=4;
                    $rpt->setXY(105,$row);	
                    $rpt->Cell(65,4,$this->dataReport['nama_jabatan_transkrip'],0,0,'L');                    
                    $row+=14;				
                    $rpt->setXY(105,$row);	
                    $rpt->Cell(65,4,$this->dataReport['nama_penandatangan_transkrip'],0,0,'L');
                    $row+=4;				
                    $rpt->setXY(105,$row);	
                    $rpt->Cell(65,4,strtoupper($this->dataReport['jabfung_penandatangan_transkrip']). ' NIDN '.$this->dataReport['nidn_penandatangan_transkrip'],0,0,'L');
                }
                $this->printOut("transkripsementara_$nim");
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Transkrip Kurikulum");
    }
    /**
     * digunakan untuk memprint Transkrip KRS
     * @param type $objNilai object
     */
    public function printTranskripKRS ($objNilai,$withsignature=false) {
        $biodata=$this->dataReport;          
        $nim=$biodata['nim'];
        $objNilai->setDataMHS($biodata);
        $smt=Logic_Akademik::$SemesterMatakuliahRomawi;
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :                
//                $this->printOut("khs_$nim");
            break;
            case 'pdf' :
                $rpt=$this->rpt;
                $rpt->setTitle('Transkriprip Nilai KRS');
				$rpt->setSubject('Transkriprip Nilai KRS');
                $rpt->AddPage();
				$this->setHeaderPT();
                
                $row=$this->currentRow;
				$row+=6;
                $rpt->SetFont ('helvetica','B',12);	
				$rpt->setXY(3,$row);			
				$rpt->Cell(0,$row,'TRANSKRIP NILAI KURIKULUM',0,0,'C');
                $row+=6;
                $rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(3,$row);			
				$rpt->Cell(0,$row,'Nama Mahasiswa');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nama_mhs']);
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'Program Studi');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nama_ps']);
				$row+=3;
				$rpt->setXY(3,$row);			
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->Cell(0,$row,'Tempat, tanggal lahir');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$biodata['tempat_lahir'].', '.$this->tgl->tanggal('j F Y',$biodata['tanggal_lahir']));				
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'Konsentrasi');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nama_konsentrasi']);
				$row+=3;
				$rpt->setXY(3,$row);			
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->Cell(0,$row,'NIM');				
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nim']);				
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
				$rpt->Cell(0,$row,'Jenjang');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': Starta 1 (satu)');		
				$row+=3;
				$rpt->setXY(3,$row);			
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->Cell(0,$row,'NIRM');				
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(38,$row);			
				$rpt->Cell(0,$row,': '.$biodata['nirm']);				
				$rpt->SetFont ('helvetica','B',8);	
				$rpt->setXY(105,$row);			
                $rpt->Cell(0,$row,'Angk. Th. Akad');
				$rpt->SetFont ('helvetica','',8);
				$rpt->setXY(130,$row);			
				$rpt->Cell(0,$row,': '.$biodata['tahun_masuk']);			
                
				$n=$objNilai->getTranskripFromKRS($this->dataReport['cek_isikuesioner']);
                $tampil_column_ganjil=false;
                $tampil_column_genap=false;
                for ($i = 1; $i <= 8; $i++) {
					$no_semester=1;                    
					if ($i%2==0) {//genap
                        foreach ($n as $v) {
                            if ($v['semester']==$i) {
                                $tampil_column_genap=true;
                                break;
                            }
                        }
                    }else{
                        foreach ($n as $s) {
                            if ($s['semester']==$i) {
                                $tampil_column_ganjil=true;
                                break;
                            }
                        }
                    }
                    if ($tampil_column_ganjil & $tampil_column_genap) {
                        break;
                    }
                }
				//field of column ganjil
				$row+=20;
                $rpt->setXY(3,$row);
                if ($tampil_column_ganjil) {                    			
                    $rpt->Cell(7,8,'SMT',1,0,'C');				
                    $rpt->Cell(6,8,'NO',1,0,'C');
                    $rpt->Cell(11,4,'KODE',1,0,'C');
                    $rpt->Cell(54,8,'MATA KULIAH',1,0,'C');
                    $rpt->Cell(8,8,'SKS',1,0,'C');
                    $rpt->Cell(8,8,'NM',1,0,'C');
                    $rpt->Cell(8,8,'AM',1,0,'C');
                    $rpt->Cell(1,8,'');				
                }else{
                    $rpt->setXY(106,$row);
                }
                if ($tampil_column_genap) {
                    //field of column genap
                    $rpt->Cell(7,8,'SMT',1,0,'C');		
                    $rpt->Cell(6,8,'NO',1,0,'C');
                    $rpt->Cell(11,4,'KODE',1,0,'C');
                    $rpt->Cell(54,8,'MATA KULIAH',1,0,'C');
                    $rpt->Cell(8,8,'SKS',1,0,'C');
                    $rpt->Cell(8,8,'NM',1,0,'C');
                    $rpt->Cell(8,8,'AM',1,0,'C');
                    $row+=4;
                    if ($tampil_column_ganjil) {
                        $rpt->setXY(16,$row);
                        $rpt->Cell(11,4,'MK',1,0,'C');
                    }
                    $rpt->setXY(119,$row);
                    $rpt->Cell(11,4,'MK',1,0,'C');
                }else {
                    $row+=4;
                    $rpt->setXY(16,$row);
                    $rpt->Cell(11,4,'MK',1,0,'C');
                }
				$totalSks=0;
				$totalM=0;
				$row+=4;
				$row_ganjil=$row;
				$row_genap = $row;
				$rpt->setXY(3,$row);	
				$rpt->SetFont ('helvetica','',6);
				$tambah_ganjil_row=false;		
				$tambah_genap_row=false;		
                $bool_khs_genap = false;
                $bool_khs_ganjil = false;
                for ($i = 1; $i <= 8; $i++) {
					$no_semester=1;                    
					if ($i%2==0) {//genap
						$tambah_genap_row=true;
						$genap_total_m=0;
						$genap_total_sks=0;		
                        $tampil_border_genap=false;
						foreach ($n as $v) {	
							if ($v['semester']==$i) {
								$n_kual=$v['n_kual'];
								$sks=$v['sks'];
								$m=($n_kual=='-')?'-':$v['m'];
								$rpt->setXY(106,$row_genap);	
								$rpt->Cell(7,4,$smt[$i],1,0,'C');	
								$rpt->Cell(6,4,$no_semester,1,0,'C');	
								$rpt->Cell(11,4,$objNilai->getKMatkul($v['kmatkul']),1,0,'C');
								$rpt->Cell(54,4,$v['nmatkul'],1,0,'L');		
								$rpt->Cell(8,4,$sks,1,0,'C');
								$rpt->Cell(8,4,$n_kual,1,0,'C');
								$rpt->Cell(8,4,$m,1,0,'C');
								$genap_total_sks += $sks;
								if ($n_kual!='-') {									
									$genap_total_m += $m;
									$totalSks+=$sks;
									$totalM+=$m;									
								}
								$row_genap+=4;
								$no_semester++;
                                $bool_khs_genap = true;
                                $tampil_border_genap=true;
							}
						}
						$ipk_genap=@ bcdiv($totalM,$totalSks,2);
						$ipk_genap=$ipk_genap==''?'0.00':$ipk_genap;
					}else {//ganjil
						$tambah_ganjil_row=true;
						$ganjil_total_m=0;
						$ganjil_total_sks=0;
                        $tampil_border_ganjil=false;
						foreach ($n as $s) {
							if ($s['semester']==$i) {                                
								$n_kual=$s['n_kual'];
								$sks=$s['sks'];
								$m=($n_kual=='-')?'-':$s['m']; 								
								$rpt->setXY(3,$row_ganjil);	
								$rpt->Cell(7,4,$smt[$i],1,0,'C');	
								$rpt->Cell(6,4,$no_semester,1,0,'C');	
								$rpt->Cell(11,4,$objNilai->getKMatkul($s['kmatkul']),1,0,'C');
								$rpt->Cell(54,4,$s['nmatkul'],1,0,'L');		
								$rpt->Cell(8,4,$sks,1,0,'C');
								$rpt->Cell(8,4,$n_kual,1,0,'C');
								$rpt->Cell(8,4,$m,1,0,'C');								
								$ganjil_total_sks += $sks;
								if ($n_kual != '-') {									
									$ganjil_total_m += $m;									
									$totalSks+=$sks;
									$totalM+=$m;									
								}
								$row_ganjil+=4;
								$no_semester++;
                                $bool_khs_ganjil = true;
                                $tampil_border_ganjil=true;
							}
						}
						$ipk_ganjil=@ bcdiv($totalM,$totalSks,2);
						$ipk_ganjil=$ipk_ganjil==''?'0.00':$ipk_ganjil;
					}
					if ($tambah_ganjil_row && $tambah_genap_row) {	
						$tambah_ganjil_row=false;
						$tambah_genap_row=false;						
						if ($row_ganjil < $row_genap){ // berarti tambah row yang ganjil
							$sisa=$row_ganjil + ($row_genap-$row_ganjil);
                            if ($tampil_border_ganjil){
                                for ($c=$row_ganjil;$c <= $row_genap;$c+=4) {                                
                                    $rpt->setXY(3,$c);
                                    $rpt->Cell(102,4,'',1,0);
                                }
							}
							$row_ganjil=$sisa;
						}else{ // berarti tambah row yang genap
							$sisa=$row_genap + ($row_ganjil-$row_genap);						
                            if ($tampil_border_genap){
                                for ($c=$row_genap;$c < $row_ganjil;$c+=4) {                                
                                    $rpt->setXY(106,$c);
                                    $rpt->Cell(102,4,'',1,0);                                
                                }
                            }
							$row_genap=$sisa;
						}		
                        if ($bool_khs_ganjil) {
                            //ganjil
                            $rpt->setXY(16,$row_ganjil);	
                            $rpt->Cell(65,4,'Jumlah',1,0,'L');						
                            $rpt->Cell(8,4,$ganjil_total_sks,1,0,'C');
                            $rpt->Cell(8,4,'',1,0,'L');						
                            $rpt->Cell(8,4,$ganjil_total_m,1,0,'C');
                            $row_ganjil+=4;
                            $rpt->setXY(16,$row_ganjil);	
                            $rpt->Cell(81,4,'Indeks Prestasi Semester',1,0,'L');
                            $ip=@ bcdiv($ganjil_total_m,$ganjil_total_sks,2);												
                            $rpt->Cell(8,4,$ip,1,0,'C');
                            $row_ganjil+=4;
                            $rpt->setXY(16,$row_ganjil);	
                            $rpt->Cell(81,4,'Indeks Prestasi Kumulatif',1,0,'L');		
                            $ipk_ganjil=$ip == '0.00'?'0.00':$ipk_ganjil;
                            $rpt->Cell(8,4,$ipk_ganjil,1,0,'C');
                            $row_ganjil+=4;	
                            $rpt->setXY(16,$row_ganjil);
                            $rpt->Cell(8,4,' ',0,0,'C');						
                            $row_ganjil+=1;		
                        }
                        if ($bool_khs_genap) {
                            //genap			
                            $rpt->setXY(119,$row_genap);	
                            $rpt->Cell(65,4,'Jumlah',1,0,'L');						
                            $rpt->Cell(8,4,$genap_total_sks,1,0,'C');
                            $rpt->Cell(8,4,'',1,0,'L');
                            $rpt->Cell(8,4,$genap_total_m,1,0,'C');
                            $row_genap+=4;
                            $rpt->setXY(119,$row_genap);	
                            $rpt->Cell(81,4,'Indeks Prestasi Semester',1,0,'L');
                            $ip=@ bcdiv($genap_total_m,$genap_total_sks,2);									
                            $rpt->Cell(8,4,$ip,1,0,'C');
                            $row_genap+=4;
                            $rpt->setXY(119,$row_genap);	
                            $rpt->Cell(81,4,'Indeks Prestasi Kumulatif',1,0,'L');								
                            $ipk_genap=$ip == '0.00'?'0.00':$ipk_genap;
                            $rpt->Cell(8,4,$ipk_genap,1,0,'C');
                            $row_genap+=4;
                            $rpt->setXY(119,$row_genap);	
                            $rpt->Cell(8,4,' ',0,0,'C');
                            $row_genap+=1;						
                        }
                        $bool_khs_genap = false;
                        $bool_khs_ganjil = false;
					}
				}
                $rpt->SetFont ('helvetica','B',6);
				$row=$row_genap+4;
				$rpt->setXY(105,$row);	
				$rpt->Cell(65,4,'Total Kredit Kumulatif',0,0,'L');
				$rpt->Cell(8,4,$totalSks,0,0,'C');
				$row+=4;
				$rpt->setXY(105,$row);	
				$rpt->Cell(65,4,'Jumlah Nilai Kumulatif',0,0,'L');
				$rpt->Cell(8,4,$totalM,0,0,'C');																
				$row+=4;
				$rpt->setXY(105,$row);	
				$rpt->Cell(65,4,'Indeks Prestasi Kumulatif',0,0,'L');
				$ipk=@ bcdiv($totalM,$totalSks,2);
				$ipk=$ipk==''?'0.00':$ipk;
				$rpt->Cell(8,4,$ipk,0,0,'C');																
                if ($withsignature) {
                    $row+=4;
                    $rpt->setXY(105,$row);	
                    $rpt->Cell(65,4,$this->setup->getSettingValue('kota_pt').', '.$this->tgl->tanggal('j F Y'),0,0,'L');
                    $row+=4;
                    $rpt->setXY(105,$row);	
                    $rpt->Cell(65,4,$this->dataReport['nama_jabatan_transkrip'],0,0,'L');                    
                    $row+=14;				
                    $rpt->setXY(105,$row);	
                    $rpt->Cell(65,4,$this->dataReport['nama_penandatangan_transkrip'],0,0,'L');
                    $row+=4;				
                    $rpt->setXY(105,$row);	
                    $rpt->Cell(65,4,strtoupper($this->dataReport['jabfung_penandatangan_transkrip']). ' NIDN '.$this->dataReport['nidn_penandatangan_transkrip'],0,0,'L');
                }
                $this->printOut("transkripkrs_$nim");
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Transkrip KRS");
    }
    /**
     * digunakan untuk memprint Transkrip Final
     * @param type $objNilai object
     */
    public function printTranskripFinal ($objNilai) {
        $biodata=$this->dataReport;
        $nim=$biodata['nim'];
        $objNilai->setDataMHS (array('nim'=>$nim));
        $n=$objNilai->getTranskrip(false);		
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :                
//                $this->printOut("khs_$nim");
            break;
            case 'pdf' :
                $rpt=$this->rpt;
                $rpt->setTitle('Transkrip Nilai Final');
				$rpt->setSubject('Transkrip Nilai Final');
                $rpt->AddPage('P','F4');
                $row=43;			
                $rpt->SetFont ('helvetica','',8);	
                $rpt->setXY(3,$row);			
                $rpt->Cell(0,5,'Nomor Ijazah: '.$biodata['dataTranskrip']['nomor_ijazah'],0,0,'R');
                $row+=6;	
                $rpt->SetFont ('helvetica','BU',12);	
				$rpt->setXY(3,$row);			
				$rpt->Cell(0,5,'TRANSKRIP AKADEMIK',0,0,'C');
				$row+=4;				
				$rpt->SetFont ('helvetica','B',10);	
				$rpt->setXY(3,$row);			
				$rpt->Cell(0,5,'Nomor : '.$biodata['dataTranskrip']['nomor_transkrip'],0,0,'C');
				$row+=8;
				$rpt->SetFont ('helvetica','',8);	
				$rpt->setXY(3,$row);			
				$rpt->Cell(44,5,'NAMA');	
				$rpt->Cell(1.5,5,':');			
				$rpt->Cell(150,5,$biodata['nama_mhs']);
				$row+=4;
				$rpt->setXY(3,$row);							
				$rpt->Cell(44,5,'NIM');
				$rpt->Cell(1.5,5,':');			
				$rpt->Cell(150,5,$biodata['nim']);
				$row+=4;
				$rpt->setXY(3,$row);							
				$rpt->Cell(44,5,'NIRM');		
				$rpt->Cell(1.5,5,':');			
				$rpt->Cell(150,5,$biodata['nirm']);		
				$row+=4;
				$rpt->setXY(3,$row);			
				$rpt->Cell(44,5,'TEMPAT, TGL. LAHIR');
				$rpt->Cell(1.5,5,':');			
				$rpt->Cell(150,5,$biodata['tempat_lahir']. ', '.$this->tgl->tanggal('j F Y',$biodata['tanggal_lahir']));	
				$row+=4;
				$rpt->setXY(3,$row);			
				$rpt->Cell(44,5,'PROGRAM STUDI');
				$rpt->Cell(1.5,5,':');									
				$rpt->Cell(150,5,$biodata['nama_ps']);	
                $row+=4;
				$rpt->setXY(3,$row);			
				$rpt->Cell(44,5,'KONSENTRASI');
				$rpt->Cell(1.5,5,':');									
				$rpt->Cell(150,5, strtoupper($biodata['nama_konsentrasi']));
				$row+=4;
				$rpt->setXY(3,$row);			
				$rpt->Cell(44,5,'INDEKS PRESTASI KUMULATIF');			
				$rpt->Cell(1.5,5,':');			
				$rpt->Cell(150,5,$objNilai->getIPKAdaNilai());		
				$row+=4;
				$rpt->setXY(3,$row);			
				$rpt->Cell(44,5,'PREDIKAT KELULUSAN');			
				$rpt->Cell(1.5,5,':');			
				$rpt->Cell(150,5,$biodata['dataTranskrip']['predikat_kelulusan']);		
				$row+=4;
				$rpt->setXY(3,$row);			
				$rpt->Cell(44,5,'TANGGAL LULUS');
				$rpt->Cell(1.5,5,':');						
				$rpt->Cell(150,5,$this->tgl->tanggal('j F Y',$biodata['dataTranskrip']['tanggal_lulus']));		
				$row+=4;
				$nama_pembimbing1=$biodata['dataTranskrip']['nama_pembimbing1'];					
				$rpt->setXY(3,$row);			
				$rpt->Cell(44,5,'PEMBIMBING SKRIPSI');			
				$rpt->Cell(1.5,5,':');			
				$rpt->Cell(150,5,"1. $nama_pembimbing1");			
				$nama_pembimbing2=$biodata['dataTranskrip']['nama_pembimbing2'];				
				if ($nama_pembimbing2!='') {					
                    $row+=4;					
					$rpt->setXY(3,$row);								
					$rpt->Cell(44,5,'');			
					$rpt->Cell(1.5,5,'');			
					$rpt->Cell(150,5,"2. $nama_pembimbing2");			
				}				
				$row+=4;
				$rpt->setXY(3,$row);			
				$rpt->Cell(44,5,'JUDUL SKRIPSI');	
				$rpt->Cell(1.5,5,':');			
				$row+=0.5;
				$rpt->setXY(3,$row);			
				$rpt->Cell(44,5,'');	
				$rpt->Cell(1.5,5,'');			
				$rpt->MultiCell(150,11,$biodata['dataTranskrip']['judul_skripsi'],0,'L',false,0,'','');		
				
				//field of column ganjil
				$row+=12;
				$rpt->setXY(3,$row);
				$rpt->Cell(205,3,' ','T',0,'C');
				$row+=0.7;
				$rpt->setXY(3,$row);				
				$rpt->Cell(15,5,'KODE','TBR',0,'C');
				$rpt->Cell(60,5,'MATA KULIAH',1,0,'C');
				$rpt->Cell(9,5,'SKS',1,0,'C');
				$rpt->Cell(9,5,'AM',1,0,'C');
				$rpt->Cell(9,5,'NM',1,0,'C');				
				$rpt->Cell(1,5,'');				
				//field of column genap				
				$rpt->Cell(15,5,'KODE',1,0,'C');
				$rpt->Cell(60,5,'MATA KULIAH',1,0,'C');
				$rpt->Cell(9,5,'SKS',1,0,'C');
				$rpt->Cell(9,5,'AM',1,0,'C');
				$rpt->Cell(9,5,'NM','TBL',0,'C');				
				$row+=5;							
				$totalSks=0;
				$totalM=0;				
				$row_ganjil=$row;
				$row_genap = $row;
				$rpt->setXY(3,$row);	
				$rpt->SetFont ('helvetica','',6);
				$tambah_ganjil_row=false;		
				$tambah_genap_row=false;						
                
                for ($i = 1; $i <= 8; $i++) {					
					if ($i%2==0) {//genap
						$tambah_genap_row=true;	
						$rpt->setXY(106,$row_genap);
						$rpt->Cell(102,4,"SEMESTER $i",'L',0,'C');
						$row_genap+=4;
						foreach ($n as $v) {	
							if ($v['semester']==$i) {
								$n_kual=$v['n_kual'];
								$n_kual=($n_kual=='-'||$n_kual=='')?'-':$n_kual;
								$sks=$v['sks'];
								$totalSks+=$sks;								
								$rpt->setXY(106,$row_genap);							
								$rpt->Cell(15,4,$objNilai->getKMatkul($v['kmatkul']),'L',0,'C');
								$rpt->Cell(60,4,$v['nmatkul'],0,0,'L');		
								$rpt->Cell(9,4,$sks,0,0,'C');
								$rpt->Cell(9,4,$objNilai->getAngkaMutu($n_kual),0,0,'C');
								$rpt->Cell(9,4,$n_kual,0,0,'C');							
								$row_genap+=4;
							}
						}
					}else {//ganjil
						$tambah_ganjil_row=true;						
						$rpt->setXY(3,$row_genap);
						$rpt->Cell(102,4,"SEMESTER $i",'R',0,'C');
						$row_ganjil+=4;
						foreach ($n as $s) {
							if ($s['semester']==$i) {
								$n_kual=$s['n_kual'];
								$n_kual=($n_kual=='-'||$n_kual=='')?'-':$n_kual;
								$sks=$s['sks'];
								$totalSks+=$sks;													
								$rpt->setXY(3,$row_ganjil);						
								$rpt->Cell(15,4,$objNilai->getKMatkul($s['kmatkul']),0,0,'C');
								$rpt->Cell(60,4,$s['nmatkul'],0,0,'L');		
								$rpt->Cell(9,4,$sks,0,0,'C');
								$rpt->Cell(9,4,$objNilai->getAngkaMutu($n_kual),0,0,'C');
								$rpt->Cell(9,4,$n_kual,'R',0,'C');							
								$row_ganjil+=4;
							}
						}
					}
					if ($tambah_ganjil_row && $tambah_genap_row) {	
						$tambah_ganjil_row=false;
						$tambah_genap_row=false;						
						if ($row_ganjil < $row_genap){ // berarti tambah row yang ganjil
							$sisa=$row_ganjil + ($row_genap-$row_ganjil);
							for ($c=$row_ganjil;$c <= $row_genap;$c+=4) {
								$rpt->setXY(3,$c);
								$rpt->Cell(15,4,'',0,0,'C');
								$rpt->Cell(60,4,'',0,0,'L');		
								$rpt->Cell(9,4,'',0,0,'C');
								$rpt->Cell(9,4,'',0,0,'C');
								$rpt->Cell(9,4,'','R',0,'C');													
							}
							$row_ganjil=$sisa;
						}else{ // berarti tambah row yang genap
							$sisa=$row_genap + ($row_ganjil-$row_genap);						
							for ($c=$row_genap;$c < $row_ganjil;$c+=4) {
								$rpt->setXY(106,$c);
								$rpt->Cell(15,4,'','L',0,'C');
								$rpt->Cell(60,4,'',0,0,'L');		
								$rpt->Cell(9,4,'',0,0,'C');
								$rpt->Cell(9,4,'',0,0,'C');
								$rpt->Cell(9,4,'',0,0,'C');							
							}
							$row_genap=$sisa;
						}					
					}
				}
				$row=$row_genap;	
				$rpt->setXY(3,$row);	
				$rpt->Cell(102,4,'','T',0);
				$rpt->Cell(1,4,'',0,0);																						
				$rpt->Cell(102,4,'','T',0);
				$row=$row_genap+0.7;					
				$rpt->setXY(3,$row);	
				$rpt->Cell(205,1,' ','T',0);
				$row+=1;					
                $rpt->SetFont ('helvetica','B',8);
				$rpt->setXY(7,$row);	
				$rpt->Cell(65,4,'Jumlah SKS Kumulatif',0);
				$rpt->SetFont ('helvetica','');
				$rpt->Cell(4,4,': ',0);		
				$rpt->SetFont ('helvetica','B');						
				$rpt->Cell(130,4,"$totalSks",0);
				$row+=4;
				$rpt->setXY(7,$row);	
				$rpt->Cell(65,4,'Keterangan',0);
				$rpt->SetFont ('helvetica','');
				$rpt->Cell(4,4,': ',0);		
				$rpt->Cell(45,4,'A = 4 (Sangat Baik)',0);
				$rpt->Cell(85,4,': B = 3 (Baik) : C = 2 (Cukup)',0);
				$row+=4;
				$rpt->setXY(7,$row);	
				$rpt->Cell(65,4,' ',0);				
				$rpt->Cell(4,4,' ',0);		
				$rpt->Cell(45,4,'D = 1 (Kurang)',0);
				$rpt->Cell(85,4,': E = 0 (Tidak Lulus)',0);																
				$row+=6;
				$rpt->setXY(20,$row);	
				$rpt->Cell(25,30,'',1);															
				$row+=2;
				$rpt->setXY(40,$row);	
				$rpt->Cell(65,4,'',0,0,'C');						
				$rpt->Cell(90,4,$this->setup->getSettingValue('kota_pt').', '.$this->tgl->tanggal('j F Y',$biodata['tanggalterbit']),0,0,'C');
				$row+=4;
				$rpt->setXY(40,$row);	
				$rpt->Cell(65,4,$biodata['nama_jabatan_transkrip'].',',0,0,'C');				
                $rpt->Cell(90,4,'KETUA PROGRAM STUDI,',0,0,'C');
                $row+=4;
                $rpt->setXY(105,$row);	                
                $rpt->Cell(90,4,$biodata['nama_ps'],0,0,'C');

				$row+=17;				                
				$rpt->setXY(40,$row);	
				$rpt->SetFont ('helvetica','B');
				$rpt->Cell(65,4,$biodata['nama_penandatangan_transkrip'],0,0,'C');								
				$rpt->Cell(90,4,$biodata['nama_kaprodi'],0,0,'C');
				$row+=4;				
				$rpt->setXY(40,$row);	
				$rpt->SetFont ('helvetica','');
				$rpt->Cell(65,4,strtoupper($biodata['jabfung_penandatangan_transkrip']). ' NIDN '.$biodata['nidn_penandatangan_transkrip'],0,0,'C');
				$rpt->Cell(90,4,strtoupper($biodata['jabfung_kaprodi']). ' NIDN '.$biodata['nidn_kaprodi'],0,0,'C');
                
                $this->printOut("transkripfinal_$nim");
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Transkrip Final");
    }
        
    /**
     * digunakan untuk memprint DPNA
     * @param type $objNilai object
     */
    public function printDPNA ($objNilai) {    
        $kmatkul=$this->dataReport['kmatkul'];
        $idkelas_mhs=$this->dataReport['idkelas_mhs'];
        $kaprodi=$objNilai->getKetuaPRODI($this->dataReport['kjur']);
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :                
                $this->setHeaderPT('K'); 
                $sheet=$this->rpt->getActiveSheet();
                $this->rpt->getDefaultStyle()->getFont()->setName('Arial');                
                $this->rpt->getDefaultStyle()->getFont()->setSize('9');                                    
                
                $sheet->mergeCells("A7:K7");
                $sheet->getRowDimension(7)->setRowHeight(20);
                $sheet->setCellValue("A7",'DAFTAR PESERTA DAN NILAI AKHIR');                                
                
                $styleArray=array(
								'font' => array('bold' => true,
                                                'size' => 16),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
							);
                $sheet->getStyle("A7:K8")->applyFromArray($styleArray);

                $sheet->getColumnDimension('A')->setWidth(8);
                $sheet->getColumnDimension('B')->setWidth(11);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(12);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('K')->setWidth(15);               
                
                if ($idkelas_mhs == 'none'){                    
                    $sheet->mergeCells('A10:B10');                    
                    $sheet->setCellValue("A10",'Matakuliah');				                    
                    $sheet->setCellValue("C10",': '.$this->dataReport['nmatkul']);
                    
                    $sheet->mergeCells('A11:B11');                    
                    $sheet->setCellValue("A11",'Kode / SKS');				
                    $sheet->setCellValue("C11",': '.$this->dataReport['kmatkul'].' / '.$this->dataReport['sks']);
                    
                    $sheet->mergeCells('A12:B12');                    
                    $sheet->setCellValue("A12",'P.S / Jenjang');				
                    $sheet->setCellValue("C12",': '.$this->dataReport['nama_ps']);
                    
                    $sheet->mergeCells('E13:F13');      
                    $sheet->setCellValue("E12",'Dosen Pengampu');				
                    $sheet->setCellValue("G12",': '.$this->dataReport['nama_dosen_pengampu']);
                    
                    $sheet->mergeCells('A13:B13');                    
                    $sheet->setCellValue("A13",'Semester');				
                    $sheet->setCellValue("C13",': '.$this->dataReport['nama_semester']);
                    
                    $sheet->mergeCells('E13:F13');      
                    $sheet->setCellValue("E13",'T.A');				
                    $sheet->setCellValue("G13",': '.$this->dataReport['ta']);
                
                    
                    $idpenyelenggaraan=$this->dataReport['idpenyelenggaraan'];                                        
                    $str ="SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,n.n_kuan,n.n_kual FROM v_krsmhs vkm JOIN v_datamhs vdm ON(vdm.nim=vkm.nim) LEFT JOIN nilai_matakuliah n ON (n.idkrsmatkul=vkm.idkrsmatkul) WHERE vkm.idpenyelenggaraan=$idpenyelenggaraan AND vkm.sah=1 AND vkm.batal=0 ORDER BY vdm.nama_mhs ASC";
                    $this->db->setFieldTable(array('nim','nirm','nama_mhs','jk','n_kuan','n_kual'));
                    
                    
                }else{
                    $sheet->mergeCells('A10:B10');                    
                    $sheet->setCellValue("A10",'Matakuliah');				                    
                    $sheet->setCellValue("C10",': '.$this->dataReport['nmatkul']);
                    
                    $sheet->mergeCells('A11:B11');                    
                    $sheet->setCellValue("A11",'Kode / SKS');				
                    $sheet->setCellValue("C11",': '.$this->dataReport['kmatkul'].' / '.$this->dataReport['sks']);
                    
                    $sheet->mergeCells('A12:B12');                    
                    $sheet->setCellValue("A12",'P.S / Jenjang');				
                    $sheet->setCellValue("C12",': '.$this->dataReport['nama_ps']);
                    
                    $sheet->mergeCells('E13:F13');      
                    $sheet->setCellValue("E12",'Dosen Pengampu');				
                    $sheet->setCellValue("G12",': '.$this->dataReport['nama_dosen_pengampu']);
                    
                    $sheet->mergeCells('A13:B13');                    
                    $sheet->setCellValue("A13",'Semester');				
                    $sheet->setCellValue("C13",': '.$this->dataReport['nama_semester']);
                    
                    $sheet->mergeCells('E13:F13');      
                    $sheet->setCellValue("E13",'T.A');				
                    $sheet->setCellValue("G13",': '.$this->dataReport['ta']);
                    
                    $sheet->mergeCells('A13:B13');                    
                    $sheet->setCellValue("A13",'Kelas');				
                    $sheet->setCellValue("C13",': '.$this->dataReport['namakelas']);
                    
                    $sheet->mergeCells('E13:F13');      
                    $sheet->setCellValue("E13",'Hari / Jam');				
                    $sheet->setCellValue("G13",': '.$this->dataReport['hari'].', '.$this->dataReport['jam_masuk'].'-'.$this->dataReport['jam_keluar']);
                    
                    $idkelas=$this->dataReport['idkelas_mhs'];                    
                    $itemcount=$this->db->getCountRowsOfTable("kelas_mhs_detail kmd JOIN v_krsmhs vkm ON (vkm.idkrsmatkul=kmd.idkrsmatkul)  JOIN v_datamhs vdm ON (vkm.nim=vdm.nim) WHERE  kmd.idkelas_mhs=$idkelas AND vkm.sah=1 AND vkm.batal=0",'vkm.nim');
                    $pagesize=40;				
                    $jumlahpage=ceil($itemcount/$pagesize);		
                    
                    $str ="SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,n.n_kual,n.n_kuan FROM kelas_mhs_detail kmd LEFT JOIN nilai_matakuliah n ON (n.idkrsmatkul=kmd.idkrsmatkul) JOIN v_krsmhs vkm ON (vkm.idkrsmatkul=kmd.idkrsmatkul)  JOIN v_datamhs vdm ON (vkm.nim=vdm.nim) WHERE  kmd.idkelas_mhs=$idkelas AND vkm.sah=1 AND vkm.batal=0 ORDER BY vdm.nama_mhs ASC";
                    $this->db->setFieldTable(array('nim','nirm','nama_mhs','jk','n_kual','n_kuan'));
                }
                
                $sheet->mergeCells('A15:A16');				
                $sheet->setCellValue('A15','NO');				
                $sheet->mergeCells('B15:D16');				
                $sheet->setCellValue('B15','NAMA');				
                $sheet->mergeCells('E15:E16');				
                $sheet->setCellValue('E15','JK');				
                $sheet->mergeCells('F15:F16');				
                $sheet->setCellValue('F15','NIM');				
                $sheet->mergeCells('G15:G16');				
                $sheet->setCellValue('G15','NIRM');				                
                $sheet->mergeCells('H15:J15');				
                $sheet->setCellValue('H15','NILAI');			
                $sheet->mergeCells('K15:K16');				
                $sheet->setCellValue('K15','KETERANGAN');				
                
                $sheet->setCellValue('H16','ANGKA');			
                $sheet->setCellValue('I16','AM');			
                $sheet->setCellValue('J16','HM');			
                
                $styleArray=array(								
                                    'font' => array('bold' => true),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                       'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                                );																					 
                $sheet->getStyle("A15:K16")->applyFromArray($styleArray);
                $sheet->getStyle("A15:K6")->getAlignment()->setWrapText(true);
                
                $row=17;
                $r=$this->db->getRecord($str);	
                while (list($k,$v)=each ($r) ){		                    				
                    $sheet->setCellValue("A$row",$v['no']);				
                    $sheet->mergeCells("B$row:D$row");				
                    $sheet->setCellValue("B$row",$v['nama_mhs']);				                    
                    $sheet->setCellValue("E$row",$v['jk']);				                                        
                    $sheet->setCellValueExplicit("F$row",$v['nim'],PHPExcel_Cell_DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit("G$row",$v['nirm'],PHPExcel_Cell_DataType::TYPE_STRING);	                        
                    $sheet->setCellValue("H$row",$v['n_kuan']);			
                    $am=$v['n_kual']==''?'-':$objNilai->getAngkaMutu($v['n_kual']);
                    $hm=$v['n_kual']==''?'-':$v['n_kual'];
                    $sheet->setCellValue("I$row",$am);			
                    $sheet->setCellValue("J$row",$hm);			
                    $sheet->setCellValue("K$row",'-');	
                    $row+=1;
                }
                $row-=1;
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                                       'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                                );																					 
                $sheet->getStyle("A17:K$row")->applyFromArray($styleArray);
                $sheet->getStyle("A17:K$row")->getAlignment()->setWrapText(true);
                
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                );
                
                $sheet->getStyle("A17:A$row")->applyFromArray($styleArray);
                $sheet->getStyle("E17:K$row")->applyFromArray($styleArray);
                
                
                $row+=3;
                $row_awal=$row;                
                
                $tanggal=$this->tgl->tanggal('l, j F Y');	
                $sheet->setCellValue("H$row",$this->setup->getSettingValue('kota_pt').", $tanggal");			
                
                $row+=2;                
                $sheet->setCellValue("C$row",'A.N. KETUA');			
                $sheet->setCellValue("F$row","KETUA PROGRAM STUDI");			
                $sheet->setCellValue("H$row","DOSEN PENGAJAR");			
               

                $row+=1;
                $sheet->setCellValue("C$row",$this->dataReport['nama_jabatan_dpna']);			
                $sheet->setCellValue("F$row",$this->dataReport['nama_ps']);			
                $sheet->setCellValue("H$row",'MATAKULIAH');			
                
                $row+=5;
                $sheet->setCellValue("C$row",$this->dataReport['nama_penandatangan_dpna']);			
                $kaprodi=$objNilai->getKetuaPRODI($this->dataReport['kjur']);
                $sheet->setCellValue("F$row",$kaprodi['nama_dosen']);	
                
                $nama_dosen_ttd=$this->dataReport['nama_dosen_pengampu'];
                $nidn_jabatan_dosen_ttd=$this->dataReport['nama_jabatan_dosen_pengampu']. ' NIDN '.$this->dataReport['nidn_dosen_pengampu'];
                if ($this->dataReport['idjabatan_dosen_pengajar'] > 0)
                {
                    $nama_dosen_ttd=$this->dataReport['nama_dosen_pengajar'];
                    $nidn_jabatan_dosen_ttd=$this->dataReport['nama_jabatan_dosen_pengajar']. ' NIDN '.$this->dataReport['nidn_dosen_pengajar'];
                }                
                $sheet->setCellValue("H$row",$nama_dosen_ttd);			
                
                $row+=1;
                $sheet->setCellValue("C$row",strtoupper($this->dataReport['jabfung_penandatangan_dpna']). ' NIDN '.$this->dataReport['nidn_penandatangan_dpna']);			
                $sheet->setCellValue("F$row",$kaprodi['nama_jabatan']. ' NIDN '.$kaprodi['nidn']);			                
                $sheet->setCellValue("H$row",$nidn_jabatan_dosen_ttd);			
                
                $this->printOut("dpna_$kmatkul");
            break;
            case 'pdf' :
                $rpt=$this->rpt;
                $rpt->setTitle('Daftar Peserta dan Nilai Akhir');
				$rpt->setSubject('Daftar Peserta dan Nilai Akhir');
                $rpt->setAutoPageBreak(true,PDF_MARGIN_BOTTOM);
                $idkelas_mhs=$this->dataReport['idkelas_mhs'];
                
                $rpt->AddPage('P','F4');
                $this->setHeaderPT();
                
                $row=$this->currentRow;
                $row+=6;
                $rpt->SetFont ('helvetica','B',12);	
                $rpt->setXY(3,$row);			
                $rpt->Cell(0,$row,'DAFTAR PESERTA DAN NILAI AKHIR',0,0,'C');                
                if ($idkelas_mhs == 'none'){
                    $row+=6;
					$rpt->SetFont ('helvetica','B',8);	
					$rpt->setXY(3,$row);			
					$rpt->Cell(0,$row,'Matakuliah');
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(38,$row);			
					$rpt->Cell(0,$row,': '.$this->dataReport['nmatkul']);
                    $row+=3;
                    $rpt->setXY(3,$row);			
					$rpt->SetFont ('helvetica','B',8);	
					$rpt->Cell(0,$row,'Kode / SKS');
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(38,$row);			
					$rpt->Cell(0,$row,': '.$this->dataReport['kmatkul'].' / '.$this->dataReport['sks']);				
                    $row+=3;
					$rpt->setXY(3,$row);
					$rpt->SetFont ('helvetica','B',8);						
					$rpt->Cell(0,$row,'P.S / Jenjang');
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(38,$row);			
					$rpt->Cell(0,$row,': '.$this->dataReport['nama_ps']);
					$rpt->SetFont ('helvetica','B',8);	
					$rpt->setXY(105,$row);			
					$rpt->Cell(0,$row,'Dosen Pengampu');
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(130,$row);			
					$rpt->Cell(0,$row,': '.$this->dataReport['nama_dosen_pengampu']);
                    $row+=3;
					$rpt->setXY(3,$row);			
					$rpt->SetFont ('helvetica','B',8);	
					$rpt->Cell(0,$row,'Semester');				
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(38,$row);			
					$rpt->Cell(0,$row,': '.$this->dataReport['nama_semester']);	
                    $rpt->SetFont ('helvetica','B',8);	
					$rpt->setXY(105,$row);			
					$rpt->Cell(0,$row,'T.A');
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(130,$row);			
					$rpt->Cell(0,$row,': '.$this->dataReport['ta']);
                    
                    $idpenyelenggaraan=$this->dataReport['idpenyelenggaraan'];                    
                    $itemcount=$this->db->getCountRowsOfTable("v_krsmhs vkm JOIN v_datamhs vdm ON(vdm.nim=vkm.nim) WHERE vkm.idpenyelenggaraan=$idpenyelenggaraan AND vkm.sah=1 AND vkm.batal=0",'vkm.nim');
                    $pagesize=39;				
                    $jumlahpage=ceil($itemcount/$pagesize);		
                    
                    $str ="SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,n.n_kual FROM v_krsmhs vkm JOIN v_datamhs vdm ON(vdm.nim=vkm.nim) LEFT JOIN nilai_matakuliah n ON (n.idkrsmatkul=vkm.idkrsmatkul) WHERE vkm.idpenyelenggaraan=$idpenyelenggaraan AND vkm.sah=1 AND vkm.batal=0 ORDER BY vdm.nama_mhs ASC";
                    $this->db->setFieldTable(array('nim','nirm','nama_mhs','jk','n_kual'));
                }else{
                    $row+=6;
					$rpt->SetFont ('helvetica','B',8);	
					$rpt->setXY(3,$row);			
					$rpt->Cell(0,$row,'Matakuliah');
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(38,$row);			
					$rpt->Cell(0,$row,': '.$this->dataReport['nmatkul']);
                    $row+=3;
                    $rpt->setXY(3,$row);			
					$rpt->SetFont ('helvetica','B',8);	
					$rpt->Cell(0,$row,'Kode / SKS');
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(38,$row);			
					$rpt->Cell(0,$row,': '.$this->dataReport['kmatkul'].' / '.$this->dataReport['sks']);
                    $rpt->SetFont ('helvetica','B',8);	
					$rpt->setXY(105,$row);			
					$rpt->Cell(0,$row,'Dosen Pengampu');
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(130,$row);			
					$rpt->Cell(0,$row,': '.$this->dataReport['nama_dosen_pengampu']);
                    $row+=3;
					$rpt->setXY(3,$row);
					$rpt->SetFont ('helvetica','B',8);						
					$rpt->Cell(0,$row,'P.S / Jenjang');
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(38,$row);			
					$rpt->Cell(0,$row,': '.$this->dataReport['nama_ps']);
                    $rpt->SetFont ('helvetica','B',8);	
					$rpt->setXY(105,$row);			
					$rpt->Cell(0,$row,'Dosen Pengajar');
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(130,$row);			
					$rpt->Cell(0,$row,': '.$this->dataReport['nama_dosen_pengajar']);			
                    $row+=3;
					$rpt->setXY(3,$row);			
					$rpt->SetFont ('helvetica','B',8);	
					$rpt->Cell(0,$row,'T.A / SMT / Kelas');				
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(38,$row);			
                    $rpt->Cell(0,$row,': '.$this->dataReport['ta'].' '.$this->dataReport['nama_semester'].' '.$this->dataReport['namakelas']);
                    $rpt->SetFont ('helvetica','B',8);	
					$rpt->setXY(105,$row);			
					$rpt->Cell(0,$row,'Hari / Jam');
					$rpt->SetFont ('helvetica','',8);
					$rpt->setXY(130,$row);			
					$rpt->Cell(0,$row,': '.$this->dataReport['hari'].', '.$this->dataReport['jam_masuk'].'-'.$this->dataReport['jam_keluar']);								
                    
                    $idkelas=$this->dataReport['idkelas_mhs'];                    
                    $itemcount=$this->db->getCountRowsOfTable("kelas_mhs_detail kmd JOIN v_krsmhs vkm ON (vkm.idkrsmatkul=kmd.idkrsmatkul)  JOIN v_datamhs vdm ON (vkm.nim=vdm.nim) WHERE  kmd.idkelas_mhs=$idkelas AND vkm.sah=1 AND vkm.batal=0",'vkm.nim');
                    $pagesize=39;				
                    $jumlahpage=ceil($itemcount/$pagesize);		
                    
                    $str ="SELECT vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,n.n_kual FROM kelas_mhs_detail kmd LEFT JOIN nilai_matakuliah n ON (n.idkrsmatkul=kmd.idkrsmatkul) JOIN v_krsmhs vkm ON (vkm.idkrsmatkul=kmd.idkrsmatkul)  JOIN v_datamhs vdm ON (vkm.nim=vdm.nim) WHERE  kmd.idkelas_mhs=$idkelas AND vkm.sah=1 AND vkm.batal=0 ORDER BY vdm.nama_mhs ASC";
                    $this->db->setFieldTable(array('nim','nirm','nama_mhs','jk','n_kual'));
                }
                $row+=23;
                $rpt->SetFont ('helvetica','B',8);
                $rpt->setXY(3,$row);			
                $rpt->Cell(13, 8, 'NO', 1, 0, 'C');				
                $rpt->Cell(70, 8, 'NAMA', 1, 0, 'C');								
                $rpt->Cell(10, 8, 'JK', 1, 0, 'C');							
                $rpt->Cell(20, 8, 'NIM', 1, 0, 'C');				
                $rpt->Cell(30, 8, 'NIRM', 1, 0, 'C');
                $rpt->Cell(20, 4, 'NILAI', 1, 0, 'C');								
                $rpt->Cell(30, 8, 'KETERANGAN', 1, 0, 'C');
                $row+=4;
                $rpt->setXY(146,$row);
                $rpt->Cell(10, 4, 'AM', 1, 0, 'C');
                $rpt->Cell(10, 4, 'HM', 1, 0, 'C');																
                $row+=4;				
                $rpt->setXY(3,$row);	
                $rpt->setFont ('helvetica','',8);                
                
                for ($i=0;$i < $jumlahpage;$i++) {        
                    $offset=$i*$pagesize;
                    $limit=$pagesize;
                    if ($offset+$limit>$itemcount) {
                        $limit=$itemcount-$offset;
                    }                                                   
                    $r=$this->db->getRecord("$str LIMIT $offset,$limit",$offset+1);	
                    while (list($k,$v)=each ($r) ){		
                        $rpt->setXY(3,$row);				
                        $rpt->Cell(13, 5, $v['no'], 1, 0, 'C');				
                        $rpt->Cell(70, 5, $v['nama_mhs'], 1, 0);								
                        $rpt->Cell(10, 5, $v['jk'], 1, 0, 'C');							
                        $rpt->Cell(20, 5, $v['nim'], 1, 0, 'C');				
                        $rpt->Cell(30, 5, $v['nirm'], 1, 0, 'C');				
                        $am=$v['n_kual']==''?'-':$objNilai->getAngkaMutu($v['n_kual']);
                        $hm=$v['n_kual']==''?'-':$v['n_kual'];
                        $rpt->Cell(10, 5, $am, 1, 0, 'C');
                        $rpt->Cell(10, 5, $hm, 1, 0, 'C');																
                        $rpt->Cell(30, 5, '', 1, 0, 'C');
                        $row+=5;
                    }
                    $row+=5;
                    $rpt->SetFont ('helvetica','B',8);
                    $rpt->setXY(3,$row);													
                    $tanggal=$this->tgl->tanggal('l, j F Y');				
                    $rpt->Cell(192, 5, $this->setup->getSettingValue('kota_pt').", $tanggal",0,0,'R');		

                    $row+=5;
                    $rpt->setXY(3,$row);			
                    $rpt->Cell(64, 5, 'A.N. KETUA',0,0,'C');			
                    $rpt->Cell(64, 5, "KETUA PROGRAM STUDI",0,0,'C');		
                    $rpt->Cell(64, 5, "DOSEN PENGAJAR",0,0,'C');		

                    $row+=5;
                    $rpt->setXY(3,$row);			
                    $rpt->Cell(64, 5, $this->dataReport['nama_jabatan_dpna'],0,0,'C');												
                    $rpt->Cell(64, 5, $this->dataReport['nama_ps'],0,0,'C');		
                    $rpt->Cell(64, 5, 'MATAKULIAH',0,0,'C');	

                    $row+=18;				
                    $rpt->setXY(3,$row);			
                    $rpt->Cell(64, 5,$this->dataReport['nama_penandatangan_dpna'],0,0,'C');
                    $rpt->Cell(64, 5,$kaprodi['nama_dosen'],0,0,'C');

                    $nama_dosen_ttd=$this->dataReport['nama_dosen_pengampu'];
                    $nidn_jabatan_dosen_ttd=$this->dataReport['nama_jabatan_dosen_pengampu']. ' NIDN '.$this->dataReport['nidn_dosen_pengampu'];
                    if ($this->dataReport['idjabatan_dosen_pengajar'] > 0)
                    {
                        $nama_dosen_ttd=$this->dataReport['nama_dosen_pengajar'];
                        $nidn_jabatan_dosen_ttd=$this->dataReport['nama_jabatan_dosen_pengajar']. ' NIDN '.$this->dataReport['nidn_dosen_pengajar'];
                    }
                    $rpt->Cell(64, 5,$nama_dosen_ttd,0,0,'C');                    

                    $row+=5;
                    $rpt->setXY(3,$row);			
                    $rpt->Cell(64, 5, strtoupper($this->dataReport['jabfung_penandatangan_dpna']). ' NIDN '.$this->dataReport['nidn_penandatangan_dpna'],0,0,'C');
                    $rpt->Cell(64, 5, $kaprodi['nama_jabatan']. ' NIDN '.$kaprodi['nidn'],0,0,'C');
                    $rpt->Cell(64, 5,$nidn_jabatan_dosen_ttd,0,0,'C');                        
                    if ($i < ($jumlahpage-1)) {
                        $rpt->AddPage('P','F4');
                        $row=5;
                        $rpt->SetFont ('helvetica','B',8);
                        $rpt->setXY(3,$row);			
                        $rpt->Cell(13, 8, 'NO', 1, 0, 'C');				
                        $rpt->Cell(70, 8, 'NAMA', 1, 0, 'C');								
                        $rpt->Cell(10, 8, 'JK', 1, 0, 'C');							
                        $rpt->Cell(20, 8, 'NIM', 1, 0, 'C');				
                        $rpt->Cell(30, 8, 'NIRM', 1, 0, 'C');				
                        $rpt->Cell(20, 4, 'NILAI', 1, 0, 'C');								
                        $rpt->Cell(30, 8, 'KETERANGAN', 1, 0, 'C');
                        $row+=4;
                        $rpt->setXY(146,$row);
                        $rpt->Cell(10, 4, 'AM', 1, 0, 'C');
                        $rpt->Cell(10, 4, 'HM', 1, 0, 'C');																
                        $row+=4;				
                        $rpt->setXY(3,$row);	
                        $rpt->setFont ('helvetica','',8);                
                    } 
                    
                }
                
                $this->printOut("dpna_$kmatkul");
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"<br/>Daftar Peserta dan Nilai Akhir");
    }
    /**
     * digunakan untuk memprint Konversi Matakuliah
     * @param type $objNilai object
     */
    public function printKonversiMatakuliah ($objNilai) {         
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :          
                $this->setHeaderPT('J');
                $sheet=$this->rpt->getActiveSheet();
                $this->rpt->getDefaultStyle()->getFont()->setName('Arial');                
                $this->rpt->getDefaultStyle()->getFont()->setSize('9');                                    
                
                $sheet->mergeCells("A7:J7");
                $sheet->getRowDimension(7)->setRowHeight(20);
                $sheet->setCellValue("A7","TABEL KONVERSI");                                
                $styleArray=array(
								'font' => array('bold' => true,
                                                'size' => 16),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
							);
                $sheet->getStyle("A7:J7")->applyFromArray($styleArray);
                                 
                $sheet->mergeCells('B9:D9');		                
                $sheet->setCellValue('B9','Nama Lengkap :');
                $sheet->mergeCells('E9:H9');
                $sheet->setCellValue('E9',$this->dataReport['nama']);

                $sheet->mergeCells('B10:D10');		
                $sheet->setCellValue('B10','Tempat / Tanggal Lahir :');
                $sheet->mergeCells('E10:H10');
                $sheet->setCellValue('E10','-');

                $sheet->mergeCells('B11:D11');		
                $sheet->setCellValue('B11','Perguruan Tinggi Asal :');
                $sheet->mergeCells('E11:H11');
                $sheet->setCellValue('E11',$this->dataReport['nama_pt_asal'].' ('.$this->dataReport['kode_pt_asal'].')');

                $sheet->mergeCells('B12:D12');		
                $sheet->setCellValue('B12','Jenjang / Program Studi (LAMA) :');
                $sheet->mergeCells('E12:H12');
                $sheet->setCellValue('E12',$this->dataReport['nama_ps_asal'].' ('.$this->dataReport['kode_ps_asal'].') '.$this->dataReport['njenjang']);

                $sheet->mergeCells('B13:D13');
                $sheet->setCellValue('B13','Jenjang / Program Studi (BARU) :');
                $sheet->mergeCells('E13:H13');
                $sheet->setCellValue('E13',$this->dataReport['nama_ps']);
                
                $sheet->getRowDimension(16)->setRowHeight(25);                   
                $sheet->getColumnDimension('B')->setWidth(12);
                $sheet->getColumnDimension('C')->setWidth(40);
                $sheet->getColumnDimension('G')->setWidth(40);
                $sheet->getColumnDimension('F')->setWidth(12);
                                
                $sheet->mergeCells('A15:A16');
                $sheet->setCellValue('A15','NO');	                
                $sheet->mergeCells('B15:E15');
                $sheet->setCellValue('B15','NILAI PT ASAL ');                
                $sheet->setCellValue('B16','KODE MK');				                
                $sheet->setCellValue('C16','NAMA MK');								
                $sheet->setCellValue('D16','SKS');				
                $sheet->setCellValue('E16','NILAI HURUF');				
                $sheet->mergeCells('F15:J15');  				                                    
                $sheet->setCellValue('F15','KONVERSI NILAI PT BARU (DIAKUI)');  
                $sheet->setCellValue('F16','KODE MK');				                
                $sheet->setCellValue('G16','NAMA MK');								
                $sheet->setCellValue('H16','SKS');				
                $sheet->setCellValue('I16','NILAI HURUF');				
                $sheet->setCellValue('J16','NILAI ANGKA');				
                
                $styleArray=array(								
                                    'font' => array('bold' => true),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                       'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                                );																					 
                $sheet->getStyle("A15:J16")->applyFromArray($styleArray);
                $sheet->getStyle("A15:J16")->getAlignment()->setWrapText(true);
                
                $nilai=$objNilai->getNilaiKonversi($this->dataReport['iddata_konversi'],$this->dataReport['idkur']);                
                $row=17;     
                $jumlah_sks=0;
                $jumlah_matkul=0;
                while (list($k,$v)=each($nilai)) {
                    $sheet->setCellValue("A$row",$v['no']);                                                        
                    $sheet->setCellValue("B$row",$v['kmatkul_asal']);                    
                    $sheet->setCellValue("C$row",$v['matkul_asal']);                    
                    $sheet->setCellValue("D$row",$v['sks_asal']);
                    $sheet->setCellValue("E$row",$v['n_kual']);                    
                    $sheet->setCellValue("F$row",$objNilai->getKMatkul($v['kmatkul']));                    
                    $sheet->setCellValue("G$row",$v['nmatkul']);                    
                    $sheet->setCellValue("H$row",$v['sks']);
                    $sheet->setCellValue("I$row",$v['n_kual']);
                    $sheet->setCellValue("J$row",$objNilai->getAngkaMutu($v['n_kual']));
                    if ($v['n_kual'] != '') {
                        $jumlah_sks+=$v['sks'];
                        $jumlah_matkul++;		
                    }
                    $sheet->getRowDimension($row)->setRowHeight(22);		
                    $row+=1;
                }
                $row-=1;
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                       'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                                );																					 
                $sheet->getStyle("A17:J$row")->applyFromArray($styleArray);
                $sheet->getStyle("A17:J$row")->getAlignment()->setWrapText(true);
                
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                                );
                
                $sheet->getStyle("C17:C$row")->applyFromArray($styleArray);
                $sheet->getStyle("G17:G$row")->applyFromArray($styleArray);
                
                $row_awal=$row;
                $row+=2;
                $sheet->mergeCells("A$row:D$row");                
                $sheet->setCellValue('A'.$row,'Jumlah Matakuliah yang Terkonversi');                
                $sheet->setCellValue("E$row",$jumlah_matkul);                
                
                $row+=1;
                $sheet->mergeCells("A$row:D$row");                
                $sheet->setCellValue("A$row",'Jumlah SKS yang Terkonversi');                
                $sheet->setCellValue("E$row",$jumlah_sks); 		

                $row+=4;
                $sheet->mergeCells("F$row:I$row");                
                $sheet->setCellValue("F$row",'TANJUNGPINANG, '.$this->tgl->tanggal('j F Y'));

                $row+=1;
                $sheet->mergeCells("F$row:I$row");                
                $sheet->setCellValue("F$row",'KETUA PROGRAM STUDI');

                $row+=1;
                $sheet->mergeCells("F$row:I$row");                
                $sheet->setCellValue("F$row",$this->dataReport['nama_ps']);

                $row+=5;
                $sheet->mergeCells("F$row:I$row");                
                $sheet->setCellValue('F'.$row,$this->dataReport['nama_kaprodi']);

                $row+=1;
                $sheet->mergeCells("F$row:I$row");                     
                $sheet->setCellValue("F$row",'NIDN : '.$this->dataReport['nidn_kaprodi']);                

                $this->printOut("konversimatakuliah");
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"<br/>Konversi Matakuliah");
        
    }
    /**
     * digunakan untuk mencetak daftar peserta kelas mahasiswa untuk mengimport nilai
     * @return type void
     */
    public function printPesertaImportNilai () {
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :  
                $sheet=$this->rpt->getActiveSheet();
                $idkelas_mhs=$this->dataReport['idkelas_mhs'];
                $sheet->setCellValue('A1','ID');
                $sheet->setCellValue('B1','NAMA MHS');
                $sheet->setCellValue('C1','NIM');
                $sheet->setCellValue('D1','PR/QUIZ');
                $sheet->setCellValue('E1','TUGAS');
                $sheet->setCellValue('F1','UTS');
                $sheet->setCellValue('G1','UAS');
                $sheet->setCellValue('H1','ABSEN');
                
                $styleArray=array(
                                'font' => array('bold' => true),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
								'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
							);
                $sheet->getStyle("A1:H1")->applyFromArray($styleArray);
                $sheet->getStyle("A1:H1")->getAlignment()->setWrapText(true);
                  
                $str = "SELECT kmd.idkrsmatkul,vdm.nim,vdm.nama_mhs FROM kelas_mhs_detail kmd JOIN krsmatkul km ON (kmd.idkrsmatkul=km.idkrsmatkul) JOIN krs k ON (km.idkrs=k.idkrs) JOIN v_datamhs vdm ON (k.nim=vdm.nim) LEFT JOIN nilai_matakuliah nm ON (km.idkrsmatkul=nm.idkrsmatkul) WHERE kmd.idkelas_mhs=$idkelas_mhs AND km.batal=0 AND nm.idkrsmatkul IS NULL ORDER BY vdm.nama_mhs ASC";
                $this->db->setFieldTable(array('idkrsmatkul','nim','nama_mhs'));	
                $r=$this->db->getRecord($str);    
                $row_awal=2;
                $row=2;
                $sheet->getColumnDimension('A')->setWidth(10);
                $sheet->getColumnDimension('B')->setWidth(40);
                $sheet->getColumnDimension('C')->setWidth(15);
                while (list($k,$v)=each($r)) {
                    $sheet->setCellValue("A$row",$v['idkrsmatkul']);
                    $sheet->setCellValue("B$row",$v['nama_mhs']);
                    $sheet->setCellValueExplicit("C$row",$v['nim'],PHPExcel_Cell_DataType::TYPE_STRING);
                    $row+=1;
                }
                $row=$row-1;
                $styleArray=array(
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
								'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
							);
                $sheet->getStyle("A$row_awal:H$row")->applyFromArray($styleArray);
                $sheet->getStyle("A$row_awal:H$row")->getAlignment()->setWrapText(true);
                
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                                );
                $sheet->getStyle("B$row_awal:B$row")->applyFromArray($styleArray);
                $this->printOut('daftarisiannilai_'.$this->dataReport['kmatkul'].'_'.$this->dataReport['nama_kelas']);
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Daftar Isian Nilai Mahasiswa");
    }
     /**
     * digunakan untuk memprint Konversi Matakuliah
     * @param type $objNilai object
     */
    public function printFormatEvaluasiHasilBelajar ($objNilai) { 
        $idkelas_mhs=$this->dataReport['idkelas_mhs'];
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :          
                $this->setHeaderPT('J');
                $sheet=$this->rpt->getActiveSheet();
                $this->rpt->getDefaultStyle()->getFont()->setName('Arial');                
                $this->rpt->getDefaultStyle()->getFont()->setSize('9');                                    
                
                $sheet->mergeCells("A7:T7");
                $sheet->getRowDimension(7)->setRowHeight(20);
                $sheet->setCellValue("A7","FORMAT EVALUASI HASIL BELAJAR");                                
                $styleArray=array(
								'font' => array('bold' => true,
                                                'size' => 16),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
							);
                $sheet->getStyle("A7:T7")->applyFromArray($styleArray);
                                 
                $sheet->mergeCells('E9:F9');		                
                $sheet->setCellValue('E9','Kode Matakuliah');
                $sheet->setCellValue('G9',': '.$this->dataReport['kmatkul']);

                $sheet->mergeCells('E10:F10');		
                $sheet->setCellValue('E10','Nama Matakuliah');
                $sheet->setCellValue('G10',': '.$this->dataReport['nmatkul']);
                
                $sheet->mergeCells('E11:F11');		
                $sheet->setCellValue('E11','SKS');
                $sheet->setCellValueExplicit("G11",': '.$this->dataReport['sks'],PHPExcel_Cell_DataType::TYPE_STRING);
                                
                $sheet->mergeCells('E12:F12');		
                $sheet->setCellValue('E12','T.A / Semester');
                $sheet->setCellValue('G12',': '.$this->dataReport['tahun'].' / '.$this->dataReport['nama_semester']);

                $sheet->mergeCells('E13:F13');		
                $sheet->setCellValue('E13','Dosen Pengampu');
                $sheet->setCellValue('G13',': '.$this->dataReport['nama_dosen_matakuliah'].' ['.$this->dataReport['nidn_dosen_matakuliah'].') ');

                $sheet->mergeCells('E14:F14');
                $sheet->setCellValue('E14','Dosen Pengajar');
                $sheet->setCellValue('G14',': '.$this->dataReport['nama_dosen'].' ['.$this->dataReport['nidn'].') ');
                
                $sheet->mergeCells('E15:F15');
                $sheet->setCellValue('E15','Kelas');
                $sheet->setCellValue('G15',': '.$this->dataReport['namakelas']);
                
                $sheet->mergeCells('A17:A18');				
                $sheet->setCellValue('A17','NO');				
                $sheet->mergeCells('B17:E18');				
                $sheet->setCellValue('B17','NAMA');				
                $sheet->mergeCells('F17:F18');				
                $sheet->setCellValue('F17','JK');				
                $sheet->mergeCells('G17:G18');				
                $sheet->setCellValue('G17','NIM');				
                $sheet->mergeCells('H17:H18');				
                $sheet->setCellValue('H17','NIRM');	
                
                $sheet->mergeCells('I17:J17');				
                $sheet->setCellValue('I17','QUIZ ('.$this->dataReport['persen_quiz'].'%)');	
                
                $sheet->mergeCells('K17:L17');				
                $sheet->setCellValue('K17','TUGAS ('.$this->dataReport['persen_tugas'].'%)');	
                
                $sheet->mergeCells('M17:N17');				
                $sheet->setCellValue('M17','UTS ('.$this->dataReport['persen_uts'].'%)');
                
                $sheet->mergeCells('O17:P17');				
                $sheet->setCellValue('O17','UAS ('.$this->dataReport['persen_uas'].'%)');
                
                $sheet->mergeCells('Q17:R17');				
                $sheet->setCellValue('Q17','ABSEN ('.$this->dataReport['persen_absen'].'%)');
                
                $sheet->mergeCells('S17:T17');				
                $sheet->setCellValue('S17','NILAI AKHIR');
                
                $sheet->setCellValue('I18','NA');			
                $sheet->setCellValue('J18','%');
                $sheet->setCellValue('K18','NA');			
                $sheet->setCellValue('L18','%');	
                $sheet->setCellValue('M18','NA');			
                $sheet->setCellValue('N18','%');
                $sheet->setCellValue('O18','NA');			
                $sheet->setCellValue('P18','%');
                $sheet->setCellValue('Q18','NA');			
                $sheet->setCellValue('R18','%');
                $sheet->setCellValue('S18','AM');			
                $sheet->setCellValue('T18','HM');
                
                $sheet->getColumnDimension('A')->setWidth(10);
                $sheet->getColumnDimension('H')->setWidth(17);
                $sheet->getColumnDimension('I')->setWidth(6);
                $sheet->getColumnDimension('J')->setWidth(6);
                $sheet->getColumnDimension('K')->setWidth(6);
                $sheet->getColumnDimension('L')->setWidth(6);
                $sheet->getColumnDimension('M')->setWidth(6);
                $sheet->getColumnDimension('N')->setWidth(6);
                $sheet->getColumnDimension('O')->setWidth(6);
                $sheet->getColumnDimension('P')->setWidth(6);
                $sheet->getColumnDimension('Q')->setWidth(6);
                $sheet->getColumnDimension('R')->setWidth(6); 
                $sheet->getColumnDimension('S')->setWidth(8);
                $sheet->getColumnDimension('T')->setWidth(8);
                
                $styleArray=array(								
                                    'font' => array('bold' => true),
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                       'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                                );																					 
                $sheet->getStyle("A17:T18")->applyFromArray($styleArray);
                $sheet->getStyle("A17:T18")->getAlignment()->setWrapText(true);
                
                $str = "SELECT vkm.idkrsmatkul,vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,n.persentase_quiz, n.persentase_tugas, n.persentase_uts, n.persentase_uas, n.persentase_absen, n.nilai_quiz, n.nilai_tugas, n.nilai_uts, n.nilai_uas, n.nilai_absen, n.n_kuan,n.n_kual FROM kelas_mhs_detail kmd LEFT JOIN nilai_matakuliah n ON (n.idkrsmatkul=kmd.idkrsmatkul) JOIN v_krsmhs vkm ON (vkm.idkrsmatkul=kmd.idkrsmatkul) JOIN v_datamhs vdm ON (vkm.nim=vdm.nim) WHERE kmd.idkelas_mhs=$idkelas_mhs AND vkm.sah=1 AND vkm.batal=0 ORDER BY vdm.nama_mhs ASC";        
                $this->db->setFieldTable(array('idkrsmatkul','nim','nirm','nama_mhs','jk','persentase_quiz', 'persentase_tugas', 'persentase_uts', 'persentase_uas', 'persentase_absen', 'nilai_quiz', 'nilai_tugas', 'nilai_uts', 'nilai_uas', 'nilai_absen','n_kuan','n_kual'));
                $r=$this->db->getRecord($str);	
                $row_awal=19;
                $row=19;
                while (list($k,$v)=each($r)) { 			
                    $sheet->setCellValue("A$row",$v['no']);				
                    $sheet->mergeCells("B$row:E$row");				
                    $sheet->setCellValue("B$row",$v['nama_mhs']);			
                    $sheet->setCellValue("F$row",$v['jk']);		
                    $sheet->setCellValueExplicit("G$row",$v['nim'],PHPExcel_Cell_DataType::TYPE_STRING);	
                    $sheet->setCellValueExplicit("H$row",$v['nirm'],PHPExcel_Cell_DataType::TYPE_STRING);			
                    
                    $sheet->setCellValue("I$row",$v['nilai_quiz']);	
                    $sheet->setCellValue("J$row",($v['persentase_quiz'] > 0 || $v['nilai_quiz']!='') ? $v['persentase_quiz']*$v['nilai_quiz']:'');	
                    $sheet->setCellValue("K$row",$v['nilai_tugas']);
                    $sheet->setCellValue("L$row",($v['persentase_tugas'] > 0 || $v['nilai_tugas']!='') ? $v['persentase_tugas']*$v['nilai_tugas']:'');
                    $sheet->setCellValue("M$row",$v['nilai_uts']);
                    $sheet->setCellValue("N$row",($v['persentase_uts'] > 0 || $v['nilai_uts']!='') ? $v['persentase_uts']*$v['nilai_uts']:'');
                    $sheet->setCellValue("O$row",$v['nilai_uas']);
                    $sheet->setCellValue("P$row",($v['persentase_uas'] > 0 || $v['nilai_uas']!='') ? $v['persentase_uas']*$v['nilai_uas'] :'');
                    $sheet->setCellValue("Q$row",$v['nilai_absen']);
                    $sheet->setCellValue("R$row",($v['persentase_absen'] > 0 || $v['nilai_absen']!='') ? $v['persentase_absen']*$v['nilai_absen'] :'');
                    $sheet->setCellValue("S$row",$v['n_kuan']);	
                     $sheet->setCellValue("T$row",$v['n_kual']);	
                    $row+=1;
                }
                $row=$row-1;
                $styleArray=array(
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
								'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
							);
                $sheet->getStyle("A$row_awal:T$row")->applyFromArray($styleArray);
                $sheet->getStyle("A$row_awal:T$row")->getAlignment()->setWrapText(true);
                
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                                );
                $sheet->getStyle("B$row_awal:B$row")->applyFromArray($styleArray);
                
                $row+=3;
                $row_awaL=$row;
                $sheet->setCellValue("B$row",'Nilai Angka (NA)');
                $sheet->setCellValue("D$row",'Huruf Mutu (HM)');
                $sheet->setCellValue("F$row",'Angka Mutu (AM)');
                $sheet->setCellValue("N$row",$this->setup->getSettingValue('kota_pt').', '.$this->tgl->tanggal('d F Y'));
                $row+=1;
                $sheet->setCellValue("B$row",'85-100');
                $sheet->setCellValue("D$row",'A');
                $sheet->setCellValue("F$row",'4');
                $sheet->setCellValue("N$row",'Dosen Matakuliah');
                $row+=1;
                $sheet->setCellValue("B$row",'70-84');
                $sheet->setCellValue("D$row",'B');
                $sheet->setCellValue("F$row",'3');
                $row+=1;
                $sheet->setCellValue("B$row",'55-69');
                $sheet->setCellValue("D$row",'C');
                $sheet->setCellValue("F$row",'2');
                $row+=1;
                $sheet->setCellValue("B$row",'40-54');
                $sheet->setCellValue("D$row",'D');
                $sheet->setCellValue("F$row",'1');
                $row+=1;
                $sheet->setCellValue("B$row",'0-39');
                $sheet->setCellValue("D$row",'E');
                $sheet->setCellValue("F$row",'0');
                $sheet->setCellValue("N$row",$this->dataReport['nama_dosen']);
                
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                                );
                $sheet->getStyle("F$row_awal:F$row")->applyFromArray($styleArray);
                
                $this->printOut('formatevaluasihasilbelajar');
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Format Evaluasi Hasil Belajar");
    }
}