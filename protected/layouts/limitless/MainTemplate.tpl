<!DOCTYPE html>
<html lang="id">
<com:THead>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<%=$this->Application->getID()%>">        
	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="<%=$this->Page->Theme->BaseUrl%>/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<%=$this->Page->Theme->BaseUrl%>/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<%=$this->Page->Theme->BaseUrl%>/assets/css/core.min.css" rel="stylesheet" type="text/css">
	<link href="<%=$this->Page->Theme->BaseUrl%>/assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="<%=$this->Page->Theme->BaseUrl%>/assets/css/colors.min.css" rel="stylesheet" type="text/css">
    <link type="image/x-icon" href="resources/favicon.ico" rel="shortcut icon"/>
    <com:TContentPlaceHolder ID="csscontent" />	    
</com:THead>
<body>
<com:TForm id="mainform" Attributes.role="form">
<!-- Main navbar -->
<div class="navbar navbar-inverse bg-green navbar-lg">
    <div class="navbar-header">
        <a class="navbar-brand" href="<%=$this->Page->constructUrl('Home',true)%>">PORTAL<span> E-Kampus</span></a>
        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>
    <div class="navbar-collapse collapse" id="navbar-mobile">   
        <p class="navbar-text">
            <com:THyperLink ID="linkTopTASemester">
                <span class="label bg-success-400">
                    Saat ini Anda berada di T.A <%=$this->Page->setup->getSettingValue('default_ta');%>/<%=$this->Page->setup->getSemester($this->Page->setup->getSettingValue('default_semester'));%>
                </span>
            </com:THyperLink>
        </p>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown dropdown-user visible">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <img src="resources/userimages/no_photo.png" alt="<%=$this->page->Pengguna->getUsername()%>">
                    <span><%=$this->page->Pengguna->getUsername()%></span>
                    <i class="caret"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="<%=$this->Page->constructUrl('settings.Profiles',true)%>"><i class="icon-user-plus"></i> My profile</a></li>                    
                    <li class="divider"></li>                    
                    <li>                       
                        <com:TActiveLinkButton ID="btnLogout" OnClick="logoutUser" ClientSide.PostState="false">
                            <i class="icon-switch2"></i> Logout
                            <prop:ClientSide.OnPreDispatch>
                                Pace.stop();
                                Pace.start();
                                $('<%=$this->btnLogout->ClientId%>').disabled='disabled';						
                            </prop:ClientSide.OnPreDispatch>
                            <prop:ClientSide.OnLoading>
                                $('<%=$this->btnLogout->ClientId%>').disabled='disabled';									                            
                            </prop:ClientSide.OnLoading>
                            <prop:ClientSide.onComplete>
                                $('<%=$this->btnLogout->ClientId%>').disabled='';									                            
                            </prop:ClientSide.OnComplete>
                        </com:TActiveLinkButton>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->
<!-- Second navbar -->
<div class="navbar navbar-inverse bg-green-800 navbar-xs" id="navbar-second">
    <ul class="nav navbar-nav no-border visible-xs-block">
        <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7"></i></a></li>
    </ul>
    <div class="navbar-collapse collapse" id="navbar-second-toggle">
        <ul class="nav navbar-nav">            
            <li<%=$this->Page->showDashboard==true?' class="active"':''%>>
                <a href="<%=$this->Page->constructUrl('Home',true)%>">
                    <i class="icon-display4 position-left"></i> 
                    <span>Dashboard</span>											
                </a>                                        
            </li> 
            <com:TLiteral Visible="<%=$this->Page->Pengguna->getTipeUser()=='k'%>">
            <li class="dropdown<%=$this->Page->showDMaster==true?' active':''%> visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-puzzle4 position-left"></i> Data Master <span class="caret"></span>
                </a>
                <ul class="dropdown-menu width-250">
                    <li class="dropdown-header">KOMPONEN BIAYA</li>
                    <li<%=$this->Page->showRekening==true?' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('dmaster.Rekening',true)%>">
                            <i class="icon-calculator4"></i> Rekening
                        </a>
                    </li>                    
                    <li<%=$this->Page->showKombiPerTA==true?' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('dmaster.KombiPerTA',true)%>">
                            <i class="icon-calculator"></i> Biaya Per Tahun
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dropdown mega-menu mega-menu-wide<%=$this->Page->showMenuAkademik==true?' active':''%> visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-stars position-left"></i> Akademik <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">
                            <div class="col-md-3">
                                <span class="menu-heading underlined">Perkuliahan</span>
                                <ul class="menu-list">
                                    <li<%=$this->Page->showKUM==true?' class="active"':''%>>
                                        <a href="<%=$this->Page->constructUrl('perkuliahan.KUM',true)%>">
                                            <i class="icon-width"></i> Kartu Ujian Mahasiswa
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="dropdown mega-menu mega-menu-wide<%=$this->Page->showMenuPembayaran==true?' active':''%> visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-book2 position-left"></i> Pembayaran <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">
                            <div class="col-md-3">
                                <span class="menu-heading underlined">SEMESTER</span>
                                <ul class="menu-list">
                                    <li<%=$this->Page->showPembayaranMahasiswaBaru==true?' class="active"':''%>>
                                        <a href="<%=$this->Page->constructUrl('pembayaran.PembayaranMahasiswaBaru',true)%>">
                                            <i class="icon-calculator3"></i> Mahasiswa Baru <%=$_SESSION['ta']%>
                                        </a>
                                    </li> 
                                    <li<%=$this->Page->showPembayaranSemesterGanjil==true?' class="active"':''%>>
                                        <a href="<%=$this->Page->constructUrl('pembayaran.PembayaranSemesterGanjil',true)%>">
                                            <i class="icon-calculator3"></i> Semester Ganjil <%=$this->Page->setup->getSettingValue('default_ta')%>
                                        </a>
                                    </li> 
                                    <li<%=$this->Page->showPembayaranSemesterGenap==true?' class="active"':''%>>
                                        <a href="<%=$this->Page->constructUrl('pembayaran.PembayaranSemesterGenap',true)%>">
                                            <i class="icon-calculator3"></i> Semester Genap <%=$this->Page->setup->getSettingValue('default_ta')%>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined">CUTI</span>
                                <ul class="menu-list">
                                    <li<%=$this->Page->showPembayaranCutiSemesterGanjil==true?' class="active"':''%>>
                                        <a href="<%=$this->Page->constructUrl('pembayaran.PembayaranCutiSemesterGanjil',true)%>">
                                            <i class="icon-calculator3"></i> Cuti Semester Ganjil
                                        </a>
                                    </li> 
                                    <li<%=$this->Page->showPembayaranCutiSemesterGenap==true?' class="active"':''%>>
                                        <a href="<%=$this->Page->constructUrl('pembayaran.PembayaranCutiSemesterGenap',true)%>">
                                            <i class="icon-calculator3"></i> Cuti Semester Genap
                                        </a>
                                    </li> 
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined">PIUTANG</span>
                                <ul class="menu-list">
                                    <li<%=$this->Page->showPembayaranPiutangSemesterGanjil==true?' class="active"':''%>>
                                        <a href="<%=$this->Page->constructUrl('pembayaran.PembayaranPiutangSemesterGanjil',true)%>">
                                            <i class="icon-calculator3"></i> Piutang Semester Ganjil
                                        </a>
                                    </li> 
                                    <li<%=$this->Page->showPembayaranPiutangSemesterGenap==true?' class="active"':''%>>
                                        <a href="<%=$this->Page->constructUrl('pembayaran.PembayaranPiutangSemesterGenap',true)%>">
                                            <i class="icon-calculator3"></i> Piutang Semester Genap
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="dropdown<%=$this->Page->showReport==true?' active':''%> visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-files-empty position-left"></i> Laporan <span class="caret"></span>
                </a>
                <ul class="dropdown-menu width-250">
                    <li class="dropdown-header">REKAPITULASI</li>
                    <li<%=$this->Page->showReportRekapPembayaranGanjil==true?' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('report.RekapPembayaranSemesterGanjil',true)%>">
                            <i class="icon-file-text"></i> Rekap. Pembayaran SMT Ganjil
                        </a>
                    </li> 
                    <li class="dropdown-header">PIUTANG</li>
                    <li<%=$this->Page->showReportPiutangJangkaPendek==true?' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('report.PiutangJangkaPendek',true)%>">
                            <i class="icon-file-text"></i> Piutang Jangka Pendek
                        </a>
                    </li>                                        
                </ul>
            </li>
            </com:TLiteral>
            <com:TLiteral Visible="<%=$this->Page->Pengguna->getTipeUser()=='on'%>">
            <li<%=$this->Page->showKonversiMatakuliah==true?' class="active"':''%>>
                <a href="<%=$this->Page->constructUrl('KonversiMatakuliah',true)%>">
                    <i class="icon-share3 position-left"></i> 
                    <span>Konversi Matakuliah</span>											
                </a>                                        
            </li> 
            <li class="dropdown<%=$this->Page->showSubMenuAkademikNilai==true?' active':''%> visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-blog position-left"></i> Nilai <span class="caret"></span>
                </a>
                <ul class="dropdown-menu width-250">
                    <li class="dropdown-header">INPUT OLEH SEKRETARIS PRODI</li>
                    <li<%=$this->Page->showNilaiPerMahasiswa==true?' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('nilai.NilaiPerMahasiswa',true)%>">
                            <i class="icon-blog"></i> Nilai Per Mahasiswa
                        </a>
                    </li>
                    <li<%=$this->Page->showNilaiPerMatakuliah==true?' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('nilai.NilaiPerMatakuliah',true)%>">
                            <i class="icon-blog"></i> Nilai Per Matakuliah
                        </a>
                    </li>
                    <li<%=$this->Page->showNilaiFinal==true?' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('nilai.NilaiFinal',true)%>">
                            <i class="icon-blog"></i> Nilai Final
                        </a>
                    </li>
                    <li class="dropdown-header">INPUT OLEH DOSEN</li>
                    <li<%=$this->Page->showStopInputNilai==true?' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('nilai.StopInputNilai',true)%>">
                            <i class="icon-blog"></i> Stop Dosen Input Nilai
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dropdown<%=$this->Page->showReport==true?' active':''%> visible">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-files-empty position-left"></i> Laporan <span class="caret"></span>
                </a>
                <ul class="dropdown-menu width-250">
                    <li class="dropdown-header">REKAPITULASI</li>
                    <li<%=$this->Page->showTranskripKurikulum==true?' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('nilai.TranskripKurikulum',true)%>">
                            <i class="icon-file-text"></i> Transkrip Kurikulum
                        </a>
                    </li>                                 
                </ul>
            </li>
            </com:TLiteral>
        </ul>                
    </div>
</div>
<!-- /second navbar -->
<!-- Page header -->
<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <h4><com:TContentPlaceHolder ID="moduleheader" /></h4>
            <ul class="breadcrumb breadcrumb-caret position-right">
                <li><a href="<%=$this->Page->constructUrl('Home',true)%>">HOME</a></li>            
                <com:TContentPlaceHolder ID="modulebreadcrumb" />
            </ul>
            <com:TContentPlaceHolder ID="modulebreadcrumbelement" />
        </div>
        <com:TContentPlaceHolder ID="moduleheaderelement" />        
    </div>
</div>
<!-- /page header -->
<div class="page-container">    
    <div class="page-content">
        <com:TContentPlaceHolder ID="sidebarcontent" />
        <div class="content-wrapper">
            <com:TContentPlaceHolder ID="maincontent" />
            <com:TJavascriptLogger />
        </div>        
    </div>    
</div>
<!-- Footer -->
<div class="footer text-muted">
    <%=$this->Application->getID()%> licensed to <%=$this->Page->setup->getSettingValue('nama_pt_alias')%> Powered by <a href="https://www.yacanet.com">Yacanet.com</a>
</div>
<!-- /footer -->

</com:TForm>
<script type="text/javascript" src="<%=$this->Page->Theme->baseUrl%>/assets/js/core/libraries/jquery.min.js"></script>
<script type="text/javascript">
    jQuery.noConflict();
</script>
<script type="text/javascript" src="<%=$this->Page->Theme->baseUrl%>/assets/js/core/libraries/bootstrap.min.js"></script>
<script type="text/javascript" src="<%=$this->Page->Theme->BaseUrl%>/assets/js/plugins/loaders/pace.min.js"></script>
<script type="text/javascript" src="<%=$this->Page->Theme->BaseUrl%>/assets/js/plugins/ui/nicescroll.min.js"></script>
<script type="text/javascript" src="<%=$this->Page->Theme->BaseUrl%>/assets/js/plugins/ui/drilldown.js"></script>
<script type="text/javascript" src="<%=$this->Page->Theme->BaseUrl%>/assets/js/core/app.min.js"></script>
<script type="text/javascript" src="<%=$this->Page->Theme->baseUrl%>/assets/js/core/portalekampus.js"></script>
<com:TContentPlaceHolder ID="jscontent" />
<com:TContentPlaceHolder ID="jsinlinecontent" />
</body>
</html>


