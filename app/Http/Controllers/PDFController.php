<?php

namespace App\Http\Controllers;

use PDF;
use App\PDF\HtmlWrapper;
use App;
use App\Customer;
use App\Course;
use App\CustomersCourses;
use App\CustomersCompanyInfo;
use App\Helpers\DataExtractor;
use App\Email;
use App\Mail\MailToSend;
use App\Jobs\SendEmail;

use App\Http\Services\MailerService;
use App\Http\Services\MailTemplate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;


class PDFController extends Controller
{
    public $dataExtractor;
    private $mailer;
    private $mailTemplate;


    public function __construct(MailTemplate $mailTemplate){
        $this->dataExtractor = new DataExtractor();
        $this->mailer = new MailerService();
        $this->mailTemplate = $mailTemplate;
    }

    /**
    //  * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        // create html wrapper for pdf
        $wrapper = new HtmlWrapper();

        // customer info
        $customerData = Customer::all()->last();

        // format date
        $dateCreated = $customerData['created_at']->toDateTimeString();
        //dd($dateCreated);
        $year = substr($dateCreated, 0, 4);
        $month = substr($dateCreated, 5, 2);
        $day = substr($dateCreated, 8, 2);
        $formattedDate = $day.'.'.$month.'.'.$year;
        $customerData['date_created'] = $formattedDate;

        // set discount value
        $customerData['discount'] = intval($customerData['discount']);
        $customerData['fee'] = intval($customerData['fee']);

        // if customer status is not default, get customer company details
        if($customerData['status'] === 'pravno'){
            $companyInfo = CustomersCompanyInfo::where('customer_id', $customerData['id'])->get()->toArray()[0];
        }  else {
            $companyInfo = [];
        }

        // extracting courses data ---- todo replace with dataextractor
        $coursesData = CustomersCourses::where('customer_id', $customerData['id'])->get()->toArray();
        $courseIds = [];
        foreach($coursesData as $key => $value){
            array_push($courseIds, $value['course_id']);
        }
        
        $coursesInfo = [];
        $priceWithoutDiscount = 0;

        foreach($coursesData as $item => $val){
            $courseData = Course::where('id', $val['course_id'])->get();
            $course_name = $courseData->pluck('name')[0];
            $course_price = $courseData->pluck('price')[0];
            $course_participants = intval($val['course_participants']);

            $priceWithoutDiscount += intval($course_price);

            $aCourse = [
                'course_name' => $course_name,
                'course_price' => $course_price,
                'course_participants' => $course_participants
            ]; 

            array_push($coursesInfo, $aCourse);
        }

        // generate pdf
        $timestamp = $wrapper->generatePDF($customerData, $companyInfo, $coursesInfo, $priceWithoutDiscount);

        // if file exist, abort generating pdf
        if($timestamp === false){
            abort(403);
        } else {
            $this->sendMails($timestamp, $customerData);
            return view('pdf');
        }
    }


    /**
    //  * Send emails to customer and to admin
     */
    public function sendMails($timestamp, $customerData){
        // get generated pdf
        $fileLocation = 'public/pdfs/predracun-'.$customerData['id'].'-'.$timestamp.'.pdf';
        $generatedPDF = Storage::get($fileLocation);

        // set customer's pdf
        $customer = Customer::where('id', $customerData['id'])->update(['pdf' => $fileLocation]);;

        // create contact and push mail to queue
        $mailTemplate = new MailTemplate();

        // create templates
        $customerTemplate = $mailTemplate->createMailTemplate($customerData, $fileLocation, false);
        $adminTemplate = $mailTemplate->createMailTemplate($customerData, $fileLocation, env('ADMIN_EMAIL'));

        // add mails to queue
        Mail::to([env('ADMIN_EMAIL')])->queue(new MailToSend($adminTemplate));
        Mail::to([$customerData['email']])->queue(new MailToSend($customerTemplate));
    }

}
