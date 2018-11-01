<?php

namespace Recca0120\PushCertificate;

use phpseclib\Crypt\RSA;

class SignedKey
{
    private $config;

    private $filesystem;

    private $publicKey;

    private $privateKey;

    public function __construct($config = [], Filesystem $files = null)
    {
        $this->config = array_merge([
            'publicKey' => __DIR__.'/keys/id_rsa.pub',
            'privateKey' => __DIR__.'/keys/id_rsa',
        ]);
        $this->files = $files ?: new Filesystem();
    }

    public function getPublicKey($returnCrypt = false)
    {
        return $this->load()->publicKey;
    }

    public function getRsaPublicKey()
    {
        return $this->asRsa($this->getPublicKey());
    }

    public function getPrivateKey($returnCrypt = false)
    {
        return $this->load()->privateKey;
    }

    public function getRsaPrivateKey()
    {
        return $this->asRsa($this->getPrivateKey());
    }

    private function asRsa($key)
    {
        $rsa = new RSA();
        $rsa->loadKey($key);

        return $rsa;
    }

    private function load()
    {
        if (
            $this->files->exists($this->config['publicKey']) === false ||
            $this->files->exists($this->config['privateKey'] === false)
        ) {
            $rsa = new RSA();
            $key = $rsa->createKey(2048);

            $this->files->put($this->config['publicKey'], $key['publickey']);
            $this->files->put($this->config['privateKey'], $key['privatekey']);
        }

        $this->publicKey = $this->files->get($this->config['publicKey']);
        $this->privateKey = $this->files->get($this->config['privateKey']);

        return $this;
    }
}
