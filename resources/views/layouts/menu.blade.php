<li class="{{ Request::is('home*') ? 'active' : '' }}">
    <a href="{{ url('home') }}"><i class="fa fa-circle" aria-hidden="true"></i><span>Dashboard</span></a>
</li>

<li class="{{ Request::is('mainCategories*') ? 'active' : (Request::is('subCategories*')?'active':(Request::is('species*') ? 'active' : (Request::is('grades*') ? 'active' : (Request::is('weightUnits*') ? 'active' : (Request::is('credit_category*') ? 'active' : (Request::is('credit_setting2*') ? 'active' : '')))))) }} treeview">
    <a href="#">
        <i class="fa fa-circle" aria-hidden="true"></i><span>Category Management</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="">
            <a href="{{ route('mainCategories.index') }}">Categories</a>
        </li>
        <li>
            <a href="{{ route('grades.index') }}">Grade & Weight</a>
        </li>
        <li>
            <a href="{{ route('credit_category.index') }}">Hot/ Mid/ Low Species</a>
        </li>
    </ul>
</li>

<li class="{{ Request::is('liveTrade*') ? 'active' : (Request::is('upcomingTrade*') ? 'active' :(Request::is('endedTrade*') ? 'active' :'')) }}">
    <a href="{{ route('live.trade') }}"><i class="fa fa-circle"></i><span>Product Management</span></a>
</li>

<li class="{{ Request::is('creditPackages*') ? 'active' : (Request::is('top-up*') ? 'active' : (Request::is('creditManagements*') ? 'active' : ''))  }} treeview ">
    <a href="#">
        <i class="fa fa-circle" aria-hidden="true"></i><span>Credit Management</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="">
            <a href="{{ route('creditManagements.index')}}"> Sales Reports</a>
        </li>
        <li class="">
            <a href="{{ route('top-up.creditBalance')}}"> Top Up / Credit Balance</a>
        </li>

        <li >
            <a href="{{ route('creditPackages.index') }}">Create Credit Package</a>
        </li>
    </ul>
</li>

<li class="{{ Request::is('userProfiles*') ? 'active' : (Request::is('Profile/deals*') ? 'active' : (Request::is('Profile/purchase*') ? 'active' :( Request::is('Profile/rating*') ? 'active' :( Request::is('preferredreqUserProfiles*') ? 'active' : '')))) }} treeview ">
    <a href="#">
        <i class="fa fa-circle" aria-hidden="true"></i><span>User Management</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    {{--    <a href="{{ route('userProfiles.index') }}"><i class="fa fa-circle" aria-hidden="true"></i><span>User Management</span></a>--}}
    <ul class="treeview-menu">
        <li class="">
            <a href="{{ route('userProfiles.index') }}"> List</a>
        </li>
        <li>
            <a href="{{ route('user.preferredreq') }}"> Preferred Requests</a>
        </li>
    </ul>
</li>

<li class="{{ Request::is('subscriptions*') ? 'active' :  (Request::is('userSubscription*') ? 'active' : '')  }} treeview ">
    <a href="#">
        <i class="fa fa-circle" aria-hidden="true"></i><span>Subscription</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="">
            <a href="{{ route('user.subscribe') }}"> List</a>
        </li>

        <li >
            <a href="{{ route('subscriptions.index') }}"> Add Package</a>
        </li>
    </ul>
</li>

<li class="{{ Request::is('banners*') ? 'active menu-open' : (Request::is('bannerPackages*') ? 'active menu-open' : '') }} treeview ">
    <a href="#">
        <i class="fa fa-circle" aria-hidden="true"></i><span>Banner Management</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
    </a>
    <ul class="treeview-menu">
        <li class="">
            <a href="{{ route('banners.index') }}"></i> List</a>
        </li>
        <li class="">
            <a href="{{ route('banners.create') }}"></i> Add Banner</a>
        </li>
        <li >
            <a href="{{ route('bannerPackages.index') }}"></i> Add Banner Package</a>
        </li>
    </ul>
</li>

<li class="{{ Request::is('logisticCompanies*') ? 'active' : '' }}">
    <a href="{{ route('logisticCompanies.index') }}"><i class="fa fa-circle"></i><span>Logistic Company</span></a>
</li>

<li class="{{ Request::is('states*') ? 'active' : (Request::is('areas*') ? 'active' : '') }}">
    <a href="{{ route('states.index') }}"><i class="fa fa-circle" aria-hidden="true"></i><span>State & Area</span></a>
</li>

<li class="{{ Request::is('notifications*') ? 'active' : '' }}">
    <a href="{{ route('notifications.index') }}"><i class="fa fa-circle" aria-hidden="true"></i><span>Notification</span></a>
</li>

{{--<li class="{{ Request::is('grades*') ? 'active' :( Request::is('weightUnits*') ? 'active' : '') }}">
    <a href="{{ route('grades.index') }}"><i class="fa fa-circle" aria-hidden="true"></i><span>Grade & Weight</span></a>
</li>--}}

<li class="{{ Request::is('contactuses*') ? 'active' : '' }}">
    <a href="{{ route('contactuses.index') }}"><i class="fa fa-circle" aria-hidden="true"></i><span>Contact Us</span></a>
</li>

<li class="{{ Request::is('settings*') ? 'active' : '' }}">
    <a href="{{ route('settings.index') }}"><i class="fa fa-circle" aria-hidden="true"></i><span>Settings</span></a>
</li>

<li class="{{ Request::is('settingPages*') ? 'active' : '' }}">
    <a href="{{ route('settingPages.index') }}"><i class="fa fa-circle" aria-hidden="true"></i><span>Setting Pages</span></a>
</li>

<li class="" >
    <a href="#"><i  class="fa fa-circle" aria-hidden="true"></i><span>Database Backup</span></a>
</li>

<li class="" onclick="logout();">
    <a href="#"><i  class="fa fa-circle" aria-hidden="true"></i><span>Logout</span></a>
</li>

