<!doctype html>
<html>
<head>
    <title>BotMan Widget Chat</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ BotManWebWidget::asset('resources/css/common.css') }}">
</head>
<body class="relative flex flex-col p-0">
    <div id="chat" v-cloak></div>
    <script>
        window.botmanWidget = @json(array_merge(BotManWebWidget::getClientConfig($config)))
    </script>
    <script type="module" src="{{ BotManWebWidget::asset('resources/js/chat.js') }}"></script>
</body>
</html>
