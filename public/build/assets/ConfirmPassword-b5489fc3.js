import{h as n,o as l,g as d,w as t,a,u as o,X as c,b as e,k as p,z as u,j as f}from"./app-15dc6fea.js";import{_}from"./GuestLayout-c284f461.js";import{_ as w}from"./InputError-1f1c8eef.js";import{_ as b}from"./InputLabel-83d1a3cd.js";import{P as h}from"./PrimaryButton-29033063.js";import{_ as g}from"./TextInput-c813ead4.js";import"./ResponsiveNavLink-2279fa77.js";import"./_plugin-vue_export-helper-c27b6911.js";const x=e("div",{class:"mb-4 text-sm text-gray-600"}," This is a secure area of the application. Please confirm your password before continuing. ",-1),v=["onSubmit"],y={class:"flex justify-end mt-4"},T={__name:"ConfirmPassword",setup(P){const s=n({password:""}),i=()=>{s.post(route("password.confirm"),{onFinish:()=>s.reset()})};return(V,r)=>(l(),d(_,null,{default:t(()=>[a(o(c),{title:"Confirm Password"}),x,e("form",{onSubmit:f(i,["prevent"])},[e("div",null,[a(b,{for:"password",value:"Password"}),a(g,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:o(s).password,"onUpdate:modelValue":r[0]||(r[0]=m=>o(s).password=m),required:"",autocomplete:"current-password",autofocus:""},null,8,["modelValue"]),a(w,{class:"mt-2",message:o(s).errors.password},null,8,["message"])]),e("div",y,[a(h,{class:u(["ml-4",{"opacity-25":o(s).processing}]),disabled:o(s).processing},{default:t(()=>[p(" Confirm ")]),_:1},8,["class","disabled"])])],40,v)]),_:1}))}};export{T as default};
