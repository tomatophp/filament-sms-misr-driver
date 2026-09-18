<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sms-misr.sms_misr_username', '');
        $this->migrator->add('sms-misr.sms_misr_password', '');
        $this->migrator->add('sms-misr.sms_misr_sender', '');
        $this->migrator->add('sms-misr.sms_misr_environment', 'live');
        $this->migrator->add('sms-misr.sms_misr_active', true);
    }
};
