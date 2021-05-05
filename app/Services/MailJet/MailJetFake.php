<?php

namespace App\Services\MailJet;

class MailJetFake
{
    public function sendWithTemplate(array $contact, string $templateId, string $subject, array $variables = null): bool
    {
        if (empty($contact['email']) || empty($contact['name'])) {
            return false;
        } elseif (!strpos($contact['email'], '@')) {
            return false;
        }

        return true;
    }
}
