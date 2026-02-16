<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!defined('INACTIVITY_TIMEOUT')) {
    define('INACTIVITY_TIMEOUT', 180);
}

if (!defined('WARNING_BEFORE')) {
    define('WARNING_BEFORE', 60);
}

$_SESSION['LAST_ACTIVITY'] = $_SESSION['LAST_ACTIVITY'] ?? time();

// AJAX keep-alive
if (isset($_GET['keepalive'])) {
    $_SESSION['LAST_ACTIVITY'] = time();
    echo "OK";
    exit();
}
?>

<!-- SESSION WARNING MODAL -->
<div id="sessionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 shadow-lg max-w-sm w-full text-center">
        <h2 class="text-xl font-bold mb-4">Session Expiring</h2>
        <p class="mb-4">Your session will expire soon.</p>
        <p>Time remaining: <span id="sessionCountdown"><?= WARNING_BEFORE ?></span>s</p>
        <button id="stayLoggedIn" class="bg-green-600 text-white px-5 py-2 rounded">Stay Logged In</button>
    </div>
</div>

<script>

    function showModal() {
    modal.classList.remove('hidden'); // this works now
    countdownInterval = setInterval(() => {
        countdown--;
        countdownEl.textContent = countdown;
        if(countdown <= 0) clearInterval(countdownInterval);
    }, 1000);
}

(function(){
    const WARNING_BEFORE = <?= WARNING_BEFORE ?>;
    const INACTIVITY_TIMEOUT = <?= INACTIVITY_TIMEOUT ?>;
    const modal = document.getElementById('sessionModal');
    const countdownEl = document.getElementById('sessionCountdown');
    const stayBtn = document.getElementById('stayLoggedIn');

    let countdown = WARNING_BEFORE;
    let warningTimer, logoutTimer, countdownInterval;

    function resetTimers() {
        clearTimeout(warningTimer);
        clearTimeout(logoutTimer);
        clearInterval(countdownInterval);

        countdown = WARNING_BEFORE;
        countdownEl.textContent = countdown;

        warningTimer = setTimeout(showModal, (INACTIVITY_TIMEOUT - WARNING_BEFORE) * 1000);
        logoutTimer = setTimeout(() => { window.location.href = '/public_html/index.php'; }, INACTIVITY_TIMEOUT * 1000);
    }

    ['mousemove','keydown','click','scroll'].forEach(evt => {
        document.addEventListener(evt, resetTimers);
    });

    resetTimers();

    function showModal() {
        modal.classList.remove('hidden');
        countdownInterval = setInterval(() => {
            countdown--;
            countdownEl.textContent = countdown;
            if(countdown <= 0) clearInterval(countdownInterval);
        }, 1000);
    }

    stayBtn.onclick = () => {
        fetch('<?= basename($_SERVER['PHP_SELF']) ?>?keepalive=1')
            .then(() => {
                modal.classList.add('hidden');
                resetTimers();
            });
    };
})();
</script>

<style>
#sessionModal {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  display: flex;       /* let it show when class hidden is removed */
  align-items: center;
  justify-content: center;
  z-index: 50;
}
#sessionModal.hidden {
  display: none;       /* hidden by default */
}
</style>
