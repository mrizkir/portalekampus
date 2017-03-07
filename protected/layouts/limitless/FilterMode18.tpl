<div class="sidebar-category">
    <div class="category-title">
        <span>Filter</span>
        <ul class="icons-list">
            <li><a href="#" data-action="collapse"></a></li>
        </ul>
    </div>
    <div class="category-content"> 
        <div class="form-group">
            <label><strong>Program Studi :</strong></label>
            <com:TActiveDropDownList ID="tbCmbPs" OnCallback="Page.changeTbPs" CssClass="form-control">				
                <prop:ClientSide.OnPreDispatch>
                    $('<%=$this->tbCmbPs->ClientId%>').disabled='disabled';
                    Pace.stop();
                    Pace.start();                    
                </prop:ClientSide.OnPreDispatch>
                <prop:ClientSide.OnLoading>
                    $('<%=$this->tbCmbPs->ClientId%>').disabled='disabled';
                </prop:ClientSide.OnLoading>
                <prop:ClientSide.onComplete>                    
                    $('<%=$this->tbCmbPs->ClientId%>').disabled='';
                </prop:ClientSide.OnComplete>	
            </com:TActiveDropDownList>	
        </div>        
        <div class="form-group">
            <label><strong>Tahun Akademik :</strong></label>
            <com:TActiveDropDownList ID="tbCmbTA" OnCallback="Page.changeTbTA" CssClass="form-control" Width="120px">
                <prop:ClientSide.OnPreDispatch>
                    $('<%=$this->tbCmbTA->ClientId%>').disabled='disabled';
                    Pace.stop();
                    Pace.start();
                </prop:ClientSide.OnPreDispatch>
               <prop:ClientSide.OnLoading>
                    $('<%=$this->tbCmbTA->ClientId%>').disabled='disabled';
                </prop:ClientSide.OnLoading>
                <prop:ClientSide.onComplete>                    
                    $('<%=$this->tbCmbTA->ClientId%>').disabled='';
                </prop:ClientSide.OnComplete>	
            </com:TActiveDropDownList>
        </div>
    </div>
</div>