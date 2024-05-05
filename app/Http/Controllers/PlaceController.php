<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PlaceResource;
use App\Models\Place;

use function Laravel\Prompts\error;

class PlaceController extends Controller
{
    /**
     * Get all places that contains q query parameter
     */
    public function search(Request $request)
    {
        $q = $request->query("q", '');
        $len = iconv_strlen($q);
        $result = [];
        $query = DB::table('places')->rightJoin('municipalities','places.municipality_id', '=','municipalities.municipalityid')->
            rightJoin('countries','municipalities.country_id', '=','countries.countryid')->
            select([
                'placeid',
                'place',
                'placepostnum',
                'municipality',
                'country'
            ]);
        if ($len > 2) {
            $result = $query->where('place','LIKE',"%$q%")->orderByRaw('IF(place = municipalities.municipality,1,0) DESC, place ASC, municipality ASC')->get();
        } else if ($len == 2) {
            $result = /*Place::with('municipality')*/$query->where('place',$q)->get();
        }
    return response(/*PlaceResource::collection($result)*/$result);
    }

    /**
     * Show place for primary key
     */
    public function show(Place $place) {
        return response(new PlaceResource($place)); // just one and therefore new instead of collection
    }
    /**
     * OpenAI Image Creation test function
     */
    public function aitestOpenAI(Request $request) {

        $prompt = $request->get('prompt', 'A cute baby sea otter');
        $size = $request->get('size', '1024x1024');
        $n = $request->get('n', 2);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.openai.com/v1/images/generations",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'prompt' => $prompt,
                'n' => $n,
                'size' => $size
            ]),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer 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",
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response("cURL Error #:" . $err, 404);
        } else {
            return response($response);
        }
    }

    /**
     * RapidAPI OpenAI Image Creation test function
     */
    public function aitest(Request $request) {

        $prompt = $request->get('prompt', 'A cute baby sea otter');
        $size = $request->get('size', '1024x1024');
        $n = $request->get('n', 2);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://openai80.p.rapidapi.com/images/generations",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'prompt' => $prompt,
                'n' => $n,
                'size' => $size
            ]),
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: openai80.p.rapidapi.com",
                "X-RapidAPI-Key: 42a9fe9fa2msh8eaebbf9ccf1c57p13e6c1jsn588ebcc3a584",
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response("cURL Error #:" . $err, 404);
        } else {
            return response($response);
        }
    }
}
