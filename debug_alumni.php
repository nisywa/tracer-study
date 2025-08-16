<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->boot();

echo "=== DEBUG INFO ===\n";

// Test user exists
echo "\n=== TEST USER EXISTS ===\n";
$users = \App\Models\User::with('alumni')->limit(5)->get();
foreach ($users as $user) {
    echo "- User ID: {$user->id}, Name: {$user->name}, Role: {$user->role}\n";
    if ($user->alumni) {
        echo "  Alumni: {$user->alumni->nama}, Tahun Lulus: {$user->alumni->tahun_lulus}\n";
    }
}

// Test survey exists  
echo "\n=== TEST SURVEY EXISTS ===\n";
$surveys = \App\Models\Survey::limit(3)->get();
foreach ($surveys as $survey) {
    echo "- Survey ID: {$survey->id}, Name: {$survey->nama}, Type: {$survey->type_survei}\n";
}

// Test alumni exists
echo "\n=== TEST ALUMNI EXISTS ===\n";
$alumni = \App\Models\Alumni::with('user')->limit(5)->get();
foreach ($alumni as $alum) {
    echo "- Alumni: {$alum->nama}, Tahun Lulus: {$alum->tahun_lulus}";
    if ($alum->user) {
        echo " (User ID: {$alum->user->id})";
    } else {
        echo " (No User)";
    }
    echo "\n";
}

// Test graduation years
echo "\n=== TEST GRADUATION YEARS ===\n";
$years = \App\Models\Alumni::whereNotNull('tahun_lulus')
    ->where('tahun_lulus', '!=', '')
    ->distinct()
    ->orderBy('tahun_lulus', 'desc')
    ->pluck('tahun_lulus');
echo "Years: " . $years->implode(', ') . "\n";

echo "\n=== DONE ===\n";
