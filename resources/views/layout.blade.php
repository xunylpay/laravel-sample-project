<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PayMongo Laravel Sample</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>
    {{-- View Output --}}
    @yield('content')
    <section class="container">
        <div class="card mt-3">
            <div class="card-body">
                <p class="lead">PayMongo documentation links:</p>

                <p>API Guide: <a href="https://developers.paymongo.com/v2/docs" target="_blank">Link</a></p>
                <p>API Reference: <a href="https://developers.paymongo.com/v2/reference/getting-started-with-your-api"
                        target="_blank">Link</a></p>
                <p>Testing: <a href="https://developers.paymongo.com/v2/docs/testing" target="_blank">Link</a>
                </p>

            </div>
        </div>
    </section>
</body>

</html>