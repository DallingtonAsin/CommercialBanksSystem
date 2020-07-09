@extends('layouts.sidebar-header')

@section('content')

<!--You can activate or deactivate defaulters as well
  as modify their details -->

  <div class="card nunito-font">
    <div class="card-header">
     <div class="row nunito-font">
       <div class="col-lg-3"> 
        <i class="fa fa-history" class="icon-i text-info"></i>
        <strong class="text-info">Loan History</strong>
        <span class="badge bg-success text-white">
          @isset($number_of_records)
          {{ $number_of_records }}
          @endisset
        </span>
      </div>

      <div class="col-lg-5 pt-2">
        <label>Transacted Loans in total: shs.</label>
        <label class="text-success">
          @isset($totalAmount)
          {{ number_format($totalAmount) }}
          @endisset
        </label>
      </div>
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
    <table class="table table-borderless table-data3 LoanHistoryTable">
      <thead>
        <tr class="tr-dark success">
          <th>Id</th>
          <th>Loanee</th>
           <th>Amount</th>
          <th>Balance</th>
          <th>Taken on</th>
          <th>Paid on</th>
          <th>Approved By</th>
          <th>Received By</th>
        </tr>
      </thead>
      <tbody>

        @isset($data)

        @foreach($data as $row)

        <tr>
          <td>{{ $row->id }}</td>
          <td>{{ $row->name }}</td>
          <td>{{ number_format($row->loan_amount) }}</td>
          <td class="text-success text-center">
            {{ number_format($row->loan_balance) }}.00
          </td>
          <td>{{ date('d-m-Y', strtotime($row->taken_on)) }}</td>
          <td>{{ date('d-m-Y', strtotime($row->paid_on)) }}</td>
          <td>{{ $row->approved_by }}</td>
          <td>{{ $row->received_by }}</td>

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

<script>
  $(document).ready(function(){
    var title = "Loan history in the system";
    var table = $('.LoanHistoryTable');
    var columns = [0,1,2,3,4,5,6,7];
    dirtyTable(table,title,columns);
  })
</script>
@endsection



