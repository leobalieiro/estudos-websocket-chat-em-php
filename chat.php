<?php
function option_1()
{
    $host = "localhost";
    $port = 20205;
    set_time_limit(0);

    
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
    $result = socket_bind($socket, $host, $port);
    $result = socket_listen($socket, 3);

    echo "lendo conexoes";
    do {
        $accept = socket_accept($socket);
        $msg = trim(socket_read($accept, 1024));

        echo "CLiente disse: " . $msg;
        // Responder
        $reply = rtrim(fgets(STDIN));

        socket_write($accept, $reply);
    } while (true);
    socket_close($accept, $socket);
}

option_1();

// OPCAO 2
function option_2()
{
    $fp = fsockopen("ws://localhost", 20205, $errno, $errstr, 30);
    if (!$fp) {
        echo "$errstr ($errno)<br />";
    } else {
        $out = "GET / HTTP/1.1\n
                Host: ws://localhost:20205\n
                Connection: Close";
        fwrite($fp, $out);
        while (!feof($fp)) {
            echo fgets($fp, 128);
        }
        fclose($fp);
    }
}

//OPCAO 3
function option_3()
{
    error_reporting(E_ALL);

    /* Allow the script to hang around waiting for connections. */
    set_time_limit(0);

    /* Turn on implicit output flushing so we see what we're getting
 * as it comes in. */
    ob_implicit_flush();

    $address = 'ws://localhost';
    $port = 20205;

    if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
        echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    }

    if (socket_bind($sock, $address, $port) === false) {
        echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
    }

    if (socket_listen($sock, 5) === false) {
        echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
    }

    do {
        if (($msgsock = socket_accept($sock)) === false) {
            echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
            break;
        }
        /* Send instructions. */
        $msg = "\nWelcome to the PHP Test Server. \n" .
            "To quit, type 'quit'. To shut down the server type 'shutdown'.\n";
        socket_write($msgsock, $msg, strlen($msg));

        do {
            if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
                echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
                break 2;
            }
            if (!$buf = trim($buf)) {
                continue;
            }
            if ($buf == 'quit') {
                break;
            }
            if ($buf == 'shutdown') {
                socket_close($msgsock);
                break 2;
            }
            $talkback = "PHP: You said '$buf'.\n";
            socket_write($msgsock, $talkback, strlen($talkback));
            echo "$buf\n";
        } while (true);
        socket_close($msgsock);
    } while (true);

    socket_close($sock);
}

//OPCAO 4
function option_4()
{
    error_reporting(E_ALL);

    echo "<h2>TCP/IP Connection</h2>\n";

    /* Get the port for the WWW service. */
    $service_port = 20205;

    /* Get the IP address for the target host. */
    $address = "localhost";

    /* Create a TCP/IP socket. */
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    } else {
        echo "OK.\n";
    }

    echo "Attempting to connect to '$address' on port '$service_port'...";
    $result = socket_connect($socket, $address, $service_port);
    if ($result === false) {
        echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
    } else {
        echo "OK.\n";
    }

    $in = "HEAD / HTTP/1.1\r\n";
    $in .= "Host: www.example.com\r\n";
    $in .= "Connection: Close\r\n\r\n";
    $out = '';

    echo "Sending HTTP HEAD request...";
    socket_write($socket, $in, strlen($in));
    echo "OK.\n";

    echo "Reading response:\n\n";
    while ($out = socket_read($socket, 2048)) {
        echo $out;
    }

    echo "Closing socket...";
    socket_close($socket);
    echo "OK.\n\n";
}
