<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Field;
use Exception;

class FieldsController extends BaseController {

    /**
     * Function to get all fields
     * @return HTTP Response
     */
    public function getAllFields() {
        try {
            $data = Field::all();
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
    public function getFieldById($id) {
        try {
            $field = Field::where('id', $id)->first();
            if (!isset($field->id)) {
                throw new Exception('Field not found');
            }
            $response = ['status' => 'success', 'data' => $field];
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
    public function addField(Request $request) {
        try {
            if ($request->auth->user_type == 'Customer') {
                $request->merge(array('user_id' => $request->auth->id));
            }
            $validator = Validator::make($request->all(), [
                        'name' => 'required|string|max:255|unique:fields',
                        'user_id' => 'required|exists:users,id',
                        'crop_id' => 'required|exists:crops,id',
                        'area' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'failed',
                            'message' => 'Validation failed',
                            'errors' => $validator->messages()]);
            }
            $field = new Field;
            $field->name = trim($request->input("name"));
            $field->user_id = trim($request->input("user_id"));
            $field->crop_id = trim($request->input("crop_id"));
            $field->area = trim($request->input("area"));
            $field->save();
            $response = ['status' => 'success', 'message' => $field];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

    /**
     * Function to edit crop
     * @param Request $request
     * @return HTTP Response
     */
    public function editField(Request $request) {
        try {
            if ($request->auth->user_type == 'Customer') {
                $request->merge(array('user_id' => $request->auth->id));
            }
            $validator = Validator::make($request->all(), [
                        'id' => 'required|numeric|exists:fields,id',
                        'name' => 'required|string|max:255|unique:fields,name,' . $request->input('id'),
                        'user_id' => 'required|exists:users,id',
                        'crop_id' => 'required|exists:crops,id',
                        'area' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 'failed',
                            'message' => 'Validation failed',
                            'errors' => $validator->messages()]);
            }
            $field = Field::where('id', $request->input("id"))->first();
            //Authorize the requested field is owned by accessing user / is admin user
            if ($field->user_id != $request->auth->id && $request->auth->user_type == 'Customer') {
                throw new Exception("Unauthorized to access this field");
            }
            $field->name = trim($request->input("name"));
            $field->crop_id = trim($request->input("crop_id"));
            $field->user_id = trim($request->input("user_id"));
            $field->area = trim($request->input("area"));
            $field->save();
            $response = ['status' => 'success', 'message' => 'Field updated successfully', 'data' => $field];
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
    public function deleteField($id, Request $request) {
        try {
            $field = Field::where('id', $id)->first();
            if (!isset($field->id)) {
                throw new Exception('Field not found');
            }
            if ($field->user_id != $request->auth->id && $request->auth->user_type == 'Customer') {
                throw new Exception("Unauthorized to access this field");
            }
            $field->delete();
            $response = ['status' => 'success', 'message' => 'Field deleted successfully'];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

}
