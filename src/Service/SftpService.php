<?php

namespace App\Service;

use phpseclib3\Net\SFTP;
use phpseclib3\Exception\RuntimeException;

class SftpService
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;

    public function __construct(
        string $host, 
        int $port, 
        string $username, 
        string $password
    ){
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    public function uploadFile(string $localFilePath, string $remoteFilePath): bool
    {
        try {
            $sftp = new SFTP($this->host, $this->port);

            if (!$sftp->login($this->username, $this->password)) {
                throw new RuntimeException('Login failed!');
            }

            return $sftp->put($remoteFilePath, $localFilePath, SFTP::SOURCE_LOCAL_FILE);
        } catch (RuntimeException $e) {
            // Handle exception or log error
            return false;
        }
    }
}
