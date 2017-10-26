<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/cotint/fileManager/css/kamadatepicker.min.css" rel="stylesheet" type="text/css">
    <link href="{{ url('cotint/fileManager/css/dropzone.css') }}" rel="stylesheet" type="text/css">
    <link href="/cotint/fileManager/css/style.css" rel="stylesheet" type="text/css">
</head>
<body>


@yield('content')


<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="{{ '/cotint/fileManager/js/dropzone.js' }}"></script>
<script src="/cotint/fileManager/js/kamadatepicker.min.js"></script>
<script>
    kamaDatepicker('test-date-id');
</script>
@yield('scripts')
</body>
</html>
