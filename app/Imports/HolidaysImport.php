<?php

namespace App\Imports;

use App\Models\HolidayList;
use Maatwebsite\Excel\Concerns\ToArray;

class HolidaysImport implements ToArray
{
    /**
    * @param array $row
    *
    */
    public function array(array $rows)
    {
        foreach(array_slice($rows,1)  as $rowInfo)
        {
            if(is_numeric($rowInfo[0])) // date
            {
                    $holiday = HolidayList::where('hDate',\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowInfo[0])->format('Y-m-d'))->first();
                    if(empty($holiday))
                    {
                        $holiday =  new HolidayList();
                    }
                    $holiday->hDate   = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rowInfo[0])->format('Y-m-d');
                    $holiday->title   = $rowInfo[1];
                    $holiday->save();
            }
        }
    }
}
