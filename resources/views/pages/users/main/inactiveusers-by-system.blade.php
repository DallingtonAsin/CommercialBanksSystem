@extends('layouts.sidebar-header')

@section('content')


<div class="panel panel-default nunito-font">
 <div class="panel-heading"> 
  <img src="{{ asset('images/icon/user-list.png') }}" class="icon-i text-info" />
  <strong class="text-info nunito-font">
    Deactivated users by system
  </strong>
  <span class="badge bg-success text-white">
    @isset($number_of_inactiveUsers)
    {{ $number_of_inactiveUsers }}
    @endisset
  </span>
</div>

<div class="panel-body">

  <div class="text-center">
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
    <table class="table table-borderless  table-data3 SystemUsersTable">
      <thead>
        <tr class="success tr-dark nunito-font">
          <th>Id</th>
          <th>Name</th>
          <th>Email</th>
          <th>Contact</th>
          <th>Address</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody class="nunito-font">
        @foreach($users as $user)
        <tr>
          <td>{{ $user->id }}</td>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->tel_no }}</td>
          <td>{{ $user->address }}</td>
          <td class="text-center">
           <a href="{{ Route('user.changestatus', ['id' => $user->id, 'status' => $user->active, 'name' => $user->name]) }}" class="btn-status  btn btn-sm btn-success nunito-font">
             <i class="fa fa-check text-warning"></i> {{__('Activate') }}</a>
           </td>
         </tr>
         @endforeach

       </tbody>
     </table>
   </div>
 </div>

</div>

<script>
  $(document).ready(function(){
    var title = "List of all users in the system";
    var table = $('.SystemUsersTable');
    var columns = [0,1,2,3,4];
    dirtyTable(table,title,columns);
  })
</script>
@endsection



