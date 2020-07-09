@extends('layouts.sidebar-header')

@section('content')

<div class="row">

  <!-- <div class="col-md-3">
    <div class="overview-wrap">
      <h2 class=" nunito-font">overview</h2>
      <button class="au-btn au-btn-icon au-btn--blue">
        <i class="zmdi zmdi-plus"></i>add item</button>
      </div>
    </div> -->

    <div class="col-md-9 d-block d-md-flex">
      <div>
        <p class="mg-b-0 nunito-font pl-4">
        Customer service / Desk monitoring dashboard.</p>
      </div>
    </div><!-- az-content-header -->


  </div>
  <div class="row m-t-25">
    <div class="col-sm-6 col-lg-3">
      <div class="overview-item overview-item--c1">
        <div class="overview__inner">
          <div class="overview-box clearfix">
            <div class="icon">
              <i class="zmdi zmdi-account-o"></i>
              <span class="pl-2">total deposits</span>
            </div>
            <div class="text">
              <h2 class="mt-4">
                @isset($dataMarr)
                {{ number_format($dataMarr['deposits']) }}
                @endisset

              </h2>

            </div>
          </div>
          <div class="overview-chart">
            <canvas id="widgetChart1"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="overview-item overview-item--c2">
        <div class="overview__inner">
          <div class="overview-box clearfix">
            <div class="icon">
              <i class="zmdi zmdi-shopping-cart"></i>
              <span class="pl-2">total withdrawals</span>
            </div>
            <div class="text">
              <h2 class="mt-4">
                @isset($dataMarr)
                {{ number_format($dataMarr['withdrawals']) }}
                @endisset

              </h2>

            </div>
          </div>
          <div class="overview-chart">
            <canvas id="widgetChart2"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="overview-item overview-item--c3">
        <div class="overview__inner">
          <div class="overview-box clearfix">
            <div class="icon">
              <i class="zmdi zmdi-calendar-note"></i>
              <span class="pl-2">this month</span>
            </div>
            <div class="text">
              <h2 class="mt-4">@isset($totalSavings)
                {{ number_format($totalSavings) }}
              @endisset</h2>
            </div>
          </div>
          <div class="overview-chart">
            <canvas id="widgetChart3"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-lg-3">
      <div class="overview-item overview-item--c4">
        <div class="overview__inner">
          <div class="overview-box clearfix">
            <div class="icon">
              <i class="zmdi zmdi-money"></i>
              <span class="pl-2">this week</span>
            </div>
            <div class="text">
              <h2 class="mt-4">
                @isset($totalSavings)
                {{ number_format($totalSavings) }}
                @endisset
              </h2>
            </div>
          </div>
          <div class="overview-chart">
            <canvas id="widgetChart4"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-6">
      <div class="au-card m-b-30">
        <div class="au-card-inner">
          <h3 class="title-2 m-b-40">Savings Bar Chart</h3>
          <canvas id="graphCanvas" class="nunito-font"></canvas>
          <!--  <canvas id="singelBarChart"></canvas> -->
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="au-card m-b-30">
        <div class="au-card-inner">
          <h2 class="title-2 m-b-40">
            Deposits Vs Withdrawals LineChart
          </h2>
          <canvas id="line-chart"></canvas>
          <!-- <canvas id="sales-chart"></canvas> -->
        </div>
      </div>
    </div>
  </div>

  <div class="row">
   <h2 class="title-2 m-b-20 pl-4 text-info text-center nunito-font"> 
     Top savers in different accounts
   </h2>
 </div>


 <div class="row nunito-font">
  <div class="col-lg-6">
    <h2 class="title-2 m-b-25 nunito-font"> Main account statistics</h2>
    <div class="table-responsive m-b-40">
     <table class="table table-borderless overview-item--c2">
      <thead class="th-dark">
        <tr>
          <th>No.</th>
          <th>Account Name</th>
          <th>Account No.</th>
          <th>Account Value</th>
        </tr>
      </thead>
      <tbody>
        @php
        $count = 1;
        @endphp
        @isset($top_savers)
        @foreach($top_savers as $row)
        <tr>
         <td>{{ $count++ }}.</td>
         <td>{{ $row->accountNo }}</td>
         <td>{{ $row->name }}</td>
         <td>{{ number_format($row->savings) }}</td>
       </tr>
       @endforeach
       @endisset
     </tbody>
   </table>
 </div>
</div>

<div class="col-lg-6">
  <h2 class="title-2 m-b-25 nunito-font">
  Education account statistics</h2>
  <div class="table-responsive m-b-40">
   <table class="table table-borderless overview-item--c3">
    <thead class="th-white">
      <tr>
        <th>No.</th>
        <th>Account Name</th>
        <th>Account No.</th>
        <th>Account Value</th>
      </tr>
    </thead>
    <tbody>
      @php
      $count = 1;
      @endphp
      @isset($education_topsavers)
      @foreach($education_topsavers as $row)
      <tr>
       <td>{{ $count++ }}.</td>
       <td>{{ $row->accountNo }}</td>
       <td>{{ $row->name }}</td>
       <td>{{ number_format($row->savings) }}</td>
     </tr>
     @endforeach
     @endisset
   </tbody>
 </table>
</div>
</div>
</div>

<div class="row nunito-font">
  <div class="col-lg-6">
    <h2 class="title-2 m-b-25 nunito-font"> 
    Retirement account statistics</h2>
    <div class="table-responsive m-b-40">
     <table class="table table-borderless overview-item--c1">
      <thead class="th-white">
        <tr>
          <th>No.</th>
          <th>Account Name</th>
          <th>Account No.</th>
          <th>Account Value</th>
        </tr>
      </thead>
      <tbody>
        @php
        $count = 1;
        @endphp
        @isset($retirement_topsavers)
        @foreach($retirement_topsavers as $row)
        <tr>
         <td>{{ $count++ }}.</td>
         <td>{{ $row->accountNo }}</td>
         <td>{{ $row->name }}</td>
         <td>{{ number_format($row->savings) }}</td>
       </tr>
       @endforeach
       @endisset
     </tbody>
   </table>
 </div>
</div>

<div class="col-lg-6">
  <h2 class="title-2 m-b-25 nunito-font">
  Shares account statistics</h2>
  <div class="table-responsive m-b-40">
   <table class="table table-borderless overview-item--c4">
    <thead class="th-dark">
      <tr class="text-white">
        <th>No.</th>
        <th>Account Name</th>
        <th>Account No.</th>
        <th>Account Value</th>
      </tr>
    </thead>
    <tbody>
      @php
      $count = 1;
      @endphp
      @isset($shares_topsavers)
      @foreach($shares_topsavers as $row)
      <tr>
       <td>{{ $count++ }}.</td>
       <td>{{ $row->accountNo }}</td>
       <td>{{ $row->name }}</td>
       <td>{{ number_format($row->savings) }}</td>
     </tr>
     @endforeach
     @endisset
   </tbody>
 </table>
</div>
</div>
</div>

<script>

 $(document).ready(function(){

   drawBarChart();
   drawLineChart();

 });

 function drawBarChart()
 {
   $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

   $.ajax({
    url:"{{ Route('savings-data.chart') }}",
    method:'get',
    data:{
      getSavingsData:1,
    },
    dataType:'json',
    success: function(data){
      console.log(data);
      getBarChartData(data);

    },
    error:function(data){
      console.log('errors loading....');
    },
  });
 }

 function drawLineChart()
 {
   $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

   $.ajax({
    url:"{{ Route('savings-data.chart') }}",
    method:'get',
    data:{
     getSavingsData:1,
   },
   dataType:'json',
   success: function(data){
    console.log(data);
    getLineChartData(data);

  },
  error:function(data){
    console.log('errors loading....');
  },
});
 }

 function getBarChartData(data)
 { 

  var monthname = [];
  var savings = [];

  for(var i=0;i<data.length;i++) {
    monthname.push(data[i].monthname);
    savings.push(data[i].savings / 1000);
  }

  var ctx = document.getElementById("graphCanvas");
  if (ctx) {
    ctx.height = 150;

    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: monthname,
        datasets: [
        {
          label: "Savings over months",
          data: savings,
          borderColor: "rgba(0, 123, 255, 0.9)",
          borderWidth: "0",
          backgroundColor: "rgba(0, 123, 255, 0.5)"
        }
        ]
      },
      options: {
        legend: {
          position: 'top',
          labels: {
            fontFamily: 'Poppins'
          }

        },
        scales: {
          xAxes: [{
            scaleLabel: {
              display: false,
              labelString: 'Month'
            },
            ticks: {
              fontFamily: "Poppins"

            }
          }],
          yAxes: [{
            scaleLabel: {
              display: true,
              labelString: "Savings in '000'",
              fontFamily:'Nunito',
            },
            ticks: {
              beginAtZero: true,
              fontFamily: "Poppins"
            }
          }]
        }
      }
    });
  }

}

function getLineChartData(data){

  var monthname = [];
  var savings = [];
  var withdrawals = [];

  for(var i=0;i<data.length;i++) {
    monthname.push(data[i].monthname);
    savings.push(data[i].savings / 1000);
    withdrawals.push(data[i].withdrawals / 1000);
  }

  var ctx = document.getElementById("line-chart");
  if (ctx) {
    ctx.height = 150;
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: monthname,
        type: 'line',
        defaultFontFamily: 'Poppins',
        datasets: [{

          label: "Savings",
          data: savings,
          backgroundColor: 'transparent',
          borderColor: 'rgba(40,167,69,0.75)',
          borderWidth: 3,
          pointStyle: 'circle',
          pointRadius: 5,
          pointBorderColor: 'transparent',
          pointBackgroundColor: 'rgba(40,167,69,0.75)',
        }, 

        {
          label: "withdrawals",
          data: withdrawals,
          backgroundColor: 'transparent',
          borderColor: 'rgba(220,53,69,0.75)',
          borderWidth: 3,
          pointStyle: 'circle',
          pointRadius: 5,
          pointBorderColor: 'transparent',
          pointBackgroundColor: 'rgba(220,53,69,0.75)',
        }]

      },
      options: {
        responsive: true,
        tooltips: {
          mode: 'index',
          titleFontSize: 12,
          titleFontColor: '#000',
          bodyFontColor: '#000',
          backgroundColor: '#fff',
          titleFontFamily: 'Poppins',
          bodyFontFamily: 'Poppins',
          cornerRadius: 3,
          intersect: false,
        },
        legend: {
          display: false,
          labels: {
            usePointStyle: true,
            fontFamily: 'Nunito',
          },
        },
        scales: {
          xAxes: [{
            display: true,
            gridLines: {
              display: false,
              drawBorder: false
            },
            scaleLabel: {
              display: false,
              labelString: 'Month'
            },
            ticks: {
              fontFamily: "Poppins"
            }
          }],
          yAxes: [{
            display: true,
            gridLines: {
              display: false,
              drawBorder: false
            },
            scaleLabel: {
              display: true,
              labelString: "Amount in '000'",
              fontFamily: "Poppins"

            },
            ticks: {
              fontFamily: "Poppins"
            }
          }]
        },
        title: {
          display: false,
          text: 'Normal Legend'
        }
      }
    });
  }
}



</script>

@endsection



