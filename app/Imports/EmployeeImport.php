<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use DateTime;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
class EmployeeImport implements ToCollection
{
    protected static $error_msg;
    protected static $skipped_rows=array();
    protected static $null_values_rows=0;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        if(self::validateSheet($rows)){
            if(count(self::$skipped_rows)===self::$null_values_rows){
                foreach ($rows as $row) 
                {
                   Employee::create([
                        'full_name' => $row[0],
                        'dob'=> $row[1],
                        'gender'=> $row[2],
                        'salary'=> $row[3],
                        'designation'=> $row[4],
                    ]);
                }
                return redirect()->back()->with('success','successfully imported to databases ')->with(compact('skipped_rows'));
            }
            else{
                return redirect()->back()->with('error','some columns have null values');
            }
        }
         else{
                return redirect()->back()->with('error',self::$error_msg);
            }

    }
    public static function validateSkipRows($rows){
        foreach($rows as $row){

            if(!isset($row[0]) && !isset($row[1]) && !isset($row[2]) && !isset($row[3]) && !isset($row[4]))
            {
                array_push(self::$skipped_rows,array_search($row,$rows));
            }
        }
    }
    public static function validateSheet($rows){
       if(!self::validateDob($rows)){
             self::$error_msg='DOB must have proper format:yyyy-mm-dd';
             return false;
        }elseif(!self::validateSalary($rows)){
             self::$error_msg='Salary must be float';
             return false;
        }else{
             return true;
        }
    }

    public static function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    public static function validateSalary($rows){
        $has_error=false;
        foreach ($rows as $row) {
            if(!is_float($row[3]))
               {
                   $has_error=true;
                   break;
                }
        }
        return !$has_error;
    }
    public static function validateNullValue($rows){
        foreach ($rows as $row) {
            if(!isset($row[0]) || !isset($row[1])|| !isset($row[2]) || !isset($row[3]) ||!isset($row[4]))
               {
                   self::$null_values_rows++;
                }
        }
    }
    public static function validateDob($rows){
        $has_error=false;
        foreach ($rows as $row) {
            if(!self::validateDate($row[1]))
               {
                   $has_error=true;
                   break;
                }
        }
        return !$has_error;
    }
   
}
