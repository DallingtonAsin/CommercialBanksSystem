@extends('layouts.sidebar-header')

@section('content')

<div class="card">
  <div class="card-body">
  <div class="panel panel-default nunito-font">
    <div class="panel-heading">
     <div class="row nunito-font">
       <div class="col-lg-5">
        <img src="{{ asset('images/icon/user-list.png') }}" class="icon-i text-info" />
        <strong class="text-info">Manage Loanees</strong>
        <span class="badge bg-success text-white">
          @isset($number_of_loanees)
          {{ $number_of_loanees }}
          @endisset
        </span>
      </div>

      <div class="col-lg-5 pt-2">
        <label>Loans in total: shs.</label>
        <label class="text-danger">
          @isset($totalLoans)
          {{ number_format($totalLoans) }}
          @endisset
        </label>
      </div>

      <div class="col-lg-2">
       <div class="btn-group">
        <button type="button" class="btn border-info text-info form-control text-center dropdown-toggle downloadfilebtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <strong> Action</strong>
       </button>
       <ul class="dropdown-menu">
        <li>
          <a href="" data-toggle="modal" data-target="#importLoanees">Import Loanees</a>
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
</div>


<div class="table-responsive m-b-40">
  <table class="table table-borderless table-data3 LoaneesTable">
    <thead>
      <tr class="tr-dark success">
        <th>LoaneeId</th>
        <th>Loan Name</th>
        <th>Amount</th>
        <th>Duration</th>
        <th>Collateral</th>
        <th>Taken on</th>
        <th>Due date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      @isset($loanees)

      @foreach($loanees as $loanee)

      <tr>
        <td>{{ $loanee->id }}</td>
        <td>{{ $loanee->name }}</td>
        <td>{{ number_format($loanee->loan_amount) }}</td>
        <td>{{ $loanee->duration}} {{ $loanee->duration_in}}</td>
        <td>{{ $loanee->collateral }}</td>
        <td>{{ $loanee->taken_on }}</td>
        <td>{{ $loanee->due_date }}</td>

        <td class="text-center">
          @can('isAdmin')
          <a href="" class="text-decoration-none text-info" data-toggle="modal"
          data-target="#updateLoanee_{{ $loanee->id }}">
          <i class="glyphicon glyphicon-pencil pr-2"></i>
        Edit</a>

        <a href="" class="text-decoration-none trash-btn pl-3"  data-toggle="modal" data-target="#deleteLoanee_{{ $loanee->id }}" >
          <i class="glyphicon glyphicon-trash pr-2"></i>Delete
        </a>
        @endcan
        <a href="" class="text-decoration-none text-success pl-3" data-toggle="modal"
        data-target="#viewLoanee_{{ $loanee->id }}">
        <i class="fa fa-eye pr-2"></i>View
      </a>
    </td>



@can('isLoansManager')
    <!--Modal DeleteLoanee Record -->
    <div class="modal fade" id="deleteLoanee_{{ $loanee->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content nunito-font">
          <div class="modal-header  overview-item--c2 text-center">
            <h6 class="modal-title w-100  text-white nunito-font">
            Delete Loanee record</h6>
            <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <div class="text-center">
               <label class="text-danger">
                Are you sure you want to delete  
                <span class="text-dark text-muted bolded">
                 {{ $loanee->name }}'s
               </span> loan record 
               ?
             </label>
           </div>
         </div>

         <div class="form-group">
           <form action="{{ route('loan.destroy', $loanee->id) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div> <!-- end of modal Delete Loanee Record-->
@endcan

<!-- View Loanee Details -->
<div class="modal fade" id="viewLoanee_{{ $loanee->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title nunito-font text-white nunito-font">
          <i class="fa fa-info-circle"></i>
          Details of a record of a loanee
        </h6>
        <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="modal-body">


          <div class="form-group">
            <span>Loanee</span>
            <input type="text" class="form-control bg-white text-left text-dark" 
            value="{{ $loanee->name }}" readonly>
          </div>


          <div class="form-group">
            <span>Amount</span>
            <input type="text" class="form-control bg-white text-danger"  value="{{ number_format($loanee->loan_amount) }}" readonly>
          </div>

          <div class="form-group">
            <span>Collateral</span>
            <input type="text" class="form-control bg-white text-success"  
            value="{{ $loanee->collateral }}" readonly>
          </div>

          <div class="form-group">
            <span>Taken on</span>
            <input type="text" class="form-control bg-white text-dark" value="{{ $loanee->taken_on }}" readonly>
          </div>

          <div class="form-group">
            <span>Due date</span>
            <input type="text" class="form-control bg-white text-dark" value="{{ $loanee->due_date }}" readonly>
          </div>


        </div>
      </div>
    </div>
  </div>
</div>  <!-- end of modal ViewLoanee Details-->


@can('isLoansManager')
<!-- Update Loanee Details -->
<div class="modal fade" id="updateLoanee_{{ $loanee->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header overview-item--c2 text-center">
        <label class="modal-title nunito-font text-white  nunito-font">
          <i class="fa fa-info-circle"></i>
          Update details of a record of a loanee
        </label>
        <button type="button" class="close view-close
        text-white text-right" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <div class="modal-body">
        <form action="{{ Route('loan.update', $loanee->id)}}" method="POST">
          @method('PATCH')
          @csrf
          <div class="form-group">
            <span>Loanee</span>
            <input type="text" class="form-control bg-white text-left text-dark"  
            value="{{ $loanee->name }}" disabled required>
          </div>

          <div class="form-group">
            <span>Amount</span>
            <input type="number" class="form-control bg-white text-danger"  value="{{ $loanee->loan_amount }}"
            name="loanAmount" required>
          </div>


          <div class="form-group">
            <span>Collateral</span>
            <input type="text" class="form-control bg-white text-success"  
            value="{{ $loanee->collateral }}" name="security" >
          </div>


          <div class="form-group">
            <span>Taken on</span>
            <input type="date" class="form-control bg-white text-dark" value="{{ $loanee->taken_on }}" disabled required>
          </div>

          <div class="form-group">
            <span>Due date</span>
            <input type="date" class="form-control bg-white text-dark" value="{{ $loanee->due_date }}" name="dueDate" required>
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
</div>  <!-- end of modal updateLoaneeDetails-->
@endcan


@cannot('isLoanManager')
<!--Modal No permissions -->
<div class="modal fade" id="updateLoanee_{{ $loanee->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
             to edit any loanee record. 
             <span class="text-dark">Only Loans Manager can!</span>
           </label>
         </div>
       </div>
    </div>
  </div>
</div>
</div> <!-- end of modal No Permissions to delete loan record-->
@endcannot

@cannot('isLoanManager')
<!--Modal No permissions -->
<div class="modal fade" id="deleteLoanee_{{ $loanee->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
             to delete any loanee record. 
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


@can('isAdmin')
<!--Import Loanees -->
<div class="modal fade" id="importLoanees" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content nunito-font">

      <form action="{{ Route('loanees.import') }}" method="post"
      enctype="multipart/form-data" name="inportRolesForm" >
      @csrf

      <div class="modal-header overview-item--c2 text-center">
        <h6 class="modal-title w-100 nunito-font font-weight-bold text-white">Import loanees from an  excel file</h6>
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


<script>
  $(document).ready(function(){
    var title = "List of all loanees in the system";
    var table = $('.LoaneesTable');
    var columns = [0,1,2,3,4,5,6];
    dirtyTable(table,title,columns);
  })
</script>
@endsection



