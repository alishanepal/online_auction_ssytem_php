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
        $this->db = $dbConnection; // Use the existing database connection
        $this->loop = $loop;

        // Set a timer to update auction status every minute
        $this->loop->addPeriodicTimer(60, [$this, 'updateAuctionStatus']);
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection
        $this->clients->attach($conn);
        echo "New connection: (" . spl_object_id($conn) . ")\n"; // Use spl_object_id
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Handle incoming messages if necessary
    }

    public function onClose(ConnectionInterface $conn) {
        // Detach the connection
        $this->clients->detach($conn);
        echo "Connection " . spl_object_id($conn) . " has disconnected\n"; // Use spl_object_id
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function updateAuctionStatus() {
        $currentDateTime = date('Y-m-d H:i:s');
        echo "Current DateTime: $currentDateTime\n";

        // Prepare the SQL query
        $sql = "UPDATE auctions SET status = CASE 
                    WHEN start_date > '$currentDateTime' THEN 'upcoming'
                    WHEN start_date <= '$currentDateTime' AND end_date >= '$currentDateTime' THEN 'live'
                    WHEN end_date < '$currentDateTime' THEN 'closed'
                END 
                WHERE start_date IS NOT NULL AND end_date IS NOT NULL";

        // Execute the query
        if (!$this->db->query($sql)) {
            echo "Error updating auction status: " . $this->db->error . "\n";
        }

        // Broadcast updated status to clients (if necessary)
        foreach ($this->clients as $client) {
            // Send the updated status (you may need to format this)
            $client->send("Auction status updated.");
        }
    }
}

// Use the existing database connection from connection.php
$dbConnection = $conn; // Use the connection from your connection.php

// Create the React event loop
$loop = Factory::create();

// Create the WebSocket server
$server = new Ratchet\App('localhost', 8080, '0.0.0.0', $loop);
$server->route('/auctions', new AuctionStatusUpdater($dbConnection, $loop));

// Run the server
$loop->run();
