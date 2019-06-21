<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Http\Request;
use App\Models\ProcessField;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    public function generateReport(Request $request) {
        $response = [];
        try {
            $qry = DB::table('process_fields')->leftjoin('fields', 'process_fields.field_id', '=', 'fields.id')
                    ->leftjoin('crops', 'fields.crop_id', '=', 'crops.id')
                    ->leftjoin('tractors', 'process_fields.tractor_id', '=', 'tractors.id')
                    ->select('fields.name as field_name', 'crops.name as culture'
                    , 'process_fields.date as date', 'process_fields.area as processed_area'
                    , 'tractors.name as tractor_name');
            if ($request->input('field_name')) {
                $qry->where('fields.name', 'LIKE', '%' . $request->input('field_name') . '%');
            }
            if ($request->input('culture')) {
                $qry->where('crops.name', 'LIKE', '%' . $request->input('culture') . '%');
            }
            if ($request->input('start_date')) {
                $qry->where('process_fields.date', '>', $request->input('start_date'));
            }
            if ($request->input('end_date')) {
                $qry->where('process_fields.date', '<', $request->input('end_date'));
            }
            $data = $qry->get()->toArray();
            $sum = array_sum(array_column($data, 'processed_area'));
            $response = ['status' => 'success','data'=> $data, 'total_area_processed'=>$sum];
        } catch (Exception $e) {
            $response = ['status' => 'success','message'=>$e->getMessage()];
        }
        return response()->json($response);
    }

}
