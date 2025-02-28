<?php

// override core en language system validation or define your own en language validation message
return [
    'userEmail' => [
        'required'     => 'The "Email" field is required.',
        'valid_email'  => 'Please enter a valid email address.',
        'is_unique'    => 'This email is already registered.'
    ],
    'userPassword' => [
        'required'     => 'The "Password" field is required.',
        'min_length'   => 'The "Password" must be at least 8 characters long.',
        'max_length'   => 'The "Password" cannot exceed 50 characters.',
        'regex_match'  => 'The "Password" must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
    ],
    'userRole' => [
        'string'       => 'The "Role" field must be a string.',
    ],
    'userName' => [
        'required'     => 'The "Username" field is required.',
        'string'       => 'The "Username" must be a string.',
        'min_length'   => 'The "Username" must be at least 3 characters long.',
        'max_length'   => 'The "Username" cannot exceed 50 characters.',
        'is_unique'    => 'This username is already taken. Please choose another one.',
    ],
    'firstName' => [
        'string'       => 'The "First Name" must be a string.',
        'max_length'   => 'The "First Name" cannot exceed 50 characters.',
    ],
    'lastName' => [
        'string'       => 'The "Last Name" must be a string.',
        'max_length'   => 'The "Last Name" cannot exceed 50 characters.',
    ],
    'description' => [
        'string'       => 'The "Description" must be a string.',
    ],
    'userId' => [
        'required'      => 'The "User ID" field is required.',
        'integer'       => 'The "User ID" must be a number.',
        'is_not_unique' => 'The specified user was not found in the database.',
    ],
];
