@extends('layouts.sidebar-header')

@section('content')

  

<div class="row pt-4">
  <div class="col-md-12">
          <div class="panel panel-default nunito-font">
        <div class="panel-heading pt-4">
          <div class="panel-title row">
         
       <div class="col-lg-9">
         <a href=""  class="add-link text-decoration-none">
          <i class="fa fa-gear"></i>
           Retirement A/C Settings
        </a>
      </div>


      @can('isAdmin')
      <div class="col-lg-3 dropdown">
         <div class="btn-group">
        <button type="button" class="btn border-info text-info form-control text-center dropdown-toggle downloadfilebtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Action
       </button>
       <ul class="dropdown-menu">
        <li>
           <a href="" class="text-decoration-none" data-toggle="modal" data-target="#addAccSetting"> Add Setting 
        </a>
          <a href="" class="text-decoration-none" data-toggle="modal" data-target="#addAccSetting"> Import settings 
        </a>
        
        </li>
      </ul>
    </div>
      </div>
  @endcan

</div>
</div>

<div class="panel-body">


 <div class="col-lg-12 text-center nunito-font">
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
    <strong>Sorry!</strong> {{ session()->get('warning') }}
  </div>
  @endif


</div>



<div class="table-responsive m-b-40">
  <table class="table table-borderless table-data3 settingsTable">
    <caption>
     <span class="text-success f-400 nunito-font">
      Retirement Account Settings
     </span> 
   </caption>
   <thead>
    <tr class="tr-dark success">
      <th>No</th>
      <th>Setting key</th>  
      <th>Setting value</th>
      <th class="text-left">Action</th>
    </tr>
  </thead>
  <tbody>


   @isset($data)

   @foreach($data as $row)
   <tr>
    <td>{{ $row->id }}</td>
    <td>{{ $row->setting_key }}</td>
    <td>{{ number_format($row->setting_value) }}</td>
    <td class="text-left">
      @can('isAdmin')
      <a href="" class="text-decoration-none" data-toggle="modal"
      data-target="#updateAccSetting_{{ $row->id }}">
      <i class="glyphicon glyphicon-pencil edit-btn"></i>
    </a>

    <a href="" class="text-decoration-none pl-5"  data-toggle="modal" data-target="#deleteAccSetting_{{ $row->id }}" >
      <i class="glyphicon glyphicon-trash trash-btn"></i>
    </a>
    @endcan
    <a href="" class="text-decoration-none pl-5" data-toggle="modal"
    data-target="#viewAccSetting_{{ $row->id }}">
    <i class="fa fa-eye text-success"></i>
  </a>
</td>

<!--Modal Delete Account Setting -->
<div class="modal fade" id="deleteAccSetting_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title w-100  text-white nunito-font">Delete  Account Setting record</h6>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="form-group">
          <div class="text-center">
           <label class="text-danger">Are you sure you want to delete account setting   <small class="text-dark text-muted bolded">
            {{ $row->setting_key }}
          </small>
          ?
        </label>
      </div>
    </div>

    <div class="form-group">
     <form action="{{ route('retirement-settings.destroy', $row->id) }}" method="post">
      @csrf
      @method('DELETE')
      <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
    </form>
  </div>


</div>
</div>
</div>
</div> <!-- end of modal Delete Account Setting-->


<!-- Add Account Setting details -->
<div class="modal fade" id="addAccSetting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title nunito-font text-white nunito-font">
          <i class="fa fa-info-circle"></i>
          Add account setting
        </h6>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="POST" action="{{ Route('retirement-settings.store') }}">
          @csrf
          <div class="form-group">
            <span class="text-muted">Setting Key</span>
            <input type="text" class="form-control bg-white text-dark" placeholder="Enter setting key" name="Skey" required>
          </div>

          <div class="form-group">
            <span class="text-muted">Setting value</span>
            <input type="text" class="form-control bg-white text-left text-dark" placeholder="Enter setting value" name="Svalue" required>
          </div>

          <div class="form-group">
            <button ype="submit" class="form-control btn overview-item--c2 text-white" name="">
              <strong>Add Setting</strong>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>  <!-- end of modal Add Account Setting-->


<!-- View Account Setting Details -->
<div class="modal fade" id="viewAccSetting_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title nunito-font text-white nunito-font">
          <i class="fa fa-info-circle"></i>
          Details of a record of account setting
        </h6>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="modal-body">
          <div class="form-group">
            <span class="text-muted">Setting Key</span>
            <input type="text" class="form-control bg-white text-dark" value="{{ $row->setting_key }}" readonly>
          </div>

          <div class="form-group">
            <span class="text-muted">Setting value</span>
            <input type="text" class="form-control bg-white text-left text-dark" 
            value="{{ $row->setting_value }}" readonly>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>  <!-- end of modal View Account Setting-->

<!-- Update Account Setting Details -->
<div class="modal fade" id="updateAccSetting_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header overview-item--c2 text-center">
        <label class="modal-title nunito-font text-white  nunito-font">
          <i class="fa fa-info-circle"></i>
          Update details of a record of account setting 
        </label>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="modal-body">
          <form action="{{ Route('retirement-settings.update', $row->id)}}" method="POST">
            @method('PATCH')
            @csrf
            <div class="form-group">
              <span>Setting Key</span>
              <input type="text" class="form-control bg-white text-dark" value="{{ $row->setting_key }}" name="SettingKey">
            </div>

            <div class="form-group">
              <span>Setting value</span>
              <input type="number" class="form-control bg-white text-left text-dark" name="SettingValue" value="{{ $row->setting_value }}">
            </div>

            <div class="form-group">
              <button ype="submit" class="form-control btn overview-item--c2 text-white" name="">
                <strong>Update Setting</strong>
              </button>
            </div>
          </form>

        </div>


      </div>
    </div>
  </div>
</div>  <!-- end of modal updateAccountSetting-->


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



<script>

  $(document).ready(function(){
    var account = "retirement";
    var title = "List of "+account+" settings";
    var table = $('.settingsTable');
    var columns = [0,1,2];
    dirtyTable(table, title, columns);
  });

</script>




@endsection



