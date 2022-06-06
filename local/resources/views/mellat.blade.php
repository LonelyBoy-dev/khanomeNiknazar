<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>در حال اتصال به درگاه</title>
</head>

<form action="{{config('app.bank_startpay')}}" method="Post" id="mellatform">
    {{ csrf_field() }}

    <input type="hidden" name="refId" value="{{$tokenId}}">
</form>


<body>
<script>
    let form = document.getElementById('mellatform');
    form.submit();
</script>
</body>
</html>
