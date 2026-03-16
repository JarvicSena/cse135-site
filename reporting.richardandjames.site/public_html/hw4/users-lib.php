<?php
require_once __DIR__ . "/config.php";

function ensure_users_file(): void {
    if (!is_dir(dirname(USERS_FILE))) {
        mkdir(dirname(USERS_FILE), 0775, true);
    }

    if (!file_exists(USERS_FILE)) {
        $defaultUsers = [
            "superadmin" => [
                "password_hash" => password_hash("Cse135Winter", PASSWORD_DEFAULT),
                "role" => "superadmin"
            ],
            "analyst" => [
                "password_hash" => password_hash("Analyst123", PASSWORD_DEFAULT),
                "role" => "analyst"
            ],
            "viewer" => [
                "password_hash" => password_hash("Viewer123", PASSWORD_DEFAULT),
                "role" => "viewer"
            ]
        ];

        file_put_contents(USERS_FILE, json_encode($defaultUsers, JSON_PRETTY_PRINT));
    }
}

function get_users(): array {
    ensure_users_file();
    $json = file_get_contents(USERS_FILE);
    $users = json_decode($json, true);
    return is_array($users) ? $users : [];
}

function save_users(array $users): void {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}
