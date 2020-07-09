@extends('layouts.sidebar-header')

@section('content')


<div class="card-table nunito-font">
  <div class="card card-dashboard-table-six">

    <div>
      <h6 class="card-title">Event Management</h6>
    </div>

    <div class="card-body">
      <form method="POST" action="{{ Route('events.store') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
          <div class="row">

           <div class="col-lg-4">
             <span>Title</span>
             <input type="text" name="event-title" class="form-control"
             placeholder="Title of the Event" required autofocus="" autocomplete="off">
           </div>


           <div class="col-lg-3">
           <span>Start Date</span>
           <input type="date" name="eventStart-date" class="form-control"
           placeholder="Start Date of the Event" required autofocus>
         </div>


         <div class="col-lg-3">
           <span>End Date</span>
           <input type="date" name="eventEnd-date" class="form-control"
           placeholder="End Date of the Event">
         </div>


         <div class="col-lg-2">
           <span>Start Time</span>
           <input type="text" name="event-time" class="event-time form-control"
           placeholder="Start time of the Event" required autofocus="">
         </div>

         </div>
       </div>


     <div class="form-group">
      <textarea id="event-message" name="event-message" rows="5" class="form-control" required autofocus="" placeholder="Write your message.."></textarea>
    </div>


    <div class="form-group">

      <div class="row">

       <div class="col-lg-3">
         <input type="submit" class="btn btn-success" name="submit" value="Save Event">
       </div>

       <div class="col-lg-8 text-center">

          @if(session()->get('event-success'))
          <div class='alert alert-success alert-dismissible' role='alert'>
           <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span></button>
            <strong>Well done!</strong> {{ session()->get('event-success') }}
          </div>
          @endif

          @if(session()->get('event-fail'))
          <div class='alert alert-danger alert-dismissible' role='alert'>
           <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span></button>
            <strong>Oops!</strong> {{ session()->get('event-fail') }}
          </div>
          @endif

        </div>


    </div>

  </div>

</form>


</div>
</div>
</div>


<script>
$(document).ready(function(){
  var config = {};
  config.placeholder = 'some value'; 
  CKEDITOR.replace('event-message', config);
$('.event-time').wickedpicker({
  twentyFour:true,
});
});
</script>


@endsection

