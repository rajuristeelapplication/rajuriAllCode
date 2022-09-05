 <div id="delete-confirmation-modal" class="modal overflow-y-auto show" tabindex="-1" aria-hidden="false" style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle w-16 h-16 text-theme-6 mx-auto mt-3"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <div class="text-3xl mt-5">Are you sure?</div>
                    <div class="text-gray-600 mt-2">
                        Do you really want to delete this record?

                    </div>
                </div>
                <div class="px-5 pb-8 text-center">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                    <button type="button" id="deleteData" class="btn btn-danger w-24">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="status-confirmation-modal"  data-bs-backdrop="static" class="modal overflow-y-auto show" tabindex="-1" aria-hidden="false" style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle w-16 h-16 text-theme-6 mx-auto mt-3"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <div class="text-3xl mt-5">Are you sure?</div>
                    <div class="text-gray-600 mt-2">
                        <span id="statusTitle"> </span>

                    </div>
                </div>
                <div class="px-5 pb-8 text-center">
                    <button type="button" id="statusCancel" data-bs-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                    <button type="button" id="statusData" class="btn btn-danger w-24">Change</button>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="delete-image-confirmation-modal" class="modal overflow-y-auto show" tabindex="-1" aria-hidden="false" style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="p-5 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle w-16 h-16 text-theme-6 mx-auto mt-3"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    <div class="text-3xl mt-5">Are you sure?</div>
                    <div class="text-gray-600 mt-2">
                        Do you really want to delete this Image?

                    </div>
                </div>
                <div class="px-5 pb-8 text-center">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                    <button type="button" id="deleteImageData" class="btn btn-danger w-24">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
