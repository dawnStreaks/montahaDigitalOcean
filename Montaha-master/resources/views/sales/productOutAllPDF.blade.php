{{--<!doctype html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--<meta charset="UTF-8">--}}
{{--<meta name="viewport"--}}
{{--content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">--}}
{{--<meta http-equiv="X-UA-Compatible" content="ie=edge">--}}
{{--<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css ')}}">--}}
{{--<!-- Font Awesome -->--}}
{{--<link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css')}} ">--}}
{{--<!-- Ionicons -->--}}
{{--<link rel="stylesheet" href="{{ asset('assets/bower_components/Ionicons/css/ionicons.min.css')}} ">--}}

{{--<title>Product Masuk Exports All PDF</title>--}}
{{--</head>--}}
{{--<body>--}}
<style>
    #product-masuk {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #product-masuk td, #product-masuk th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #product-masuk tr:nth-child(even){background-color: #f2f2f2;}

    #product-masuk tr:hover {background-color: #ddd;}

    #product-masuk th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #4CAF50;
        color: white;
    }
</style>


<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="5">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ asset('upload/logo/'.$companyInfo->logo) }}" style="width:100%; width:200px;">
                            </td>

                            <td></td>
                            <td></td>
                            <td></td>
                            
                            <td>
                                Purchase Order #: {{rand(1, 99999)}}<br>
                                Created: {{date("Y-m-d",time())}}<br>
                                Due: February 1, 2021
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="5">
                    <table>
                        <tr>
                            <td>
                                {{ $companyInfo->name }}<br>
                                {{ $companyInfo->address }}<br>
                                {{ $companyInfo->email }}
                            </td>

                            <td></td>
                            <td></td>
                            <td></td>
{{--                             
                            <td>
                                {{$Product_Out[0]->customer->name}}<br>
                                {{$Product_Out[0]->customer->address}}<br>
                                {{$Product_Out[0]->customer->email}}
                            </td> --}}
                        </tr>
                    </table>
                </td>
            </tr>
    </table>
</div>
<table id="product-masuk" width="100%">
    <thead>
    <tr>
        <td>ID</td>
        <td>Product</td>
        <td>Price</td>
        <td>Quantity</td>
        <td>Subtotal</td>
        <td>Date</td>
    </tr>
    </thead>
    @foreach($Product_Out as $p)
        <tbody>
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->product->name }}</td>
            <td>{{ $p->price }}</td>
            <td>{{ $p->qty }}</td>
            <td>{{ number_format($p->price * $p->qty, 2, '.', '') }}</td>
            <td>{{ $p->date }}</td>
        </tr>
        </tbody>
    @endforeach

</table>


{{--<!-- jQuery 3 -->--}}
{{--<script src="{{  asset('assets/bower_components/jquery/dist/jquery.min.js') }} "></script>--}}
{{--<!-- Bootstrap 3.3.7 -->--}}
{{--<script src="{{  asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }} "></script>--}}
{{--<!-- AdminLTE App -->--}}
{{--<script src="{{  asset('assets/dist/js/adminlte.min.js') }}"></script>--}}
{{--</body>--}}
{{--</html>--}}


