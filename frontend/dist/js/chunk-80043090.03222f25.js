(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-80043090"],{"071d":function(t,e,n){"use strict";n.r(e);var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("AdminLayout",[n("ModuleHeader",{scopedSlots:t._u([{key:"icon",fn:function(){return[t._v(" mdi-file-document-edit-outline ")]},proxy:!0},{key:"name",fn:function(){return[t._v(" PERSYARATAN PMB ")]},proxy:!0},{key:"subtitle",fn:function(){return[t._v(" TAHUN "+t._s(t._f("formatTA")(t.tahunmasuk))+" ")]},proxy:!0},{key:"breadcrumbs",fn:function(){return[n("v-breadcrumbs",{staticClass:"pa-0",attrs:{items:t.breadcrumbs},scopedSlots:t._u([{key:"divider",fn:function(){return[n("v-icon",[t._v("mdi-chevron-right")])]},proxy:!0}])})]},proxy:!0},"mahasiswabaru"==t.dashboard?{key:"desc",fn:function(){return[n("v-alert",{attrs:{color:"cyan",border:"left","colored-border":"",type:"info"}},[t._v(" Halaman ini digunakan untuk upload peryaratan pendaftaran, mohon diisi dengan lengkap dan benar. ")])]},proxy:!0}:{key:"desc",fn:function(){return[n("v-alert",{attrs:{color:"cyan",border:"left","colored-border":"",type:"info"}},[t._v(" Halaman ini berisi file-file persyaratan pendaftaran mahasiswa baru, mohon disesuaikan di filter tahun akademik, kemudian tekan refresh. ")])]},proxy:!0}],null,!0)}),"mahasiswabaru"==t.dashboard?n("v-container",[n("FormPersyaratan")],1):t._e()],1)},i=[],r=n("5530"),s=(n("96cf"),n("1da1")),o=n("2f62"),u=n("a1b3"),l=n("e477"),c=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-row",[t._l(t.daftar_persyaratan,(function(t,e){return n("v-col",{key:t.persyaratan_id,attrs:{xs:"12",sm:"6",md:"4"}},[n("FileUpload",{attrs:{item:t,index:e}})],1)})),t.$vuetify.breakpoint.xsOnly?n("v-responsive",{attrs:{width:"100%"}}):t._e()],2)},d=[],h=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-form",{ref:"frmpersyaratan",attrs:{"lazy-validation":""},model:{value:t.form_valid,callback:function(e){t.form_valid=e},expression:"form_valid"}},[n("v-card",{staticClass:"mx-auto",attrs:{"max-width":"400"}},[n("v-img",{staticClass:"white--text align-end",attrs:{height:"200px",src:t.photoPersyaratan}}),n("v-card-text",{staticClass:"text--primary"},[n("div",[n("v-file-input",{attrs:{accept:"image/jpeg,image/png",label:t.item.nama_persyaratan+" (.png atau .jpg)",rules:t.rule_foto,"show-size":""},on:{change:t.previewImage},model:{value:t.filepersyaratan[t.index],callback:function(e){t.$set(t.filepersyaratan,t.index,e)},expression:"filepersyaratan[index]"}})],1)]),n("v-card-actions",[n("v-spacer"),n("v-btn",{attrs:{color:"orange",text:""},on:{click:function(e){return t.upload(t.index,t.item)}}},[t._v(" Upload ")]),n("v-btn",{attrs:{color:"orange",text:""}},[t._v(" Hapus ")])],1)],1)],1)},f=[],p=(n("a9e3"),{name:"FileUploadPersyaratan",created:function(){this.image_prev=this.item.path},props:{index:Number,item:Object},data:function(){return{image_prev:null,form_valid:!0,filepersyaratan:[],rule_foto:[function(t){return!!t||"Mohon pilih gambar !!!"},function(t){return!t||t.size<2e6||"File foto harus kurang dari 2MB."}]}},methods:{previewImage:function(t){var e=this;if("undefined"===typeof t)this.image_prev=null;else{var n=new FileReader;n.readAsDataURL(t),n.onload=function(t){e.image_prev=t.target.result}}},upload:function(t,e){var n=e;this.$refs.frmpersyaratan.validate()&&"undefined"!==typeof this.filepersyaratan[t]&&(console.log(n),console.log(this.filepersyaratan[t]))}},computed:{photoPersyaratan:{get:function(){return null==this.image_prev?n("bd21"):this.image_prev},set:function(t){this.image_prev=t}}}}),m=p,v=n("2877"),b=n("6544"),y=n.n(b),g=n("8336"),_=n("b0af"),x=n("99d9"),w=(n("99af"),n("a623"),n("4160"),n("caad"),n("d81d"),n("13d5"),n("fb6a"),n("a434"),n("b0c0"),n("159b"),n("2909")),V=n("53ca"),S=(n("5803"),n("2677")),k=n("cc20"),$=n("80d2"),O=n("d9bd"),j=S["a"].extend({name:"v-file-input",model:{prop:"value",event:"change"},props:{chips:Boolean,clearable:{type:Boolean,default:!0},counterSizeString:{type:String,default:"$vuetify.fileInput.counterSize"},counterString:{type:String,default:"$vuetify.fileInput.counter"},placeholder:String,prependIcon:{type:String,default:"$file"},readonly:{type:Boolean,default:!1},showSize:{type:[Boolean,Number],default:!1,validator:function(t){return"boolean"===typeof t||[1e3,1024].includes(t)}},smallChips:Boolean,truncateLength:{type:[Number,String],default:22},type:{type:String,default:"file"},value:{default:void 0,validator:function(t){return Object($["E"])(t).every((function(t){return null!=t&&"object"===Object(V["a"])(t)}))}}},computed:{classes:function(){return Object(r["a"])({},S["a"].options.computed.classes.call(this),{"v-file-input":!0})},computedCounterValue:function(){var t=this.isMultiple&&this.lazyValue?this.lazyValue.length:this.lazyValue instanceof File?1:0;if(!this.showSize)return this.$vuetify.lang.t(this.counterString,t);var e=this.internalArrayValue.reduce((function(t,e){var n=e.size,a=void 0===n?0:n;return t+a}),0);return this.$vuetify.lang.t(this.counterSizeString,t,Object($["u"])(e,1024===this.base))},internalArrayValue:function(){return Object($["E"])(this.internalValue)},internalValue:{get:function(){return this.lazyValue},set:function(t){this.lazyValue=t,this.$emit("change",this.lazyValue)}},isDirty:function(){return this.internalArrayValue.length>0},isLabelActive:function(){return this.isDirty},isMultiple:function(){return this.$attrs.hasOwnProperty("multiple")},text:function(){var t=this;return this.isDirty?this.internalArrayValue.map((function(e){var n=e.name,a=void 0===n?"":n,i=e.size,r=void 0===i?0:i,s=t.truncateText(a);return t.showSize?"".concat(s," (").concat(Object($["u"])(r,1024===t.base),")"):s})):[this.placeholder]},base:function(){return"boolean"!==typeof this.showSize?this.showSize:void 0},hasChips:function(){return this.chips||this.smallChips}},watch:{readonly:{handler:function(t){!0===t&&Object(O["b"])("readonly is not supported on <v-file-input>",this)},immediate:!0},value:function(t){var e=this.isMultiple?t:t?[t]:[];Object($["j"])(e,this.$refs.input.files)||(this.$refs.input.value="")}},methods:{clearableCallback:function(){this.internalValue=this.isMultiple?[]:void 0,this.$refs.input.value=""},genChips:function(){var t=this;return this.isDirty?this.text.map((function(e,n){return t.$createElement(k["a"],{props:{small:t.smallChips},on:{"click:close":function(){var e=t.internalValue;e.splice(n,1),t.internalValue=e}}},[e])})):[]},genInput:function(){var t=S["a"].options.methods.genInput.call(this);return delete t.data.domProps.value,delete t.data.on.input,t.data.on.change=this.onInput,[this.genSelections(),t]},genPrependSlot:function(){var t=this;if(!this.prependIcon)return null;var e=this.genIcon("prepend",(function(){t.$refs.input.click()}));return this.genSlot("prepend","outer",[e])},genSelectionText:function(){var t=this.text.length;return t<2?this.text:this.showSize&&!this.counter?[this.computedCounterValue]:[this.$vuetify.lang.t(this.counterString,t)]},genSelections:function(){var t=this,e=[];return this.isDirty&&this.$scopedSlots.selection?this.internalArrayValue.forEach((function(n,a){t.$scopedSlots.selection&&e.push(t.$scopedSlots.selection({text:t.text[a],file:n,index:a}))})):e.push(this.hasChips&&this.isDirty?this.genChips():this.genSelectionText()),this.$createElement("div",{staticClass:"v-file-input__text",class:{"v-file-input__text--placeholder":this.placeholder&&!this.isDirty,"v-file-input__text--chips":this.hasChips&&!this.$scopedSlots.selection}},e)},genTextFieldSlot:function(){var t=this,e=S["a"].options.methods.genTextFieldSlot.call(this);return e.data.on=Object(r["a"])({},e.data.on||{},{click:function(){return t.$refs.input.click()}}),e},onInput:function(t){var e=Object(w["a"])(t.target.files||[]);this.internalValue=this.isMultiple?e:e[0],this.initialValue=this.internalValue},onKeyDown:function(t){this.$emit("keydown",t)},truncateText:function(t){if(t.length<Number(this.truncateLength))return t;var e=Math.floor((Number(this.truncateLength)-1)/2);return"".concat(t.slice(0,e),"…").concat(t.slice(t.length-e))}}}),z=n("4bd4"),E=n("adda"),A=n("2fa4"),T=Object(v["a"])(m,h,f,!1,null,null,null),C=T.exports;y()(T,{VBtn:g["a"],VCard:_["a"],VCardActions:x["a"],VCardText:x["c"],VFileInput:j,VForm:z["a"],VImg:E["a"],VSpacer:A["a"]});var B={name:"FormPersyaratanPMB",created:function(){this.initialize()},data:function(){return{daftar_persyaratan:[]}},methods:{initialize:function(){var t=Object(s["a"])(regeneratorRuntime.mark((function t(){var e=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,this.$ajax.get("/spmb/pmbpersyaratan/"+this.ATTRIBUTE_USER("id"),{headers:{Authorization:this.TOKEN}}).then((function(t){var n=t.data;e.daftar_persyaratan=n.persyaratan}));case 2:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}()},computed:Object(r["a"])({},Object(o["b"])("auth",{TOKEN:"Token",ATTRIBUTE_USER:"AtributeUser"})),components:{FileUpload:C}},I=B,R=n("62ad"),F=n("6b53"),M=n("0fd9"),P=Object(v["a"])(I,c,d,!1,null,null,null),N=P.exports;y()(P,{VCol:R["a"],VResponsive:F["a"],VRow:M["a"]});var D={name:"FormulirPendaftaran",created:function(){this.breadcrumbs=[{text:"HOME",disabled:!1,href:"/dashboard/"+this.ACCESS_TOKEN},{text:"SPMB",disabled:!1,href:"#"},{text:"FORMULIR PENDAFTARAN",disabled:!0,href:"#"}],this.initialize()},data:function(){return{breadcrumbs:[],dashboard:null,form_valid:!0,frmmhsbaru:{}}},methods:{initialize:function(){var t=Object(s["a"])(regeneratorRuntime.mark((function t(){return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:this.dashboard=this.$store.getters["uiadmin/getDefaultDashboard"],t.t0=this.dashboard,t.next="mahasiswabaru"===t.t0?4:5;break;case 4:return t.abrupt("break",5);case 5:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}()},computed:Object(r["a"])({},Object(o["b"])("auth",{ACCESS_TOKEN:"AccessToken",TOKEN:"Token"}),{tahunmasuk:function(){return this.$store.getters["uiadmin/getTahunMasuk"]}}),components:{AdminLayout:u["a"],ModuleHeader:l["a"],FormPersyaratan:N}},U=D,L=n("0798"),H=n("2bc5"),K=n("a523"),J=n("132d"),Y=Object(v["a"])(U,a,i,!1,null,null,null);e["default"]=Y.exports;y()(Y,{VAlert:L["a"],VBreadcrumbs:H["a"],VContainer:K["a"],VIcon:J["a"]})},2677:function(t,e,n){"use strict";var a=n("8654");e["a"]=a["a"]},"4bd4":function(t,e,n){"use strict";n("4de4"),n("7db0"),n("4160"),n("caad"),n("07ac"),n("2532"),n("159b");var a=n("5530"),i=n("58df"),r=n("7e2b"),s=n("3206");e["a"]=Object(i["a"])(r["a"],Object(s["b"])("form")).extend({name:"v-form",inheritAttrs:!1,props:{lazyValidation:Boolean,value:Boolean},data:function(){return{inputs:[],watchers:[],errorBag:{}}},watch:{errorBag:{handler:function(t){var e=Object.values(t).includes(!0);this.$emit("input",!e)},deep:!0,immediate:!0}},methods:{watchInput:function(t){var e=this,n=function(t){return t.$watch("hasError",(function(n){e.$set(e.errorBag,t._uid,n)}),{immediate:!0})},a={_uid:t._uid,valid:function(){},shouldValidate:function(){}};return this.lazyValidation?a.shouldValidate=t.$watch("shouldValidate",(function(i){i&&(e.errorBag.hasOwnProperty(t._uid)||(a.valid=n(t)))})):a.valid=n(t),a},validate:function(){return 0===this.inputs.filter((function(t){return!t.validate(!0)})).length},reset:function(){this.inputs.forEach((function(t){return t.reset()})),this.resetErrorBag()},resetErrorBag:function(){var t=this;this.lazyValidation&&setTimeout((function(){t.errorBag={}}),0)},resetValidation:function(){this.inputs.forEach((function(t){return t.resetValidation()})),this.resetErrorBag()},register:function(t){this.inputs.push(t),this.watchers.push(this.watchInput(t))},unregister:function(t){var e=this.inputs.find((function(e){return e._uid===t._uid}));if(e){var n=this.watchers.find((function(t){return t._uid===e._uid}));n&&(n.valid(),n.shouldValidate()),this.watchers=this.watchers.filter((function(t){return t._uid!==e._uid})),this.inputs=this.inputs.filter((function(t){return t._uid!==e._uid})),this.$delete(this.errorBag,e._uid)}}},render:function(t){var e=this;return t("form",{staticClass:"v-form",attrs:Object(a["a"])({novalidate:!0},this.attrs$),on:{submit:function(t){return e.$emit("submit",t)}}},this.$slots.default)}})},5803:function(t,e,n){},bd21:function(t,e,n){t.exports=n.p+"img/no-image.695dffd6.png"}}]);