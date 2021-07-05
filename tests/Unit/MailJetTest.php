<?php

namespace Tests\Unit;

use App\Facades\Mailjet;
use Tests\TestCase;

class MailJetTest extends TestCase
{
    private string $templateTestId = 'payment_refused';

    /*
     * Methode SendWithTemplate
     */
    /**
     * @test
     */
    public function sendEmailWithWrongEmail()
    {
        $contact = [
            'email' => $this->faker->firstName,
            'name' => $this->faker->firstName  . ' ' . $this->faker->lastName
        ];
        $variable = [
            'firstName' => $contact['email'],
            'email' => $contact['email'],
            'fakeLink' => $this->faker->url
        ];

        $response = MailJet::sendWithTemplate($contact, $this->templateTestId, 'random', $variable);

        $this->assertFalse($response);
    }

    /**
     * @test
     */
    public function sendEmail()
    {
        $contact = [
            'email' => $this->faker->email,
            'name' => $this->faker->firstName  . ' ' . $this->faker->lastName
        ];
        $variable = [
            'firstName' => $contact['email'],
            'email' => $contact['email'],
            'fakeLink' => $this->faker->url
        ];

        $response = MailJet::sendWithTemplate($contact, $this->templateTestId, 'random', $variable);

        $this->assertTrue($response);
    }
}
