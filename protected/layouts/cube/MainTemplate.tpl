<!DOCTYPE html>
<html>
<com:THead>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="stylesheet" type="text/css" href="<%=$this->Page->Theme->baseUrl%>/css/bootstrap/bootstrap.min.css" />	
	<link rel="stylesheet" type="text/css" href="<%=$this->Page->Theme->baseUrl%>/css/libs/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="<%=$this->Page->Theme->baseUrl%>/css/libs/nanoscroller.css" />
	<link rel="stylesheet" type="text/css" href="<%=$this->Page->Theme->baseUrl%>/css/compiled/theme_styles.css" />
    <com:TContentPlaceHolder ID="csscontent" />   		
	<link type="image/x-icon" href="resources/favicon.ico" rel="shortcut icon"/>
	<!--<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>-->
	<!--[if lt IE 9]>
		<script src="<%=$this->Page->Theme->baseUrl%>/js/html5shiv.js"></script>
		<script src="<%=$this->Page->Theme->baseUrl%>/js/respond.min.js"></script>
	<![endif]-->
</com:THead>
<body class="theme-blue fixed-header">
<com:TForm id="mainform">
	<div id="theme-wrapper">
		<header class="navbar" id="header-navbar">
			<div class="container">
                <a href="<%=$this->Page->constructUrl('Home',true)%>" id="logo" class="navbar-brand">
					Portal E-Kampus
                    <!--<img src="<%=$this->Page->Theme->baseUrl%>/img/logo.png" alt="" class="normal-logo logo-white"/>-->					
				</a>				
				<div class="clearfix">
				<button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="fa fa-bars"></span>
				</button>				
				<div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
					<ul class="nav navbar-nav pull-left">
						<li>
							<a class="btn" id="make-small-nav">
								<i class="fa fa-bars"></i>
							</a>
						</li>
                        <li class="dropdown hidden-xs visible">
							<a class="btn dropdown-toggle" data-toggle="dropdown">
								Themes
								<i class="fa fa-caret-down"></i>
							</a>
                            <com:TRepeater ID="RepeaterTheme" DataKeyField="idtheme">
                                <prop:HeaderTemplate>
                                    <ul class="dropdown-menu">
                                </prop:HeaderTemplate>
                                <prop:ItemTemplate>
                                    <li class="item">
                                    <com:TActiveLinkButton ID="btnChangeTheme" ClientSide.PostState="false" OnClick="SourceTemplateControl.changeTheme" Enabled="<%#$_SESSION['theme']!=$this->DataItem['idtheme']%>" CommandParameter="<%#$this->DataItem['idtheme']%>">
                                            <prop:Text>
                                                <%#$_SESSION['theme']==$this->DataItem['idtheme'] ? '<i class="fa fa-check-square"></i>' :''%>
                                                <%#$this->DataItem['namatheme']%>
                                            </prop:Text>
                                            <prop:ClientSide.OnPreDispatch>
                                                $('loading').show();                                             
                                                $('<%=$this->btnChangeTheme->ClientId%>').disabled='disabled';						
                                            </prop:ClientSide.OnPreDispatch>
                                            <prop:ClientSide.OnLoading>
                                                $('<%=$this->btnChangeTheme->ClientId%>').disabled='disabled';									                            
                                            </prop:ClientSide.OnLoading>
                                            <prop:ClientSide.onComplete>
                                                $('loading').hide();                                                
                                            </prop:ClientSide.OnComplete>
                                        </com:TActiveLinkButton>                                        
                                    </li>
                                </prop:ItemTemplate>
                                <prop:FooterTemplate>
                                    </ul>
                                </prop:FooterTemplate>
                            </com:TRepeater>
						</li>
					</ul>
				</div>				
				<div class="nav-no-collapse pull-right" id="header-nav">
					<ul class="nav navbar-nav pull-right">	
                        <li>                            
                            <div id="loading" style="display: none">
                                Please wait while process your request !!!
                            </div>
                        </li>                        
                        <li class="hidden-xxs">
							<com:THyperLink ID="linkTopTASemester" CssClass="btn">
								Saat ini Anda berada di T.A <%=$this->Page->setup->getSettingValue('default_ta');%>/<%=$this->Page->setup->getSemester($this->Page->setup->getSettingValue('default_semester'));%>
							</com:THyperLink>
						</li>
						<li class="dropdown profile-dropdown visible">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img alt="<%=$this->Page->Pengguna->getUsername()%>" src="resources/userimages/<%=$_SESSION['foto']%>" OnError="no_photo(this,'<%=$this->Page->setup->getAddress()%>/resources/userimages/no_photo.png')" />
								<span class="hidden-xs"><%=$this->Page->Pengguna->getUsername()%></span> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li><a href="<%=$this->Page->constructUrl('settings.Profiles',true)%>"><i class="fa fa-user"></i>Profiles</a></li>
                                <li>
                                    <com:TActiveLinkButton ID="btnLogout2" OnClick="logoutUser" ClientSide.PostState="false">
                                        <i class="fa fa-power-off"></i> Logout
                                        <prop:ClientSide.OnPreDispatch>                                                                   
                                            $('loading').show(); 
                                            $('<%=$this->btnLogout2->ClientId%>').disabled='disabled';						
                                        </prop:ClientSide.OnPreDispatch>
                                        <prop:ClientSide.OnLoading>
                                            $('<%=$this->btnLogout2->ClientId%>').disabled='disabled';						
                                        </prop:ClientSide.OnLoading>
                                        <prop:ClientSide.OnComplete>																	                                    						                                                                            
                                            $('<%=$this->btnLogout2->ClientId%>').disabled='';
                                            $('loading').hide(); 
                                        </prop:ClientSide.OnComplete>
                                    </com:TActiveLinkButton>
                                </li>                                        
							</ul>
						</li>                        
						<li class="hidden-xxs">
							<com:TActiveLinkButton ID="btnLogout" OnClick="logoutUser" ClientSide.PostState="false" CssClass="btn">
                                <i class="fa fa-power-off"></i>
                                <prop:ClientSide.OnPreDispatch>                                                                   
                                    $('loading').show(); 
                                    $('<%=$this->btnLogout->ClientId%>').disabled='disabled';						
                                </prop:ClientSide.OnPreDispatch>
                                <prop:ClientSide.OnLoading>
                                    $('<%=$this->btnLogout->ClientId%>').disabled='disabled';						
                                </prop:ClientSide.OnLoading>
                                <prop:ClientSide.OnComplete>																	                                    						                                                                            
                                    $('<%=$this->btnLogout->ClientId%>').disabled='';
                                    $('loading').hide(); 
                                </prop:ClientSide.OnComplete>
                            </com:TActiveLinkButton>
						</li>
					</ul>
				</div>
				</div>
			</div>
		</header>
		<div id="page-wrapper" class="container">
			<div class="row">
				<div id="nav-col">
					<section id="col-left" class="col-left-nano">
						<div id="col-left-inner" class="col-left-nano-content">
							<div id="user-left-box" class="clearfix hidden-sm hidden-xs dropdown profile2-dropdown">
								<img alt="<%=$this->Page->Pengguna->getUsername()%>" src="resources/userimages/<%=$_SESSION['foto']%>" />
								<div class="user-box">
									<span class="name">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown">
											<%=$this->Page->Pengguna->getUsername()%>
											<i class="fa fa-angle-down"></i>
										</a>
										<ul class="dropdown-menu">
											<li><a href="<%=$this->Page->constructUrl('settings.Profiles',true)%>"><i class="fa fa-user"></i>Profiles</a></li>
                                            <li>
                                                <com:TActiveLinkButton ID="btnLogout3" OnClick="logoutUser" ClientSide.PostState="false">
                                                    <i class="fa fa-power-off"></i> Logout
                                                    <prop:ClientSide.OnPreDispatch>                                                                   
                                                        $('loading').show(); 
                                                        $('<%=$this->btnLogout3->ClientId%>').disabled='disabled';						
                                                    </prop:ClientSide.OnPreDispatch>
                                                    <prop:ClientSide.OnLoading>
                                                        $('<%=$this->btnLogout3->ClientId%>').disabled='disabled';						
                                                    </prop:ClientSide.OnLoading>
                                                    <prop:ClientSide.OnComplete>																	                                    						                                                                            
                                                        $('<%=$this->btnLogout3->ClientId%>').disabled='';
                                                        $('loading').hide(); 
                                                    </prop:ClientSide.OnComplete>
                                                </com:TActiveLinkButton>
                                            </li>
										</ul>
									</span>
                                    <com:TActiveLabel ID="lblStatusUser" CssClass="status" />									
								</div>
							</div>
							<div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">	
								<ul class="nav nav-pills nav-stacked">
									<li class="nav-header nav-header-first hidden-sm hidden-xs">
										NAVIGASI
									</li>
                                    <com:TLiteral Visible="<%=$this->Page->Pengguna->getTipeUser()=='sa' && $this->Page->showSideBarMenu==true%>">
                                        <li<%=$this->Page->showDashboard==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('Home',true)%>">
                                                <i class="fa fa-dashboard"></i>
                                                <span>Dashboard</span>											
                                            </a>                                        
                                        </li> 
                                        <li<%=$this->Page->showSubMenuAkademikPerkuliahan==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-truck"></i>
                                                <span>Perkuliahan</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.KRS',true)%>"<%=$this->Page->showKRS==true ? ' class="active" ':''%>>
                                                        KRS
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>   
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            SETTING
                                        </li>
                                        <li<%=$this->Page->showVariable==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('settings.Variables',true)%>">
                                                <i class="fa fa-legal"></i>
                                                <span>Variables</span>											
                                            </a>                                        
                                        </li> 
                                        <li<%=$this->Page->showSubMenuSettingAkademik==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-medkit"></i>
                                                <span>Akademik</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('settings.Kaprodi',true)%>"<%=$this->Page->showKaprodi==true ? ' class="active" ':''%>>
                                                        Ketua Prodi
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li<%=$this->Page->showSubMenuSettingSistem==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-cog"></i>
                                                <span>Sistem</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('settings.UserSA',true)%>"<%=$this->Page->showUserSA==true ? ' class="active" ':''%>>
                                                        User Super Admin
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('settings.UserManajemen',true)%>"<%=$this->Page->showUserManajemen==true ? ' class="active" ':''%>>
                                                        User Manajemen
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('settings.UserKeuangan',true)%>"<%=$this->Page->showUserKeuangan==true ? ' class="active" ':''%>>
                                                        User Keuangan
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('settings.UserDosen',true)%>"<%=$this->Page->showUserDosen==true ? ' class="active" ':''%>>
                                                        User Dosen
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('settings.UserON',true)%>"<%=$this->Page->showUserON==true ? ' class="active" ':''%>>
                                                        User Operator Nilai
                                                    </a>
                                                </li>
                                                <li>                                                
                                                    <a href="<%=$this->Page->constructUrl('settings.Cache',true)%>"<%=$this->Page->showCache==true?' class="active"':''%>>                                                    
                                                        Cache											
                                                    </a>                                        
                                                </li>
                                            </ul>
                                        </li> 
                                        <li<%=$this->Page->showProfiles==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('settings.Profiles',true)%>">
                                                <i class="fa fa-user"></i>
                                                <span>Profiles</span>											
                                            </a>                                        
                                        </li> 
                                        <li>
                                            <a href="<%=$this->Page->setup->getAddress()%>/change_log.txt">
                                                <i class="fa fa-file-o"></i>
                                                <span>Change Log</span>											
                                            </a>                                        
                                        </li> 
                                    </com:TLiteral>
                                    <com:TLiteral Visible="<%=$this->Page->Pengguna->getTipeUser()=='m' && $this->Page->showSideBarMenu==true%>">
                                        <li<%=$this->Page->showDashboard==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('Home',true)%>">
                                                <i class="fa fa-dashboard"></i>
                                                <span>Dashboard</span>											
                                            </a>                                        
                                        </li>
                                        <li<%=$this->Page->showPengumuman==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('forum.Pengumuman',true)%>">
                                                <i class="fa fa-info-circle"></i>
                                                <span>Pengumuman</span>											
                                            </a>                                        
                                        </li>
                                        <li<%=$this->Page->showForumDiskusi==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('forum.Diskusi',true)%>">
                                                <i class="fa fa-comment"></i>
                                                <span>Forum Diskusi</span>											
                                            </a>                                        
                                        </li>  
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            DATA MASTER
                                        </li>
                                        <li<%=$this->Page->showDosen==true ? ' class="active" ':''%>>                                            
                                            <a href="<%=$this->Page->constructUrl('dmaster.Dosen',true)%>">
                                                <i class="fa fa-user"></i>
                                                <span>Dosen</span>                                                
                                            </a>
                                        </li>
                                        <li<%=$this->Page->showMatakuliah==true ? ' class="active" ':''%>>                                            
                                            <a href="<%=$this->Page->constructUrl('dmaster.Matakuliah',true)%>">
                                                <i class="fa fa-bars"></i>
                                                <span>Matakuliah</span>                                                
                                            </a>
                                        </li>
                                        <li<%=$this->Page->showSubMenuKuesioner==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-sign-in"></i>
                                                <span>Kuesioner</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('dmaster.KelompokPertanyaan',true)%>"<%=$this->Page->showKelompokPertanyaan==true ? ' class="active" ':''%>>
                                                        Kelompok Pertanyaan
                                                    </a>
                                                </li>                                                
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('dmaster.DaftarPertanyaan',true)%>"<%=$this->Page->showDaftarPertanyaan==true ? ' class="active" ':''%>>
                                                        Daftar Pertanyaan
                                                    </a>
                                                </li>                                                
                                            </ul>
                                        </li> 
                                        <li<%=$this->Page->showSoalPMB==true ? ' class="active" ':''%>>                                            
                                            <a href="<%=$this->Page->constructUrl('dmaster.SoalPMB',true)%>">
                                                <i class="fa fa-list-ul"></i>
                                                <span>Soal PMB</span>                                                
                                            </a>
                                        </li>
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            SPMB
                                        </li>  
                                        <li<%=$this->Page->showPIN==true ? ' class="active" ':''%>>                                            
                                            <a href="<%=$this->Page->constructUrl('spmb.PIN',true)%>">
                                                <i class="fa fa-circle-o"></i>
                                                <span>PIN</span>                                                
                                            </a>
                                        </li>
                                        <li<%=$this->Page->showSubMenuSPMBPendaftaran==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-sign-in"></i>
                                                <span>Pendaftaran</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="#"<%=$this->Page->showPendaftaranViaFO==true ? ' class="active" ':''%>>
                                                        Via Front Office
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('spmb.PendaftaranViaWeb',true)%>"<%=$this->Page->showPendaftaranViaWeb==true ? ' class="active" ':''%>>
                                                        Via Web
                                                    </a>
                                                </li>                                                
                                            </ul>
                                        </li>    
                                        <li<%=$this->Page->showSubMenuSPMBUjianPMB==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-flag-checkered"></i>
                                                <span>Ujian PMB</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">                                                
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('spmb.PassingGrade',true)%>"<%=$this->Page->showPassingGradePMB==true ? ' class="active" ':''%>>
                                                        Passing Grade
                                                    </a>
                                                </li>                                                
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('spmb.NilaiUjianPMB',true)%>"<%=$this->Page->showNilaiUjianPMB==true ? ' class="active" ':''%>>
                                                        Nilai Ujian
                                                    </a>
                                                </li>                                                
                                            </ul>
                                        </li>    
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            AKADEMIK
                                        </li>
                                        <li<%=$this->Page->showSubMenuAkademikKemahasiswaan==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-users"></i>
                                                <span>Kemahasiswaan</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="#"<%=$this->Page->showProfilMahasiswa==true ? ' class="active" ':''%>>
                                                        Profil Mahasiswa
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('kemahasiswaan.DaftarMahasiswa',true)%>"<%=$this->Page->showDaftarMahasiswa==true ? ' class="active" ':''%>>
                                                        Daftar Mahasiswa
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('kemahasiswaan.PendaftaranKonsentrasi',true)%>"<%=$this->Page->showPendaftaranKonsentrasi==true ? ' class="active" ':''%>>
                                                        Pend. Konsentrasi
                                                    </a>
                                                </li>  
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('kemahasiswaan.RekapStatusMahasiswa',true)%>"<%=$this->Page->showRekapStatusMahasiswa==true ? ' class="active" ':''%>>
                                                        Rekap. Status Mahasiswa
                                                    </a>
                                                </li> 
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('kemahasiswaan.PindahKelas',true)%>"<%=$this->Page->showPindahKelas==true ? ' class="active" ':''%>>
                                                        Pindah Kelas
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>                                                                                                                       
                                        <li<%=$this->Page->showSubMenuAkademikDulang==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-location-arrow"></i>
                                                <span>Daftar Ulang</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('dulang.CalonMHS',true)%>"<%=$this->Page->showCalonMHS==true ? ' class="active" ':''%>>
                                                        Calon Mahasiswa
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('dulang.DulangMHSBaru',true)%>"<%=$this->Page->showDulangMHSBaru==true ? ' class="active" ':''%>>
                                                        Mahasiswa Baru
                                                    </a>
                                                </li>    
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('dulang.DulangMHSLama',true)%>"<%=$this->Page->showDulangMHSLama==true ? ' class="active" ':''%>>
                                                        Mahasiswa Lama
                                                    </a>
                                                </li> 
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('dulang.DulangMHSEkstension',true)%>"<%=$this->Page->showDulangMHSEkstension==true ? ' class="active" ':''%>>
                                                        Mahasiswa Ekstension
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('dulang.DulangMHSLulus',true)%>"<%=$this->Page->showDulangMHSLulus==true ? ' class="active" ':''%>>
                                                        Mahasiswa Lulus
                                                    </a>
                                                </li> 
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('dulang.DulangMHSNonAktif',true)%>"<%=$this->Page->showDulangMHSNonAktif==true ? ' class="active" ':''%>>
                                                        Mahasiswa Non-Aktif
                                                    </a>
                                                </li> 
                                            </ul>
                                        </li>                                                                                                                       
                                        <li<%=$this->Page->showSubMenuAkademikPerkuliahan==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-truck"></i>
                                                <span>Perkuliahan</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.Penyelenggaraan',true)%>"<%=$this->Page->showPenyelenggaraan==true ? ' class="active" ':''%>>
                                                        Penyelenggaraan
                                                    </a>
                                                </li> 
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.PembagianKelas',true)%>"<%=$this->Page->showPembagianKelas==true ? ' class="active" ':''%>>
                                                        Pembagian Kelas
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.KRS',true)%>"<%=$this->Page->showKRS==true ? ' class="active" ':''%>>
                                                        KRS
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.KRSEkstension',true)%>"<%=$this->Page->showKRSEkstension==true ? ' class="active" ':''%>>
                                                        KRS Ekstension
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.PesertaMatakuliah',true)%>"<%=$this->Page->showPesertaMatakuliah==true ? ' class="active" ':''%>>
                                                        Peserta Matakuliah
                                                    </a>
                                                </li>                                                
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.Kuesioner',true)%>"<%=$this->Page->showKuesioner==true ? ' class="active" ':''%>>
                                                        Kuesioner
                                                    </a>
                                                </li> 
                                            </ul>
                                        </li>   
                                        <li<%=$this->Page->showSubMenuAkademikNilai==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-file-excel-o"></i>
                                                <span>Nilai</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.KonversiMatakuliah',true)%>"<%=$this->Page->showKonversiMatakuliah==true?' class="active"':''%>>
                                                        Konversi Matakuliah											
                                                    </a>                                        
                                                </li>  
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.DPNA',true)%>"<%=$this->Page->showDPNA==true ? ' class="active" ':''%>>
                                                        DPNA
                                                    </a>
                                                </li>                                                
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.KHS',true)%>"<%=$this->Page->showKHS==true ? ' class="active" ':''%>>
                                                        KHS
                                                    </a>
                                                </li>                                                
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.KHSEkstension',true)%>"<%=$this->Page->showKHSEkstension==true ? ' class="active" ':''%>>
                                                        KHS Ekstension
                                                    </a>
                                                </li>                                                
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.TranskripKurikulum',true)%>"<%=$this->Page->showTranskripKurikulum==true ? ' class="active" ':''%>>
                                                        Transkrip Kurikulum
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.TranskripKRS',true)%>"<%=$this->Page->showTranskripKRS==true ? ' class="active" ':''%>>
                                                        Transkrip KRS
                                                    </a>
                                                </li>                                                   
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.TranskripFinal',true)%>"<%=$this->Page->showTranskripFinal==true ? ' class="active" ':''%>>
                                                        Transkrip Final
                                                    </a>
                                                </li>                                                   
                                            </ul>
                                        </li> 
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            KEUANGAN
                                        </li>
                                        <li<%=$this->Page->showSubMenuRekapKeuangan==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-file-text-o"></i>
                                                <span>Rekapitulasi</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('keuangan.RekapPembayaranSemesterGanjil',true)%>"<%=$this->Page->showReportRekapPembayaranGanjil==true ? ' class="active" ':''%>>
                                                        Pembayararan SMT Ganjil
                                                    </a>
                                                </li> 
                                            </ul>
                                        </li>
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            SETTING
                                        </li>
                                        <li<%=$this->Page->showProfiles==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('settings.Profiles',true)%>">
                                                <i class="fa fa-user"></i>
                                                <span>Profiles</span>											
                                            </a>                                        
                                        </li> 
                                        <li>
                                            <a href="<%=$this->Page->setup->getAddress()%>/change_log.txt">
                                                <i class="fa fa-file-o"></i>
                                                <span>Change Log</span>											
                                            </a>                                        
                                        </li> 
                                    </com:TLiteral>		
                                    <com:TLiteral Visible="<%=$this->Page->Pengguna->getTipeUser()=='mh' && $this->Page->showSideBarMenu==true%>">
                                        <li<%=$this->Page->showDashboard==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('Home',true)%>">
                                                <i class="fa fa-dashboard"></i>
                                                <span>Dashboard</span>											
                                            </a>                                        
                                        </li> 
                                        <li<%=$this->Page->showPengumuman==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('forum.Pengumuman',true)%>">
                                                <i class="fa fa-info-circle"></i>
                                                <span>Pengumuman</span>											
                                            </a>                                        
                                        </li>
                                        <li<%=$this->Page->showForumDiskusi==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('forum.Diskusi',true)%>">
                                                <i class="fa fa-comment"></i>
                                                <span>Forum Diskusi</span>											
                                            </a>                                        
                                        </li> 
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            AKADEMIK
                                        </li>   
                                        <li<%=$this->Page->showSubMenuAkademikPerkuliahan==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-truck"></i>
                                                <span>Perkuliahan</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.JadwalPerkuliahan',true)%>"<%=$this->Page->showJadwalPerkuliahan==true ? ' class="active" ':''%>>
                                                        Jadwal Perkuliahan
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.KRS',true)%>"<%=$this->Page->showKRS==true ? ' class="active" ':''%>>
                                                        KRS
                                                    </a>
                                                </li>                                                
                                                <li>
                                                    <a href="#"<%=$this->Page->showKuesioner==true ? ' class="active" ':''%>>
                                                        Kuesioner
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>   
                                        <li<%=$this->Page->showSubMenuAkademikNilai==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-file-excel-o"></i>
                                                <span>Nilai</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">                                                                                   
                                                <li>
                                                    <a<%=$this->Page->showKHS==true ? ' class="active" ':''%>  href="<%=$this->Page->constructUrl('nilai.KHS',true)%>">                                                        
                                                        <span>Kartu Hasil Studi</span>											
                                                    </a> 
                                                </li>                                                
                                                <li>
                                                    <a<%=$this->Page->showTranskripKurikulum==true ? ' class="active" ':''%>  href="<%=$this->Page->constructUrl('nilai.TranskripKurikulum',true)%>">                                                        
                                                        <span>Transkrip Kurikulum</span>											
                                                    </a> 
                                                </li> 
                                                <li>
                                                    <a<%=$this->Page->showTranskripKRS==true ? ' class="active" ':''%>  href="<%=$this->Page->constructUrl('nilai.TranskripKRS',true)%>">                                                        
                                                        <span>Transkrip KRS</span>											
                                                    </a> 
                                                </li> 
                                            </ul>
                                        </li>                                          
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            KEMAHASISWAAN
                                        </li>  
                                        <li<%=$this->Page->showDaftarKonsentrasi==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('kemahasiswaan.DaftarKonsentrasi',true)%>">
                                                <i class="fa fa-users"></i>
                                                <span>Daftar Konsentrasi</span>											
                                            </a>                                        
                                        </li> 
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            RIWAYAT
                                        </li>  
                                        <li<%=$this->Page->showDulangMHSLama==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('riwayat.DulangMHSLama',true)%>">
                                                <i class="fa fa-location-arrow"></i>
                                                <span>Daftar Ulang</span>											
                                            </a>                                        
                                        </li>  
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            SETTING
                                        </li>                                                                             
                                        <li<%=$this->Page->showProfiles==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('settings.Profiles',true)%>">
                                                <i class="fa fa-user"></i>
                                                <span>Profiles</span>											
                                            </a>                                        
                                        </li>                                        
                                    </com:TLiteral>	
                                    <com:TLiteral Visible="<%=$this->Page->Pengguna->getTipeUser()=='mb' && $this->Page->showSideBarMenu==true%>">
                                        <li<%=$this->Page->showDashboard==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('Home',true)%>">
                                                <i class="fa fa-dashboard"></i>
                                                <span>Dashboard</span>											
                                            </a>                                        
                                        </li> 
                                        <li<%=$this->Page->showFormulirPendaftaran==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('FormulirPendaftaran',true)%>">
                                                <i class="fa fa-file-o"></i>
                                                <span>Formulir Pendaftaran</span>											
                                            </a>                                        
                                        </li>
                                        <li<%=$this->Page->showSoalPMB==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('SoalPMB',true)%>">
                                                <i class="fa fa-list-ul"></i>
                                                <span>Soal Ujian PMB</span>											
                                            </a>                                        
                                        </li>
                                    </com:TLiteral>	
                                    <com:TLiteral Visible="<%=$this->Page->Pengguna->getTipeUser()=='d' && $this->Page->showSideBarMenu==true%>">
                                        <li<%=$this->Page->showDashboard==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('Home',true)%>">
                                                <i class="fa fa-dashboard"></i>
                                                <span>Dashboard</span>											
                                            </a>                                        
                                        </li>  
                                        <li<%=$this->Page->showForumDiskusi==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('forum.Diskusi',true)%>">
                                                <i class="fa fa-comment"></i>
                                                <span>Forum Diskusi</span>											
                                            </a>                                        
                                        </li> 
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            AKADEMIK
                                        </li>
                                        <li<%=$this->Page->showSubMenuAkademikPerkuliahan==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-truck"></i>
                                                <span>Perkuliahan</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.PembagianKelas',true)%>"<%=$this->Page->showPembagianKelas==true ? ' class="active" ':''%>>
                                                        Pembagian Kelas
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.Kuesioner',true)%>"<%=$this->Page->showKuesioner==true ? ' class="active" ':''%>>
                                                        Kuesioner
                                                    </a>
                                                </li> 
                                            </ul>
                                        </li>
                                        <li<%=$this->Page->showSubMenuAkademikNilai==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-file-excel-o"></i>
                                                <span>Nilai</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">                                                
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.DPNA',true)%>"<%=$this->Page->showDPNA==true ? ' class="active" ':''%>>
                                                        DPNA
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.EditNilai',true)%>"<%=$this->Page->showEditNilai==true ? ' class="active" ':''%>>
                                                        Edit Nilai
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" <%=$this->Page->showImportNilai==true ? ' class="active" ':''%>>
                                                        Import Nilai
                                                    </a>
                                                </li>
                                            </ul>
                                        </li> 
                                    </com:TLiteral>	
                                    <com:TLiteral Visible="<%=$this->Page->Pengguna->getTipeUser()=='dw' && $this->Page->showSideBarMenu==true%>">
                                        <li<%=$this->Page->showDashboard==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('Home',true)%>">
                                                <i class="fa fa-dashboard"></i>
                                                <span>Dashboard</span>											
                                            </a>                                        
                                        </li>    
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            AKADEMIK
                                        </li>
                                        <li<%=$this->Page->showSubMenuAkademikKemahasiswaan==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-users"></i>
                                                <span>Kemahasiswaan</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="#"<%=$this->Page->showProfilMahasiswa==true ? ' class="active" ':''%>>
                                                        Profil Mahasiswa
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('kemahasiswaan.DaftarMahasiswa',true)%>"<%=$this->Page->showDaftarMahasiswa==true ? ' class="active" ':''%>>
                                                        Daftar Mahasiswa
                                                    </a>
                                                </li>                                                                                            
                                            </ul>
                                        </li>                                                                                                                       
                                        <li<%=$this->Page->showSubMenuAkademikDulang==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-location-arrow"></i>
                                                <span>Daftar Ulang</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">                                                
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('dulang.DulangMHSBaru',true)%>"<%=$this->Page->showDulangMHSBaru==true ? ' class="active" ':''%>>
                                                        Mahasiswa Baru
                                                    </a>
                                                </li>    
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('dulang.DulangMHSLama',true)%>"<%=$this->Page->showDulangMHSLama==true ? ' class="active" ':''%>>
                                                        Mahasiswa Lama
                                                    </a>
                                                </li> 
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('dulang.DulangMHSEkstension',true)%>"<%=$this->Page->showDulangMHSEkstension==true ? ' class="active" ':''%>>
                                                        Mahasiswa Ekstension
                                                    </a>
                                                </li> 
                                            </ul>
                                        </li>                                                                                                                       
                                        <li<%=$this->Page->showSubMenuAkademikPerkuliahan==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-truck"></i>
                                                <span>Perkuliahan</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.Penyelenggaraan',true)%>"<%=$this->Page->showPenyelenggaraan==true ? ' class="active" ':''%>>
                                                        Penyelenggaraan
                                                    </a>
                                                </li> 
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.PembagianKelas',true)%>"<%=$this->Page->showPembagianKelas==true ? ' class="active" ':''%>>
                                                        Pembagian Kelas
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.KRS',true)%>"<%=$this->Page->showKRS==true ? ' class="active" ':''%>>
                                                        KRS
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.KRSEkstension',true)%>"<%=$this->Page->showKRSEkstension==true ? ' class="active" ':''%>>
                                                        KRS Ekstension
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('perkuliahan.PKRS',true)%>"<%=$this->Page->showPKRS==true ? ' class="active" ':''%>>
                                                        Perubahan KRS
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>   
                                        <li<%=$this->Page->showSubMenuAkademikNilai==true?' class="active"':''%>>
                                            <a href="#" class="dropdown-toggle">
                                                <i class="fa fa-file-excel-o"></i>
                                                <span>Nilai</span>
                                                <i class="fa fa-angle-right drop-icon"></i>
                                            </a>
                                            <ul class="submenu">                                                                                                
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.KHS',true)%>"<%=$this->Page->showKHS==true ? ' class="active" ':''%>>
                                                        KHS
                                                    </a>
                                                </li>                                                
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.KHSEkstension',true)%>"<%=$this->Page->showKHSEkstension==true ? ' class="active" ':''%>>
                                                        KHS Ekstension
                                                    </a>
                                                </li>                                                
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.TranskripKurikulum',true)%>"<%=$this->Page->showTranskripKurikulum==true ? ' class="active" ':''%>>
                                                        Transkrip Kurikulum
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<%=$this->Page->constructUrl('nilai.TranskripKRS',true)%>"<%=$this->Page->showTranskripKRS==true ? ' class="active" ':''%>>
                                                        Transkrip KRS
                                                    </a>
                                                </li>                                        
                                            </ul>
                                        </li>
                                        <li class="nav-header nav-header-first hidden-sm hidden-xs">
                                            SETTING
                                        </li>
                                        <li<%=$this->Page->showProfiles==true?' class="active"':''%>>
                                            <a href="<%=$this->Page->constructUrl('settings.Profiles',true)%>">
                                                <i class="fa fa-user"></i>
                                                <span>Profiles</span>											
                                            </a>                                        
                                        </li> 
                                        <li>
                                            <a href="<%=$this->Page->setup->getAddress()%>/change_log.txt">
                                                <i class="fa fa-file-o"></i>
                                                <span>Change Log</span>											
                                            </a>                                        
                                        </li>
                                    </com:TLiteral>	
								</ul>
							</div>
						</div>
					</section>
					<div id="nav-col-submenu"></div>
				</div>
				<div id="content-wrapper">
					<div class="row">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-lg-12">
									<ol class="breadcrumb">
										<li><a href="<%=$this->Page->constructUrl('Home',true)%>">Home</a></li>
										<com:TContentPlaceHolder ID="modulebreadcrumb" />
									</ol>
									<h1><com:TContentPlaceHolder ID="moduleheader" /></h1>
								</div>
                                <com:TContentPlaceHolder ID="maincontent" /> 
							</div>
						</div>
					</div>					
					<footer id="footer-bar" class="row">
						<p id="footer-copyright" class="col-xs-12">                        
                            <%=$this->Application->getID()%> licensed to <%=$this->Page->setup->getSettingValue('nama_pt_alias')%> Powered by <a href="https://www.yacanet.com">Yacanet.com</a>
                            <com:TJavascriptLogger />
                        </p>
					</footer>
				</div>
			</div>
		</div>
	</div>	
	<com:TContentPlaceHolder ID="configtools" />
</com:TForm>
<script src="<%=$this->Page->Theme->baseUrl%>/js/jquery.js"></script>
<script type="text/javascript">
    jQuery.noConflict();
</script>
<script src="<%=$this->Page->Theme->baseUrl%>/js/bootstrap.min.js"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/js/jquery.nanoscroller.min.js"></script>				
<com:TContentPlaceHolder ID="jscontent" />	
<script src="<%=$this->Page->Theme->baseUrl%>/js/scripts.js"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/js/pace.min.js"></script>	
<script src="<%=$this->Page->Theme->baseUrl%>/js/portalekampus.js" type="text/javascript"></script>
<!-- this page specific inline scripts -->
<com:TContentPlaceHolder ID="jsinlinecontent" />	
</body>
</html>