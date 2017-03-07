<?php
prado::using ('Application.logic.Logic_Report');
class Logic_ReportAkademik extends Logic_Report {	    
	public function __construct ($db) {
		parent::__construct ($db);	        
	}
    /**
     * digunakan untuk mencetak data master matakuliah
     */
    public function printMatakuliah ($objDemik) {
        $idkur=$this->dataReport['idkur'];        
        $nama_ps=$this->dataReport['nama_ps'];        
        $str = "SELECT ta FROM kurikulum WHERE idkur=$idkur";
        $this->db->setFieldTable(array('ta'));
        $data = $this->db->getRecord($str);
        $tahun_kurikulum=$data[1]['ta'];
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :                
                $this->setHeaderPT('Q'); 
                $sheet=$this->rpt->getActiveSheet();
                $this->rpt->getDefaultStyle()->getFont()->setName('Arial');                
                $this->rpt->getDefaultStyle()->getFont()->setSize('9');                                    
                
                $sheet->mergeCells("A7:P7");
                $sheet->getRowDimension(7)->setRowHeight(20);
                $sheet->setCellValue("A7","KURIKULUM TAHUN $tahun_kurikulum");                                
                
                $sheet->mergeCells("A8:Q8");
                $sheet->setCellValue("A8","PROGRAM STUDI $nama_ps");                                
                $sheet->getRowDimension(8)->setRowHeight(20);
                $styleArray=array(
								'font' => array('bold' => true,
                                                'size' => 16),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
							);
                $sheet->getStyle("A7:Q8")->applyFromArray($styleArray);
                
                $sheet->getRowDimension(10)->setRowHeight(20);                                                
                
                $sheet->getColumnDimension('C')->setWidth(12);
                $sheet->getColumnDimension('F')->setWidth(23);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(3);
                $sheet->getColumnDimension('L')->setWidth(12);
                $sheet->getColumnDimension('M')->setWidth(23);
                $sheet->getColumnDimension('Q')->setWidth(20);
                
                //field of column ganjil				
				$sheet->setCellValue('A10','SMT');				
				$sheet->setCellValue('B10','NO');
				$sheet->setCellValue('C10','KODE MK');				
				$sheet->mergeCells('D10:F10');
				$sheet->setCellValue('D10','MATA KULIAH');				
				$sheet->setCellValue('G10','SKS');				
				$sheet->setCellValue('H10','KETERANGAN');				
				
				//field of column genap				
				$sheet->setCellValue('J10','SMT');				
				$sheet->setCellValue('K10','NO');
				$sheet->setCellValue('L10','KODE MK');				
				$sheet->mergeCells('M10:O10');
				$sheet->setCellValue('M10','MATA KULIAH');				
				$sheet->setCellValue('P10','SKS');				
				$sheet->setCellValue('Q10','KETERANGAN');				
                
                $styleArray=array(
								'font' => array('bold' => true),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
								'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
							);
                $sheet->getStyle("A10:Q10")->applyFromArray($styleArray);
                $sheet->getStyle("A10:Q10")->getAlignment()->setWrapText(true);
                
                $row_ganjil=11;
				$row_genap=11;															
												
				$tambah_ganjil_row=false;		
				$tambah_genap_row=false;		
                $str = "SELECT m.kmatkul,m.nmatkul,m.sks,m.semester,m.idkonsentrasi,k.nama_konsentrasi,m.ispilihan,m.islintas_prodi FROM matakuliah m LEFT JOIN konsentrasi k ON (k.idkonsentrasi=m.idkonsentrasi) WHERE idkur=$idkur ORDER BY semester,kmatkul ASC";			                
                $this->db->setFieldTable(array('kmatkul','nmatkul','sks','semester','idkonsentrasi','nama_konsentrasi','ispilihan','islintas_prodi','aktif'));
                $data = $this->db->getRecord($str);
                
                $smt = $objDemik->getSemesterMatakuliahRomawi();
				for ($i=1; $i <= 8; $i+=1) {					
					if ($i%2==0) {//genap
						$tambah_genap_row=true;
						$no_semester=1;
						$row_smt_awal=$row_genap;												
						$ada_matkul=true;						
						$genap_total_sks=0;								
						foreach ($data as $k=>$v) {														
							if ($v['semester']==$i) {								
								if ($v['kmatkul'] == '') {
									$ada_matkul=false;
								}else {							                                    
									$sks=$v['sks'];									
									$sheet->setCellValue("K$row_genap",$no_semester);
									$sheet->setCellValue("L$row_genap",$objDemik->getKMatkul($v['kmatkul']));
									$sheet->mergeCells("M$row_genap:O$row_genap");
									$sheet->setCellValue("M$row_genap",$v['nmatkul']);
									$sheet->setCellValue("P$row_genap",$sks);
                                    $keterangan='-';
                                    if ($v['idkonsentrasi'] == 0) {
                                        if($v['islintas_prodi'] == 1){
                                            $keterangan='Matkul Lintas Prodi';                                            
                                        }elseif($v['ispilihan'] == 1) {
                                            $keterangan='Matkul Pilihan';                                            
                                        }
                                    }else{
                                        $keterangan='Matkul Konsentrasi';                                        
                                    }
									$sheet->setCellValue("Q$row_genap",$keterangan);
									$genap_total_sks += $sks;																	
								}
								$no_semester+=1;								
								$row_genap+=1;												
							}						
						}			
						if ($ada_matkul) {
							if ($row_genap <= $row_ganjil) {
								$row_genap=($row_genap+($row_ganjil-$row_genap))-1;
							}							
							$sheet->mergeCells("M$row_genap:O$row_genap");
							$sheet->setCellValue("M$row_genap",'Jumlah SKS');							
							$sheet->setCellValue("P$row_genap",$genap_total_sks);							
						
							$row_genap+=1;
							$row_smt_akhir=$row_genap-1;							
							
                            $sheet->mergeCells("J$row_smt_awal:J$row_smt_akhir");
							$sheet->setCellValue("J$row_smt_awal",$smt[$i]);
						}						
					}else {//ganjil				
						$tambah_ganjil_row=true;						
						$no_semester=1;
						$row_smt_awal=$row_ganjil;
						$ada_matkul=true;										
						$ganjil_total_sks=0;								
						foreach ($data as $r=>$s) {												
							if ($s['semester']==$i) {	
								if ($s['kmatkul'] == '') {
									$ada_matkul=false;
								}else {												
									$sks=$s['sks'];									
									$sheet->setCellValue("B$row_ganjil",$no_semester);
									$sheet->setCellValue("C$row_ganjil",$objDemik->getKMatkul($s['kmatkul']));
									$sheet->mergeCells("D$row_ganjil:F$row_ganjil");
									$sheet->setCellValue("D$row_ganjil",$s['nmatkul']);
									$sheet->setCellValue("G$row_ganjil",$sks);
                                    $keterangan='-';
                                    if ($s['idkonsentrasi'] == 0) {
                                        if($s['islintas_prodi'] == 1){
                                            $keterangan='Matkul Lintas Prodi';                                            
                                        }elseif($s['ispilihan'] == 1) {
                                            $keterangan='Matkul Pilihan';                                            
                                        }
                                    }else{
                                        $keterangan='Matkul Konsentrasi';                                        
                                    }
									$sheet->setCellValue("H$row_ganjil",$keterangan);									
									$ganjil_total_sks += $sks;																	
								}
                                $sheet->getRowDimension($row_ganjil)->setRowHeight(22);
								$no_semester+=1;								
								$row_ganjil+=1;
							}						
						}
						if ($ada_matkul) {							
                            $sheet->getRowDimension($row_ganjil)->setRowHeight(22);
							$sheet->mergeCells("D$row_ganjil:F$row_ganjil");
							$sheet->setCellValue("D$row_ganjil",'Jumlah SKS');							
							$sheet->setCellValue("G$row_ganjil",$ganjil_total_sks);							
							
							$row_ganjil+=1;							
							$row_smt_akhir=$row_ganjil-1;
                            $sheet->mergeCells("A$row_smt_awal:A$row_smt_akhir");
							$sheet->setCellValue("A$row_smt_awal",$smt[$i]);						
						}																	
					}
					if ($tambah_ganjil_row && $tambah_genap_row) {						
						$sheet->getRowDimension($row_ganjil)->setRowHeight(3);
						$sheet->mergeCells("A$row_ganjil:Q$row_ganjil");						
						$row_ganjil+=1;
						$row_genap+=1;
						$tambah_ganjil_row=false;
						$tambah_genap_row=false;
					}
				}	
				$row_akhir = (($row_ganjil <= $row_genap)?$row_genap:$row_ganjil)-1;
                $sheet->mergeCells("I9:I$row_akhir");
                
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                                                       'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                                );																					 
                $sheet->getStyle("A11:Q$row_akhir")->applyFromArray($styleArray);
                $sheet->getStyle("A11:Q$row_akhir")->getAlignment()->setWrapText(true);
                
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
                                );																					 
                $sheet->getStyle("A11:C$row_akhir")->applyFromArray($styleArray);
                $sheet->getStyle("G11:H$row_akhir")->applyFromArray($styleArray);
                $sheet->getStyle("J11:L$row_akhir")->applyFromArray($styleArray);
                $sheet->getStyle("P11:Q$row_akhir")->applyFromArray($styleArray);
                
                $this->printOut('daftarmatakuliah');
            break;
            case 'pdf' :
                                                
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Daftar Matakuliah $nama_ps");
    }
    /**
     * digunakan untuk mencetak data mahasiwa mahasiswa
     * @return type void
     */
    public function printDaftarMahasiswa ($objDemik,$objDMaster) {
        $kjur=$this->dataReport['kjur'];
        $nama_ps=$this->dataReport['nama_ps'];
        $tahun_masuk=$this->dataReport['tahun_masuk'];
        $nama_tahun=$this->dataReport['nama_tahun'];
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :    
                $this->setHeaderPT('S');                
                $sheet=$this->rpt->getActiveSheet();
                $this->rpt->getDefaultStyle()->getFont()->setName('Arial');                
                $this->rpt->getDefaultStyle()->getFont()->setSize('9');                                    
                
                $sheet->mergeCells("A7:S7");
                $sheet->getRowDimension(7)->setRowHeight(20);
                $sheet->setCellValue("A7","DAFTAR MAHASISWA");
                $sheet->mergeCells("A8:S8");
                $sheet->getRowDimension(8)->setRowHeight(20);
                $sheet->setCellValue("A8","PROGRAM STUDI $nama_ps TAHUN MASUK $nama_tahun");   
                
                $styleArray=array(
								'font' => array('bold' => true,
                                                'size' => 16),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
							);
                $sheet->getStyle("A7:S9")->applyFromArray($styleArray);
                
                $sheet->getRowDimension(11)->setRowHeight(25); 
                $sheet->getColumnDimension('C')->setWidth(3);
                $sheet->getColumnDimension('D')->setWidth(10);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(35);
                $sheet->getColumnDimension('G')->setWidth(8);
                $sheet->getColumnDimension('H')->setWidth(13);
                $sheet->getColumnDimension('I')->setWidth(35);
                $sheet->getColumnDimension('J')->setWidth(25);
                $sheet->getColumnDimension('K')->setWidth(18);
                $sheet->getColumnDimension('L')->setWidth(35);
                $sheet->getColumnDimension('M')->setWidth(10);
                $sheet->getColumnDimension('N')->setWidth(19);
                $sheet->getColumnDimension('O')->setWidth(40);
                $sheet->getColumnDimension('P')->setWidth(25);
                $sheet->getColumnDimension('Q')->setWidth(25);
                $sheet->getColumnDimension('R')->setWidth(15);
                
                $sheet->setCellValue('A11','NO');
                $sheet->mergeCells("B11:C11");
                $sheet->setCellValue('B11','NO. FORMULIR');
                $sheet->setCellValue('D11','NIM');
                $sheet->setCellValue('E11','NIRM');
                $sheet->setCellValue('F11','NAMA');
                $sheet->setCellValue('G11','JK');
                $sheet->setCellValue('H11','STATUS AKHIR');
                $sheet->setCellValue('I11','DOSEN WALI');
                $sheet->setCellValue('J11','TEMPAT LAHIR');
                $sheet->setCellValue('K11','TANGGAL LAHIR');
                $sheet->setCellValue('L11','NAMA IBU');
                $sheet->setCellValue('M11','AGAMA');
                $sheet->setCellValue('N11','NO. NIK');
                $sheet->setCellValue('O11','ALAMAT');
                $sheet->setCellValue('P11','KELURAHAN');
                $sheet->setCellValue('Q11','KECAMATAN');
                $sheet->setCellValue('R11','KELAS');
                $sheet->setCellValue('S11','KET.');
               
                $styleArray=array(
								'font' => array('bold' => true),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
								'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
							);
                $sheet->getStyle("A11:S11")->applyFromArray($styleArray);
                $sheet->getStyle("A11:S11")->getAlignment()->setWrapText(true);
                
                $str = "SELECT fp.no_formulir,rm.nim,rm.nirm,rm.no_formulir,fp.nama_mhs,fp.jk,rm.k_status,rm.iddosen_wali,fp.tempat_lahir,fp.tanggal_lahir,fp.nama_ibu_kandung,a.nama_agama,fp.nik,fp.alamat_rumah,fp.kelurahan,fp.kecamatan,rm.idkelas FROM formulir_pendaftaran fp JOIN register_mahasiswa rm ON (fp.no_formulir=rm.no_formulir) LEFT JOIN agama a ON (a.idagama=fp.idagama) WHERE rm.kjur='$kjur' AND fp.ta=$tahun_masuk ORDER BY fp.nama_mhs ASC,rm.idkelas ASC";
                $this->db->setFieldTable(array('no_formulir','nim','nirm','no_formulir','nama_mhs','jk','k_status','iddosen_wali','tempat_lahir','tanggal_lahir','nama_ibu_kandung','nama_agama','nik','alamat_rumah','kelurahan','kecamatan','idkelas'));	
                $r = $this->db->getRecord($str);
                $row=12;
                while (list($k,$v)=each ($r)) {            
                    $sheet->setCellValue("A$row",$v['no']);
                    $sheet->mergeCells("B$row:C$row");
                    $sheet->setCellValue("B$row",$v['no_formulir']);
                    $sheet->setCellValueExplicit("D$row",$v['nim'],PHPExcel_Cell_DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit("E$row",$v['nirm'],PHPExcel_Cell_DataType::TYPE_STRING);
                    $sheet->setCellValue("F$row",$v['nama_mhs']);
                    $sheet->setCellValue("G$row",$v['jk']);
                    $sheet->setCellValue("H$row",$objDMaster->getNamaStatusMHSByID($v['k_status']));
                    $sheet->setCellValue("I$row",$objDMaster->getNamaDosenWaliByID($v['iddosen_wali']));
                    $sheet->setCellValue("J$row",$v['tempat_lahir']);
                    $sheet->setCellValue("K$row",$this->tgl->tanggal('j F Y',$v['tanggal_lahir']));
                    $sheet->setCellValue("L$row",$v['nama_ibu_kandung']);
                    $sheet->setCellValue("M$row",$v['nama_agama']);
                    $sheet->setCellValueExplicit("N$row",$v['nik'],PHPExcel_Cell_DataType::TYPE_STRING);                    
                    $sheet->setCellValue("O$row",$v['alamat_rumah']);
                    $sheet->setCellValue("P$row",$v['kelurahan']);
                    $sheet->setCellValue("Q$row",$v['kecamatan']);
                    $sheet->setCellValue("R$row",$objDMaster->getNamaKelasByID($v['idkelas']));
                    $sheet->setCellValue("S$row",$v['keterangan']);
                    $row+=1;
                } 
                $row-=1;
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                                       'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                                );																					 
                $sheet->getStyle("A12:S$row")->applyFromArray($styleArray);
                $sheet->getStyle("A12:S$row")->getAlignment()->setWrapText(true);
                
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                                );																					 
                $sheet->getStyle("F12:F$row")->applyFromArray($styleArray);
                $sheet->getStyle("I12:M$row")->applyFromArray($styleArray);
                $sheet->getStyle("O12:R$row")->applyFromArray($styleArray);
                $this->printOut("daftarmahasiswa$kjur");
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Daftar Mahasiswa $nama_ps tahun masuk $nama_tahun");
    }
    /**
     * digunakan untuk mencetak daftar hadir mahasiswa
     * @return type void
     */
    public function printDaftarHadirMahasiswa () {
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :  
                $this->setHeaderPT('X'); 
                $sheet=$this->rpt->getActiveSheet();
                $this->rpt->getDefaultStyle()->getFont()->setName('Arial');                
                $this->rpt->getDefaultStyle()->getFont()->setSize('9');                                    
                
                $sheet->mergeCells("A7:V7");
                $sheet->getRowDimension(7)->setRowHeight(20);
                $sheet->setCellValue("A7","DAFTAR HADIR MAHASISWA");
                
                $styleArray=array(
								'font' => array('bold' => true,
                                                'size' => 16),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
							);
                $sheet->getStyle("A7:A7")->applyFromArray($styleArray);
                
                $sheet->getRowDimension(9)->setRowHeight(20);
                $sheet->getRowDimension(10)->setRowHeight(20);
                $sheet->getRowDimension(11)->setRowHeight(20);
                $sheet->getRowDimension(12)->setRowHeight(20);
                
                //field of column left				
				$sheet->setCellValue('B9','MATA KULIAH/KELAS');				
				$sheet->setCellValue('B10','PROGRAM STUDI/JENJANG');
				$sheet->setCellValue('B11','SEMESTER/TAHUN AKADEMIK');				
				$sheet->setCellValue('B12','DOSEN MATAKULIAH');
                
                $sheet->setCellValue('D9',': '.$this->dataReport['nmatkul'].' / '.$this->dataReport['namakelas']);				
				$sheet->setCellValue('D10',': '.$this->dataReport['nama_prodi']);
				$sheet->setCellValue('D11',': '.$this->dataReport['nama_semester']. ' - '.$this->dataReport['nama_tahun']);			
				$sheet->setCellValue('D12',': '.$this->dataReport['nama_dosen_matakuliah']. ' ['.$this->dataReport['nidn_dosen_matakuliah'].']');
                
                $sheet->setCellValue('M9','KODE MATAKULIAH');				
				$sheet->setCellValue('M10','JUMLAH SKS');
				$sheet->setCellValue('M11','DOSEN PENGAJAR');				
				$sheet->setCellValue('M12','JUMLAH MAHASISWA');
                
                $sheet->setCellValue('P9',': '.$this->dataReport['kmatkul']);				
				$sheet->setCellValue('P10',': '.$this->dataReport['sks']);
				$sheet->setCellValue('P11',': '.$this->dataReport['nama_dosen']. ' ['.$this->dataReport['nidn'].']');			
				$sheet->setCellValue('P12',': '.$this->dataReport['jumlah_peserta']);
                
                $sheet->setCellValue('V14','Halaman ke 1');
                $sheet->getRowDimension(15)->setRowHeight(20);
                $sheet->mergeCells("A15:A16");
				$sheet->setCellValue('A15','NO');
                $sheet->mergeCells("B15:C16");
                $sheet->setCellValue('B15','NAMA MAHASISWA');
                $sheet->mergeCells("D15:D16");
                $sheet->setCellValue('D15','L/P');
                $sheet->mergeCells("E15:E16");
                $sheet->setCellValue('E15','NIM');
                $sheet->mergeCells("F15:U15");
                $sheet->setCellValue('F15','PARAF TANDA HADIR KULIAH / PRAKTIKUM KE'); 
                $sheet->mergeCells("V15:V16");
                $sheet->setCellValue('V15','JUMLAH HADIR');               
                $sheet->mergeCells("W15:W16");
                $sheet->setCellValue('W15','%');                
                $sheet->mergeCells("X15:X16");
                $sheet->setCellValue('X15','KETR.'); 
                
                $sheet->getColumnDimension('C')->setWidth(23);
                $sheet->getColumnDimension('D')->setWidth(5);
                $sheet->getColumnDimension('E')->setWidth(12);
                $sheet->getColumnDimension('F')->setWidth(7);
                $sheet->getColumnDimension('G')->setWidth(7);
                $sheet->getColumnDimension('H')->setWidth(7);
                $sheet->getColumnDimension('I')->setWidth(7);
                $sheet->getColumnDimension('J')->setWidth(7);
                $sheet->getColumnDimension('K')->setWidth(7);
                $sheet->getColumnDimension('L')->setWidth(7);
                $sheet->getColumnDimension('M')->setWidth(7);
                $sheet->getColumnDimension('N')->setWidth(7);
                $sheet->getColumnDimension('O')->setWidth(7);
                $sheet->getColumnDimension('P')->setWidth(7);
                $sheet->getColumnDimension('Q')->setWidth(7);
                $sheet->getColumnDimension('R')->setWidth(7);
                $sheet->getColumnDimension('S')->setWidth(7);
                $sheet->getColumnDimension('T')->setWidth(7);
                $sheet->getColumnDimension('U')->setWidth(7);
                $sheet->getColumnDimension('W')->setWidth(7);
                
                $sheet->getRowDimension(16)->setRowHeight(20);
                $sheet->setCellValue('F16',1);
                $sheet->setCellValue('G16',2);
                $sheet->setCellValue('H16',3);
                $sheet->setCellValue('I16',4);
                $sheet->setCellValue('J16',5);
                $sheet->setCellValue('K16',6);
                $sheet->setCellValue('L16',7);
                $sheet->setCellValue('M16',8);
                $sheet->setCellValue('N16',9);
                $sheet->setCellValue('O16',10);
                $sheet->setCellValue('P16',11);
                $sheet->setCellValue('Q16',12);
                $sheet->setCellValue('R16',13);
                $sheet->setCellValue('S16',14);
                $sheet->setCellValue('T16',15);
                $sheet->setCellValue('U16',16);
                
                
                $styleArray=array(
								'font' => array('bold' => true),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
								'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
							);
                $sheet->getStyle("A15:X16")->applyFromArray($styleArray);
                $sheet->getStyle("A15:X16")->getAlignment()->setWrapText(true);
                
                $idkelas_mhs=$this->dataReport['idkelas_mhs'];
                $str = "SELECT kmd.idkrsmatkul,vdm.nim,vdm.nirm,vdm.nama_mhs,vdm.jk,vdm.tahun_masuk,k.sah FROM kelas_mhs_detail kmd,krsmatkul km,krs k,v_datamhs vdm WHERE kmd.idkrsmatkul=km.idkrsmatkul AND km.idkrs=k.idkrs AND k.nim=vdm.nim AND kmd.idkelas_mhs=$idkelas_mhs AND km.batal=0 ORDER BY vdm.nama_mhs ASC";
                
                $this->db->setFieldTable(array('nim','nirm','nama_mhs','jk','tahun_masuk','sah'));	
                $r=$this->db->getRecord($str);       
                $row_awal=17;
                $row=17;
                while (list($k,$v)=each($r)) {
                    $sheet->getRowDimension($row)->setRowHeight(17);
                    $sheet->setCellValue("A$row",$v['no']);
                    $sheet->mergeCells("B$row:C$row");
                    $sheet->setCellValue("B$row",$v['nama_mhs']);
                    $sheet->setCellValue("D$row",$v['jk']);
                    $sheet->setCellValueExplicit("E$row",$v['nim'],PHPExcel_Cell_DataType::TYPE_STRING);
                    $row+=1;
                }
                $row=$row-1;
                $styleArray=array(
								'font' => array('bold' => true),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
								'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
							);
                $sheet->getStyle("A$row_awal:X$row")->applyFromArray($styleArray);
                $sheet->getStyle("A$row_awal:X$row")->getAlignment()->setWrapText(true);
                
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                                );
                $sheet->getStyle("B$row_awal:B$row")->applyFromArray($styleArray);
                $sheet->getStyle("B$row_awal:B$row")->getAlignment()->setWrapText(true);
                
                $row+=2;
                $sheet->setCellValue("A$row",'Catatan :');
                $sheet->setCellValue("S$row",'Tanjungpinang, '.$this->tgl->tanggal('d F Y'));
                $row+=1;
                $sheet->setCellValue("A$row",'1');
                $sheet->setCellValue("B$row",'Mahasiswa tidak diperkenankan menambah daftar hadir yang telah dikeluarkan.');
                $sheet->setCellValue("S$row",'DOSEN Ybs, ');
                $row+=1;
                $sheet->setCellValue("A$row",'2');
                $sheet->setCellValue("B$row",'Tingkat kehadiran mahasiswa yang diperbolehkan mengikuti UAS tanpa syarat minimal 75% .');
                $row+=1;
                $sheet->setCellValue("A$row",'3');
                $sheet->setCellValue("B$row",'Daftar hadir dikembalikan ke sekretariat setiap kali selesai perkuliahan.');
                $row+=2;
                $sheet->setCellValue("S$row",$this->dataReport['nama_dosen']);                
                $this->printOut('daftarhadirmahasiswa');
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Daftar Hadir Mahasiswa");
    }
    /**
     * digunakan untuk mencetak rekap status mahasiswa
     * @return type void
     */
    public function printRekapStatusMahasiswa ($objDemik,$objDMaster) {
        $kjur=$this->dataReport['kjur'];
        $nama_ps=$this->dataReport['nama_ps'];
        $ta1=$this->dataReport['ta1'];
        $ta2=$this->dataReport['ta2'];                
        $nama_tahun1=$this->dataReport['nama_tahun1'];                
        $nama_tahun2=$this->dataReport['nama_tahun2'];   
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :                
                $this->setHeaderPT('Q'); 
                $sheet=$this->rpt->getActiveSheet();
                $this->rpt->getDefaultStyle()->getFont()->setName('Arial');                
                $this->rpt->getDefaultStyle()->getFont()->setSize('9');                                    
                
                $sheet->mergeCells("A7:Q7");
                $sheet->getRowDimension(7)->setRowHeight(20);
                $sheet->setCellValue("A7","LAPORAN JUMLAH STATUS MAHASISWA");  
                
                $sheet->mergeCells("A8:Q8");
                $sheet->getRowDimension(8)->setRowHeight(20);
                $sheet->setCellValue("A8","PERIODE TAHUN AKADEMIK $nama_tahun1 S.D TAHUN AKADEMIK $nama_tahun2");   
                
                $sheet->mergeCells("A9:Q9");
                $sheet->setCellValue("A9","PROGRAM STUDI $nama_ps");                                
                $sheet->getRowDimension(9)->setRowHeight(20); 
                
                $styleArray=array(
								'font' => array('bold' => true,
                                                'size' => 16),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
							);
                $sheet->getStyle("A7:Q9")->applyFromArray($styleArray);
                
                $sheet->getRowDimension(11)->setRowHeight(20);                                                
                
//                $sheet->getColumnDimension('C')->setWidth(12);
//                $sheet->getColumnDimension('F')->setWidth(23);
//                $sheet->getColumnDimension('H')->setWidth(20);
//                $sheet->getColumnDimension('I')->setWidth(3);
//                $sheet->getColumnDimension('L')->setWidth(12);
//                $sheet->getColumnDimension('M')->setWidth(23);
//                $sheet->getColumnDimension('Q')->setWidth(20);
                
                //field of column ganjil
                $sheet->mergeCells('A11:A12');
				$sheet->setCellValue('A11','T.A');
                $sheet->mergeCells('B11:B12');
				$sheet->setCellValue('B11','SMT');
                $sheet->mergeCells('C11:c12');
				$sheet->setCellValue('C11','KELAS');				
				
                $sheet->mergeCells('D11:F11');
                $sheet->setCellValue('D11','AKTIF');
				$sheet->setCellValue('D12','L');				
				$sheet->setCellValue('E12','P');				
				$sheet->setCellValue('F12','L + P');		
                
                $sheet->mergeCells('G11:I11');
                $sheet->setCellValue('G11','NON-AKTIF');
				$sheet->setCellValue('G12','L');				
				$sheet->setCellValue('H12','P');				
				$sheet->setCellValue('I12','L + P');
                
                $sheet->mergeCells('J11:M11');
                $sheet->setCellValue('J11','CUTI');
				$sheet->setCellValue('J12','L');				
				$sheet->setCellValue('K12','P');				
				$sheet->setCellValue('L12','L + P');
                                
                $sheet->mergeCells('N11:P11');
                $sheet->setCellValue('N11','KELUAR');
				$sheet->setCellValue('N12','L');				
				$sheet->setCellValue('O12','P');				
				$sheet->setCellValue('P12','L + P');
                
                $sheet->mergeCells('Q11:S11');
                $sheet->setCellValue('Q11','DROP OUT');
				$sheet->setCellValue('Q12','L');				
				$sheet->setCellValue('R12','P');				
				$sheet->setCellValue('S12','L + P');
                
                $sheet->mergeCells('T11:W11');
                $sheet->setCellValue('T11','LULUS');
				$sheet->setCellValue('T12','L');				
				$sheet->setCellValue('U12','P');				
				$sheet->setCellValue('V12','L + P');
                
                $styleArray=array(
								'font' => array('bold' => true),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
								'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
							);
                $sheet->getStyle("A11:V12")->applyFromArray($styleArray);
                $sheet->getStyle("A11:V12")->getAlignment()->setWrapText(true);
                
                
                $str = "SELECT ta,idsmt,idkelas FROM rekap_status_mahasiswa WHERE (ta >= $ta1 AND ta <= $ta2) AND kjur=$kjur GROUP BY ta,idsmt,idkelas";
                $this->db->setFieldTable(array('ta','idsmt','idkelas'));	
                $r = $this->db->getRecord($str);  
                
                $result=array();
                if (isset($r[1])) {
                    $data =array();
                    while (list($k,$v)=each ($r)) {            
                        $index=$v['ta'].$v['idsmt'].$v['idkelas'];                        
                        $data[$index]=array();
                    }  
                    $dataaktif=$objDemik->getRekapStatusMHS($kjur,$ta1,$ta2,'A');
                    $row=13;
                    while (list($m,$n)=each ($data)) {            
                        $sheet->setCellValue("D$row",$dataaktif[$m]['jumlah_pria']);				
                        $sheet->setCellValue("E$row",$dataaktif[$m]['jumlah_wanita']);				
                        $sheet->setCellValue("F$row",'L + P');	
                        $row+=1;
                    } 
                    $styleArray=array(
								'font' => array('bold' => true),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
								'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
							);
                    $sheet->getStyle("A13:V$row")->applyFromArray($styleArray);
                    $sheet->getStyle("A13:V$row")->getAlignment()->setWrapText(true);
                }
                $this->printOut('rekapstatusmahasiswa');
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Rekap Status Mahasiswa");
    }
    /**
     * cetak daftar peserta matakuliah
     * @param type $objDMaster
     */
    public function printPesertaMatakuliah($objDMaster) {
        $idkelas=$this->dataReport['idkelas'];
        switch ($this->getDriver()) {
            case 'excel2003' :               
            case 'excel2007' :    
                $this->setHeaderPT('G');                
                $sheet=$this->rpt->getActiveSheet();
                $this->rpt->getDefaultStyle()->getFont()->setName('Arial');                
                $this->rpt->getDefaultStyle()->getFont()->setSize('9');                                    
                
                $sheet->mergeCells("A7:G7");
                
                $sheet->getRowDimension(7)->setRowHeight(20);
                $sheet->setCellValue('A7','DAFTAR PESERTA MATAKULIAH');
                $sheet->mergeCells("A8:B8");
                $sheet->setCellValue('A8','KODE');
                $sheet->setCellValue('C8',$this->dataReport['kmatkul']);
                
                $sheet->mergeCells("A9:B9");
                $sheet->setCellValue('A9','NAMA');
                $sheet->setCellValue('C9',$this->dataReport['nmatkul']);
                
                $sheet->mergeCells("A10:B10");
                $sheet->setCellValue('A10','SKS');
                $sheet->setCellValue('C10',$this->dataReport['sks']);
                
                $sheet->mergeCells("A11:B11");
                $sheet->setCellValue('A11','SEMESTER');
                $sheet->setCellValue('C11',$this->dataReport['nama_semester']);
                
                 $sheet->mergeCells("A12:B12");
                $sheet->setCellValue('A12','T.A');
                $sheet->setCellValue('C12',$this->dataReport['nama_tahun']);

                $styleArray=array(
								'font' => array('bold' => true,'size' => 16),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
							);
                $sheet->getStyle("A7:A7")->applyFromArray($styleArray);
                //$sheet->getRowDimension(9)->setRowHeight(25); 
                $sheet->getColumnDimension('A')->setWidth(10);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(35);
                $sheet->getColumnDimension('D')->setWidth(30);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(20);
                $sheet->setCellValue('A14','NO');
                $sheet->setCellValue('B14','NIM');
                $sheet->setCellValue('C14','NAMA');
                $sheet->setCellValue('D14','KELAS');
                $sheet->setCellValue('E14','TAHUN');
                $sheet->setCellValue('F14','KET');
                $styleArray=array(
								'font' => array('bold' => true),
								'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												   'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
								'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
							);
                $sheet->getStyle("A14:F14")->applyFromArray($styleArray);
                $sheet->getStyle("A14:F14")->getAlignment()->setWrapText(true);
                    
                $str_display='';
                $id=$this->dataReport['idpenyelenggaraan'];
                if($idkelas=='none')
                {
                    $str_display="SELECT vkm.nim,vdm.nama_mhs,vdm.idkelas,vdm.jk,vdm.tahun_masuk,vkm.batal,vkm.sah FROM v_krsmhs vkm,v_datamhs vdm WHERE vkm.nim=vdm.nim AND idpenyelenggaraan='$id'";
                }
                else
                {
                    $str_display="SELECT vkm.nim,vdm.nama_mhs,vdm.idkelas,vdm.jk,vdm.tahun_masuk,vkm.batal,vkm.sah FROM v_krsmhs vkm,v_datamhs vdm WHERE vkm.nim=vdm.nim AND idpenyelenggaraan='$id' AND vdm.idkelas='$idkelas'";
                }
               // $str = "SELECT vkm.nim,vdm.nama_mhs,vdm.idkelas,vdm.jk,vdm.tahun_masuk,vkm.batal,vkm.sah FROM v_krsmhs vkm,v_datamhs vdm WHERE vkm.nim=vdm.nim AND idpenyelenggaraan='$id' AND vdm.idkelas='$idkelas'";
                $this->db->setFieldTable(array('nim','nama_mhs','idkelas','jk','tahun_masuk','batal','sah'));	
                $r = $this->db->getRecord($str_display);
                $result=array();
                
                $row=15;
                
                while (list($k,$v)=each ($r)) {            
                    $sheet->setCellValue("A$row",$v['no']);
                    $sheet->setCellValue("B$row",$v['nim']);
                    $sheet->setCellValue("C$row",$v['nama_mhs']);
                    //$sheet->setCellValue("E$row",$v['idkelas']);
                    $sheet->setCellValue("D$row",$objDMaster->getNamaKelasByID($v['idkelas']));
                    $sheet->setCellValue("E$row",$v['tahun_masuk']);
                    $status='belum disahkan';
                    if ($v['sah']==1 && $v['batal']==0) {
                        $status='sah';
                    }elseif($v['sah']==1 && $v['batal']==1){
                        $status='batal';
                    }
                    $v['status']=$status;
                    $result[$k]=$v;
                    $sheet->setCellValue("F$row",$status);
                    $row+=1;
                } 
                $row-=1;
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                                    'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))
                                );																					 
                $sheet->getStyle("A14:F$row")->applyFromArray($styleArray);
                $sheet->getStyle("A14:F$row")->getAlignment()->setWrapText(true);
                
                $styleArray=array(								
                                    'alignment' => array('horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
                                );																					 
                $sheet->getStyle("C15:C$row")->applyFromArray($styleArray);
                $sheet->getStyle("C10:C10")->applyFromArray($styleArray);
                $this->printOut("daftarpeserta$tahun_masuk");
            break;
        }
        $this->setLink($this->dataReport['linkoutput'],"Daftar Peserta");
    }
}
?>

