<?php
prado::using ('Application.pagecontroller.m.perkuliahan.CDetailKRSMerdeka');
class DetailKRSMerdeka extends CDetailKRSMerdeka {		
    public function onLoad($param) {
		parent::onLoad($param);	
        if (!$this->IsPostBack&&!$this->IsCallback) {
            try {
                if ($_SESSION['currentPageKRSMerdeka']['DataMHS']['idkelas'] == 'C') {
                    $idkrs=$_SESSION['currentPageKRSMerdeka']['DataKRS']['krs']['idkrs'];
                    throw new Exception("KRS dengan ID ($idkrs) terdaftar di KRS Ekstension.");
                }
            } catch (Exception $e) {
                $this->idProcess='view';	
                $this->errorMessage->Text=$e->getMessage();
            }            
        }        
	} 
}