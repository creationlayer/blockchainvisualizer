<html>
    <head>
        <title>WebSocket TEST</title>
    </head>
    <body>
        <h1>Server Time</h1>
        <strong id="time"></strong>

        <script>
            var socket = new WebSocket("ws://188.166.27.94:4024/");
            socket.onmessage = function(msg) {
                document.getElementById("time").innerText += msg.data + "\n";
            };
        </script>
    </body>
</html>