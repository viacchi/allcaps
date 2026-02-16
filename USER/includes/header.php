<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Load functions if not already loaded
if (!function_exists('getUserNotifications')) {
    require_once __DIR__ . '/functions.php'; 
}

$user = $_SESSION['user'] ?? ['full_name' => 'Guest Driver', 'role' => 'Driver'];
$initial = strtoupper(substr($user['full_name'], 0, 1));
$unreadCount = 0; 

// Fetch current driver details
$driver_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($user['user_id']) ? $user['user_id'] : 1); 
$driverProfile = getDriverProfileDetails($driver_id); 

// Fallbacks
$fullName = $driverProfile['full_name'] ?? $user['full_name'];
$license = $driverProfile['license'] ?? 'Not Set';
$contact = $driverProfile['emergency_contact'] ?? 'Not Set';
$profilePicPath = $driverProfile['profile_picture'] ?? null;
$profilePic = !empty($profilePicPath) ? '../' . $profilePicPath : null;
?>

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

<header class="h-16 bg-white flex items-center justify-between px-4 sm:px-6 relative shadow-[0_2px_8px_rgba(0,0,0,0.06)] z-30">
    
    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="md:hidden w-10 h-10 rounded-xl hover:bg-gray-100 text-gray-600 flex items-center justify-center transition">
            <i class="fas fa-bars text-lg"></i>
        </button>
        <h1 class="text-lg font-extrabold text-gray-800 hidden sm:block tracking-wide">Driver Portal</h1>
    </div>

    <div class="flex items-center gap-3 sm:gap-5">
        
        <div id="real-time-clock" class="hidden sm:block text-xs font-bold text-gray-700 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200 font-mono tracking-widest shadow-sm">
            00:00:00 AM
        </div>

        <button class="w-10 h-10 rounded-xl hover:bg-brand-background-main text-gray-400 hover:text-brand-primary transition flex items-center justify-center relative">
            <i class="fas fa-bell text-lg"></i>
            <?php if ($unreadCount > 0): ?>
            <span class="absolute top-2.5 right-2.5 w-2.5 h-2.5 rounded-full bg-red-500 border-2 border-white"></span>
            <?php endif; ?>
        </button>

        <div class="h-8 w-px bg-gray-200 hidden sm:block"></div>

        <div class="relative">
            <button onclick="toggleUserMenu()" class="flex items-center gap-3 focus:outline-none group rounded-xl px-2 py-1.5 hover:bg-gray-50 transition active:scale-95">
                <div class="w-9 h-9 rounded-full bg-emerald-50 text-brand-primary flex items-center justify-center font-extrabold shadow-sm border border-brand-border overflow-hidden">
                    <?php if ($profilePic): ?>
                        <img id="headerProfileImage" src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile" class="w-full h-full object-cover">
                    <?php else: ?>
                        <span id="headerProfileInitial"><?php echo $initial; ?></span>
                    <?php endif; ?>
                </div>
                <div class="hidden md:flex flex-col items-start text-left">
                    <span class="text-sm font-bold text-gray-800 group-hover:text-brand-primary transition-colors leading-tight">
                        <?php echo htmlspecialchars($fullName); ?>
                    </span>
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider leading-tight group-hover:text-brand-primary transition-colors">
                        <?php echo htmlspecialchars($user['role'] ?? 'Driver'); ?>
                    </span>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-[10px] ml-1 group-hover:text-brand-primary transition-colors"></i>
            </button>

            <div id="user-menu-dropdown" class="hidden absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50 transform origin-top-right transition-all">
                <button onclick="openProfileModal(); toggleUserMenu();" class="w-full text-left block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-emerald-50 hover:text-brand-primary transition group">
                    <i class="fas fa-user-circle mr-2 text-gray-400 group-hover:text-brand-primary"></i> My Profile
                </button>
                <div class="h-px bg-gray-100 my-1"></div>
                <a href="../logout.php" class="block px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition group">
                    <i class="fas fa-sign-out-alt mr-2 text-red-400 group-hover:text-red-600"></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>

<div id="profileModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4 backdrop-blur-sm transition-opacity duration-300 opacity-0 pointer-events-none">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg mx-auto overflow-hidden transform scale-95 transition-transform duration-300" id="profileModalContent">
        
        <div class="bg-brand-primary p-6 text-white relative overflow-hidden">
             <div class="absolute -right-10 -top-10 text-9xl opacity-10">
                <i class="fas fa-id-card"></i>
            </div>
            <h2 class="text-2xl font-bold relative z-10">Driver Profile</h2>
            <p class="opacity-80 relative z-10">Official credentials and contact details</p>
            <button onclick="closeProfileModal()" class="absolute top-4 right-4 text-white/70 hover:text-white text-2xl focus:outline-none z-20">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="p-6">
            <div class="flex flex-col items-center -mt-16 mb-6 relative z-10">
                <img id="modalProfileImage" src="<?php echo $profilePic ? htmlspecialchars($profilePic) : 'https://ui-avatars.com/api/?name=' . $initial . '&background=059669&color=fff&size=150'; ?>" alt="Profile" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg bg-white">
            </div>

            <div class="space-y-4">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <label class="text-xs text-gray-500 font-bold uppercase tracking-wider">Full Name</label>
                    <p class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-user text-brand-primary opacity-50"></i>
                        <?php echo htmlspecialchars($fullName); ?>
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                         <label class="text-xs text-gray-500 font-bold uppercase tracking-wider">License No.</label>
                         <p class="font-bold text-gray-800"><?php echo htmlspecialchars($license); ?></p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                         <label class="text-xs text-gray-500 font-bold uppercase tracking-wider">Driver ID</label>
                         <p class="font-bold text-gray-800">#<?php echo htmlspecialchars($driver_id); ?></p>
                    </div>
                </div>

                 <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <label class="text-xs text-gray-500 font-bold uppercase tracking-wider">Emergency Contact</label>
                    <p class="font-semibold text-gray-800 flex items-center gap-2 mb-1">
                        <i class="fas fa-phone text-brand-primary opacity-50 w-5"></i>
                        <?php echo htmlspecialchars($contact); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Header Dropdown & Clock Logic
    function toggleUserMenu() {
        const menu = document.getElementById('user-menu-dropdown');
        menu.classList.toggle('hidden');
    }
    
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (sidebar && sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            if(overlay) {
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            }
        } else if (sidebar) {
            sidebar.classList.add('-translate-x-full');
            if(overlay) {
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }
    }

    setInterval(() => {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const clock = document.getElementById('real-time-clock');
        if(clock) clock.innerText = timeString;
    }, 1000);

    document.addEventListener('click', function(e) {
        const menu = document.getElementById('user-menu-dropdown');
        const btn = document.querySelector('button[onclick="toggleUserMenu()"]');
        if (menu && !menu.classList.contains('hidden')) {
            if (btn && !btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const userDrop = document.getElementById('user-menu-dropdown');
            if (userDrop) userDrop.classList.add('hidden');
        }
    });

    // Profile Modal Logic
    const modal = document.getElementById('profileModal');
    const modalContent = document.getElementById('profileModalContent');

    function openProfileModal() {
        modal.classList.remove('hidden', 'pointer-events-none');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closeProfileModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden', 'pointer-events-none');
        }, 300);
    }

    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeProfileModal();
    });
</script>