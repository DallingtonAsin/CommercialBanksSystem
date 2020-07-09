@extends('layouts.sidebar-header')

@section('content')
     <div class="card-table nunito-font">
          <div class="card card-dashboard-table-six">
            <h6 class="card-title">
              <i class="fa fa-th text-success"></i> 
                <span class="pl-3">Help Desk</span>

               </h6>


            <div class="card-body ">

              <form class="form-group" >

             <div class="d-flex justify-content-center">
              <div class="form-group">
                <p><i class="text-info">1. User login page</i><br>
                  Here the user enters credentials i.e username and password
                  to login to able able to access the system.
                </p>
                <img src="{{ asset('data/documentation/login.PNG') }}"
                 class='manuals'/>
                </div>

              <div class="form-group">
                <p><i class="text-info">2. Dashboard</i><br>
                The dashboard page shows the statistics and allows the system user to perform tasks which can
                be selected from the left dashboard.
                </p>
              <img src='{{ asset('data/documentation/manager/dashboard.png') }}'
              class='manuals'/>
              </div>

            </div>


            <div class="d-flex justify-content-center">
              <div class="form-group">
                <p><i class="text-info">3. Applications</i><br>
                  Pending, Approved and Denied applications etc.
                </p>
                <img src="{{ asset('data/documentation/apps/pending-apps.png') }}" class='manuals'/>
                </div>

              <div class="form-group">
                <p><i class="text-info">4. Accounts</i><br>
                Main, Education, Retirement and Shares Accounts.
                </p>
              <img src="{{ asset('data/documentation/accounts/main-account.png') }}"
              class='manuals'/>
              </div>
            </div>

             <div class="form-group">
                <a href="">Find more by</a>
                <a href="" class="text-danger">reading more from this file</a>
              </div>

              </form>
            </div>
          </div>
        </div>



@endsection

