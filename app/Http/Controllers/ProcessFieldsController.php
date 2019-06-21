<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\ProcessField;
use App\Models\Field;
use Exception;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ProcessFieldsController extends BaseController {

    /**
     * Function to get all process requests
     * @return HTTP Response
     */
    public function getAllProcessFields() {
        try {
            $data = ProcessField::all();
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
    public function getProcessFieldById($id) {
        try {
            $process = ProcessField::where('id', $id)->first();
            if (!isset($process->id)) {
                throw new Exception('Process field request not found');
            }
            $response = ['status' => 'success', 'data' => $process];
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
    public function addProcessField(Request $request) {
        try {
            if ($request->auth->user_type == 'Cutomer') {
                $request->merge(array('user_id' => $request->auth->id));
            }
            $field = Field::where('id', $request->input('field_id'))->first();
            $validator = $this->validateAddProcess($request, $field);
            if ($validator->fails()) {
                return response()->json(['status' => 'failed',
                            'message' => 'Validation failed',
                            'errors' => $validator->messages()]);
            }
            
            //Authorize the requested field is owned by accessing user / is admin
            if ($field->user_id != $request->auth->id && $request->auth->user_type == 'Customer') {
                throw new Exception("Unauthorized to access this field");
            }

            $process = new ProcessField;
            $process->date = Carbon::createFromFormat('Y-m-d', $request->input("date"))->toDateString();
            $process->field_id = trim($request->input("field_id"));
            $process->tractor_id = trim($request->input("tractor_id"));
            $process->area = trim($request->input("area"));
            $process->save();
            $response = ['status' => 'success',
                'message' => 'Process field request added successfully',
                'message' => $process];
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
    public function editProcessField(Request $request) {
        try {

            if ($request->auth->user_type == 'Cutomer') {
                $request->merge(array('user_id' => $request->auth->id));
            }
            $field = Field::where('id', $request->input('field_id'))->first();

            $validator = $this->validateEditProcess($request, $field);

            if ($validator->fails()) {
                return response()->json(['status' => 'failed',
                            'message' => 'Validation failed',
                            'errors' => $validator->messages()]);
            }
            //Authorize the requested field is owned by accessing user / is admin
            if ($field->user_id != $request->auth->id && $request->auth->user_type == 'Customer') {
                throw new Exception("Unauthorized to access this field");
            }
            $process = ProcessField::where('id', $request->input("id"))->first();
            if (!isset($process->id)) {
                throw new Exception('ProcessField not found');
            }
            //Authorize the current field belongs to accessing user / is admin
            $field = Field::where('id', $process->field_id)->first();
            if ($field->user_id != $request->auth->id && $request->auth->user_type == 'Customer') {
                throw new Exception("Unauthorized to access this field");
            }
            $process->date = Carbon::createFromFormat('Y-m-d', $request->input("date"))->toDateString();
            $process->field_id = trim($request->input("field_id"));
            $process->tractor_id = trim($request->input("tractor_id"));
            $process->area = trim($request->input("area"));
            $process->save();

            $response = ['status' => 'success',
                'message' => 'Process field request updated successfully',
                'data' => $process];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

    public function changeProcessFieldStatus(Request $request) {
        try {

            $validator = Validator::make($request->all(), [
                        'id' => 'required|numeric|exists:process_fields,id',
                        'status' => 'required|in:Pending,Processed']);

            if ($validator->fails()) {
                return response()->json(['status' => 'failed',
                            'message' => 'Validation failed',
                            'errors' => $validator->messages()]);
            }
            $process = ProcessField::where('id', $request->input("id"))->first();
            if (!isset($process->id)) {
                throw new Exception('ProcessField not found');
            }
            $process->status = trim($request->input("status"));
            $process->save();

            $response = ['status' => 'success',
                'message' => 'Status updated successfully',
                'data' => $process];
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
    public function deleteProcessField($id, Request $request) {
        try {
            $process = ProcessField::where('id', $id)->first();
            if (!isset($process->id)) {
                throw new Exception('ProcessField not found');
            }
            $process->delete();
            $response = ['status' => 'success', 'message' => 'Process field request deleted successfully'];
        } catch (Exception $ex) {
            $response = ['status' => 'failed', 'message' => $ex->getMessage()];
        }
        return response()->json($response);
    }

    private function validateEditProcess($request, $field) {
        $maxAreaCondition = '';
        if (isset($field->id)) {
            $maxAreaCondition = '|max:' . $field->area;
        }
        return Validator::make($request->all(), [
                    'id' => 'required|numeric|exists:process_fields,id',
                    'tractor_id' => ['required', 'exists:tractors,id',
                        Rule::unique('process_fields')->where(function ($query)use ($request) {
                                    return $query->where('tractor_id', "=", $request->tractor_id)
                                                    ->where('date', "=", $request->input("date"))
                                                    ->where('id', '!=', $request->input("id"));
                                })],
                    'field_id' => 'required|exists:fields,id',
                    'date' => 'required|date_format:Y-m-d',
                    'area' => 'required|numeric' . $maxAreaCondition
                        ], ['tractor_id.unique' => 'Tractor is already booked for this date']);
    }

    private function validateAddProcess($request, $field) {
        $maxAreaCondition = '';
        if (isset($field->id)) {
            $maxAreaCondition = '|max:' . $field->area;
        }
        return Validator::make($request->all(), [
                    'tractor_id' => ['required', 'exists:tractors,id',
                        Rule::unique('process_fields')->where(function ($query)use ($request) {
                                    return $query->where('tractor_id', "=", $request->tractor_id)
                                                    ->where('date', "=", $request->input("date"));
                                })],
                    'field_id' => 'required|exists:fields,id',
                    'date' => 'required|date_format:Y-m-d',
                    'area' => 'required|numeric' . $maxAreaCondition
                        ], ['tractor_id.unique' => 'Tractor is already booked for this date']);
    }

}
