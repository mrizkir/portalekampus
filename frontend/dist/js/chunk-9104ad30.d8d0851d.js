(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-9104ad30"],{"132d":function(t,e,i){"use strict";i("7db0"),i("caad"),i("c975"),i("fb6a"),i("45fc"),i("a9e3"),i("2532"),i("498a"),i("c96a");var n,a=i("5530"),s=(i("4804"),i("7e2b")),o=i("a9ad"),r=i("af2b"),c=i("7560"),l=i("80d2"),h=i("2b0e"),u=i("58df");function d(t){return["fas","far","fal","fab","fad"].some((function(e){return t.includes(e)}))}function p(t){return/^[mzlhvcsqta]\s*[-+.0-9][^mlhvzcsqta]+/i.test(t)&&/[\dz]$/i.test(t)&&t.length>4}(function(t){t["xSmall"]="12px",t["small"]="16px",t["default"]="24px",t["medium"]="28px",t["large"]="36px",t["xLarge"]="40px"})(n||(n={}));var f=Object(u["a"])(s["a"],o["a"],r["a"],c["a"]).extend({name:"v-icon",props:{dense:Boolean,disabled:Boolean,left:Boolean,right:Boolean,size:[Number,String],tag:{type:String,required:!1,default:"i"}},computed:{medium:function(){return!1},hasClickListener:function(){return Boolean(this.listeners$.click||this.listeners$["!click"])}},methods:{getIcon:function(){var t="";return this.$slots.default&&(t=this.$slots.default[0].text.trim()),Object(l["s"])(this,t)},getSize:function(){var t={xSmall:this.xSmall,small:this.small,medium:this.medium,large:this.large,xLarge:this.xLarge},e=Object(l["p"])(t).find((function(e){return t[e]}));return e&&n[e]||Object(l["f"])(this.size)},getDefaultData:function(){var t={staticClass:"v-icon notranslate",class:{"v-icon--disabled":this.disabled,"v-icon--left":this.left,"v-icon--link":this.hasClickListener,"v-icon--right":this.right,"v-icon--dense":this.dense},attrs:Object(a["a"])({"aria-hidden":!this.hasClickListener,disabled:this.hasClickListener&&this.disabled,type:this.hasClickListener?"button":void 0},this.attrs$),on:this.listeners$};return t},applyColors:function(t){t.class=Object(a["a"])({},t.class,{},this.themeClasses),this.setTextColor(this.color,t)},renderFontIcon:function(t,e){var i=[],n=this.getDefaultData(),a="material-icons",s=t.indexOf("-"),o=s<=-1;o?i.push(t):(a=t.slice(0,s),d(a)&&(a="")),n.class[a]=!0,n.class[t]=!o;var r=this.getSize();return r&&(n.style={fontSize:r}),this.applyColors(n),e(this.hasClickListener?"button":this.tag,n,i)},renderSvgIcon:function(t,e){var i=this.getSize(),n=Object(a["a"])({},this.getDefaultData(),{style:i?{fontSize:i,height:i,width:i}:void 0});n.class["v-icon--svg"]=!0,this.applyColors(n);var s={attrs:{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",height:i||"24",width:i||"24",role:"img","aria-hidden":!0}};return e(this.hasClickListener?"button":"span",n,[e("svg",s,[e("path",{attrs:{d:t}})])])},renderSvgIconComponent:function(t,e){var i=this.getDefaultData();i.class["v-icon--is-component"]=!0;var n=this.getSize();n&&(i.style={fontSize:n,height:n,width:n}),this.applyColors(i);var a=t.component;return i.props=t.props,i.nativeOn=i.on,e(a,i)}},render:function(t){var e=this.getIcon();return"string"===typeof e?p(e)?this.renderSvgIcon(e,t):this.renderFontIcon(e,t):this.renderSvgIconComponent(e,t)}});e["a"]=h["a"].extend({name:"v-icon",$_wrapperFor:f,functional:!0,render:function(t,e){var i=e.data,n=e.children,a="";return i.domProps&&(a=i.domProps.textContent||i.domProps.innerHTML||a,delete i.domProps.textContent,delete i.domProps.innerHTML),t(f,i,a?[a]:n)}})},"3a66":function(t,e,i){"use strict";i.d(e,"a",(function(){return s}));var n=i("fe6c"),a=i("58df");function s(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:[];return Object(a["a"])(Object(n["b"])(["absolute","fixed"])).extend({name:"applicationable",props:{app:Boolean},computed:{applicationProperty:function(){return t}},watch:{app:function(t,e){e?this.removeApplication(!0):this.callUpdate()},applicationProperty:function(t,e){this.$vuetify.application.unregister(this._uid,e)}},activated:function(){this.callUpdate()},created:function(){for(var t=0,i=e.length;t<i;t++)this.$watch(e[t],this.callUpdate);this.callUpdate()},mounted:function(){this.callUpdate()},deactivated:function(){this.removeApplication()},destroyed:function(){this.removeApplication()},methods:{callUpdate:function(){this.app&&this.$vuetify.application.register(this._uid,this.applicationProperty,this.updateApplication())},removeApplication:function(){var t=arguments.length>0&&void 0!==arguments[0]&&arguments[0];(t||this.app)&&this.$vuetify.application.unregister(this._uid,this.applicationProperty)},updateApplication:function(){return 0}}})}},4804:function(t,e,i){},8308:function(t,e,i){},"969c":function(t,e,i){"use strict";i.r(e);var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("AdminLayout",[i("ModuleHeader")],1)},a=[],s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",[i("v-system-bar",{attrs:{app:"",dark:"",color:"primary white--text"}})],1)},o=[],r=i("2877"),c=i("6544"),l=i.n(c),h=(i("a9e3"),i("c7cd"),i("5530")),u=(i("8308"),i("3a66")),d=i("a9ad"),p=i("7560"),f=i("58df"),v=i("80d2"),m=Object(f["a"])(Object(u["a"])("bar",["height","window"]),d["a"],p["a"]).extend({name:"v-system-bar",props:{height:[Number,String],lightsOut:Boolean,window:Boolean},computed:{classes:function(){return Object(h["a"])({"v-system-bar--lights-out":this.lightsOut,"v-system-bar--absolute":this.absolute,"v-system-bar--fixed":!this.absolute&&(this.app||this.fixed),"v-system-bar--window":this.window},this.themeClasses)},computedHeight:function(){return this.height?isNaN(parseInt(this.height))?this.height:parseInt(this.height):this.window?32:24},styles:function(){return{height:Object(v["f"])(this.computedHeight)}}},methods:{updateApplication:function(){return this.$el?this.$el.clientHeight:this.computedHeight}},render:function(t){var e={staticClass:"v-system-bar",class:this.classes,style:this.styles,on:this.$listeners};return t("div",this.setBackgroundColor(this.color,e),Object(v["k"])(this))}}),g={},b=Object(r["a"])(g,s,o,!1,null,null,null),y=b.exports;l()(b,{VSystemBar:m});var x=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-container",{attrs:{fluid:t.isReportPage}},[i("v-row",{attrs:{"no-gutters":""}},[i("v-col",[i("h1",{staticClass:"subheading grey--text"},[i("v-icon")],1)]),i("v-col",[t._t("default")],2)],1)],1)},w=[],C={name:"ModuleHeader",computed:{isReportPage:function(){return!0}}},O=C,S=i("62ad"),$=i("a523"),j=i("132d"),k=i("0fd9"),z=Object(r["a"])(O,x,w,!1,null,null,null),L=z.exports;l()(z,{VCol:S["a"],VContainer:$["a"],VIcon:j["a"],VRow:k["a"]});var B={name:"Dashboard",created:function(){this.initialize()},data:function(){return{}},methods:{initialize:function(){}},computed:{},components:{AdminLayout:y,ModuleHeader:L}},I=B,P=Object(r["a"])(I,n,a,!1,null,null,null);e["default"]=P.exports}}]);