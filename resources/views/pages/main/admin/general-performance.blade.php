@extends('layouts.sidebar-header')

@section('content')

<div class="panel panel-success nunito-font">
  <div class="panel-heading"> 
    <img src="{{ asset('images/icon/brand.png') }}" class="icon-i text-info" />
    <strong class="nunito-font text-dark pt-4">
      Sacco General Performance
    </strong>
  </div>


  <div class="panel-body">
<div class="panel panel-default">
<div class="panel-body">
    <div class="table-responsive m-b-40">
      <table class="table table-bordered PerformanceTable">
        <caption>
        The table above shows {{ date('Y') }} performance in most variables</caption>
        <thead>
          <tr class="tr-dark success">
            <th>{{ __('Item') }}</th>
          @foreach($yearsArr as $key => $value)
            <th class="text-center">{{ $value }}</th>
           @endforeach
           <th class="text-center">Increase (%)</th>
           </tr>
        </thead>

        <tbody>

          <tr>
            <td class="text-left">{{ __('Membership') }}</td>
          @foreach($membershipArr as $key => $value)
            <td class="text-center">{{ number_format($value) }}</td>
           @endforeach
           </tr>

           <tr>
            <td class="text-left">{{ __('Savings') }}</td>
          @foreach($mainArr as $key => $value)
            <td class="text-center">{{ number_format($value) }}</td>
           @endforeach
           </tr>

            <tr>
            <td class="text-left">{{ __('Retirement') }}</td>
          @foreach($retirementArr as $key => $value)
            <td class="text-center">{{ number_format($value) }}</td>
           @endforeach
           </tr>

           <tr>
            <td class="text-left">{{ __('Education') }}</td>
          @foreach($educArr as $key => $value)
            <td class="text-center">{{ number_format($value) }}</td>
           @endforeach
           </tr>

            <tr>
            <td class="text-left">{{ __('Shares') }}</td>
          @foreach($sharesArr as $key => $value)
            <td class="text-center">{{ number_format($value) }}</td>
           @endforeach
           </tr>

         </tbody>

       </table>
     </div>
   </div>
 </div>
</div>

   <div class="panel-footer">
    <small class="nunito">
     SACCO GENERAL PERFORMANCE
    </small>
  </div>

</div>

<script>
  $(document).ready(function(){
    var title = "<h1 class='text-dark text-center nunito-font mb-3'>{{ config('app.companyName')}} </h1> <h2 class='text-center text-info mb-3 nunito-font'>GENERAL PERFORMANCE</h2>";
    var table = $('.PerformanceTable');
    pdfsTable(table,title);
  })
</script>
@endsection



