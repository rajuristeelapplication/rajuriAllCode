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
                            class="form-select form-select mt-2 requestStatus selectStatus">
                            <option value="{{__('labels.pending')}}">{{__('labels.pending')}}</option>
                            <option value="{{__('labels.not_available')}}">{{__('labels.not_available')}}</option>
                            <option value="{{__('labels.approved')}}">{{__('labels.approved')}}</option>
                        </select>
                    </div>

                    <div class="preview vehicleNumber">
                        <label for="vertical-form-1" class="form-label mt-2">{{__('labels.vehicle_number')}}</label>

                     <select id="{{__('labels.vehicle_number_db')}}" name="{{__('labels.vehicle_number_db')}}"
                        class="form-select form-select mt-2 selectStatus">
                        <option value="">Please select vehicle number</option>
                        @php
                        $vehicleArray = config('constant.vehicleArray');
                        @endphp
                            @foreach ($vehicleArray as $key => $vehicle)
                            <option value="{{ $vehicle }}"> {{ $vehicle }}</option>
                            @endforeach
                     </select>

                     <label id="vehicleNumber-error" class="error" style="display:none;" for="vehicleNumber">Please select vehicle number</label>

                    </div>
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
