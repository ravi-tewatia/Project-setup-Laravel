<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{env('APP_NAME')}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <table class="table table-striped table-bordered">
        <thead>
            <tr align="center">
                <th align="center" scope="col">FULL NAME</th>
                <th align="center" scope="col">EMAIL</th>
                <th align="center" scope="col">PHONE</th>
                <th align="center" scope="col">ADDRESS</th>
                <th align="center" scope="col">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($result))
            @foreach ($result as $key => $value)
            <tr>
                <td align="center">{{$value['full_name']}}</td>
                <td align="center">{{$value['email']}}</td>
                <td align="center">{{$value['phone']}}</td>
                <td align="center">{!!$value['full_address']!!}</td>
                <td align="center">{{$value['status_id_disp']}}</td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5" align="center"><b> No users data found.</b> </td>
            </tr>
            @endif
        </tbody>
    </table>
</body>

</html>
