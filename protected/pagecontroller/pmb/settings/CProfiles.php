<?php
prado::using ('Application.MainPagePMB');
class CProfiles extends MainPagePMB {    
	public function onLoad($param) {		
		parent::onLoad($param);		
        $this->showProfiles=true;        
		if (!$this->IsPostBack&&!$this->IsCallBack) {	
            if (!isset($_SESSION['currentPageCache'])||$_SESSION['currentPageCache']['page_name']!='pmb.settings.Profiles') {
				$_SESSION['currentPageCache']=array('page_name'=>'pmb.settings.Profiles','page_num'=>0);												
			}            
            $this->populateData ();
		}
        
	}   
    public function populateData () {        
        $this->imgPhotoUser->ImageUrl=$_SESSION['foto']; 
        $this->cmbTheme->DataSource=$this->setup->getListThemes();
        $this->cmbTheme->Text=$_SESSION['theme'];
        $this->cmbTheme->DataBind();
    }
    
    public function saveData ($sender,$param) {
        if ($this->IsValid) {
            $theme=$this->cmbTheme->Text;
            $_SESSION['theme']=$theme;
            $userid=$this->Pengguna->getDataUser('userid');
            $str = "UPDATE user SET theme='$theme' WHERE userid=$userid";            
            $this->DB->updateRecord($str);
            $this->redirect('settings.Profiles',true);
        }
    }

    public function uploadPhotoProfile ($sender,$param) {
		if ($sender->getHasFile()) {
            $this->lblTipeFileError->Text='';
            $mime=$sender->getFileType();
            if($mime!="image/png" && $mime!="image/jpg" && $mime!="image/jpeg"){
                $error =  '<div class="alert alert-warning">                
                            <p><strong>Error:</strong>File ini bukan tipe gambar</p>
                        </div>'; 
                $this->lblTipeFileError->Text=$error;
                return;
            }         

            if($mime=="image/png")	{
                if(!(imagetypes() & IMG_PNG)) {
                    $error =  '<div class="alert alert-warning">                
                            <p><strong>Error:</strong>missing png support in gd library.</p>
                        </div>'; 
                    $this->lblTipeFileError->Text=$error;                    
                    return;
                }
            }
            if(($mime=="image/jpg" || $mime=="image/jpeg")){
                if(!(imagetypes() & IMG_JPG)){                    
                    $error =  '<div class="alert alert-warning">                
                            <p><strong>Error:</strong>missing jpeg support in gd library.</p>
                        </div>'; 
                    $this->lblTipeFileError->Text=$error;
                    return;
                }
            }
            $filename=substr(hash('sha512',rand()),0,8);
            $name=$sender->FileName;
            $part=$this->setup->cleanFileNameString($name);            
            $path="resources/userimages/$filename-$part";
            $sender->saveAs($path);            
            chmod(BASEPATH."/$path",0644); 
            $this->imgPhotoUser->ImageUrl=$path; 
            $username=$this->Pengguna->getDataUser('username');
            $this->DB->updateRecord("UPDATE user SET foto='$path' WHERE username='$username'");
            $this->DB->updateRecord("UPDATE user SET foto='$path' WHERE username='$username'");
            $_SESSION['foto']=$path;
        }else {                    
            //error handling
            switch ($sender->ErrorCode){
                case 1:
                    $err="file size too big (php.ini).";
                break;
                case 2:
                    $err="file size too big (form).";
                break;
                case 3:
                    $err="file upload interrupted.";
                break;
                case 4:
                    $err="no file chosen.";
                break;
                case 6:
                    $err="internal problem (missing temporary directory).";
                break;
                case 7:
                    $err="unable to write file on disk.";
                break;
                case 8:
                    $err="file type not accepted.";
                break;
            }
            $error =  '<div class="alert alert-warning">                
                            <p><strong>Error:</strong>'.$err.'</p>
                        </div>';   
            $this->lblTipeFileError->Text=$error;
            return;   
        }
    }

    public function saveDataPassword ($sender,$param) {
        if ($this->IsValid) {
            $userid=$this->Pengguna->getDataUser('userid');
            if ($this->txtPassword->Text != '') {                
                $data=$this->Pengguna->createHashPassword($this->txtPassword->Text);
                $salt=$data['salt'];
                $password=$data['password'];
                $str = "UPDATE user SET userpassword='$password',salt='$salt' WHERE userid=$userid";               
            }
            $this->DB->updateRecord($str);
            $this->redirect('settings.Profiles',true);
        }
    }
}