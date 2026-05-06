<?php

function usersFilePath()
{
    return __DIR__ . '/users.json';
}

function loadUsers()
{
    $filePath = usersFilePath();

    if (!file_exists($filePath)) {
        file_put_contents($filePath, '{}');
    }

    $content = file_get_contents($filePath);
    $users = json_decode($content, true);

    return is_array($users) ? $users : [];
}

function saveUsers($users)
{
    $filePath = usersFilePath();

    if (!is_dir(__DIR__)) {
        mkdir(__DIR__, 0777, true);
    }

    file_put_contents(
        $filePath,
        json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

function normalizeUserEmail($email)
{
    return mb_strtolower(trim((string) $email));
}

function getUserByEmail($email)
{
    $email = normalizeUserEmail($email);
    $users = loadUsers();

    return $users[$email] ?? null;
}

function saveUserData($email, $data)
{
    $email = normalizeUserEmail($email);
    $users = loadUsers();

    $currentUser = $users[$email] ?? [];

    $users[$email] = array_merge($currentUser, $data, [
        'email' => $email
    ]);

    saveUsers($users);

    return $users[$email];
}

function deleteUserByEmail($email)
{
    $email = normalizeUserEmail($email);
    $users = loadUsers();

    if (isset($users[$email])) {
        unset($users[$email]);
        saveUsers($users);
    }
}

function ensureDefaultAdminUser()
{
    $adminEmail = 'admin@admin.com';
    $admin = getUserByEmail($adminEmail);

    if ($admin) {
        return $admin;
    }

    return saveUserData($adminEmail, [
        'id' => 1,
        'name' => 'Администратор',
        'password_hash' => password_hash('123456', PASSWORD_DEFAULT),
        'phone' => '',
        'city' => '',
        'address' => '',
        'postcode' => ''
    ]);
}

function putUserIntoSession($user)
{
    $_SESSION['user_id'] = $user['id'] ?? 1;
    $_SESSION['user_name'] = $user['name'] ?? 'Пользователь';
    $_SESSION['user_email'] = $user['email'] ?? '';
    $_SESSION['profile_phone'] = $user['phone'] ?? '';
    $_SESSION['profile_city'] = $user['city'] ?? '';
    $_SESSION['profile_address'] = $user['address'] ?? '';
    $_SESSION['profile_postcode'] = $user['postcode'] ?? '';
}