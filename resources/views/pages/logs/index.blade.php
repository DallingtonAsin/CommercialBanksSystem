@extends('layouts.sidebar-header')

@section('content')

<!-- <div class="card-table custom-family"> -->
  <div class="card">
    <div class="card-body">
      <div class="panel panel-default">

        <div class="panel-heading">
          <div class="panel-title">
           <div class="row">
              <div class="col-lg-10">
                <h6 class="nunito-font">
                  <i class="typcn typcn-cloud-storage-outline text-dark"></i> 
                  <span class="text-dark">
                     <strong class="text-info">Activity logs</strong>
                     <span class="badge bg-success">
                    @isset($number_of_logs)
                    {{ $number_of_logs}}
                    @endisset
                    </span>
                  </span>
                </h6>
              </div>

              @can('isSuperAdmin')
              <div class="col-lg-2">
               <h5>
                <a  href="{{ route('logs.truncate')}}" class="text-decoration-none text-danger nunito-font" type="submit"><i class="glyphicon glyphicon-trash"></i> <strong>clear-all-logs</strong></a>
              </h5>
            </div>
            @endcan


          </div>
        </div>
      </div>

        <div class="panel-body nunito-font">
        <div class="col-lg-8 text-center ">
         @if(session()->get('log-deleted'))
         <div class='alert alert-success alert-dismissible text-center' role='alert'>
           <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span></button>
            <strong>Yello!</strong>
             {{ session()->get('log-deleted') }}
             <i class="fa fa-check-circle"></i>
          </div>
          @endif

          @if(session()->get('log-not-deleted'))
          <div class='alert alert-danger alert-dismissible text-center' role='alert'>
           <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span></button>
            <strong>Opps!</strong> {{ session()->get('log-not-deleted') }}
          </div>
          @endif
        </div>


        <div class="table-responsive m-b-40">
          <table class="table table-bordered  table-data3 logsTable">
            <caption>Log of activities done by system users</caption>
            <thead>
              <tr class="tr-dark success">
                <th class="td-sm">Id</th>
                 <th>Date</th>
                 <th>Time</th>
                <th>User</th>
                <th>Role</th>
                <th>Log</th>
                <th>IP</th> 
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @isset($logs)

              @foreach($logs as $log)
              <tr>
                <td>{{ $log->id }}</td>
                 <td class="text-center">{{ date('d-m-Y', strtotime($log->date)) }}</td>
                <td class="pl-4">{{ date('H:i A', strtotime($log->date)) }}</td>
                <td>{{ $log->name }}</td>
                <td>
                  @if($log->role=='Administrator') 
                    {{__('Admin') }}
                   
                  @else
                   {{ $log->role }} 

                  @endif
                </td>
                <td class="text-center">{{ $log->action }}</td>
                <td>{{ $log->ip_address }}</td>
                
                <td class="td-sm"> 
                 <a href="" class="text-success"
                 data-toggle="modal" data-target="#viewLog_{{ $log->id }}"><i class="fa fa-eye" ></i></a>
    
                <a href="" class="text-danger pl-5"
                data-toggle="modal" data-target="#deleteLog_{{ $log->id }}"><i class="glyphicon glyphicon-trash"></i></a>
              </td>
                  

@can('isSuperAdmin')
<!--Modal DeleteLog -->
        <div class="modal fade" id="deleteLog_{{ $log->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content nunito-font border border-custom-dark rounded-0">
              <div class="modal-header overview-item--c2  text-center">
                <h5 class="modal-title w-100 text-white font-weight-bold">
                   Delete Log</h5>
                <button type="button" class="close view-close" data-dismiss="modal" aria-label="Close ">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">

                <div class="form-group">
                  <div class="text-center">
                   <label class="text-danger">Are you sure you want to delete this log;
                    <h4>
                    <small class="text-dark text-muted bolded">
                     {{ $log->action }}
                   </small>?
                 </h4>
                   
                 </label>
               </div>
             </div>

             <div class="form-group">
               <form action="{{ route('logs.destroy', $log->id) }}"
                method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-primary"  name="ConfirmBtn">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- end of modal DeleteLog-->
    @endcan

    @cannot('isSuperAdmin')
<!--Modal CannotDeleteLog -->
        <div class="modal fade" id="deleteLog_{{ $log->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
                    Sorry <label class="text-dark">{{ Auth::user()->name }}</label>, you don't have permissions to delete logs
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

           


              <!-- View Log Details -->
              <div class="modal fade" id="viewLog_{{ $log->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">

                  <div class="modal-content nunito-font border border-custom-dark rounded-0">
                    <div class="modal-header overview-item--c2  text-center">
                      <h5 class="modal-title w-100 nunito-font text-white  font-weight-bold">
                        <i class="fa fa-info-circle"></i>
                        Details of a logged activity 
                      </h5>
                      <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>

                    <div class="modal-body">

                      <div class="modal-body">
                        <div class="form-group">
                          <span>User</span>
                          <input type="text" class="form-control bg-white text-dark" value="{{ $log->name }}" readonly>
                        </div>

                        <div class="form-group">
                          <span>Role</span>
                          <input type="text" class="form-control bg-white text-dark"  value="{{ $log->role }}" readonly>

                        </div>

                        <div class="form-group">
                          <span>Action</span>
                          <textarea class="form-control bg-white text-dark" readonly>{{ $log->action }}</textarea>

                        </div>

                        <div class="form-group">
                          <span>Ip Address</span>
                          <input type="text" class="form-control bg-white text-dark"  value="{{ $log->ip_address }}" readonly>
                        </div>

                        <div class="form-group">
                          <span>Date</span>
                          <input type="text" class="form-control bg-white text-dark"  value="{{ $log->date }}" readonly>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>  <!-- end of modal ViewLog-->

            </tr>
            @endforeach

            @endisset

          </tbody>
        </table>


      </div>
    </div>
  </div>
</div>
</div>
  <!-- </div> -->



<script>
 $(document).ready(function(){
  var title="System Audit/ Activity logs";
  var table =  $('.logsTable');
  var columns = [0,1,2,3,4,5,6];
  dirtTable(table, title,columns);
});
</script>

@endsection

