<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaterialType;


class MaterialTypesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $materialNameTypes = ['Rajuri 500','Rajuri 500D','Rajuri 550','Rajuri 500DPLUS','Rajuri 550D','Rajuri 600','Rajuri CRS','Binding Wire','Nails'];

            foreach($materialNameTypes as $materialNameType)
            {
                MaterialType::updateOrCreate(
                        ['mName' => $materialNameType],
                        ['mName' => $materialNameType]
                    );
            }

    }
}
