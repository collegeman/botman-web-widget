<!doctype html>
<html>
<head>
    <title>BotMan Widget Chat</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="{{ BotmanWebWidget::asset('resources/css/common.css') }}">
</head>
<body class="relative flex flex-col p-0">
    <div id="chat" v-cloak></div>
    <script>
        window.botmanWidget = @json(BotmanWebWidget::config())
    </script>
    <script type="module" src="{{ BotmanWebWidget::asset('resources/js/chat.js') }}"></script>
</body>
</html>
