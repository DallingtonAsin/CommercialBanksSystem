<?php

namespace MillionsSaving\Http\Controllers\Events;

use MillionsSaving\Http\Controllers\Controller;
use MillionsSaving\Http\Controllers\CentralController;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Notification;
use MillionsSaving\Http\Controllers\Logs\LogsController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use MillionsSaving\Models\Event;
use MillionsSaving\User;
use Illuminate\Notifications\Notifiable;
use MillionsSaving\Notifications\NotifyUser;
use MillionsSaving\Notifications\EventNotifier;


class EventsController extends Controller
{
   use Notifiable;

  public $date_of_action;
  private $old_title,$old_content,$old_sdate,$old_edate,$old_stime;
  

  public function __construct()
  {
    $this->date_of_action = now();
    $this->middleware('auth');
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
      $events = Event::all();
      return view('pages.events.index')->with(compact('events'));
    }

    public function ShowEventForm(){

      return view('pages.events.events');
    }

   public function loadCalendar()
   {
     return view('pages.events.index');
   }

   


   public function displayCalender()  
   {  
     // $events = [];  
     // $data = Event::all();  
     // if($data->count())
     // {  
     //   foreach ($data as $key => $value)
     //   {  
     //     $events[] = Calendar::event(  
     //       $value->title,  
     //       true,  
     //       new \DateTime($value->start_date),  
     //       new \DateTime($value->end_date.' +1 day')  
     //     );  
     //   }

     // }  

     // $calendar = Calendar::addEvents($events);   
     // return view('pages.events.calendar', compact('calendar'));  
   }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     return view('pages.events.events');
   }

   public function getUserEmails(){
     $users = User::all();
     $userEmails = array();
     foreach($users as $user){
        array_push($userEmails, $user->email);
     }
     return $userEmails;

   }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
       'event-title' => 'required',
       'event-message' => 'required',
       'eventStart-date' => 'required',
       'event-time' => 'required'

     ]);

      $event = new Event();

      $title = request('event-title');
      $startDate = request('eventStart-date');
      $endDate = request('eventEnd-date');
      $time = request('event-time');
      $event_description = request('event-message');

      $event_description = preg_replace("/^<p.*?>/", "", $event_description);
      $event_description = preg_replace("|</p>$|", "", $event_description);
      $time = preg_replace('/\s+/', '', $time) ;
      $registra = Auth::user()->name;

      $event->title = $title;
      $event->start_date = $startDate;
      $event->end_date = $endDate;
      $event->start_time = $time;
      $event->description = $event_description;
      $event->event_registra = $registra;
      $registraEmail = Auth::user()->email;
      $registraPhone = Auth::user()->contact;
      $subject = "New Event";

      $event_save_status = $event->save();

      if($event_save_status){

        $users = User::all();

        ($endDate == "")?
        $endDate_of_event = "the same day" : $endDate_of_event = $endDate;
       

        $data = array(
          'title' => $title,
          'description' => $event_description,
          'start_date' => $startDate,
          'end_date' => $endDate_of_event,
          'time' => $time,
          'registra' => $registra,
          'registra_email' => $registraEmail,
          'registraMobileNo' => $registraPhone,
          'subject' => $subject,
        );

        $action = "Recorded an event with the title '".$title."'";
        LogsController::logger($action, $this->date_of_action);

        $notified = Notification::send($users, new EventNotifier($data));
        
        $central = new CentralController();
        if($central->is_connectedToInternet() == 1)
        {
          $user_emails = $this->getUserEmails();
          Mail::send('pages.mail.mail_event', $data, function($message) use ($title,$event_description, $startDate,$endDate_of_event,
            $time, $registra,$registraEmail,$user_emails,$registraPhone,$subject)
          {   
            $message->from($registraEmail, 'Dallington');
            $message->to($user_emails)->subject($subject);
          }); 

          if(Mail::failures()){
           return redirect('events/event-form')->with('event-success','Event has been recorded successfully and all users of the system have been notified with in the app notification but not via email because of internet connection issues');

         } 

         else{

          return redirect('events/event-form')->with('event-success','Event has been recorded successfully & all users of the system have been notified within the app notification & email');  
        }

      }

      else if($central->is_connectedToInternet() == 0)
      {
       return redirect('events/event-form')->with('event-success','Event has been recorded successfully and all users of the system have been notified only in app notification');

     }



   }
   else
   {
    return redirect('events/event-form')->with('event-fail','Event has not been recorded!');
  }

}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }


    public function getEventdetails($id)
    {
      $data = DB::table('events')->where('id', '=', $id)->get();
      return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


     $request->validate([
       'event-title' => 'required',
       'event-message' => 'required',
       'eventStart-date' => 'required',
       'event-time' => 'required'

     ]);

     $event = Event::find($id);
     if(Auth::check()){
        $logged_in_user = Auth::user()->name;
     }
     $registra = $event->event_registra;
  
     if(Gate::allows('update-event',$registra)){

     $title = request('event-title');
     $startDate = request('eventStart-date');
     $endDate = request('eventEnd-date');
     $time = request('event-time');
     $event_description = request('event-message');

     $event_description = preg_replace("/^<p.*?>/", "", $event_description);
     $event_description = preg_replace("|</p>$|", "", $event_description);
     $time = preg_replace('/\s+/', '', $time) ;



     $event_details = $this->getEventdetails($id);
     foreach($event_details as $data)
     {
      $this->old_title = $data->title;
      $this->old_content = $data->description;
      $this->old_sdate = $data->start_date;
      $this->old_edate = $data->end_date;
      $this->old_stime = $data->start_time;
    }
     
     switch(true){
       case($title != $this->old_title):
          $changes[] = "changed ".$this->old_title."";
          break;
      case($startDate != $this->old_sdate):
          $changes[] = "changed ".$this->old_sdate."";
          break;
      case($endDate != $this->old_edate):
          $changes[] = "changed ".$this->old_edate."";
          break;
      case($time != $this->old_stime):
          $changes[] = "changed ".$this->old_stime."";
          break;
     }

    

    $event->title = $title;
    $event->start_date = $startDate;
    $event->end_date = $endDate;
    $event->start_time = $time;
    $event->description = $event_description;
    $event->event_registra = $logged_in_user;
    $registraEmail = Auth::user()->email;
    $registraPhone = Auth::user()->contact;
    $subject = "Updated Event ".$this->old_title."";

    $event_update_status = $event->save();

    if($event_update_status){

      $users = User::all();

      ($endDate == "")?
      $endDate_of_event = "the same day" : $endDate_of_event = $endDate;


      $data = array(
        'title' => $title,
        'description' => $event_description,
        'start_date' => $startDate,
        'end_date' => $endDate_of_event,
        'time' => $time,
        'registra' => $logged_in_user,
        'registra_email' => $registraEmail,
        'registraMobileNo' => $registraPhone,
        'subject' => $subject,
      );

      $action = "Updated an event with the title '".$title."'";
      LogsController::logger($action, $this->date_of_action);

      $notified = Notification::send($users, new EventNotifier($data));
      
      $central = new CentralController();

      if($central->is_connectedToInternet() == 1)
      {
        Mail::send('pages.mail.mail_event', $data, function($message) use ($title,$event_description, $startDate,$endDate_of_event,
          $time, $logged_in_user,$registraEmail,$registraPhone,$subject)
        {   
          $message->from($registraEmail, 'Dallington');
          $message->to($registraEmail, 'Henry')->subject($subject);
        }); 

        if(Mail::failures()){
         return redirect('events')->with('event-success','Event has been updated successfully and all users of the system have been notified with in the app notification but not via email because of internet connection issues');

       } 

       else{

        return redirect('events')->with('event-success','Event has been updated successfully & all users of the system have been notified within the app notification & email');  
      }

    }

    else if($central->is_connectedToInternet() == 0)
    {
     return redirect('events')->with('event-success','Event has been updated successfully and all users of the system have been notified only in app notification');

   }



 }
 else
 {
  return redirect('events')->with('event-fail','Event has not been updated!');
}

}
 else
 {
  return redirect('/events')->with('event-fail','You cannot edit this event since you are not the one who created it!');
}

}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

     $event = Event::find($id);

     $registra = $event->event_registra;
  
     if(Gate::allows('delete-event',$registra)){

     $title = $event->title;
     $event_delete_status = $event->delete();

     if($event_delete_status){

       $action = "Deleted an event with the title '".$title."'";
       LogsController::logger($action, $this->date_of_action); 

       return redirect('/events')->with('event-success','Event successfully deleted');
     }
     else{
       return redirect('/events')->with('event-fail','Event not deleted!');
     }

   }

   }


 




} // end of the class
