@extends('layouts.sidebar-header')

@section('content')

  <div class="card nunito-font">
    <div class="card-header">
     <div class="row nunito-font">
       <div class="col-lg-3">
        <img src="{{ asset('images/icon/user-list.png') }}" class="icon-i text-info" />
        <strong class="text-info">Paid Loans</strong>
        <span class="badge bg-success text-white">
          @isset($number_of_paidLoans)
          {{ $number_of_paidLoans }}
          @endisset
        </span>
      </div>

      <div class="col-lg-5 pt-2">
        <label>Paid Loans in total: shs.</label>
        <label class="text-success">
          @isset($totalPaidLoans)
          {{ number_format($totalPaidLoans) }}
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
          <a href="">Import paid loans</a>
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
  <table class="table table-borderless table-data3 paidLoansTable">
    <thead>
      <tr class="tr-dark success">
        <th>Id</th>
        <th>Loan Name</th>
        <th>Amount</th>
        <th>Balance</th>
        <th>Taken on</th>
        <th>Paid on</th>
        @can('isAdmin')
        <th>ApprovedBy</th>
        @endcan
        <th>ReceivedBy</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>

      @isset($paidLoans)

      @foreach($paidLoans as $row)

      <tr>
        <td>{{ $row->id }}</td>
        <td>{{ $row->name }}</td>
        <td>{{ number_format($row->loan_amount) }}</td>
        <td class="text-success text-center">
          {{ number_format($row->loan_balance) }}.00
        </td>
        <td>{{ date('d-m-Y', strtotime($row->taken_on)) }}</td>
        <td>{{ date('d-m-Y', strtotime($row->paid_on)) }}</td>
        @can('isAdmin')
        <td>{{ $row->approved_by }}</td>
        @endcan
        <td>{{ $row->received_by }}</td>
        <td class="text-center">
        
         @can('isAdmin')
         <a href="" class="text-info" 
         data-toggle="modal" data-target="#updatepaidLoan_{{ $row->id }}"><i class="glyphicon glyphicon-pencil pr-2" ></i> Edit</a>
         <a href="" class="pl-2 trash-btn"
         data-toggle="modal" data-target="#deletepaidLoan_{{ $row->id }}"><span class="glyphicon glyphicon-trash pr-2" ></span>Delete</a>
         @endcan
          <a href="" class="text-success pl-2"
         data-toggle="modal" data-target="#viewpaidLoan_{{ $row->id }}"><i class="fa fa-eye pr-2" ></i>View</a>
       </td>

@can('isLoansManager')
       <!--Modal DeletepaidLoan Record -->
       <div class="modal fade" id="deletepaidLoan_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content nunito-font">
            <div class="modal-header  overview-item--c2 text-center">
              <h6 class="modal-title w-100  text-white nunito-font">
                Delete {{ $row->name }}'s paid loan record</h6>
                <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">

                <div class="form-group">
                  <div class="text-left">
                   <label class="text-danger">
                    Are you sure you want to delete  
                    <span class="text-dark text-muted bolded">
                     {{ $row->name }}'s
                   </span> paid loan record 
                   ?
                 </label>
               </div>
             </div>

             <div class="form-group">
               <form action="{{ route('paidLoans.destroy', $row->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div> <!-- end of modal Delete paidLoan Record-->
@endcan

    <!-- View paidLoan Details -->
    <div class="modal fade" id="viewpaidLoan_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">

        <div class="modal-content nunito-font border border-custom-dark rounded-0">
          <div class="modal-header  overview-item--c2 text-center">
            <h6 class="modal-title nunito-font text-white nunito-font">
              <i class="fa fa-info-circle"></i>
              Details of {{ $row->name }}'s paid loan record
            </h6>
            <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="modal-body">


              <div class="form-group">
                <span>Name</span>
                <input type="text" class="form-control bg-white text-left text-dark" 
                value="{{ $row->name }}" readonly>
              </div>


              <div class="form-group">
                <span>Amount</span>
                <input type="text" class="form-control bg-white text-danger"  value="{{ number_format($row->loan_amount) }}" readonly>
              </div>

              <div class="form-group">
                <span>Taken on</span>
                <input type="text" class="form-control bg-white text-dark" value="{{ date('d-M-Y', strtotime($row->taken_on)) }}" readonly>
              </div>

              <div class="form-group">
                <span>Paid on</span>
                <input type="text" class="form-control bg-white text-dark" value="{{ date('d-M-Y', strtotime($row->paid_on)) }}" readonly>
              </div>

              <div class="form-group">
                <span>Approved By</span>
                <input type="text" class="form-control bg-white text-dark" value="{{ $row->approved_by }}" readonly>
              </div>

              <div class="form-group">
                <span>Received By</span>
                <input type="text" class="form-control bg-white text-dark" value="{{ $row->received_by }}" readonly>
              </div>


            </div>
          </div>
        </div>
      </div>
    </div>  <!-- end of modal ViewpaidLoan Details-->

@can('isLoansManager')
    <!-- Update paidLoan Details -->
    <div class="modal fade" id="updatepaidLoan_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-dialog-centered modal-md" role="document">

        <div class="modal-content nunito-font border border-custom-dark rounded-0">
          <div class="modal-header overview-item--c2 text-center">
            <label class="modal-title nunito-font text-white  nunito-font">
              <i class="fa fa-info-circle"></i>
              Update details of {{ $row->name}}'s paid loan record
            </label>
            <button type="button" class="close view-close
            text-white text-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="modal-body">
            <form action="{{ Route('paidLoans.update', $row->id)}}" method="POST">
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
                name="amount">
              </div>

              <div class="form-group">
                <span>Taken on</span>
                <input type="date" class="form-control bg-white text-dark" value="{{ $row->taken_on }}" disabled>
              </div>

              <div class="form-group">
                <span>Paid on</span>
                <input type="date" class="form-control bg-white text-dark" value="{{ $row->paid_on }}" name="paidOn">
              </div>

              <div class="form-group">
                <span>Approved By</span>
                <input type="text" class="form-control bg-white text-dark" value="{{ $row->approved_by }}" name="approvedBy">
              </div>

              <div class="form-group">
                <span>Received By</span>
                <input type="text" class="form-control bg-white text-dark" value="{{ $row->received_by }}" name="receivedBy">
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
  </div>  <!-- end of modal updatepaidLoanDetails-->
@endcan


@cannot('isLoansManager')
<!--Modal No permissions -->
<div class="modal fade" id="updatepaidLoan_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
             to edit any paid loan record. 
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
<div class="modal fade" id="deletepaidLoan_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
             to delete any paid loan. 
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
    List of fully paid loans
  </small>
</div>

</div>

@can('isAdmin')
<script>
  $(document).ready(function(){
    var title = "List of all paid Loans";
    var table = $('.paidLoansTable');
    var columns = [0,1,2,3,4,5,6,7];
    dirtyTable(table,title,columns);
  })
</script>
@endcan

@can('isMember')
<script>
  $(document).ready(function(){
    var title = "List of all paid Loans";
    var table = $('.paidLoansTable');
    var columns = [0,1,2,3,4,5,6];
    dirtyTable(table,title,columns);
  })
</script>
@endcan


@endsection



