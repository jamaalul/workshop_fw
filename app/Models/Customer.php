<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'photo_blob',
        'photo_path',
    ];

    /**
     * Return base64-encoded photo blob for inline display.
     * PostgreSQL returns bytea columns as PHP resource streams,
     * so we need to handle both string and resource types.
     */
    public function getPhotoBase64Attribute(): ?string
    {
        $raw = $this->photo_blob;

        if (!$raw) {
            return null;
        }

        // PostgreSQL PDO driver can return a resource stream for bytea
        if (is_resource($raw)) {
            $raw = stream_get_contents($raw);
        }

        // Some drivers may return hex-encoded \x... strings
        if (is_string($raw) && str_starts_with($raw, '\x')) {
            $raw = hex2bin(substr($raw, 2));
        }

        return 'data:image/jpeg;base64,' . base64_encode($raw);
    }
}
