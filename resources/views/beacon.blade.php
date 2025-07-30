<!doctype html>
<html>
<head>
    <title>BotMan Widget Chat</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ BotManWebWidget::asset('resources/css/common.css') }}">
</head>
<body class="relative flex flex-col p-0 justify-center items-center h-screen">
    <div id="beacon" v-cloak></div>
    <script>
        window.botmanWidget = @json(BotManWebWidget::getClientConfig($config))
    </script>
    <script type="module" src="{{ BotManWebWidget::asset('resources/js/beacon.js') }}"></script>
</body>
</html>
