(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-538f23fe"],{"1f4f":function(t,e,i){"use strict";i("a9e3");var a=i("5530"),s=(i("8b37"),i("80d2")),r=i("7560"),n=i("58df");e["a"]=Object(n["a"])(r["a"]).extend({name:"v-simple-table",props:{dense:Boolean,fixedHeader:Boolean,height:[Number,String]},computed:{classes:function(){return Object(a["a"])({"v-data-table--dense":this.dense,"v-data-table--fixed-height":!!this.height&&!this.fixedHeader,"v-data-table--fixed-header":this.fixedHeader},this.themeClasses)}},methods:{genWrapper:function(){return this.$slots.wrapper||this.$createElement("div",{staticClass:"v-data-table__wrapper",style:{height:Object(s["g"])(this.height)}},[this.$createElement("table",this.$slots.default)])}},render:function(t){return t("div",{staticClass:"v-data-table",class:this.classes},[this.$slots.top,this.genWrapper(),this.$slots.bottom])}})},"4bd4":function(t,e,i){"use strict";i("4de4"),i("7db0"),i("4160"),i("caad"),i("07ac"),i("2532"),i("159b");var a=i("5530"),s=i("58df"),r=i("7e2b"),n=i("3206");e["a"]=Object(s["a"])(r["a"],Object(n["b"])("form")).extend({name:"v-form",provide:function(){return{form:this}},inheritAttrs:!1,props:{disabled:Boolean,lazyValidation:Boolean,readonly:Boolean,value:Boolean},data:function(){return{inputs:[],watchers:[],errorBag:{}}},watch:{errorBag:{handler:function(t){var e=Object.values(t).includes(!0);this.$emit("input",!e)},deep:!0,immediate:!0}},methods:{watchInput:function(t){var e=this,i=function(t){return t.$watch("hasError",(function(i){e.$set(e.errorBag,t._uid,i)}),{immediate:!0})},a={_uid:t._uid,valid:function(){},shouldValidate:function(){}};return this.lazyValidation?a.shouldValidate=t.$watch("shouldValidate",(function(s){s&&(e.errorBag.hasOwnProperty(t._uid)||(a.valid=i(t)))})):a.valid=i(t),a},validate:function(){return 0===this.inputs.filter((function(t){return!t.validate(!0)})).length},reset:function(){this.inputs.forEach((function(t){return t.reset()})),this.resetErrorBag()},resetErrorBag:function(){var t=this;this.lazyValidation&&setTimeout((function(){t.errorBag={}}),0)},resetValidation:function(){this.inputs.forEach((function(t){return t.resetValidation()})),this.resetErrorBag()},register:function(t){this.inputs.push(t),this.watchers.push(this.watchInput(t))},unregister:function(t){var e=this.inputs.find((function(e){return e._uid===t._uid}));if(e){var i=this.watchers.find((function(t){return t._uid===e._uid}));i&&(i.valid(),i.shouldValidate()),this.watchers=this.watchers.filter((function(t){return t._uid!==e._uid})),this.inputs=this.inputs.filter((function(t){return t._uid!==e._uid})),this.$delete(this.errorBag,e._uid)}}},render:function(t){var e=this;return t("form",{staticClass:"v-form",attrs:Object(a["a"])({novalidate:!0},this.attrs$),on:{submit:function(t){return e.$emit("submit",t)}}},this.$slots.default)}})},"641f":function(t,e,i){"use strict";i.r(e);var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("SystemUserLayout",[i("ModuleHeader",{scopedSlots:t._u([{key:"icon",fn:function(){return[t._v(" mdi-account ")]},proxy:!0},{key:"name",fn:function(){return[t._v(" USER PROFILE ")]},proxy:!0},{key:"breadcrumbs",fn:function(){return[i("v-breadcrumbs",{staticClass:"pa-0",attrs:{items:t.breadcrumbs},scopedSlots:t._u([{key:"divider",fn:function(){return[i("v-icon",[t._v("mdi-chevron-right")])]},proxy:!0}])})]},proxy:!0},{key:"desc",fn:function(){return[i("v-alert",{attrs:{color:"cyan",border:"left","colored-border":"",type:"info"}},[t._v(" berisi informasi profile user. ")])]},proxy:!0}])}),i("v-container",[i("v-row",{staticClass:"mb-4",attrs:{"no-gutters":""}},[i("v-col",{attrs:{md:"12"}},[i("v-card",[i("v-card-text",[i("v-simple-table",{scopedSlots:t._u([{key:"default",fn:function(){return[i("tbody",[i("tr",[i("td",{attrs:{width:"150"}},[t._v("ID")]),i("td",[t._v(t._s(t.$store.getters["auth/AttributeUser"]("id")))]),i("td",{attrs:{width:"150"}},[t._v("EMAIL")]),i("td",[t._v(t._s(t.$store.getters["auth/AttributeUser"]("email")))])]),i("tr",[i("td",{attrs:{width:"150"}},[t._v("USERNAME")]),i("td",[t._v(t._s(t.$store.getters["auth/AttributeUser"]("username")))]),i("td",{attrs:{width:"150"}},[t._v("CREATED")]),i("td",[t._v(t._s(t.$date(t.$store.getters["auth/AttributeUser"]("created_at")).format("DD/MM/YYYY HH:mm")))])]),i("tr",[i("td",{attrs:{width:"150"}},[t._v("NAMA")]),i("td",[t._v(t._s(t.$store.getters["auth/AttributeUser"]("name")))]),i("td",{attrs:{width:"150"}},[t._v("UPDATED")]),i("td",[t._v(t._s(t.$date(t.$store.getters["auth/AttributeUser"]("updated_at")).format("DD/MM/YYYY HH:mm")))])])])]},proxy:!0}])})],1)],1)],1)],1),i("v-row",[i("v-col",{attrs:{xs:"12",sm:"6",md:"6"}},[i("v-form",{ref:"frmdata",attrs:{"lazy-validation":""},model:{value:t.form_valid,callback:function(e){t.form_valid=e},expression:"form_valid"}},[i("v-card",[i("v-card-title",[i("span",{staticClass:"headline"},[t._v("GANTI PASSWORD")])]),i("v-card-text",[i("v-text-field",{attrs:{label:"PASSWORD BARU",type:"password",filled:"",rules:t.rule_user_password},model:{value:t.formdata.password,callback:function(e){t.$set(t.formdata,"password",e)},expression:"formdata.password"}})],1),i("v-card-actions",[i("v-spacer"),i("v-btn",{attrs:{color:"blue darken-1",text:"",loading:t.btnLoading,disabled:!t.form_valid||t.btnLoading},on:{click:function(e){return e.stopPropagation(),t.save(e)}}},[t._v("SIMPAN")])],1)],1)],1)],1)],1)],1)],1)},s=[],r=(i("96cf"),i("1da1")),n=i("e0b6"),o=i("e477"),c={name:"UsersProfile",created:function(){this.breadcrumbs=[{text:"HOME",disabled:!1,href:"/dashboard/"+this.$store.getters["auth/AccessToken"]},{text:"SYSTEM",disabled:!1,href:"#"},{text:"PROFILE USER",disabled:!0,href:"#"}]},data:function(){return{btnLoading:!1,datatable:[],avatar:null,form_valid:!0,formdata:{id:0,foto:null,password:"",created_at:"",updated_at:""},formdefault:{id:0,foto:null,password:"",created_at:"",updated_at:""},rule_foto:[function(t){return!!t||"Mohon pilih gambar !!!"},function(t){return!t||t.size<2e6||"File foto harus kurang dari 2MB."}],rule_user_password:[function(t){return!!t||"Mohon untuk di isi password User !!!"},function(t){return t.length>=8||"Minimial Password 8 karaketer"}]}},methods:{save:function(){var t=this;this.$refs.frmdata.validate()&&(this.btnLoading=!0,this.$ajax.post("/system/users/updatepassword/"+this.$store.getters["auth/AttributeUser"]("id"),{_method:"PUT",password:this.formdata.password},{headers:{Authorization:this.$store.getters["auth/Token"]}}).then((function(e){var i=e.data;t.$refs.frmdata.reset(),t.formdata.foto=i.foto,t.formdata=t.formdefault,t.btnLoading=!1})).catch((function(){t.btnLoading=!1})))},previewImage:function(t){var e=this;if("undefined"===typeof t)this.avatar=null;else{var i=new FileReader;i.readAsDataURL(t),i.onload=function(t){e.photoUser=t.target.result}}},uploadFoto:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(){var e,i=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(!this.$refs.frmuploadfoto.validate()){t.next=8;break}if(!this.formdata.foto){t.next=8;break}return this.btnLoading=!0,e=new FormData,e.append("foto",this.formdata.foto),t.next=7,this.$ajax.post("/setting/users/uploadfoto/"+this.$store.getters.User.id,e,{headers:{Authorization:this.$store.getters["auth/Token"],"Content-Type":"multipart/form-data"}}).then((function(t){var e=t.data;i.btnLoading=!1,i.$store.dispatch("updateFoto",e.user.foto)})).catch((function(){i.btnLoading=!1}));case 7:this.$refs.frmdata.reset();case 8:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}(),resetFoto:function(){var t=Object(r["a"])(regeneratorRuntime.mark((function t(){var e=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return this.btnLoading=!0,t.next=3,this.$ajax.post("/setting/users/resetfoto/"+this.$store.getters.User.id,{},{headers:{Authorization:this.$store.getters["auth/Token"]}}).then((function(t){var i=t.data;e.btnLoading=!1,e.$store.dispatch("updateFoto",i.user.foto)})).catch((function(){e.btnLoading=!1}));case 3:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}()},computed:{photoUser:{get:function(){if(null==this.avatar){var t=this.$api.url+"/"+this.$store.getters.User.foto;return t}return this.avatar},set:function(t){this.avatar=t}}},components:{SystemUserLayout:n["a"],ModuleHeader:o["a"]}},l=c,u=i("2877"),d=i("6544"),v=i.n(d),m=i("0798"),h=i("2bc5"),f=i("8336"),p=i("b0af"),_=i("99d9"),b=i("62ad"),g=i("a523"),S=i("4bd4"),E=i("132d"),T=i("0fd9"),A=i("1f4f"),C=i("2fa4"),w=i("8654"),$=Object(u["a"])(l,a,s,!1,null,null,null);e["default"]=$.exports;v()($,{VAlert:m["a"],VBreadcrumbs:h["a"],VBtn:f["a"],VCard:p["a"],VCardActions:_["a"],VCardText:_["c"],VCardTitle:_["d"],VCol:b["a"],VContainer:g["a"],VForm:S["a"],VIcon:E["a"],VRow:T["a"],VSimpleTable:A["a"],VSpacer:C["a"],VTextField:w["a"]})},"8b37":function(t,e,i){},e0b6:function(t,e,i){"use strict";var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("v-system-bar",{staticClass:"brown darken-2 white--text",attrs:{app:"",dark:""}}),i("v-app-bar",{attrs:{app:""}},[i("v-app-bar-nav-icon",{staticClass:"grey--text",on:{click:function(e){e.stopPropagation(),t.drawer=!t.drawer}}}),i("v-toolbar-title",{staticClass:"headline clickable",on:{click:function(e){e.stopPropagation(),t.$router.push("/dashboard/"+t.$store.getters["auth/AccessToken"]).catch((function(t){}))}}},[i("span",{staticClass:"hidden-sm-and-down"},[t._v(t._s(t.APP_NAME))])]),i("v-spacer"),t.CAN_ACCESS("SYSTEM-SETTING-GROUP")?i("v-menu",{attrs:{"close-on-content-click":!1,origin:"center center",transition:"scale-transition","offset-y":!0,bottom:"",left:""},scopedSlots:t._u([{key:"activator",fn:function(e){var a=e.on;return[i("v-btn",t._g({attrs:{icon:""}},a),[i("v-icon",[t._v("mdi-cogs")])],1)]}}],null,!1,501191807)},[i("v-list",{attrs:{dense:""}},[i("v-list-item",[i("v-list-item-icon",{staticClass:"mr-2"},[i("v-icon",[t._v("mdi-cogs")])],1),i("v-list-item-content",[i("v-list-item-title",{staticClass:"title"},[t._v(" KONFIGURASI SISTEM ")]),i("v-list-item-subtitle")],1)],1),i("v-divider"),i("v-list-item",{staticClass:"teal lighten-5"},[i("v-list-item-icon",{staticClass:"mr-2"},[i("v-icon",[t._v("mdi-account")])],1),i("v-list-item-content",[i("v-list-item-title",[t._v("PERGURUAN TINGGI")])],1)],1),t.CAN_ACCESS("SYSTEM-SETTING-IDENTITAS-DIRI")?i("v-list-item",{attrs:{link:"",to:"/system-setting/identitasdiri"}},[i("v-list-item-icon",{staticClass:"mr-2"},[i("v-icon",[t._v("mdi-chevron-right")])],1),i("v-list-item-content",[i("v-list-item-title",[t._v(" IDENTITAS DIRI ")])],1)],1):t._e(),i("v-list-item",{staticClass:"teal lighten-5"},[i("v-list-item-icon",{staticClass:"mr-2"},[i("v-icon",[t._v("mdi-account")])],1),i("v-list-item-content",[i("v-list-item-title",[t._v("USER")])],1)],1),t.CAN_ACCESS("SYSTEM-SETTING-PERMISSIONS")?i("v-list-item",{attrs:{link:"",to:"/system-setting/permissions"}},[i("v-list-item-icon",{staticClass:"mr-2"},[i("v-icon",[t._v("mdi-chevron-right")])],1),i("v-list-item-content",[i("v-list-item-title",[t._v(" PERMISSIONS ")])],1)],1):t._e(),t.CAN_ACCESS("SYSTEM-SETTING-ROLES")?i("v-list-item",{attrs:{link:"",to:"/system-setting/roles"}},[i("v-list-item-icon",{staticClass:"mr-2"},[i("v-icon",[t._v("mdi-chevron-right")])],1),i("v-list-item-content",[i("v-list-item-title",[t._v(" ROLES ")])],1)],1):t._e(),i("v-list-item",{staticClass:"teal lighten-5"},[i("v-list-item-icon",{staticClass:"mr-2"},[i("v-icon",[t._v("mdi-server-network")])],1),i("v-list-item-content",[i("v-list-item-title",[t._v("SERVER")])],1)],1),t.CAN_ACCESS("SYSTEM-SETTING-VARIABLES")?i("v-list-item",{attrs:{link:"",to:"/system-setting/captcha"}},[i("v-list-item-icon",{staticClass:"mr-2"},[i("v-icon",[t._v("mdi-chevron-right")])],1),i("v-list-item-content",[i("v-list-item-title",[t._v(" CAPTCHA ")])],1)],1):t._e()],1)],1):t._e(),i("v-divider",{staticClass:"mx-4",attrs:{inset:"",vertical:""}}),i("v-menu",{attrs:{"close-on-content-click":!0,origin:"center center",transition:"scale-transition","offset-y":!0,bottom:"",left:""},scopedSlots:t._u([{key:"activator",fn:function(e){var a=e.on;return[i("v-avatar",{attrs:{size:"30"}},[i("v-img",t._g({attrs:{src:t.photoUser}},a))],1)]}}])},[i("v-list",[i("v-list-item",[i("v-list-item-avatar",[i("v-img",{attrs:{src:t.photoUser}})],1),i("v-list-item-content",[i("v-list-item-title",{staticClass:"title"},[t._v(" "+t._s(t.ATTRIBUTE_USER("username"))+" ")]),i("v-list-item-subtitle",[t._v(" "+t._s(t.ROLE)+" ")])],1)],1),i("v-divider"),i("v-list-item",{attrs:{to:"/system-users/profil"}},[i("v-list-item-icon",{staticClass:"mr-2"},[i("v-icon",[t._v("mdi-account")])],1),i("v-list-item-title",[t._v("Profil")])],1),i("v-divider"),i("v-list-item",{on:{click:function(e){return e.preventDefault(),t.logout(e)}}},[i("v-list-item-icon",{staticClass:"mr-2"},[i("v-icon",[t._v("mdi-power")])],1),i("v-list-item-title",[t._v("Logout")])],1)],1)],1)],1),i("v-navigation-drawer",{staticClass:"brown darken-4",attrs:{width:"300",dark:"",temporary:t.isReportPage,app:""},model:{value:t.drawer,callback:function(e){t.drawer=e},expression:"drawer"}},[i("v-list-item",[i("v-list-item-avatar",[i("v-img",{attrs:{src:t.photoUser},on:{click:function(e){return e.stopPropagation(),t.toProfile(e)}}})],1),i("v-list-item-content",[i("v-list-item-title",{staticClass:"title"},[t._v(" "+t._s(t.ATTRIBUTE_USER("username"))+" ")]),i("v-list-item-subtitle",[t._v(" "+t._s(t.ROLE)+" ")])],1)],1),i("v-divider"),i("v-list",{attrs:{expand:""}},[t.CAN_ACCESS("SYSTEM-USERS-GROUP")?i("v-list-item",{attrs:{to:{path:"/system-users"},link:"",color:"deep-orange darken-1"}},[i("v-list-item-icon",{staticClass:"mr-2"},[i("v-icon",[t._v("mdi-account")])],1),i("v-list-item-content",[i("v-list-item-title",[t._v("MODULE USER SISTEM")])],1)],1):t._e(),t.CAN_ACCESS("SYSTEM-USERS-PMB")?i("v-list-item",{attrs:{link:"",to:"/system-users/pmb"}},[i("v-list-item-icon",{staticClass:"mr-2"},[i("v-icon",[t._v("mdi-account")])],1),i("v-list-item-content",[i("v-list-item-title",[t._v(" TIM PMB ")])],1)],1):t._e()],1)],1),i("v-main",{staticClass:"mx-4 mb-4"},[t._t("default")],2)],1)},s=[],r=(i("b0c0"),i("ac1f"),i("5319"),i("5530")),n=i("2f62"),o={name:"DataMasterLayout",data:function(){return{loginTime:0,drawer:null}},methods:{logout:function(){var t=this;this.loginTime=0,this.$ajax.post("/auth/logout",{},{headers:{Authorization:this.TOKEN}}).then((function(){t.$store.dispatch("auth/logout"),t.$store.dispatch("uifront/reinit"),t.$store.dispatch("uiadmin/reinit"),t.$router.push("/")})).catch((function(){t.$store.dispatch("auth/logout"),t.$store.dispatch("uifront/reinit"),t.$store.dispatch("uiadmin/reinit"),t.$router.push("/")}))},isBentukPT:function(t){return this.$store.getters["uifront/getBentukPT"]==t}},computed:Object(r["a"])({},Object(n["b"])("auth",{AUTHENTICATED:"Authenticated",ACCESS_TOKEN:"AccessToken",TOKEN:"Token",ROLE:"Role",CAN_ACCESS:"can",ATTRIBUTE_USER:"AttributeUser"}),{APP_NAME:function(){return"PortalEkampus v3"},photoUser:function(){var t,e=this.ATTRIBUTE_USER("foto");return t=""==e?this.$api.url+"/storage/images/users/no_photo.png":this.$api.url+"/"+e,t},isReportPage:function(){return"ReportFormBMurni"==this.$route.name}}),watch:{loginTime:{handler:function(t){var e=this;t>=0?setTimeout((function(){e.loginTime=1==e.AUTHENTICATED?e.loginTime+1:-1}),1e3):(this.$store.dispatch("auth/logout"),this.$router.replace("/login"))},immediate:!0}}},c=o,l=i("2877"),u=i("6544"),d=i.n(u),v=i("40dc"),m=i("5bc1"),h=i("8212"),f=i("8336"),p=i("ce7e"),_=i("132d"),b=i("adda"),g=i("8860"),S=i("da13"),E=i("8270"),T=i("5d23"),A=i("34c3"),C=i("f6c4"),w=i("e449"),$=i("f774"),k=i("2fa4"),I=i("afd9"),R=i("2a7f"),y=Object(l["a"])(c,a,s,!1,null,null,null);e["a"]=y.exports;d()(y,{VAppBar:v["a"],VAppBarNavIcon:m["a"],VAvatar:h["a"],VBtn:f["a"],VDivider:p["a"],VIcon:_["a"],VImg:b["a"],VList:g["a"],VListItem:S["a"],VListItemAvatar:E["a"],VListItemContent:T["a"],VListItemIcon:A["a"],VListItemSubtitle:T["b"],VListItemTitle:T["c"],VMain:C["a"],VMenu:w["a"],VNavigationDrawer:$["a"],VSpacer:k["a"],VSystemBar:I["a"],VToolbarTitle:R["a"]})}}]);