@extends('layouts.sidebar-header')

@section('content')


  <div class="card">
 

        <div class="card-header">
          <h4>
            <i class="fa fa-sun-o text-dark"></i>
            <span class="card-title text-info pl-3"> Events </span>
        </h4>
        </div>
        
   <div class="card-body">
      <div class="col-lg-12 text-center">
       @if(session()->get('event-success'))
       <div class='alert alert-success alert-dismissible' role='alert'>
         <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span></button>
          <strong>Well done!</strong>
           {{ session()->get('event-success') }}
           <i class="fa fa-check-circle"></i>
        </div>
        @endif

        @if(session()->get('event-fail'))
        <div class='alert alert-success alert-dismissible' role='alert'>
         <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span></button>
          <strong>Sorry!</strong> {{ session()->get('event-fail') }}
        </div>
        @endif
      </div>






      <div class="table-responsive m-b-40">
        <table class="table table-borderless table-data3 events-table">
          <thead >
            <tr class="tr-dark success">
              <th class="td-sm">No</th>
              <th class="event-ti">Event Title</th>
              <th class="td-lg-2">S.Date</th>
              <th class="td-lg-2">E.Date</th>
              <th>S.Time</th>
              <th>User</th>
              <th>Status</th>
              
              @cannot('isCashier')
              <th class="td-md">Edit</th>
              <th class="td-md">Delete</th>
              @endcan
              <th>View</th>
              
            </tr>
          </thead>
          <tbody>
           @isset($events)

           @foreach($events as $event)
           <tr>
            <td>{{ $event->id }}</td>
            <td>{{ $event->title }}</td>
            <td>{{  date('d-m-Y', strtotime($event->start_date)) }}</td>
            <td>{{ $event->end_date }}</td>
            <td class="pr-4">{{ date('h:i A',strtotime($event->start_time)) }}</td>
            <td>{{ $event->event_registra }}</td>
            <td>
              @php

              $date_of_event = new DateTime($event->start_date);
              $now = new DateTime();

              $date1 = date('d-m-Y', strtotime($event->date_of_event));
              $date2 = date('d-m-Y', strtotime($event->now));


              $diff = abs(strtotime($date1) - strtotime($date2));

              $years = floor($diff / (365*60*60*24)); 

              $months = floor(($diff - $years * 365*60*60*24) 
              / (30*60*60*24)); 

              $days = floor(($diff - $years * 365*60*60*24 -  
              $months*30*60*60*24)/ (60*60*24)); 

              $hours = floor(($diff - $years * 365*60*60*24  
              - $months*30*60*60*24 - $days*60*60*24) 
              / (60*60));

              $minutes = floor(($diff - $years * 365*60*60*24  
              - $months*30*60*60*24 - $days*60*60*24  
              - $hours*60*60)/ 60);

              $seconds = floor(($diff - $years * 365*60*60*24  
              - $months*30*60*60*24 - $days*60*60*24 
              - $hours*60*60 - $minutes*60)); 
              $format = 'Y-m-d';
            

              if($date_of_event > $now){
                $observation1 = '';
                $observation2 = 'to the event'; 

              @endphp
                  
              <span class="text-success">{{__('on')}}</span>
              @php
            }
            else
            {
              $observation1 ='Ended';
              $observation2 = 'ago';
              @endphp
              <span class="text-info">{{__('ended')}}</span>
              @php } @endphp

            </td>
            

            @cannot('isCashier')
            <td>
              <a href="" class="edit-btn"
              data-toggle="modal" data-target="#updateEvent_{{ $event->id }}">
              <span class="glyphicon glyphicon-pencil" ></span></a>
            </td>

            <td>
              <a href="" class="trash-btn"
              data-toggle="modal" data-target="#deleteEvent_{{ $event->id }}">
              <span class="glyphicon glyphicon-trash" ></span></a>
            </td>
            @endcan

            <td>
             <a href="" class="text-success"
             data-toggle="modal" data-target="#viewEvent_{{ $event->id }}"><i class="fa fa-eye" ></i></a>
           </td>


           <!-- View Event Details -->
           <div class="modal fade" id="viewEvent_{{ $event->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">

              <div class="modal-content nunito-font border border-custom-dark rounded-0">
                <div class="modal-header main-color-bg text-center">
                  <h5 class="modal-title w-100 nunito-font text-white  font-weight-bold">
                    <i class="fa fa-info-circle"></i>
                    Details of an event 
                  </h5>
                  <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="modal-body">

                  <div class="modal-body">
                    <div class="form-group">
                      <span>Title</span>
                      <input type="text" class="form-control bg-white text-dark" value="{{ $event->title }}" readonly>
                    </div>

                    <div class="form-group">
                      <span>Description</span>
                      <textarea class="form-control bg-white text-dark" readonly>{{ $event->description }}</textarea>
                    </div>

                    <div class="form-group">
                      <span>Event Status</span>
                      <input type="text" class="form-control bg-white text-left text-dark" 
                       value="{{ $observation1 }} {{ $years }} years {{ $months }} months {{ $days}} days {{$hours }} hours {{ $minutes}} mins {{ $observation2 }}" readonly>
                    </div>

                    <div class="form-group">
                      <span>Start Date</span>
                      <input type="text" class="form-control bg-white text-dark"  value="{{  date('d-M-Y', strtotime($event->start_date)) }}" readonly>
                    </div>

                    <div class="form-group">
                      <span>End Date</span>
                      <input type="text" class="form-control bg-white text-dark"  value="{{  date('d-M-Y', strtotime($event->end_date)) }}" readonly>
                    </div>

                    <div class="form-group">
                      <span>Start Time</span>
                      <input type="text" class="form-control bg-white text-dark"  
                      value="{{ date('h:i A',strtotime($event->start_time)) }}" readonly>
                    </div>

                    <div class="form-group">
                      <span>Recorded by</span>
                      <input type="text" class="form-control bg-white text-dark"  value="{{ $event->event_registra }}" readonly>
                    </div>

                  </div>
                </div>


              </div>
            </div>
          </div>  <!-- end of modal ViewEvent-->


          @can('delete-event', $event->event_registra)
          <!--Modal DeleteEvent -->
          <div class="modal fade" id="deleteEvent_{{ $event->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header text-center">
                  <h5 class="modal-title w-100 font-weight-bold">Delete Event</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <div class="modal-body">

                  <div class="form-group">
                    <div class="text-center">
                     <label class="text-danger">Are you sure you want to delete an event with title 
                      <h4 class="text-dark text-muted bolded">
                       <small> {{ $event->title }}? </small>
                     </h4>
                   </label>
                 </div>
               </div>

               <div class="form-group">
                 <form action="{{ route('events.destroy', $event->id) }}" method="post">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-primary"  name="ConfirmBtn">Yes</button>
                  <button type="button" class="btn btn-dark" data-dismiss="modal">No</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- end of modal DeleteEvent-->
      @endcan

      
      @can('update-event', $event->event_registra )
      <!-- Update Event Details -->
      <div class="modal fade custom-family" id="updateEvent_{{ $event->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <div class="modal-header text-center">
              <h5 class="modal-title w-100 font-weight-bold">Update Event</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">

              @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
              
              @endif
              <form method="post" action="{{ route('events.update', $event->id) }}">
                @method('PATCH')
                @csrf

                
                <div class="form-group">
                  <span>Title</span>
                  <input type="text" class="form-control" name="event-title" value="{{ $event->title }}"  Required autofocus>
                </div>

                <div class="form-group">
                  <span>Start Date</span>
                  <input type="date" class="form-control" name="eventStart-date" value="{{ $event->start_date }}" Required autofocus>
                </div>

                <div class="form-group">
                  <span>End Date</span>
                  <input type="date" class="form-control" name="eventEnd-date" value="{{ $event->end_date }}" >
                </div>

                <div class="form-group">
                  <span>Description</span>
                  <textarea class="form-control" height=200 name="event-message" placeholder="please include place of an event in the description......" 
                  required >{{ $event->description }}</textarea>
                </div>


                <div class="form-group">
                  <span>Time</span>
                  <input type="time" class="form-control" name="event-time" value="{{ $event->start_time }}" Required autofocus>
                </div>



                <div class="form-group">
                  <button type="submit" class="btn btn-primary"  name="UpdateEventBtn">Update</button>
                  <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                </div>

              </form>
              
            </div>  <!-- end of modal UpdateEvent -->
          </div>
        </div>
      </div>
      @endcan


      @cannot('update-event', $event->event_registra)

      <div class="modal fade" id="updateEvent_{{ $event->id}}"  tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">


            <div class="modal-header text-center">
              <h5 class="modal-title w-100 nunito-font text-dark  font-weight-bold">
                <i class="fa fa-info-circle text-warning"></i>
                Warning 
              </h5>
              <button type="button" class="close view-close text-dark" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">

              <div class="form-group">

               <label class="text-danger  f-15 nunito-font">
                <h5 class="text-success">Dear {{Auth::user()->name }},</h5>
                  You cannot update this event since u didn't create it, please contact <span class="text-dark">{{ $event->event_registra }}</span> the creator of this event to update this event!
                </label>

              </div>

            </div>
          </div>
        </div>
      </div>

      @endcannot


      @cannot('delete-event', $event->event_registra)

      <div class="modal fade" id="deleteEvent_{{ $event->id}}"  tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">


            <div class="modal-header text-center">
              <h5 class="modal-title w-100 nunito-font text-dark  font-weight-bold">
                <i class="fa fa-info-circle text-warning"></i>
                Warning 
              </h5>
              <button type="button" class="close view-close text-dark" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">

              <div class="form-group">

               <label class="text-danger f-15 nunito-font pt-2">
                <h5 class="text-success">Dear {{Auth::user()->name }},</h5>
                  You cannot delete this event since u didn't create it, please contact <span class="text-dark">{{ $event->event_registra }}</span> the creator of this event to delete this event!
                </label>

              </div>

            </div>
          </div>
        </div>
      </div>

      @endcannot

    </tr>
    @endforeach
    @endisset

  </tbody>
</table>

</div>
</div>
</div>


<script>

  $(document).ready(function(){
    $('.events-table').dataTable();
    $('.event-time').wickedpicker({
      twentyFour:true,
    });

  });

</script>

@endsection

