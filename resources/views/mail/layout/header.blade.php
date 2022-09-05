<table class="w3-ban" width="100%" height="150" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td bgcolor="#1F4BA4">
            <table width="620" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
                <tr>
                    <td>
                        <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
                            <tr>
                                <td class="wthree-logo" align="center">
                                    <a target="_blank" href="{{ url('/') }}">
                                        <img class="img-medium"
                                            style="width: 100px;max-width: 100%; text-align: center; "
                                            src="{{ url('admin-assets/images/logo/logo.png') }}" alt=""
                                            title="{{ config('app.name') }}">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td bgcolor="#232488"
            style="background: url({{ url('admin-assets/') }}) center center / cover no-repeat, #f3f3f3;">
            <table width="620" border="0" cellspacing="0" cellpadding="0" align="center" class="scale section">
                @yield('header')
            </table>
        </td>
    </tr>
</table>
