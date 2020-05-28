(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-06f73e2e"],{"071d":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("AdminLayout",{attrs:{pagename:"spmbpersyaratanpmb",dashboard:t.dashboard},on:{setPageData:t.setPageData}},[a("ModuleHeader",{scopedSlots:t._u([{key:"icon",fn:function(){return[t._v(" mdi-file-document-edit-outline ")]},proxy:!0},{key:"name",fn:function(){return[t._v(" PERSYARATAN PMB ")]},proxy:!0},{key:"subtitle",fn:function(){return[t._v(" TAHUN "+t._s(t._f("formatTA")(t.tahun_masuk))+" ")]},proxy:!0},{key:"breadcrumbs",fn:function(){return[a("v-breadcrumbs",{staticClass:"pa-0",attrs:{items:t.breadcrumbs},scopedSlots:t._u([{key:"divider",fn:function(){return[a("v-icon",[t._v("mdi-chevron-right")])]},proxy:!0}])})]},proxy:!0},"mahasiswabaru"==t.dashboard?{key:"desc",fn:function(){return[a("v-alert",{attrs:{color:"cyan",border:"left","colored-border":"",type:"info"}},[t._v(" Halaman ini digunakan untuk upload peryaratan pendaftaran, mohon diisi dengan lengkap dan benar. ")])]},proxy:!0}:{key:"desc",fn:function(){return[a("v-alert",{attrs:{color:"cyan",border:"left","colored-border":"",type:"info"}},[t._v(" Halaman ini berisi file-file persyaratan pendaftaran yang diupload oleh mahasiswa baru, mohon disesuaikan di filter tahun akademik, kemudian tekan refresh. ")])]},proxy:!0}],null,!0)}),"mahasiswabaru"==t.dashboard?a("v-container",[a("FormPersyaratan")],1):a("v-container",[a("v-row",{staticClass:"mb-4",attrs:{"no-gutters":""}},[a("v-col",{attrs:{cols:"12"}},[a("v-card",[a("v-card-text",[a("v-text-field",{attrs:{"append-icon":"mdi-database-search",label:"Search","single-line":"","hide-details":""},model:{value:t.search,callback:function(e){t.search=e},expression:"search"}})],1)],1)],1)],1)],1)],1)},i=[],r=(a("96cf"),a("1da1")),s=a("a1b3"),o=a("e477"),l=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-row",[t._l(t.daftar_persyaratan,(function(t,e){return a("v-col",{key:t.persyaratan_id,attrs:{xs:"12",sm:"6",md:"4"}},[a("FileUpload",{attrs:{item:t,index:e}})],1)})),t.$vuetify.breakpoint.xsOnly?a("v-responsive",{attrs:{width:"100%"}}):t._e()],2)},u=[],c=a("5530"),h=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-form",{ref:"frmpersyaratan",attrs:{"lazy-validation":""},model:{value:t.form_valid,callback:function(e){t.form_valid=e},expression:"form_valid"}},[a("v-card",{staticClass:"mx-auto",attrs:{"max-width":"400"}},[a("v-img",{staticClass:"white--text align-end",attrs:{height:"200px",src:t.photoPersyaratan}}),a("v-card-text",{staticClass:"text--primary"},[a("div",[a("v-file-input",{attrs:{accept:"image/jpeg,image/png",label:t.item.nama_persyaratan+" (.png atau .jpg)",rules:t.rule_foto,"show-size":""},on:{change:t.previewImage},model:{value:t.filepersyaratan[t.index],callback:function(e){t.$set(t.filepersyaratan,t.index,e)},expression:"filepersyaratan[index]"}})],1)]),a("v-card-actions",[a("v-spacer"),a("v-btn",{attrs:{color:"orange",text:"",loading:t.btnLoading,disabled:t.btnLoading},on:{click:function(e){return t.upload(t.index,t.item)}}},[t._v(" Upload ")]),a("v-btn",{attrs:{color:"orange",text:"",loading:t.btnLoading,disabled:t.btnLoading||t.btnHapus},on:{click:function(e){return t.hapusfilepersysaratan(t.item)}}},[t._v(" Hapus ")])],1)],1)],1)},d=[],p=(a("a9e3"),a("2f62")),f={name:"FileUploadPersyaratan",created:function(){null==this.item.path||null==this.item.persyaratan_pmb_id?this.image_prev=this.item.path:(this.btnHapus=!1,this.image_prev=this.$api.url+"/"+this.item.path)},props:{index:Number,item:Object},data:function(){return{btnHapus:!0,btnLoading:!1,image_prev:null,form_valid:!0,filepersyaratan:[],rule_foto:[function(t){return!!t||"Mohon pilih gambar !!!"},function(t){return!t||t.size<2e6||"File foto harus kurang dari 2MB."}]}},methods:{previewImage:function(t){var e=this;if("undefined"===typeof t)this.image_prev=null;else{var a=new FileReader;a.readAsDataURL(t),a.onload=function(t){e.image_prev=t.target.result}}},upload:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(e,a){var n,i,r=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(n=a,!this.$refs.frmpersyaratan.validate()){t.next=11;break}if("undefined"===typeof this.filepersyaratan[e]){t.next=11;break}return this.btnLoading=!0,i=new FormData,i.append("nama_persyaratan",n.nama_persyaratan),i.append("persyaratan_id",n.persyaratan_id),i.append("persyaratan_pmb_id",n.persyaratan_pmb_id),i.append("foto",this.filepersyaratan[e]),t.next=11,this.$ajax.post("/spmb/pmbpersyaratan/upload/"+this.ATTRIBUTE_USER("id"),i,{headers:{Authorization:this.TOKEN,"Content-Type":"multipart/form-data"}}).then((function(){r.btnHapus=!1,r.btnLoading=!1})).catch((function(){r.btnLoading=!1}));case 11:case"end":return t.stop()}}),t,this)})));function e(e,a){return t.apply(this,arguments)}return e}(),hapusfilepersysaratan:function(t){var e=this;this.$root.$confirm.open("Delete","Apakah Anda ingin menghapus persyaratan "+t.nama_persyaratan+" ?",{color:"red"}).then((function(n){n&&e.$ajax.post("/spmb/pmbpersyaratan/hapusfilepersyaratan/"+t.persyaratan_pmb_id,{_method:"DELETE"},{headers:{Authorization:e.TOKEN}}).then((function(){e.btnHapus=!0,e.photoPersyaratan=a("bd21"),e.btnLoading=!1})).catch((function(){e.btnLoading=!1}))}))}},computed:Object(c["a"])({},Object(p["b"])("auth",{TOKEN:"Token",ATTRIBUTE_USER:"AtributeUser"}),{photoPersyaratan:{get:function(){return null==this.image_prev?a("bd21"):this.image_prev},set:function(t){this.image_prev=t}}})},m=f,b=a("2877"),v=a("6544"),g=a.n(v),y=a("8336"),_=a("b0af"),x=a("99d9"),k=(a("99af"),a("a623"),a("4160"),a("caad"),a("d81d"),a("13d5"),a("fb6a"),a("a434"),a("b0c0"),a("159b"),a("2909")),w=a("53ca"),$=(a("5803"),a("2677")),V=a("cc20"),C=a("80d2"),S=a("d9bd"),O=$["a"].extend({name:"v-file-input",model:{prop:"value",event:"change"},props:{chips:Boolean,clearable:{type:Boolean,default:!0},counterSizeString:{type:String,default:"$vuetify.fileInput.counterSize"},counterString:{type:String,default:"$vuetify.fileInput.counter"},placeholder:String,prependIcon:{type:String,default:"$file"},readonly:{type:Boolean,default:!1},showSize:{type:[Boolean,Number],default:!1,validator:function(t){return"boolean"===typeof t||[1e3,1024].includes(t)}},smallChips:Boolean,truncateLength:{type:[Number,String],default:22},type:{type:String,default:"file"},value:{default:void 0,validator:function(t){return Object(C["E"])(t).every((function(t){return null!=t&&"object"===Object(w["a"])(t)}))}}},computed:{classes:function(){return Object(c["a"])({},$["a"].options.computed.classes.call(this),{"v-file-input":!0})},computedCounterValue:function(){var t=this.isMultiple&&this.lazyValue?this.lazyValue.length:this.lazyValue instanceof File?1:0;if(!this.showSize)return this.$vuetify.lang.t(this.counterString,t);var e=this.internalArrayValue.reduce((function(t,e){var a=e.size,n=void 0===a?0:a;return t+n}),0);return this.$vuetify.lang.t(this.counterSizeString,t,Object(C["u"])(e,1024===this.base))},internalArrayValue:function(){return Object(C["E"])(this.internalValue)},internalValue:{get:function(){return this.lazyValue},set:function(t){this.lazyValue=t,this.$emit("change",this.lazyValue)}},isDirty:function(){return this.internalArrayValue.length>0},isLabelActive:function(){return this.isDirty},isMultiple:function(){return this.$attrs.hasOwnProperty("multiple")},text:function(){var t=this;return this.isDirty?this.internalArrayValue.map((function(e){var a=e.name,n=void 0===a?"":a,i=e.size,r=void 0===i?0:i,s=t.truncateText(n);return t.showSize?"".concat(s," (").concat(Object(C["u"])(r,1024===t.base),")"):s})):[this.placeholder]},base:function(){return"boolean"!==typeof this.showSize?this.showSize:void 0},hasChips:function(){return this.chips||this.smallChips}},watch:{readonly:{handler:function(t){!0===t&&Object(S["b"])("readonly is not supported on <v-file-input>",this)},immediate:!0},value:function(t){var e=this.isMultiple?t:t?[t]:[];Object(C["j"])(e,this.$refs.input.files)||(this.$refs.input.value="")}},methods:{clearableCallback:function(){this.internalValue=this.isMultiple?[]:void 0,this.$refs.input.value=""},genChips:function(){var t=this;return this.isDirty?this.text.map((function(e,a){return t.$createElement(V["a"],{props:{small:t.smallChips},on:{"click:close":function(){var e=t.internalValue;e.splice(a,1),t.internalValue=e}}},[e])})):[]},genInput:function(){var t=$["a"].options.methods.genInput.call(this);return delete t.data.domProps.value,delete t.data.on.input,t.data.on.change=this.onInput,[this.genSelections(),t]},genPrependSlot:function(){var t=this;if(!this.prependIcon)return null;var e=this.genIcon("prepend",(function(){t.$refs.input.click()}));return this.genSlot("prepend","outer",[e])},genSelectionText:function(){var t=this.text.length;return t<2?this.text:this.showSize&&!this.counter?[this.computedCounterValue]:[this.$vuetify.lang.t(this.counterString,t)]},genSelections:function(){var t=this,e=[];return this.isDirty&&this.$scopedSlots.selection?this.internalArrayValue.forEach((function(a,n){t.$scopedSlots.selection&&e.push(t.$scopedSlots.selection({text:t.text[n],file:a,index:n}))})):e.push(this.hasChips&&this.isDirty?this.genChips():this.genSelectionText()),this.$createElement("div",{staticClass:"v-file-input__text",class:{"v-file-input__text--placeholder":this.placeholder&&!this.isDirty,"v-file-input__text--chips":this.hasChips&&!this.$scopedSlots.selection}},e)},genTextFieldSlot:function(){var t=this,e=$["a"].options.methods.genTextFieldSlot.call(this);return e.data.on=Object(c["a"])({},e.data.on||{},{click:function(){return t.$refs.input.click()}}),e},onInput:function(t){var e=Object(k["a"])(t.target.files||[]);this.internalValue=this.isMultiple?e:e[0],this.initialValue=this.internalValue},onKeyDown:function(t){this.$emit("keydown",t)},truncateText:function(t){if(t.length<Number(this.truncateLength))return t;var e=Math.floor((Number(this.truncateLength)-1)/2);return"".concat(t.slice(0,e),"…").concat(t.slice(t.length-e))}}}),j=a("4bd4"),B=a("adda"),E=a("2fa4"),T=Object(b["a"])(m,h,d,!1,null,null,null),z=T.exports;g()(T,{VBtn:y["a"],VCard:_["a"],VCardActions:x["a"],VCardText:x["c"],VFileInput:O,VForm:j["a"],VImg:B["a"],VSpacer:E["a"]});var A={name:"FormPersyaratanPMB",created:function(){this.initialize()},data:function(){return{daftar_persyaratan:[]}},methods:{initialize:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(){var e=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,this.$ajax.get("/spmb/pmbpersyaratan/"+this.ATTRIBUTE_USER("id"),{headers:{Authorization:this.TOKEN}}).then((function(t){var a=t.data;e.daftar_persyaratan=a.persyaratan}));case 2:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}()},computed:Object(c["a"])({},Object(p["b"])("auth",{TOKEN:"Token",ATTRIBUTE_USER:"AtributeUser"})),components:{FileUpload:z}},I=A,R=a("62ad"),L=a("6b53"),P=a("0fd9"),F=Object(b["a"])(I,l,u,!1,null,null,null),D=F.exports;g()(F,{VCol:R["a"],VResponsive:L["a"],VRow:P["a"]});var U={name:"FormulirPendaftaran",created:function(){this.dashboard=this.$store.getters["uiadmin/getDefaultDashboard"]},mounted:function(){this.breadcrumbs=[{text:"HOME",disabled:!1,href:"/dashboard/"+this.access_token},{text:"SPMB",disabled:!1,href:"#"},{text:"FORMULIR PENDAFTARAN",disabled:!0,href:"#"}],this.initialize()},data:function(){return{access_token:null,token:null,tahun_masuk:null,breadcrumbs:[],dashboard:null,search:""}},methods:{initialize:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(){var e=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:if("mahasiswabaru"==this.dashboard||"mahasiswa"==this.dashboard){t.next=4;break}return this.datatableLoading=!0,t.next=4,this.$ajax.get("/spmb/pmbpersyaratan",{headers:{Authorization:this.token}}).then((function(t){var a=t.data;console.log(a),e.datatableLoading=!1}));case 4:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}(),setPageData:function(t){this.tahun_masuk=t.tahun_masuk,this.token=t.token,this.access_token=t.access_token}},components:{AdminLayout:s["a"],ModuleHeader:o["a"],FormPersyaratan:D}},M=U,N=a("0798"),H=a("2bc5"),G=a("a523"),K=a("132d"),J=a("8654"),Y=Object(b["a"])(M,n,i,!1,null,null,null);e["default"]=Y.exports;g()(Y,{VAlert:N["a"],VBreadcrumbs:H["a"],VCard:_["a"],VCardText:x["c"],VCol:R["a"],VContainer:G["a"],VIcon:K["a"],VRow:P["a"],VTextField:J["a"]})},2677:function(t,e,a){"use strict";var n=a("8654");e["a"]=n["a"]},"4bd4":function(t,e,a){"use strict";a("4de4"),a("7db0"),a("4160"),a("caad"),a("07ac"),a("2532"),a("159b");var n=a("5530"),i=a("58df"),r=a("7e2b"),s=a("3206");e["a"]=Object(i["a"])(r["a"],Object(s["b"])("form")).extend({name:"v-form",inheritAttrs:!1,props:{lazyValidation:Boolean,value:Boolean},data:function(){return{inputs:[],watchers:[],errorBag:{}}},watch:{errorBag:{handler:function(t){var e=Object.values(t).includes(!0);this.$emit("input",!e)},deep:!0,immediate:!0}},methods:{watchInput:function(t){var e=this,a=function(t){return t.$watch("hasError",(function(a){e.$set(e.errorBag,t._uid,a)}),{immediate:!0})},n={_uid:t._uid,valid:function(){},shouldValidate:function(){}};return this.lazyValidation?n.shouldValidate=t.$watch("shouldValidate",(function(i){i&&(e.errorBag.hasOwnProperty(t._uid)||(n.valid=a(t)))})):n.valid=a(t),n},validate:function(){return 0===this.inputs.filter((function(t){return!t.validate(!0)})).length},reset:function(){this.inputs.forEach((function(t){return t.reset()})),this.resetErrorBag()},resetErrorBag:function(){var t=this;this.lazyValidation&&setTimeout((function(){t.errorBag={}}),0)},resetValidation:function(){this.inputs.forEach((function(t){return t.resetValidation()})),this.resetErrorBag()},register:function(t){this.inputs.push(t),this.watchers.push(this.watchInput(t))},unregister:function(t){var e=this.inputs.find((function(e){return e._uid===t._uid}));if(e){var a=this.watchers.find((function(t){return t._uid===e._uid}));a&&(a.valid(),a.shouldValidate()),this.watchers=this.watchers.filter((function(t){return t._uid!==e._uid})),this.inputs=this.inputs.filter((function(t){return t._uid!==e._uid})),this.$delete(this.errorBag,e._uid)}}},render:function(t){var e=this;return t("form",{staticClass:"v-form",attrs:Object(n["a"])({novalidate:!0},this.attrs$),on:{submit:function(t){return e.$emit("submit",t)}}},this.$slots.default)}})},5803:function(t,e,a){},"8adc":function(t,e,a){},bd21:function(t,e,a){t.exports=a.p+"img/no-image.695dffd6.png"},cc20:function(t,e,a){"use strict";a("4de4"),a("4160");var n=a("3835"),i=a("5530"),r=(a("8adc"),a("58df")),s=a("0789"),o=a("9d26"),l=a("a9ad"),u=a("4e82"),c=a("7560"),h=a("f2e7"),d=a("1c87"),p=a("af2b"),f=a("d9bd");e["a"]=Object(r["a"])(l["a"],p["a"],d["a"],c["a"],Object(u["a"])("chipGroup"),Object(h["b"])("inputValue")).extend({name:"v-chip",props:{active:{type:Boolean,default:!0},activeClass:{type:String,default:function(){return this.chipGroup?this.chipGroup.activeClass:""}},close:Boolean,closeIcon:{type:String,default:"$delete"},disabled:Boolean,draggable:Boolean,filter:Boolean,filterIcon:{type:String,default:"$complete"},label:Boolean,link:Boolean,outlined:Boolean,pill:Boolean,tag:{type:String,default:"span"},textColor:String,value:null},data:function(){return{proxyClass:"v-chip--active"}},computed:{classes:function(){return Object(i["a"])({"v-chip":!0},d["a"].options.computed.classes.call(this),{"v-chip--clickable":this.isClickable,"v-chip--disabled":this.disabled,"v-chip--draggable":this.draggable,"v-chip--label":this.label,"v-chip--link":this.isLink,"v-chip--no-color":!this.color,"v-chip--outlined":this.outlined,"v-chip--pill":this.pill,"v-chip--removable":this.hasClose},this.themeClasses,{},this.sizeableClasses,{},this.groupClasses)},hasClose:function(){return Boolean(this.close)},isClickable:function(){return Boolean(d["a"].options.computed.isClickable.call(this)||this.chipGroup)}},created:function(){var t=this,e=[["outline","outlined"],["selected","input-value"],["value","active"],["@input","@active.sync"]];e.forEach((function(e){var a=Object(n["a"])(e,2),i=a[0],r=a[1];t.$attrs.hasOwnProperty(i)&&Object(f["a"])(i,r,t)}))},methods:{click:function(t){this.$emit("click",t),this.chipGroup&&this.toggle()},genFilter:function(){var t=[];return this.isActive&&t.push(this.$createElement(o["a"],{staticClass:"v-chip__filter",props:{left:!0}},this.filterIcon)),this.$createElement(s["b"],t)},genClose:function(){var t=this;return this.$createElement(o["a"],{staticClass:"v-chip__close",props:{right:!0,size:18},on:{click:function(e){e.stopPropagation(),e.preventDefault(),t.$emit("click:close"),t.$emit("update:active",!1)}}},this.closeIcon)},genContent:function(){return this.$createElement("span",{staticClass:"v-chip__content"},[this.filter&&this.genFilter(),this.$slots.default,this.hasClose&&this.genClose()])}},render:function(t){var e=[this.genContent()],a=this.generateRouteLink(),n=a.tag,r=a.data;r.attrs=Object(i["a"])({},r.attrs,{draggable:this.draggable?"true":void 0,tabindex:this.chipGroup&&!this.disabled?0:r.attrs.tabindex}),r.directives.push({name:"show",value:this.active}),r=this.setBackgroundColor(this.color,r);var s=this.textColor||this.outlined&&this.color;return t(n,this.setTextColor(s,r),e)}})}}]);