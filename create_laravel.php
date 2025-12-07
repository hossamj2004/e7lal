<?php
// Create composer.json for Laravel 12
$composerJson = [
    "name" => "laravel/laravel",
    "description" => "The Laravel Framework.",
    "keywords" => ["framework", "laravel"],
    "license" => "MIT",
    "type" => "project",
    "require" => [
        "php" => "^8.2",
        "laravel/framework" => "^12.0",
        "laravel/tinker" => "^2.9"
    ],
    "require-dev" => [
        "fakerphp/faker" => "^1.23",
        "laravel/pint" => "^1.13",
        "laravel/sail" => "^1.26",
        "mockery/mockery" => "^1.6",
        "nunomaduro/collision" => "^8.0",
        "phpunit/phpunit" => "^11.0.1"
    ],
    "autoload" => [
        "psr-4" => [
            "App\\" => "app/"
        ],
        "classmap" => [
            "database/seeds",
            "database/factories"
        ]
    ],
    "autoload-dev" => [
        "psr-4" => [
            "Tests\\" => "tests/"
        ]
    ],
    "scripts" => [
        "post-autoload-dump" => [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi"
        ],
        "post-update-cmd" => [
            "@php artisan vendor:publish --tag=laravel-assets --ansi --force"
        ],
        "post-root-package-install" => [
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
        ],
        "post-create-project-cmd" => [
            "@php artisan key:generate --ansi"
        ]
    ],
    "extra" => [
        "laravel" => [
            "dont-discover" => []
        ]
    ],
    "config" => [
        "optimize-autoloader" => true,
        "preferred-install" => "dist",
        "sort-packages" => true,
        "allow-plugins" => [
            "pestphp/pest-plugin" => true,
            "php-http/discovery" => true
        ]
    ],
    "minimum-stability" => "stable",
    "prefer-stable" => true
];

file_put_contents('composer.json', json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "composer.json created successfully!\n";

// Download Laravel skeleton
$zipUrl = "https://github.com/laravel/laravel/archive/refs/heads/12.x.zip";
$zipFile = "laravel-skeleton.zip";

echo "Downloading Laravel skeleton...\n";
file_put_contents($zipFile, file_get_contents($zipUrl));

if (file_exists($zipFile)) {
    echo "Extracting Laravel skeleton...\n";
    $zip = new ZipArchive();
    if ($zip->open($zipFile) === TRUE) {
        $zip->extractTo('.');
        $zip->close();

        // Move contents from laravel-12.x directory to current directory
        $skeletonDir = 'laravel-12.x';
        if (is_dir($skeletonDir)) {
            $files = scandir($skeletonDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    rename($skeletonDir . '/' . $file, $file);
                }
            }
            rmdir($skeletonDir);
        }

        unlink($zipFile);
        echo "Laravel skeleton extracted successfully!\n";
    } else {
        echo "Failed to extract ZIP file\n";
    }
} else {
    echo "Failed to download Laravel skeleton\n";
}
?>




