import{B as b,d as k,q as y,o as i,c as h,h as v,g as u,w as m,a as o,u as s,X as x,t as V,e as c,b as r,n as B,k as p,y as C,j as $}from"./app-58c3ca15.js";import{_ as q}from"./GuestLayout-400e8d5a.js";import{_ as f}from"./InputError-dc24760a.js";import{_}from"./InputLabel-69ab2fd7.js";import{P}from"./PrimaryButton-02a4193a.js";import{_ as g}from"./TextInput-7ce8902b.js";import"./ResponsiveNavLink-46f1027e.js";import"./_plugin-vue_export-helper-c27b6911.js";const N=["value"],S={__name:"Checkbox",props:{checked:{type:[Array,Boolean],required:!0},value:{default:null}},emits:["update:checked"],setup(l,{emit:e}){const d=l,n=b({get(){return d.checked},set(t){e("update:checked",t)}});return(t,a)=>k((i(),h("input",{type:"checkbox",value:l.value,"onUpdate:modelValue":a[0]||(a[0]=w=>n.value=w),class:"rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"},null,8,N)),[[y,n.value]])}},U=r("title",null,"Log in",-1),L={key:0,class:"mb-4 font-medium text-sm text-green-600"},R=["onSubmit"],j={class:"mt-4"},D={class:"block mt-4"},E={class:"flex items-center"},F=r("span",{class:"ml-2 text-sm text-gray-600"},"Remember me",-1),M={class:"flex items-center justify-end mt-4"},K={__name:"Login",props:{canResetPassword:{type:Boolean},status:{type:String}},setup(l){const e=v({email:"",password:"",remember:!1}),d=()=>{e.post(route("login"),{onFinish:()=>e.reset("password")})};return(n,t)=>(i(),u(q,null,{default:m(()=>[o(s(x),null,{default:m(()=>[U]),_:1}),l.status?(i(),h("div",L,V(l.status),1)):c("",!0),r("form",{onSubmit:$(d,["prevent"])},[r("div",null,[o(_,{for:"email",value:"Email"}),o(g,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:s(e).email,"onUpdate:modelValue":t[0]||(t[0]=a=>s(e).email=a),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),o(f,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),r("div",j,[o(_,{for:"password",value:"Password"}),o(g,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:s(e).password,"onUpdate:modelValue":t[1]||(t[1]=a=>s(e).password=a),required:"",autocomplete:"current-password"},null,8,["modelValue"]),o(f,{class:"mt-2",message:s(e).errors.password},null,8,["message"])]),r("div",D,[r("label",E,[o(S,{name:"remember",checked:s(e).remember,"onUpdate:checked":t[2]||(t[2]=a=>s(e).remember=a)},null,8,["checked"]),F])]),r("div",M,[l.canResetPassword?(i(),u(s(B),{key:0,href:n.route("password.request"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:m(()=>[p(" Forgot your password? ")]),_:1},8,["href"])):c("",!0),o(P,{class:C(["ml-4",{"opacity-25":s(e).processing}]),disabled:s(e).processing},{default:m(()=>[p(" Log in ")]),_:1},8,["class","disabled"])])],40,R)]),_:1}))}};export{K as default};
