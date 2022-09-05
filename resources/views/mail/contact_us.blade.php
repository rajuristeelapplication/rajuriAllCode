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
                <table width="620" border="0" cellspacing="0" cellpadding="0" align="center" class="scale section">
                    <tr>
                        <td bgcolor="#FFFFFF">
                            <table width="540" border="0" cellspacing="0" cellpadding="0" align="center"
                                class="agile1 scale">
                                <tr>
                                    <td height="25" style="font-size: 1px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="agile-main scale-center-both"
                                        style="font-weight: 400; color: #000; font-size: 20px; height:34px !important;">
                                        <b>Respond By Admin<b>
                                </tr>
                                <tr>
                                    <td class="esd-block-button" align="left" style="padding:15px 0;font-size: 13px;">
                                        <p style="word-break: break-all">{{$details['respond'] ?? ''}} </p><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w3l-p2 scale-center-both"
                                        style="font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:left;">

                                    </td>
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
