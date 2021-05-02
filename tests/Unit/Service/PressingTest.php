<?php

namespace Tests\Unit\Service;

use App\Facades\Service\Pressing;
use Tests\TestCase;

class PressingTest extends TestCase
{
    /*
     * Methode checkProvider
     */
    /**
     * @test
     */
    public function getProviderNotExist()
    {
        $response = Pressing::checkProvider('test@pr.com', 'nono');

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function getProviderWithoutPassword()
    {
        $response = Pressing::checkProvider('ravier@gmail.com', '');

        $this->assertNotNull($response);
        $this->assertEquals('Ravier', $response['name']);
    }

    /**
     * @test
     */
    public function getProviderWithPassword()
    {
        $response = Pressing::checkProvider('test@fidensio.com', 'ok');

        $this->assertNotNull($response);
        $this->assertEquals('Fidensio', $response['name']);
    }

    /*
     * Methode changeStatus
     */
    /**
     * @test
     */
    public function changeOrderStatusWithWrongOrderId()
    {
        $response = Pressing::changeStatus(10, 1);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function changeOrderStatusWithWrongStatusId()
    {
        $response = Pressing::changeStatus(1, 50);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function changeOrderStatus()
    {
        $order = [
            'id' => 2,
            'userId' => 2,
            'companyId' => 1,
            'serviceId' => 4,
            'providerId' => 1,
            'status' => 1,
            'userComment' => 'Test PressingAtCompany',
            'amount' => '3027',
            'deliveryDate' => '2021-05-03 12:04:12',
            'createdAt' => '2021-04-30 12:04:12',
            'updatedAt' => '2021-04-30 12:04:12'
        ];

        $response = Pressing::changeStatus($order['id'], 3);

        $this->assertEquals($order['id'], $response['id']);
        $this->assertNotEquals($order['status'], $response['status']);
        $this->assertEquals(3, $response['status']);
    }
}
