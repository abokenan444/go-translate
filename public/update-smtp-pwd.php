<?php
require __DIR__ . '/../vendor/autoload.php';
\ = require_once __DIR__ . '/../bootstrap/app.php';
\ = \->make(Illuminate\Contracts\Console\Kernel::class);
\->bootstrap();

\ = App\Models\SmtpSetting::find(1);
if (\) {
    \->password = 'Shatha-1992';
    \->save();
    echo 'Password updated successfully for: ' . \->username;
} else {
    echo 'SMTP setting not found';
}
