<!DOCTYPE html>
<html>

<head>
    <title>Socket.IO chat</title>
    <style>
        body {
            margin: 0;
            padding-bottom: 3rem;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        #form {
            background: rgba(0, 0, 0, 0.15);
            padding: 0.25rem;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            height: 3rem;
            box-sizing: border-box;
            backdrop-filter: blur(10px);
        }

        #input {
            border: none;
            padding: 0 1rem;
            flex-grow: 1;
            border-radius: 2rem;
            margin: 0.25rem;
        }

        #input:focus {
            outline: none;
        }

        #form>button {
            background: #333;
            border: none;
            padding: 0 1rem;
            margin: 0.25rem;
            border-radius: 3px;
            outline: none;
            color: #fff;
        }

        #messages {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        #messages>li {
            padding: 0.5rem 1rem;
        }

        #messages>li:nth-child(odd) {
            background: #efefef;
        }
    </style>
</head>

<body>
    <?php
    // Teste 2
    //include("chat.php"); 
    if ($_POST) {
        $msg = $_REQUEST['message'];
        $socket = socket_create(AF_INET, SOCK_STREAM, 0);
        socket_connect($socket, "127.0.0.1", 20205);

        socket_write($socket, $msg, strlen($msg));

        echo "Servido disse " . trim(socket_read($socket, 1924));
    }
    ?>

    <form method="post">
        <input name="message" autocomplete="off" /><button type="submit">Send</button>
    </form>
    <!-- <script type="text/javascript">
        function WebSocketTest() {

            if ("WebSocket" in window) {
                alert("WebSocket is supported by your Browser!");

                // Let us open a web socket
                var ws = new WebSocket("ws://localhost:20205/echo");

                ws.onopen = function() {
                    // Web Socket is connected, send data using send()
                    ws.send("Message to send");
                    alert("Message is sent...");
                };

                ws.onmessage = function(evt) {
                    var received_msg = evt.data;
                    alert("Message is received...");
                };

                ws.onclose = function() {
                    // websocket is closed.
                    alert("Connection is closed...");
                };
            } else {
                // The browser doesn't support WebSocket
                alert("WebSocket NOT supported by your Browser!");
            }
        }
    </script>
    <script>
        let socket = new WebSocket("ws://localhost:20205/send");

        socket.onopen = function(e) {
            alert("[open] Connection established" + e);
            socket.send("My name is John");
        };

        socket.onmessage = function(msg) {
            console.log("Ws-data" + msg);
            log("Server>: " + msg.data);
        };

        // socket.onclose = function(event) {
        //     if (event.wasClean) {
        //         alert(`[close] Connection closed cleanly, code=${event.code} reason=${event.reason}`);
        //     } else {
        //         // e.g. server process killed or network down
        //         // event.code is usually 1006 in this case
        //         alert('[close] Connection died');
        //     }
        // };

        // socket.onerror = function(error) {
        //     alert(`[error] ${error.message}`);
        // };

        function send() {
            let socket = new WebSocket("ws://localhost:20205/send");
            let msg = document.querySelector('#input').value;
            socket.onmessage = function(event) {
                socket.send(msg);
            };
        }
    </script> -->
</body>

</html>