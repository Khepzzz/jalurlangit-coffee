<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pelanggan;
use Carbon\Carbon;

class UpdateExpiredTokens extends Command
{
    // Nama dan signature command
    protected $signature = 'tokens:update-expired';

    // Deskripsi command
    protected $description = 'Update expired tokens status to expired';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Cari semua token yang sudah expired
        $expiredTokens = Pelanggan::where('expired_at', '<', Carbon::now())
                                  ->where('status', '!=', 'expired')  // Pastikan hanya update yang statusnya belum expired
                                  ->get();

        // Jika ada token yang expired, update status mereka
        if ($expiredTokens->isNotEmpty()) {
            foreach ($expiredTokens as $token) {
                $token->update(['status' => 'expired']);
                $this->info("Token {$token->token} telah kedaluwarsa dan statusnya diperbarui.");
            }
        } else {
            // Jika tidak ada token yang kadaluarsa
            $this->info('Tidak ada token yang kadaluarsa saat ini.');
        }
    }
}
