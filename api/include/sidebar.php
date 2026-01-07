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
            <li class="nav-item <?php if($page == 'dashboard') {echo 'actives';} ?>">
                <a class="nav-link dashboard-link <?php if($page == 'dashboard') {echo 'text-dark fw-bold';} ?>" href="dashboard.php" data-user-id="<?php echo $adminID; ?>">
                    <i class="fa-solid fa-fw fa-chart-simple <?php if($page == 'dashboard') {echo 'text-dark fw-bold';} ?>"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php if($page == 'project') {echo 'actives';} ?>">
                <a class="nav-link project-link <?php if($page == 'project') {echo 'text-dark fw-bold';} ?>" href="project.php" data-user-id="<?php echo $adminID; ?>">
                    <i class="fa-solid fa-fw fa-bars-progress <?php if($page == 'project') {echo 'text-dark fw-bold';} ?>"></i>
                    <span>Project</span>
                </a>
            </li>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php if($page == 'inventory') {echo 'actives';} ?>">
                <a class="nav-link inventory-link <?php if($page == 'inventory') {echo 'text-dark fw-bold';} ?>" href="inventory.php" data-user-id="<?php echo $adminID; ?>">
                    <i class="fa-solid fa-fw fa-box-open <?php if($page == 'inventory') {echo 'text-dark fw-bold';} ?>"></i>
                    <span>Inventory</span>
                </a>
            </li>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php if($page == 'customer') {echo 'actives';} ?>">
                <a class="nav-link customer-link <?php if($page == 'customer') {echo 'text-dark fw-bold';} ?>" href="customer.php" data-user-id="<?php echo $adminID; ?>">
                    <i class="fa-solid fa-fw fa-users <?php if($page == 'customer') {echo 'text-dark fw-bold';} ?>"></i>
                    <span>Customer</span>
                </a>
            </li>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php if($page == 'supplier') {echo 'actives';} ?>">
                <a class="nav-link supplier-link <?php if($page == 'supplier') {echo 'text-dark fw-bold';} ?>" href="supplier.php" data-user-id="<?php echo $adminID; ?>">
                    <i class="fa-solid fa-fw fa-truck-fast <?php if($page == 'supplier') {echo 'text-dark fw-bold';} ?>"></i>
                    <span>Supplier</span>
                </a>
            </li>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php if($page == 'employee') {echo 'actives';} ?>">
                <a class="nav-link employee-link <?php if($page == 'employee') {echo 'text-dark fw-bold';} ?>" href="employee.php" data-user-id="<?php echo $adminID; ?>">
                    <i class="fa-solid fa-fw fa-user-tie <?php if($page == 'employee') {echo 'text-dark fw-bold';} ?>"></i>
                    <span>Employee</span>
                </a>
            </li>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?php if($page == 'cashier') {echo 'actives';} ?>">
                <a class="nav-link cashier-link <?php if($page == 'cashier') {echo 'text-dark fw-bold';} ?>" href="cashier.php" data-user-id="<?php echo $adminID; ?>">
                    <i class="fa-solid fa-fw fa-money-bill-wave <?php if($page == 'cashier') {echo 'text-dark fw-bold';} ?>"></i>
                    <span>Cashier</span>
                </a>
            </li>

            <li class="nav-item <?php if($page == 'act_log') {echo 'actives';} ?>">
                <a class="nav-link <?php if($page == 'act_log') {echo 'text-dark fw-bold';} ?>" href="act_log.php" data-user-id="<?php echo $adminID; ?>">
                    <i class="fa-solid fa-fw fa-user-clock <?php if($page == 'act_log') {echo 'text-dark fw-bold';} ?>"></i>
                    <span>Activity Log</span>
                </a>
            </li>

            <li class="nav-item <?php if($page == 'archive') {echo 'actives';} ?>">
                <a class="nav-link archive-link <?php if($page == 'archive') {echo 'text-dark fw-bold';} ?>" href="archive.php" data-user-id="<?php echo $adminID; ?>">
                    <i class="fa-solid fa-fw fa-trash-arrow-up <?php if($page == 'archive') {echo 'text-dark fw-bold';} ?>"></i>
                    <span>Archive</span>
                </a>
            </li>
</ul>