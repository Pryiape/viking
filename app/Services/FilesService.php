<?php
// File: app/Services/FilesService.php

namespace App\Services;

class FilesService
{
    // Other methods...

    /**
     * Check if a file exists at the given path.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return file_exists($path);
    }
}


