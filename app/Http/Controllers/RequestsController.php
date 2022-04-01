<?php

namespace App\Http\Controllers;

use App\Http\Resources\RequestResource;
use App\Models\Flood;
use App\Models\Flood_Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class RequestsController extends BaseController
{
    public function gettAllPending(Request $request)
    {
        $result = RequestResource::collection(Flood_Request::where("is_approved", 0)->get());
        return $this->sendResponse($result, 'Pending requests.');
    }
    public function gettAll(Request $request)
    {
        $result = RequestResource::collection(Flood_Request::all());
        return $this->sendResponse($result, 'All requests.');
    }
    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->userable_type == 'App\\Models\\Civilian') {
            $validator = Validator::make($request->all(), [
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $flood_request = new Flood_Request();
            $flood_request->lat = $request->lat;
            $flood_request->lng = $request->lng;
            $flood_request->is_approved = 0;
            $flood_request->approved_by = 0;
            $flood_request->civilian_id = $user->userable_id;
            $profile_picture = $request->img;
            $file_name = "";
            if ($profile_picture == null) {
                $file_name = "default.png";
            } else {
                $generate_name = uniqid() . "_" . time() . date("Ymd") . "_IMG";
                $base64Image = $profile_picture;
                $fileBin = file_get_contents($base64Image);
                $mimtype = mime_content_type($base64Image);
                if ($mimtype == "image/png") {
                    $file_name = $generate_name . ".png";
                } else if ($mimtype == "image/jpeg") {
                    $file_name = $generate_name . ".jpeg";
                } else if ($mimtype == "image/jpg") {
                    $file_name = $generate_name . ".jpg";
                } else {

                    return $this->sendError('Validation Error.', ["Profile image must be image file (png,jpeg,jpg)"]);
                }
            }
            $flood_request->img = "/img/" . $file_name;

            if ($profile_picture != null) {
                file_put_contents("img/" . $file_name, $fileBin);
            }
            $flood_request->save();
            return $this->sendResponse($flood_request, "Flood request added successfully.");
        }
        return $this->sendError("UnAuthorized acceess", ['User should be civilian']);
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        if ($user->userable_type == 'App\\Models\\Admin') {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'is_approved' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            $flood_request = Flood_Request::find($request->id);
            $flood_request->is_approved = $request->is_approved;
            $flood_request->approved_by = $user->userable_id;
            $flood_request->save();
            if ($request->is_approved == 1) {
                $flood = new Flood();
                $flood->lat = $flood_request->lat;
                $flood->lng = $flood_request->lng;
                $flood->added_by = $user->userable_id;
                $flood->is_active = 1;
                $flood->save();
            }
            return $this->sendResponse($flood_request, "Flood request updated successfully.");
        }
        return $this->sendError("UnAuthorized acceess", ['User should be admin']);
    }
}
