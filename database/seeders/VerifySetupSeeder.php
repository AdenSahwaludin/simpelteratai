<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class VerifySetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "\n========== SETUP VERIFICATION ==========\n";

        // Check Mail Configuration
        echo "\n✓ Mail Configuration:\n";
        echo '  - MAIL_MAILER: '.config('mail.default')."\n";
        echo '  - MAIL_HOST: '.config('mail.mailers.smtp.host')."\n";
        echo '  - MAIL_PORT: '.config('mail.mailers.smtp.port')."\n";
        echo '  - MAIL_USERNAME: '.config('mail.mailers.smtp.username')."\n";
        echo '  - FROM_ADDRESS: '.config('mail.from.address')."\n";

        // Check Test Users
        echo "\n✓ Test Users Available:\n";
        $adminCount = Admin::count();
        echo "  - Admins: $adminCount users\n";

        if ($adminCount > 0) {
            Admin::select('email')->limit(2)->get()->each(function ($admin) {
                echo "    • {$admin->email}\n";
            });
        }

        echo "\n✓ Password Reset Flow:\n";
        echo "  1. Go to /forgot-password\n";
        echo "  2. Enter email address\n";
        echo "  3. Check Mailtrap.io inbox for reset email\n";
        echo "  4. Click link in email to reset password\n";
        echo "  5. Set new password and click RESET PASSWORD\n";
        echo "  6. Login with new password\n";

        echo "\n========== END VERIFICATION ==========\n\n";
    }
}
