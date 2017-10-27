<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Category;

class GeneralController extends Controller
{
    public function getCountries()
    {
        $countries = DB::table('countries')
            ->select('id','name')
            ->get();

        return response()->json($countries);
    }

    public function getStates($country_id)
    {
        $states = DB::table('states')
            ->where('country_id', $country_id)
            ->select('id','name','country_id')
            ->get();

        return response()->json($states);
    }

    public function checkUsername($username)
    {
        if(User::where('username', $username)->exists()){
            return response()->json([
                'exists' => true
            ]);
        }

        return response()->json([
            'exists'=>false
        ]);
    }

    public function checkEmail($email)
    {
        if(User::where('email', $email)->exists()){
            return response()->json([
                'exists' => true
            ]);
        }

        return response()->json([
            'exists'=>false
        ]);
    }

    public function getCategories()
    {
        $categories = Category::select('id', 'title')
            ->get();
        return response()->json([
            'categories' => $categories
        ]);
    }
}
