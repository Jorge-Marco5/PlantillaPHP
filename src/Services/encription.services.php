<?php

declare(strict_types=1);

namespace App\Services;

class EncriptionServices
{
    private string $masterKey;

    public function __construct(string $masterKey)
    {
        $this->masterKey = $masterKey;
    }


    function encriptarArchivo(string $rutaOrigen, string $rutaDestino, string $masterKey): bool
    {
        $contenido = file_get_contents($rutaOrigen);
        if ($contenido === false)
            return false;

        $metodo = 'AES-256-CBC';
        $ivLongitud = openssl_cipher_iv_length($metodo);
        $iv = openssl_random_pseudo_bytes($ivLongitud);

        // Derivar una llave segura de 32 bytes usando SHA-256
        $key = hash('sha256', $masterKey, true);

        $cifrado = openssl_encrypt($contenido, $metodo, $key, OPENSSL_RAW_DATA, $iv);
        if ($cifrado === false)
            return false;

        // Guardar el IV concatenado con los datos cifrados
        $resultado = file_put_contents($rutaDestino, $iv . $cifrado);
        return $resultado !== false;
    }

    function desencriptarArchivo(string $rutaOrigen, string $rutaDestino, string $masterKey): bool
    {
        $cifradoCompleto = file_get_contents($rutaOrigen);
        if ($cifradoCompleto === false)
            return false;

        $metodo = 'AES-256-CBC';
        $ivLongitud = openssl_cipher_iv_length($metodo);

        if (strlen($cifradoCompleto) < $ivLongitud)
            return false;

        $iv = substr($cifradoCompleto, 0, $ivLongitud);
        $cifrado = substr($cifradoCompleto, $ivLongitud);
        $key = hash('sha256', $masterKey, true);

        $contenido = openssl_decrypt($cifrado, $metodo, $key, OPENSSL_RAW_DATA, $iv);
        if ($contenido === false)
            return false;

        $resultado = file_put_contents($rutaDestino, $contenido);
        return $resultado !== false;
    }

    function file_get_contents(string $filename): string|false
    {

        if (!file_exists($filename)) {
            return false;
        }
        return file_get_contents($filename);
    }
}
