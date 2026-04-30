<?php

namespace Hub\Helpers;

use Hub\Core\Config;

class FileValidator {
    public static function validateResource($file) {
        $errors = [];

        // Check if file was uploaded without errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "File upload failed with error code: " . $file['error'];
            return $errors;
        }

        // Check file size
        if ($file['size'] > Config::MAX_FILE_SIZE) {
            $errors[] = "File size exceeds the limit of 15MB.";
        }

        // Check extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, Config::ALLOWED_EXTENSIONS)) {
            $errors[] = "Invalid file type. Allowed: " . implode(', ', Config::ALLOWED_EXTENSIONS);
        }

        // Robust MIME Type Verification (Engineering Factor)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimes = [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // docx
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' // pptx
        ];

        if (!in_array($mime, $allowedMimes)) {
            $errors[] = "File content doesn't match the reported extension (Security Alert).";
        }

        return $errors;
    }
}
