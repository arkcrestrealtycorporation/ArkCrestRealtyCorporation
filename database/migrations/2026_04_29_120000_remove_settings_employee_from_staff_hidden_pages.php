<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // For all staff users, remove 'settings.employee' from hidden_pages
        // so that staff who have been granted visibility can edit/delete employee data
        $users = DB::table('users')->where('role', 'staff')->get();

        foreach ($users as $user) {
            $hidden = json_decode($user->hidden_pages ?? '[]', true) ?: [];
            if (in_array('settings.employee', $hidden)) {
                $updated = array_values(array_filter($hidden, fn($p) => $p !== 'settings.employee'));
                DB::table('users')->where('id', $user->id)->update([
                    'hidden_pages' => json_encode($updated),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Not reversible — visibility was already customized per user
    }
};
