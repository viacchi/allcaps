<?php
session_start();
// Ensure database connection is available
include "includes/db.php";

$error_msg = "";
$success_msg = "";

// 1. CHECK FOR REGISTRATION SUCCESS
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_msg = "Registration successful! Please log in.";
}

// 2. HANDLE LOGIN FORM SUBMISSION
// FIXED: We now check for the hidden input 'login_attempt' instead of the disabled button
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login_attempt'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_msg = "Please enter both email and password.";
    } else {
        // Use Prepared Statements for security
        $stmt = $conn->prepare("SELECT user_id, full_name, role, email, password, status FROM users WHERE email = ? LIMIT 1");
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Verify the Hashed Password
                if (password_verify($password, $user['password'])) {
                    // Set Session Variables
                    $_SESSION['user'] = [
                        'user_id'   => $user['user_id'],
                        'full_name' => $user['full_name'],
                        'role'      => $user['role'],
                        'email'     => $user['email'],
                        'status'    => $user['status']
                    ];
                    $_SESSION['logged_in'] = true;

                    // Smart Routing based on User Role
                    $role = strtoupper(trim($user['role'])); 
                    
                    switch ($role) {
                        case 'ADMIN':
                            $redirect_url = 'ADMIN/dashboard.php'; 
                            break;
                        case 'DRIVER':
                            $redirect_url = 'USER/dashboard.php';
                            break;
                        case 'STAFF':
                            $redirect_url = 'STAFF/dashboard.php';
                            break;
                        case 'EMPLOYEE':
                            $redirect_url = 'EMPLOYEE/dashboard.php';
                            break;
                        default:
                            $redirect_url = 'USER/dashboard.php'; 
                            break;
                    }

                    header("Location: " . $redirect_url);
                    exit;
                } else {
                    $error_msg = "Invalid password. Please try again.";
                }
            } else {
                $error_msg = "No account found with this email address.";
            }
            $stmt->close();
        } else {
            $error_msg = "Database error. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Microfinance HR3 - Login</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            "brand-primary": "#059669",
            "brand-primary-hover": "#047857",
            "brand-background-main": "#F0FDF4",
            "brand-border": "#D1FAE5",
            "brand-text-primary": "#1F2937",
            "brand-text-secondary": "#4B5563",
          }
        }
      }
    }
  </script>

  <link rel="stylesheet" href="styles.css" />
</head>

<body class="min-h-screen bg-brand-primary relative overflow-hidden">

  <div class="absolute inset-0 z-0">
    <div class="shape w-72 h-72 top-[5%] left-[-5%] bg-white/5"></div>
    <div class="shape shape-2 w-96 h-96 bottom-[-20%] left-[15%] bg-white/5"></div>
    <div class="shape shape-3 w-80 h-80 top-[-15%] right-[-10%] bg-white/5"></div>
    <div class="shape shape-4 w-56 h-56 bottom-[5%] right-[10%] bg-white/5"></div>
    <div class="shape shape-5 w-48 h-48 top-[50%] left-[50%] -translate-x-1/2 -translate-y-1/2 bg-white/5"></div>
  </div>

  <div class="min-h-screen flex relative z-10">

    <section class="hidden lg:flex w-1/2 items-center justify-center p-8 lg:p-12 text-white">
      <div class="flex flex-col items-center w-full">
        <div class="text-center">
          <img src="assets/images/logo.png" alt="Microfinance Logo" class="w-24 h-24 lg:w-28 lg:h-28 mx-auto">
          <h1 class="text-3xl lg:text-4xl font-bold mt-4">Microfinance HR</h1>
          <p class="text-white/80 uppercase tracking-widest text-sm mt-1 font-semibold">Human Resource III</p>
        </div>

        <div class="relative w-full max-w-lg h-72 lg:h-80 my-8">
          <img src="assets/images/login/illustration-1.svg" alt="Illustration 1" class="login-svg absolute inset-0 w-full h-full object-contain">
          <img src="assets/images/login/illustration-2.svg" alt="Illustration 2" class="login-svg absolute inset-0 w-full h-full object-contain">
          <img src="assets/images/login/illustration-3.svg" alt="Illustration 3" class="login-svg absolute inset-0 w-full h-full object-contain">
          <img src="assets/images/login/illustration-4.svg" alt="Illustration 4" class="login-svg absolute inset-0 w-full h-full object-contain">
          <img src="assets/images/login/illustration-5.svg" alt="Illustration 5" class="login-svg absolute inset-0 w-full h-full object-contain">
        </div>

        <div class="text-center max-w-xl">
          <p class="italic text-white/90 text-base lg:text-lg leading-relaxed">
            “The strength of the team is each individual member. The strength of each member is the team.”
          </p>
          <cite class="block text-right mt-2 text-white/60">- Phil Jackson</cite>
        </div>
      </div>
    </section>

    <section class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-8 max-h-screen overflow-y-auto custom-scrollbar">
      <div class="w-full max-w-md bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl p-6 lg:p-8 border border-white/50 my-auto">

        <div class="text-center mb-5">
          <h2 class="text-2xl lg:text-3xl font-bold text-brand-text-primary">Welcome Back!</h2>
          <p class="text-brand-text-secondary mt-1 text-sm">Please enter your details to sign in.</p>
        </div>

        <?php if (!empty($success_msg)): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2 rounded-lg mb-4 text-sm text-center font-medium shadow-sm">
                <?php echo $success_msg; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_msg)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg mb-4 text-sm text-center font-medium shadow-sm animate-pulse">
                <?php echo $error_msg; ?>
            </div>
        <?php endif; ?>

        <form id="login-form" method="POST" action="" class="space-y-3.5">
          
          <input type="hidden" name="login_attempt" value="1">

          <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5" for="email">Email Address</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-400 font-bold">@</span>
              </div>
              <input id="email" name="email" type="email" placeholder="Enter your email"
                class="w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg shadow-sm
                       focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary
                       transition-all duration-200 text-sm"
                required />
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5" for="password">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 11c1.657 0 3 1.343 3 3v2a2 2 0 01-2 2H9a2 2 0 01-2-2v-2c0-1.657 1.343-3 3-3h2zm4-1V7a4 4 0 00-8 0v3h8z">
                  </path>
                </svg>
              </div>

              <input id="password" name="password" type="password" placeholder="Enter your password"
                class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-300 rounded-lg shadow-sm
                       focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-primary focus:border-brand-primary
                       transition-all duration-200 text-sm"
                required />

              <div id="password-toggle"
                   class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer select-none transition-transform duration-150">
                <svg id="eye-open" class="h-4 w-4 text-gray-400 hover:text-brand-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg id="eye-closed" class="h-4 w-4 text-gray-400 hover:text-brand-primary transition-colors hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.943-9.543-7a9.966 9.966 0 012.257-3.592m3.086-2.16A9.956 9.956 0 0112 5c4.478 0 8.269 2.943 9.543 7a9.97 9.97 0 01-4.043 5.197M15 12a3 3 0 00-4.5-2.598M9 12a3 3 0 004.5 2.598M3 3l18 18"></path>
                </svg>
              </div>
            </div>
          </div>

          <button id="sign-in-btn" type="submit" disabled
            class="w-full bg-brand-primary text-white font-bold py-2.5 px-4 rounded-lg mt-2
                   transition-all duration-300 shadow-md
                   transform active:translate-y-0 active:scale-[0.99]
                   opacity-60 cursor-not-allowed text-sm">
            Sign In
          </button>

          <div class="mt-3 flex items-start gap-2 bg-brand-background-main border border-brand-border p-2.5 rounded-lg">
            <input id="terms-check" type="checkbox"
              class="mt-0.5 h-4 w-4 text-brand-primary border-gray-300 rounded focus:ring-brand-primary transition cursor-pointer">
            <label for="terms-check" class="text-xs text-gray-700 leading-relaxed select-none cursor-pointer">
              I agree to the
              <button id="terms-link" type="button"
                class="text-brand-primary hover:text-brand-primary-hover hover:underline transition-colors font-bold">
                Terms and Conditions
              </button>
            </label>
          </div>

        </form>

        <div class="text-center mt-5 space-y-1.5 border-t border-gray-100 pt-4">
            <p class="text-xs text-gray-500">
                <a href="#" class="text-brand-primary hover:underline font-medium">Forgot your password?</a>
            </p>
            <p class="text-xs text-gray-600">
                Don't have an account? 
                <a href="signup.php" class="text-brand-primary hover:underline font-bold">Sign Up</a>
            </p>
        </div>
      </div>
    </section>
  </div>

  <div id="terms-modal" class="fixed inset-0 hidden z-50">
    <div id="terms-backdrop" class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

    <div class="relative mx-auto mt-16 w-[92%] max-w-xl bg-white rounded-2xl shadow-2xl border border-gray-100
                opacity-0 scale-95 translate-y-2 transition-all duration-200 flex flex-col max-h-[85vh]"
         id="terms-panel">
      
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50 rounded-t-2xl">
        <div class="font-bold text-brand-primary text-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Terms and Conditions
        </div>
        <button id="terms-close" class="w-8 h-8 rounded-full hover:bg-red-50 hover:text-red-500 text-gray-400 transition flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>

      <div class="p-6 overflow-y-auto flex-1 custom-scrollbar" id="termsContent">
        <article class="space-y-6 text-gray-600 text-sm leading-relaxed">
          <section>
            <h3 class="text-base font-bold text-gray-800 mb-1 border-b border-gray-100 pb-1">1. Introduction</h3>
            <p>These Terms & Conditions govern your use of the Logistics Platform and its connected services.</p>
          </section>

          <section>
            <h3 class="text-base font-bold text-gray-800 mb-1 border-b border-gray-100 pb-1">2. Definitions</h3>
            <ul class="list-disc list-inside mt-2 space-y-1 ml-2">
              <li><strong>Consignment</strong>: Goods or cargo handled in the system.</li>
              <li><strong>Shipper</strong>: The party sending the goods.</li>
              <li><strong>Receiver</strong>: The party receiving the goods.</li>
            </ul>
          </section>

          <section>
            <h3 class="text-base font-bold text-gray-800 mb-1 border-b border-gray-100 pb-1">3. Accounts & Eligibility</h3>
            <p>Users must be 18+ and provide accurate information. You are responsible for maintaining account security.</p>
          </section>
          
          <section>
            <h3 class="text-base font-bold text-gray-800 mb-1 border-b border-gray-100 pb-1">4. Scope of Service</h3>
            <p>The platform provides tools for booking, tracking, and managing shipments. Actual transport is handled by carriers.</p>
          </section>

          <section>
            <h3 class="text-base font-bold text-brand-primary border-b border-brand-border pb-1 mb-3 mt-6">Acknowledgment</h3>
            <div class="bg-brand-background-main border border-brand-border p-4 rounded-lg">
              <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" id="agreeCheck" class="w-4 h-4 text-brand-primary accent-brand-primary rounded cursor-pointer">
                <span class="font-medium text-gray-800">I have read and agree to the Terms & Conditions.</span>
              </label>
              <div class="flex justify-end mt-4 gap-3">
                <button id="agreeBtn" disabled class="bg-brand-primary text-white font-semibold px-5 py-2 rounded-lg hover:bg-brand-primary-hover disabled:bg-gray-300 disabled:cursor-not-allowed transition text-sm">I Agree</button>
                <button id="topBtn" class="bg-gray-200 text-gray-700 font-semibold px-5 py-2 rounded-lg hover:bg-gray-300 transition text-sm" onclick="document.getElementById('termsContent').scrollTo({top: 0, behavior: 'smooth'});">Back to Top</button>
              </div>
            </div>
          </section>
        </article>  
      </div>
    </div>
  </div>

  <script src="app.js"></script>
</body>
</html>