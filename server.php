<?php
require 'vendor/autoload.php'; // Include Composer's autoloader
require 'includes/connection.php'; // Include your database connection

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use React\EventLoop\Factory;

class AuctionStatusUpdater implements MessageComponentInterface {
    protected $clients;
    protected $db;
    protected $loop;

    public function __construct($dbConnection, $loop) {
        $this->clients = new \SplObjectStorage;
        $this->db = $dbConnection;
        $this->loop = $loop;

        // Set Kathmandu timezone
        date_default_timezone_set('Asia/Kathmandu');

        // Set a timer to update auction status every minute
        $this->loop->addPeriodicTimer(60, [$this, 'updateAuctionStatus']);
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection: (" . spl_object_id($conn) . ")\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Handle incoming messages if necessary
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection " . spl_object_id($conn) . " has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function updateAuctionStatus() {
        $currentDateTime = date('Y-m-d H:i:s');
        echo "Current DateTime (Kathmandu): $currentDateTime\n";

        $sql = "UPDATE auctions SET status = CASE 
                    WHEN start_date > '$currentDateTime' THEN 'upcoming'
                    WHEN start_date <= '$currentDateTime' AND end_date >= '$currentDateTime' THEN 'live'
                    WHEN end_date < '$currentDateTime' THEN 'closed'
                END 
                WHERE start_date IS NOT NULL AND end_date IS NOT NULL";

        if (!$this->db->query($sql)) {
            echo "Error updating auction status: " . $this->db->error . "\n";
        }

        foreach ($this->clients as $client) {
            $client->send("Auction status updated.");
        }
    }
}

// Use the existing database connection from connection.php
$dbConnection = $conn;

$loop = Factory::create();
$server = new Ratchet\App('localhost', 8080, '0.0.0.0', $loop);
$server->route('/auctions', new AuctionStatusUpdater($dbConnection, $loop));

$loop->run();
