@extends('layouts.sidebar-header')

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="overview-wrap">
      <h2 class="title-1 text-dark">overview</h2>
            </div>
          </div>
        </div>
        <div class="row m-t-25">
          <div class="col-sm-6 col-lg-3">
            <div class="overview-item overview-item--c1">
              <div class="overview__inner">
                <div class="overview-box clearfix">
                  <div class="icon">
                    <i class="zmdi zmdi-account-o"></i>
                     <span class="text-dark">total deposits</span>
                  </div><br>
                  <div class="text">
                    <h2 class="mt-3">
                      @isset($arr1)
                      {{ number_format($arr1['deposits']) }}
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
                    <span>total withdrawals</span>
                  </div>
                  <div class="text">
                       <h2 class="mt-4">
                      @isset($arr1)
                      {{ number_format($arr1['withdrawals']) }}
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
                    <i class="zmdi zmdi-money"></i>
                    <span>savings this month</span>
                  </div>
                  <div class="text">
                    <h2 class="mt-4">
                       @isset($TimedDataArr)
                      {{ number_format($TimedDataArr['TvalueM1']) }}
                      @endisset
                    </h2>
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
                    <span>savings this week</span>
                  </div>
                  <div class="text">
                    <h2 class="mt-4">
                      @isset($TimedDataArr)
                      {{ number_format($TimedDataArr['TvalueM2']) }}
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
              <h3 class="title-2 m-b-40">Savings Line Chart</h3>
              <canvas id="lineChart"></canvas>
            </div>
          </div>
        </div>


        <div class="col-lg-6">
          <div class="au-card m-b-30">
            <div class="au-card-inner">
              <h3 class="title-2 m-b-40">Savings Bar Chart</h3>
              <canvas id="singelBarChart"></canvas>
            </div>
          </div>
        </div>
      </div>

        
      <div class="row nunito-font">
        <div class="col-lg-6">
          <h2 class="title-2 m-b-25 text-info text-left">
           {{ __("Account statistics") }}
          </h2>
        
          <div class="table-responsive m-b-40">
            @isset($memberDataArr)
            <table class="table table-borderless overview-item--c4">
              <thead class="th-dark">
                <tr>
                  <th>Account</th>
                  <th>Account No.</th>
                  <th>Account Value</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Main Savings</td>
                  <td>{{ $memberDataArr['accM'] }}</td>
                  <td class="text-primary">
                    {{ number_format($memberDataArr['valueM']) }}
                  </td>
                </tr>

                <tr>
                  <td>Education Savings</td>
                  <td>{{ $memberDataArr['accE'] }}</td>
                  <td class="text-success">{{ number_format($memberDataArr['valueE']) }}
                  </td>
                </tr>

                <tr>
                  <td>Shares Savings</td>
                  <td>{{ $memberDataArr['accS'] }}</td>
                  <td class="text-info">{{ number_format($memberDataArr['valueS']) }}
                  </td>
                </tr>

                <tr>
                  <td>Retirement Savings</td>
                  <td>{{ $memberDataArr['accR'] }}</td>
                  <td class="text-danger">{{ number_format($memberDataArr['valueR']) }}
                  </td>
                </tr>

              </tbody>
            </table>
            @endisset
          </div>
        </div>

        <div class="col-lg-6">
          <h2 class="title-2 m-b-25 text-info nunito-font text-left">
            Account statistics, this current month [ 
             <span class="text-dark">{{ date('M') }}</span> ]
            </h2>
          <div class="table-responsive m-b-40">
            @isset($memberDataArr)
             @isset($TimedDataArr)
            <table class="table table-borderless overview-item--c3">
              <thead class="th-white">
                <tr>
                  <th>Account</th>
                  <th>Account No.</th>
                  <th>Deposits</th>
                  <th>Withdrawals</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Main Savings</td>
                  <td>{{ $memberDataArr['accM'] }}</td>
                  <td class="text-success">
                    {{ number_format($TimedDataArr['TvalueM1']) }}
                  </td>
                   <td class="text-danger">
                    {{ number_format($TimedDataArr['TvalueM2']) }}
                  </td>
                </tr>

                <tr>
                  <td>Education Savings</td>
                  <td>{{ $memberDataArr['accE'] }}</td>
                 <td class="text-success">
                    {{ number_format($TimedDataArr['TvalueE1']) }}
                  </td>
                   <td class="text-danger">
                    {{ number_format($TimedDataArr['TvalueE2']) }}
                  </td>
                </tr>

                <tr>
                  <td>Shares Savings</td>
                  <td>{{ $memberDataArr['accS'] }}</td>
                 <td class="text-success">
                    {{ number_format($TimedDataArr['TvalueS1']) }}
                  </td>
                   <td class="text-danger">
                    {{ number_format($TimedDataArr['TvalueS2']) }}
                  </td>
                </tr>

                <tr>
                  <td>Retirement Savings</td>
                  <td>{{ $memberDataArr['accR'] }}</td>
                 <td class="text-success">
                    {{ number_format($TimedDataArr['TvalueR1']) }}
                  </td>
                   <td class="text-danger">
                    {{ number_format($TimedDataArr['TvalueR2']) }}
                  </td>
                </tr>

              </tbody>
            </table>
            @endisset
            @endisset
          </div>
        </div>
      </div>

      @endsection



