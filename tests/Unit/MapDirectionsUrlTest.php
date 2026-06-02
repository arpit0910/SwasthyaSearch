<?php

namespace Tests\Unit;

use App\Models\BloodBank;
use App\Models\Hospital;
use Tests\TestCase;

class MapDirectionsUrlTest extends TestCase
{
    public function test_hospital_map_directions_use_coordinates_when_available(): void
    {
        $hospital = new Hospital([
            'name_en' => 'City Care Hospital',
            'address_line1' => '12 Main Road',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
            'latitude' => '26.91243400',
            'longitude' => '75.78727100',
        ]);

        $this->assertSame(
            'https://www.google.com/maps/dir/?api=1&destination=26.91243400%2C75.78727100',
            $hospital->map_directions_url
        );
    }

    public function test_hospital_map_directions_fall_back_to_full_structured_address(): void
    {
        $hospital = new Hospital([
            'name_en' => 'City Care Hospital',
            'address_line1' => '12 Main Road',
            'address_line2' => 'Near Metro Station',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302001',
        ]);

        $this->assertSame(
            'https://www.google.com/maps/dir/?api=1&destination=City+Care+Hospital%2C+12+Main+Road%2C+Near+Metro+Station%2C+Jaipur%2C+Rajasthan%2C+302001',
            $hospital->map_directions_url
        );
    }

    public function test_blood_bank_map_directions_fall_back_to_name_and_address(): void
    {
        $bank = new BloodBank([
            'name_en' => 'LifeLine Blood Bank',
            'address_en' => '45 Civil Lines',
            'city' => 'Jaipur',
            'state' => 'Rajasthan',
            'pincode' => '302006',
        ]);

        $this->assertSame(
            'https://www.google.com/maps/dir/?api=1&destination=LifeLine+Blood+Bank%2C+45+Civil+Lines%2C+Jaipur%2C+Rajasthan%2C+302006',
            $bank->map_directions_url
        );
    }
}
