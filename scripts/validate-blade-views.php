#!/usr/bin/env php
<?php

/**
 * Blade View Validation Script
 *
 * Script ini memeriksa semua file blade view untuk memastikan:
 * 1. Semua view yang extends 'layouts.dashboard' memiliki @section('sidebar-menu')
 * 2. Format sidebar-menu sudah benar
 */
$errors = [];
$warnings = [];
$checked = 0;

// Directories to scan
$directories = [
    __DIR__.'/../resources/views/admin',
    __DIR__.'/../resources/views/guru',
    __DIR__.'/../resources/views/orangtua',
];

function scanDirectory($dir)
{
    $files = [];

    if (! is_dir($dir)) {
        return $files;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $files[] = $file->getPathname();
        }
    }

    return $files;
}

function checkBladeFile($filePath)
{
    global $errors, $warnings, $checked;

    $content = file_get_contents($filePath);
    $relativePath = str_replace(__DIR__.'/../', '', $filePath);

    // Check if extends layouts.dashboard
    if (! preg_match('/@extends\([\'"]layouts\.dashboard[\'"]\)/', $content)) {
        return; // Skip files that don't use dashboard layout
    }

    $checked++;

    // Check for sidebar-menu section
    if (! preg_match('/@section\([\'"]sidebar-menu[\'"]\)/', $content)) {
        $errors[] = "❌ MISSING SIDEBAR-MENU: $relativePath";

        return;
    }

    // Check if sidebar-menu has x-sidebar-menu component
    if (! preg_match('/<x-sidebar-menu/', $content)) {
        $errors[] = "❌ MISSING <x-sidebar-menu>: $relativePath";

        return;
    }

    // Check if guard is specified
    if (! preg_match('/:guard=[\'"](\w+)[\'"]/', $content, $matches)) {
        $warnings[] = "⚠️  NO GUARD SPECIFIED: $relativePath";
    }

    // Check if currentRoute is specified
    if (! preg_match('/:currentRoute=/', $content)) {
        $warnings[] = "⚠️  NO CURRENT ROUTE: $relativePath";
    }

    echo "✅ Valid: $relativePath\n";
}

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║          Blade View Validation Script                      ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

foreach ($directories as $dir) {
    if (! is_dir($dir)) {
        echo "⚠️  Directory not found: $dir\n";

        continue;
    }

    echo "Scanning: $dir\n";
    $files = scanDirectory($dir);

    foreach ($files as $file) {
        checkBladeFile($file);
    }
}

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                    Validation Results                       ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

echo "Total files checked: $checked\n";
echo 'Errors found: '.count($errors)."\n";
echo 'Warnings found: '.count($warnings)."\n";
echo "\n";

if (count($errors) > 0) {
    echo "ERRORS:\n";
    echo "-------\n";
    foreach ($errors as $error) {
        echo "$error\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "WARNINGS:\n";
    echo "---------\n";
    foreach ($warnings as $warning) {
        echo "$warning\n";
    }
    echo "\n";
}

if (count($errors) === 0 && count($warnings) === 0) {
    echo "✅ All views are valid!\n\n";
    exit(0);
} elseif (count($errors) > 0) {
    echo "❌ Validation failed! Please fix the errors above.\n\n";
    exit(1);
} else {
    echo "⚠️  Validation passed with warnings.\n\n";
    exit(0);
}
