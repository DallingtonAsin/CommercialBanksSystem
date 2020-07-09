<?php

namespace MillionsSaving\Http\Controllers\Messages;

use MillionsSaving\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate;
use MillionsSaving\Mail\SendMail;


class MailController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.mail.mail');
    }

    public function MailWelcome(){
     return new WelcomeMail();
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
    public function store(Request $request)
    {
        $this->validate($request, [   
         'receiverName' => 'required',  
         'senderEmail' => 'required|email',
         'receiverEmail' => 'required|email',
         'subject' => 'required',
         'message' => 'required',

     ]);
        $senderName = Auth::user()->name;
        $receiverName = $request->input("receiverName");
        $senderEmail = $request->input("senderEmail");
        $receiverEmail = $request->input("receiverEmail");
        $subject = $request->input("subject");
        $writing = $request->input("message");

        $position = Auth::user()->position;

        $data = array(
            'senderName' => $senderName,
            'receiverName' => $receiverName,
            
            'senderEmail' => $senderEmail,
            'receiverEmail' => $receiverEmail,
            'position' => $position,
            'subject' => $subject,
            'writing' => $writing,
        );


        if($request->hasfile('email-attachment')){
            $file = $request->file('email-attachment');
            
            if($this->is_connectedToInternet() == 1){

                Mail::send('pages.mail.dynamic_email_template', $data, function($message) use ($senderName,$senderEmail, $receiverName,$receiverEmail,$position, $subject, $writing, $file)
                {   
                    $message->from($senderEmail, 'Dallington');
                    $message->to($receiverEmail, 'Henry')->subject($subject);
                    $message->attach($file->getRealpath(), 
                        ['as' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                    ]);
                });

                if (Mail::failures()) {
                    return back()->with("MailSendFailed","Email send has failed!");
                }
                else{
                    return back()->with("MailSendSuccess","You have successfully sent an email message to ".$receiverEmail." ");
                }
            }

            else if($this->is_connectedToInternet() == 0)
            {
                return back()->with("NoInternetErr","Couldn't send email because there is no internet");
            }

        } //end of method: when email message has an attachment
        else{

           if($this->is_connectedToInternet() == 1){ 

           Mail::send('pages.mail.dynamic_email_template', $data, function($message) use ($senderName,$senderEmail, $receiverName,$receiverEmail,$position, $subject, $writing)
           {   
            $message->from($senderEmail, 'Dallington');
            $message->to($receiverEmail, 'Henry')->subject($subject);
        }); 

           if (Mail::failures()) {
                    return back()->with("MailSendFailed","Email send has failed!");
                }
                else{
                    return back()->with("MailSendSuccess","You have successfully sent an email message to ".$receiverEmail." ");
                }

       }
    
            else if($this->is_connectedToInternet() == 0)
            {
                return back()->with("NoInternetErr","Couldn't send email because there is no internet");
            }
        }

       }





public function is_connectedToInternet()
{
    $connected = @fsockopen('www.google.com', 80);
    if($connected){
      $is_conn = 1;
      fclose($connected);
  }
  else{
   $is_conn = 0;
}
return $is_conn;
}

    /**
     * Display the specified resource.
     *
     * @param  \MillionsSaving\CustomModels\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function show(Mail $mail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \MillionsSaving\CustomModels\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function edit(Mail $mail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \MillionsSaving\CustomModels\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mail $mail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \MillionsSaving\CustomModels\Mail  $mail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mail $mail)
    {
        //
    }
}
