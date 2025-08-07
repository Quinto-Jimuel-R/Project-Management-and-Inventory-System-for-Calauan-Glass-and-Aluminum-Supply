<nav class="navbar navbar-expand topbar shadow" style="background-color: #213040;">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link text-dark rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"></span>
                                <img src="images/<?php echo $userData['image'] ? $userData['image'] : $defaultImage; ?>" alt="Current Image" style="border-radius: 50%; width: 45px; height: 45px;">
                            </a>

                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item profile-link" href="#" data-user-id="<?php echo $adminID; ?>">
                                    <i class="fa-solid fa-fw fa-user-gear fa-fw mr-2"></i>
                                    Manage Account
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item logout-link" href="#" data-toggle="modal" data-target="#logoutModal" data-user-id="<?php echo $adminID; ?>">
                                    <i class="fa-solid fa-fw fa-right-from-bracket fa-sm fa-fw mr-2"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->