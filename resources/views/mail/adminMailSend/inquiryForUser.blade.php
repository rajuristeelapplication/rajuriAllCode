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
                                    <td class="agile-main scale-center-both"
                                        style="font-weight: 400; text-align: center; color: #000; font-size: 15px; height:34px !important;">
                                        <b>Inquiry<b>
                                </tr>
                                <tr>
                                    <td height="25" style="font-size: 1px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="esd-block-button" align="left" style="padding:15px 0;font-size: 13px;">
                                        <h2>User Name </h2>
                                        <p>{{ $feedback->fullName }}</p><br>

                                        <h2>Email </h2>
                                        <p>{{ $feedback->email }} </p><br>

                                        <h2>Subject </h2>
                                        <p>{{ strtolower($feedback->post) }}</p><br>

                                        <h2>Content </h2>
                                        <p>{{ $feedback->message }} </p><br>

                                    </td>
                                </tr>
                                <tr>
                                    <td height="25" style="font-size: 1px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="w3l-p2 scale-center-both"
                                        style="font-weight: 400; color: #000; font-size: 14px; line-height: 20px; text-align:left;">
                                        <!-- The user {{ strtolower($feedback->post) }} -->
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
