import{r as g,h as p,o as a,c as r,b as e,z as v,t as s,k as d,a as i,w as _,T as y,d as b,l as w,F as x,f as k,e as C,u as c}from"./app-32a5c4fa.js";import{_ as u}from"./InputError-499f6de0.js";const T={class:"absolute right-3 top-3"},D=e("path",{d:"M0 0h24v24H0z",stroke:"none"},null,-1),N=e("polyline",{points:"6 9 12 15 18 9"},null,-1),j=[D,N],z={class:"flex space-x-28"},B={class:"font-bold"},E=e("h1",{class:"font-bold"},"TOTAL",-1),O={class:"pt-2 pr-4"},R={class:"z-50 rounded-md shadow-lg p-7 bg-gray-200"},S={class:"flex justify-between space-y-12"},V={class:"my-auto"},$={class:"font-semibold text-xl text-black leading-tight"},A={class:"text-start"},q={key:0,class:"text-end mt-10 mb-5"},M={__name:"Order",props:{order:{type:Object,required:!0}},setup(t){const h=t,o=g(!1),n=p({orderId:h.order.id}),f=()=>{n.post(route("payment.retry"),{preserveScroll:!0})};return(F,m)=>(a(),r("div",null,[e("div",{class:v([{"bg-gray-100":o.value,"bg-white":!o.value},"w-full shadow-md p-7 flex justify-between relative rounded-2xl"]),onClick:m[0]||(m[0]=l=>o.value=!o.value)},[e("div",T,[(a(),r("svg",{class:v([{"rotate-180":o.value},"transition h-6 w-6 text-gray-500"]),fill:"none",height:"24",stroke:"currentColor","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",viewBox:"0 0 24 24",width:"24"},j,2))]),e("div",z,[e("div",null,[e("h1",B,"ORDER "+s(t.order.status.toUpperCase()),1),d(" "+s(new Date(t.order.created_at).toDateString()),1)]),e("div",null,[E,d(" $"+s(t.order.total),1)])]),e("div",O,s(t.order.code),1)],2),i(y,{"enter-active-class":"transition ease-out duration-200","enter-from-class":"transform opacity-0 scale-95","enter-to-class":"transform opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"transform opacity-100 scale-100","leave-to-class":"transform opacity-0 scale-95"},{default:_(()=>[b(e("div",R,[e("div",null,[(a(!0),r(x,null,k(t.order.order_detail,l=>(a(),r("div",S,[e("div",V,[e("h1",$,s(l.product_name),1),d(" Quantity: "+s(l.quantity),1)]),e("div",A,[e("strong",null,"$ "+s(l.amount),1)])]))),256))]),t.order.active?(a(),r("div",q,[e("button",{class:"bg-yellow-400 rounded-lg px-5 py-1 font-semibold hover:bg-amber-400 active:bg-yellow-500 focus:outline-none transition ease-in-out duration-150 border border-transparent",onClick:f}," RETRY PAYMENT ")])):C("",!0),i(u,{message:c(n).errors.orderId},null,8,["message"]),i(u,{message:c(n).errors.payment},null,8,["message"]),i(u,{message:c(n).errors.app},null,8,["message"])],512),[[w,o.value]])]),_:1})]))}};export{M as default};
