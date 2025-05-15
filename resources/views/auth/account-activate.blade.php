<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{env('APP_NAME')}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        .centerClass {
            text-align: center;
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body style="background-color: #474eb7!important;">
    <div class="container centerClass" style="">
        <!-- Modal -->
        <img src="{{url('storage/default/account-verified.png')}}" class="mx-auto d-block img-fluid" alt="Logo Home"
            style="text-align: center;">
        <div style="color: #FFF;">
            <div class="modal-body">
                <p>{{$result['message']}}</p>
                <p><a href="{{env('FRONTEND_URL')}}" style="color: #FFF;">click here to login</a></p>
            </div>
        </div>
    </div>
</body>

</html>
