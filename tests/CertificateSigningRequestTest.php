<?php

namespace Recca0120\PushCertificate\Tests;

use PHPUnit\Framework\TestCase;
use Recca0120\PushCertificate\Cert;
use Recca0120\PushCertificate\SignedKey;
use Recca0120\PushCertificate\CertificateSigningRequest;

class CertificateSigningRequestTest extends TestCase
{
    /** @test */
    public function test_make()
    {
        $signedKey = new SignedKey();
        $request = new CertificateSigningRequest();

        var_dump($request->save($signedKey));
    }

    /** @test */
    public function test_pem()
    {
        $cert = new Cert();

        var_dump($cert->make(__DIR__.'/aps_development.cer'));
    }
}
