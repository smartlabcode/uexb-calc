<?php

namespace App\Http\Controllers;

use PDF;
use App;

use App\Customer;
use App\CustomersCourses;
use App\CustomersCompanyInfo;
use App\Helpers\DataExtractor;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class CustomerController extends Controller
{
    public $dataExtractor;

    function __construct(){
        $this->dataExtractor = new DataExtractor();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store() // Request $request
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        // get courses data
        $coursesIds = CustomersCourses::where('customer_id', $customer['id'])->get();
        $courses = $this->dataExtractor->getCoursesData($coursesIds);

        // get company details
        $companyData = CustomersCompanyInfo::where('customer_id', $customer['id'])->get();
        if(isset($companyData[0])){
            $companyInfo = $companyData[0]->toArray();
        } else {
            $companyInfo = [];
        }

        return view('customer.view', compact('customer', 'companyInfo', 'courses'));
    }

    /**
    //  * Preview customer pdf.
     */
    public function viewPDF(Customer $customer)
    {
        return view('customer.pdf-view', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if(!auth()->User()){
            abort(403);
        } else {
            Storage::delete($customer->pdf);
            $customer->delete();
            return redirect('/customers/');
        }
    }
}
