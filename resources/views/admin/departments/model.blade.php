<div id="department-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- BEGIN: Modal Header -->
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto" id="formTitle">{{__('labels.add_department')}}</h2>
            </div> <!-- END: Modal Header -->
            <!-- BEGIN: Modal Body -->
            <form class="form createDepartment" name="createDepartment" id="createDepartment" method="post"
                enctype="multipart/form-data" action="{{ route('departments.store') }}">
                {{ csrf_field() }}

                <input type="hidden" name="id" id="id" value="">
                <div id="vertical-form" class="p-5">
                    <div class="preview">
                        <label for="vertical-form-1" class="form-label mt-2">{{__('labels.department')}}</label>
                        <input id="{{__('labels.department_name')}}" minlength="2" maxlength="40" type="text" class="form-control"
                            name="{{__('labels.department_name')}}" placeholder="{{__('labels.enter_department')}}">
                    </div>
                </div>

                <!-- END: Modal Body -->
                <!-- BEGIN: Modal Footer -->
                <div class="modal-footer text-right">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1 reset_form"
                        id="reset_form">{{__('labels.cancel')}}</button>
                    <button type="{{__('labels.submit')}}" class="btn btn-primary w-20">{{__('labels.submit')}}</button>
                </div> <!-- END: Modal Footer -->
            </form>
        </div>
    </div>
</div>
<!-- END: Modal Content -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>
    $(".reset_form").click(function() {
        $("label.error").hide();
        $(".error").removeClass("error");
    });
</script>
