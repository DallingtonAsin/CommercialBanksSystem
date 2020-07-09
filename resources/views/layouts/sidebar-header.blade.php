@include('layouts.header')

@auth
<body class="az-body az-body-sidebar az-light">
  <link href="{{ asset('vendors/css/notification.css') }}" rel="stylesheet">
  <div class="az-sidebar">
    <br>
    <div class="logo colored-icon-1 pl-5">
      <a href="#">
        <img src="{{ asset('images/brand.png') }}" 
        class="co-icon-center-1 pd-4 br-70 d-white">
      </a><span class="pl-2 nunito-font">{{ config('app.name') }}</span>
    </div>

    @php
    ($user_role == "Administrator")?
    $show = "" : $show = "";
    @endphp

    <div class="az-sidebar-body nunito-font">
      <ul class="nav">
        <!-- section[0] of Applications shared by both Admin and members -->
        <li class="nav-label">Main Menu</li>

        <li>
          <a href="{{ route('home') }}" class="nav-link">
            <i class="typcn typcn-home"></i>Home</a>
          </li>

          <li class="nav-item active {{ $show }} ">
            <a href="" class="nav-link with-sub">
              <i class="typcn typcn-clipboard"></i>Dashboard</a>

              <!-- first section for the Admin ordered chronologically  -->

              @can('isAdmin')
              <ul class="nav-sub">


                @can('isSuperAdmin')
                <li class="nav-sub-item">
                  <a href="{{ Route('roles.home')}}" class="nav-sub-link">
                   <i class="fa fa-users pr-2"></i>
                   Roles
                 </a>
               </li>

               <li class="nav-sub-item">
                <a href="{{ Route('staff')}}" class="nav-sub-link">
                  <i class="fa fa-user-friends pr-2"></i>
                  Staff
                </a>
              </li>
              @endcan

              <li class="nav-sub-item">
                <a href="{{ Route('members')}}" class="nav-sub-link">
                 <i class="fa fa-user-friends pr-2"></i>
                 Members
               </a>
             </li>

              @can('isSuperAdmin')
             <li class="nav-sub-item">
                <a href="{{ Route('general.performance')}}" class="nav-sub-link">
                  <i class="fa fa-bar-chart pr-2"></i>
                  General Performance
                </a>
              </li>
              @endcan

           </ul>
         </li>

         @endcan

         <!-- first section for the member ordered chronologically  -->

         @can('isMember') 
         <ul class="nav-sub">
          <li class="nav-sub-item"><a href="{{ Route('home') }}" class="nav-sub-link"><i class="fa fa-th pr-2"></i>Overview</a></li>
        </ul>
        @endcan

        <li class="nav-item">
          <a href="" class="nav-link with-sub"><i class="fa fa-money"></i>Loans</a>
          <ul class="nav-sub">

            @can('isAdmin')
            <li class="nav-sub-item"><a href="{{ Route('loanees.home') }}" class="nav-sub-link"><i class="fa fa-user pr-2"></i> Loanees</a></li>
            <li class="nav-sub-item"><a href="{{ Route('defaulters.home') }}" class="nav-sub-link"><i class="fa fa-user pr-2"></i> Defaulters</a></li>
            @endcan

            <li class="nav-sub-item"><a href="{{ Route('dueLoans.home') }}" class="nav-sub-link"><i class="fa fa-forward pr-2"></i>Due Loans</a></li>
            <li class="nav-sub-item"><a href="{{ Route('paidLoans.home') }}" class="nav-sub-link"><i class="fa fa-check-circle pr-2"></i>Paid Loans</a></li>
            <li class="nav-sub-item"><a href="{{ Route('loan-history.index') }}" class="nav-sub-link">
              <i class="fa fa-history pr-2"></i>Loan history</a></li>
              <li class="nav-sub-item"><a href="{{ Route('loan.settings') }}" class="nav-sub-link"><i class="fa fa-forward pr-2"></i>Interest rates</a></li>

              <li class="nav-item">
                <a href="" class="nav-link with-sub">
                  <i class="fa fa-folder-open"></i>Loan requests
                </a>
                <ul class="nav-sub">
                 <li class="nav-sub-item"><a href="{{ route('p-loan-requests') }}" class="nav-sub-link"><i class="fa fa-play-circle pr-2"></i>Pending requests</a></li>
                 <li class="nav-sub-item"><a href="{{ route('a-loan-requests')}}" class="nav-sub-link"><i class="fa fa-info-circle pr-2"></i>Approved requests</a></li>
                 <li class="nav-sub-item"><a href="{{ Route('d-loan-requests') }}" class="nav-sub-link"><i class="fa fa-times-circle pr-2"></i>Denied requests</a></li>
               </ul>
             </li>


           </ul>
         </li> 

         
         @can('isMember')
         <li class="nav-item">
          <a href="" class="nav-link with-sub"><i class="fa fa-address-book"></i>Forms</a>
          <ul class="nav-sub">
           <li class="nav-sub-item"><a href="{{ route('loan-application.index') }}" class="nav-sub-link"><i class="fa fa-file-text pr-2"></i>Loan</a></li>
           <li class="nav-sub-item"><a href="{{ Route('general-form') }}" class="nav-sub-link"><i class="fa fa-file pr-2"></i>A/C application</a></li>
         </ul>
       </li>

       <li class="nav-item">
        <a href="" class="nav-link with-sub"><i class="fa fa-user-friends"></i>Accounts</a>
        <ul class="nav-sub">
         
          <li class="nav-sub-item"><a href="{{ route('MainSavingAccounts.home') }}" class="nav-sub-link"><i class="fa fa-gbp pr-2"></i>Main Savings</a></li>
          <li class="nav-sub-item"><a href="{{ route('education.home')}}" class="nav-sub-link"><i class="fa fa-rub pr-2"></i>Education Savings</a></li>
          <li class="nav-sub-item"><a href="{{ Route('retirement.home') }}" class="nav-sub-link"><i class="fa fa-rmb pr-2"></i>Retirement Savings</a></li>
           <li class="nav-sub-item"><a href="{{ Route('shares.home') }}" class="nav-sub-link"><i class="fa fa-euro pr-2"></i>Shares Savings</a></li>
        </ul>
      </li>
      @endcan


      <!-- middle section of Applications shared by both Admin and members -->

      <li class="nav-item">
        <a href="" class="nav-link with-sub"><i class="fa fa-file-text"></i>Applications</a>
        <ul class="nav-sub">
          <li class="nav-sub-item"><a href="{{ route('pendingApplications') }}" class="nav-sub-link"><i class="fa fa-play-circle pr-2"></i>
          Pending applications</a></li>
          <li class="nav-sub-item">
            <a href="{{ Route('approvedApplications') }}" class="nav-sub-link"><i class="fa fa-check-circle pr-2"></i> Approved applications</a></li>
            <li class="nav-sub-item"><a href="{{ Route('deniedApplications') }}" class="nav-sub-link"><i class="fa fa-times-circle pr-2"></i>
            Denied applications</a></li>

          </ul>
        </li>

        <!-- second section for the Admin ordered chronologically  -->

        @can('isAdmin')
        <li class="nav-item">
          <a href="" class="nav-link with-sub"><i class="fa fa-user-cog"></i>Administration</a>
          <ul class="nav-sub">
            <li class="nav-sub-item"><a href="{{ route('users.index') }}" class="nav-sub-link"><i class="fa fa-user-edit pr-1"></i>
            Manage users</a></li>
            <li class="nav-sub-item"><a href="{{ Route('inactive-a') }}" class="nav-sub-link">
              <i class="fa fa-user pr-2"></i>Inactive users by admin</a></li>
              <li class="nav-sub-item"><a href="{{ Route('inactive-s') }}" class="nav-sub-link">
                <i class="fa fa-user pr-2"></i>Inactive users by system</a></li>
                <!-- <li class="nav-sub-item"><a href="" class="nav-sub-link">
                  <i class="fa fa-unlock-alt pr-2"></i>Retrieve user password</a></li> -->
                </ul>
              </li>

              <li class="nav-item">
                <a href="" class="nav-link with-sub"><i class="fa fa-clipboard-check"></i>Manage accounts</a>
                <ul class="nav-sub">
                  <li class="nav-sub-item"><a href="{{ route('MainSavingAccounts.home') }}" class="nav-sub-link"><i class="fa fa-gbp pr-2"></i>Main Savings</a></li>
                  <li class="nav-sub-item"><a href="{{ route('education.home')}}" class="nav-sub-link"><i class="fa fa-rub pr-2"></i>Education Savings</a></li>
                  <li class="nav-sub-item"><a href="{{ Route('retirement.home') }}" class="nav-sub-link"><i class="fa fa-rmb pr-2"></i>Retirement Savings</a></li>
                  <li class="nav-sub-item"><a href="{{ Route('shares.home') }}" class="nav-sub-link"><i class="fa fa-euro pr-2"></i>Shares Savings</a></li>
                </ul>
              </li>

                 <li class="nav-item">
                    <a href="" class="nav-link with-sub"><i class="fa fa-gear"></i>Account settings</a>
                    <ul class="nav-sub">
                      <li class="nav-sub-item"><a href="{{ route('main-settings.home') }}" class="nav-sub-link"><i class="fa fa-gear pr-2"></i> Main </a></li>
                       <li class="nav-sub-item"><a href="{{ route('education-settings.home') }}" class="nav-sub-link"><i class="fa fa-gear pr-2"></i> Education </a></li>
                        <li class="nav-sub-item"><a href="{{ route('retirement-settings.home') }}" class="nav-sub-link"><i class="fa fa-gear pr-2"></i> Retirement </a></li>
                         <li class="nav-sub-item"><a href="{{ route('shares-settings.home') }}" class="nav-sub-link"><i class="fa fa-gear pr-2"></i> Shares </a></li>
                    </ul>
                  </li>

              @endcan


              <!-- second section for the members ordered chronologically  -->

              @can('isMember')
           <!--        <li class="nav-item">
                    <a href="" class="nav-link with-sub"><i class="fa fa-money-bill-alt"></i>Beneficiaries</a>
                    <ul class="nav-sub">
                      <li class="nav-sub-item"><a href="{{ Route('overview') }}" class="nav-sub-link">Shares</a></li>
                      <li class="nav-sub-item"><a href="" class="nav-sub-link">Main Savings</a></li>
                      <li class="nav-sub-item"><a href="" class="nav-sub-link">Education Savings</a></li>
                      <li class="nav-sub-item"><a href="" class="nav-sub-link">Retirement Savings</a></li>
                    </ul>
                  </li> -->

                <!--   <li class="nav-item">
                    <a href="" class="nav-link with-sub"><i class="fa fa-list-ol"></i>Resources</a>
                    <ul class="nav-sub">
                      <li class="nav-sub-item"><a href="{{ Route('overview') }}" class="nav-sub-link">Shares</a></li>
                      <li class="nav-sub-item"><a href="" class="nav-sub-link">Main Savings</a></li>  
                    </ul>
                  </li> -->

                  @endcan


                  <!-- section shared by both Admin and members -->

                  <li class="nav-item">
                    <a href="" class="nav-link with-sub"><i class="fa fa-dollar"></i>Investments</a>

                    <ul class="nav-sub">
                      <li class="nav-sub-item"><a href="{{ Route('investments.running') }}" class="nav-sub-link"><i class="fa fa-check-circle pr-2"></i>Running investments </a></li>
                      <li class="nav-sub-item"><a href="{{ Route('investments.pending') }}" class="nav-sub-link"><i class="fa fa-play-circle pr-2"></i>Planned Investments</a></li>
                    </ul>
                  </li>

                  <li class="nav-item">
                    <a href="" class="nav-link with-sub"><i class="fa fa-credit-card"></i>Payments</a>

                    <ul class="nav-sub">

                      <li class="nav-sub-item"><a href="{{ Route('creditcard.index') }}" class="nav-sub-link"><i class="fa fa-credit-card pr-3"></i> Credit Card </a></li>

                      <li class="nav-sub-item"><a href="{{ Route('mobileMoney.index') }}" class="nav-sub-link"><i class="fa fa-gbp pr-4"></i> Mobile Money </a></li>

                      <li class="nav-sub-item"><a href="{{ Route('funds.transfer') }}" class="nav-sub-link"><i class="fa fa-money pr-2"></i> Funds Transfer </a></li>
                    </ul>
                  </li>


                  @can('isSuperAdmin')
                  <li class="nav-item">
                    <a href="" class="nav-link with-sub"><i class="typcn typcn-cloud-storage-outline"></i>System audit</a>
                    <ul class="nav-sub">
                      <li class="nav-sub-item"><a href="{{ route('logs.index') }}" class="nav-sub-link"><i class="fa fa-download pr-2 "></i> Activity logs</a></li>
                    </ul>
                  </li>
                  @endcan


                  <li class="nav-item">
                    <a href="" class="nav-link with-sub"><i class="fa fa-comments"></i>Communication</a>

                    <ul class="nav-sub">
                     <li class="nav-sub-item"><a href="{{ route('mail.index') }}" class="nav-sub-link"><i class="fa fa-envelope pr-2"></i>Send Email</a></li>

                     <li class="nav-item">
                      <a href="" class="nav-link with-sub"><i class="fa fa-comments pr-2"></i>Events</a>

                      <ul class="nav-sub">
                       <li class="nav-sub-item"><a href="{{ route('events.index') }}" class="nav-sub-link"><i class="fa fa-hand-o-right pr-2"></i>Events</a></li>

                       <li class="nav-sub-item"><a href="{{ Route('calendar.home') }}" class="nav-sub-link">
                        <i class="fa fa-calendar-plus pr-2"></i> Calendar</a></li>

                        @can('isAdmin')
                        <li class="nav-sub-item"><a href="{{ Route('events.showForm') }}" class="nav-sub-link"><i class="fa fa-plus-circle pr-2"></i>Add Event</a></li>
                        @endcan
                      </ul>
                    </li>
                    
                  </ul>
                </li>


                <li class="nav-item">
                  <a href="" class="nav-link with-sub"><i class="typcn typcn-info"></i>Information desk</a>
                  <ul class="nav-sub">
                    @can('isAdmin')
                    <li class="nav-sub-item"><a href="{{ route('userguide' )}}" class="nav-sub-link"><i class="fa fa-info-circle pr-2"></i>Help Desk</a></li>
                    @endcan
                    @can('isMember')
                    <li class="nav-sub-item"><a href="{{ route('userguide' )}}" class="nav-sub-link"><i class="fa fa-info-circle pr-2"></i>Help Desk</a></li>
                    @endcan
                    <li class="nav-sub-item"><a href="{{ route('aboutCST') }}" class="nav-sub-link"><i class="fa fa-globe pr-2"></i>About CST</a></li>
                  </ul>
                </li>

              </ul>
            </div>
          </div>


          <!-- top header that has notifications and user profile dropdown -->
          <div class="az-content az-content-dashboard-five">
            <div class="az-header">

              <div class="container-fluid">
                <div class="az-header-left">
                  <a href="" id="azSidebarToggle" class="az-header-menu-icon"><span></span></a>
                </div>

                <div class="az-header-center nunito-font">
                  <h5 class="nav-label colored-icon-1">{{ config('app.name_abbr')}}</h5>
                </div>


                <div class="noti__item js-item-menu">
                  <i class="zmdi zmdi-comment-more icon-white"></i>
                  <span class="quantity">1</span>
                  <div class="mess-dropdown js-dropdown">
                    <div class="mess__title">
                      <p>You have 2 news message</p>
                    </div>
                    <div class="mess__item">
                      <div class="image img-cir img-40">
                        <img src="images/icon/avatar-06.jpg" alt="Michelle Moreno" />
                      </div>
                      <div class="content">
                        <h6>Michelle Moreno</h6>
                        <p>Have sent a photo</p>
                        <span class="time">3 min ago</span>
                      </div>
                    </div>
                    <div class="mess__item">
                      <div class="image img-cir img-40">
                        <img src="images/icon/avatar-04.jpg" alt="Diane Myers" />
                      </div>
                      <div class="content" id="notification">
                        <h6>Diane Myers</h6>
                        <p>You are now connected on message</p>
                        <span class="time">Yesterday</span>
                      </div>
                    </div>

                    <div class="mess__footer">
                      <a href="#">View all messages</a>
                    </div>
                  </div>
                </div>



                <div class="noti__item js-item-menu">
                  <i class="zmdi zmdi-email icon-white"></i>
                  <span class="quantity">1</span>
                  <div class="email-dropdown js-dropdown">
                    <div class="email__title">
                      <p>You have 3 New Emails</p>
                    </div>
                    <div class="email__item">
                      <div class="image img-cir img-40">
                        <img src="images/icon/avatar-06.jpg" alt="Cynthia Harvey" />
                      </div>
                      <div class="content">
                        <p>Meeting about new dashboard...</p>
                        <span>Cynthia Harvey, 3 min ago</span>
                      </div>
                    </div>
                    <div class="email__item">
                      <div class="image img-cir img-40">
                        <img src="images/icon/avatar-05.jpg" alt="Cynthia Harvey" />
                      </div>
                      <div class="content">
                        <p>Meeting about new dashboard...</p>
                        <span>Cynthia Harvey, Yesterday</span>
                      </div>
                    </div>
                    <div class="email__item">
                      <div class="image img-cir img-40">
                        <img src="images/icon/avatar-04.jpg" alt="Cynthia Harvey" />
                      </div>
                      <div class="content">
                        <p>Meeting about new dashboard...</p>
                        <span>Cynthia Harvey, April 12,,2018</span>
                      </div>
                    </div>
                    <div class="email__footer">
                      <a href="#">See all emails</a>
                    </div>
                  </div>
                </div>



                <div class="noti__item js-item-menu">
                  <i class="zmdi zmdi-notifications icon-white"></i>
                  <span class="quantity">3</span>
                  <div class="notifi-dropdown js-dropdown">
                    <div class="notifi__title">
                      <p>You have 3 Notifications</p>
                    </div>
                    <div class="notifi__item">
                      <div class="bg-c1 img-cir img-40">
                        <i class="zmdi zmdi-email-open"></i>
                      </div>
                      <div class="content">
                        <p>You got an email notification</p>
                        <span class="date">April 12, 2018 06:50</span>
                      </div>
                    </div>
                    <div class="notifi__item">
                      <div class="bg-c2 img-cir img-40">
                        <i class="zmdi zmdi-account-box"></i>
                      </div>
                      <div class="content">
                        <p>Your account has been blocked</p>
                        <span class="date">April 12, 2018 06:50</span>
                      </div>
                    </div>
                    <div class="notifi__item">
                      <div class="bg-c3 img-cir img-40">
                        <i class="zmdi zmdi-file-text"></i>
                      </div>
                      <div class="content">
                        <p>You got a new file</p>
                        <span class="date">April 12, 2018 06:50</span>
                      </div>
                    </div>
                    <div class="notifi__footer">
                      <a href="#">All notifications</a>
                    </div>
                  </div>
                </div>

                <div class="account-wrap">
                  <div class="account-item clearfix js-item-menu">
                    <div class="image">
                      @isset(Auth::user()->image)
                      <img src="{{ asset('uploads/images/'.$user_role.'/'.Auth::user()->image.'') }}" alt="">
                      @endisset
                      @empty(Auth::user()->image)
                      <img src="{{ asset('uploads/images/default/user.png') }}"
                      alt="{{Auth::user()->name}}" class="az-img-user pull-right" >
                      @endempty
                    </div>
                    <div class="content pt-4">
                      <a class="js-acc-btn username" href="#">{{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}</a>
                    </div>
                    <div class="account-dropdown js-dropdown">
                      <div class="info clearfix">
                        <div class="image">
                          <a href="#">
                           @isset(Auth::user()->image)
                           <img src="{{ asset('uploads/images/'.$user_role.'/'.Auth::user()->image.'') }}" alt="">
                           @endisset
                           @empty(Auth::user()->image)
                           <img src="{{ asset('uploads/images/default/user.png') }}"
                           alt="{{Auth::user()->name}}" class="az-img-user pull-right" >
                           @endempty
                         </a>
                       </div>
                       <div class="content">
                        <h5 class="name">
                          <a href="#">{{{ isset(Auth::user()->name) ? Auth::user()->name : Auth::user()->email }}}</a>
                        </h5>
                        <span class="email capitalize">{{{ $user_role }}}</span>
                      </div>
                    </div>
                    <div class="account-dropdown__body">
                     <div class="account-dropdown__item">
                      <a href="{{ Route('profile.index') }}" class="dropdown-item"><i class="zmdi zmdi-account"></i> My Profile</a>
                    </div>
                    <div class="account-dropdown__item">
                      <a href="{{ route('profile-settings')}}" class="dropdown-item"><i class="typcn typcn-edit"></i> Edit Profile</a>
                    </div>
                    <div class="account-dropdown__item">
                      <a href="{{ route('logs.index' )}}" class="dropdown-item"><i class="typcn typcn-time"></i> Activity Logs</a>
                    </div>
                    <div class="account-dropdown__item">
                      <a href="{{route('account-settings')}}" class="dropdown-item">
                        <i class="typcn typcn-cog-outline"></i> Account settings</a>
                      </div>
                      <div class="account-dropdown__item">
                        <a class="dropdown-item" href="{{ route('signout') }}">
                          <i class="typcn typcn-power-outline"></i>{{ __('Sign Out') }}
                        </a>
                      </div>
                    </div>

                  </div>
                </div>
              </div>

            </div>
          </div>


          <!-- main content that is filled by other external sections -->
          <div class="az-content-body">
           @yield('content')
         </div>


         <!-- footer layout of the application -->

         <div class="az-footer copyright ht-45 nunito-font">
          <div class="container-fluid pt-4 ht-100p">
           <span class="text-center font-md">Copyright &copy; {{ date('Y')}} Code Solution Tech:: Dallington companies.<br> All rights reserved. Template by <a href="https://codesolutionug.xyz">Code Solution Tech</a>.</span>
         </div>
       </div>

     </div>



     <script>
      $(document).ready(function(){
        var title='List of loanees';
        var table = $('.loanees-table');
        smartTable(table,title);
                                          // $('.loanees-table').dataTable();
                                        });
                                      </script>


                                      <script src="{{ asset('vendors/vendor/bootstrap-4.1/popper.min.js') }}"></script>

                                      <!-- Vendor JS       -->
                                      <script src="{{ asset('vendors/js/ionicons.js') }}"></script>
                                      <script src="{{ asset('vendors/vendor/slick/slick.min.js') }}">
                                      </script>
                                      <script src="{{ asset('vendors/vendor/wow/wow.min.js') }}"></script>
                                      <script src="{{ asset('vendors/vendor/animsition/animsition.min.js') }}"></script>
                                      <script src="{{ asset('css/vendors/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js') }}">
                                      </script>
                                      <script src="{{ asset('vendors/vendor/counter-up/jquery.waypoints.min.js') }}"></script>
                                      <script src="{{ asset('vendors/vendor/counter-up/jquery.counterup.min.js') }}">
                                      </script>
                                      <script src="{{ asset('vendors/vendor/circle-progress/circle-progress.min.js') }}"></script>
                                      <script src="{{ asset('vendors/vendor/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
                                      <script src="{{ asset('vendors/vendor/chartjs/Chart.bundle.min.js') }}"></script>
                                      <script src="{{ asset('vendors/vendor/select2/select2.min.js') }}">
                                      </script>

                                      <!-- Main JS-->
                                      <script src="{{ asset('js/main.js') }} "></script>
                                    </body>
                                    @endauth
                                    </html>

