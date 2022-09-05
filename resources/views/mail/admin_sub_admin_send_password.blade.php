@extends('mail.layout.index')
@section('header')
    <tr>
        <td bgcolor="#FFFFFF" style="border-top-left-radius: 4px; border-top-right-radius: 4px;">
            <table width="540" border="0" cellspacing="0" cellpadding="0" align="center" class="scale">
            </table>
        </td>
    </tr>

@endsection

@section('content')
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
            <td bgcolor="#f3f3f3">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="scale section">
                    <tr>
                        <td bgcolor="#FFFFFF">
                            <table width="540" border="0" cellspacing="0" cellpadding="0" align="center"
                                class="agile1 scale">
                                <tr>
                                    <td class="agile-main scale-center-both"
                                        style="font-weight: 400; text-align: center; color: #000; font-size: 15px; height:34px !important;">
                                        {{-- <b>Respond By Admin To Topup Request<b> --}}
                                </tr>
                                <tr>
                                    <td class="esd-block-button" align="left" style="padding:15px 0;font-size: 14px;">
                                        <p style="margin-left: 30px;">Dear <b>{{ $details->fullName ?? ''}},</b><br><br>
                                            <br>
                                            Your Access URL : <a href="{{url($siteUrl)}}"  target="_blank" style="color:#e82148;"><b>{{url($siteUrl)}}</b></a>
                                            <br>
                                            <br>
                                            Your Email : <b>{{ $details->email ?? ''}}</b>
                                            <br>
                                            <br>
                                            Your  Password : <b>{{ $password  ?? ''}}</b>

                                            <br><br><br>
                                            Thanks,<br>
                                            <b> {{ env('APP_NAME')}} Team</b>
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td height="25" style="font-size: 1px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="w3l-p2 scale-center-both"
                                        style="font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:left;">

                                    </td>
                                </tr>

                                <tr>
                                    <td height="15" style="font-size: 1px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="w3l-p2 scale-center-both"
                                        style="font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:left;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
