<?php

return [
    'IMAGE_PATH' => 'user/profile',
    'SLUG_LENGTH' => 12,
    "STATUS_PENDING" => 0,
    "STATUS_ACTIVE" => 1,
    "STATUS_INACTIVE" => 2,
    "STATUS_DELETE" => 3,
    "PENDING_ACTIVATION" => 4,
    "STATUS_DRAFT" => 5,
    "ATTEMPT_OTP" => 3,
    "ATTEMPT_TYPES" => [
        "LOGIN" => 1,
        'INVALID_LOGIN' => 2,
        "FORGOT_PASSWORD" => 3,
    ],
    "OTP_TYPES" => [
        'LOGIN' => 1,
        'FORGOT_PASSWORD' => 2,
    ],
    'TOKEN_TYPES' => [
        'NEW_USER' => 1,
        'FORGOT_TOKEN' => 2,
    ],
    'SENSITIVE' => [
        "user",
        "register",
    ],
    'SESITIVE_FIELDS' => ['password', 'card_no', 'card_cvv', 'password_confirmation'],
    'IS_EXPORT' => 1,
    'IS_VIEW_FILE' => 1,
    'MESSAGE_STATUS' => [
        'UNREAD' => 1,
        'READ' => 2,
        'DELETED' => 3,
    ],
    "IS_BLOCKED" => 1,
    "UNBLOCKED_AT_DATE_FORMAT" => "g:ia \o\\n l jS F Y",
    "DEMO_PDF_VIEW_NAME" => "pdf.demo-pdf-export",
];
