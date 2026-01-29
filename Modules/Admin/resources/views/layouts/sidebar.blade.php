<div class="app-sidebar-menu">

     <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
		
		 <div class="logo-box">
			<a href="index.html" class="logo logo-light">
				<span class="logo-sm">
					<img src="assets/images/logo-sm.png" alt="" height="22">
				</span>
				<span class="logo-lg">
					<img src="assets/images/logo-light.png" alt="" height="26">
				</span>
			</a>
			<a href="index.html" class="logo logo-dark">
				<span class="logo-sm">
					<img src="assets/images/logo-sm.png" alt="" height="22">
				</span>
				<span class="logo-lg">
					<img src="assets/images/logo-dark.png" alt="" height="26">
				</span>
			</a>
                        </div>
            <!-- Left Menu Start -->
             <ul id="side-navbar">
                <li class="menu-title" data-key="t-menu">Menu</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}" class="tp-link">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="#sliders" data-bs-toggle="collapse">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:gallery-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Home Page Slider</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sliders">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('admin.sliders.create') }}" data-key="t-slide-create">Create</a></li>
                            <li><a href="{{ route('admin.sliders.index') }}" data-key="t-slide-list">List</a></li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#menus" data-bs-toggle="collapse">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:hamburger-menu-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Menus</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="menus">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('admin.menus.index', ['type' => 'main']) }}" data-key="t-menu-main">Main Menu</a></li>
                            <li><a href="{{ route('admin.menus.index', ['type' => 'footer']) }}" data-key="t-menu-footer">Footer Menu</a></li>
                        </ul>
                    </div>
                </li>

             

                <li>
                    <a href="javascript: void(0);" class="tp-link">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:user-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Customers</span>
                    </a>                    
                </li>

                  <li>
					<a href="#sidebarCategories" data-bs-toggle="collapse">
						<span class="nav-icon">
							<iconify-icon icon="solar:smartphone-2-bold-duotone"></iconify-icon>
						</span>
						<span class="sidebar-text"> Categories </span>
						<span class="menu-arrow"></span>
					</a>

					<div class="collapse" id="sidebarCategories">
						<ul class="nav-second-level">
							<li>
								<a href="{{ route('admin.categories.create') }}" class="tp-link"><i class="ti ti-point"></i>Create</a>
							</li>
							<li>
								<a href="{{ route('admin.categories.index') }}" class="tp-link"><i class="ti ti-point"></i>List</a>
							</li>
						</ul>
					</div>
				</li>

                <li>
                    <a href="#attributes" data-bs-toggle="collapse">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Attributes</span>
						<span class="menu-arrow"></span>
                    </a>
                   <div class="collapse" id="attributes">
						<ul class="nav-second-level">
                        <li><a href="{{ route('admin.attributes.create') }}" data-key="t-att-create">Create</a></li>
                        <li><a href="{{ route('admin.attributes.index') }}" data-key="t-att-list">List</a></li>
                    </ul>
					</div>
                </li>

                <li>
                    <a href="#attributeValues" data-bs-toggle="collapse">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:list-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Attribute Values</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="attributeValues">
						<ul class="nav-second-level">
                            <li><a href="{{ route('admin.attribute_values.create') }}" data-key="t-val-create">Create</a></li>
                            <li><a href="{{ route('admin.attribute_values.index') }}" data-key="t-val-list">List</a></li>
                        </ul>
                    </div>
                </li>


                <li>
                    <a href="#products" data-bs-toggle="collapse">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:bag-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Products</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="products">
						<ul class="nav-second-level">
                            <li><a href="{{ route('admin.products.create') }}" data-key="t-prod-create">Create</a></li>
                            <li><a href="{{ route('admin.products.index') }}" data-key="t-prod-list">List</a></li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#orders" data-bs-toggle="collapse">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:cart-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Orders</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="orders">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('admin.orders.index') }}" data-key="t-ord-list">All Orders</a></li>
                            <li><a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" data-key="t-ord-pending">Pending</a></li>
                            <li><a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" data-key="t-ord-processing">Processing</a></li>
                            <li><a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" data-key="t-ord-shipped">Shipped</a></li>
                            <li><a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" data-key="t-ord-delivered">Delivered</a></li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#discount" data-bs-toggle="collapse">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:sale-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Discount</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="discount">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('admin.discounts.create') }}" data-key="t-disc-create">Create</a></li>
                            <li><a href="{{ route('admin.discounts.index') }}" data-key="t-disc-list">List</a></li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#coupons" data-bs-toggle="collapse">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:ticket-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Coupon Code</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="coupons">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('admin.coupons.create') }}" data-key="t-coup-create">Create</a></li>
                            <li><a href="{{ route('admin.coupons.index') }}" data-key="t-coup-list">List</a></li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#pages" data-bs-toggle="collapse">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:file-text-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Pages</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="pages">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('admin.pages.create') }}" data-key="t-page-create">Create</a></li>
                            <li><a href="{{ route('admin.pages.index') }}" data-key="t-page-list">List</a></li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#supportTickets" data-bs-toggle="collapse">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:chat-round-dots-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Support Tickets</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="supportTickets">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('admin.support.tickets.index') }}" data-key="t-ticket-list">List</a></li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#companySettings" data-bs-toggle="collapse">
                         <span class="nav-icon">
                            <iconify-icon icon="solar:settings-minimalistic-bold-duotone"></iconify-icon>
                        </span>
                        <span class="sidebar-text">Company Settings</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="companySettings">
                        <ul class="nav-second-level">
                            <li><a href="{{ route('admin.company_settings.edit') }}" data-key="t-comp-update">Update</a></li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
