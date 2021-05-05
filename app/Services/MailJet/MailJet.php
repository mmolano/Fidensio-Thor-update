<?php

namespace App\Services\MailJet;

use Mailjet\Client;
use Mailjet\Resources;

class MailJet
{
    private Client $client;
    private array $resources;
    private array $templateId = [
        'payment_refused' => 2352392,
        'payment_3DSecure' => 2352557,
        'payment_confirmed' => 2755436,
        'order_completed_bringMe' => 2352530,
        'order_completed_oldLockers' => 1029154,
    ];

    public function __construct()
    {
        $this->client = new Client(env('MAILJET_KEY_PUBLIC'), env('MAILJET_KEY_SECRET'), true, [
            'version' => env('MAILJET_VERSION')
        ]);
        $this->resources = Resources::$Email;
    }

    public function sendWithTemplate(array $contact, string $templateId, string $subject, array $variables = null): bool
    {
        if (empty($contact['email']) || empty($contact['name'])) {
            return false;
        }

        $email = $this->client->post($this->resources, [
            'body' => [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => env('MAILJET_EMAIL_SENDER'),
                            'Name' => env('MAILJET_NAME_SENDER')
                        ],
                        'To' => [
                            [
                                'Email' => $contact['email'],
                                'Name' => $contact['name']
                            ]
                        ],
                        'Bcc' => [
                            [
                                'Email' => env('MAILJET_EMAIL_ARCHIVES'),
                                'Name' => "Archives"
                            ]
                        ],
                        'Subject' => $subject,
                        'TemplateID' => $this->templateId[$templateId],
                        'TemplateLanguage' => true,
                        'Variables' => $variables
                    ]
                ],
                'SandboxMode' => env('MAILJET_SANDBOX')
            ]
        ]);

        return $email->success();
    }
}
