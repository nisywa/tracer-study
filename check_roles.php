<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->boot();

echo "=== ROLES ===\n";
$roles = \Spatie\Permission\Models\Role::pluck('name')->toArray();
foreach ($roles as $role) {
    echo "- {$role}\n";
}

echo "\n=== USERS ===\n";
$users = \App\Models\User::with('roles')->select('name', 'email', 'role')->get();
foreach ($users as $user) {
    $userRoles = $user->roles->pluck('name')->implode(', ');
    echo "- {$user->name} ({$user->email}) | DB Role: {$user->role} | Spatie Roles: {$userRoles}\n";
}
