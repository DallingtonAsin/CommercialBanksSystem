@extends('layouts.sidebar-header')

@section('content')

<div class="row pt-3 pl-0 nunito-font">

  <div class="col-lg-12">
   <strong class="f-15">
    <i class="text-danger">*</i> <small> You can filter results for a particular data range by
    selecting start & end dates and by clicking view button</small>
  </strong>

  @can('isMember')
  <label class="pl-3">
    <a href="{{ Route('payments.index') }}"
    class="f-14 text-dark nunito-font">
    <i class="fa fa-play-circle text-success pr-1"></i>
  Pay monthly contribution</a>
</label>
@endcan 

</div>
</div>


<form class="form form-inline nunito-font pt-1" action="{{ Route('education.search') }}" method="POST">
  @csrf
  <div class="row">

   @can('isAdmin')
    <div class="form-group ml-4">
    <span class="f-13 pl-2">Account No</span>
    <input type="text" name="accountNo" class="form-control" placeholder="Enter Account Number" value="{{ old('accountNo') }}" id="accountNo"
  autocomplete="off" >
  </div>
 @endcan

    <div class="form-group ml-4">
      <span class="f-13">Start date</span>
      <input type="date" name="startDate" class="form-control"
      value="{{ old('startDate') }}" >
    </div>
    <div class="form-group">
     <span class="pl-4 f-13">End date</span>
     <input type="date" name="endDate" class="form-control"
     value="{{ old('endDate') }}" >
   </div>

   <div class="pl-4 form-group">
    <button type="submit" class="form-control nunito-font btn overview-item--c2 border-white text-white">View results
    </button>
    @isset($filtered_data)
    <a href="{{ Route('education.home')}}" 
    class="nunito-font bolded pl-2 f-14">
    Load all
  </a>
  @endisset
</div>

</div> 
</form>

<div class="row pt-1 nunito-font">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row nunito-font pt-2">
         <div class="col-lg-3">
           <a href=""  class="add-link text-decoration-none">
            <strong>Education Savings</strong>
            <span class="badge overview-item--c2 text-white">
              @isset($num_of_transactions)
              {{ number_format($num_of_transactions) }}
              @endisset
            </span>
          </a>
        </div>

        <div class="col-lg-3">
          <label>Deposits: shs.</label>
          <label class="text-success">
            @isset($totalCredit)
            {{ number_format($totalCredit) }}
            @endisset
          </label>
        </div>

        <div class="col-lg-3">
          <label>Withdrawals: shs.</label>
          <label class="text-danger">
           @isset($totalDebt)
           {{ number_format($totalDebt) }}
           @endisset
         </label>
       </div>

       @can('isMember')
       <div class="col-lg-3">
        <label>Balance: shs.</label>
        <label class="text-success">
         @if(isset($totalCredit) && isset($totalDebt))
         {{ number_format($totalCredit-$totalDebt) }}
         @endisset
       </label>
     </div>
     @endcan

     @can('isAdmin')
     <div class="col-lg-2 pull-right">
       <div class="btn-group">
        <button type="button" class="btn border-info text-info form-control text-center dropdown-toggle downloadfilebtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         Action
       </button>
       <ul class="dropdown-menu">
        <li>
          <a href="" data-toggle="modal" data-target="#importEducationSavings" class="text-info">Import savings</a>
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
</div>



<div class="table-responsive m-b-40">
  <table class="table table-borderless table-data3 educationSavingsTable">
    <caption>
     <span class="text-success f-400 nunito-font">
       Education Savings Account

     </span> 
   </caption>
   <thead>
    <tr class="tr-dark success">
      <th>No</th>
      <th>date</th>
      @can('isAdmin')
      <th>A/C No</th>
      @endcan
      <!-- <th>Particulars</th> -->
      <th>details</th>
      <th>Deposit(+)</th>
      <th>Withdrawal(-)</th>   
      <th>Balance</th>
      <th class="text-left">Action</th>

    </tr>
  </thead>
  <tbody>

   @php
   (isset($filtered_data))
   ? $dataRes = $filtered_data
   : $dataRes = $data;
   @endphp

   @isset($dataRes)

   @foreach($dataRes as $row)
   <tr>
    <td>{{ $row->id }}</td>
    <td>{{ date('d-m-Y', strtotime($row->date)) }}</td>
    @can('isAdmin')
    <td>{{ $row->acc_no }}</td>
    @endcan
    <!-- <td>{{ $row->type }}</td> -->
    <td class="process">{{ $row->description }}</td>
    <td class="text-left">{{ number_format($row->deposit) }}</td>
    <td class="text-left">{{ number_format($row->withdrawal) }}</td>
    <td class="text-left">{{ number_format($row->balance) }}</td>
    <td class="text-center">
      @can('isAdmin')
      <a href="" class="text-decoration-none" data-toggle="modal"
      data-target="#updateEducationSaving_{{ $row->id }}">
      <i class="glyphicon glyphicon-pencil edit-btn"></i>
    </a>

    <a href="" class="text-decoration-none pl-3"  data-toggle="modal" data-target="#deleteMemberSaving_{{ $row->id }}" >
      <i class="glyphicon glyphicon-trash trash-btn"></i>
    </a>
    @endcan
    <a href="" class="text-decoration-none pl-3" data-toggle="modal"
    data-target="#viewEducationSaving_{{ $row->id }}">
    <i class="fa fa-eye text-success"></i>
  </a>
</td>

<!--Modal DeleteEducationSavingRecord -->
<div class="modal fade" id="deleteMemberSaving_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title w-100  text-white nunito-font">Delete Education Saving Account record</h6>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="form-group">
          <div class="text-center">
           <label class="text-danger">Are you sure you want to delete this saving by   <small class="text-dark text-muted bolded">
             {{ $row->acc_name }}
           </small>
           ?
         </label>
       </div>
     </div>

     <div class="form-group">
       <form action="{{ route('education.destroy', $row->id) }}" method="post">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
      </form>
    </div>
  </div>
</div>
</div>
</div> <!-- end of modal Delete EducationSaving-->

<!-- View Education Saving Details -->
<div class="modal fade" id="viewEducationSaving_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title nunito-font text-white nunito-font">
          <i class="fa fa-info-circle"></i>
          Details of a record of @can('isMember') your @endcan Education Saving Account
        </h6>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="modal-body">
          <div class="form-group">
            <span>Date</span>
            <input type="text" class="form-control bg-white text-dark" value="{{ $row->date }}" readonly>
          </div>

          <div class="form-group">
            <span>Member</span>
            <input type="text" class="form-control bg-white text-left text-dark" 
            value=" {{ $row->acc_name }}" readonly>
          </div>

          <div class="form-group">
            <span>Type</span>
            <input type="text" class="form-control bg-white text-left text-dark" 
            value="{{ $row->type }}" readonly>
          </div>

          <div class="form-group">
            <span>Description</span>
            <input type="text" class="form-control bg-white text-dark"  value="{{  $row->description }}" readonly>
          </div>


          <div class="form-group">
            <span>Deposit</span>
            <input type="text" class="form-control bg-white text-dark"  
            value="{{ number_format($row->deposit) }}" readonly>
          </div>

          <div class="form-group">
            <span>Withdrawal</span>
            <input type="text" class="form-control bg-white text-dark"  value="{{ number_format($row->withdrawal) }}" readonly>
          </div>


          <div class="form-group">
            <span>Balance</span>
            <input type="text" class="form-control bg-white text-dark"  value="{{ number_format($row->balance) }}" readonly>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>  <!-- end of modal ViewEducationSaving-->

<!-- Update Education Saving Details -->
<div class="modal fade" id="updateEducationSaving_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header overview-item--c2 text-center">
        <label class="modal-title nunito-font text-white  nunito-font">
          <i class="fa fa-info-circle"></i>
          Update details of an Education Saving Account
        </label>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="modal-body">
          <form action="{{ Route('education.update', $row->id)}}" method="POST">
            @method('PATCH')
            @csrf
            <div class="row">
              <div class="form-group col-lg-6">
                <span>Date</span>
                <input type="text" class="form-control text-dark bg-white" value="{{ $row->date }}" readonly>
              </div>

              <div class="form-group col-lg-6">
                <span>Member</span>
                <input type="text" class="form-control bg-white text-left text-dark" 
                value=" {{ $row->acc_name }}" readonly>
              </div>
            </div>

            <div class="form-group">
              <span>Type</span>
              <input type="text" name="type" class="form-control text-left text-dark" 
              value="{{ $row->type }}">
            </div>

            <div class="form-group">
              <span>Description</span>
              <input type="text" name="description" class="form-control  text-dark"  value="{{  $row->description }}">
            </div>

            <div class="form-group">
              <span>Deposit</span>
              <input type="number" name="credit" class="form-control text-dark"  
              value="{{ $row->deposit }}">
            </div>

            <div class="form-group">
              <span>Withdrawal</span>
              <input type="number" name="debt" class="form-control text-dark"  value="{{ $row->withdrawal }}">
            </div>



            <div class="form-group">
              <span>Balance</span>
              <input type="number" name="balance" class="form-control text-dark"  value="{{ $row->balance }}">
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
</div>  <!-- end of modal updateEducationSaving-->


</tr>

@endforeach
@endisset

</tbody>
</table>



@can('isAdmin')
<!--Import Education Savings -->
<div class="modal fade" id="importEducationSavings" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content nunito-font">

      <form action="{{ Route('education-savings.import') }}" method="post"
      enctype="multipart/form-data" name="inportMainSavingsForm" >
      @csrf

      <div class="modal-header overview-item--c2 text-center">
        <h6 class="modal-title w-100 nunito-font font-weight-bold text-white">Import education savings from an  excel file</h6>
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
@endcan

</div>
</div>
</div>
</div>
</div>   


@can('isAdmin')
<script>
  $(document).ready(function(){

     $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var query = $('#accountNo').val();
    $("#accountNo").typeahead({
      source:function(query,result){
        $.ajax({
          url:"{{ Route('educaccount.search') }}",
          method:'post',
          data:{
            query:query,
          },
          dataType:'json',
          success: function(data){
            result($.map(data, function(item){
              return item;
            }));
          },
          error:function(data){
            console.log(data);
          },
        });
      }
    });

    var title = "<h2 class='text-info text-center'>Education Savings A/C Statement</h2><br><strong>Date: {{ now() }}</strong>";
    var table = $('.educationSavingsTable');
    var columns = [0,1,2,3,4,5,6];
    dirtyTable(table,title,columns);
    
  });

</script>
@endcan


@can('isMember')
<script>
  $(document).ready(function(){
    var account = "Educ";
    var title = "<h2 class='text-info text-center'>Education Savings A/C Statement</h2> "+ "<br> <div class='text-center'>Member's Name: <span class='text-success'>{{ Auth::user()->name }}</span></div><div class='text-center pt-3'>Member's A/C NO: <span class='text-success'> {{ Auth::user()->acc_noE }}</span></div> <br><strong>Date: {{ now() }}</strong>";
    var table = $('.educationSavingsTable');
    var columns = [0,1,2,3,4,5];
    AccountsTable(table,title,account,columns);
  });
</script>
@endcan



@endsection



