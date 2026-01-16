<?php
// Header component - Top navigation bar
$notifications = getNotifications();
$unreadCount = getUnreadNotificationCount();
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Hide scrollbar but keep functionality */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* Notification badge pulse animation */
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }
    
    .notification-badge {
        animation: pulse 2s infinite;
    }
</style>

<header class="sticky top-0 z-30 border-b border-gray-200" style="background: linear-gradient(135deg, #2D7A5C 0%, #1F5240 100%);">
    <div class="flex items-center justify-between px-6 py-4">
        <div class="flex items-center gap-4">
            <!-- Mobile Hamburger (shows on mobile) -->
            <button class="md:hidden text-white" onclick="toggleSidebarMobile()">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <!-- Desktop Hamburger (shows on desktop when sidebar is hidden) -->
            <button class="hidden md:block text-white hover:bg-white hover:bg-opacity-10 p-2 rounded-lg transition-all" onclick="toggleSidebarDesktop()" title="Toggle Sidebar" id="desktopHamburger">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <!-- Page title will be set by JavaScript based on current page -->
            <h1 class="text-2xl font-bold text-white" id="pageTitle">Dashboard</h1>
        </div>

        <div class="flex items-center gap-6">
            <!-- Notifications -->
            <div class="relative">
                <button class="text-white relative" onclick="toggleNotifications()" id="notificationButton">
                    <i class="fas fa-bell text-xl hover:text-gray-200 transition-colors"></i>
                    <?php if ($unreadCount > 0): ?>
                    <div class="notification-badge absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold">
                        <?php echo $unreadCount; ?>
                    </div>
                    <?php endif; ?>
                </button>
            </div>

            <!-- User Profile -->
            <div class="flex items-center gap-3 cursor-pointer hover:bg-white hover:bg-opacity-10 rounded-lg px-3 py-2 transition-all" onclick="toggleUserMenu()">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-white font-bold">
                    EM
                </div>
                <div class="hidden md:block">
                    <div class="text-sm font-semibold text-white">Emer</div>
                    <div class="text-xs text-white text-opacity-70">Admin</div>
                </div>
                <i class="fas fa-chevron-down text-white text-opacity-70 text-sm"></i>
            </div>
        </div>
    </div>
</header>

<!-- Notifications Dropdown -->
<div class="hidden fixed top-20 right-6 w-96 bg-white rounded-lg shadow-2xl border border-gray-200 z-50 max-h-[600px] overflow-hidden" id="notificationsDropdown">
    <!-- Header -->
    <div class="bg-gradient-to-r from-primary-green to-dark-green text-white px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fas fa-bell"></i>
            <h3 class="font-bold">Notifications</h3>
            <?php if ($unreadCount > 0): ?>
            <span class="bg-white bg-opacity-20 px-2 py-0.5 rounded-full text-xs"><?php echo $unreadCount; ?> new</span>
            <?php endif; ?>
        </div>
        <button onclick="markAllAsRead()" class="text-xs text-white hover:text-gray-200 transition-colors">
            <i class="fas fa-check-double"></i> Mark all read
        </button>
    </div>

    <!-- Notifications List -->
    <div class="overflow-y-auto no-scrollbar" style="max-height: 450px;">
        <?php if (empty($notifications)): ?>
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-bell-slash text-4xl mb-3 text-gray-300"></i>
            <p class="font-medium">No notifications</p>
            <p class="text-sm text-gray-400 mt-1">You're all caught up!</p>
        </div>
        <?php else: ?>
            <?php foreach ($notifications as $notification): 
                $bgColor = $notification['read'] ? 'bg-white' : 'bg-blue-50';
                $iconColors = [
                    'yellow' => 'bg-yellow-100 text-yellow-600',
                    'red' => 'bg-red-100 text-red-600',
                    'blue' => 'bg-blue-100 text-blue-600',
                    'green' => 'bg-green-100 text-green-600'
                ];
            ?>
            <a href="<?php echo $notification['link']; ?>" class="block border-b border-gray-100 hover:bg-gray-50 transition-colors" onclick="markAsRead(<?php echo $notification['id']; ?>)">
                <div class="p-4 <?php echo $bgColor; ?>">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 <?php echo $iconColors[$notification['color']]; ?> rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas <?php echo $notification['icon']; ?>"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <h4 class="font-semibold text-gray-900 text-sm"><?php echo $notification['title']; ?></h4>
                                <?php if (!$notification['read']): ?>
                                <span class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0 mt-1"></span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-gray-600 mb-2"><?php echo $notification['message']; ?></p>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <i class="fas fa-clock"></i>
                                <span><?php echo $notification['time']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
        <a href="/CAPTONES/notifications.php" class="block text-center text-sm font-semibold text-primary-green hover:text-dark-green transition-colors">
            View All Notifications →
        </a>
    </div>
</div>

<!-- User Menu Dropdown -->
<div class="hidden fixed top-20 right-6 w-64 bg-white rounded-lg shadow-2xl border border-gray-200 z-50" id="userMenuDropdown">
    <!-- User Info -->
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-primary-green to-dark-green rounded-full flex items-center justify-center text-white font-bold text-lg">
                EM
            </div>
            <div>
                <div class="font-semibold text-gray-900">Emer</div>
                <div class="text-xs text-gray-500">emer@logistics.com</div>
            </div>
        </div>
    </div>

    <!-- Menu Items -->
    <div class="py-2">
        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-user w-5"></i>
            <span>My Profile</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-cog w-5"></i>
            <span>Settings</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-bell w-5"></i>
            <span>Notification Settings</span>
        </a>
        <a href="/CAPTONES/contact-support.php" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-life-ring w-5"></i>
            <span>Help & Support</span>
        </a>
    </div>

    <!-- Footer -->
    <div class="border-t border-gray-200 py-2">
        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors" onclick="confirmLogout()">
            <i class="fas fa-sign-out-alt w-5"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

<script>
// Mobile sidebar toggle (for mobile devices)
function toggleSidebarMobile() {
    document.getElementById('sidebar').classList.toggle('!w-[280px]');
}

// Desktop sidebar toggle (for desktop - collapses/expands sidebar)
function toggleSidebarDesktop() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.ml-0');
    
    if (sidebar.classList.contains('md:w-[280px]')) {
        // Hide sidebar
        sidebar.classList.remove('md:w-[280px]');
        sidebar.classList.add('md:w-0');
        if (mainContent) {
            mainContent.classList.remove('md:ml-[280px]');
            mainContent.classList.add('md:ml-0');
        }
    } else {
        // Show sidebar
        sidebar.classList.remove('md:w-0');
        sidebar.classList.add('md:w-[280px]');
        if (mainContent) {
            mainContent.classList.remove('md:ml-0');
            mainContent.classList.add('md:ml-[280px]');
        }
    }
}

// Toggle notifications dropdown
function toggleNotifications() {
    const dropdown = document.getElementById('notificationsDropdown');
    const userMenu = document.getElementById('userMenuDropdown');
    
    // Close user menu if open
    userMenu.classList.add('hidden');
    
    // Toggle notifications
    dropdown.classList.toggle('hidden');
}

// Toggle user menu dropdown
function toggleUserMenu() {
    const dropdown = document.getElementById('userMenuDropdown');
    const notifications = document.getElementById('notificationsDropdown');
    
    // Close notifications if open
    notifications.classList.add('hidden');
    
    // Toggle user menu
    dropdown.classList.toggle('hidden');
}

// Mark single notification as read
function markAsRead(id) {
    // In a real application, this would make an AJAX call to update the database
    console.log('Marking notification ' + id + ' as read');
}

// Mark all notifications as read
function markAllAsRead() {
    // In a real application, this would make an AJAX call to update the database
    alert('All notifications marked as read!');
    location.reload();
}

// Confirm logout
function confirmLogout() {
    if (confirm('Are you sure you want to logout?')) {
        // Redirect to logout page
        window.location.href = '/CAPTONES/logout.php';
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const notificationButton = document.getElementById('notificationButton');
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    const userMenuDropdown = document.getElementById('userMenuDropdown');
    
    // Close notifications dropdown if clicking outside
    if (!notificationButton.contains(event.target) && !notificationsDropdown.contains(event.target)) {
        notificationsDropdown.classList.add('hidden');
    }
    
    // Close user menu if clicking outside
    if (!event.target.closest('[onclick="toggleUserMenu()"]') && !userMenuDropdown.contains(event.target)) {
        userMenuDropdown.classList.add('hidden');
    }
});

// Close dropdowns when pressing Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.getElementById('notificationsDropdown').classList.add('hidden');
        document.getElementById('userMenuDropdown').classList.add('hidden');
    }
});

// Automatically set page title based on current page
document.addEventListener('DOMContentLoaded', function() {
    const pageTitles = {
        'index': 'Dashboard',
        'vehicle-registry': 'Vehicle Registry',
        'maintenance-tracker': 'Maintenance Tracker',
        'fuel-expense-records': 'Fuel & Expense Records',
        'maintenance-approvals': 'Maintenance Approvals',
        'compliance-licensing': 'Compliance & Licensing',
        'predictive-maintenance': 'Predictive Maintenance',
        'dispatch-assignment': 'Dispatch & Assignment',
        'reservation-management': 'Reservation Management',
        'availability-calendar': 'Availability Calendar',
        'behavior-analytics': 'Behavior Analytics',
        'incident-case-management': 'Incident Case Management',
        'driver-profiles': 'Driver Profiles',
        'trip-performance': 'Trip Performance Reports',
        'transport-cost-optimization': 'Transport Cost & Optimization',
        'contact-support': 'Contact Support',
        'notifications': 'All Notifications'
    };
    
    // Get current page name from URL
    const path = window.location.pathname;
    const page = path.split('/').pop().replace('.php', '');
    
    // Set title
    if (pageTitles[page]) {
        document.getElementById('pageTitle').textContent = pageTitles[page];
    }
});
</script>