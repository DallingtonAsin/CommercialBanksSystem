@extends('layouts.sidebar-header')

@section('content')
<div class="panel panel-default nunito-font">
  
  <div class="panel-heading"> 
    <div class="row">
      <div class="col-lg-7">
        <img src="{{ asset('images/icon/file.png') }}" class="icon-i mb-1 circular-icon">
        <strong class="text-info nunito-font pl-3">
          @can('isAdmin')
          Manage pending applications
          @endcan

          @can('isMember')
          Pending applications
          @endcan
          
        </strong>
        <span class="badge bg-success text-white">
          @if($number_of_pendingApplications && $number_of_pendingApplications > 0)
          {{ $number_of_pendingApplications }}
          @else
          {{ __('0') }}
          @endif
        </span>
      </div>

@can('isAdmin')
      <div class="col-lg-5">
       <div class="btn-group">
        <button type="button" class="btn border-info text-info form-control text-center dropdown-toggle downloadfilebtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         Action
       </button>
       <ul class="dropdown-menu">
        <li>
          <a href="{{ Route('pendingApps.truncate') }}">Delete all pending applications</a>
        </li>
      </ul>
    </div>
  </div>
@endcan


</div>
</div>



<div class="panel-body">

  <div class="nunito-font text-center">
   @if(session()->get('success'))
   <div class='alert alert-success alert-dismissible' role='alert'>
     <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>&times;</span></button>
      <strong>Yello!</strong>
      {{ session()->get('success') }}
      <i class="fa fa-check-circle"></i>
    </div>
    @endif

    @if(session()->get('fail'))
    <div class='alert alert-danger alert-dismissible' role='alert'>
     <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>&times;</span></button>
      <strong>Opps!</strong> {{ session()->get('fail') }}
    </div>
    @endif
  </div>


  <div class="table-responsive m-b-40">
     <table class="table table-borderless table-data3 pendingAppsTable">
      <thead>
        <tr class="tr-dark success">
          <th>Id</th>
          <th>Name</th>
          <!-- <th>Telno</th> -->
          <th>A/C</th>
          <th>Occupation</th>
          <th>SubmittedOn</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        @foreach($rows as $row)
        <tr>
          <td>{{ $row->id }}</td>
          <td class="capitalize">
            {{ $row->first_name }}
            {{ $row->last_name }}
          </td>`
          <!-- <td>{{ $row->tel_no }} </td> -->
          <td>{{ $row->type }} </td>
          <td>{{ $row->occupation }} </td>
          <td>{{ date('Y-m-d', strtotime($row->submitted_on)) }} </td>
          
          <td>

           <a href="" class="text-success"
           data-toggle="modal" data-target="#viewrowDetails_{{ $row->id }}"><i class="fa fa-eye" ></i></a>
            @can('isAdmin')
           <a href="" class="pl-4"
           data-toggle="modal" data-target="#deleterowAccount_{{ $row->id }}"><span class="fa fa-trash-alt text-danger" ></span></a>
    
           <a href="" class="text-success pl-3"
           data-toggle="modal" data-target="#approverowAccount_{{ $row->id }}"><span class="fa fa-check-circle" ></span> Approve</a>

           <a href="" class="trash-btn pl-3"
           data-toggle="modal" data-target="#denyrowAccount_{{ $row->id }}"><span class="fa fa-times-circle" ></span> Deny</a>
              @endcan
         </td>
      


         @can('isAdmin')
         <!--Modal Delete Approved Applicant -->
         <div class="modal fade" id="deleterowAccount_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header  overview-item--c2 text-center">
                <h6 class="modal-title w-100  text-white nunito-font">
                Delete pending application</h6>
                <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <div class="form-group">
                  <div class="text-center">
                   <label class="text-danger nunito-font">
                    Are you sure you want to delete pending
                     application for<br>
                    <small class="text-dark capitalize text-muted bolded">
                      {{ $row->first_name }} {{ $row->last_name }}
                    </small>
                    ?
                  </label>
                </div>
              </div>

              <div class="form-group">
               <form action="{{ route('applications.destroy', $row->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- end of modal Delete Approved Applicant-->
    @endcan

   @cannot('isAdmin')
<!--Modal CannotDeleteLog -->
        <div class="modal fade" id="deleterowAccount_{{ $row->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content nunito-font border border-custom-dark rounded-0">
              <div class="modal-header overview-item--c2  text-center">
                <h5 class="modal-title w-100 text-white font-weight-bold">
                  <i class="fa fa-info-circle text-warning"></i> Permissions info</h5>
                <button type="button" class="close view-close" data-dismiss="modal" aria-label="Close ">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">

                <div class="form-group">
                  <div class="text-center">
                   <label class="text-danger">
                    Sorry <label class="text-dark">{{ Auth::user()->name }}</label>, you don't have permissions to carry out this task
                    <h5 class="text-success">
                      Only administrators with super privileges can!
                 </h5>
                   
                 </label>
               </div>
             </div>

             <div class="form-group">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- end of modal CannotDeleteLog-->
    @endcannot


       @can('isAdmin')
         <!--Modal Approve Applicant -->
         <div class="modal fade" id="approverowAccount_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header  overview-item--c2 text-center">
                <h6 class="modal-title w-100  text-white nunito-font">
                Approve application</h6>
                <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <div class="form-group">
                  <div class="text-center">
                   <label class="text-danger nunito-font">
                    Are you sure you want to approve <small class="text-dark text-muted bolded capitalize">
                      {{ $row->first_name }} {{ $row->last_name }}
                    </small>'s application for {{ $row->type }} account
                    ?
                    ?
                  </label>
                </div>
              </div>

              <div class="form-group">
               <form action="{{ Route('approve.app', ['id' => $row->id, 'account' => $row->type]) }}"
                method="post">
                @csrf
                <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- end of modal  Approve Applicant-->
    @endcan

    @can('isAdmin')
         <!--Modal Deny Applicant -->
         <div class="modal fade" id="denyrowAccount_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header  overview-item--c2 text-center">
                <h6 class="modal-title w-100  text-white nunito-font">
                Deny application</h6>
                <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body nunito-font">
                 <form action="{{ Route('deny.app', ['id' => $row->id, 'account' => $row->type]) }}" method="post">
                @csrf
                 
                <div class="form-group">
                  <div>
                   <label class="text-danger text-center nunito-font">
                    Are you sure you want to deny 
                    <small class="text-dark text-muted bolded capitalize">
                      {{ $row->first_name }} {{ $row->last_name }}
                    </small>'s application for {{ $row->type }} account
                    ?
                  </label>

                   <span>Reason</span><small class="pl-3 text-danger">*optional</small>
                  <input type="text" name="reason" placeholder="Any reason for denying this application" class="form-control">

                </div>
              </div>

              <div class="form-group">
                <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- end of modal Deny Applicant-->
    @endcan


    <!-- View row details -->
    <div class="modal fade" id="viewrowDetails_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">

        <div class="modal-content nunito-font border border-custom-dark rounded-0">
          <div class="modal-header  overview-item--c2 text-center">
            <h6 class="modal-title nunito-font text-white nunito-font">
              <i class="fa fa-info-circle"></i>
              @can('isAdmin')
              Details of an applicant
             <span class="capitalize"> {{ $row->first_name }} {{ $row->last_name }}</span>
              @endcan

              @can('isMember')
                Details of your pending application
              @endcan
            </h6>
            <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div class="form-group">
              <span class="text-muted">Name</span>
              <input type="text" class="form-control capitalize text-dark bg-white" value="{{ $row->first_name }} {{ $row->last_name }}" readonly>
            </div>

            <div class="form-group">
              <span class="text-muted">A/C Type</span>
              <input type="text" name="type" class="form-control bg-white text-left text-dark" value="{{ $row->type }}" readonly>
            </div>

            <div class="form-group">
              <span class="text-muted">Telno</span>
              <input type="text" class="form-control bg-white text-left text-dark" 
              value="{{ $row->tel_no }}" readonly>
            </div>

            @if($row->alt_telno)
            <div class="form-group">
              <span class="text-muted">Alternative TelNo</span>
              <input type="text" class="form-control text-dark bg-white" value="{{ $row->alt_telno }}" readonly>
            </div>
            @endif


            <div class="form-group">
              <span class="text-muted">Address</span>
              <input type="text" name="description" class="form-control  text-dark bg-white"  value="{{  $row->address }}" readonly>
            </div>

            <div class="form-group">
              <span class="text-muted">Occupation</span>
              <input type="text" name="description" class="form-control  text-dark bg-white"  value="{{  $row->occupation }}" readonly>
            </div>

            @if($row->company)
            <div class="form-group">
              <span class="text-muted">Company</span>
              <input type="text" class="form-control text-dark bg-white" value="{{ $row->company }}" readonly>
            </div>
            @endif

            @if($row->city)
            <div class="form-group">
              <span class="text-muted">City</span>
              <input type="text" class="form-control text-dark bg-white" value="{{ $row->city }}" readonly>
            </div>
            @endif

            @if($row->state)
            <div class="form-group">
              <span class="text-muted">State</span>
              <input type="text" class="form-control bg-white text-left text-dark" 
              value="{{ $row->state }}" readonly>
            </div>
            @endif

            @if($row->zipcode)
            <div class="form-group">
              <span class="text-muted">ZipCode</span>
              <input type="text" class="form-control text-dark bg-white" value="{{ $row->zipcode }}" readonly>
            </div>
            @endif

            @if($row->email)
            <div class="form-group">
              <span class="text-muted">Email</span>
              <input type="text" class="form-control bg-white text-left text-dark" 
              value="{{ $row->email }}" readonly>
            </div>
            @endif

            <div class="form-group">
              <span class="text-muted">Date of Birth</span>
              <input type="text" class="form-control bg-white text-left text-dark" 
              value="{{ $row->date_of_birth }}" readonly>
            </div>

            <div class="form-group">
              <span class="text-muted">Submitted on</span>
              <input type="text" class="form-control bg-white text-left text-dark" 
              value="{{ $row->submitted_on }}" readonly>
            </div>

          </div>
        </div>
      </div>
    </div>  <!-- end of modal View rowDetails -->
 


    @can('isAdmin')
    <!-- Update row details -->
    <div class="modal fade" id="updaterowAccount_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">

        <div class="modal-content nunito-font border border-custom-dark rounded-0">
          <div class="modal-header overview-item--c2 text-center">
            <label class="modal-title nunito-font text-white  nunito-font">
              <i class="fa fa-info-circle"></i>
              Update details of row {{ $row->first_name }} {{ $row->last_name }}
            </label>
            <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

    
            <div class="modal-body">
              <form action="{{ Route('applications.update', $row->id)}}" method="POST">
                @method('PATCH')
                @csrf

               <div class="form-group">
              <span class="text-muted">Name</span>
              <input type="text" class="form-control capitalize text-dark bg-white" value="{{ $row->first_name }} {{ $row->last_name }}" readonly>
            </div>

            <div class="form-group">
              <span class="text-muted">A/C Type</span>
              <input type="text" name="type" class="form-control bg-white text-left text-dark" value="{{ $row->type }}" readonly>
            </div>

            <div class="form-group">
              <span class="text-muted">Telno</span>
              <input type="text" class="form-control bg-white text-left text-dark" 
              value="{{ $row->tel_no }}" readonly>
            </div>

            @if($row->alt_telno)
            <div class="form-group">
              <span class="text-muted">Alternative TelNo</span>
              <input type="text" class="form-control text-dark bg-white" value="{{ $row->alt_telno }}" readonly>
            </div>
            @endif


            <div class="form-group">
              <span class="text-muted">Address</span>
              <input type="text" name="description" class="form-control  text-dark bg-white"  value="{{  $row->address }}" readonly>
            </div>

            <div class="form-group">
              <span class="text-muted">Occupation</span>
              <input type="text" name="description" class="form-control  text-dark bg-white"  value="{{  $row->occupation }}" readonly>
            </div>

            @if($row->company)
            <div class="form-group">
              <span class="text-muted">Company</span>
              <input type="text" class="form-control text-dark bg-white" value="{{ $row->company }}" readonly>
            </div>
            @endif

            @if($row->city)
            <div class="form-group">
              <span class="text-muted">City</span>
              <input type="text" class="form-control text-dark bg-white" value="{{ $row->city }}" readonly>
            </div>
            @endif

            @if($row->state)
            <div class="form-group">
              <span class="text-muted">State</span>
              <input type="text" class="form-control bg-white text-left text-dark" 
              value="{{ $row->state }}" readonly>
            </div>
            @endif

            @if($row->zipcode)
            <div class="form-group">
              <span class="text-muted">ZipCode</span>
              <input type="text" class="form-control text-dark bg-white" value="{{ $row->zipcode }}" readonly>
            </div>
            @endif

            @if($row->email)
            <div class="form-group">
              <span class="text-muted">Email</span>
              <input type="text" class="form-control bg-white text-left text-dark" 
              value="{{ $row->email }}" readonly>
            </div>
            @endif

            <div class="form-group">
              <span class="text-muted">Date of Birth</span>
              <input type="text" class="form-control bg-white text-left text-dark" 
              value="{{ $row->date_of_birth }}" readonly>
            </div>

            <div class="form-group">
              <span class="text-muted">Submitted on</span>
              <input type="text" class="form-control bg-white text-left text-dark" 
              value="{{ $row->submitted_on }}" readonly>
            </div>
                <div class="form-group">
                  <button ype="submit" class="form-control btn overview-item--c2 text-white" name="">
                    <strong>Update account</strong>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
     <!-- end of modal updaterowDetails -->
    @endcan
  </tr>
  @endforeach

</tbody>
</table>
</div>


</div>


</div>

@can('isAdmin')
<script>
  $(document).ready(function(){
    var title = "List of approved applications in the system";
    var table = $('.pendingAppsTable');
    var columns = [0,1,2,3,4,5,6];
    dirtyTable(table,title,columns);
  });
</script>
@endcan


@can('isMember')
<script>
  $(document).ready(function(){
    var title = "List of pending applications in the system";
    var table = $('.pendingAppsTable');
    var columns = [0,1,2,3,4];
    dirtyTable(table,title,columns);
  });
</script>
@endcan


@endsection



