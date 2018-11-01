<?php

namespace Recca0120\PushCertificate;

use Exception;

class P12
{
    private $signedKey;

    private $password = null;

    public function __construct(SignedKey $signedKey = null)
    {
        $this->signedKey = $signedKey ?: new SignedKey();
    }

    public function setSignedKey(SignedKey $signedKey)
    {
        $this->signedKey = $signedKey;

        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function export($cert)
    {
        $pem = '-----BEGIN CERTIFICATE-----'."\n"
            .chunk_split(base64_encode($cert), 64, "\n")
            .'-----END CERTIFICATE-----'."\n";

        return $this->load($this->fromPem($pem));
    }

    private function load($p12)
    {
        if (openssl_pkcs12_read($p12, $data, $this->password)) {
            return [
                'data' => $data,
                'cert' => openssl_x509_parse($data['cert']),
            ];
        }

        throw new Exception(openssl_error_string());
    }

    private function fromPem($pem)
    {
        if (openssl_pkcs12_export($pem, $p12, $this->signedKey->getPrivateKey(), $this->password)) {
            return $p12;
        }

        throw new Exception(openssl_error_string());
    }
}
