function formatangka(objek,tanpatitik) {
	a = objek.value;
	b = a.replace(/[^\d]/g,"");
	c = "";
	panjang = b.length;
	j = 0;
	for (i = panjang; i > 0; i--) {
		j = j + 1;
		if (((j % 3) == 1) && (j != 1)) {
            if (tanpatitik)
                c = b.substr(i-1,1) + c;
            else
                c = b.substr(i-1,1) + "." + c;
		} else {
			c = b.substr(i-1,1) + c;
		}
	}
	objek.value = c;
}
function no_photo (object,url) {
	object.src = url;
	object.onerror = "";
	return true;
}
jQuery(document).click(function(){    
    var target = jQuery(".name");
    if (target.is("[style]")) {        
        target.removeAttr("style");        
    }  
    var target = jQuery(".dropdown.profile-dropdown");
    if (target.is("[style]")) {        
        target.removeAttr("style");        
    } 
});
/* CONFIG TOOLS SETTINGS */
jQuery('#config-tool-cog').on('click', function(){
    jQuery('#config-tool').toggleClass('closed');
});

jQuery('.select-all').click(function() {
  var $this = jQuery(this),
    checked = !$this.data('checked');

  jQuery('.' + $this.data('class')).not(':disabled').prop('checked', checked);
  $this.data('checked', checked)
});

