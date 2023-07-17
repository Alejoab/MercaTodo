import{r as _,J as r,o as a,c as i,b as e,d as g,l as b,a as n,w as l,T as L,F as c,f as y,u as $,O as w,t as m,i as j,s as P,k,g as S}from"./app-15dc6fea.js";import V from"./ProductCard-219b9607.js";import{_ as M}from"./Dropdown-735e5356.js";import{_ as q}from"./InputLabel-83d1a3cd.js";import{P as F}from"./PrimaryButton-29033063.js";import{_ as N}from"./SecondaryButton-db5f17b8.js";import{i as O}from"./laravel-vue-pagination.es-3139b5b6.js";import"./CounterStore-7d1db5ad.js";import"./_plugin-vue_export-helper-c27b6911.js";const A={class:"max-w-xs bg-white fixed inset-0 z-50 flex flex-col pt-16 px-6 justify-between"},H={class:"space-y-6"},T={class:"mt-4"},U=e("option",{selected:"",value:""},"All",-1),D=["value"],E={class:"space-y-2 pl-2 h-30 overflow-y-auto"},J=["onUpdate:modelValue","value"],Q={class:"ml-2.5",for:"brand.id"},G={class:"mb-10 flex justify-between px-3"},I={class:"m-auto max-w-7xl w-full"},K={class:"flex flex-col space-y-5 md:space-y-0 md:flex-row md:justify-between px-5 md:px-10 lg:px-20"},R={class:"flex items-center"},W=e("span",{class:"text-gray-500 font-bold text-xl"},"Filter",-1),X=e("svg",{class:"ml-1 h-6 w-6 text-gray-500",fill:"none",height:"24",stroke:"currentColor","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",viewBox:"0 0 24 24",width:"24"},[e("path",{d:"M0 0h24v24H0z",stroke:"none"}),e("path",{d:"M5.5 5h13a1 1 0 0 1 0.5 1.5L14 12L14 19L10 16L10 12L5 6.5a1 1 0 0 1 0.5 -1.5"})],-1),Y=[W,X],Z={class:"inline-flex transition ease-in-out duration-150 items-center"},ee={class:"text-gray-500 font-bold text-xl"},te=e("svg",{class:"ml-1 h-5 w-5 text-gray-500",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},[e("path",{d:"M19 9l-7 7-7-7","stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2"})],-1),se={class:"flex flex-col"},oe=["onClick"],re={class:"bg-white mt-5 pb-5 rounded-2xl"},ae={class:"justify-items-center grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-y-5"},ie={class:"mt-7 flex justify-center"},fe={__name:"ProductsList",props:{products:{type:Object,required:!0},categories:{type:Array,required:!0},brands:{type:Array,required:!0}},setup(d){const B=d,h={0:"Price: Low to High",1:"Price: High to Low",2:"Newest",3:"Oldest"},o=_({sortBy:r().props.ziggy.query.sortBy?r().props.ziggy.query.sortBy:2,page:r().props.ziggy.query.page?r().props.ziggy.query.page:1,search:r().props.ziggy.query.search?r().props.ziggy.query.search:"",category:r().props.ziggy.query.category?r().props.ziggy.query.category:"",brand:r().props.ziggy.query.brand?r().props.ziggy.query.brand:[]}),x=_(B.brands),u=_(!1),v=async(p=1)=>{o.value.page=p,await w.visit(route("home",o.value),{replace:!0})},C=async()=>{await w.visit(route("home"),{preserveScroll:!0,replace:!0})},z=async()=>{const p=await fetch(route("brands",o.value.category));x.value=await p.json()};return(p,s)=>(a(),i(c,null,[e("div",null,[g(e("div",{class:"fixed inset-0 z-40 bg-gray-600 opacity-60",onClick:s[0]||(s[0]=t=>u.value=!1)},null,512),[[b,u.value]]),n(L,{"enter-active-class":"transition ease-out duration-200","enter-from-class":"transform opacity-0 scale-95","enter-to-class":"transform opacity-100 scale-100","leave-active-class":"transition ease-in duration-75","leave-from-class":"transform opacity-100 scale-100","leave-to-class":"transform opacity-0 scale-95"},{default:l(()=>[g(e("div",A,[e("div",H,[e("div",T,[n(q,{for:"category",value:"Categories"}),g(e("select",{id:"category","onUpdate:modelValue":s[1]||(s[1]=t=>o.value.category=t),class:"mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full",required:"",onChange:s[2]||(s[2]=t=>{z(),o.value.brand=[]})},[U,(a(!0),i(c,null,y(d.categories,t=>(a(),i("option",{value:t.id},m(t.name),9,D))),256))],544),[[j,o.value.category]])]),e("div",E,[n(q,{value:"Brands"}),(a(!0),i(c,null,y(x.value,t=>(a(),i("div",null,[g(e("input",{id:"brand.id","onUpdate:modelValue":f=>o.value.brand=f,value:t.id,class:"cursor-pointer rounded text-indigo-600 focus:ring-indigo-700 focus:ring-2",type:"checkbox"},null,8,J),[[P,o.value.brand]]),e("label",Q,m(t.name),1)]))),256))])]),e("div",G,[n(N,{onClick:s[3]||(s[3]=t=>C())},{default:l(()=>[k("Clear")]),_:1}),n(F,{onClick:s[4]||(s[4]=t=>v())},{default:l(()=>[k("Search")]),_:1})])],512),[[b,u.value]])]),_:1})]),e("div",I,[e("div",K,[e("div",R,[e("button",{class:"inline-flex transition ease-in-out duration-150 items-center",onClick:s[5]||(s[5]=t=>u.value=!0)},Y)]),n(M,{align:"right",width:"48"},{trigger:l(()=>[e("button",Z,[e("span",ee,"Sort by: "+m(h[o.value.sortBy]),1),te])]),content:l(()=>[e("div",se,[(a(),i(c,null,y(h,(t,f)=>e("button",{class:"text-start p-2 pl-3",onClick:ne=>{o.value.sortBy=f,v()}},m(t),9,oe)),64))])]),_:1})]),e("div",re,[e("div",ae,[(a(!0),i(c,null,y(d.products.data,t=>(a(),S(V,{product:t,class:"text-sm"},null,8,["product"]))),256))]),e("div",ie,[n($(O),{data:d.products,limit:1,onPaginationChangePage:v,keepLength:!0},null,8,["data"])])])])],64))}};export{fe as default};
