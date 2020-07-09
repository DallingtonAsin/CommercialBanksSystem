@extends('layouts.sidebar-header')

@section('content')

<!--You can activate or deactivate defaulters as well
  as modify their details -->

  <div class="card nunito-font">
    <div class="card-header">
     <div class="row nunito-font">
       <div class="col-lg-4">
        <img src="{{ asset('images/icon/user-list.png') }}" class="icon-i text-info" />
        <strong class="text-info">Manage defaulters</strong>
        <span class="badge bg-success text-white">
          @isset($number_of_defaulters)
          {{ $number_of_defaulters }}
          @endisset
        </span>
      </div>

      <div class="col-lg-4 pt-2">
        <label>Loans in total: shs.</label>
        <label class="text-danger">
          @isset($totalLoans)
          {{ number_format($totalLoans) }}
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
          <a href="">Import defaulters</a>
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
    <table class="table table-borderless table-data3 defaultersTable">
      <thead>
        <tr class="tr-dark success">
          <th>defaulterId</th>
          <th>Loan Name</th>
          <th>Amount</th>
          <th>Collateral</th>
          <th>Taken on</th>
          <th>Due date</th>
      <!--     <th>Days left</th> -->
          <!-- <th>LoanApp file</th> -->
          <th>Action</th>
        </tr>
      </thead>
      <tbody>

        @isset($defaulters)

        @foreach($defaulters as $defaulter)

        <tr>
          <td>{{ $defaulter->id }}</td>
          <td>{{ $defaulter->name }}</td>
          <td>{{ number_format($defaulter->loan_amount) }}</td>
          <td>{{ $defaulter->collateral }}</td>
          <td>{{ $defaulter->taken_on }}</td>
          <td>{{ $defaulter->due_date }}</td>

          <td class="text-center">
            @can('isAdmin')
            <a href="" class="text-decoration-none text-info" data-toggle="modal"
            data-target="#updatedefaulter_{{ $defaulter->id }}">
            <i class="glyphicon glyphicon-pencil"></i>
          </a>

          <a href="" class="text-decoration-none pl-5"  data-toggle="modal" data-target="#deletedefaulter_{{ $defaulter->id }}" >
            <i class="glyphicon glyphicon-trash trash-btn"></i>
          </a>
          @endcan
          <a href="" class="text-decoration-none pl-5" data-toggle="modal"
          data-target="#viewdefaulter_{{ $defaulter->id }}">
          <i class="fa fa-eye text-success"></i>
        </a>
      </td>



@can('isLoansManager')
      <!--Modal Deletedefaulter Record -->
      <div class="modal fade" id="deletedefaulter_{{ $defaulter->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content nunito-font">
            <div class="modal-header  overview-item--c2 text-center">
              <h6 class="modal-title w-100  text-white nunito-font">
              Delete defaulter record
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
                  <span class="text-dark text-muted bolded">
                   {{ $defaulter->name }}'s
                 </span> loan record 
                 ?
               </label>
             </div>
           </div>

           <div class="form-group">
             <form action="{{ route('loan.destroy', $defaulter->id) }}" method="post">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div> <!-- end of modal Delete defaulter Record-->
@endcan

  <!-- View defaulter Details -->
  <div class="modal fade" id="viewdefaulter_{{ $defaulter->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">

      <div class="modal-content nunito-font border border-custom-dark rounded-0">
        <div class="modal-header  overview-item--c2 text-center">
          <h6 class="modal-title nunito-font text-white nunito-font">
            <i class="fa fa-info-circle"></i>
            Details of a record of a defaulter
          </h6>
          <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

          <div class="modal-body">


            <div class="form-group">
              <span>defaulter</span>
              <input type="text" class="form-control bg-white text-left text-dark" 
              value="{{ $defaulter->name }}" readonly>
            </div>


            <div class="form-group">
              <span>Amount</span>
              <input type="text" class="form-control bg-white text-danger"  value="{{ number_format($defaulter->loan_amount) }}" readonly>
            </div>

            <div class="form-group">
              <span>Collateral</span>
              <input type="text" class="form-control bg-white text-success"  
              value="{{ $defaulter->collateral }}" readonly>
            </div>

            <div class="form-group">
              <span>Taken on</span>
              <input type="text" class="form-control bg-white text-dark" value="{{ $defaulter->taken_on }}" readonly>
            </div>

            <div class="form-group">
              <span>Due date</span>
              <input type="text" class="form-control bg-white text-dark" value="{{ $defaulter->due_date }}" readonly>
            </div>


          </div>
        </div>
      </div>
    </div>
  </div>  <!-- end of modal Viewdefaulter Details-->

@can('isLoansManager')
  <!-- Update defaulter Details -->
  <div class="modal fade" id="updatedefaulter_{{ $defaulter->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">

      <div class="modal-content nunito-font border border-custom-dark rounded-0">
        <div class="modal-header overview-item--c2 text-center">
          <label class="modal-title nunito-font text-white  nunito-font">
            <i class="fa fa-info-circle"></i>
            Update details of a record of a defaulter
          </label>
          <button type="button" class="close view-close
          text-white text-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="modal-body">
          <form action="{{ Route('loan.update', $defaulter->id)}}" method="POST">
            @method('PATCH')
            @csrf
            <div class="form-group">
              <span>defaulter</span>
              <input type="text" class="form-control bg-white text-left text-dark"  
              value="{{ $defaulter->name }}" disabled>
            </div>

            <div class="form-group">
              <span>Amount</span>
              <input type="number" class="form-control bg-white text-danger"  value="{{ $defaulter->loan_amount }}"
              name="loanAmount">
            </div>


            <div class="form-group">
              <span>Collateral</span>
              <input type="text" class="form-control bg-white text-success"  
              value="{{ $defaulter->collateral }}" name="security">
            </div>


            <div class="form-group">
              <span>Taken on</span>
              <input type="date" class="form-control bg-white text-dark" value="{{ $defaulter->taken_on }}" disabled>
            </div>

            <div class="form-group">
              <span>Due date</span>
              <input type="date" class="form-control bg-white text-dark" value="{{ $defaulter->due_date }}" name="dueDate">
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
</div>  <!-- end of modal updatedefaulterDetails-->
@endcan


@cannot('isLoansManager')
<!--Modal No permissions -->
<div class="modal fade" id="updatedefaulter_{{ $defaulter->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
             to edit any defaulter's loan record. 
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
<div class="modal fade" id="deletedefaulter_{{ $defaulter->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
             to delete any defaulter. 
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
    List of defaulters
  </small>
</div>

</div>

<script>
  $(document).ready(function(){
    var title = "List of all defaulters in the system";
    var table = $('.defaultersTable');
    var columns =[0,1,2,3,4,5];
    dirtyTable(table,title,columns);
  })
</script>
@endsection



