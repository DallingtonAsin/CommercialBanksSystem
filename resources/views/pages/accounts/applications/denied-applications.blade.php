@extends('layouts.sidebar-header')

@section('content')


<div class="panel panel-default nunito-font">
  <div class="panel-heading"> 
<div class="row">
    <div class="col-lg-7">
    <img src="{{ asset('images/icon/file.png') }}" class="icon-i mb-1 circular-icon">
    <strong class="text-info nunito-font">
    @can('isAdmin')
          Manage rejected applications
          @endcan

          @can('isMember')
          Rejected applications
          @endcan
        </strong>
    <span class="badge bg-success text-white">
      @isset($number_of_deniedApplications)
      {{ $number_of_deniedApplications }}
      @endisset
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
          <a href="{{ Route('deniedApps.truncate') }}">Delete all rejected applications</a>
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

    <div class="table-responsive">
      
      <table class="table table-borderless table-data3 deniedAppsTable">
        <thead>
          <tr class="tr-dark success">
            <th>Id</th>
            <th>Name</th>
            <th>Telno</th>
            <th>A/C</th>
            <th>Occupation</th>
            <th>DeniedBy</th>
            <th>DeniedOn</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          @isset($denied_apps)
          @foreach($denied_apps as $row)
          <tr>
            <td>{{ $row->id }}</td>
            <td class="capitalize">{{ $row->first_name }} {{ $row->last_name }}</td>`
            <td>{{ $row->tel_no }} </td>
            <td>{{ $row->type }} </td>
            <td>{{ $row->occupation }} </td>
            <td>{{ $row->denied_by }} </td>
            <td>{{ date('Y-m-d', strtotime($row->denied_on)) }} </td>
          
            <td class="text-center">
             <a href="" class="text-success"
             data-toggle="modal" data-target="#viewrowDetails_{{ $row->id }}"><i class="fa fa-eye" ></i></a>

            @can('isAdmin')
             <a href="" class="trash-btn pl-3"
             data-toggle="modal" data-target="#deleterowAccount_{{ $row->id }}"><span class="glyphicon glyphicon-trash" ></span></a>

              <a href="" class="text-success pl-4"
           data-toggle="modal" data-target="#approverowAccount_{{ $row->id }}"><span class="fa fa-check-circle" ></span> Approve</a>
            @endcan

           </td>

           @can('isSuperAdmin')
           <!--Modal Delete Approved Applicant -->
           <div class="modal fade" id="deleterowAccount_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
            <div class="modal-dialog" role="document">
              <div class="modal-content nunito-font">
                <div class="modal-header  overview-item--c2 text-center">
                  <h6 class="modal-title w-100  text-white nunito-font">
                    <i class="fa fa-info-circle text-warning"></i>
                    Delete rejected applicant</h6>
                    <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">
                    <div class="form-group">
                      <div class="text-center">
                       <label class="text-danger">
                        Are you sure you want to delete rejected applicant
                      
                        <small class="text-dark text-muted bolded">
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

        <!-- View row details -->
        <div class="modal fade" id="viewrowDetails_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-dialog-centered modal-md" role="document">

            <div class="modal-content nunito-font border border-custom-dark rounded-0">
              <div class="modal-header  overview-item--c2 text-center">
                <h6 class="modal-title nunito-font text-white nunito-font">
                  <i class="fa fa-info-circle"></i>
                  @can('isAdmin')
                  Details of rejected applicant
                   {{ $row->first_name }} {{ $row->last_name }}
                  @endcan

                  @can('isMember')
                   Details of your rejected application
                  @endcan
                </h6>
                <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <div class="form-group">
                  <span class="text-muted">Name</span>
                  <input type="text" class="form-control text-dark bg-white" value="{{ $row->first_name }} {{ $row->last_name }}" readonly>
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
                  <span class="text-muted">Denied By</span>
                  @can('isAdmin')
                  <input type="text" class="form-control bg-white text-left text-dark" 
                  value="{{ $row->denied_by }}" readonly>
                  @endcan
                  @can('isMember')
                  <input type="text" class="form-control bg-white text-left text-dark" 
                  value="Admin" readonly>
                  @endcan
                </div>

                 <div class="form-group">
                  <span class="text-muted">Date of denial</span>
                  <input type="text" class="form-control bg-white text-left text-dark" 
                  value="{{ $row->denied_on }}" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>  <!-- end of modal View rowDetails -->



         @can('isSuperAdmin')
         <!--Modal Approve Applicant -->
         <div class="modal fade" id="approverowAccount_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header  overview-item--c2 text-center">
                <h6 class="modal-title w-100  text-white nunito-font">
                 Revert decision by approving application</h6>
                <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                <div class="form-group">
                  <div class="text-center">
                   <label class="text-danger nunito-font">
                    Are you sure you want to approve an application for<br>
                    <small class="text-dark text-muted bolded">
                      {{ $row->first_name }} {{ $row->last_name }}
                    </small>
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

      </tr>
      @endforeach
      @endisset
    </tbody>
  </table>
</div>
</div>

<div class="card-footer">
  <small class="nunito">
    @can('isAdmin')
    @if(isset($rows) && count($rows) > 0)
    List of denied applications in the system
    @else
    No denied applications in the system yet
    @endif
    @endcan

    @can('isMember')
    @if(isset($rows) && count($rows) > 0)
    List of your denied application
    @else
    You don't have any denied applications
    @endif
    @endcan
  </small>
</div>

</div>



<script>
  $(document).ready(function(){
    var title = "List of denied applications in the system";
    var table = $('.deniedAppsTable');
    var columns = [0,1,2,3,4,5,6];
    dirtyTable(table,title,columns);
  })
</script>
@endsection



