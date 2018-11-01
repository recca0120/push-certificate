<?php

namespace Recca0120\PushCertificate;

class Filesystem
{
    public function exists($path)
    {
        return file_exists($path);
    }

    public function get($path)
    {
        return file_get_contents($path);
    }

    public function put($path, $data)
    {
        return file_put_contents($path, $data);
    }
}
