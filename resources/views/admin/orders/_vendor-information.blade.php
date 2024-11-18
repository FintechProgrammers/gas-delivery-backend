 <div class="card">
     <div class="card-header">
         <div class="row align-items-center">
             <div class="col">
                 <h4 class="card-title">Vendor Information</h4>
             </div>
         </div>
         <!--end row-->
     </div>
     <!--end card-header-->
     <div class="card-body pt-0">
         <div>
             <div class="d-flex justify-content-between mb-2">
                 <p class="text-body fw-semibold"><i
                         class="iconoir-people-tag text-secondary fs-20 align-middle me-1"></i>Full Name :</p>
                 <p class="text-body-emphasis fw-semibold">{{ optional($order->business)->business_name }}</p>
             </div>
             <div class="d-flex justify-content-between mb-2">
                 <p class="text-body fw-semibold"><i
                         class="iconoir-mail text-secondary fs-20 align-middle me-1"></i>Email :</p>
                 <p class="text-body-emphasis fw-semibold">{{ optional($order->business)->email }}</p>
             </div>
             <div class="d-flex justify-content-between">
                 <p class="text-body fw-semibold"><i
                         class="iconoir-map-pin text-secondary fs-20 align-middle me-1"></i>Address :</p>
                 <p class="text-body-emphasis fw-semibold">
                     {{ optional($order->business->profile)->address }}
                 </p>
             </div>
         </div>
     </div>
     <!--card-body-->
 </div>
 <!--end card-->
