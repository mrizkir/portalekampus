<?php
class MainPageMHS extends MainPage {  
    /**     
     * show daftar kampus merdeka [kemahasiswaan]
     */
    public $showDaftarKampusMerdeka=false;
    /**     
     * show daftar konsentrasi [kemahasiswaan]
     */
    public $showDaftarKonsentrasi=false;    
    /**     
     * show skripsi [akademik]
     */
    public $showSkripsi=false;   
    /**
     * show page konversi sementara [akademik nilai]
     */
    public $showKonversiMatakuliah=false;
    /**     
     * show page pembayaran mahasiswa baru [pembayaran]
     */
    public $showPembayaranMahasiswaBaru=false;
    /**     
     * show page pembayaran semester Ganjil [pembayaran]
     */
    public $showPembayaranSemesterGanjil=false;
    /**     
     * show page pembayaran semester Genap [pembayaran]
     */
    public $showPembayaranSemesterGenap=false;
    /**     
     * show page pembayaran semester Pendek [pembayaran]
     */
    public $showPembayaranSemesterPendek=false;
    /**     
     * show page pembayaran Cuti semester Ganjil [pembayaran]
     */
    public $showPembayaranCutiSemesterGanjil=false;
     /**     
     * show page pembayaran Cuti semester Genap [pembayaran]
     */
    public $showPembayaranCutiSemesterGenap=false;
	public function onLoad ($param) {		
		parent::onLoad($param);				
        if (!$this->IsPostBack&&!$this->IsCallBack) {	
            
        }
	}   
}