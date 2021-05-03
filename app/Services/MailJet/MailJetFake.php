<?php

namespace App\Services\MailJet;

class MailJetFake
{
    public function sendWithTemplate(array $contact, int $templateId, array $variables = null): bool
    {
        if (empty($contact['email']) || empty($contact['name'])) {
            return false;
        } elseif (!strpos($contact['email'], '@')) {
            return false;
        }

        return true;
    }

    public function sendSms(string $mobile, string $message): bool
    {
        return true;
    }
}
