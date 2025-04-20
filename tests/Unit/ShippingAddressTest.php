<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use App\Models\ShippingAddress;
use Tests\TestCase;


class ShippingAddressTest extends TestCase
{
    
    /**
     * test crear direccion
     * @return void
     */
    public function test_creating_shipping_address()
    {
        $shippingAddressData = [
            'address' => 'calle falsa, 123, test',
            'cp' => 12345,
            'city' => 'falseCity',
            'country' => 'falseCountry',
            'role_address' => 1,        // rol 1 --> centro de distribucion
            'notes' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit.',
            'contact_name' => 'Anonimo Jaja',
            'contact_phone' => 123456789,
            'contact_email' => 'anonimo_jaja@example.com',
        ];

        $shippingAddress = ShippingAddress::create($shippingAddressData);

        // verificacion de creacion OK
        $this->assertDatabaseHas('shipping_address', [
            'address' => 'calle falsa, 123, test',
            'cp' => 12345,
            'city' => 'falseCity',
            'country' => 'falseCountry',
            'role_address' => 1,        // rol 1 --> centro de distribucion
            'notes' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit.',
            'contact_name' => 'Anonimo Jaja',
            'contact_phone' => 123456789,
            'contact_email' => 'anonimo_jaja@example.com',
        ]);

        // verificacion de datos
        $this->assertEquals($shippingAddress->address, 'calle falsa, 123, test');
        $this->assertEquals($shippingAddress->contact_name, 'Anonimo Jaja');
    }

    /**
     * Test para validar los datos antes de guardar.
     *
     * @return void
     */
    public function test_validation_shipping_address()
    {
        // Simula la creación de una dirección de envío sin datos obligatorios.
        $shippingAddressData = [
            'address' => '',
            'cp' => '',
            'city' => 'falseCity',
            'country' => 'falseCountry',
            'role_address' => 1,
            'notes' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit.',
            'contact_name' => 'Anonimo Jaja',
            'contact_phone' => '123456789',
            'contact_email' => 'anonimo_jaja@example.com',
        ];

        $shippingAddress = new ShippingAddress($shippingAddressData);
        $this->assertFalse($shippingAddress->save());       // falla por address y cp no añadido y role_address no esta incluido
    }

    public function test_deleting_shipping_address()
{
    // Crea una nueva dirección de envío.
    $shippingAddressData = [
        'address' => 'calle falsa, 123, test2',
        'cp' => 12345,
        'city' => 'falseCity',
        'country' => 'falseCountry',
        'role_address' => 1,        // rol 1 --> centro de distribucion
        'notes' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit.',
        'contact_name' => 'Anonimo Jaja',
        'contact_phone' => 123456789,
        'contact_email' => 'anonimo_jaja@example.com',
    ];

    $shippingAddress = ShippingAddress::create($shippingAddressData);

    // check si existe
    $this->assertDatabaseHas('shipping_address', [
        'address' => 'calle falsa, 123, test2',
        'cp' => 12345,
        'city' => 'falseCity',
        'country' => 'falseCountry',
        'role_address' => 1,
        'notes' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit.',
        'contact_name' => 'Anonimo Jaja',
        'contact_phone' => 123456789,
        'contact_email' => 'anonimo_jaja@example.com',
    ]);

    $shippingAddress->delete();

    // check si fue eliminada OK
    $this->assertDatabaseMissing('shipping_address', [
        'address' => 'calle falsa, 123, test2',
        'cp' => 12345,
        'city' => 'falseCity',
        'country' => 'falseCountry',
        'role_address' => 1,
        'notes' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit.',
        'contact_name' => 'Anonimo Jaja',
        'contact_phone' => 123456789,
        'contact_email' => 'anonimo_jaja@example.com',
    ]);
}
}
