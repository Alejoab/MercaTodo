import{r as y,o as l,c as i,b as e,d as $,v as A,u as n,e as p,F as k,f as B,a as r,w as a,O as D,J as d,t as v,g as j,n as S,k as _,m as b}from"./app-32a5c4fa.js";import{i as V}from"./laravel-vue-pagination.es-c3c127de.js";import{_ as w}from"./Modal-4e9485ed.js";import{_ as g}from"./SecondaryButton-6421a108.js";import{D as H}from"./DangerButton-123fe33b.js";import{S as I}from"./SuccessButton-d7ad6163.js";import"./_plugin-vue_export-helper-c27b6911.js";const N={class:"mb-8 xl:ml-10"},O={class:"flex"},z=e("label",{class:"sr-only",for:"simple-search"},"Search",-1),T={class:"relative"},q=e("svg",{class:"h-8 w-8 text-black",fill:"none",height:"24",stroke:"currentColor","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",viewBox:"0 0 24 24",width:"24"},[e("path",{d:"M0 0h24v24H0z",stroke:"none"}),e("circle",{cx:"10",cy:"10",r:"7"}),e("line",{x1:"21",x2:"15",y1:"21",y2:"15"})],-1),E=[q],F=e("svg",{class:"h-8 w-8 text-black",fill:"none",height:"24",stroke:"currentColor","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",viewBox:"0 0 24 24",width:"24"},[e("path",{d:"M0 0h24v24H0z",stroke:"none"}),e("path",{d:"M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -5v5h5"}),e("path",{d:"M4 13a8.1 8.1 0 0 0 15.5 2m.5 5v-5h-5"})],-1),L=[F],P={class:"overflow-auto"},R={class:"w-full text-xs md:text-sm text-left"},J={class:"text-xs uppercase bg-gray-50"},G=e("th",{class:"px-6 py-3",scope:"col"}," User ID",-1),K=e("th",{class:"px-6 py-3",scope:"col"}," Email",-1),Q=e("th",{class:"px-6 py-3",scope:"col"}," Role",-1),W=e("th",{class:"px-6 py-3",scope:"col"}," Status",-1),X={key:0,class:"px-6 py-3 text-center",scope:"col"},Y={class:"bg-white border-b"},Z={class:"px-6 py-1.5 font-medium whitespace-nowrap",scope:"row"},ee={class:"px-6 py-1.5"},te={class:"px-6 py-1.5"},se={class:"px-6 py-1.5"},oe={key:0,class:"px-6 py-1.5 text-center"},ne={class:"inline-flex space-x-1"},le=e("svg",{class:"h-6 w-6 text-black",fill:"none",stroke:"currentColor","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",viewBox:"0 0 24 24"},[e("path",{d:"M0 0h24v24H0z",stroke:"none"}),e("path",{d:"M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3"}),e("path",{d:"M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3"}),e("line",{x1:"16",x2:"19",y1:"5",y2:"8"})],-1),ie=["onClick"],re=b('<svg class="h-6 w-6 text-red-500" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" stroke="none"></path><line x1="4" x2="20" y1="7" y2="7"></line><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>',1),ae=[re],de=["onClick"],ce=b('<svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" x2="20" y1="8" y2="14"></line><line x1="23" x2="17" y1="11" y2="11"></line></svg>',1),ue=[ce],pe={class:"mt-7 flex justify-center"},he={class:"p-6"},ve=e("h2",{class:"text-lg font-medium text-gray-900"}," Are you sure you want to delete the account? ",-1),_e=e("p",{class:"mt-1 text-sm text-gray-600"}," Once you delete this account, it will only be disabled for the user, but the information will still be stored. ",-1),xe={class:"mt-6 flex justify-end"},me={class:"p-6"},ye=e("h2",{class:"text-lg font-medium text-gray-900"}," Are you sure you want to restore the account? ",-1),fe=e("p",{class:"mt-1 text-sm text-gray-600"}," Once you restore the account the user will have access to it again. ",-1),ke={class:"mt-6 flex justify-end"},Ae={__name:"UsersTable",props:{users:{type:Object,required:!0}},setup(x){const f=x,h=y(""),c=y(""),u=y(""),m=async(o=1,t="")=>{await D.visit(route("admin.users",{page:o,search:t}),{preserveScroll:!0,replace:!0})},C=o=>{c.value="",axios.delete(route("admin.user.destroy",o)).then(()=>{const t=f.users.data.find(s=>s.id===o);t&&(t.deleted=t.deleted==="Active"?"Inactive":"Active")})},U=async o=>{u.value="",axios.put(route("admin.user.restore",o)).then(()=>{const t=f.users.data.find(s=>s.id===o);t&&(t.deleted=t.deleted==="Active"?"Inactive":"Active")})};return(o,t)=>(l(),i(k,null,[e("div",null,[e("div",N,[e("div",O,[z,e("div",T,[$(e("input",{id:"simple-search","onUpdate:modelValue":t[0]||(t[0]=s=>h.value=s),class:"bg-gray-50 border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-60 pl-4 p-2.5",placeholder:"Search",required:"",type:"text"},null,512),[[A,h.value]])]),e("button",{class:"p-1 ml-2",onClick:t[1]||(t[1]=s=>m(1,h.value))},E),e("button",{class:"p-1 ml-2",onClick:t[2]||(t[2]=s=>{m(1),h.value=""})},L)])]),e("div",null,[e("div",P,[e("table",R,[e("thead",J,[G,K,Q,W,n(d)().props.permissions.includes("Update")||n(d)().props.permissions.includes("Delete")?(l(),i("th",X," Actions ")):p("",!0)]),e("tbody",null,[(l(!0),i(k,null,B(x.users.data,s=>(l(),i("tr",Y,[e("th",Z,v(s.id),1),e("td",ee,v(s.email),1),e("td",te,v(s.role),1),e("td",se,v(s.deleted),1),n(d)().props.permissions.includes("Update")||n(d)().props.permissions.includes("Delete")?(l(),i("td",oe,[e("div",ne,[n(d)().props.permissions.includes("Update")?(l(),j(n(S),{key:0,href:o.route("admin.user.show",s.id)},{default:a(()=>[le]),_:2},1032,["href"])):p("",!0),s.deleted==="Active"&&n(d)().props.permissions.includes("Delete")?(l(),i("button",{key:1,onClick:M=>c.value=s.id},ae,8,ie)):p("",!0),s.deleted!=="Active"&&n(d)().props.permissions.includes("Delete")?(l(),i("button",{key:2,onClick:M=>u.value=s.id},ue,8,de)):p("",!0)])])):p("",!0)]))),256))])])]),e("div",pe,[r(n(V),{data:x.users,limit:1,onPaginationChangePage:m,keepLength:!0},null,8,["data"])])])]),r(w,{show:!!c.value,onClose:t[5]||(t[5]=s=>c.value="")},{default:a(()=>[e("div",he,[ve,_e,e("div",xe,[r(g,{onClick:t[3]||(t[3]=s=>c.value="")},{default:a(()=>[_(" Cancel")]),_:1}),r(H,{class:"ml-3",onClick:t[4]||(t[4]=s=>C(c.value))},{default:a(()=>[_(" Delete Account ")]),_:1})])])]),_:1},8,["show"]),r(w,{show:!!u.value,onClose:t[8]||(t[8]=s=>u.value="")},{default:a(()=>[e("div",me,[ye,fe,e("div",ke,[r(g,{onClick:t[6]||(t[6]=s=>u.value="")},{default:a(()=>[_(" Cancel")]),_:1}),r(I,{class:"ml-3",onClick:t[7]||(t[7]=s=>U(u.value))},{default:a(()=>[_(" Restore Account ")]),_:1})])])]),_:1},8,["show"])],64))}};export{Ae as default};