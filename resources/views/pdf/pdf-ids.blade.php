<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TAGCOTZ</title>
    <style>
        @page {
            margin: 0;
        }
    </style>
</head>

<body>
    @foreach ($attendees as $singleCard)
        <div
            style="
        background: linear-gradient(rgba(29, 71, 143,0.3), rgb(255,255,255), rgb(255,255,255));
             height:100%; padding:6px">
            <table style="width:100%">
                <tbody>
                    <tr>
                        <td>
                            <img src="{{ public_path('img/logo.png') }}" style="height: 50px; width: 50px;"
                                alt="Logo">
                        </td>
                        <td style="font-size: 18px;  font-weight: bold; text-align:right">TAGCOTZ</td>
                    </tr>
                    <tr>
                        <td colspan="2"
                            style="text-align: center;  background-color: #1D478F; font-size:.75em; font-weight:bold; color:white">
                            27-29 Feb 2024</td>
                    </tr>
                    <tr style="">
                        <td colspan="2">
                            <b>
                                {{ $singleCard->user->name }}
                            </b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size:.6em; text-transform:uppercase;">
                            {{ $singleCard->user->institution }}
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="2" style="width: 70px; ">
                            {!! str_replace(
                                '<?xml version="1.0" encoding="UTF-8"?>',
                                '',
                                QrCode::size(70)->generate('TAGCOTZ-' . $singleCard->id),
                            ) !!}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:.8em; padding-left:10px">
                            <p style="padding-top: 20px"><b>Venue:</b> AICC </p>
                            <p style="padding-top: 20px"><b>Region:</b> {{ $singleCard->user->region?->name }}</p>
                            <p style="padding-top: 20px"><b>District:</b> {{ $singleCard->user->district?->name }}</p>
                        </td>
                    </tr>
                   
                </tbody>
            </table>
        </div>
    @endforeach

</body>

</html>
