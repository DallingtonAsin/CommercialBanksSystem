@extends('layouts.sidebar-header')

@section('content')

<div class="card">
  <div class="card-body">

  <div class="panel panel-default nunito-font">
    <div class="panel-heading">
     <div class="row nunito-font">
       <div class="col-lg-5">
        <img src="{{ asset('images/icon/user-list.png') }}" class="icon-i text-info" />
        <strong class="text-info">
          @can('isAdmin') Manage pending loan requests @endcan
          @can('isMember') Pending loan requests @endcan
        </strong>
        <span class="badge bg-success text-white">
          @isset($no_of_pendingRequests)
          {{ $no_of_pendingRequests }}
          @endisset
        </span>
      </div>

      <div class="col-lg-5 pt-2">
        <label>Loans requests in total: shs.</label>
        <label class="text-danger">
          @isset($totalLoansInRequest)
          {{ number_format($totalLoansInRequest) }}
          @endisset
        </label>
      </div>

      @can('isAdmin')
      <div class="col-lg-2">
       <div class="btn-group">
        <button type="button" class="btn border-info text-info form-control text-center dropdown-toggle downloadfilebtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         Action
       </button>
       <ul class="dropdown-menu">
        <li>
          <a href="">Import loan requests</a>
        </li>
      </ul>
    </div>
  </div>
  @endcan

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
  <table class="table table-borderless table-data3 pendingLoanRequestsTable">
    <thead>
      <tr class="tr-dark success">
        <th>Id</th>
        <th>Name</th>
        <th>Tel</th>
        <th>Amt</th>
        <th>Duration</th>
        <th>Security</th>
        <th>Submitted on</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      @isset($pendingRequests)

      @foreach($pendingRequests as $row)

      <tr>
        <td>{{ $row->id }}</td>
        <td>{{ $row->name }}</td>
        <td>{{ $row->telno }}</td>
        <td>{{ number_format($row->loan_amount) }}</td>
        <td>{{ $row->duration}} {{ $row->duration_in}}</td>
        <td>{{ $row->collateral }}</td>
        <td>{{ date('Y-m-d', strtotime($row->submitted_on)) }}</td>

        <td class="text-center">
         <a href="" class="text-success"
         data-toggle="modal" data-target="#viewRequest_{{ $row->id }}"><i class="fa fa-eye pr-2" ></i> View</a>
         @can('isAdmin')

          <!-- <a href="" class="pl-4"
         data-toggle="modal" data-target="#updateRequest_{{ $row->id }}"><span class="glyphicon glyphicon-pencil text-success" ></span></a> -->

         <a href="" class="pl-4"
         data-toggle="modal" data-target="#deleteRequest_{{ $row->id }}"><span class="glyphicon glyphicon-trash trash-btn pr-2" ></span>Delete</a>

         <a href="" class="text-success pl-3"
         data-toggle="modal" data-target="#approveRequest_{{ $row->id }}"><span class="fa fa-check-circle pr-2" ></span>Approve</a>

         <a href="" class="trash-btn pl-3"
         data-toggle="modal" data-target="#denyRequest_{{ $row->id }}"><span class="fa fa-times-circle pr-2" ></span>Deny</a>
         @endcan
       </td>

     </td>



     @can('isLoansManager')
     <!--Modal Deleterow Record -->
     <div class="modal fade" id="deleteRequest_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content nunito-font">
          <div class="modal-header  overview-item--c2 text-center">
            <h6 class="modal-title w-100  text-white nunito-font">
            Delete pending loan request</h6>
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
                 {{ $row->name }}'s
               </span> loan request of
               <span class="text-success">{{ number_format($row->loan_amount) }}</span>
               ?
             </label>
           </div>
         </div>

         <div class="form-group">
           <form action="{{ route('loan.destroy', $row->id) }}" method="post">
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

@cannot('isLoansManager')
<!--Modal No permissions -->
<div class="modal fade" id="deleteRequest_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
             to delete a loan record. 
             <span class="text-dark">Only Loans Manager can!</span>
           </label>
         </div>
       </div>
    </div>
  </div>
</div>
</div> <!-- end of modal No Permissions to delete loan record-->
@endcan


<!-- View row Details -->
<div class="modal fade" id="viewRequest_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title nunito-font text-white nunito-font">
          <i class="fa fa-info-circle"></i>
          @can('isAdmin')
          Details of a pending loan request
          @endcan
          @can('isMember')
          Details of your pending loan request
          @endcan
        </h6>
        <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="form-group">
          <span class="text-muted">Name</span>
          <input type="text" class="form-control bg-white text-left text-dark" 
          value="{{ $row->name }}" readonly>
        </div>

        <div class="form-group">
          <span class="text-muted">Address</span>
          <input type="text" class="form-control bg-white"  
          value="{{ $row->address }}" readonly>
        </div>

        <div class="form-group">
          <span class="text-muted">Telephone No</span>
          <input type="text" class="form-control bg-white"  
          value="{{ $row->telno }}" readonly>
        </div>


        <div class="form-group">
          <span class="text-muted">Amount</span>
          <input type="text" class="form-control bg-white text-danger"  value="{{ number_format($row->loan_amount) }}" readonly>
        </div>

        <div class="form-group">
          <span class="text-muted">Duration</span>
          <input type="text" class="form-control bg-white"  value="{{ $row->duration}} {{ $row->duration_in}}" readonly>
        </div>

        <div class="form-group">
          <span class="text-muted">Collateral</span>
          <input type="text" class="form-control bg-white text-success"  
          value="{{ $row->collateral }}" readonly>
        </div>

        <div class="form-group">
          <span class="text-muted">Occupation</span>
          <input type="text" class="form-control bg-white"  value="{{ $row->occupation }}" readonly>
        </div>

        <div class="form-group">
          <span class="text-muted">Submitted on</span>
          <input type="text" class="form-control bg-white text-dark" value="{{ $row->submitted_on }}" readonly>
        </div>

      </div>
    </div>
  </div>
</div>  <!-- end of modal Viewrow Details-->

<!-- Update row Details -->
<div class="modal fade" id="updateRequest_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header overview-item--c2 text-center">
        <label class="modal-title nunito-font text-white  nunito-font">
          <i class="fa fa-info-circle"></i>
          Update details of a record of a row
        </label>
        <button type="button" class="close view-close
        text-white text-right" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <div class="modal-body">
        <form action="{{ Route('loan.update', $row->id)}}" method="POST">
          @method('PATCH')
          @csrf
          <div class="form-group">
            <span>Name</span>
            <input type="text" class="form-control bg-white text-left text-dark"  
            value="{{ $row->name }}" disabled>
          </div>

          <div class="form-group">
            <span>Amount</span>
            <input type="number" class="form-control bg-white text-danger"  value="{{ $row->loan_amount }}"
            name="loanAmount">
          </div>


          <div class="form-group">
            <span>Collateral</span>
            <input type="text" class="form-control bg-white text-success"  
            value="{{ $row->collateral }}" name="security">
          </div>


          <div class="form-group">
            <span>Taken on</span>
            <input type="date" class="form-control bg-white text-dark" value="{{ $row->taken_on }}" disabled>
          </div>

          <div class="form-group">
            <span class="text-muted">Duration</span>
            <input type="text" class="form-control bg-white"  value="{{ $row->duration}}" name="loanDuration">
            <input  type="radio" name="durationIn" value="years" checked>
            <span class="pl-2">years</span>&nbsp;
            <input  type="radio" name="durationIn" value="months">
            <span   class="pl-2" checked="">months</span>
            <input  type="radio" name="durationIn" value="days">
            <span   class="pl-2" checked="">days</span>
          </div>

          <div class="form-group">
            <span>Due date</span>
            <input type="date" class="form-control bg-white text-dark" value="{{ $row->due_date }}" name="dueDate">
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
</div>  <!-- end of modal updaterowDetails-->

@cannot('isLoansManager')
<!--Modal No permissions -->
<div class="modal fade" id="updateRequest_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title w-100  text-white nunito-font">
        <i class="fa fa-warning text-warning pr-2"></i>Permissions Denied!</h6>
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
             to update a loan record. 
             <span class="text-dark">Only Loans Manager can!</span>
           </label>
         </div>
       </div>
    </div>
  </div>
</div>
</div> <!-- end of modal No Permissions to delete loan record-->
@endcan

@can('isLoansManager')
<!--Modal Approve Applicant -->
<div class="modal fade" id="approveRequest_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title w-100  text-white nunito-font">
        Approve loan request</h6>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <div class="text-center">
           <label class="text-danger nunito-font">
            Are you sure you want to approve  
            <span class="text-dark text-muted bolded">
             {{ $row->name }}'s</span> loan request of
             <span class="text-success">{{ number_format($row->loan_amount) }}</span> ? </label>
           </div>
         </div>
         <form action="{{ Route('approve.loan', $row->id)}}"
          method="post">
          @csrf
          <div class="row nunito-font">
           <div class="col-lg-6">
             <span class="pr-4">Due Date</span>
             <input type="date" class="form-control" name="loanDueDate" required>
           </div>
         </div>
         <div class="form-group pt-3">
          <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div> <!-- end of modal  Approve Applicant-->
@endcan

@cannot('isLoansManager')
<!--Modal No permissions -->
<div class="modal fade" id="approveRequest_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title w-100  text-white nunito-font">
        <i class="fa fa-warning text-warning pr-2"></i>Permissions Denied!</h6>
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
             to approve any loan. 
             <span class="text-dark">Only Loans Manager can!</span>
           </label>
         </div>
       </div>
    </div>
  </div>
</div>
</div> <!-- end of modal No Permissions to delete loan record-->
@endcan

@can('isLoansManager')
<!--Modal Deny Loan Request -->
<div class="modal fade" id="denyRequest_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title w-100  text-white nunito-font">
        Deny loan request</h6>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <div class="text-center">
           <label class="text-danger nunito-font">
            Are you sure you want to reject
            <span class="text-dark text-muted bolded">
             {{ $row->name }}'s</span> loan request of
             <span class="text-success">{{ number_format($row->loan_amount) }}</span>
             ?
           </label>
         </div>
       </div>

       <div class="form-group">
         <form action="{{ Route('deny.loan', $row->id)}}" method="post">
          @csrf
          <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div> <!-- end of modal Deny Applicant-->
@endcan

@cannot('isLoansManager')
<!--Modal No permissions -->
<div class="modal fade" id="denyRequest_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title w-100  text-white nunito-font">
        <i class="fa fa-warning text-warning pr-2"></i>Permissions Denied!</h6>
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
             to deny any loan. 
             <span class="text-dark">Only Loans Manager can!</span>
           </label>
         </div>
       </div>
    </div>
  </div>
</div>
</div> <!-- end of modal No Permissions to deny loan record-->
@endcan

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
    var title = "List of all pending loan requests in the system";
    var table = $('.pendingLoanRequestsTable');
    var columns = [0,1,2,3,4,5,6];
    dirtyTable(table,title,columns);
  })
</script>
@endsection



