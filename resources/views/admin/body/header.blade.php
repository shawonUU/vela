@php
$org = App\Models\OrgDetails::first();
@endphp
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ url('/dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset($org->logo) }}" alt="logo-sm" height="12">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset($org->logo) }}" alt="logo-dark" height="20">
                    </span>
                </a>

                <a href="{{ url('/dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset($org->logo) }}" alt="logo-sm-light" height="22">
                    </span>
                    <span class="logo-lg">
                        <img class="dashboard-logo-circle" src="{{ asset($org->logo) }}" alt="logo-light" height="54">
                        <!-- <p class="dashboard-logo">ECS Engineering</p> -->
                        <!-- <h4 >ECS Engineering </h4> -->
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm mx-4 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle"></i>
            </button>

            <!-- App Search-->
            <!-- <form class="app-search d-none d-lg-block">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Search...">
                        <span class="ri-search-line"></span>
                    </div>
                </form> -->

        </div>

        <div class="d-flex">
            @can('invoice-create')
            <button type="button" class="btn btn-sm px-2 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <a href="{{ route('invoice.add') }}" class="btn btn-light btn waves-effect waves-light" style="float:right;"><i class="fa fa-cart-plus" aria-hidden="true"></i> POS </a>
            </button>
            @endcan
			
			<button type="button" class="btn btn-sm px-2 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <a href="{{ route('sales.return.index') }}" class="btn btn-light btn waves-effect waves-light" style="float:right;"><i class="fa fa-cart-plus" aria-hidden="true"></i> Sales Return </a>
            </button>
			
			@can('purchase-create')
            <button type="button" class="btn btn-sm px-2 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <a href="{{ route('purchase.add') }}" class="btn btn-light btn waves-effect waves-light" style="float:right;"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Purchase </a>
            </button>
            @endcan
			
            @can('customer-transaction-list')
            <button type="button" class="btn btn-sm px-2 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <a href="{{ route('customer.due_payment.all') }}" class="btn btn-light btn waves-effect waves-light" style="float:right;"><i class="fas fa-users"></i> Customer Due</a>
            </button>
            @endcan
			
			 @can('product-create')
            <button type="button" class="btn btn-sm px-2 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <a href="{{route('product.all')}}" class="btn btn-light btn waves-effect waves-light" style="float:right;"><i class="fa fa-archive" aria-hidden="true"></i> Products </a>
            </button>
            @endcan
			
			 @can('product-label-list')
            <button type="button" class="btn btn-sm px-2 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <a href="{{ route('productLabelsprint.index') }}" class="btn btn-light btn waves-effect waves-light" style="float:right;"><i class="fa fa-barcode"></i> Barcode</a>
            </button>
            @endcan
			
            @can('stock-list')
            <button type="button" class="btn btn-sm px-2 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <a href="{{route('stock.report')}}" class="btn btn-light btn waves-effect waves-light" style="float:right;"><i class="fa fa-archive" aria-hidden="true"></i> Stocks </a>
            </button>
            @endcan
            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" onclick="showFullscreenHint()">
                    <i class="ri-fullscreen-line"></i>
                </button>
            </div>
            @php
            $id = Auth::user()->id;
            $adminData = App\Models\User::find($id);
            @endphp

            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="{{ (!empty($adminData->profile_image)) ? asset($adminData->profile_image) : asset('upload/no_image 2.jpg') }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1">{{ $adminData->name}}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="ri-user-line align-middle me-1"></i> Profile</a>
                    <a class="dropdown-item" href="{{ route('change.password') }}"><i class="ri-wallet-2-line align-middle me-1"></i> Change Password</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"><i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>