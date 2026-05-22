<?php
// Quick MongoDB connection test
try {
    $uri = 'mongodb+srv://nwdankit701_db_user:4Bxw6Q2Ton4doy8x@outreachcluster.vp59vr8.mongodb.net/?retryWrites=true&w=majority&appName=OutReachCluster&tlsInsecure=true';
    $m = new MongoDB\Driver\Manager($uri);
    $cmd = new MongoDB\Driver\Command(['ping' => 1]);
    $m->executeCommand('admin', $cmd);
    echo "Connected successfully!\n";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    
    // Try without SRV
    echo "\nTrying direct connection...\n";
    try {
        $uri2 = 'mongodb+srv://nwdankit701_db_user:4Bxw6Q2Ton4doy8x@outreachcluster.vp59vr8.mongodb.net/?retryWrites=true&w=majority&tls=true&tlsAllowInvalidCertificates=true';
        $m2 = new MongoDB\Driver\Manager($uri2);
        $cmd2 = new MongoDB\Driver\Command(['ping' => 1]);
        $m2->executeCommand('admin', $cmd2);
        echo "Connected with alt config!\n";
    } catch(Exception $e2) {
        echo "Alt error: " . $e2->getMessage() . "\n";
    }
}
