<div id="config-tool" class="closed">
    <a id="config-tool-cog">
        <i class="fa fa-cog"></i>
    </a>
    <div id="config-tool-options">
        <h4>OPTION</h4>        
        <br/>
        <h4 style="font-size:12px"><strong>Tahun Masuk:</strong></h4>
        <ul id="skin-colors" class="clearfix">
            <li style="font-size:10px">
                <com:TActiveDropDownList ID="tbCmbTahunMasuk" OnCallback="Page.changeTbTahunMasuk" CssClass="form-control" Width="120px">
                    <prop:ClientSide.OnPreDispatch>
                        $('loading').show();
                    </prop:ClientSide.OnPreDispatch>
                   <prop:ClientSide.OnLoading>
                        $('<%=$this->tbCmbTahunMasuk->ClientId%>').disabled='disabled';
                    </prop:ClientSide.OnLoading>
                    <prop:ClientSide.onComplete>
                        $('loading').hide();
                        $('<%=$this->tbCmbTahunMasuk->ClientId%>').disabled='';
                    </prop:ClientSide.OnComplete>	
                </com:TActiveDropDownList>          
            </li>            
        </ul>  
        <br/>
        <h4 style="font-size:12px"><strong>Output Report / Compress:</strong></h4>
        <ul id="skin-colors" class="clearfix">
            <li style="font-size:10px">
                <div class="row">
                    <div class="col-sm-7">
                        <com:TActiveDropDownList ID="tbCmbOutputReport" OnCallback="Page.changeOutputReport" CssClass="form-control" Width="150px">
                            <prop:ClientSide.OnPreDispatch>
                                $('loading').show();
                            </prop:ClientSide.OnPreDispatch>
                           <prop:ClientSide.OnLoading>
                                $('<%=$this->tbCmbOutputReport->ClientId%>').disabled='disabled';
                            </prop:ClientSide.OnLoading>
                            <prop:ClientSide.onComplete>
                                $('loading').hide();
                                $('<%=$this->tbCmbOutputReport->ClientId%>').disabled='';
                            </prop:ClientSide.OnComplete>	
                        </com:TActiveDropDownList>                        
                    </div>
                    <div class="col-sm-1">
                        &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <com:TActiveDropDownList ID="tbCmbOutputCompress" OnCallback="Page.changeOutputCompress" CssClass="form-control">
                            <prop:ClientSide.OnPreDispatch>
                                $('loading').show();
                            </prop:ClientSide.OnPreDispatch>
                           <prop:ClientSide.OnLoading>
                                $('<%=$this->tbCmbOutputCompress->ClientId%>').disabled='disabled';
                            </prop:ClientSide.OnLoading>
                            <prop:ClientSide.onComplete>
                                $('loading').hide();
                                $('<%=$this->tbCmbOutputCompress->ClientId%>').disabled='';
                            </prop:ClientSide.OnComplete>	
                        </com:TActiveDropDownList>
                    </div>
                </div>               
            </li>            
        </ul>
    </div>
</div>