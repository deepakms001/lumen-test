<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Crop;
use Exception;

class CropsController extends BaseController {

    /**
     * Function to get all crops
     * @return HTTP Response
     */
    public function getAllCrops() {
        try {
            $data = Crop::all();
            $response = ['status' => 'success', 'data' => $data];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

    /**
     * Function to get crop by id
     * @param integer $id
     * @return HTTP Response
     */
    public function getCropById($id) {
        try {
            $crop = Crop::where('id', $id)->first();
            if (!isset($crop->id)) {
                throw new Exception('Crop not found');
            }
            $response = ['status' => 'success', 'data' => $crop];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

    /**
     * Function to add crop
     * @param Request $request
     * @return HTTP Response
     */
    public function addCrop(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                        'name' => 'required|string|max:255|unique:crops',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'failed',
                            'message' => 'Validation failed',
                            'errors' => $validator->messages()]);
            }
            $crop = new Crop;
            $crop->name = trim($request->input("name"));
            $crop->save();
            $response = ['status' => 'success', 'message' => $crop];
        }  catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

    /**
     * Function to edit crop
     * @param Request $request
     * @return HTTP Response
     */
    public function editCrop(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                        'id' => 'required|numeric',
                        'name' => 'required|string|max:255|unique:crops,name,' . $request->input('id'),
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'failed',
                            'message' => 'Validation failed',
                            'errors' => $validator->messages()]);
            }
            $crop = Crop::where('id', $request->input("id"))->first();
            if (!isset($crop->id)) {
                throw new Exception('Crop not found');
            }
            $crop->name = trim($request->input("name"));
            $crop->save();
            $response = ['status' => 'success', 'message' => 'Crop updated successfully', 'data' => $crop];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

    /**
     * Function to delete crop
     * @param integer $id
     * @return HTTP Response
     */
    public function deleteCrop($id) {
        try {
            $crop = Crop::where('id', $id)->first();
            if (!isset($crop->id)) {
                throw new Exception('Crop not found');
            }
            $crop->delete();
            $response = ['status' => 'success', 'message' => 'Crop deleted successfully'];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

}
