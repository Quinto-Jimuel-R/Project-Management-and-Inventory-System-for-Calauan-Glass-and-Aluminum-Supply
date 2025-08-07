<style>
    .nav-link{
        cursor: pointer;
    }
</style>

<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #213040;">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon">
                    <img src="pictures/logo-removebg-preview.png" alt="" width="50">
                </div>
                <div class="sidebar-brand-text mx-3">Calauan Glass</div>
            </a>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php if($page == 'cashier_dashboard') {echo 'actives';} ?>">
                <a class="nav-link cashier-dashboard-link <?php if($page == 'cashier_dashboard') {echo 'text-dark fw-bold';} ?>" href="cashier_dashboard.php" data-user-id="<?php echo $adminID; ?>">
                    <i class="fa-solid fa-fw fa-solid fa-cash-register <?php if($page == 'cashier_dashboard') {echo 'text-dark fw-bold';} ?>"></i>
                    <span>Payment</span>
                </a>
            </li>
</ul>