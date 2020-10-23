<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>

    <style type="text/css">
        @page {
            margin: 0px;
        }
        body {
            margin: 0px;
        }
        * {
            font-family: Verdana, Arial, sans-serif;
        }
        a {
            color: #fff;
            text-decoration: none;
        }
        table {
            font-size: x-small;
        }
        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }
        .invoice table {
            margin: 15px;
        }
        .invoice h3 {
            margin-left: 15px;
        }
        .information {
            background-color: #60A7A6;
            color: #FFF;
        }
        .information .logo {
            margin: 5px;
        }
        .information table {
            padding: 10px;
        }
    </style>

</head>
<body>

<div class="information">
    <table width="100%">
        <tr>
            <td align="left" style="width: 40%;">
                <h3>{{$customer->name}}</h3>
                <pre>

<br /><br />
Date: <?php echo date('Y-m-d'); ?> </br>
Status: Paid
</pre>


            </td>
           
            <td  style="width: 40%; text-align:right; padding-right:7px;">

            <img style="padding-top: 20px;" src="C:\xampp\htdocs\TripApi\storage\app\logo.jpg" width="70px">
                <h3>{{$agency->company_name}}</h3>
                <pre>
                    {{$agency->web}} </br>
                   
                    Address: {{$agency->address}}
                </pre>
            </td>
        </tr>

    </table>
</div>


<br/>

<div class="invoice">
    <h3>Invoice specification</h3>
    <table width="100%">
        <thead>
        <tr style="font-size:18px">
            <th>Description</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
         
        <tr style="font-size:15px" >
            <td style="text-align:center; padding-top:5px;">{{$trip->title}}</td>
            <td style="text-align:center; padding-top:5px;">1</td>
            <td style="text-align:center; padding-top:5px;">{{$trip->price}}$</td>
        </tr>
       
       
        </tbody>
    </table>

    <div style="padding-top:30px" >
    <h4 style="text-align:right; margin-right:70px"><span >Subtotal: {{0.75 * $trip->price}}$</span></h4>
    <h4 style="text-align:right; margin-right:70px"><span > + Tax: {{0.25 * $trip->price}}$</span></h4>
    <hr>
    <h4 style="text-align:right; margin-right:70px"><span >Total: {{$trip->price}}$</span></h4>
</div>

<div class="information" style="position: absolute; bottom: 0;">
    <table width="100%">
        <tr>
            <td align="left" style="width: 50%;">
                &copy; {{ date('Y') }} {{$agency->web }} - All rights reserved.
            </td>
            <td align="right" style="width: 50%;">
            Fill Your Life With Adventure
            </td>
        </tr>

    </table>
</div>
</body>
</html>