<?php

namespace App\Http\Controllers;

use App\Models\Civilian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class CiviliansController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'NID' => 'required',
            'mobile' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        DB::beginTransaction();
        $civilian = new Civilian();
        $civilian->mobile = $request->mobile;
        $civilian->NID = $request->NID;
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
        $civilian->img = "/img/" . $file_name;

        if ($profile_picture != null) {
            file_put_contents("img/" . $file_name, $fileBin);
        }

        // $organization->img=$request->img;

        $civilian->save();
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'userable_id' => $civilian->id,
            'userable_type' => 'App\Models\Civilian'
        ]);

        DB::commit();
        if ($profile_picture != null) {
            file_put_contents("img/" . $file_name, $fileBin);
        }
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }
}
