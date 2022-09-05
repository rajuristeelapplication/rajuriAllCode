
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="{{url('js/all.min.js')}}"></script>
<script src="{{ url('js/jquery.validate.min.js') }}" ></script>
<!-- Bootstrap tether Core JavaScript -->
<script type="text/javascript">
	
//for alert message disppper after 3 sec
$(document).ready(function(){
    setTimeout(function(){$('.alertdisapper').fadeOut();}, 2000);
});

</script>

@yield('js')
