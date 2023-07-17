import{A as $,_ as c}from"./ResponsiveNavLink-34f70eb1.js";import{_ as C}from"./Dropdown-8a285acd.js";import{o as n,g as y,w as o,B as b,u as d,n as p,r as x,J as g,c as i,d as w,v as M,b as e,l as B,t as _,a as r,e as f,k as a,z as v}from"./app-32a5c4fa.js";import{u as z}from"./CounterStore-5c42db56.js";const l={__name:"DropdownLink",props:{href:{type:String,required:!0}},setup(u){return(s,t)=>(n(),y(d(p),{href:u.href,class:"block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"},{default:o(()=>[b(s.$slots,"default")]),_:3},8,["href"]))}},L={class:"relative flex items-center w-full"},j=e("svg",{class:"h-8 w-8 text-gray-600",fill:"none",height:"24",stroke:"currentColor","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",viewBox:"0 0 24 24",width:"24"},[e("path",{d:"M0 0h24v24H0z",stroke:"none"}),e("circle",{cx:"10",cy:"10",r:"7"}),e("line",{x1:"21",x2:"15",y1:"21",y2:"15"})],-1),S=[j],A={__name:"SearchBox",setup(u){const s=x(g().props.ziggy.query.search?g().props.ziggy.query.search:""),t=()=>{window.location.href=route("home",{search:s.value})};return(h,m)=>(n(),i("div",L,[w(e("input",{id:"default-search","onUpdate:modelValue":m[0]||(m[0]=k=>s.value=k),class:"block w-full p-3 pl-5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500",placeholder:"Search Products ...",type:"text"},null,512),[[M,s.value]]),e("button",{class:"-ml-10",type:"submit",onClick:t},S)]))}},N=["href"],H=e("svg",{class:"h-9 w-9 text-gray-600",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[e("path",{d:"M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2"})],-1),V={__name:"ShoppingCart",setup(u){const s=z();return s.syncCartCount(),(t,h)=>(n(),i("a",{href:t.route("cart"),class:"relative"},[w(e("span",{class:"absolute w-5 h-5 rounded-full flex justify-center items-center bg-amber-300 -top-0.5 -right-2 text-gray-600 font-bold"},_(d(s).count),513),[[B,d(s).count>0]]),H],8,N))}},D={class:"bg-white border-b border-gray-100"},O={class:"px-4 sm:px-6 lg:px-8"},R={class:"flex justify-between h-16"},U={class:"flex w-full"},q={class:"shrink-0 flex items-center"},P={key:0,class:"w-[60%] flex m-auto"},T={class:"hidden lg:flex lg:items-center lg:ml-6"},E={class:"mr-6"},I={key:0,class:"ml-3 relative"},J={class:"inline-flex rounded-md"},F={class:"inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150",type:"button"},G=e("svg",{class:"-mr-0.5 h-4 w-4",fill:"currentColor",viewBox:"0 0 20 20",xmlns:"http://www.w3.org/2000/svg"},[e("path",{"clip-rule":"evenodd",d:"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z","fill-rule":"evenodd"})],-1),K={key:0},Q=e("div",{class:"border border-indigo-100 mr-3"},null,-1),W=e("div",{class:"border border-indigo-100 mr-3"},null,-1),X=e("div",{class:"border border-indigo-100 mr-3"},null,-1),Y=e("div",{class:"border border-indigo-100 mr-3"},null,-1),Z={key:1,class:"ml-3 relative"},ee={class:"whitespace-nowrap"},te={key:0,class:"flex items-center"},se={class:"ml-3 -mr-2 flex items-center lg:hidden"},re={class:"h-6 w-6",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},oe={key:0,class:"pt-4 pb-1 border-t border-gray-200"},ae={class:"px-4"},ne={class:"font-medium text-base text-gray-800"},ie={class:"font-medium text-sm text-gray-500"},le={class:"mt-3 space-y-1"},de={key:1,class:"pt-4 pb-1 border-t border-gray-200"},ue={class:"mt-3 space-y-1"},ce={__name:"UserNavBar",setup(u){const s=x(!1);return(t,h)=>(n(),i("nav",D,[e("div",O,[e("div",R,[e("div",U,[e("div",q,[r(d(p),{href:t.route("home")},{default:o(()=>[r($,{class:"block h-9 w-auto fill-current text-gray-800"})]),_:1},8,["href"])]),d(g)().props.ziggy.location.includes("admin")?f("",!0):(n(),i("div",P,[r(A)]))]),e("div",T,[e("div",E,[t.$page.props.auth.user?(n(),i("div",I,[r(C,{align:"right",width:"48"},{trigger:o(()=>[e("span",J,[e("button",F,[a(_(t.$page.props.auth.user.email)+" ",1),G])])]),content:o(()=>[t.$page.props.isAdmin?(n(),i("div",K,[r(l,{href:t.route("admin")},{default:o(()=>[a("Administrator")]),_:1},8,["href"]),r(l,{href:t.route("admin.users"),class:"pl-8 flex"},{default:o(()=>[Q,a("Users")]),_:1},8,["href"]),r(l,{href:t.route("admin.customers"),class:"pl-8 flex"},{default:o(()=>[W,a("Customers")]),_:1},8,["href"]),r(l,{href:t.route("admin.products"),class:"pl-8 flex"},{default:o(()=>[X,a("Products")]),_:1},8,["href"]),r(l,{href:t.route("admin.reports"),class:"pl-8 flex"},{default:o(()=>[Y,a("Reports")]),_:1},8,["href"])])):f("",!0),r(l,{href:t.route("profile.edit")},{default:o(()=>[a(" Profile")]),_:1},8,["href"]),r(l,{href:t.route("order.history")},{default:o(()=>[a(" Order History")]),_:1},8,["href"]),r(l,{href:t.route("logout"),as:"button",method:"post"},{default:o(()=>[a("Log Out")]),_:1},8,["href"])]),_:1})])):(n(),i("div",Z,[e("div",ee,[r(d(p),{href:t.route("login"),class:"font-semibold text-gray-400 hover:text-gray-900"},{default:o(()=>[a("Log in ")]),_:1},8,["href"]),r(d(p),{href:t.route("register"),class:"ml-4 font-semibold text-gray-400 hover:text-gray-900"},{default:o(()=>[a("Register ")]),_:1},8,["href"])])]))])]),d(g)().props.ziggy.location.includes("admin")?f("",!0):(n(),i("div",te,[r(V)])),e("div",se,[e("button",{class:"inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out",onClick:h[0]||(h[0]=m=>s.value=!s.value)},[(n(),i("svg",re,[e("path",{class:v({hidden:s.value,"inline-flex":!s.value}),d:"M4 6h16M4 12h16M4 18h16","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2"},null,2),e("path",{class:v({hidden:!s.value,"inline-flex":s.value}),d:"M6 18L18 6M6 6l12 12","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2"},null,2)]))])])])]),e("div",{class:v([{block:s.value,hidden:!s.value},"lg:hidden"])},[t.$page.props.auth.user?(n(),i("div",oe,[e("div",ae,[e("div",ne,_(t.$page.props.auth.user.email),1),e("div",ie,_(t.$page.props.auth.user.email),1)]),e("div",le,[t.$page.props.isAdmin?(n(),y(c,{key:0,href:t.route("admin")},{default:o(()=>[a(" Administrator")]),_:1},8,["href"])):f("",!0),r(c,{href:t.route("order.history")},{default:o(()=>[a(" Order History")]),_:1},8,["href"]),r(c,{href:t.route("logout"),as:"button",method:"post"},{default:o(()=>[a("Log Out")]),_:1},8,["href"])])])):(n(),i("div",de,[e("div",ue,[r(c,{href:t.route("register")},{default:o(()=>[a(" Register")]),_:1},8,["href"]),r(c,{href:t.route("login")},{default:o(()=>[a(" Log In")]),_:1},8,["href"])])]))],2)]))}},he={class:"min-h-screen bg-gray-100"},fe={class:"py-12"},ve={__name:"UserLayout",setup(u){return(s,t)=>(n(),i("div",he,[r(ce),e("main",fe,[b(s.$slots,"default")])]))}};export{ve as _};
