<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\Customer;
use App\CustomerCompanyInfo;

class MainController extends Controller
{

    public function index()
    {
        $courses = Course::all();
        return view('calculator', compact('courses'));
    }

    /**
    * Calculate customer's price of courses.
    */
    public function store()
    {
        dd(request()->all());
        $attributes =request()->validate(
            [
                'name' => 'required|min:3',
                'surname' => 'required|min:3',
                'email' => 'required',
                'phone' => 'required|numeric',
                'city' => 'required|min:3',

            ]
        );

        if(request('status') !== 'f'){
            $attributes['status'] = 'pravno';

            $companyAttributes =request()->validate(
                [   
                    'company_id' => 'required|numberic',
                    'company_address' => 'required',
                    'assignee' => 'required'
                ]
            );
        } else {
            $attributes['status'] = 'fizicko';
        }

        dd($attributes);
        dd($companyAttributes);

        Customer::create($attributes);
        CustomerCompanyInfo::create($companyAttributes);
       
        // // course prices
        // $idKurseva = [$kurs1, $kurs2];
        // $cijenaKurseva = 0;
        // foreach($idKurseva as $id){
        //     $kurs = Course::where('id', $id)->get();
        //     $cijena_kursa = $kurs->pluck('price');
        //     //$cijenaKurseva += $cijena_kursa[0];
        //     $cijenaKurseva = [$cijena_kursa[0]];
        // }

        return view('/');

    }

}
