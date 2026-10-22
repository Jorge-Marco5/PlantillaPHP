<?php

declare(strict_types=1);

namespace App\Services;

class EncriptionServices
{
    private string $masterKey;

    public function __construct(string $masterKey = '')
    {
        $this->masterKey = $masterKey;
    }

    public function encriptarArchivo(string $rutaOrigen, string $rutaDestino, ?string $masterKey = null): bool
    {
        $contenido = $this->file_get_contents($rutaOrigen);
        if ($contenido === false) {
            return false;
        }

        $metodo = 'AES-256-CBC';
        $ivLongitud = openssl_cipher_iv_length($metodo);
        if ($ivLongitud === false || $ivLongitud <= 0) {
            return false;
        }

        $iv = openssl_random_pseudo_bytes($ivLongitud);

        // Derivar una llave segura de 32 bytes usando SHA-256
        $effectiveKey = $masterKey ?? $this->masterKey;
        $key = hash('sha256', $effectiveKey, true);

        $cifrado = openssl_encrypt($contenido, $metodo, $key, OPENSSL_RAW_DATA, $iv);
        if ($cifrado === false) {
            return false;
        }

        // Guardar el IV concatenado con los datos cifrados
        $resultado = file_put_contents($rutaDestino, $iv . $cifrado);
        return $resultado !== false;
    }

    public function desencriptarArchivo(string $rutaOrigen, string $rutaDestino, ?string $masterKey = null): bool
    {
        $cifradoCompleto = $this->file_get_contents($rutaOrigen);
        if ($cifradoCompleto === false) {
            return false;
        }

        $metodo = 'AES-256-CBC';
        $ivLongitud = openssl_cipher_iv_length($metodo);
        if ($ivLongitud === false || $ivLongitud <= 0) {
            return false;
        }

        if (strlen($cifradoCompleto) < $ivLongitud) {
            return false;
        }

        $iv = substr($cifradoCompleto, 0, $ivLongitud);
        $cifrado = substr($cifradoCompleto, $ivLongitud);
        $effectiveKey = $masterKey ?? $this->masterKey;
        $key = hash('sha256', $effectiveKey, true);

        $contenido = openssl_decrypt($cifrado, $metodo, $key, OPENSSL_RAW_DATA, $iv);
        if ($contenido === false) {
            return false;
        }

        $resultado = file_put_contents($rutaDestino, $contenido);
        return $resultado !== false;
    }

    public function file_get_contents(string $filename): string|false
    {
        if (!file_exists($filename)) {
            return false;
        }
        return file_get_contents($filename);
    }
}
