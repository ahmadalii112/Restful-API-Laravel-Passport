<?php

namespace App\Http\Controllers\Country;

use App\Http\Controllers\Controller;
use App\Models\Country\CountryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    public function country()
    {
        $country = CountryModel::all();
        return response()->json($country,'200'); // 200 is status
    }

    // Todo: Show By ID
    public function showCountryByID($id)
    {
        $country=CountryModel::find($id);
        if (is_null($country)){
            return response()->json(["message"=>"No Record Found"],404);
        }
        return response()->json($country,200); // 200 is status
    }

    // Todo: Store Data
    public function storeCountryRecord(Request $request)
    {
        $rules = [
            'name'=>'required|min:3',
            'iso'=>'required|min:2|max:2',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()){
            return response()->json($validator->errors(),400); # 400 (Bad Request)
        }

        $country = CountryModel::create($request->all());
        return response()->json($country,201); // 201 for Created
    }

    // Todo: Update Data
    public function updateCountryRecord(Request $request, $id)
    {
        $country=CountryModel::find($id);
        if (is_null($country)){
            return response()->json("No Record Found",404);
        }
        $country->update($request->all());
        return response()->json($country,200); // 200 (OK)
    }
    // Todo: Delete Data
    public function deleteCountryRecord($id)
    {

        $country=CountryModel::find($id);
        if (is_null($country)){
            return response()->json("No Record Found",404);
        }
        $country->delete();
        return response()->json(null,204); // 204 (NO Content)
    }

}
