<?php
class MainPageK extends MainPage {      
    /**     
     * show page rekening [datamaster]
     */
    public $showRekening=false;
    /**     
     * show page komponen biaya per TA [datamaster]
     */
    public $showKombiPerTA=false;
    /**     
     * show page pembayaran 
     */
    public $showMenuPembayaran=false;
    /**     
     * show page pembayaran mahasiswa baru [pembayaran]
     */
    public $showPembayaranMahasiswaBaru=false;
    /**     
     * show page pembayaran semester Ganjil [pembayaran]
     */
    public $showPembayaranSemesterGanjil=false;
    /**     
     * show page pembayaran semester Fenap [pembayaran]
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
    /**     
     * show page pembayaran semester Ganjil [pembayaran]
     */
    public $showPembayaranPiutangSemesterGanjil=false;
    /**     
     * show page pembayaran semester Fenap [pembayaran]
     */
    public $showPembayaranPiutangSemesterGenap=false;
    /**     
     * show page rekap pembayaran ganjil [report]
     */
    public $showReportRekapPembayaranGanjil=false;
    /**     
     * show page piutang jangka pendek [report]
     */
    public $showReportPiutangJangkaPendek=false;
	public function onLoad ($param) {		
		parent::onLoad($param);				
        if (!$this->IsPostBack&&!$this->IsCallBack) {	
            
        }
	}   
}
?>