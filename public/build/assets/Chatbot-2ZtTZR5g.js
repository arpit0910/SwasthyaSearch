import{r as l,X as L,j as e,x as g,_ as z}from"./app-DRXwQhJW.js";/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const E=(...r)=>r.filter((t,a,n)=>!!t&&t.trim()!==""&&n.indexOf(t)===a).join(" ").trim();/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const H=r=>r.replace(/([a-z0-9])([A-Z])/g,"$1-$2").toLowerCase();/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const O=r=>r.replace(/^([A-Z])|[\s-_]+(\w)/g,(t,a,n)=>n?n.toUpperCase():a.toLowerCase());/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const A=r=>{const t=O(r);return t.charAt(0).toUpperCase()+t.slice(1)};/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */var _={xmlns:"http://www.w3.org/2000/svg",width:24,height:24,viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:2,strokeLinecap:"round",strokeLinejoin:"round"};/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const D=r=>{for(const t in r)if(t.startsWith("aria-")||t==="role"||t==="title")return!0;return!1},T=l.createContext({}),P=()=>l.useContext(T),W=l.forwardRef(({color:r,size:t,strokeWidth:a,absoluteStrokeWidth:n,className:o="",children:c,iconNode:x,...p},b)=>{const{size:u=24,strokeWidth:w=2,absoluteStrokeWidth:y=!1,color:j="currentColor",className:v=""}=P()??{},k=n??y?Number(a??w)*24/Number(t??u):a??w;return l.createElement("svg",{ref:b,..._,width:t??u??_.width,height:t??u??_.height,stroke:r??j,strokeWidth:k,className:E("lucide",v,o),...!c&&!D(p)&&{"aria-hidden":"true"},...p},[...x.map(([N,s])=>l.createElement(N,s)),...Array.isArray(c)?c:[c]])});/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const d=(r,t)=>{const a=l.forwardRef(({className:n,...o},c)=>l.createElement(W,{ref:c,iconNode:t,className:E(`lucide-${H(A(r))}`,`lucide-${r}`,n),...o}));return a.displayName=A(r),a};/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const q=[["path",{d:"M12 8V4H8",key:"hb8ula"}],["rect",{width:"16",height:"12",x:"4",y:"8",rx:"2",key:"enze0r"}],["path",{d:"M2 14h2",key:"vft8re"}],["path",{d:"M20 14h2",key:"4cs60a"}],["path",{d:"M15 13v2",key:"1xurst"}],["path",{d:"M9 13v2",key:"rq6x2g"}]],C=d("bot",q);/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const R=[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["path",{d:"m9 12 2 2 4-4",key:"dzmm74"}]],U=d("circle-check",R);/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const V=[["circle",{cx:"12",cy:"12",r:"10",key:"1mglay"}],["path",{d:"M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20",key:"13o1zl"}],["path",{d:"M2 12h20",key:"9i4pu4"}]],I=d("globe",V);/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const B=[["path",{d:"M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5",key:"mvr1a0"}],["path",{d:"M3.22 13H9.5l.5-1 2 4.5 2-7 1.5 3.5h5.27",key:"auskq0"}]],X=d("heart-pulse",B);/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const G=[["path",{d:"M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0",key:"1r0f0z"}],["circle",{cx:"12",cy:"10",r:"3",key:"ilqhr7"}]],K=d("map-pin",G);/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Z=[["path",{d:"M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z",key:"18887p"}]],F=d("message-square",Z);/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const J=[["path",{d:"M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z",key:"1ffxy3"}],["path",{d:"m21.854 2.147-10.94 10.939",key:"12cjpa"}]],Y=d("send",J);/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const Q=[["path",{d:"M11 2v2",key:"1539x4"}],["path",{d:"M5 2v2",key:"1yf1q8"}],["path",{d:"M5 3H4a2 2 0 0 0-2 2v4a6 6 0 0 0 12 0V5a2 2 0 0 0-2-2h-1",key:"rb5t3r"}],["path",{d:"M8 15a6 6 0 0 0 12 0v-3",key:"x18d4x"}],["circle",{cx:"20",cy:"10",r:"2",key:"ts1r5v"}]],ee=d("stethoscope",Q);/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const te=[["path",{d:"M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2",key:"975kel"}],["circle",{cx:"12",cy:"7",r:"4",key:"17ys0d"}]],se=d("user",te);/**
 * @license lucide-react v1.16.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const ae=[["path",{d:"M18 6 6 18",key:"1bl5f8"}],["path",{d:"m6 6 12 12",key:"d8bk6v"}]],re=d("x",ae);function oe(){const{url:r,props:t}=L(),a=t.locale||"en",n=c=>{z(async()=>{const{router:x}=await import("./app-DRXwQhJW.js").then(p=>p.i);return{router:x}},[]).then(({router:x})=>{x.post("/switch-locale",{locale:c})})},o=c=>`px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 ${r.startsWith(c)?"bg-teal-50 text-teal-700 border border-teal-100/80 shadow-2xs":"text-slate-600 hover:text-slate-900 hover:bg-slate-50"}`;return e.jsx("nav",{className:"sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/80 shadow-sm transition-all duration-300",children:e.jsx("div",{className:"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8",children:e.jsxs("div",{className:"flex justify-between h-16 items-center gap-4",children:[e.jsxs("div",{className:"flex items-center min-w-0",children:[e.jsxs(g,{href:"/",className:"flex items-center space-x-3 group mr-6 shrink-0",children:[e.jsx("div",{className:"p-2.5 bg-gradient-to-tr from-teal-500 to-indigo-600 rounded-2xl shadow-md group-hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-0.5",children:e.jsx(X,{className:"w-6 h-6 text-white animate-pulse"})}),e.jsxs("span",{className:"text-2xl font-bold bg-gradient-to-r from-slate-800 to-indigo-900 bg-clip-text text-transparent tracking-tight",children:["Swasthya",e.jsx("span",{className:"text-teal-600",children:"Search"})]})]}),e.jsxs("div",{className:"hidden md:flex items-center space-x-1 lg:space-x-2",children:[e.jsx(g,{href:"/doctors",className:o("/doctors"),children:a==="hi"?"डॉक्टर खोजें":"Doctors"}),e.jsx(g,{href:"/hospitals",className:o("/hospitals"),children:a==="hi"?"अस्पताल व क्लीनिक":"Hospitals"}),e.jsx(g,{href:"/articles",className:o("/articles"),children:a==="hi"?"स्वास्थ्य लेख":"Articles"}),e.jsx(g,{href:"/about",className:o("/about"),children:a==="hi"?"हमारे बारे में":"About Us"}),e.jsx(g,{href:"/contact",className:o("/contact"),children:a==="hi"?"संपर्क करें":"Contact Us"})]})]}),e.jsx("div",{className:"flex items-center shrink-0",children:e.jsxs("div",{className:"flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200/60 shadow-inner",children:[e.jsxs("button",{onClick:()=>n("en"),className:`flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 ${a==="en"?"bg-white text-indigo-900 shadow-sm font-semibold":"text-slate-600 hover:text-slate-900"}`,children:[e.jsx(I,{className:"w-4 h-4 text-teal-600"}),e.jsx("span",{children:"English"})]}),e.jsxs("button",{onClick:()=>n("hi"),className:`flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-200 ${a==="hi"?"bg-white text-indigo-900 shadow-sm font-semibold":"text-slate-600 hover:text-slate-900"}`,children:[e.jsx(I,{className:"w-4 h-4 text-teal-600"}),e.jsx("span",{children:"हिंदी"})]})]})})]})})})}function ie(){const{props:r}=L(),t=r.locale||"en",[a,n]=l.useState(!1),[o,c]=l.useState(""),[x,p]=l.useState(null),[b,u]=l.useState(!1),[w,y]=l.useState([{sender:"bot",text:t==="hi"?'नमस्ते! मैं स्वास्थ्या एआई हूँ। आप अपनी बीमारी के लक्षण (जैसे "पेट दर्द" या "बुखार") या डॉक्टर का नाम बता सकते हैं।':`Hello! I am Swasthya AI. You can tell me your symptoms (e.g. "stomach ache" or "fever") or a doctor's name, and I will find the right specialist for you.`,timestamp:new Date().toISOString()}]),j=l.useRef(null),v=()=>{var s;(s=j.current)==null||s.scrollIntoView({behavior:"smooth"})};l.useEffect(()=>{v()},[w,a]);const k=async s=>{var i;if(s.preventDefault(),!o.trim()||b)return;const m=o;c(""),y(f=>[...f,{sender:"user",text:m,timestamp:new Date().toISOString()}]),u(!0);try{const h=await(await fetch("/api/chatbot",{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":((i=document.querySelector('meta[name="csrf-token"]'))==null?void 0:i.content)||""},body:JSON.stringify({session_token:x,message:m})})).json();h.session_token&&p(h.session_token),h.history&&y(h.history)}catch(f){console.error("Chatbot error:",f),y(h=>[...h,{sender:"bot",text:t==="hi"?"क्षमा करें, कोई तकनीकी समस्या आ गई है।":"Sorry, a technical error occurred.",timestamp:new Date().toISOString()}])}finally{u(!1)}},N=(s,m="")=>s?typeof s=="string"?s:s[t]||s.en||m:m;return e.jsxs("div",{className:"fixed bottom-6 right-6 z-50",children:[!a&&e.jsxs("button",{onClick:()=>n(!0),className:"flex items-center gap-3 bg-gradient-to-tr from-teal-500 to-indigo-600 text-white px-6 py-3.5 rounded-full shadow-2xl hover:shadow-indigo-500/50 hover:scale-105 transition-all duration-300 transform group",children:[e.jsx("div",{className:"w-6 h-6 flex items-center justify-center shrink-0 animate-bounce group-hover:animate-none",children:e.jsx(F,{className:"w-6 h-6 text-white"})}),e.jsx("span",{className:"font-bold text-base tracking-wide whitespace-nowrap leading-none pt-0.5",children:t==="hi"?"स्वास्थ्या एआई से पूछें":"Ask Swasthya AI"})]}),a&&e.jsxs("div",{className:"w-[90vw] sm:w-[420px] h-[550px] bg-white rounded-3xl shadow-2xl border border-slate-200/80 flex flex-col overflow-hidden animate-in fade-in slide-in-from-bottom-5 duration-300",children:[e.jsxs("div",{className:"bg-gradient-to-r from-slate-900 to-indigo-900 text-white p-4 flex justify-between items-center shadow-md",children:[e.jsxs("div",{className:"flex items-center space-x-3",children:[e.jsx("div",{className:"p-2 bg-teal-500/20 rounded-2xl border border-teal-500/30",children:e.jsx(C,{className:"w-6 h-6 text-teal-400"})}),e.jsxs("div",{children:[e.jsx("h3",{className:"font-bold text-lg leading-tight",children:"Swasthya AI Assistant"}),e.jsxs("p",{className:"text-xs text-teal-300 flex items-center space-x-1 mt-0.5",children:[e.jsx("span",{className:"w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"}),e.jsx("span",{children:t==="hi"?"लक्षण से डॉक्टर खोजें":"Symptom-to-Doctor AI"})]})]})]}),e.jsx("button",{onClick:()=>n(!1),className:"p-2 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all duration-200",children:e.jsx(re,{className:"w-5 h-5"})})]}),e.jsxs("div",{className:"flex-1 p-4 overflow-y-auto space-y-4 bg-slate-50/50",children:[w.map((s,m)=>e.jsx("div",{className:`flex ${s.sender==="user"?"justify-end":"justify-start"} animate-in fade-in duration-200`,children:e.jsxs("div",{className:`flex space-x-2 max-w-[85%] ${s.sender==="user"?"flex-row-reverse space-x-reverse":"flex-row"}`,children:[e.jsx("div",{className:`w-7 h-7 rounded-full flex items-center justify-center shrink-0 shadow-sm ${s.sender==="user"?"bg-indigo-600 text-white":"bg-teal-500 text-white"}`,children:s.sender==="user"?e.jsx(se,{className:"w-4 h-4"}):e.jsx(C,{className:"w-4 h-4"})}),e.jsxs("div",{className:"space-y-2",children:[e.jsx("div",{className:`p-3.5 rounded-2xl text-sm shadow-sm leading-relaxed ${s.sender==="user"?"bg-indigo-600 text-white rounded-tr-none":"bg-white text-slate-800 border border-slate-200/60 rounded-tl-none"}`,children:s.text}),s.doctors&&s.doctors.length>0&&e.jsx("div",{className:"space-y-2 pt-1",children:s.doctors.map(i=>{var $,M;const f=`Dr. ${i.first_name} ${i.last_name}`,h=i.department?N(i.department.name):"",S=((M=($=i.hospitals)==null?void 0:$[0])==null?void 0:M.emergency_phone)||"";return e.jsxs("div",{className:"bg-white p-3 rounded-2xl border border-indigo-100 shadow-sm hover:shadow transition-all duration-200",children:[e.jsxs("div",{className:"flex justify-between items-start",children:[e.jsxs("h4",{className:"font-bold text-sm text-indigo-950 flex items-center space-x-1",children:[e.jsx("span",{children:f}),i.is_verified&&e.jsx(U,{className:"w-3.5 h-3.5 text-teal-600 inline"})]}),e.jsxs("span",{className:"text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-lg font-medium",children:[i.experience_years," ",t==="hi"?"वर्ष अनुभव":"yrs exp"]})]}),e.jsxs("div",{className:"mt-2 space-y-1 text-xs text-slate-600",children:[e.jsxs("div",{className:"flex items-center space-x-1",children:[e.jsx(ee,{className:"w-3.5 h-3.5 text-teal-600 shrink-0"}),e.jsx("span",{className:"font-medium text-slate-700",children:h})]}),i.hospitals&&i.hospitals.length>0&&e.jsxs("div",{className:"flex items-start space-x-1 pt-0.5",children:[e.jsx(K,{className:"w-3.5 h-3.5 text-indigo-500 shrink-0 mt-0.5"}),e.jsx("span",{children:N(i.hospitals[0].name)})]})]}),S&&e.jsx("div",{className:"mt-2 pt-2 border-t border-slate-100 flex justify-end",children:e.jsx("a",{href:`tel:${S}`,className:"text-xs bg-teal-50 hover:bg-teal-600 text-white font-medium px-3 py-1 rounded-xl shadow-sm transition-all duration-200",children:t==="hi"?"कॉल करें":"Call Doctor"})})]},i.id)})})]})]})},m)),b&&e.jsxs("div",{className:"flex space-x-2 items-center text-slate-400 text-sm italic",children:[e.jsx(C,{className:"w-5 h-5 text-teal-500 animate-spin"}),e.jsx("span",{children:t==="hi"?"स्वास्थ्या एआई सोच रहा है...":"Swasthya AI is thinking..."})]}),e.jsx("div",{ref:j})]}),e.jsxs("form",{onSubmit:k,className:"p-3 bg-white border-t border-slate-200/80 flex items-center space-x-2 shadow-lg",children:[e.jsx("input",{type:"text",value:o,onChange:s=>c(s.target.value),placeholder:t==="hi"?"लक्षण या डॉक्टर का नाम लिखें...":"Type a symptom or doctor name...",className:"flex-1 bg-slate-100 border border-slate-200/80 rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500/80 transition-all duration-200"}),e.jsx("button",{type:"submit",disabled:b||!o.trim(),className:"bg-indigo-600 hover:bg-indigo-500 disabled:bg-slate-300 text-white p-2.5 rounded-2xl shadow-md transition-all duration-200 transform active:scale-95",children:e.jsx(Y,{className:"w-5 h-5"})})]})]})]})}export{ie as C,I as G,X as H,K as M,oe as N,Y as S,se as U,U as a,F as b,ee as c,d};
