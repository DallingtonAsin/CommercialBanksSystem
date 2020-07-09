@extends('layouts.sidebar-header')

@section('content')

  <div class="panel panel-default nunito-font">
    <div class="panel-heading">
     <div class="row">
       <div class="col-lg-3">
        <img src="{{ asset('images/icon/user-list.png') }}" class="icon-i text-info" />
        <strong class="text-info">
          @can('isAdmin') Manage Investments @endcan
          @can('isMember') Investments @endcan
        </strong>
        <span class="badge bg-success text-white">
          @isset($number_of_runningInvestments)
          {{ $number_of_runningInvestments }}
          @endisset
        </span>
      </div>

      <div class="col-lg-7 pt-2">
        <label>Inve'nt capital: shs.</label>
        <label class="text-danger pr-4">
          @isset($totl_investmentCapital)
          {{ number_format($totl_investmentCapital) }}
          @endisset
        </label>

          <label>Total returns: shs.</label>
        <label class="text-success">
          @isset($totl_returns)
          {{ number_format($totl_returns) }}
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
        <li class="text-info">
          <a href="" data-toggle="modal" data-target="#addInvestment">Add investment</a>
          <a href="" data-toggle="modal" data-target="#importInvestments">Import investments</a>
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
  <table class="table table-borderless table-data3 runningInvestmentsTable">
    <thead>
      <tr class="tr-dark success">
        <th>Id</th>
        <th>asset</th>
        <th>capital</th>
        <th>ROI</th>
        <th>approved on</th>
        <th>approved by</th>
        <th class="text-left">Action</th>
      </tr>
    </thead>
    <tbody>

      @isset($rows)

      @foreach($rows as $row)

      <tr>
        <td>{{ $row->id }}</td>
        <td>{{ $row->asset }}</td>
        <td>{{ number_format($row->capital) }}</td>
        <td>{{ number_format($row->returns) }}</td>
        <td>{{ date('Y-m-d', strtotime($row->approved_on)) }}</td>
        <td>{{ $row->approved_by }}</td>
        <td class="text-left">
         <a href="" class="text-success"
         data-toggle="modal" data-target="#viewInvestment_{{ $row->id }}"><i class="fa fa-eye pr-2" ></i></a>
         @can('isAdmin')

          <a href="" class="pl-3 text-info"
         data-toggle="modal" data-target="#updateInvestment_{{ $row->id }}"><span class="glyphicon glyphicon-pencil pr-2" ></span>Edit</a>

         <a href="" class="pl-3 text-danger"
         data-toggle="modal" data-target="#deleteInvestment_{{ $row->id }}"><span class="glyphicon glyphicon-trash  pr-2" ></span>Delete</a>

         <a href="" class="trash-btn pl-3"
         data-toggle="modal" data-target="#denyInvestment_{{ $row->id }}"><span class="fa fa-times-circle pr-2" ></span>Disapprove</a>
         @endcan
       </td>

     </td>

<!-- Update row Details -->
<div class="modal fade" id="updateInvestment_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header overview-item--c2 text-center">
        <label class="modal-title nunito-font text-white  nunito-font">
          <i class="fa fa-info-circle"></i>
          Update details of a running investment
        </label>
        <button type="button" class="close view-close
        text-white text-right" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

   <div class="modal-body">
        <form method="post" action="{{ Route('investments.update', $row->id) }}">
          @csrf
          @method('PATCH')
        <div class="form-group">
          <span class="text-muted">Asset</span>
          <input type="text" class="form-control bg-white text-left text-dark" 
         value="{{ $row->asset }}" name="asset" required>
        </div>

        <div class="form-group">
          <span class="text-muted">Details</span>
          <input type="text" class="form-control bg-white text-left text-dark" 
         value="{{ $row->details }}" name="details">
        </div>

        <div class="form-group">
          <span class="text-muted">Capital</span>
          <input type="number" class="form-control bg-white"  
           value="{{ $row->capital }}" name="capital" required>
        </div>


        <div class="form-group">
          <span class="text-muted">Return on Investment</span>
          <input type="number" class="form-control bg-white text-success" value="{{ $row->returns }}" name="returns" required>
        </div>

        <div class="form-group">
          <span class="text-muted">Approved On</span>
          <input type="date" class="form-control bg-white"
           name="date-of-approval" value="{{ $row->approved_on }}" required>
        </div>

        <div class="form-group">
          <span class="text-muted">Approved By</span>
          <input type="text" class="form-control bg-white" 
           value="{{ $row->approved_by }}" name="approvedby" required>
        </div>

         <div class="form-group">
            <button ype="submit" class="form-control btn overview-item--c2 text-white" name="submit">
              <strong>Update investment</strong>
            </button>
          </div>

      </form>
      </div>
  </div>
</div>
</div>  <!-- end of modal updaterowDetails-->

  

<!-- View row Details -->
<div class="modal fade" id="viewInvestment_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title nunito-font text-white nunito-font">
          <i class="fa fa-info-circle"></i>
          Details of a running investment
        </h6>
        <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="form-group">
          <span class="text-muted">Asset</span>
          <input type="text" class="form-control bg-white text-left text-dark" 
          value="{{ $row->asset }}" readonly>
        </div>

         <div class="form-group">
          <span class="text-muted">Details</span>
          <input type="text" class="form-control bg-white text-left text-dark" 
          value="{{ $row->details }}" readonly>
        </div>

        <div class="form-group">
          <span class="text-muted">Capital</span>
          <input type="text" class="form-control bg-white"  
          value="{{ number_format($row->capital) }}" readonly>
        </div>


        <div class="form-group">
          <span class="text-muted">Return on Investment</span>
          <input type="text" class="form-control bg-white text-success"  value="{{ number_format($row->returns) }}" readonly>
        </div>

        <div class="form-group">
          <span class="text-muted">Approved On</span>
          <input type="text" class="form-control bg-white"  value="{{ $row->approved_on}}" readonly>
        </div>

        <div class="form-group">
          <span class="text-muted">Approved By</span>
          <input type="text" class="form-control bg-white"  value="{{ $row->approved_by }}" readonly>
        </div>

      </div>
    </div>
  </div>
</div>  <!-- end of modal Viewrow Details-->


     @can('isAdmin')
     <!--Modal Deleterow Record -->
     <div class="modal fade" id="deleteInvestment_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content nunito-font">
          <div class="modal-header  overview-item--c2 text-center">
            <h6 class="modal-title w-100  text-white nunito-font">
            Delete a running investment</h6>
            <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="form-group">
              <div class="text-center">
               <label class="text-danger">
                Are you sure you want to delete running investment of
                <span class="text-dark">{{ $row->asset }}</span>
                worth <span class="text-dark">{{ number_format($row->capital) }}</span>
             </label>
           </div>
         </div>

         <div class="form-group">
           <form action="{{ route('investments.destroy', $row->id) }}" method="post">
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


@can('isAdmin')
<!--Modal Disapprove Investment -->
<div class="modal fade" id="denyInvestment_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title w-100  text-white nunito-font">
        Disapprove Investment</h6>
        <button type="button" class="close view-close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="form-group">
          <div class="text-center">
           <label class="text-danger nunito-font">
            Are you sure you want to disapprove investment in 
            <span class="text-dark">{{ $row->asset }}</span> worth <span class="text-dark">{{ number_format($row->capital) }}</span>
           </label>
         </div>
       </div>

       <div class="form-group">
         <form action="{{ Route('investment.disapprove', $row->id)}}" method="post">
          @csrf
          <button type="submit" class="btn overview-item--c2 text-white"  name="ConfirmBtn">Yes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div> <!-- end of modal Deny Investment-->
@endcan


@cannot('isAdmin')
<!--Modal No permissions -->
<div class="modal fade" id="deleteInvestment_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
@endcannot

@cannot('isAdmin')
<!--Modal No permissions -->
<div class="modal fade" id="updateInvestment_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
@endcannot


@cannot('isAdmin')
<!--Modal No permissions -->
<div class="modal fade" id="approveInvestment_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
@endcannot


@cannot('isAdmin')
<!--Modal No permissions -->
<div class="modal fade" id="denyInvestment_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel">
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
@endcannot

</tr>
@endforeach
@endisset

</tbody>
</table>


<!--Import Investments-->
<div class="modal fade" id="importInvestments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content nunito-font">

      <form action="{{ Route('investments.import') }}" method="post"
      enctype="multipart/form-data">
      @csrf
      <div class="modal-header text-center">
        <h5 class="modal-title w-100 font-weight-bold">Import data of investments from an excel file</h5>
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


<!-- Modal Add investment -->
<div class="modal fade" id="addInvestment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">

    <div class="modal-content nunito-font border border-custom-dark rounded-0">
      <div class="modal-header  overview-item--c2 text-center">
        <h6 class="modal-title nunito-font text-white nunito-font">
          <i class="fa fa-info-circle"></i>
          Add an investment
        </h6>
        <button type="button" class="close view-close text-white text-right" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="post" action="{{ Route('investments.store') }}">
          @csrf
        <div class="form-group">
          <span class="text-muted">Asset</span>
          <input type="text" class="form-control bg-white text-left text-dark" 
          placeholder="Enter asset" name="asset" required>
        </div>

        <div class="form-group">
          <span class="text-muted">Details</span>
          <input type="text" class="form-control bg-white text-left text-dark" 
          placeholder="Enter details about investment" name="details">
        </div>

        <div class="form-group">
          <span class="text-muted">Capital</span>
          <input type="number" class="form-control bg-white"  
           placeholder="Enter capital" name="capital" required>
        </div>


        <div class="form-group">
          <span class="text-muted">Return on Investment</span>
          <input type="number" class="form-control bg-white text-success" placeholder="Enter return on investment" name="returns" required>
        </div>

        <div class="form-group">
          <span class="text-muted">Approved On</span>
          <input type="date" class="form-control bg-white"
           name="date-of-approval" placeholder="Enter date of approval">
        </div>

        <div class="form-group">
          <span class="text-muted">Approved By</span>
          <input type="text" class="form-control bg-white" placeholder="Enter who approved the investment" name="approvedby">
        </div>

         <div class="form-group">
            <button ype="submit" class="form-control btn overview-item--c2 text-white" name="submit">
              <strong>Add investment</strong>
            </button>
          </div>

      </form>
      </div>
    </div>
  </div>
</div>  <!-- end of modal Add investment-->






</div>
</div>

<div class="card-footer">
  <small class="nunito">
    List of rows
  </small>
</div>

</div>

<script>
  $(document).ready(function(){
    var title = "List of running investments";
    var table = $('.runningInvestmentsTable');
    var columns = [0,1,2,3,4,5];
    dirtyTable(table,title,columns);
  })
</script>
@endsection



