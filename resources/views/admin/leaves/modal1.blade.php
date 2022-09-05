<div id="leaveStatusChange-modal1" class="modal modalCloseResetAll" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto" id="formTitle">{{__('labels.update_status_title')}}</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form class="form updateLeaveStatus" name="updateLeaveStatus" id="updateLeaveStatus" method="post"
                enctype="multipart/form-data"  action="{{ route('changeSlipStatus')}}">
                {{ csrf_field() }}

                <input type="hidden" name="id" id="id" >
                {{-- <input type="hidden" name="status" id="statusValue" > --}}

                <div id="vertical-form" class="p-5">
                    <div class="preview">
                        <label for="vertical-form-1" class="form-label mt-2">{{__('labels.status')}}</label>
                        <select id="requestStatus" name="requestStatus"  class="form-select form-select mt-2  select2Class requestStatus">
                            <option value="{{__('labels.pending')}}">{{__('labels.pending')}}</option>
                            <option value="{{__('labels.approved')}}">{{__('labels.approved')}}</option>
                            <option value="{{__('labels.rejected')}}">{{__('labels.rejected')}}</option>
                        </select>
                    </div>

                    <div class="preview  paySleepPdf addClass">
                        <label for="vertical-form-1" class="form-label mt-2">
                            Pay Slip PDF </label>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <input class="form-control dropify mt-2 mb-2 payPdf" type="file" value=""
                                id="payPdf"  name="payPdf"
                                tabindex="5" placeholder="Please upload pdf"
                                data-show-remove="false" accept="application/pdf"
                                data-allowed-file-extensions="pdf"
                                data-default-file="{{$paySlipPdf->payPdf ?? ""}}">
                            <label id="image-error" class="error" for="payPdf"
                                style="display: none;">Please select pdf</label>
                        </div>
                    </div>



                    {{-- <div class="preview adminRejectOtherText addClass">
                        <label for="vertical-form-1" class="form-label mt-2">Reject Leave Reason</label>
                        <input type="text" name="adminRejectOtherText"
                            id="adminRejectOtherText"
                            class="form-control input-valid w-100 mt-2 mb-2 decimal countInput"
                            maxlength="255" placeholder="Please Enter Reject Reason "
                            aria-required="true">
                    </div> --}}

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
