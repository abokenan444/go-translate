<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class SmtpSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'is_active',
        'is_default',
        'last_tested_at',
        'test_passed',
        'test_error',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'test_passed' => 'boolean',
        'last_tested_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function setAsDefault()
    {
        // Remove default from all other settings
        static::where('id', '!=', $this->id)->update(['is_default' => false]);
        
        $this->update(['is_default' => true]);
    }

    public function testConnection()
    {
        try {
            $originalConfig = Config::get('mail');
            
            // Log the actual password being used
            \Log::info('SMTP Test Password Length: ' . strlen($this->password));
            \Log::info('SMTP Test Password First 5 chars: ' . substr($this->password, 0, 5));
            
            Config::set('mail.mailers.test_smtp', [
                'transport' => 'smtp',
                'host' => $this->host,
                'port' => $this->port,
                'encryption' => $this->encryption,
                'username' => $this->username,
                'password' => $this->password,
                'timeout' => 10,
            ]);
            
            Config::set('mail.default', 'test_smtp');
            
            $mailer = app('mail.manager')->mailer('test_smtp');
            $transport = $mailer->getSymfonyTransport();
            
            $transport->start();
            $transport->stop();
            
            Config::set('mail', $originalConfig);

            $this->update([
                'test_passed' => true,
                'test_error' => null,
                'last_tested_at' => now(),
            ]);

            return true;
        } catch (\Exception $e) {
            if (isset($originalConfig)) {
                Config::set('mail', $originalConfig);
            }
            
            $this->update([
                'test_passed' => false,
                'test_error' => $e->getMessage(),
                'last_tested_at' => now(),
            ]);

            return false;
        }
    }
}
