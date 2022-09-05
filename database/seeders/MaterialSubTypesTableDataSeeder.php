<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\materialSubType;
use App\Models\MaterialType;

class MaterialSubTypesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $materialTypes = MaterialType::get();

       foreach($materialTypes as $materialTypes)
       {
            $twoSubTypes = ['Bend','Straight'];
            $mtValuesNames = ['6mm','8mm','12mn','16mm','20mm','25mm','28mm','32mm','40mm'];

            foreach($twoSubTypes as $key=>$twoSubType)
            {
                foreach($mtValuesNames as $key1=>$mtValuesName)
                {
                    materialSubType::updateOrCreate(
                        ['materialTypeId' => $materialTypes->id,'msType'=>$twoSubType,'msName' => $mtValuesName],
                        ['materialTypeId' => $materialTypes->id,'msType'=>$twoSubType,'msName' => $mtValuesName,'orderByKey'=>$key1]
                    );
                }
            }
       }

    //    dd($materialType->toArray());
    }
}
