<?php
include 'includes/functions.php';
$notifications = getNotifications();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Logistics Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-green': '#2D7A5C',
                        'light-green': '#E8F5F0',
                        'dark-green': '#1F5240',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="ml-0 md:ml-[280px] min-h-screen transition-all duration-300">
        <!-- Header -->
        <?php include 'includes/header.php'; ?>

        <!-- Page Content -->
        <main class="p-6">
            <!-- Page Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <i class="fas fa-bell text-primary-green"></i>
                        All Notifications
                    </h2>
                    <p class="text-gray-600 mt-2">Stay updated with all system activities and alerts</p>
                </div>
                <div class="flex gap-3">
                    <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all inline-flex items-center gap-2" onclick="markAllAsRead()">
                        <i class="fas fa-check-double"></i> Mark All Read
                    </button>
                    <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-2" onclick="clearAll()">
                        <i class="fas fa-trash"></i> Clear All
                    </button>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white rounded-lg shadow-sm mb-6 overflow-hidden">
                <div class="flex border-b border-gray-200">
                    <button class="px-6 py-3 text-sm font-semibold border-b-2 border-primary-green text-primary-green" onclick="filterNotifications('all')" id="tabAll">
                        All <span class="ml-1 px-2 py-0.5 bg-gray-100 rounded-full text-xs"><?php echo count($notifications); ?></span>
                    </button>
                    <button class="px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300" onclick="filterNotifications('unread')" id="tabUnread">
                        Unread <span class="ml-1 px-2 py-0.5 bg-blue-100 text-blue-800 rounded-full text-xs"><?php echo getUnreadNotificationCount(); ?></span>
                    </button>
                    <button class="px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300" onclick="filterNotifications('maintenance')" id="tabMaintenance">
                        <i class="fas fa-wrench mr-1"></i> Maintenance
                    </button>
                    <button class="px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300" onclick="filterNotifications('incident')" id="tabIncident">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Incidents
                    </button>
                    <button class="px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300" onclick="filterNotifications('approval')" id="tabApproval">
                        <i class="fas fa-clipboard-check mr-1"></i> Approvals
                    </button>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="space-y-4" id="notificationsList">
                <?php foreach ($notifications as $notification): 
                    $bgColor = $notification['read'] ? 'bg-white' : 'bg-blue-50';
                    $borderColor = $notification['read'] ? 'border-gray-200' : 'border-blue-200';
                    $iconColors = [
                        'yellow' => 'bg-yellow-100 text-yellow-600',
                        'red' => 'bg-red-100 text-red-600',
                        'blue' => 'bg-blue-100 text-blue-600',
                        'green' => 'bg-green-100 text-green-600'
                    ];
                ?>
                <div class="<?php echo $bgColor; ?> border <?php echo $borderColor; ?> rounded-lg p-5 hover:shadow-md transition-all notification-item" data-type="<?php echo $notification['type']; ?>" data-read="<?php echo $notification['read'] ? 'true' : 'false'; ?>">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 <?php echo $iconColors[$notification['color']]; ?> rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas <?php echo $notification['icon']; ?> text-lg"></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-4 mb-2">
                                <div>
                                    <h4 class="font-bold text-gray-900 mb-1"><?php echo $notification['title']; ?></h4>
                                    <p class="text-sm text-gray-600"><?php echo $notification['message']; ?></p>
                                </div>
                                <?php if (!$notification['read']): ?>
                                <span class="w-3 h-3 bg-blue-600 rounded-full flex-shrink-0 mt-1"></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center gap-4 mt-3">
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo $notification['time']; ?></span>
                                </div>
                                <div class="flex gap-2">
                                    <a href="<?php echo $notification['link']; ?>" class="text-xs font-semibold text-primary-green hover:text-dark-green inline-flex items-center gap-1">
                                        <i class="fas fa-arrow-right"></i> View Details
                                    </a>
                                    <?php if (!$notification['read']): ?>
                                    <button class="text-xs font-semibold text-blue-600 hover:text-blue-700 inline-flex items-center gap-1" onclick="markAsRead(<?php echo $notification['id']; ?>)">
                                        <i class="fas fa-check"></i> Mark as read
                                    </button>
                                    <?php endif; ?>
                                    <button class="text-xs font-semibold text-red-600 hover:text-red-700 inline-flex items-center gap-1" onclick="deleteNotification(<?php echo $notification['id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Empty State (hidden by default, shown when no notifications match filter) -->
            <div class="hidden bg-white rounded-lg shadow-sm p-12 text-center" id="emptyState">
                <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No notifications found</h3>
                <p class="text-gray-600">You're all caught up! Check back later for updates.</p>
            </div>
        </main>
    </div>

    <script>
        function filterNotifications(filter) {
            const allItems = document.querySelectorAll('.notification-item');
            const emptyState = document.getElementById('emptyState');
            let visibleCount = 0;

            // Reset all tabs
            document.querySelectorAll('[id^="tab"]').forEach(tab => {
                tab.className = 'px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-gray-300';
            });

            // Highlight active tab
            const activeTab = document.getElementById('tab' + filter.charAt(0).toUpperCase() + filter.slice(1));
            if (activeTab) {
                activeTab.className = 'px-6 py-3 text-sm font-semibold border-b-2 border-primary-green text-primary-green';
            }

            // Filter notifications
            allItems.forEach(item => {
                const type = item.getAttribute('data-type');
                const isRead = item.getAttribute('data-read') === 'true';

                let shouldShow = false;

                if (filter === 'all') {
                    shouldShow = true;
                } else if (filter === 'unread') {
                    shouldShow = !isRead;
                } else {
                    shouldShow = type === filter;
                }

                if (shouldShow) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            // Show empty state if no items visible
            if (visibleCount === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
            }
        }

        function markAsRead(id) {
            alert('Notification ' + id + ' marked as read!');
            location.reload();
        }

        function markAllAsRead() {
            if (confirm('Mark all notifications as read?')) {
                alert('All notifications marked as read!');
                location.reload();
            }
        }

        function deleteNotification(id) {
            if (confirm('Delete this notification?')) {
                alert('Notification ' + id + ' deleted!');
                location.reload();
            }
        }

        function clearAll() {
            if (confirm('Delete all notifications? This action cannot be undone.')) {
                alert('All notifications cleared!');
                location.reload();
            }
        }
    </script>
</body>
</html>