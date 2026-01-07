<?php
    $page = 'customer_project';

    include 'database.php';
    include "auth_middleware.php";
    checkAuth();
    checkRole('customer');
    
    if (!isset($_SESSION['customer_name'])) {
        header('location:index.php');
    }

    $customerID = $_SESSION['customerID'];

    if(isset($_GET['project_id']))
    {
        $project_id = $_GET['project_id'];
    }
    
    if(isset($_GET['employee_id']))
    {
        $employee_id = $_GET['employee_id'];
    }

    $employeeResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $employee_id");

    if (mysqli_num_rows($employeeResult) > 0) {
        $employeeData = mysqli_fetch_array($employeeResult);
    }

    $userResult = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $customerID");

    if (mysqli_num_rows($userResult) > 0) {
        $userData = mysqli_fetch_array($userResult);
    }

    $defaultImage = '../images/default.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'include2/header.php'; ?>
    <style>
        /* Ensure the card takes full viewport height and uses flexbox */
        .card {
            display: flex;
            flex-direction: column;
            height: 100vh; /* Full viewport height */
            margin: 0; /* Ensure no margin affects layout */
        }

        /* Fixed header styling */
        .fixed-header {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 100;
            border-bottom: 1px solid #ddd;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%; /* Ensure it spans full width */
        }

        .scrollable-content {
            flex: 1;
            display: flex;
            flex-direction: column; /* Ensure content grows from top to bottom */
            overflow-y: auto; /* Enable vertical scrolling */
            background-color: #ffffff;
            padding: 15px;
            box-sizing: border-box; /* Include padding in the element's total width and height */
        }

        .messages-container {
            display: flex;
            flex-direction: column; /* Ensure messages are stacked vertically */
            margin-top: auto; /* Push the messages container to the bottom */
        }

        /* Input container fixed at the bottom */
        .input-container {
            position: sticky;
            bottom: 0;
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #ffffff;
            border-top: 1px solid #ddd;
            gap: 10px;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1); /* Optional: Add a shadow to distinguish it from content */
            box-sizing: border-box; /* Include padding in the element's total width and height */
        }

        .input-container input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid black;
            border-radius: 20px;
            transition: border-color 0.3s ease; /* Optional: smooth transition for border color */
            box-sizing: border-box; /* Include padding in the element's total width and height */
        }

        .input-container input[type="text"]:focus {
            outline: none; /* Remove the default focus outline */
            border-color: black; /* Make the border color transparent on focus */
        }

        .input-container a {
            font-size: 20px;
            color: #007bff;
            display: flex;
            align-items: center;
        }

                /* Style for messages from the current user */
        .message-from {
            text-align: right; /* Align entire message to the right */
            margin-bottom: 10px; /* Space between messages */
        }

        /* Style for messages to the current user */
        .message-to {
            text-align: left; /* Align entire message to the left */
            margin-bottom: 10px; /* Space between messages */
        }

        /* Style for the message content itself */
        .message-content {
            display: inline-block; /* Allow the bubble to adjust based on the content */
            padding: 10px; /* Padding around the message */
            border-radius: 10px; /* Rounded corners */
            background-color: #cce5ff; /* Default background color for "from" messages */
            max-width: 70%; /* Set maximum width for the message bubble */
            word-wrap: break-word; /* Ensure long words wrap inside the bubble */
        }

        /* Override background color for messages to the current user */
        .message-to .message-content {
            background-color: #cce5ff; /* Light red background for "to" messages */
        }

        .message-divider {
            text-align: center; /* Center the text inside the divider */
            margin: 10px 0;     /* Margin for spacing above and below */
            font-size: 13px;    /* Font size of the time */
            color: #888;        /* Color of the time text */
            position: relative; /* Ensures the divider is positioned correctly */
            padding: 5px;      /* Add some padding for better spacing */
        }

        .message-divider::before {
            content: "";
            display: block;
            width: 100%;
            height: 1px;
            background-color: #ddd; /* Light gray line for the divider */
            position: absolute;
            left: 0;
            top: 50%;
            z-index: -1; /* Ensure the line is behind the text */
        }

        emoji-picker {
            width: 100%;

        }

        @media (max-width: 767px) 
        {
            .container{
                padding: 0;
            }
        }
    </style>
</head>
<body id="page-top" style="background-color: #213040;">
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <!-- Begin Page Content -->
            <div class="container">
                <!-- Card with fixed header and scrollable content -->
                <div class="card border-0 bg-primary rounded-0">
                    <div class="fixed-header p-3">
                        <div class="d-flex align-items-center gap-3">
                            <a href="messages.php"><i class="fa-solid fa-arrow-left"></i></a>
                            <img src="images/<?php echo $employeeData['image'] ? $employeeData['image'] : $defaultImage; ?>" alt="Current Image" style="border-radius: 50%; width: 45px; height: 45px;">
                            <div class="d-flex align-items-center" style="font-weight: 700;"><?php echo htmlspecialchars($employeeData['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                    </div>

                    <div class="scrollable-content bg-white px-3">
                        
                    <?php
                        // Fetch messages from the database
                        $messageResult = mysqli_query($conn, "
                            SELECT * 
                            FROM message 
                            WHERE project_id = $project_id 
                            AND ((`from` = $customerID AND `to` = $employee_id) 
                                OR (`from` = $employee_id AND `to` = $customerID))
                            ORDER BY sent_date ASC
                        ");

                        $messages = [];
                        while ($row = mysqli_fetch_assoc($messageResult)) {
                            $messages[] = $row;
                        }

                        $firstMessageTime = count($messages) > 0 ? strtotime($messages[0]['sent_date']) : null;

                        $previousMessageTime = null;
                        $dividerThreshold = 10 * 60;

                        // Function to format the date for messages from today, yesterday, a week ago, or other dates
                        function getDateFormat($timestamp) {
                            $currentDate = new DateTime();
                            $messageDate = new DateTime('@' . $timestamp);

                            // Format for messages older than yesterday
                            return $messageDate->format('F j, Y h:i A'); // Example: "July 1, 2023 8:30 PM"
                        }

                        ?>

                        <div class="messages-container">
                            <?php foreach ($messages as $index => $message): ?>
                                <?php
                                    $currentMessageTime = strtotime($message['sent_date']);
                                    
                                    // Show the divider for the first message
                                    if ($index === 0 && $firstMessageTime !== null) {
                                        $dividerDate = getDateFormat($firstMessageTime);
                                        if ($dividerDate) {
                                            ?>
                                                <div class="message-divider"><?= $dividerDate; ?></div>
                                            <?php
                                        }
                                    }

                                    // Show a divider if the time difference exceeds the threshold
                                    if ($previousMessageTime !== null && ($currentMessageTime - $previousMessageTime) > $dividerThreshold) {
                                        $dividerDate = getDateFormat($currentMessageTime);
                                        if ($dividerDate) {
                                            ?>
                                                <div class="message-divider"><?= $dividerDate; ?></div>
                                            <?php
                                        }
                                    }

                                    // Add the message content
                                    $messageClass = ($message['from'] == $customerID) ? 'message-from' : 'message-to';
                                    $messageContent = htmlspecialchars($message['message'] ?? '', ENT_QUOTES, 'UTF-8');
                                    $messageTimestamp = date('h:i A', $currentMessageTime);
                                ?>
                                <div class="message <?= $messageClass; ?>">
                                    <div class="message-content text-left">
                                        <?= $messageContent; ?>
                                    </div>
                                    <div class="message-timestamp d-none">
                                        <small>Sent: <?= $messageTimestamp; ?></small>
                                    </div>
                                </div>
                                <?php
                                // Update the previous message time
                                $previousMessageTime = $currentMessageTime;
                                ?>
                            <?php endforeach; ?>
                        </div>
                    </div>


                    <form action="add.php?action=insertMessage" class="input-container" id="messageForm" name="messageForm" method="POST">
                        <input type="text" id="messageInput" name="message" placeholder="Type your message here...">
                        <button type="button" id="emojiButton" class="border-0">
                            <i class="fa-solid fa-smile text-primary fs-4"></i>
                        </button>
                        <input type="hidden" name="customerID" value="<?php echo $customerID ?>">
                        <input type="hidden" name="employeeID" value="<?php echo $employee_id ?>">
                        <input type="hidden" name="projectID" value="<?php echo $project_id ?>">
                        <button type="submit" id="insertMessage" class="border-0" style="background-color: transparent;">
                            <i id="submitIcon" class="fa-solid fa-thumbs-up text-primary fs-4"></i>
                        </button>
                    </form>
                    <div id="emojiPickerContainer" style="display: none;">
                        <emoji-picker emoji-version="15.0"></emoji-picker>
                    </div>
                </div>
            </div>
            <!-- End Page Content -->
        </div>
        <!-- End of Main Content -->
        <!-- Footer -->
        <?php include('include2/footer.php'); ?>
        <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>

    <script>
$(document).ready(function () {
    // Initialize emoji picker
    const emojiPicker = document.querySelector('emoji-picker');
    const $emojiPickerContainer = $('#emojiPickerContainer');
    const $messageInput = $('#messageInput');
    const $submitIcon = $('#submitIcon');
    const $emojiButton = $('#emojiButton');

    $('.message-content').on('click', function() {
        var $clickedTimestamp = $(this).siblings('.message-timestamp');

        if ($clickedTimestamp.hasClass('d-none')) {
            $('.message-timestamp').addClass('d-none');
            
            $clickedTimestamp.removeClass('d-none');
        } else {
            $clickedTimestamp.addClass('d-none');
        }
    });

    $emojiButton.on('click', function () {
        $emojiPickerContainer.toggle();
    });

    emojiPicker.addEventListener('emoji-click', (event) => {
        const emoji = event.detail.unicode;
        const cursorPos = $messageInput.prop('selectionStart');
        const textBefore = $messageInput.val().substring(0, cursorPos);
        const textAfter = $messageInput.val().substring(cursorPos);
        $messageInput.val(textBefore + emoji + textAfter);
        $messageInput.focus();

        const messageInput = $messageInput.val().trim();
        if (messageInput === "") {
            $submitIcon.removeClass('fa-paper-plane').addClass('fa-thumbs-up');
        } else {
            $submitIcon.removeClass('fa-thumbs-up').addClass('fa-paper-plane');
        }
    });

    $(document).on('click', '#insertMessage', function (e) {
        e.preventDefault();

        let messageInput = $messageInput.val().trim();

        if (messageInput === "") {
            $messageInput.val('👍️');
        }

        $.ajax({
            type: "POST",
            url: "add.php?action=insertMessage",
            data: $("#messageForm").serialize(),
            success: function (response) {
                location.reload();
            },
        });
    });
});

$(document).ready(function () {
    const $messageInput = $('#messageInput'); // Select the input field
    const $submitIcon = $('#submitIcon'); // Select the submit button icon

    // Check input value and change icon
    $messageInput.on('input', function () {
        const messageInput = $(this).val(); // Get the value of the input field

        // Check if input field contains any characters (including emoji)
        if (messageInput.trim() === "") {
            $submitIcon.removeClass('fa-paper-plane').addClass('fa-thumbs-up');
        } 
        else {
            // If input contains any text, including emoji, show the paper plane
            $submitIcon.removeClass('fa-thumbs-up').addClass('fa-paper-plane');
        }
    });
});

    const scrollableContent = document.querySelector('.scrollable-content');
    if (scrollableContent) {
        scrollableContent.scrollTop = scrollableContent.scrollHeight;
    }
</script>


    <!-- Success/Error message handling -->
    <?php
        if (isset($_SESSION['success'])) {
    ?>  
        <script>
            Swal.fire({
                position: "top-end",
                text: '<?php echo $_SESSION['success']; ?>',
                icon: "success",
                showConfirmButton: false,
                timer: 1500
            });  
        </script>
    <?php
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
    ?>  
        <script>
            Swal.fire({
                position: "top-end",
                text: '<?php echo $_SESSION['error']; ?>',
                icon: "error",
                showConfirmButton: false,
                timer: 1500
            });  
        </script>
    <?php
            unset($_SESSION['error']);
        }
    ?>
</body>
</html>