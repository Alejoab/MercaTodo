import a from"./UpdateInformation-bd700eda.js";import l from"./UpdatePasswordAdmin-a4416b9c.js";import n from"./ActionsAdmin-fc9dedc7.js";import{_ as c}from"./UserLayout-97946590.js";import{o,c as r,a as e,w as m,u as i,F as p,X as u,b as t,J as d,e as _}from"./app-32a5c4fa.js";import"./PrimaryButton-e8cde6a0.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./InputError-499f6de0.js";import"./TextInput-9f124e2f.js";import"./InputLabel-609d8761.js";import"./DangerButton-123fe33b.js";import"./SecondaryButton-6421a108.js";import"./Modal-4e9485ed.js";import"./SuccessButton-d7ad6163.js";import"./ResponsiveNavLink-34f70eb1.js";import"./Dropdown-8a285acd.js";import"./CounterStore-5c42db56.js";const f=t("title",null,"Edit User",-1),w={class:"max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"},x={class:"p-4 sm:p-8 bg-white shadow sm:rounded-lg"},h={class:"p-4 sm:p-8 bg-white shadow sm:rounded-lg"},g={key:0,class:"p-4 sm:p-8 bg-white shadow sm:rounded-lg"},A={__name:"EditUser",props:{user:{type:Object},roles:{type:Object},permissions_view:{type:Object}},setup(s){return(b,v)=>(o(),r(p,null,[e(i(u),null,{default:m(()=>[f]),_:1}),e(c,null,{default:m(()=>[t("div",w,[t("div",x,[e(a,{permissions:s.permissions_view,roles:s.roles,user:s.user,class:"max-w-xl sm:mt-0 mt-16"},null,8,["permissions","roles","user"])]),t("div",h,[e(l,{user:s.user,class:"max-w-xl"},null,8,["user"])]),i(d)().props.permissions.includes("Delete")?(o(),r("div",g,[e(n,{user:s.user,class:"max-w-xl"},null,8,["user"])])):_("",!0)])]),_:1})],64))}};export{A as default};