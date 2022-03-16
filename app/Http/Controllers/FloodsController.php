<?php

namespace App\Http\Controllers;

use App\Http\Resources\FloodsResource;
use App\Models\Flood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class FloodsController extends BaseController
{
    public function getAllActive(Request $request)
    {
        $result = FloodsResource::collection(Flood::where('is_active', 1)->get());
        return $this->sendResponse($result, 'Active floods.');
    }
    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->userable_type == 'App\\Models\\Admin') {
            $validator = Validator::make($request->all(), [
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $flood = new Flood();
            $flood->lat = $request->lat;
            $flood->lng = $request->lng;
            $flood->added_by = $user->userable_id;
            $flood->save();
            return $this->sendResponse($flood, "Flood added successfully.");
        }
        return $this->sendError("UnAuthorized acceess", ['User should be admin']);
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        if ($user->userable_type == 'App\\Models\\Admin') {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'is_active' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $flood = Flood::find($request->id);
            $flood->is_active = $request->is_active;
            $flood->save();
            return $this->sendResponse($flood, "Flood updated successfully.");
        }
        return $this->sendError("UnAuthorized acceess", ['User should be admin']);
    }
}
