import{B as V,z as N,A as T,x as l,e as o,f as m,j as C,n as w,C as A,i as h,t as L,b as I,h as a,F as B,m as R,D as U,q as g,E as O,s as y,G as x,H as E,r as j,y as F,k as S,d as p,I as H,p as z}from"./app-DhgeM_ro.js";import{_ as W}from"./_plugin-vue_export-helper-DlAUqK2U.js";var q=function(r){var t=r.dt;return`
.p-breadcrumb {
    background: `.concat(t("breadcrumb.background"),`;
    padding: `).concat(t("breadcrumb.padding"),`;
    overflow-x: auto;
}

.p-breadcrumb-list {
    margin: 0;
    padding: 0;
    list-style-type: none;
    display: flex;
    align-items: center;
    flex-wrap: nowrap;
    gap: `).concat(t("breadcrumb.gap"),`;
}

.p-breadcrumb-separator {
    display: flex;
    align-items: center;
    color: `).concat(t("breadcrumb.separator.color"),`;
}

.p-breadcrumb-separator-icon:dir(rtl) {
    transform: rotate(180deg);
}

.p-breadcrumb::-webkit-scrollbar {
    display: none;
}

.p-breadcrumb-item-link {
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: `).concat(t("breadcrumb.item.gap"),`;
    transition: background `).concat(t("breadcrumb.transition.duration"),", color ").concat(t("breadcrumb.transition.duration"),", outline-color ").concat(t("breadcrumb.transition.duration"),", box-shadow ").concat(t("breadcrumb.transition.duration"),`;
    border-radius: `).concat(t("breadcrumb.item.border.radius"),`;
    outline-color: transparent;
    color: `).concat(t("breadcrumb.item.color"),`;
}

.p-breadcrumb-item-link:focus-visible {
    box-shadow: `).concat(t("breadcrumb.item.focus.ring.shadow"),`;
    outline: `).concat(t("breadcrumb.item.focus.ring.width")," ").concat(t("breadcrumb.item.focus.ring.style")," ").concat(t("breadcrumb.item.focus.ring.color"),`;
    outline-offset: `).concat(t("breadcrumb.item.focus.ring.offset"),`;
}

.p-breadcrumb-item-link:hover .p-breadcrumb-item-label {
    color: `).concat(t("breadcrumb.item.hover.color"),`;
}

.p-breadcrumb-item-label {
    transition: inherit;
}

.p-breadcrumb-item-icon {
    color: `).concat(t("breadcrumb.item.icon.color"),`;
    transition: inherit;
}

.p-breadcrumb-item-link:hover .p-breadcrumb-item-icon {
    color: `).concat(t("breadcrumb.item.icon.hover.color"),`;
}
`)},G={root:"p-breadcrumb p-component",list:"p-breadcrumb-list",homeItem:"p-breadcrumb-home-item",separator:"p-breadcrumb-separator",separatorIcon:"p-breadcrumb-separator-icon",item:function(r){var t=r.instance;return["p-breadcrumb-item",{"p-disabled":t.disabled()}]},itemLink:"p-breadcrumb-item-link",itemIcon:"p-breadcrumb-item-icon",itemLabel:"p-breadcrumb-item-label"},$=V.extend({name:"breadcrumb",theme:q,classes:G}),J={name:"BaseBreadcrumb",extends:T,props:{model:{type:Array,default:null},home:{type:null,default:null}},style:$,provide:function(){return{$pcBreadcrumb:this,$parentInstance:this}}},P={name:"BreadcrumbItem",hostName:"Breadcrumb",extends:T,props:{item:null,templates:null,index:null},methods:{onClick:function(r){this.item.command&&this.item.command({originalEvent:r,item:this.item})},visible:function(){return typeof this.item.visible=="function"?this.item.visible():this.item.visible!==!1},disabled:function(){return typeof this.item.disabled=="function"?this.item.disabled():this.item.disabled},label:function(){return typeof this.item.label=="function"?this.item.label():this.item.label},isCurrentUrl:function(){var r=this.item,t=r.to,s=r.url,c=typeof window<"u"?window.location.pathname:"";return t===c||s===c?"page":void 0}},computed:{ptmOptions:function(){return{context:{item:this.item,index:this.index}}},getMenuItemProps:function(){var r=this;return{action:l({class:this.cx("itemLink"),"aria-current":this.isCurrentUrl(),onClick:function(s){return r.onClick(s)}},this.ptm("itemLink",this.ptmOptions)),icon:l({class:[this.cx("icon"),this.item.icon]},this.ptm("icon",this.ptmOptions)),label:l({class:this.cx("label")},this.ptm("label",this.ptmOptions))}}}},K=["href","target","aria-current"];function Q(e,r,t,s,c,i){return i.visible()?(o(),m("li",l({key:0,class:[e.cx("item"),t.item.class]},e.ptm("item",i.ptmOptions)),[t.templates.item?(o(),C(A(t.templates.item),{key:1,item:t.item,label:i.label(),props:i.getMenuItemProps},null,8,["item","label","props"])):(o(),m("a",l({key:0,href:t.item.url||"#",class:e.cx("itemLink"),target:t.item.target,"aria-current":i.isCurrentUrl(),onClick:r[0]||(r[0]=function(){return i.onClick&&i.onClick.apply(i,arguments)})},e.ptm("itemLink",i.ptmOptions)),[t.templates&&t.templates.itemicon?(o(),C(A(t.templates.itemicon),{key:0,item:t.item,class:w(e.cx("itemIcon",i.ptmOptions))},null,8,["item","class"])):t.item.icon?(o(),m("span",l({key:1,class:[e.cx("itemIcon"),t.item.icon]},e.ptm("itemIcon",i.ptmOptions)),null,16)):h("",!0),t.item.label?(o(),m("span",l({key:2,class:e.cx("itemLabel")},e.ptm("itemLabel",i.ptmOptions)),L(i.label()),17)):h("",!0)],16,K))],16)):h("",!0)}P.render=Q;var X={name:"Breadcrumb",extends:J,inheritAttrs:!1,components:{BreadcrumbItem:P,ChevronRightIcon:N}};function Y(e,r,t,s,c,i){var u=I("BreadcrumbItem"),f=I("ChevronRightIcon");return o(),m("nav",l({class:e.cx("root")},e.ptmi("root")),[a("ol",l({class:e.cx("list")},e.ptm("list")),[e.home?(o(),C(u,l({key:0,item:e.home,class:e.cx("homeItem"),templates:e.$slots,pt:e.pt,unstyled:e.unstyled},e.ptm("homeItem")),null,16,["item","class","templates","pt","unstyled"])):h("",!0),(o(!0),m(B,null,R(e.model,function(k,n){return o(),m(B,{key:k.label+"_"+n},[e.home||n!==0?(o(),m("li",l({key:0,class:e.cx("separator"),ref_for:!0},e.ptm("separator")),[U(e.$slots,"separator",{},function(){return[g(f,l({"aria-hidden":"true",class:e.cx("separatorIcon"),ref_for:!0},e.ptm("separatorIcon")),null,16,["class"])]})],16)):h("",!0),g(u,{item:k,index:n,templates:e.$slots,pt:e.pt,unstyled:e.unstyled},null,8,["item","index","templates","pt","unstyled"])],64)}),128))],16)],16)}X.render=Y;const b=O({ripple:!1,darkTheme:!1,inputStyle:"outlined",menuMode:"static",theme:"lara-light-indigo",scale:14,activeMenuItem:null}),d=O({staticMenuDesktopInactive:!1,overlayMenuActive:!1,profileSidebarVisible:!1,configSidebarVisible:!1,staticMenuMobileActive:!1,menuHoverActive:!1});function D(){const e=(u,f)=>{b.darkTheme=f,b.theme=u},r=u=>{b.scale=u},t=u=>{b.activeMenuItem=u.value||u},s=()=>{b.menuMode==="overlay"&&(d.overlayMenuActive=!d.overlayMenuActive),window.innerWidth>991?d.staticMenuDesktopInactive=!d.staticMenuDesktopInactive:d.staticMenuMobileActive=!d.staticMenuMobileActive},c=y(()=>d.overlayMenuActive||d.staticMenuMobileActive),i=y(()=>b.darkTheme);return{layoutConfig:x(b),layoutState:x(d),changeThemeSettings:e,setScale:r,onMenuToggle:s,isSidebarActive:c,isDarkTheme:i,setActiveMenuItem:t}}const Z={class:"layout-topbar"},_={class:"p-link layout-topbar-button layout-topbar-button-c nav-item dropdown",role:"button","data-bs-toggle":"dropdown"},ee={class:"dropdown-menu dropdown-menu-end border-0 shadow-sm"},te={key:0},ne={key:1},ae=["disabled"],re={class:"nav-link dropdown-toggle ms-3 me-2",href:"#",role:"button","data-bs-toggle":"dropdown","aria-expanded":"false"},ie={__name:"AppTopbar",setup(e){const{onMenuToggle:r}=D(),{processing:t,logout:s}=E(),c=j(!1),i=F(),u=()=>{c.value=!c.value},f=y(()=>({"layout-topbar-menu-mobile-active":c.value}));return(k,n)=>{const M=I("router-link");return o(),m("div",Z,[g(M,{to:"/",class:"layout-topbar-logo"},{default:S(()=>n[5]||(n[5]=[a("img",{src:"/images/logo.svg",alt:"logo"},null,-1),a("span",null,null,-1)])),_:1}),a("button",{class:"p-link layout-menu-button layout-topbar-button",onClick:n[0]||(n[0]=v=>p(r)())},n[6]||(n[6]=[a("i",{class:"pi pi-bars"},null,-1)])),a("button",{class:"p-link layout-topbar-menu-button layout-topbar-button",onClick:n[1]||(n[1]=v=>u())},n[7]||(n[7]=[a("i",{class:"pi pi-ellipsis-v"},null,-1)])),a("div",{class:w(["layout-topbar-menu",f.value])},[a("button",_,[n[11]||(n[11]=a("i",{class:"pi pi-user"},null,-1)),a("ul",ee,[a("li",null,[g(M,{to:{name:"profile.index"},class:"dropdown-item"},{default:S(()=>n[8]||(n[8]=[H("Perfil")])),_:1})]),n[9]||(n[9]=a("li",null,[a("a",{class:"dropdown-item",href:"#"},"Preferencias")],-1)),(o(),m("li",te,[a("a",{class:"dropdown-item",href:"#",onClick:n[2]||(n[2]=v=>p(i).push({name:"admin.index"}))},"Panel Admin")])),(o(),m("li",ne,[a("a",{class:"dropdown-item",href:"#",onClick:n[3]||(n[3]=v=>p(i).push({name:"app"}))},"Panel Usuario")])),n[10]||(n[10]=a("li",null,[a("hr",{class:"dropdown-divider"})],-1)),a("li",null,[a("a",{class:w(["dropdown-item",{"opacity-25":p(t)}]),disabled:p(t),href:"javascript:void(0)",onClick:n[4]||(n[4]=(...v)=>p(s)&&p(s)(...v))},"Cerrar sessión",10,ae)])]),a("span",re," Hola, "+L(p(z)().user.name),1)])],2)])}}},me=W(ie,[["__scopeId","data-v-ff8b9f90"]]),oe={class:"layout-footer"},ue={__name:"AppFooter",setup(e){const{layoutConfig:r}=D();return y(()=>`layout/images/${r.darkTheme.value?"logo-white":"logo-dark"}.svg`),(t,s)=>(o(),m("div",oe,s[0]||(s[0]=[a("span",{class:"font-medium ml-2"},"Ejemplo DAW2",-1)])))}};export{me as A,ue as _,X as s,D as u};
