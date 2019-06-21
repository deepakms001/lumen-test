<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Tractor;
use Exception;

class TractorsController extends BaseController {

    /**
     * Function to get all tractor
     * @return HTTP Response
     */
    public function getAllTractors() {
        try {
            $data = Tractor::all();
            $response = ['status' => 'success', 'data' => $data];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

    /**
     * Function to get tractor by id
     * @param integer $id
     * @return HTTP Response
     */
    public function getTractorById($id) {
        try {
            $tractor = Tractor::where('id', $id)->first();
            if (!isset($tractor->id)) {
                throw new Exception('Tractor not found');
            }
            $response = ['status' => 'success', 'data' => $tractor];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

    /**
     * Function to add tractor
     * @param Request $request
     * @return HTTP Response
     */
    public function addTractor(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                        'name' => 'required|string|max:255|unique:tractors',
                        'reg_number' => 'required|string|max:255|unique:tractors'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'failed',
                            'message' => 'Validation failed',
                            'errors' => $validator->messages()]);
            }
            $tractor = new Tractor;
            $tractor->name = trim($request->input("name"));
            $tractor->reg_number = trim($request->input("reg_number"));
            $tractor->save();
            $response = ['status' => 'success', 'message' => $tractor];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

    /**
     * Function to edit tractor
     * @param Request $request
     * @return HTTP Response
     */
    public function editTractor(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                        'id' => 'required|numeric|exists:tractors,id',
                        'name' => 'required|string|max:255|unique:tractors,name,' . $request->input('id'),
                        'reg_number' => 'required|string|max:255|unique:tractors,reg_number,' . $request->input('id'),
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'failed',
                            'message' => 'Validation failed',
                            'errors' => $validator->messages()]);
            }
            $tractor = Tractor::where('id', $request->input("id"))->first();
            $tractor->name = trim($request->input("name"));
            $tractor->reg_number = trim($request->input("reg_number"));
            $tractor->save();
            $response = ['status' => 'success', 'message' => 'Tractor updated successfully', 'data' => $tractor];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

    /**
     * Function to delete tractor
     * @param integer $id
     * @return HTTP Response
     */
    public function deleteTractor($id) {
        try {
            $tractor = Tractor::where('id', $id)->first();
            if (!isset($tractor->id)) {
                throw new Exception('Tractor not found');
            }
            $tractor->delete();
            $response = ['status' => 'success', 'message' => 'Tractor deleted successfully'];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

}
