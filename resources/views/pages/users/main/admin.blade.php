@extends('layouts.sidebar-header')

@section('content')


  <div class="panel panel-default">
     <div class="panel-heading">
        <div class="panel-title row">
          <div class="col-lg-9">
            <img src="{{ asset('images/icon/user-list.png') }}" class="icon-i text-info" />
            <strong class="text-info nunito-font">Manage Staff</strong>
            <span class="badge bg-success text-white">
              @isset($number_of_admin)
              {{ $number_of_admin }}
              @endisset
            </span>
          </div>

          <div class="col-lg-3">
            @can('isAdmin')
            <div class="btn-group">
              <button type="button" class="btn border-info text-info form-control text-center dropdown-toggle downloadfilebtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <strong>Action</strong>
             </button>
             <ul class="dropdown-menu">
              <li class="text-info">
                <a href="" data-toggle="modal" data-target="#addStaff">Add Staff</a>
                <a href="" data-toggle="modal" data-target="#importStaff">Import staff</a>
              </li>
            </ul>
          </div>
          @endcan
        </div>
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
        <table class="table table-borderless table-data3 StaffMembersTable">
          <thead>
            <tr class="tr-dark success">
              <th>Id</th>
              <th>Name</th>
              <th>Email</th>
              <th>Contact</th>
              <th>Address</th>
              <th>Action</th>
              <th>status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->tel_no }}</td>
              <td>{{ $user->address }} </td>
              <td class="text-center">
               <a href="" class="text-success"
               data-toggle="modal" data-target="#viewUser_{{ $user->id }}"><i class="fa fa-eye" ></i></a>
               <a href="" class="text-success pl-3"
               data-toggle="modal" data-target="#updateUser_{{ $user->id }}"><span class="glyphicon glyphicon-pencil" ></span></a>
               <a href="" class="trash-btn pl-3"
               data-toggle="modal" data-target="#deleteUser_{{ $user->id }}"><span class="glyphicon glyphicon-trash" ></span></a>
             </td>
             <td class="text-center">
              @if($user->active === 1)
              <span class="text-success">{{ __('Active') }}</span>
              @endif
              @if($user->active === 0)
              <span class="text-danger">{{ __('Inactive') }}</span>
              @endif
            </td>

            <td class="text-center">
             @if($user->active === 1)
             <a href="{{ Route('user.changestatus', ['id' => $user->id, 'status' => $user->active, 'name' => $user->name]) }}"  class="btn-status btn btn-sm  btn-danger nunito-font"><i class="fa fa-times"></i> {{__('Deactivate') }}</a>
             @endif

             @if($user->active === 0)
             <a href="{{ Route('user.changestatus', ['id' => $user->id, 'status' => $user->active, 'name' => $user->name]) }}" class="btn-status  btn btn-sm btn-success nunito-font">
               <i class="fa fa-check"></i> {{__('Activate') }}</a>
               @endif
             </td>

             <!-- Modal Delete User Record -->
             <div class="modal fade" id="deleteUser_{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header  overview-item--c2 text-center">
                    <h6 class="modal-title w-100  text-white nunito-font">
                      Delete staff member
                    </h6>
                    <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>

                  <div class="modal-body">

                    <div class="form-group">
                      <div class="text-center">
                       <label class="text-danger">
                        Are you sure you want to delete staff member
                        <span class="text-dark text-muted bolded">
                         {{ $user->name }}
                       </span>
                       ?
                     </label>
                   </div>
                 </div>

                 <div class="form-group">
                   <form action="{{ route('users.destroy', $user->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div> <!-- end of modal Delete user Record-->

        <!-- View user Details -->
        <div class="modal fade" id="viewUser_{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content nunito-font border border-custom-dark rounded-0">
              <div class="modal-header  overview-item--c2 text-center">
                <h6 class="modal-title nunito-font text-white nunito-font">
                  <i class="fa fa-info-circle"></i>
                  Details of staff member {{ $user->name }}
                </h6>
                <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">

                <div class="modal-body">

                  <div class="form-group">
                    <span class="text-muted">Gender</span>
                    <input type="text" class="form-control bg-white"  value="{{ $user->gender }}" readonly>
                  </div>

                  <div class="form-group">
                    <span class="text-muted">Email</span>
                    <input type="text" class="form-control bg-white"  
                    value="{{ $user->email }}" readonly>
                  </div>

                  <div class="form-group">
                    <span class="text-muted">Occupation</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->occupation }}" readonly>
                  </div>

                  @if($user->company)
                  <div class="form-group">
                    <span class="text-muted">Company</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->company }}">
                  </div>
                  @endif

                  <div class="form-group">
                    <span class="text-muted">Telno</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->tel_no }}" readonly>
                  </div>

                  @if($user->alt_telno)
                  <div class="form-group">
                    <span class="text-muted">Alternative Telno</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->alt_telno }}" name="mobileno2" placeholder="Enter your alternative tel number" required>
                  </div>
                  @endif

                  <div class="form-group">
                    <span class="text-muted">Address</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->address }}" readonly>
                  </div>

                  <div class="form-group">
                    <span>City</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->city }}" readonly>
                  </div>

                  <div class="form-group">
                    <span class="text-muted">State</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->state }}" readonly>
                  </div>

                  <div class="form-group">
                    <span class="text-muted">Date of Birth</span>
                    <input type="date" class="form-control bg-white text-dark" value="{{ $user->date_of_birth }}" readonly>
                  </div>

                  <div class="form-group">
                    <span class="text-muted">Main A/c No</span>
                    <input type="text" class="form-control bg-white text-success" value="{{ $user->acc_noM }}" readonly>
                  </div>

                  @if($user->acc_noE)
                  <div class="form-group">
                    <span class="text-muted">Education A/c No</span>
                    <input type="text" class="form-control bg-white text-info" value="{{ $user->acc_noE }}" readonly>
                  </div>
                  @endif

                  @if($user->acc_noR)
                  <div class="form-group">
                    <span class="text-muted">Retirement A/c No</span>
                    <input type="text" class="form-control bg-white text-danger" value="{{ $user->acc_noR }}" readonly>
                  </div>
                  @endif

                  @if($user->acc_noS)
                  <div class="form-group">
                    <span class="text-muted">Shares A/c No</span>
                    <input type="text" class="form-control bg-white text-primary" value="{{ $user->acc_noS }}" readonly>
                  </div>
                  @endif

                </div>
              </div>
            </div>
          </div>
        </div>  <!-- end of modal Viewuser Details-->

        <!-- Update user Details -->
        <div class="modal fade" id="updateUser_{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog modal-dialog-centered modal-md" role="document">

            <div class="modal-content nunito-font border border-custom-dark rounded-0">
              <div class="modal-header overview-item--c2 text-center">
                <label class="modal-title nunito-font text-white  nunito-font">
                  <i class="fa fa-info-circle"></i>
                  Update details of staff member {{ $user->name }}
                </label>
                <button type="button" class="close view-close
                text-white text-right" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="modal-body">
              <div class="modal-body">
                <form action="{{ Route('users.update', $user->id)}}" method="POST">
                  @method('PATCH')
                  @csrf
                  <div class="form-group">
                    <span class="text-muted">First Name</span>
                    <input type="text" class="form-control bg-white"  value="{{ $user->first_name }}" name="firstName" required>
                  </div>
                  
                  <div class="form-group">
                    <span class="text-muted">Middle Name</span>
                    <input type="text" class="form-control bg-white"  value="{{ $user->middle_name }}" name="middleName">
                  </div>
                  
                  <div class="form-group">
                    <span class="text-muted">Last Name</span>
                    <input type="text" class="form-control bg-white"  value="{{ $user->last_name }}" name="lastName" required>
                  </div>

                  <div class="form-group">
                    <span class="text-muted">Username</span>
                    <input type="text" class="form-control bg-white"  value="{{ $user->username }}" name="username" required>
                  </div>

                  <div class="form-group">
                    <span class="text-muted">Gender</span>
                    <input type="text" class="form-control bg-white"  value="{{ $user->gender }}" name="gender" required>
                  </div>

                  <div class="form-group">
                    <span class="text-muted">Email</span>
                    <input type="text" class="form-control bg-white"  
                    value="{{ $user->email }}" name="email" required>
                  </div>

                  <div class="form-group">
                    <span class="text-muted">Occupation</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->occupation }}" name="occupation" required>
                  </div>
                  
                  @if($user->company)
                  <div class="form-group">
                    <span class="text-muted">Company</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->company }}" name="company">
                  </div>
                  @endif


                  <div class="form-group">
                    <span class="text-muted">Telno</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->tel_no }}" name="mobileno" required>
                  </div>

                  <div class="form-group">
                    <span class="text-muted">Alternative Telno</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->alt_telno }}" name="mobileno2" placeholder="Enter your alternative tel number" required>
                  </div>

                  <div class="form-group">
                    <span class="text-muted">Address</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->address }}" name="address" required>
                  </div>

                  <div class="form-group">
                    <span>City</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->city }}" name="city" required>
                  </div>

                  <div class="form-group">
                    <span class="text-muted">State</span>
                    <input type="text" class="form-control bg-white text-dark" value="{{ $user->state }}" name="state" required>
                  </div>


                  <div class="form-group">
                    <span class="text-muted">Date of Birth</span>
                    <input type="date" class="form-control bg-white text-dark" value="{{ $user->date_of_birth }}" name="dob" required>
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
      </div>  <!-- end of modal updateuserDetails-->

    </tr>
    @endforeach

  </tbody>
</table>

<!--Import Staff-->
<div class="modal fade" id="importStaff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content nunito-font">

      <form action="{{ Route('staff.import') }}" method="post"
      enctype="multipart/form-data">
      @csrf

      <div class="modal-header text-center">
        <h5 class="modal-title w-100 font-weight-bold">
          Import staff members from an excel file
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="form-group">
          <span>Select file for Upload</span>
        </div>

        <div class="form-group">
          <input type="file" class="form-control-file @error('select_file') is-invalid @enderror" name="select_file" Required autofocus>
        </div>

        @error('select_file')
        <div class='alert alert-danger alert-dismissible text-center' role='alert'>
         <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
          <span aria-hidden='true'>&times;</span></button>
          <strong>Sorry!</strong> {{ $message }}
        </div>
        @enderror

        <div class="form-group">
          <button type="submit" class="btn overview-item--c2 text-white"  name="AddItemBtn">Upload</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
</div>
</div>
</div>



<script>
  $(document).ready(function(){
    var title = "List of all staff members in the system";
    var table = $('.StaffMembersTable');
    var columns = [0,1,2,3,4];
    dirtyTable(table,title,columns);
  })
</script>
@endsection



