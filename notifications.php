<?php
include 'includes/functions.php';

// --- HANDLE ACTIONS (This makes the buttons work) ---
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($action == 'mark_read' && $id) {
        $conn->query("UPDATE notifications SET read_status = 1 WHERE id = $id");
    } elseif ($action == 'mark_all_read') {
        $conn->query("UPDATE notifications SET read_status = 1");
    } elseif ($action == 'delete' && $id) {
        $conn->query("DELETE FROM notifications WHERE id = $id");
    } elseif ($action == 'clear_all') {
        $conn->query("DELETE FROM notifications");
    }
    
    // Redirect to remove query params and refresh
    header("Location: notifications.php");
    exit();
}

$notifications = getNotifications();
$unreadCount = getUnreadNotificationCount();
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
    <?php include 'includes/sidebar.php'; ?>

    <div class="ml-0 md:ml-[280px] min-h-screen transition-all duration-300">
        <?php include 'includes/header.php'; ?>

        <main class="p-6">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <i class="fas fa-bell text-primary-green"></i>
                        All Notifications
                    </h2>
                    <p class="text-gray-600 mt-2">Stay updated with all system activities and alerts</p>
                </div>
                <div class="flex gap-3">
                    <button class="px-4 py-2 bg-primary-green text-white rounded-md text-sm font-semibold hover:bg-dark-green transition-all inline-flex items-center gap-2" onclick="triggerAction('mark_all_read')">
                        <i class="fas fa-check-double"></i> Mark All Read
                    </button>
                    <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-300 transition-all inline-flex items-center gap-2" onclick="triggerAction('clear_all')">
                        <i class="fas fa-trash"></i> Clear All
                    </button>
                </div>
            </div>

            <div class="space-y-4" id="notificationsList">
                <?php if (empty($notifications)): ?>
                    <div class="p-12 text-center text-gray-500 bg-white rounded-lg shadow-sm">
                        <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No notifications found</h3>
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $notification): 
                        $isRead = $notification['read'] == 1;
                        $bgColor = $isRead ? 'bg-white' : 'bg-blue-50';
                        $borderColor = $isRead ? 'border-gray-200' : 'border-blue-200';
                        $iconColors = [
                            'yellow' => 'bg-yellow-100 text-yellow-600',
                            'red' => 'bg-red-100 text-red-600',
                            'blue' => 'bg-blue-100 text-blue-600',
                            'green' => 'bg-green-100 text-green-600'
                        ];
                        $iconClass = $iconColors[$notification['color'] ?? 'blue'];
                    ?>
                    <div class="<?php echo $bgColor; ?> border <?php echo $borderColor; ?> rounded-lg p-5 hover:shadow-md transition-all notification-item">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 <?php echo $iconClass; ?> rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas <?php echo $notification['icon']; ?> text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between gap-4 mb-2">
                                    <div>
                                        <h4 class="font-bold text-gray-900 mb-1"><?php echo $notification['title']; ?></h4>
                                        <p class="text-sm text-gray-600"><?php echo $notification['message']; ?></p>
                                    </div>
                                    <?php if (!$isRead): ?>
                                    <span class="w-3 h-3 bg-blue-600 rounded-full flex-shrink-0 mt-1" title="Unread"></span>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center gap-4 mt-3">
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <i class="fas fa-clock"></i>
                                        <span><?php echo $notification['time']; ?></span>
                                    </div>
                                    <div class="flex gap-2">
                                        <?php if (!$isRead): ?>
                                        <button class="text-xs font-semibold text-blue-600 hover:text-blue-700 inline-flex items-center gap-1" onclick="triggerAction('mark_read', <?php echo $notification['id']; ?>)">
                                            <i class="fas fa-check"></i> Mark Read
                                        </button>
                                        <?php endif; ?>
                                        <button class="text-xs font-semibold text-red-600 hover:text-red-700 inline-flex items-center gap-1" onclick="triggerAction('delete', <?php echo $notification['id']; ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function triggerAction(action, id = null) {
            let url = `notifications.php?action=${action}`;
            if (id) url += `&id=${id}`;
            
            if (action === 'delete' || action === 'clear_all') {
                if (!confirm("Are you sure?")) return;
            }
            window.location.href = url;
        }
    </script>
</body>
</html>