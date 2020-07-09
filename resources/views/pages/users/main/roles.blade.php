@extends('layouts.sidebar-header')

@section('content')


  <div class="panel panel-default">
     <div class="panel-heading">
     <div class="row nunito-font">
       <div class="col-lg-4">
        <img src="{{ asset('images/icon/user-list.png') }}" class="icon-i text-info" />
        <strong class="text-info">Manage roles</strong>
        <span class="badge bg-success text-white">
          @isset($number_of_roles)
          {{ $number_of_roles }}
          @endisset
        </span>
      </div>

      <div class="col-lg-6 pt-2">
        <label>

        
       </label>
     </div>

     <div class="col-lg-2">
       <div class="btn-group">
        <button type="button" class="btn btn-secondary text-info bolded
        border-info form-control text-center dropdown-toggle downloadfilebtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Action
      </button>
      <ul class="dropdown-menu">
         <li>
           <a href="" class="text-info" class="add-link text-decoration-none" data-toggle="modal" data-target="#AddRole"> Add role</a>
        </li>
        <li>
          <a href="" class="text-info" data-toggle="modal" data-target="#importRoles">
          Import roles
         </a>
        </li>
       
      </ul>
    </div>
  </div>

</div>

</div>
  <div class="panel-body">
 <div class="col-lg-12 text-center">
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
    <strong>Sorry!</strong> {{ session()->get('fail') }}
  </div>
  @endif

  @if(session()->get('warning'))
  <div class='alert alert-warning alert-dismissible' role='alert'>
   <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span></button>
    <strong>Warning!</strong> {{ session()->get('warning') }}
  </div>
  @endif
</div>


<div class="table-responsive m-b-40">
  <table class="table table-bordered table-data3 rolesTable">
    <thead>
      <tr class="tr-dark success">
        <th>RoleId</th>
        <th>Role</th>
        <th class="text-left">is Admin</th>
        <th>is SuperAdmin</th>
         @can('isSuperAdmin')
        <th class="text-center">Action</th>
        <th>isAdmin action</th>
        <th>isSuperAdmin action</th>
        @endcan
      </tr>
    </thead>
    <tbody>

      @isset($roles)

      @foreach($roles as $role)

      <tr>
        <td>{{ $role->role_id }}</td>
        <td>{{ $role->role }}</td>
        <td>
          @if($role->is_admin == 1)
          <span class="text-success">{{ __('Yes') }}</span>
          @else
          <span class="trash-btn">{{ __('No') }}</span>
          @endif
        </td>

        <td>
          @if($role->isSuperAdmin == 1)
          <span class="text-success">{{ __('Yes') }}</span>
          @else
          <span class="trash-btn">{{ __('No') }}</span>
          @endif
        </td>

      @can('isSuperAdmin')  
        <td class="text-center">
          <a href="" class="text-decoration-none" data-toggle="modal"
          data-target="#updaterole_{{ $role->role_id }}">
          <i class="glyphicon glyphicon-pencil edit-btn"></i> Edit
        </a>

        <a href="" class="text-decoration-none pl-5"  data-toggle="modal" data-target="#deleterole_{{ $role->role_id }}" >
          <i class="glyphicon glyphicon-trash trash-btn"></i> Delete
        </a>
      </td>

      <td class="text-center">
       @if($role->is_admin == 1)
       <a href="{{ Route('adminPriv',['id' => $role->role_id, 'priv' => $role->is_admin, 'role' => $role->role]) }}"  class="text-danger nunito-font"><i class="fa fa-arrow-circle-down"></i> {{__('lower privileges') }}</a>
       @else
       <a href="{{ Route('adminPriv',['id' => $role->role_id, 'priv' => $role->is_admin, 'role' => $role->role]) }}"  class="text-success nunito-font"><i class="fa fa-arrow-circle-up"></i> {{__('raise privileges') }}</a>
       @endif
     </td>


     <td class="text-center">
       @if($role->isSuperAdmin == 1)
       <a href="{{ Route('superAdminPriv',['id' => $role->role_id, 'priv' => $role->isSuperAdmin, 'role' => $role->role]) }}"  class="text-danger nunito-font"><i class="fa fa-arrow-circle-down"></i> {{__('lower privileges') }}</a>
       @else
       <a href="{{ Route('superAdminPriv', ['id' => $role->role_id, 'priv' => $role->isSuperAdmin, 'role' => $role->role]) }}"  class=" text-success nunito-font"><i class="fa fa-arrow-circle-up"></i> {{__('raise privileges') }}</a>
       @endif
     </td>
     @endcan



@can('isSuperAdmin')
     <!--Modal Deleterole Record -->
     <div class="modal fade" id="deleterole_{{ $role->role_id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header  overview-item--c2 text-center">
            <h6 class="modal-title w-100  text-white nunito-font">Delete Role</h6>
            <button type="button" class="close text-white text-right" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <div class="text-center">
               <label class="text-danger">
                Are you sure you want to delete role 
                <span class="text-dark text-muted bolded">
                 {{ $role->role }}
               </span> 
               ?
             </label>
           </div>
         </div>

         <div class="form-group">
           <form action="{{ route('roles.destroy', $role->role_id) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div> <!-- end of modal Delete role Record-->
@endcan



@can('isSuperAdmin')
<!-- Update role Details -->
<div class="modal fade" id="updaterole_{{ $role->role_id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header overview-item--c2 text-center">
        <label class="modal-title nunito-font text-white  nunito-font">
          <i class="fa fa-info-circle"></i>
          Update a role
        </label>
        <button type="button" class="close view-close
        text-white text-right" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <div class="modal-body">
        <form action="{{ Route('roles.update', $role->role_id)}}" method="POST">
          @method('PATCH')
          @csrf
          <div class="form-group">
            <span>Role</span>
            <input type="text" name="Role" class="form-control bg-white text-left text-dark" value="{{ $role->role }}" placeholder="Add a role">
          </div>

          <div class="form-group">
            <input type="submit" name="submit" value="Update Role" class="btn overview-item--c2 text-white">
          </div>
          
        </form>

      </div>
    </div>
  </div>
</div>
</div>  <!-- end of modal updateroleDetails-->
@endcan


</tr>
@endforeach
@endisset

</tbody>
</table>
</div>
</div>
<!-- View AddRole -->
<div class="modal fade" id="AddRole" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title nunito-font text-white nunito-font">
          <i class="fa fa-info-circle text-info"></i>
          Add a role
        </h6>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="modal-body">
          <form action="{{ Route('roles.store') }}" method="POST">
            @csrf
          <div class="form-group">
            <span>Role</span>
            <input type="text" name="role" class="form-control bg-white text-left text-dark" placeholder="Add a role">
          </div>

            <div class="row form-group">
          <div class="col-lg-6">
            <input type="checkbox" name="isAdmin" value="">
            <span>isAdmin</span>
          </div>

          <div class="col-lg-6">
            <input type="checkbox" name="isSuperAdmin" value="" >
            <span>IsSuperAdmin</span>
          </div>
        </div>

          <div class="form-group">
            <input type="submit" name="submit" value="Add Role" class="btn overview-item--c2 text-white">
          </div>


      </form>

        </div>
      </div>
    </div>
  </div>
</div>  <!-- end of modal AddRole-->

</div>


<!--Import Roles -->
<div class="modal fade" id="importRoles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content nunito-font">

      <form action="{{ Route('roles.import') }}" method="post"
      enctype="multipart/form-data" name="inportRolesForm" >
      @csrf

      <div class="modal-header overview-item--c2 text-center">
        <h6 class="modal-title w-100 nunito-font font-weight-bold text-white">Import roles from an  excel file</h6>
        <button type="button" class="close view-close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="form-group nunito-font">
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



<script>
  $(document).ready(function(){
    var title = "List of all roles in the system";
    var table = $('.rolesTable');
    var columns = [0,1,2,3];
    dirtTable(table,title,columns);
  });
</script>
@endsection



