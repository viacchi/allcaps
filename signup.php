<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Fleet and Transportation Operations · Logistics II</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      line-height: 1.6;
      color: #333;
      background: #fff;
    }
    .terms-link {
      color: blue;
      text-decoration: underline;
      cursor: pointer;
    }
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      display: none;
      z-index: 50;
    }
    .modal.show {
      display: block;
    }
    .modal-content {
      background: #fff;
      padding: 20px;
      margin: 5% auto;
      width: 70%;
      max-height: 80vh;
      overflow-y: auto;
      border-radius: 10px;
    }
    .close {
      float: right;
      text-decoration: none;
      font-size: 20px;
      font-weight: bold;
      color: red;
      cursor: pointer;
    }
    header, footer {
      background: #f8f9fa;
      border-bottom: 1px solid #ddd;
      padding: 20px;
    }
    header h1 {
      margin: 0;
      font-size: 1.8rem;
    }
    header p {
      margin: 5px 0 0;
      font-size: 0.9rem;
      color: #555;
    }
    main {
      display: grid;
      grid-template-columns: 250px 1fr;
      gap: 20px;
      max-width: 1200px;
      margin: 20px auto;
      padding: 0 20px;
    }
    nav {
      border: 1px solid #ddd;
      padding: 15px;
      border-radius: 8px;
      background: #fafafa;
    }
    nav h2 {
      font-size: 1rem;
      margin-bottom: 10px;
    }
    nav ol {
      padding-left: 20px;
    }
    nav a {
      text-decoration: none;
      color: #007bff;
    }
    nav a:hover {
      text-decoration: underline;
    }
    article h2 {
      margin-top: 40px;
      font-size: 1.4rem;
      border-bottom: 1px solid #eee;
      padding-bottom: 5px;
    }
    article ul {
      padding-left: 20px;
    }
    .ack-box {
      border: 1px solid #ddd;
      padding: 15px;
      border-radius: 8px;
      margin-top: 20px;
      background: #fafafa;
    }
    .ack-box label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.9rem;
    }
    .ack-box button {
      margin-top: 10px;
      padding: 8px 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .agree-btn {
      background: #333;
      color: #fff;
    }
    .agree-btn:disabled {
      background: #ccc;
      cursor: not-allowed;
    }
    .top-btn {
      background: #f0f0f0;
      margin-left: 10px;
    }
    footer {
      border-top: 1px solid #ddd;
      font-size: 0.8rem;
      text-align: center;
    }
    
    article h2 {
      color: #166534;
      font-weight: 700;
      margin-top: 30px;
      margin-bottom: 10px;
    }
    
    article p {
      color: #444;
      font-size: 0.95rem;
      line-height: 1.6;
    }

  </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center bg-white">

  <!-- Logo & Title -->
  <div class="text-center mb-8">
    <div class="w-16 h-16 mx-auto bg-green-600 rounded-full flex items-center justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
        stroke-width="1.5" stroke="currentColor" class="size-9 text-white">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
      </svg>
    </div>
    <h1 class="mt-4 text-2xl font-bold text-gray-800">Fleet and Transportation Operations</h1>
    <p class="text-gray-600">Secure access to your Fleet Management System</p>
  </div>

  <div class="bg-white shadow-lg rounded-lg w-full max-w-md p-6 border-2 border-green-600 shadow-green-200">
    <h2 class="text-xl font-semibold text-center mb-2 text-green-700">Sign Up</h2>
    <p class="text-sm text-gray-600 text-center mb-4">Enter your credentials to access the dashboard</p>

    <form id="SignUpForm" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-black-700">Full Name</label>
        <input id="fullname" type="text" placeholder="Enter your Full Name" required
          class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:border-green-600 shadow-sm shadow-green-100">
      </div>

      <div>
        <label class="block text-sm font-medium text-black-700">Phone Number</label>
        <input id="phonenumber" type="text" placeholder="Enter your Phone Number" required
          class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:border-green-600 shadow-sm shadow-green-100">
      </div>

      <div>
        <label class="block text-sm font-medium text-black-700">Email Address</label>
        <input id="email" type="email" placeholder="Enter your Email Address" required
          class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:border-green-600 shadow-sm shadow-green-100">
      </div>

      <div>
        <label class="block text-sm font-medium text-black-700">Set Password</label>
        <input id="password" type="password" placeholder="Enter your password..." required
          class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:border-green-600 shadow-sm shadow-green-100">
      </div>

      <div>
        <label class="block text-sm font-medium text-black-700">Confirm Password</label>
        <input id="confirmPassword" type="password" placeholder="Re-enter your password..." required
          class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-green-600 focus:border-green-600 shadow-sm shadow-green-100">
      </div>

      <button type="submit"
        class="w-full py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
        Sign Up
      </button>

      <p class="text-center text-sm text-gray-500 mt-2">
        <a href="#" class="text-green-600 hover:underline">Forgot your password?</a>
      </p>

      <p id="message" class="text-center mt-4 text-sm font-medium"></p>
      <p class="text-center text-black-600 mt-4">
        Already have an account?
        <a href="login.html" class="text-green-600 hover:underline font-medium">Login</a>
      </p>
      <p class="text-medium mt-2 text-center">
        By signing up, you agree to our
        <a href="#" id="openTerms" class="terms-link">Terms and Conditions</a>
      </p>
    </form>
  </div>

 <!-- Terms & Conditions Modal -->
<div id="termsModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-2xl shadow-2xl w-11/12 max-w-4xl max-h-[85vh] overflow-y-auto transform transition-all scale-95 opacity-0 duration-300 p-6 md:p-8 relative" id="modalContent">
    
    <!-- Close Button -->
    <button id="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-red-600 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>

    <!-- Header -->
    <header class="text-center mb-6">
      <h1 class="text-2xl md:text-3xl font-bold text-green-700 flex items-center justify-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
        Logistics Platform — Terms & Conditions
      </h1>
      <p class="text-black-600 text-sm mt-1">Effective Date: September 8, 2025 · Version 1.0</p>
    </header>

    <!-- Table of Contents -->
<nav class="bg-green-50 border border-green-100 rounded-lg p-4 mb-6">
      <h2 class="text-green-700 font-semibold mb-2 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        Table of Contents
      </h2>
      <ol class="list-decimal list-inside text-gray-700 space-y-1">
        <li><a href="#intro" class="hover:text-green-700 transition">Introduction</a></li>
        <li><a href="#definitions" class="hover:text-green-700 transition">Definitions</a></li>
        <li><a href="#accounts" class="hover:text-green-700 transition">Accounts & Eligibility</a></li>
        <li><a href="#scope" class="hover:text-green-700 transition">Scope of Service</a></li>
        <li><a href="#bookings" class="hover:text-green-700 transition">Bookings, Payments & Fees</a></li>
        <li><a href="#cancellations" class="hover:text-green-700 transition">Cancellations & Refunds</a></li>
        <li><a href="#privacy" class="hover:text-green-700 transition">Data Privacy</a></li>
        <li><a href="#security" class="hover:text-green-700 transition">Security & Access</a></li>
        <li><a href="#probihited" class="hover:text-green-700 transition">Prohibited Cargo</a></li>
        <li><a href="#insurance" class="hover:text-green-700 transition">Insurance & Claims</a></li>
        <li><a href="#liability" class="hover:text-green-700 transition">Liability</a></li>
        <li><a href="#termination" class="hover:text-green-700 transition">Termination</a></li>
        <li><a href="#dispute" class="hover:text-green-700 transition">Dispute Resolution</a></li>
        <li><a href="#law" class="hover:text-green-700 transition">Governing Law</a></li>
        <li><a href="#contact" class="hover:text-green-700 transition">Contact</a></li>
        <li><a href="#acknowledge" class="hover:text-green-700 transition">Acknowledgment</a></li>
      </ol>
    </nav>


    <!-- Article Content -->
    <article class="space-y-8 text-black-700 leading-relaxed">
      <section id="intro">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">1. Introduction</h3>
        <p class="mt-3 text-black">These Terms & Conditions govern your use of the Logistics Platform. By using this Service, you agree to these Terms and our Privacy Policy.</p>
      </section>

      <section id="definitions">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">2. Definitions</h3>
        <ul class="mt-3  list-inside text-black">
          <li><strong>Consignment</strong>: Goods or cargo handled in the system.</li>
          <li><strong>Shipper</strong>: The party sending the goods.</li>
          <li><strong>Receiver</strong>: The party receiving the goods.</li>
        </ul>
      </section>

      <section id="accounts">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">3. Accounts & Eligibility</h3>
        <p class="mt-1 text-black">Users must be 18+ and provide accurate informationn.</p>
        <p class="mt-1 text-black">You are responsible for maintaining account security.</p>
      </section>
      
      <section id="scope">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">4. Scope of Service</h3>
        <p class="mt-2 text-black">The platform provides tools for booking, tracking, and managing shipments. Actual transport is handled by carriers.</p>
      </section>

      <section id="bookings">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">5. Bookings, Payments & Fees</h3>
        <ul class="mt-3 list-inside text-black">
          <li>Service fees may apply, including taxes and surcharges.</li>
          <li>Payments must be made through approved channels.</li>
        </ul>
      </section>

      <section id="camcellations">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">6. Cancellations & Refunds</h3>
        <p class="mt-2 text-black">Cancellations may incur charges. Refunds are subject to verification.</p>
      </section>

      <section id="privacy">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">7. Data Privacy</h3>
        <p class="mt-2 text-black">User and shipment data is collected to provide the Service. We do not sell personal data.</p>
      </section>

      <section id="security">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">8. Security & Access</h3>
        <p class="mt-2 text-black">We use reasonable safeguards. Users must not misuse the system or bypass security controls.</p>
      </section>

      <section id="probihited">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">9. Prohibited Cargo</h3>
        <p class="mt-2 text-black">Illegal, hazardous, or restricted goods may not be shipped using the platform.</p>
      </section>

      <section id="insurance">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">10. Insurance & Claims</h3>
        <p class="mt-2 text-black">Insurance is not included unless stated. Claims must be filed promptly. </p>
      </section>

      <section id="liability">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">11. Liability</h3>
        <p class="mt-2 text-black">Our liability is limited. We are not responsible for indirect or consequential damages.</p>
      </section>

      <section id="termination">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">12. Termination</h3>
        <p class="mt-2 text-black">We may suspend or terminate accounts for violations, non-payment, or legal reasons.</p>
      </section>

       <section id="dispute">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">13. Dispute Resolution</h3>
        <p class="mt-2 text-black">Disputes shall be resolved through mediation or arbitration where applicable.</p>
      </section>
      <section id="law">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">14. Governing Law</h3>
        <p class="mt-2 text-black">These Terms are governed by the laws of the Republic of the Philippines.</p>
      </section>

      <section id="contact">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">15. Contact</h3>
        <p class="mt-2 text-black">For inquiries, email us at <a href="mailto:legal@acmelogistics.example">legal@logistics.example</a>.</p>
      </section>

      <section id="acknowledge">
        <h3 class="text-xl font-semibold text-green-700 border-b border-green-200 pb-1">16. Acknowledgment</h3>
        <div class="bg-green-50 border border-green-100 p-4 rounded-lg mt-3">
          <label class="flex items-center gap-3">
            <input type="checkbox" id="agreeCheck" class="accent-green-600">
            <span>I have read and agree to the Terms & Conditions.</span>
          </label>
          <div class="flex justify-end mt-4 gap-3">
            <button id="agreeBtn" disabled class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 disabled:bg-gray-300 transition">I Agree</button>
            <button id="topBtn" class="bg-gray-100 px-5 py-2 rounded-lg hover:bg-gray-200 transition">Back to Top</button>
          </div>
        </div>
      </section>
    </article>

    <!-- Footer -->
    <footer class="mt-8 border-t border-gray-200 pt-4 text-center text-sm text-black-500">
      © 2025 Logistics Systems, Inc. — All Rights Reserved.
    </footer>
  </div>
</div>

<!-- JS -->
<script>
  const modal = document.getElementById("termsModal");
  const modalContent = document.getElementById("modalContent");
  const openBtn = document.getElementById("openTerms");
  const closeBtn = document.getElementById("closeModal");
  const agreeBtn = document.getElementById("agreeBtn");
  const agreeCheck = document.getElementById("agreeCheck");
  const topBtn = document.getElementById("topBtn");

  openBtn.addEventListener("click", (e) => {
    e.preventDefault();
    modal.classList.remove("hidden");
    setTimeout(() => modalContent.classList.add("scale-100", "opacity-100"), 10);
  });

  closeBtn.addEventListener("click", () => closeModal());
  window.addEventListener("click", (e) => { if (e.target === modal) closeModal(); });

  function closeModal() {
    modalContent.classList.remove("scale-100", "opacity-100");
    setTimeout(() => modal.classList.add("hidden"), 200);
  }

  agreeCheck.addEventListener("change", () => {
    agreeBtn.disabled = !agreeCheck.checked;
  });

  agreeBtn.addEventListener("click", () => {
    alert("Thank you! You have agreed to the Terms & Conditions.");
    closeModal();
  });

  topBtn.addEventListener("click", () => {
    modalContent.scrollTo({ top: 0, behavior: "smooth" });
  });

    topBtn.addEventListener('click', e => {
      e.preventDefault();
      modal.querySelector('.modal-content').scrollTo({ top: 0, behavior: 'smooth' });
    });

    agreeCheck.addEventListener('change', () => {
      agreeBtn.disabled = !agreeCheck.checked;
    });

    agreeBtn.addEventListener('click', () => {
      alert('Thank you. Your acceptance has been recorded.');
      modal.classList.remove('show');
    });

    document.getElementById("SignUpForm")?.addEventListener("submit", function(e) {
      e.preventDefault();

      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();

      if (email === "admin@example.com" && password === "1234") {
        localStorage.setItem("SignUp", "true");
        alert("Welcome Admin!");
        window.location.href = "admin.html";
      } else if (email === "user@example.com" && password === "5678") {
        localStorage.setItem("SignUp", "true");
        alert("Welcome User!");
        window.location.href = "user.html";
      } else {
        alert("Invalid email or password. Try Again!");
      }
    });
  </script>
</body>
</html>
