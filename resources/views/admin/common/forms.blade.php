<form id="deleteForm" name="deleteForm" action="" method="post">
    @csrf
    @method('DELETE')
</form>

<form id="changeStatusForm" name="changeStatusForm" action="" method="post">
    @csrf
</form>

<form id="sendNotificationForm" name="sendNotificationForm" action="">
    @csrf
</form>
