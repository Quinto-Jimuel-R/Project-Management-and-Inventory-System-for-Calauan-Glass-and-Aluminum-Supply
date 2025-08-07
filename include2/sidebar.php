<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #213040;">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon">
                    <img src="pictures/logo-removebg-preview.png" alt="" width="50">
                </div>
                <div class="sidebar-brand-text mx-3">Calauan Glass</div>
            </a>

            <li class="nav-item <?php if($page == 'customer_project') {echo 'actives';} ?>">
                <a class="nav-link customer-purchase-link <?php if($page == 'customer_project') {echo 'text-dark fw-bold';} ?>" href="customer_project.php" data-user-id="<?php echo $customerID; ?>">
                    <i class="fa-solid fa-fw fa-clipboard-list <?php if($page == 'customer_project') {echo 'text-dark fw-bold';} ?>"></i>
                    <span>Purchase</span>
                </a>
            </li>
            
            <?php
                $unreadCount = 0; // Initialize the variable

                // Check for unread messages
                $unreadMessagesQuery = "SELECT COUNT(*) AS unread_count
                    FROM message
                    WHERE (`to` = '$customerID' AND is_read = 0)";

                $unreadResult = mysqli_query($conn, $unreadMessagesQuery);

                if ($unreadResult) {
                    $unreadData = mysqli_fetch_assoc($unreadResult);
                    $unreadCount = $unreadData['unread_count'];
                }
            ?>

            <li class="nav-item <?php if($page == 'messages') {echo 'actives';} ?>">
                <a class="nav-link user-message-link <?php if($page == 'messages') {echo 'text-dark fw-bold';} ?>" href="messages.php" data-user-id="<?php echo $customerID; ?>">
                    <i class="fa-solid fa-fw fa-envelope <?php if($page == 'messages') {echo 'text-dark fw-bold';} ?>"></i>
                    <?php if ($unreadCount > 0): ?>
                            <!-- Red badge to show unread messages -->
                        <span class="badge badge-danger mt-1" style="position: absolute; top: 50; right: 190px; background-color: red; border-radius: 50%; font-size: 8px; padding: 4px;">
                        </span>
                    <?php endif; ?>
                    <span>Message</span>
                </a>
            </li>
</ul>