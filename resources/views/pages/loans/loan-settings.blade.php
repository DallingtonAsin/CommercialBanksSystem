@extends('layouts.sidebar-header')

@section('content')

  <div class="card nunito-font">
    <div class="card-header">
     <div class="row nunito-font">
       <div class="col-lg-4">
        <img src="{{ asset('images/icon/user-list.png') }}" class="icon-i text-info" />
        <strong class="text-info">
          @can('isAdmin') Manage loan interest rates @endcan
           @can('isMember') Loan interest rates @endcan
        </strong>
        <span class="badge bg-success text-white">
          @isset($no_of_settings)
          {{ $no_of_settings }}
          @endisset
        </span>
      </div>

      @can('isAdmin')
      <div class="col-lg-4">
        <label>
          <a href="" class="pl-4 text-info"
         data-toggle="modal" data-target="#addSetting">
           Add new interest rate
         </a>
       </label>
      </div>

      <div class="col-lg-2">
       <div class="btn-group">
        <button type="button" class="btn border-info text-info form-control text-center dropdown-toggle downloadfilebtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         Action
       </button>
       <ul class="dropdown-menu">
        <li>
          <a href="" data-toggle="modal" data-target="#importLoanRates">Import loan settings</a>
        </li>
      </ul>
    </div>
  </div>
  @endcan

</div>
</div>

<div class="card-body">

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
</div>


<div class="table-responsive m-b-40">
  <table class="table table-borderless table-data3 loanInterestRatesTable">
    <thead>
      <tr class="tr-dark success">
        <th>Id</th>
        <th>Min Amount</th>
        <th>Max Amount</th>
        <th>Range</th>
        <th>Interest Rate</th>
        <th class="text-left">Action</th>
      </tr>
    </thead>
    <tbody>

      @isset($rows)

      @foreach($rows as $row)

      <tr>
        <td>{{ $row->id }}</td>
        <td>{{ number_format($row->min_loanamt) }}</td>
        <td>{{ number_format($row->max_loanamt) }}</td>
        <td>{{ number_format($row->min_loanamt) }}-{{ number_format($row->max_loanamt) }}</td>
        <td>{{ $row->interest_rate }}</td>

        <td class="text-center">
         <a href="" class="text-success"
         data-toggle="modal" data-target="#viewSetting_{{ $row->id }}"><i class="fa fa-eye pr-2" ></i>View</a>
         @can('isAdmin')
          <a href="" class="pl-4 text-info"
         data-toggle="modal" data-target="#updateSetting_{{ $row->id }}"><span class="glyphicon glyphicon-pencil pr-2" ></span>Edit</a>
         <a href="" class="pl-4 trash-btn"
         data-toggle="modal" data-target="#deleteSetting_{{ $row->id }}"><span class="glyphicon glyphicon-trash pr-2" ></span>Delete</a>
         @endcan
       </td>

     </td>



@can('isLoansManager')
  <!--Modal Deleterow Record -->
     <div class="modal fade" id="deleteSetting_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content nunito-font">
          <div class="modal-header  overview-item--c2 text-center">
            <h6 class="modal-title w-100  text-white nunito-font">
              Delete loan interest rate
            </h6>
            <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <div class="text-center">
               <label class="text-danger">
                Are you sure you want to delete  
                loan interest rate for<br>
               <span class="text-success">
                {{ number_format($row->min_loanamt) }}-{{ number_format($row->max_loanamt) }}</span>
               ?
             </label>
           </div>
         </div>

         <div class="form-group">
           <form action="{{ route('loan-settings.destroy', $row->id) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div> <!-- end of modal Delete row Record-->
@endcan


<!-- View row Details -->
<div class="modal fade" id="viewSetting_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title nunito-font text-white nunito-font">
          <i class="fa fa-info-circle"></i>
          Details of a loan interest for range {{ number_format($row->min_loanamt) }}-{{ number_format($row->max_loanamt) }} 
        </h6>
        <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="form-group">
          <span class="text-muted">Minimum Amount</span>
          <input type="text" class="form-control bg-white text-left text-dark" 
          value="{{ $row->min_loanamt }}" readonly>
        </div>

        <div class="form-group">
          <span class="text-muted">Maximum Amount</span>
          <input type="text" class="form-control bg-white"  
          value="{{ $row->max_loanamt }}" readonly>
        </div>

        <div class="form-group">
          <span class="text-muted">Interest rate</span>
          <input type="text" class="form-control bg-white"  
          value="{{ $row->interest_rate }}" readonly>
        </div>
      </div>
    </div>
  </div>
</div>  <!-- end of modal Viewrow Details-->


@can('isLoansManager')
<!-- Update row Details -->
<div class="modal fade" id="updateSetting_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header overview-item--c2 text-center">
        <label class="modal-title nunito-font text-white f-16 nunito-font">
          <i class="fa fa-info-circle"></i>
          Update loan interest rate for range
          {{ number_format($row->min_loanamt) }}-
          {{ number_format($row->max_loanamt) }} 
        </label>
        <button type="button" class="close view-close
        text-white text-right" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <div class="modal-body">
        <form action="{{ Route('loan-settings.update', $row->id)}}" method="POST">
          @method('PATCH')
          @csrf
          <div class="form-group">
          <span class="text-muted">Minimum Amount</span>
          <input type="text" class="form-control bg-white text-left text-dark"  value="{{ $row->min_loanamt }}" name="minAmt" >
        </div>

        <div class="form-group">
          <span class="text-muted">Maximum Amount</span>
          <input type="text" class="form-control bg-white"  
          value="{{ $row->max_loanamt }}" name="maxAmt">
        </div>

        <div class="form-group">
          <span class="text-muted">Interest rate</span>
          <input type="text" class="form-control bg-white"  
          value="{{ $row->interest_rate }}" name="rate">
        </div>

          <div class="form-group">
            <button ype="submit" class="form-control btn overview-item--c2 text-white" name="">
              <strong>Update setting</strong>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>  <!-- end of modal update row Details-->
@endcan


@can('isLoansManager')
<!-- Add loan interest Details -->
<div class="modal fade" id="addSetting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header overview-item--c2 text-center">
        <label class="modal-title nunito-font text-white f-16 nunito-font">
          <i class="fa fa-info-circle"></i>
          Add loan interest rate for specific range
        </label>
        <button type="button" class="close view-close
        text-white text-right" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <div class="modal-body">
        <form action="{{ Route('loan-settings.store')}}" method="POST">
          @csrf
          <div class="form-group">
          <span class="text-muted">Minimum Amount</span>
          <input type="text" placeholder="Enter minimum amount for range" 
          class="form-control bg-white text-left text-dark" name="minAmt" >
        </div>

        <div class="form-group">
          <span class="text-muted">Maximum Amount</span>
          <input type="text" class="form-control bg-white"
          placeholder="Enter maximum amount for range" name="maxAmt">
        </div>

        <div class="form-group">
          <span class="text-muted">Interest rate</span>
          <input type="text" class="form-control bg-white"
          placeholder="Enter interest rate for range" name="rate">
        </div>

          <div class="form-group">
            <button ype="submit" class="form-control btn overview-item--c2 text-white" name="">
              <strong>Add loan interest rate</strong>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>  <!-- end of modal storeDetails-->
@endcan


@can('isAdmin')
<!--Import Roles -->
<div class="modal fade" id="importLoanRates" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content nunito-font">

      <form action="{{ Route('loan-rates.import') }}" method="post"
      enctype="multipart/form-data" name="inportLoanInterestRatesForm" >
      @csrf

      <div class="modal-header overview-item--c2 text-center">
        <h6 class="modal-title w-100 nunito-font font-weight-bold text-white">Import loan interest rates from an  excel file</h6>
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


@cannot('isLoansManager')
<!--Modal No permissions -->
<div class="modal fade" id="updateSetting_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title w-100  text-white nunito-font">
       <i class="fa fa-warning text-warning pr-2"></i> Permissions Denied!</h6>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <div class="text-left">
           <label class="text-danger nunito-font">
            Sorry<span class="text-dark">
             {{ Auth::user()->name }}</span>, you don't have permissions
             to edit any interest rate for loans. 
             <span class="text-dark">Only Loans Manager can!</span>
           </label>
         </div>
       </div>
    </div>
  </div>
</div>
</div> <!-- end of modal No Permissions to delete loan record-->
@endcannot

@cannot('isLoansManager')
<!--Modal No permissions -->
<div class="modal fade" id="deleteSetting_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title w-100  text-white nunito-font">
       <i class="fa fa-warning text-warning pr-2"></i> Permissions Denied!</h6>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <div class="text-left">
           <label class="text-danger nunito-font">
            Sorry<span class="text-dark">
             {{ Auth::user()->name }}</span>, you don't have permissions
             to delete any interest rate for loans. 
             <span class="text-dark">Only Loans Manager can!</span>
           </label>
         </div>
       </div>
    </div>
  </div>
</div>
</div> <!-- end of modal No Permissions to delete loan record-->
@endcannot

@cannot('isLoansManager')
<!--Modal No permissions -->
<div class="modal fade" id="addSetting" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title w-100  text-white nunito-font">
       <i class="fa fa-warning text-warning pr-2"></i> Permissions Denied!</h6>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <div class="text-left">
           <label class="text-danger nunito-font">
            Sorry<span class="text-dark">
             {{ Auth::user()->name }}</span>, you don't have permissions
             to add any interest rate for loans. 
             <span class="text-dark">Only Loans Manager can!</span>
           </label>
         </div>
       </div>
    </div>
  </div>
</div>
</div> <!-- end of modal No Permissions to delete loan record-->
@endcannot


</tr>
@endforeach
@endisset

</tbody>
</table>
</div>
</div>

<div class="card-footer">
  <small class="nunito">
    List of interest rates for loans
  </small>
</div>

</div>

<script>
  $(document).ready(function(){
    var title = "List of loan interest rates";
    var table = $('.loanInterestRatesTable');
    var columns = [0,1,2,3,4];
    dirtTable(table,title,columns);
  })
</script>
@endsection



