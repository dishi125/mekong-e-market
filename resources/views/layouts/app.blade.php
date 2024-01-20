<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mekong e-Market</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css">

    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>


    @yield('css')
    <style>

        body{
            font-size: 15px !important;
        }
        button, button:focus, button:active, button:hover{
            outline: none !important;
            border:none !important;
        }
        .content_table tbody{
            background-color: #f1f1f1;
        }

        .content-wrapper {
            background: white !important;
        }

        .export{
            position: absolute;
            left: 5%;
            top: 70%;
            color: #818286;
            font-weight: bold;
        }

        .main-sidebar {
            padding: unset !important;
            width: 270px;
        }

        .content-wrapper, .main-footer {
            margin-left: 270px;
        }

        .skin-blue .wrapper, .skin-blue .main-sidebar, .skin-blue .left-side {
            background-color: #804A1E;
        }

        .main-header .navbar {

            /*min-height: 85px;*/
            margin-left: 270px;
        }


        .navbar-nav {
            margin: 5px 16px 0px 5px;
        }

        .dropdown-toggle span {
            color: #807772;
            font-size: 18px;
        }

        .navbar-nav .dropdown-toggle .fa {
            color: #04A34F;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar-nav > .user-menu .user-image {
            float: left;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            margin-right: 17px;
            margin-top: -11px;
        }

        .skin-blue .main-header .navbar .nav > li > a:hover {
            background: transparent !important;
        }

        .main-header .navbar .nav > li > a > .label {
            border-radius: 50%;
        }

        .clr-green {
            color: #00A652;
        }

        .main-header .navbar .navbar-header {
            margin: 16px 30px;
        }

        .navbar .navbar-header .heade-icon {
            display: inline-block;
        }

        .navbar .navbar-header .heade-icon i {
            color: #00A652;
            font-size: 40px;
        }

        .bg-green {
            color: #00A652;
        }

        .titel-nav {
            display: inline-block;
            vertical-align: top;
        }

        .titel-nav span {
            display: inline;
        }

        .logo-sidebar {
            width: 215px;
            height: 140px;
            display: block;
            margin: 10px auto;
            background: white;
            padding: 8PX 45PX;
            border-bottom: 2px solid #9F9792;
        }

        .user-panel {
            border-bottom: 2px solid #CFD2D3;
        }

        .sidebar-menu li {
            font-size: 16px;
            border-bottom: 2px solid #CFD2D3;
            text-transform: capitalize;
            letter-spacing: 0.3px;
            position: relative;
            /*font-weight: 100;*/
        }

        .sidebar-menu li > a > .pull-right-container {
            position: inherit;
            right: unset;
            top: unset;
            margin-top: unset;
        }

        .sidebar-menu li span {
            margin-left: 8px;
        }

        .skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.menu-open > a, .skin-blue .sidebar-menu > li:hover, .skin-blue .sidebar-menu > li.active, .skin-blue .sidebar-menu > li.menu-open {
            color: #CFD2D3;
            background: #2C1A0C;
        }

        .sidebar-menu li.active {
            color: #CFD2D3;
            background: #2C1A0C;
        }

        .sidebar-menu li a {
            display: table;
            /*width: 100%;*/
        }

        .sidebar-menu li a .fa-circle {
            font-size: 24px;
        }

        .sidebar-menu li a span {
            display: table-cell;
            padding-left: inherit;
            vertical-align: middle;
            width: 100%;
        }

        .skin-blue .sidebar-menu > li.active > a {
            color: #CFD2D3;
            border: unset;
            background: #2C1A0C;
        }

        .skin-blue .sidebar-menu > li > .treeview-menu {
            margin: -5px 0px 0px 22px;
            background: transparent;


        }

        .skin-blue .sidebar-menu > li > .treeview-menu li {
            color: #CFD2D3;

            border: unset;

        }

        .skin-blue .sidebar-menu > li > .treeview-menu li a {
            font-size: 14px;
            color: #CFD2D3;

        }

        .skin-blue .sidebar-menu > li > .treeview-menu li.active a,
        .skin-blue .sidebar-menu > li > .treeview-menu li:hover a {
            color: #e5e8e9;

        }

        /*-------- datetimepicker css (clock)----------*/
        .table-display .time-picker .list-unstyled table,
        .table-display .time-picker .list-unstyled table th,
        .table-display .time-picker .list-unstyled table td {
            border: none !important;
        }
        .table-display .list-unstyled .picker-switch table.table-condensed td:first-child {
            text-align: right;
        }
        .table-display .list-unstyled .picker-switch table.table-condensed td:last-child {
            width: 30px;
            text-align: center;
        }
        .table-display .time-picker .list-unstyled table td span:hover {
            background: none !important;
        }
        .table-display .timepicker table, .table-display .timepicker table th, .table-display .timepicker table td{
            border: none !important;
        }
        .pull-right-container {
            transform: rotateY(180deg);
        }
        .fast-buy-div{
            width: 7%;
        }
        .fast-buy-div button{
            border: none;
            outline: none;
            background: #ffffff;
        }
        .fast-buy-div-color{
            background: #81828630 !important;
        }

        .content_table table, .content_table table th, .content_table table td {
            border: 2px solid #9ba2ab !important;
            color: #9198a1;
            font-weight: normal;
            text-align: center;
        }

        .content_table table tbody td {
            color: #2C2A2B;
            text-align: left;
            vertical-align: top;
        }

        .content_table table.table-horizontal-border-none tbody td {
            border-bottom: 0px !important;
            border-top: 0px !important;
        }

        .table-display table tbody td .user-image-round {
            display: block;
            margin: 0 auto;
            width: 50px;
            border-radius: 50%;
        }

        .table-display table tbody td .product-image-round {
            display: block;
            margin: 0 auto;
            width: 70px;
            border-radius: 10%
        }

        .table-display table tbody td .product-image {
            width: 100px;
            border-radius: 10%;
            border: 1px solid;
        }

        .table-display table tbody td .product-name {
            display: inline-block;
            vertical-align: top;
            font-weight: bold;

        }

        table.sub-table, table.sub-table td{
            margin-bottom:0px !important;
            border: none !important;
            text-align: left !important;
        }

        table.sub-table td{
            padding: 5px !important;
        }

        .text-gray-color{
            color: #999999;
            font-size: 14px;
        }

        table.sub-table .product-name {
            font-size : 16px;
            padding-bottom: 5px;
        }
        .search-area {
            margin: 10px 0px;
        }

        .to-from-text {
            color: #9198a1;
            display: block;
            line-height: 2.3;
            padding-left: 10px;
            text-align: left;
            vertical-align: middle;
        }

        .input-group {
            border: 2px solid #9198a1;
            border-radius: 30px;
            overflow: hidden;
            min-width: 155px;
        }
        @media screen and (min-width: 1024px) {
            .input-group {
                min-width:unset;
            }
        }
        .input-group .form-control.search {
            border: 0px;

        }

        .input-group .input-group-addon {
            background: #818286;
            color: #e5e8e9;
            border: 0px;
        }

        .input-group .input-group-addon label {
            margin-top: 3px;

        }

        .text-center {
            text-align: center !important;
        }

        .clock,.fast-buy-div {
            padding: 2px 6px;
            text-align: center;
            cursor: pointer;
        }

        .fast-buy{
            display: inline-flex;
            width: 50px;
            padding: 2px 4px;
            line-height: initial;
            color: #818286;
            font-size: 13px;
            font-weight: 600;
        }

        .action-icon .fa {
            color: #00A652;
            font-size: 20px;
            /*padding: 0px 20px;*/
            padding: 0px 10px;
        }

        .btn-tab {
            font-size: 18px !important;
            border: 1px solid #afb0b4;
            color: #555557;
            border-radius: 10px;
            min-width: 150px;
        }

        .btn-tab:active, .btn-tab.active {
            /*font-size: 18px !important;*/
            background: #afb0b4;
            color: white;
            outline: 0px;
        }

        .btn-tab:active:focus, .btn-tab:focus {
            outline: 0;
        }

        form label {
            color: #7F7F81;
            font-size: 16px;
            vertical-align: middle;
        }

        form .form-control {

            background: #F3F3F5;
            border-radius: 50px;
            border: 0px;
            padding-left: 20px;
        }

        form input[type=text], form select {
            height: 30px !important;
        }

        .btn-submit {
            background: #056839;
            color: white;
            padding: 4px 30px;
            border-radius: 8px;
        }

        .btn-cancel {
            background: #F57977;
            color: white;
            padding: 4px 30px;
            border-radius: 8px;
        }

        .btn-submit:hover, .btn-cancel:hover, .btn-submit:focus, .btn-cancel:focus {
            color: white;

        }

        hr {

            border: 1px solid #CECED0;
            width: 98%;
            align-self: center;

        }

        .pagination {
            display: inline-block;
            padding-left: 0;
            margin-left: 16px;
            border-radius: 4px;
            margin-top: -5px;
        }

        .pagination > li > a, .pagination > li > span {

            padding: 2px 9px;
            margin: 0px 4px;
            border-radius: 26px;
            border: 0px;
            color: #8A9094;
            background: #E5E6E7;
        }

        .pagination > li:hover > a, .pagination > li:hover > span, .pagination > li.active > a, .pagination > li.active > span {
            background: #F57977;
            border: 0px;
            color: #767c80;

        }

        .green-page .pagination > li:hover > a, .green-page .pagination > li:hover > span, .green-page .pagination > li.active > a, .green-page .pagination > li.active > span {
            background: #00A653;
            border: 0px;
            color: #E6E6E7;
        }

        .green-page .pagination {
            margin-top: 10px;
        }

        .parpage-display {
            margin-top: 10px;
        }

        .pagination > li > a, .pagination > li > span {
            padding: 2px 6px;
            font-size: 10px;
        }

        .pagination > li:last-child > a, .pagination > li:last-child > span {
            border-radius: 26px;
            border: 0px;
            color: #8A9094;
        }

        .pagination > li:first-child > a, .pagination > li:first-child > span {
            border-radius: 26px;
            border: 0px;
            color: #8A9094;
        }

        .select-box {
            position: relative;
        }

        .select-box .fa-caret-right {
            position: absolute;
            color:#999999;
            right: 20px;
            background: #F3F3F5;
            width: 10px;
            font-size: 18px;
            top: 22%;
            position: absolute;

        }

        textarea {
            resize: none;
            min-height: 6rem;
            max-height: 6rem;
            border-radius: 15px !important;
        }

        .fa-star {
            color: #D2D2D4;
        }

        .fa-star.checked {
            color: #59585D;
        }

        .reviews-user {
            font-size: 13px;
        }

        .user-display-image {
            border-radius: 50%;
        }

        .user-date span {
            margin-top: 10px;
        }

        .bg-gray1 {
            background: #BCBDC1;
            padding: 10px;
            margin-top: -15px;
            color: #7c7c7e !important;
        }

        .font {
            color: #7c7c7e;
        }

        .time-picker table {
            border: none !important;
        }
        input[type=radio] {
            padding: 0.5em;
            -webkit-appearance: none;
            outline: 0.1em solid #bfbfbf !important;
            font-size: 12px;
        }

        input[type=radio]:checked {
            display: inline-block;
            background-color: #bfbfbf;
            outline: 0.1em solid #bfbfbf !important;
        }

        .radio_button {
            font-size: 15px;
            font-weight: 100;
        }

        textarea.border_radius {
            border-radius: 15px;
        }

        .search_by_text {
            cursor: pointer;
        }

        .table-display .content_table {
            position: relative;
        }

        .content_table .green-page {
            position: absolute;
            top: -46px;
            right: 29%;
        }

        table th {
            vertical-align: middle !important;
        }

        .margin-top-0{
            margin-top: 0px;
        }

        .font-size-25{
            font-size: 25px;
        }

        #live-detail h5{
            font-size: 15px;
        }

        .time-picker .bootstrap-datetimepicker-widget{
            top: 39px !important;
        }

        .clock .display-time{
            font-size: 12px;
            position: absolute;
            bottom: 95%;
            left: 15%;
            color: #666666;
        }

        .main-header .navbar .navbar-header .main-header-sub-title {
            display: block;
            margin-left: 10px;
            font-size: 17px;
            text-transform: capitalize;
            color: #5E5E5E;
        }

        .main-header .navbar .navbar-header .main-header-title {
            line-height: 20px;
            display: block;
            font-size: 20px;
            margin-left: 10px;
            color: #00A652;
            text-transform: capitalize;
        }
        .skin-blue .main-header .navbar {
            background-color: #fff;
            border-bottom: 2px solid #A79F9C;
        }
        #select-page, .pull-left #user_type,#export_type{
            padding: 8px;
            background: #f6f6f6;
            border: 1px solid #818286;
        }
        #select-page, .pull-left #main_cat,#sub_cat,#buyer_filer,#payment_filer,#paymenttype_filer{
            padding: 8px;
            background: #f6f6f6;
            border: 1px solid #818286;
        }

        @if(Request::is('home*'))
        .navbar-nav {
            margin: 5px 142px 0px 5px;
        }
        .navbar-nav .dropdown-toggle .fa {
            color: #0d8fde;
        }
        .skin-blue .main-header .navbar {
             border-bottom: 0px ;
        }
        .main-header .navbar .navbar-header .main-header-title {
            line-height: 29px;
            font-size: 25px;
        }
        @endif
        #ui-datepicker-div .ui-state-highlight {background-color: #b5651d;border-color: #717171;color: #fdfcfc;}
        #ui-datepicker-div .ui-state-active {background-color: rgba(184, 100, 41, 0.6);border-color: #717171;color: #fdfcfc}
        /*.ui-datepicker {*/
        /*    width: 14em!important;*/
        /*}*/
        .ui-datepicker {
            width: 17em!important;
            padding: .2em .2em 0!important;
            /*display: none!important;*/
            background: #f2f0ef !important
        }
        .ui-datepicker .ui-datepicker-title {
            margin: 0 2.3em!important;
            line-height: 1.8em!important;
            text-align: center!important;
            color:#FFFFFF!important;
            background:#804A1E!important;
            border-radius: 125px!important;
        }
        .ui-datepicker table {
            /*width: 14em!important;*/
            font-size: .7em!important;
            border-collapse: collapse!important;
            font-family:verdana!important;
            margin: 0 0 .4em!important;
            color:#000000!important;
            background:#f4f4f2!important
        }
        .ui-datepicker td {
            border: 0!important;
            padding: 1px!important;
        }
        .ui-datepicker td span,
        .ui-datepicker td a {
            display: block!important;
            padding: .8em!important;
            text-align: right!important;
            text-decoration: none!important;
        }
        .bootstrap-datetimepicker-widget.timepicker-picker table td,
        .bootstrap-datetimepicker-widget.timepicker-picker table td span,
        .bootstrap-datetimepicker-widget.timepicker-picker table td a span
        {height: 30px; line-height: 30px; width: 30px; padding:0px;}
        .bootstrap-datetimepicker-widget.dropdown-menu {width: auto;}
        .bootstrap-datetimepicker-widget .datepicker table {width: 19em;}
        .table-condensed>tbody>tr>td, .table-condensed>tbody>tr>th, .table-condensed>tfoot>tr>td, .table-condensed>tfoot>tr>th, .table-condensed>thead>tr>td, .table-condensed>thead>tr>th {
            padding: unset!important;
        }
        .bootstrap-datetimepicker-widget a[data-action]{
            padding: unset!important;
        }
        .table-condensed >.btn{
            padding: unset!important;
        }
        .table-condensed  span{
            color: #804a1e!important;
        }
        .table-condensed td button{
            background-color: #804a1e!important;
        }
        .bootstrap-datetimepicker-widget{
            box-shadow: 0 0 5px #888;
        }
        /*#set_notifydata li:hover{
            background: #f4f4f4;
        }*/
    </style>
</head>

<body class="skin-blue sidebar-mini">
@if (!Auth::guest())
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">


            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">

                <!-- Navbar Right Menu -->
                <div class="navbar-header">

                    <!-- The user image in the navbar-->
                    <div class="heade-icon">
                        @if(! Request::is('home*'))
                            <i class="fa fa-circle" aria-hidden="true"></i>
                        @endif
                    </div>
                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                    <div class="titel-nav">
                        <span class="main-header-title">@yield('title-main')</span>
                        @if(! Request::is('home*'))
                        <span class="main-header-sub-title">@yield('sub-title','list')</span>
                        @endif
                    </div>
                </div>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        @if(! Request::is('home*'))
                        <li class="dropdown messages-menu">
                            <a class="dropdown-toggle " href="#" {{--data-toggle="push-menu"--}} >
                                <i class="fa fa-bars"></i>

                            </a>
                        </li>
                        @endif
                        <li class="dropdown messages-menu notify">
                            @if($preferd_cnt > 0)
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="fa fa-bell"></i>
                                <span class="label label-danger">{{$preferd_cnt}}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have {{$preferd_cnt}} messages</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu" id="set_notifydata">
                                        <li style="border-bottom: 1px solid #f4f4f4;padding-left: 10px"><!-- start message -->
{{--                                            <a class="pull-left">--}}
                                                {{--<div class="pull-left">
                                                </div>--}}
                                                <h4 style="font-size: 14px">
                                                    Request from {{ $preferd_req[0]->name }} for preferred
{{--                                                    <small><i class="fa fa-clock-o"></i> 5 mins</small>--}}
                                                </h4>
{{--                                                <p>Why not buy a new awesome theme?</p>--}}
{{--                                            </a>--}}
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a id="all_notify">See All Messages</a></li>
                            </ul>
                            @else
                                <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                    <i class="fa fa-bell"></i>
{{--                                    <span class="label label-danger">{{$preferd_cnt}}</span>--}}
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">You have 0 messages</li>
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu" id="set_notifydata">
                                            <li style="display: table;margin-left: auto;margin-right: auto"><!-- start message -->
{{--                                                <a href="#" class="pull-left">--}}
{{--                                                    <div class="pull-left">--}}

{{--                                                    </div>--}}

                                                    <h4 style="font-size: 14px">
                                                        No notifications found.
{{--                                                                                          <small><i class="fa fa-clock-o"></i> 5 mins</small>--}}
                                                    </h4>
{{--                                                                                                    <p>Why not buy a new awesome theme?</p>--}}
{{--                                                </a>--}}
                                            </li    >
                                        </ul>
                                    </li>
{{--                                    <li class="footer" id="all_notify">See All Messages</li>--}}
                                </ul>
                            @endif
                        </li>
                        <li class="dropdown messages-menu">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="fa fa-envelope"></i>
                                <span class="label label-danger">4</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 4 messages</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li><!-- start message -->
                                            <a href="#">
                                                <div class="pull-left">
                                                    {{--                                                    <img class="img-circle" alt="User Image" src="../../dist/img/user2-160x160.jpg">--}}
                                                </div>
                                                <h4>
                                                    Support Team
                                                    <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                </h4>
                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <li class="footer"><a href="#">See All Messages</a></li>
                            </ul>
                        </li>
                        @if(! Request::is('home*'))
                        <li class="dropdown messages-menu">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="fa fa-link"></i>
                            </a>
                        </li>

                        <li class="dropdown messages-menu">
                            <a class="dropdown-toggle" href="{{ url('userSettings') }}">
                                <i class="fa fa-gears"></i>
                            </a>
                        </li>
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <img
                                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ63h_yORZPB_ZNWqMIm5u-1CS9BYQ6FxSHqSrGTCa0jASRtAgRtQ&s"
                                    class="user-image" alt="User Image"/>
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{{ Auth::user()->name }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </nav>
        </header>
        @include('layouts.sidebar')
        <div class="content-wrapper">
            @yield('content')
        </div>

    </div>

@endif

<!-- jQuery 3.1.1 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!--date-picker-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!--end_date-picker-->

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
{{--<script src="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>--}}
<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/js/adminlte.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<script type="text/javascript" src="https://www.dwuser.com/education/content/easy-javascript-jquery-countdown-clock-builder/assets/jquery.countdown.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<script src="//cdn.ckeditor.com/4.14.0/full/ckeditor.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>

@stack('custom-scripts')
@yield('scripts')
<script !src="">

    $('.datepicker').datepicker({
        autoclose: true,
        dateFormat: 'dd-mm-yy',
        // minDate:0 //disable past dates
    });

    $('.datetime').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss'
    });
    var $btn = $('.time-picker');
    $(function () {
        $('.time').datetimepicker({
            widgetParent: $btn,
            format: 'LT',
            showClose:true,
            showClear:true
        });
    });
    $btn.click(function () {
        $('.time').data('DateTimePicker').toggle();
    });

    $("select").on('click', function () {
        var $filterContent = $(this).children(".filter-content");
        var $arrow = $(this).find(".fa-caret-down");

        if (!$filterContent.hasClass("opened")) {
            $filterContent.addClass("opened").slideDown(200);
            $arrow.addClass("rotated");
        } else if ($filterContent.hasClass("opened")) {
            $filterContent.removeClass("opened").slideUp(200);
            $arrow.removeClass("rotated");
        }
    });

    //search concept
    var start_date;
    var end_date;
    var time;
    var search = '';
    var page = 1;
    var per_page = 10;
    var user_type = '';
    var main_cat = '';
    var buyer_filer='';
    var payment_filer='';
    var paymenttype_filer='';
    var sub_cat = '';
    var is_fast_buy = '';
    var prefered=null;
    $(function () {

        $('#select-page').val(per_page);

        $('#select-page').change(function () {
            per_page = $(this).val();
            page = 1;
            fetch_data();
        });

        $('#start-time').change(function () {
            start_date = $(this).val();
            page = 1;
            @if(isset($view) && $view == 'credit_managements')
                $('#start_date').val(start_date);
            @endif
            fetch_data();
        });

        $('#end-time').change(function () {
            end_date = $(this).val();
            page = 1;
            @if(isset($view) && $view == 'credit_managements')
                $('#end_date').val(end_date);
            @endif
            fetch_data();
        });

        $( "#search_text" ).keyup(function() {
            search = $(this).val();
            page = 1;
            @if(isset($view) && $view == 'credit_managements')
            $('#search').val(search);
            @endif
            fetch_data();
        });

        $(document).on('click', '#chkpreferred', function(event){
            var ckbox = $('#chkpreferred');
            if (ckbox.is(':checked')) {
                // alert('You have Checked it');
                // ckbox.setAttribute('data',1);
                prefered=1;
                page = 1;
            }
            else {
                // alert('You Un-Checked it');
                // ckbox.setAttribute('data',1);
                prefered=null;
                page = 1;
            }
            fetch_data();
        });

        //search with button click
        {{--$('.search_by_text').click(function () {--}}
        {{--    search = $('#search_text').val();--}}
        {{--    page = 1;--}}
        {{--    @if(isset($view) && $view == 'credit_managements')--}}
        {{--        $('#search').val(search);--}}
        {{--    @endif--}}
        {{--    fetch_data();--}}
        {{--});--}}

        @if(isset($view) && $view != 'notifications')
            $('#user_type').change(function () {
                user_type = $(this).val();
                page = 1;
                fetch_data();
            });
        @endif

        @if(isset($view))
        $('#main_cat').change(function () {
            main_cat = $(this).val();
            sub_cat=null;
            page = 1;
            fetch_data();
        });
        @endif

        @if(isset($view))
        $('#sub_cat').change(function () {
            sub_cat = $(this).val();
            page = 1;
            fetch_data();
        });
        @endif

        @if(isset($view))
        $('#buyer_filer').change(function () {
            buyer_filer = $(this).val();
            page = 1;
            fetch_data();
        });
        @endif

        @if(isset($view))
        $('#payment_filer').change(function () {
            payment_filer = $(this).val();
            page = 1;
            fetch_data();
        });
        @endif

        @if(isset($view))
        $('#paymenttype_filer').change(function () {
            paymenttype_filer = $(this).val();
            page = 1;
            fetch_data();
        });
        @endif

        $('.time').on('dp.hide', function(e){
            time = $(this).val();
            page = 1;
            fetch_data();
        });

        $('.fast-buy-btn').click(function (e) {

            if($('#is_fast_buy').val() == 0){
                is_fast_buy = 1;
                $('#is_fast_buy').val(1);
                $('.fast-buy-btn').addClass('fast-buy-div-color');
            }else{
                is_fast_buy = 0;
                $('#is_fast_buy').val(0);
                $('.fast-buy-btn').removeClass('fast-buy-div-color');
            }
            page = 1;
            fetch_data();
        });
    });

    $(document).ready(function () {
        @if(isset($view))
        fetch_data();
        @endif
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            page = $(this).attr('href').split('page=')[1];
            fetch_data();
        });

        $(document).on('click','#all_notify',function(e) {
            var prefered_data= {{$prefered_ids}};

            $.ajax({
                type: 'POST',
                url: "{{ url('set_allnotificationdata') }}",
                data: {_token: "{{ csrf_token() }}", prefered_data: prefered_data},

                success: function (res) {
                    if (res.success == true) {
                        $('#set_notifydata').empty();
                        $('#set_notifydata').html(res.message);
                        $('.notify').addClass('open');
                        $('.notify a').prop('aria-expanded','true');
                        $('.notify').find('span').css('display','none');
                        $('#all_notify').css('display','none');
                        timeout();
                    }
                    if (res.success == false) {
                        console.log("set_allnotificationdata failed.");
                    }
                },
            });
        })

        function timeout(){
            setTimeout(function(){
                var prefered_data= {{$prefered_ids}};
                $.ajax({
                    type: 'POST',
                    url: "{{ url('change_nofification_status') }}",
                    data: {_token: "{{ csrf_token() }}", prefered_data: prefered_data},
                    success: function (res) {
                        if (res.success == true) {
                            console.log("change_nofification_status success.");
                        }
                        if (res.success == false) {
                            console.log("change_nofification_status failed.");
                        }
                    },
                });
            }, 5000 );
        }
    });

    function fetch_data() {
        var seller_id  = '{{isset($seller_id) ? $seller_id : ''}}';
        var url = '{{isset($view) ? url('ajax/'.$view) : ''}}';
        if(!search) {
            block_ui();
        }
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                'seller_id':seller_id,
                'search': search,
                'start_date': start_date,
                'end_date': end_date,
                'time': time,
                'page': page,
                'per_page': per_page,
                'user_type': user_type,
                'main_cat': main_cat,
                'sub_cat': sub_cat,
                'is_fast_buy': is_fast_buy,
                'prefered':prefered,
                'buyer_filer':buyer_filer,
                'payment_filer':payment_filer,
                'paymenttype_filer':paymenttype_filer
            },
            beforeSend: function () {
                // $("#loader").show();
            },
            success: function (data) {
                unblock_ui();
                $('.content_table').html(data);
            }
        });
    }

    function block_ui()
    {
        $.blockUI({ message: '',
            css: {
                border: 'none',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                backgroundColor: 'unset',
            }});
    }

    function unblock_ui()
    {
        $.unblockUI();
    }

    //on time-picker change set value for search
    $('.time').on('dp.change', function(e){

        if($('.time').val()){
            var time = timeConvertor($('.time').val());
            $('.time').val(time);
            $('.display-time').text(time).removeClass('hide');
        }else{
            $('.display-time').text('').addClass('hide');
        }
    });

    //convert 12hr format to 24hr
    function timeConvertor(time) {
        var PM = time.match('PM') ? true : false;
        time = time.split(':');
        var min = time[1];

        if (PM) {
            var hour = 12 + parseInt(time[0],10);
            min = min.replace("PM", "");
        } else {
            var hour = ("0" + time[0]).slice(-2);
            min = min.replace("AM", "");
        }
        return hour + ':' + min;
    }

    function logout() {
        $.ajax({
            url: "{{ route('logout') }}",
            type: 'post',
            data: {_token: '{{csrf_token()}}'},
            success: function () {
                location.href = '{{ route('login') }}';
            }
        })
    }
</script>
<script>
    $(document).ready(function() {
        @if(Request::is('settingPages*'))
        CKEDITOR.replace('description', {
            toolbarGroups: [
                {name: 'styles', groups: ['styles']},
                { name: 'document',	   groups: [ 'mode', 'document' ] },			// Displays document group with its two subgroups.
                { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },			// Group's name will be used to create voice label.
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'links' },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ]},
                { name: 'insert', groups: [ 'insert' ] }
            ],
            removeButtons:'Flash,SpecialChar,Iframe,Subscript,Superscript,Strike,CreateDiv,HorizontalRule',
            removeDialogTabs : 'image:advanced'
        });
        @endif
    });
</script>
</body>
</html>
