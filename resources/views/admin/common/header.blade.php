 <!-- ============================================================== -->
 <!-- Topbar header - style you can find in pages.scss -->
 <!-- ============================================================== -->
 <div class="loader" style="display: none">
     <svg width="20" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8">
         <defs>
             <linearGradient x1="8.042%" y1="0%" x2="65.682%" y2="23.865%" id="a">
                 <stop stop-color="rgb(45, 55, 72)" stop-opacity="0" offset="0%"></stop>
                 <stop stop-color="rgb(45, 55, 72)" stop-opacity=".631" offset="63.146%"></stop>
                 <stop stop-color="rgb(45, 55, 72)" offset="100%"></stop>
             </linearGradient>
         </defs>
         <g fill="none" fill-rule="evenodd">
             <g transform="translate(1 1)">
                 <path d="M36 18c0-9.94-8.06-18-18-18" id="Oval-2" stroke="url(#a)" stroke-width="3">
                     <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s"
                         repeatCount="indefinite"></animateTransform>
                 </path>
                 <circle fill="rgb(45, 55, 72)" cx="36" cy="18" r="1">
                     <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.9s"
                         repeatCount="indefinite"></animateTransform>
                 </circle>
             </g>
         </g>
     </svg>
 </div>
 @include('admin.common.model')
