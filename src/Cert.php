<?php

namespace Recca0120\PushCertificate;

class Cert
{
    private $p12;
    private $signedKey;
    private $files;

    public function __construct(P12 $p12 = null, SignedKey $signedKey = null, Filesystem $files = null)
    {
        $this->p12 = $p12 ?: new P12;
        $this->signedKey = $signedKey ?: new signedKey();
        $this->files = $files ?: new Filesystem();
    }

    public function make($cert, $password = null)
    {
        $p12 = $this->p12
            ->setSignedKey($this->signedKey)
            ->setPassword($password)
            ->export($this->files->get($cert));

        $cert = [
            'Bag Attributes',
            '    friendlyName: '.Arr::get($p12, 'cert.subject.CN'),
            '    localKeyID: '.Arr::get($p12, 'cert.extensions.subjectKeyIdentifier'),
            $this->line($p12, 'subject'),
            $this->line($p12, 'issuer'),
            Arr::get($p12, 'data.cert'),
            Arr::get($p12, 'data.pkey'),
        ];

        return implode("\n", $cert);
    }

    private function line($p12, $index)
    {
        $line = $index.'=';

        foreach (Arr::get($p12, 'cert.'.$index, []) as $key => $value) {
            $line .= '/'.$key.'='.$value;
        }

        return $line;
    }
}
