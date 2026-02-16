<?php
include 'includes/db.php';

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phonenumber']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Validation
    if ($password !== $confirm_password) {
        $error_msg = "Passwords do not match.";
    } else {
        // 2. Check if email exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_msg = "Email is already registered.";
        } else {
            // 3. Register User as DRIVER
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'Driver';  // <--- CHANGED: Forces every new user to be a Driver
            $status = 'active';

            // Insert into Users Table
            $insert_stmt = $conn->prepare("INSERT INTO users (full_name, phone_number, email, password, role, status) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_stmt->bind_param("ssssss", $fullname, $phone, $email, $hashed_password, $role, $status);

            if ($insert_stmt->execute()) {
                // 4. AUTOMATIC DRIVER PROFILE CREATION
                // Get the ID of the new user we just created
                $new_user_id = $conn->insert_id;
                
                // Generate a placeholder license (User can update this later in profile)
                $temp_license = "PENDING-" . rand(1000, 9999);
                
                // Insert into Drivers Table
                $driver_stmt = $conn->prepare("INSERT INTO drivers (user_id, full_name, license, status, join_date) VALUES (?, ?, ?, 'Active', NOW())");
                $driver_stmt->bind_param("iss", $new_user_id, $fullname, $temp_license);
                $driver_stmt->execute();
                $driver_stmt->close();

                // Redirect to Login
                header("Location: index.php?success=1");
                exit;
            } else {
                $error_msg = "Error: " . $conn->error;
            }
            $insert_stmt->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up · Logistics II</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { font-family: Arial, sans-serif; background: #fff; }
    .terms-link { color: blue; text-decoration: underline; cursor: pointer; }
    .modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); display: none; z-index: 50; }
    .modal.show { display: block; }
  </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center bg-white">

  <div class="text-center mb-8">
    <div class="w-16 h-16 mx-auto bg-green-600 rounded-full flex items-center justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-9 text-white">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
      </svg>
    </div>
    <h1 class="mt-4 text-2xl font-bold text-gray-800">Fleet and Transportation Operations</h1>
    <p class="text-gray-600">Secure access to your Fleet Management System</p>
  </div>

  <div class="bg-white shadow-lg rounded-lg w-full max-w-md p-6 border-2 border-green-600 shadow-green-200">
    <h2 class="text-xl font-semibold text-center mb-2 text-green-700">Driver Registration</h2>
    <p class="text-sm text-gray-600 text-center mb-4">Create your driver account to access the portal</p>

    <?php if (!empty($error_msg)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-sm text-center">
            <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <form id="SignUpForm" method="POST" action="" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-black-700">Full Name</label>
        <input name="fullname" type="text" placeholder="Enter your Full Name" required
          class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:border-green-600 shadow-sm shadow-green-100">
      </div>

      <div>
        <label class="block text-sm font-medium text-black-700">Phone Number</label>
        <input name="phonenumber" type="text" placeholder="Enter your Phone Number" required
          class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:border-green-600 shadow-sm shadow-green-100">
      </div>

      <div>
        <label class="block text-sm font-medium text-black-700">Email Address</label>
        <input name="email" type="email" placeholder="Enter your Email Address" required
          class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:border-green-600 shadow-sm shadow-green-100">
      </div>

      <div>
        <label class="block text-sm font-medium text-black-700">Set Password</label>
        <input name="password" id="password" type="password" placeholder="Enter your password..." required
          class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:border-green-600 shadow-sm shadow-green-100">
      </div>

      <div>
        <label class="block text-sm font-medium text-black-700">Confirm Password</label>
        <input name="confirm_password" id="confirmPassword" type="password" placeholder="Re-enter your password..." required
          class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:border-green-600 shadow-sm shadow-green-100">
      </div>

      <button type="submit"
        class="w-full py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
        Register as Driver
      </button>

      <p class="text-center text-black-600 mt-4">
        Already have an account?
        <a href="index.php" class="text-green-600 hover:underline font-medium">Login</a>
      </p>
      <p class="text-medium mt-2 text-center">
        By signing up, you agree to our
        <a href="#" id="openTerms" class="terms-link">Terms and Conditions</a>
      </p>
    </form>
  </div>

  <div id="termsModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-11/12 max-w-4xl max-h-[85vh] overflow-y-auto p-6 md:p-8 relative">
        <button id="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-red-600 font-bold text-xl">&times;</button>
        <h2 class="text-2xl font-bold text-green-700 mb-4">Terms & Conditions</h2>
        <p class="mb-4">These are the terms and conditions for drivers...</p>
        <div class="bg-green-50 p-4 rounded-lg">
            <label class="flex items-center gap-2">
                <input type="checkbox" id="agreeCheck">
                I agree to the terms.
            </label>
            <button id="agreeBtn" disabled class="bg-green-600 text-white px-4 py-2 rounded mt-2 disabled:bg-gray-300">I Agree</button>
        </div>
    </div>
  </div>

  <script>
    const modal = document.getElementById("termsModal");
    const openBtn = document.getElementById("openTerms");
    const closeBtn = document.getElementById("closeModal");
    const agreeBtn = document.getElementById("agreeBtn");
    const agreeCheck = document.getElementById("agreeCheck");

    openBtn.onclick = (e) => { e.preventDefault(); modal.classList.remove('hidden'); };
    closeBtn.onclick = () => modal.classList.add('hidden');
    
    agreeCheck.onchange = () => agreeBtn.disabled = !agreeCheck.checked;
    agreeBtn.onclick = () => { modal.classList.add('hidden'); };
  </script>
</body>
</html>