<?php
// Enable all error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Server configuration
$rcon_password = ''; // your Rcon password
$server_IP = '';         // your server_ip, e.g. '198.168.124.142'
$server_port = '28961';           // your server_port

echo "Starting screenshot process...<br>";
echo "Server IP: {$server_IP}<br>";
echo "Server Port: {$server_port}<br>";

// Function to send rcon command to COD4X server via UDP
function sendRconCommand($ip, $port, $rcon_password, $command) {
    echo "Attempting to connect to {$ip}:{$port}...<br>";
    
    $fp = @fsockopen('udp://' . $ip, $port, $errno, $errstr, 10);
    
    if (!$fp) {
        echo "ERROR: Cannot connect to server ($errno): $errstr<br>";
        return false;
    }
    
    echo "Connected successfully!<br>";
    echo "Sending command: {$command}<br>";
    
    $packet = "\xff\xff\xff\xff" . 'rcon "' . $rcon_password . '" ' . $command . "\n";
    $bytes_written = fwrite($fp, $packet);
    
    if ($bytes_written === false) {
        echo "Failed to write data to socket<br>";
    } else {
        echo "Sent {$bytes_written} bytes to server<br>";
    }
    
    // Try to read response (COD4X servers may or may not respond to rcon commands)
    echo "Attempting to read response...<br>";
    stream_set_timeout($fp, 5);
    $response = '';
    
    while ($buf = fread($fp, 1024)) {
        $response .= $buf;
        echo "Received data from server<br>";
        if (strlen($buf) < 1024) break;
    }
    
    if (empty($response)) {
        echo "No response received from server (this might be normal)<br>";
    } else {
        echo "Response received: " . bin2hex($response) . "<br>";
        echo "Response (text): " . htmlspecialchars(preg_replace('/[\x00-\x1F\x7F]/', '', $response)) . "<br>";
    }
    
    fclose($fp);
    echo "Connection closed<br>";
    
    return true;
}

// Take screenshots of all players
echo "Sending 'getss all' command...<br>";
$result = sendRconCommand($server_IP, $server_port, $rcon_password, 'getss all');

if ($result) {
    echo "Command sent successfully!<br>";
} else {
    echo "Failed to send command<br>";
}

// Output result
echo "Script execution complete<br>";
?>