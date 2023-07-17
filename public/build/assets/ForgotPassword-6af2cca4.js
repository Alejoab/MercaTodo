import{h as u,o as l,g as d,w as i,a as t,u as s,X as c,c as p,t as f,e as _,b as a,k as w,z as g,j as y}from"./app-32a5c4fa.js";import{_ as b}from"./GuestLayout-9d1a5470.js";import{_ as k}from"./InputError-499f6de0.js";import{_ as x}from"./InputLabel-609d8761.js";import{P as h}from"./PrimaryButton-e8cde6a0.js";import{_ as v}from"./TextInput-9f124e2f.js";import"./ResponsiveNavLink-34f70eb1.js";import"./_plugin-vue_export-helper-c27b6911.js";const V=a("div",{class:"mb-4 text-sm text-gray-600"}," Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one. ",-1),B={key:0,class:"mb-4 font-medium text-sm text-green-600"},N=["onSubmit"],P={class:"flex items-center justify-end mt-4"},J={__name:"ForgotPassword",props:{status:{type:String}},setup(o){const e=u({email:""}),m=()=>{e.post(route("password.email"))};return(S,r)=>(l(),d(b,null,{default:i(()=>[t(s(c),{title:"Forgot Password"}),V,o.status?(l(),p("div",B,f(o.status),1)):_("",!0),a("form",{onSubmit:y(m,["prevent"])},[a("div",null,[t(x,{for:"email",value:"Email"}),t(v,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:s(e).email,"onUpdate:modelValue":r[0]||(r[0]=n=>s(e).email=n),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),t(k,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),a("div",P,[t(h,{class:g({"opacity-25":s(e).processing}),disabled:s(e).processing},{default:i(()=>[w(" Email Password Reset Link ")]),_:1},8,["class","disabled"])])],40,N)]),_:1}))}};export{J as default};
