<?php

namespace MillionsSaving\Http\Controllers\Charts;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MillionsSaving\Http\Controllers\Controller;

class ChartsController extends Controller
{


 public function chartSavingsData(Request $request)
 {

      if($request->has('getSavingsData')){
           try{
                $sqlQuery = DB::table('mainaccount_stats')
                 ->orderBy('month','asc')
                ->where('year', date('Y'))
                ->get();

                $data = array();
                foreach ($sqlQuery as $row) {
                     $data[] = $row;
               }

               echo json_encode($data);
         }catch(Exception $ex){
          echo $ex;
    }
  }

}


}
