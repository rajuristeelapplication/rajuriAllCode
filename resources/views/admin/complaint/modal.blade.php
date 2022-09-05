<div id="userStatusChange-modal" class="modal modalCloseResetAll" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto" id="formTitle">{{__('labels.update_status_title')}}</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form class="form updateStatus" name="updateStatus" id="updateStatus" method="post"
                enctype="multipart/form-data">
                {{ csrf_field() }}

                <input type="hidden" name="id" id="dataId">
                <input type="hidden" name="status" id="statusValue">

                <div id="vertical-form" class="p-5">
                    <div class="preview">
                        <label for="vertical-form-1" class="form-label mt-2">{{__('labels.status')}}</label>
                        <select id="requestStatus" name="requestStatus"
                            class="form-select form-select mt-2 requestStatus select2Class1">
                            <option value="{{__('labels.pending')}}">{{__('labels.pending')}}</option>
                            <option value="{{__('labels.solved')}}">{{__('labels.solved')}}</option>
                        </select>
                    </div>
                    {{-- <br>
                    <div class="input-form remarkTextarea hideClass">
                        <label class="d-flex flex-column flex-sm-row"> Desscription <span
                                class="text-danger">
                                *</span> </label>
                        <textarea type="text" name="cDesc" id="cDesc"
                            class="form-control input w-100 mt-2 mb-2"
                            value="" rows="3"
                            placeholder="Please enter description" aria-required="true" required></textarea>
                    </div>
 --}}

                </div>
                <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer text-right">
                    <button type="button" data-bs-dismiss="modal"
                        class="btn btn-outline-secondary w-20 mr-1 resetForm">{{__('labels.cancel')}}</button>
                    <button type="submit" class="btn btn-primary w-20">{{__('labels.submit')}}</button>
                </div> <!-- END: Modal Footer -->
            </form>
        </div>
    </div>
</div>
<!-- END: Modal Content -->
