<?php

namespace Recca0120\PushCertificate;

use phpseclib\File\X509;

class CertificateSigningRequest
{
    private $files;

    private $props = [
        'emailAddress' => 'recca0120@gmail.com',
    ];

    public function __construct(Filesystem $files = null)
    {
        $this->files = $files ?: new Filesystem();
    }

    public function setProps($props)
    {
        $this->props = $props;

        return $this;
    }

    public function create(SignedKey $signedKey)
    {
        $X509 = new X509();
        $X509->setPublicKey($signedKey->getRsaPublicKey());
        $X509->setPrivateKey($signedKey->getRsaPrivateKey());

        foreach ($this->props as $key => $value) {
            $X509->setDNProp($key, $value);
        }

        return $X509->saveCSR($X509->signCSR());
    }

    public function save(SignedKey $signedKey, $path = null, $filename = 'CertificateSigningRequest.certSigningRequest')
    {
        $path = $path ?: __DIR__.'/keys/'.$filename;

        return $this->files->put($path, $this->create($signedKey));
    }
}
