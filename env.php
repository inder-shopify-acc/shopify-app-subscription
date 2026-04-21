<?php
function getDotEnv($key) {
    // Try getenv() or $_ENV first
    $value = getenv($key) ?: $_ENV[$key] ?? null;

    // If value is null, read from the .env file directly
    if ($value === null) {
        $envFile = __DIR__ . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), "#") === 0) continue; // Skip full-line comments

                $parts = explode('=', $line, 2);
                if (count($parts) == 2) {
                    $envKey = trim($parts[0]);
                    $envValue = trim($parts[1]);

                    // Remove surrounding quotes (for PHP 7.x)
                    if ((substr($envValue, 0, 1) === '"' && substr($envValue, -1) === '"') ||
                        (substr($envValue, 0, 1) === "'" && substr($envValue, -1) === "'")) {
                        $envValue = substr($envValue, 1, -1);
                    }

                    if ($envKey === $key) {
                        return $envValue;
                    }
                }
            }
        }
    }

    return $value ?? ''; // Return empty string if not found
}
