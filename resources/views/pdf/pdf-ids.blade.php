<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TAGCOTZ</title>
</head>

<body>
    <table style="width: 100%; border: 1px dotted black; border-collapse: collapse;">
        @foreach ($attendees as $key => $attendee)
            <tr>
                @foreach ($attendee as $singleCard)
                    <td style="min-width: 85.6mm;min-height: 53.98mm; border: 2px dotted black;padding: 6px;">
                        <table style="width: 100%;">
                            <tr>
                                <td>
                                    <img src="{{ public_path('img/logo.png') }}" style="height: 50px; width: 50px"
                                        alt="Logo">
                                </td>
                                <td style="font-size: 18px;  font-weight: bold">TAGCOTZ</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-size: 14px">
                                    {{--  <b>{{ $singleCard->name }}</b>  --}}
                                </td>
                                <td rowspan="5" style="width: 70px;">
                                    {!! str_replace('<?xml version="1.0" encoding="UTF-8"?>','',QrCode::size(130)->generate('TAGCOTZ-'.$singleCard->id)) !!}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 4px;">
                                    <b>
                                        {{ $singleCard->name }}
                                    </b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    {{ $singleCard->institution }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Region: {{ $singleCard->region->name }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Region: {{ $singleCard->district->name }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center; font-size: 10px">
                                    <i>{{ date('Y', time()) }}</i></td>
                            </tr>
                        </table>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</body>

</html>
