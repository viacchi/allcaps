<?php
// functions.php - Connect to database and fetch data
include 'db.php';
include 'session.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2️⃣ VEHICLES
function getVehicles() {
    global $conn;
    $sql = "SELECT * FROM vehicles ORDER BY plate ASC";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

// NEW: Function to populate the Vehicle Dropdown in the Edit Form
function getAllVehicles() {
    global $conn;
    $res = $conn->query("SELECT id, plate, model, type FROM vehicles ORDER BY plate ASC");
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

// 3️⃣ DRIVERS
function getDrivers() {
    global $conn; // assuming you have a $conn MySQL connection

    $sql = "SELECT user_id, full_name 
            FROM users 
            WHERE role = 'Driver' AND status = 'Active' 
            ORDER BY full_name ASC";
    $result = $conn->query($sql);

    $drivers = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $drivers[] = $row;
        }
    }
    return $drivers;
}

// 4️⃣ AVAILABLE DRIVERS
function getAvailableDrivers() {
    global $conn;

    $sql = "
        SELECT 
            d.id,
            u.full_name AS name,
            d.license,
            d.status
        FROM drivers d
        JOIN users u ON d.user_id = u.user_id
        WHERE d.status = 'Active'
        ORDER BY u.full_name ASC
    ";

    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

// 5️⃣ TRIPS / DISPATCH ASSIGNMENTS
function getTrips() {
    global $conn;
    $sql = "
        SELECT t.id, t.trip_code, t.route, t.dispatch_date, t.return_date, t.status,
               v.plate AS vehicle, v.model AS vehicle_model,
               d.name AS driver
        FROM trips t
        LEFT JOIN vehicles v ON t.vehicle_id = v.id
        LEFT JOIN drivers d ON t.driver_id = d.id
        ORDER BY t.dispatch_date DESC
    ";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

// 6️⃣ TRIP SCHEDULES (for calendar)
function getTripSchedules() {
    global $conn;
    $sql = "SELECT t.id, v.plate AS vehicle, d.name AS driver, t.route, t.dispatch_date AS date
            FROM trips t
            JOIN vehicles v ON t.vehicle_id = v.id
            LEFT JOIN drivers d ON t.driver_id = d.id
            ORDER BY t.dispatch_date ASC";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function getDispatchAssignments() {
    global $conn;
    $sql = "SELECT d.id, v.plate AS vehicle, v.model, v.type, d.driver_id, dr.name AS driver, d.status, d.dispatch_date, d.route, d.availability, v.lat, v.lng
            FROM dispatches d
            JOIN vehicles v ON d.vehicle_id = v.id
            LEFT JOIN drivers dr ON d.driver_id = dr.id
            ORDER BY d.dispatch_date DESC";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}


function addVehicle($data) {
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO vehicles (plate, model, type, year, status, last_maintenance)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssiss",
        $data['plate'],            // s = string
        $data['model'],            // s
        $data['type'],             // s
        $data['year'],             // i = integer
        $data['status'],           // s
        $data['last_maintenance']  // s (can be null)
    );

    return $stmt->execute();
}


function updateVehicle($data) {
    global $conn;

    $stmt = $conn->prepare("
        UPDATE vehicles 
        SET plate = ?, model = ?, type = ?, year = ?, status = ?, last_maintenance = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "sssissi",
        $data['plate'],            // s = string
        $data['model'],            // s
        $data['type'],             // s
        $data['year'],             // i = integer
        $data['status'],           // s
        $data['last_maintenance'], // s
        $data['id']                // i
    );

    return $stmt->execute();
}

function deactivateVehicle($id) {
    global $conn;

    $stmt = $conn->prepare("
        UPDATE vehicles 
        SET status = 'Inactive'
        WHERE id = ?
    ");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


// 8️⃣ MAINTENANCE RECORDS
function getMaintenanceRecords() {
    global $conn;
    $sql = "
        SELECT m.*, v.plate
        FROM maintenance m
        JOIN vehicles v ON m.vehicle_id = v.id
        WHERE m.source = 'LOG1'
        ORDER BY m.date DESC
    ";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Add a new maintenance record
function addMaintenance($data) {
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO maintenance
        (vehicle_id, type, date, priority, status, notes, source)
        VALUES (?, ?, ?, ?, ?, ?, 'LOCAL')
    ");

    $stmt->bind_param(
        "isssss",
        $data['vehicle_id'],
        $data['type'],
        $data['date'],
        $data['priority'],
        $data['status'],
        $data['notes']
    );

    return $stmt->execute();
}

// Mark a maintenance record complete
function completeMaintenance($id) {
    global $conn;

    $stmt = $conn->prepare("
        UPDATE maintenance
        SET status = 'Completed'
        WHERE id = ? AND source = 'LOG1'
    ");

    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Get vehicle ID by plate
function getVehicleIdByPlate($plate) {
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM vehicles WHERE plate = ?");
    $stmt->bind_param("s", $plate);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row['id'] ?? null;
}


// 9️⃣ FUEL EXPENSES
function getFuelExpenses() {
    global $conn;

    $expenses = [];

    $sql = "
        SELECT
            fe.id,
            fe.date,
            fe.liters,
            fe.cost,
            fe.receipt_path,
            fe.fuel_type,
            fe.status,
            CONCAT(v.plate, ' - ', v.model) AS vehicle,
            u.full_name AS driver
        FROM fuel_expenses fe
        LEFT JOIN vehicles v ON fe.vehicle_id = v.id
        LEFT JOIN drivers d ON fe.driver_id = d.user_id
        LEFT JOIN users u ON d.user_id = u.user_id
        ORDER BY fe.date DESC
    ";

    $result = $conn->query($sql);
    if (!$result) {
        die('SQL ERROR: ' . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $expenses[] = $row;
    }

    return $expenses;
}

// 10️⃣ APPROVALS
function getApprovals() {
    global $conn;
    $sql = "SELECT a.id, v.plate AS vehicle, a.type, a.request_date AS date, a.cost AS amount, a.status
            FROM maintenance_approvals a
            JOIN vehicles v ON a.vehicle_id = v.id
            ORDER BY a.request_date DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) die("SQL Prepare Error: " . $conn->error);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function approveRequestById($id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE maintenance_approvals SET status = 'Approved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function rejectRequestById($id, $reason) {
    global $conn;
    $stmt = $conn->prepare("UPDATE maintenance_approvals SET status = 'Rejected', rejection_reason = ? WHERE id = ?");
    $stmt->bind_param("si", $reason, $id);
    return $stmt->execute();
}

function insertApprovedMaintenance($approvalId) {
    global $conn;
    $stmt = $conn->prepare("
        INSERT INTO maintenance (vehicle_id, type, date, status, notes)
        SELECT vehicle_id, type, request_date, 'In Progress', notes
        FROM maintenance_approvals
        WHERE id = ?
    ");
    $stmt->bind_param("i", $approvalId);
    return $stmt->execute();
}

function getComplianceRecords($vehicle_id = null) {
    global $conn;

    if ($vehicle_id) {
        $stmt = $conn->prepare("
            SELECT cd.*, v.plate AS vehicle
            FROM compliance_documents cd
            JOIN vehicles v ON cd.vehicle_id = v.id
            WHERE cd.vehicle_id = ?
        ");
        $stmt->bind_param("i", $vehicle_id);
    } else {
        $stmt = $conn->prepare("
            SELECT cd.*, v.plate AS vehicle
            FROM compliance_documents cd
            JOIN vehicles v ON cd.vehicle_id = v.id
        ");
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $records = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

    $today = new DateTime();
    foreach ($records as &$rec) {
        $expiry = new DateTime($rec['expiry_date']);
        $diff = (int)$today->diff($expiry)->format('%r%a'); // signed days

        $rec['days_remaining'] = $diff;
        if ($diff < 0) {
            $rec['status'] = 'Expired';
        } elseif ($diff <= 30) {
            $rec['status'] = 'Expiring Soon';
        } else {
            $rec['status'] = 'Valid';
        }
    }

    return $records;
}


// 11️⃣ NOTIFICATIONS
function getNotifications() {
    global $conn;
    $sql = "SELECT * FROM notifications ORDER BY created_at DESC LIMIT 20";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function getUnreadNotificationCount() {
    global $conn;
    $sql = "SELECT COUNT(*) AS unread FROM notifications WHERE read_status = 0";
    $res = $conn->query($sql);
    $row = $res ? $res->fetch_assoc() : ['unread' => 0];
    return $row['unread'];
}

// 12️⃣ KPI DATA
function getKPIData() {
    $vehicles = getVehicles();
    $drivers = getDrivers();
    $trips = getTrips();

    $totalVehicles = count($vehicles);
    $activeVehicles = count(array_filter($vehicles, fn($v) => $v['status'] === 'Active'));
    $inactiveVehicles = count(array_filter($vehicles, fn($v) => $v['status'] === 'Inactive'));

    // Maintenance due: vehicles with last_maintenance > 6 months ago or never maintained
    $maintenanceDue = count(array_filter($vehicles, function($v) {
        if (empty($v['last_maintenance'])) return true; // never maintained
        $last = new DateTime($v['last_maintenance']);
        $now = new DateTime();
        $diff = $now->diff($last)->m + ($now->diff($last)->y * 12); // months difference
        return $diff >= 6; // due if last maintenance >= 6 months
    }));

    $availableDrivers = count(array_filter($drivers, fn($d) => ($d['status'] ?? '') === 'Active'));

    return [
        'total_vehicles'    => $totalVehicles,
        'active_vehicles'   => $activeVehicles,
        'inactive_vehicles' => $inactiveVehicles,
        'maintenance_due'   => $maintenanceDue,
        'available_drivers' => $availableDrivers,
        'active_trips'      => count(array_filter($trips, fn($t) => $t['status'] === 'Assigned')),
    ];
}

// 13️⃣ HELPER: STATUS CLASS
function getStatusClass($status) {
    return match($status) {
        'Available', 'Active', 'Completed' => 'bg-green-100 text-green-800',
        'Assigned', 'On Duty', 'In Progress' => 'bg-blue-100 text-blue-800',
        'Maintenance', 'Pending', 'Inactive' => 'bg-yellow-100 text-yellow-800',
        default => 'bg-gray-100 text-gray-800',
    };
}

function getVehicleById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getComplianceByVehicleId($vehicle_id) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            document_type,
            issue_date,
            expiry_date
        FROM compliance_documents
        WHERE vehicle_id = ?
        ORDER BY expiry_date ASC
    ");

    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function addFuelExpense($vehicle_id, $date, $liters, $cost, $driver_id, $receipt_path) {
    global $conn; // make sure your DB connection is available here

    // Prepare SQL
    $sql = "INSERT INTO fuel_expenses (vehicle_id, date, liters, cost, driver_id, receipt_path) 
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters: i = integer, s = string, d = double/decimal
        $stmt->bind_param("isddis", $vehicle_id, $date, $liters, $cost, $driver_id, $receipt_path);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            echo "Database insert failed: " . $stmt->error;
            $stmt->close();
            return false;
        }
    } else {
        echo "SQL Prepare failed: " . $conn->error;
        return false;
    }
}

// DRIVER PROFILES (UPDATED WITH PROFILE PICTURE AND VEHICLE JOIN)
function getDriverProfiles() {
    global $conn;

    $sql = "
        SELECT
            d.id,
            d.user_id,

            u.full_name AS name,
            u.email,
            u.phone_number AS phone,

            d.license,
            d.status,
            d.address,
            d.emergency_contact,
            d.blood_type,
            d.join_date,
            d.expiry,

            d.rating,
            d.safety_score,
            d.on_time_rate,
            d.total_trips,
            d.total_distance,
            d.incidents,
            
            d.profile_picture,
            d.assigned_vehicle_id,
            v.plate AS assigned_plate,
            v.model AS assigned_model

        FROM drivers d
        JOIN users u ON d.user_id = u.user_id
        LEFT JOIN vehicles v ON d.assigned_vehicle_id = v.id
        ORDER BY u.full_name
    ";

    $result = $conn->query($sql);

    if (!$result) {
        die('SQL Error: ' . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

function getIncidentCases() {
    global $conn;

    $sql = "
        SELECT 
            ic.id,
            ic.case_number,
            ic.type,
            ic.severity,
            ic.date,
            ic.location,
            ic.status,
            ic.reported_by,
            ic.attachments,

            CONCAT(u.full_name) AS driver,
            IFNULL(CONCAT(v.plate, ' - ', v.model), 'N/A') AS vehicle

        FROM incident_cases ic
        JOIN users u ON ic.driver_id = u.user_id
        LEFT JOIN vehicles v ON ic.vehicle_id = v.id
        ORDER BY ic.date DESC
    ";

    $result = $conn->query($sql);

    if (!$result) {
        die('SQL Error: ' . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch all driver behavior data
function getDriverBehaviorData() {
    global $conn; // make sure $conn is available

    $sql = "SELECT driver, score, speeding, harsh_braking, idle_time, trips FROM driver_behavior";
    $result = $conn->query($sql);

    $data = [];
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

// Fetch recent behavior incidents
function getBehaviorIncidents($limit = 5) {
    global $conn;
    $sql = "SELECT bi.*, d.name AS driver
            FROM behavior_incidents bi
            JOIN drivers d ON bi.driver_id = d.id
            ORDER BY bi.date DESC
            LIMIT $limit";
    $result = $conn->query($sql);

    $data = [];

    if ($result) { 
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'driver' => $row['driver'],
                'type' => $row['type'],
                'severity' => $row['severity'],
                'location' => $row['location'],
                'speed' => $row['speed'],
                'date' => $row['date']
            ];
        }
    } else {
        error_log("getBehaviorIncidents SQL Error: " . $conn->error);
    }

    return $data;
}

// Fetch monthly behavior trends
function getMonthlyBehaviorTrends($months = 6) {
    global $conn;
    $sql = "SELECT DATE_FORMAT(month_date, '%b %Y') AS month, total_speeding AS speeding, total_harsh_braking AS harsh_braking
            FROM monthly_behavior_trends
            ORDER BY month_date DESC
            LIMIT $months";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return array_reverse($data); // So earliest month appears first
}

// Trip Performance Reports
function getTripPerformanceReports($startDate = null, $endDate = null) {
    global $conn;

    $where = [];
    if ($startDate) $where[] = "t.dispatch_date >= '$startDate'";
    if ($endDate) $where[] = "t.dispatch_date <= '$endDate'";
    $whereSql = $where ? "WHERE " . implode(' AND ', $where) : "";

$sql = "
    SELECT 
        t.id AS trip_id,
        t.trip_code,
        t.route,
        t.dispatch_date AS date,
        t.return_date,
        t.departure_time,
        t.arrival_time,
        t.start_location,
        t.end_location,
        t.planned_distance,
        t.actual_distance,
        t.planned_duration,
        t.actual_duration,
        t.fuel_used,
        t.fuel_cost,
        t.status,
        t.on_time_percentage,
        t.idle_time,
        t.route_deviation,
        t.notes,
        v.plate AS vehicle,
        v.model AS vehicle_model,
        d.full_name AS driver,
        d.rating AS driver_rating
    FROM trips t
    LEFT JOIN vehicles v ON t.vehicle_id = v.id
    LEFT JOIN drivers d ON t.driver_id = d.id
    $whereSql
    ORDER BY t.dispatch_date DESC
";

    $result = $conn->query($sql);
    $trips = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Ensure numeric fields are numbers
            $row['planned_distance'] = (float)$row['planned_distance'];
            $row['actual_distance'] = (float)$row['actual_distance'];
            $row['fuel_used'] = (float)$row['fuel_used'];
            $row['fuel_cost'] = (float)$row['fuel_cost'];
            $row['idle_time'] = (int)$row['idle_time'];
            $row['route_deviation'] = (float)$row['route_deviation'];
            
            // Default on_time_percentage if null
            $row['on_time_percentage'] = $row['on_time_percentage'] ?? ($row['status'] === 'On-Time' ? 100 : 0);

            $trips[] = $row;
        }
    }

    return $trips;
}

// Trip Statistics for KPIs
function getTripStatistics($startDate = null, $endDate = null) {
    global $conn;

    // Optional date filter
    $where = [];
    if ($startDate) $where[] = "date >= '$startDate'";
    if ($endDate) $where[] = "date <= '$endDate'";
    $whereSql = $where ? "WHERE " . implode(' AND ', $where) : "";

    $sql = "
        SELECT 
            COUNT(*) AS total_trips,
            SUM(CASE WHEN status = 'On-Time' THEN 1 ELSE 0 END) AS on_time_trips,
            SUM(CASE WHEN status = 'Delayed' THEN 1 ELSE 0 END) AS delayed_trips,
            SUM(CASE WHEN status = 'Cancelled' THEN 1 ELSE 0 END) AS cancelled_trips,
            SUM(actual_distance) AS total_distance,
            SUM(fuel_used) AS total_fuel
        FROM trips
        $whereSql
    ";

    $result = $conn->query($sql);
    if ($result && $row = $result->fetch_assoc()) {
        // Ensure numeric values
        return [
            'total_trips' => (int)$row['total_trips'],
            'on_time_trips' => (int)$row['on_time_trips'],
            'delayed_trips' => (int)$row['delayed_trips'],
            'cancelled_trips' => (int)$row['cancelled_trips'],
            'total_distance' => (float)$row['total_distance'],
            'total_fuel' => (float)$row['total_fuel']
        ];
    }

    // Default if no trips
    return [
        'total_trips' => 0,
        'on_time_trips' => 0,
        'delayed_trips' => 0,
        'cancelled_trips' => 0,
        'total_distance' => 0,
        'total_fuel' => 0
    ];
}

function getTransportExpenses() {
    global $conn;

    // Added "AS" to make the database columns match what the HTML expects
    $sql = "
        SELECT
            er.id AS expense_id,
            er.expense_type AS category,
            er.requested_by,
            er.request_date AS date,
            er.amount,
            er.description,
            er.receipt_path,
            er.status,
            er.driver_id,
            er.vehicle_id,
            IFNULL(CONCAT(v.plate, ' - ', v.model), 'N/A') AS vehicle,
            IFNULL(u.full_name, 'N/A') AS driver
        FROM expense_requests er
        LEFT JOIN vehicles v ON er.vehicle_id = v.id
        LEFT JOIN drivers d ON er.driver_id = d.id
        LEFT JOIN users u ON d.user_id = u.user_id
        ORDER BY er.request_date DESC
    ";

    $result = $conn->query($sql);
    if (!$result) die('SQL ERROR in getTransportExpenses(): ' . $conn->error);
    return $result->fetch_all(MYSQLI_ASSOC);
}


function getTransportCostSummary() {
    global $conn; // mysqli connection

    // Example: Total cost
    $sqlTotal = "SELECT SUM(amount) AS total_cost FROM transport_expenses";
    $result = $conn->query($sqlTotal);
    $totalCost = ($row = $result->fetch_assoc()) ? $row['total_cost'] : 0;

    // Example: Average daily cost
    $sqlDays = "SELECT COUNT(DISTINCT DATE(date)) AS days FROM transport_expenses";
    $resultDays = $conn->query($sqlDays);
    $days = ($row = $resultDays->fetch_assoc()) ? $row['days'] : 1;
    $avgDailyCost = $days ? $totalCost / $days : 0;

    // Top 3 categories
    $sqlTop = "SELECT category, SUM(amount) AS total FROM transport_expenses GROUP BY category ORDER BY total DESC LIMIT 3";
    $resultTop = $conn->query($sqlTop);
    $topCategories = [];
    while ($row = $resultTop->fetch_assoc()) {
        $topCategories[$row['category']] = $row['total'];
    }

    // Category breakdown for charts
    $sqlBreakdown = "SELECT category, SUM(amount) AS total FROM transport_expenses GROUP BY category";
    $resultBreakdown = $conn->query($sqlBreakdown);
    $categoryBreakdown = [];
    while ($row = $resultBreakdown->fetch_assoc()) {
        $categoryBreakdown[$row['category']] = $row['total'];
    }

    return [
        'total_cost' => $totalCost,
        'avg_daily_cost' => $avgDailyCost,
        'top_categories' => $topCategories,
        'category_breakdown' => $categoryBreakdown,
        'monthly_change' => 5 // you can calculate this properly if you have previous month data
    ];
}

// 1. UPDATED: Forces 6 months of data so the Line Chart always renders beautifully
function getFuelConsumptionTrends() {
    global $conn;

    // Create a blank array for the last 6 months
    $months = [];
    for ($i = 5; $i >= 0; $i--) {
        $months[date('b Y', strtotime("-$i months"))] = ['consumption' => 0, 'cost' => 0];
    }

    // Fetch from transport_expenses
    $sql1 = "
        SELECT DATE_FORMAT(date, '%b %Y') AS month, SUM(amount) AS cost, SUM(amount) / 60 AS consumption
        FROM transport_expenses
        WHERE category = 'Fuel' AND date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY YEAR(date), MONTH(date)
    ";
    if ($res1 = $conn->query($sql1)) {
        while ($row = $res1->fetch_assoc()) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['cost'] += (float)$row['cost'];
                $months[$row['month']]['consumption'] += (float)$row['consumption'];
            }
        }
    }

    // Fetch from expense_requests (Since you log February fuel here!)
    $sql2 = "
        SELECT DATE_FORMAT(request_date, '%b %Y') AS month, SUM(amount) AS cost, SUM(amount) / 60 AS consumption
        FROM expense_requests
        WHERE expense_type LIKE '%Fuel%' AND status = 'Approved' AND request_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY YEAR(request_date), MONTH(request_date)
    ";
    if ($res2 = $conn->query($sql2)) {
        while ($row = $res2->fetch_assoc()) {
            if (isset($months[$row['month']])) {
                $months[$row['month']]['cost'] += (float)$row['cost'];
                $months[$row['month']]['consumption'] += (float)$row['consumption'];
            }
        }
    }

    $final = [];
    foreach ($months as $m => $data) {
        $final[] = [
            'month' => $m,
            'consumption' => round($data['consumption'], 1),
            'cost' => $data['cost']
        ];
    }
    return $final;
}

// 2️⃣ Compare vehicle costs
function getVehicleCostComparison() {
    global $conn;

    $sql = "
        SELECT v.plate, SUM(e.amount) AS total_cost
        FROM transport_expenses e
        JOIN vehicles v ON e.vehicle_id = v.id
        GROUP BY v.plate
    ";

    $result = $conn->query($sql);

    $vehicleCosts = [];
    $total = 0;
    $count = 0;

    while ($row = $result->fetch_assoc()) {
        $vehicleCosts[$row['plate']] = (float)$row['total_cost'];
        $total += $row['total_cost'];
        $count++;
    }

    $fleetAverage = $count > 0 ? $total / $count : 0;

    return [
        'vehicle_costs' => $vehicleCosts,
        'fleet_average' => $fleetAverage
    ];
}


// 4️⃣ Generate optimization insights
function getOptimizationInsights() {
    global $conn;

    // Fleet average expense per vehicle
    $avgSql = "
        SELECT AVG(vehicle_total) AS fleet_average
        FROM (
            SELECT SUM(amount) AS vehicle_total
            FROM transport_expenses
            WHERE vehicle_id IS NOT NULL
            GROUP BY vehicle_id
        ) t
    ";
    $avgRes = $conn->query($avgSql);
    $fleetAverage = $avgRes->fetch_assoc()['fleet_average'] ?? 0;

    // Per-vehicle cost
    $sql = "
        SELECT v.id, v.plate,
               COALESCE(SUM(te.amount),0) AS total_cost
        FROM vehicles v
        LEFT JOIN transport_expenses te ON te.vehicle_id = v.id
        GROUP BY v.id
        ORDER BY total_cost DESC
    ";
    $res = $conn->query($sql);
    $vehicles = $res->fetch_all(MYSQLI_ASSOC);

    return [
        'fleet_average' => $fleetAverage,
        'vehicles' => $vehicles
    ];
}

function getReservations() {
    global $conn;

    $sql = "
        SELECT
            r.id,
            IFNULL(v.plate, 'N/A') AS vehicle,
            v.model,
            v.type,
            IFNULL(u.full_name, 'N/A') AS driver,
            r.start_datetime,
            r.end_datetime,
            r.purpose,
            r.notes,
            r.status
        FROM reservations r
        LEFT JOIN vehicles v ON r.vehicle_id = v.id
        LEFT JOIN users u ON r.driver_id = u.user_id
        ORDER BY r.start_datetime DESC
    ";

    $result = $conn->query($sql);
    if (!$result) {
        die("SQL Error: " . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}


// Assign driver
function assignDriverToReservation($reservationId, $driverId) {
    global $conn;
    $stmt = $conn->prepare("UPDATE reservations SET driver_id=? WHERE id=?");
    $stmt->bind_param("ii", $driverId, $reservationId);
    return $stmt->execute();
}

// Update reservation status
function updateReservationStatus($reservationId, $status) {
    global $conn;
    $stmt = $conn->prepare("UPDATE reservations SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $reservationId);
    return $stmt->execute();
}

function isVehicleAvailable($vehicle_id, $start_datetime, $end_datetime) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) as cnt FROM reservations
        WHERE vehicle_id = ?
        AND status IN ('Pending', 'Approved', 'In Use')
        AND (
            (start_datetime <= ? AND end_datetime >= ?)
            OR
            (start_datetime <= ? AND end_datetime >= ?)
            OR
            (start_datetime >= ? AND end_datetime <= ?)
        )
    ");

    $stmt->bind_param(
        "issssss",
        $vehicle_id,
        $start_datetime, $start_datetime,
        $end_datetime, $end_datetime,
        $start_datetime, $end_datetime
    );
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['cnt'] == 0; // available if count = 0
}

function isDriverAvailable($driver_id, $start, $end) {
    global $conn;

    // Make sure driver_id is integer
    $driver_id = intval($driver_id);

    $sql = "SELECT COUNT(*) AS cnt 
            FROM reservations
            WHERE driver_id = ?
              AND (
                  (start_datetime <= ? AND end_datetime >= ?) OR
                  (start_datetime <= ? AND end_datetime >= ?) OR
                  (start_datetime >= ? AND end_datetime <= ?)
              )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", 
        $driver_id, 
        $start, $start, 
        $end, $end, 
        $start, $end
    );

    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    // 0 means available
    return $res['cnt'] == 0;
}


// Create new reservation
function createReservation($data) {
    global $conn;

    $vehicle_id = $data['vehicle_id'];
    $driver_id = $data['driver_id'] ?: NULL; // optional
    $start_datetime = $data['start_datetime']; // e.g., '2026-02-14 08:00:00'
    $end_datetime = $data['end_datetime'];     // e.g., '2026-02-14 12:00:00'
    $purpose = $data['purpose'];
    $notes = $data['notes'];

    // 1️⃣ Check vehicle availability
    if (!isVehicleAvailable($vehicle_id, $start_datetime, $end_datetime)) {
        return ['success' => false, 'message' => 'Vehicle is not available during the selected time.'];
    }

    // 2️⃣ Check driver availability if driver assigned
    if ($driver_id && !isDriverAvailable($driver_id, $start_datetime, $end_datetime)) {
        return ['success' => false, 'message' => 'Driver is not available during the selected time.'];
    }

    // 3️⃣ Insert reservation
    $stmt = $conn->prepare("
        INSERT INTO reservations 
        (vehicle_id, driver_id, start_datetime, end_datetime, purpose, notes) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iissss", $vehicle_id, $driver_id, $start_datetime, $end_datetime, $purpose, $notes);

    if ($stmt->execute()) {
        return ['success' => true, 'id' => $stmt->insert_id, 'message' => 'Reservation created successfully.'];
    } else {
        return ['success' => false, 'message' => 'Database error: ' . $stmt->error];
    }
}

// Get a single fuel expense by ID
function getFuelExpenseById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM fuel_expenses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function updateFuelExpense($data) {
    global $conn;
    $stmt = $conn->prepare("
        UPDATE fuel_expenses
        SET vehicle_id=?, driver_id=?, date=?, liters=?, cost=?, fuel_type=?, gas_station=?, receipt_number=?, notes=?
        WHERE id=?
    ");
    $stmt->bind_param(
        "iisdissssi",
        $data['vehicle_id'],
        $data['driver_id'],
        $data['date'],
        $data['liters'],
        $data['cost'],
        $data['fuel_type'],
        $data['gas_station'],
        $data['receipt_number'],
        $data['notes'],
        $data['id']
    );
    return $stmt->execute();
}

function getAvailableVehicles($date, $time) {
    global $conn;

    // Combine date + time
    $dt = "$date $time:00";

    $stmt = $conn->prepare("
        SELECT * FROM vehicles
        WHERE id NOT IN (
            SELECT vehicle_id FROM reservations
            WHERE status IN ('Pending','Approved','In Use')
            AND (
                (? BETWEEN start_datetime AND end_datetime)
                OR
                (DATE_ADD(?, INTERVAL 1 HOUR) BETWEEN start_datetime AND end_datetime)
            )
        )
        ORDER BY plate ASC
    ");
    $stmt->bind_param("ss", $dt, $dt);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAvailableDriversForReservation($start, $end) {
    global $conn;

    $sql = "
        SELECT u.user_id, u.full_name
        FROM users u
        WHERE u.role = 'Driver'
        AND u.user_id NOT IN (
            SELECT driver_id
            FROM reservations
            WHERE driver_id IS NOT NULL
            AND status != 'Cancelled'
            AND (
                (start_datetime <= ? AND end_datetime >= ?)
                OR
                (start_datetime <= ? AND end_datetime >= ?)
                OR
                (start_datetime >= ? AND end_datetime <= ?)
            )
        )
        ORDER BY u.full_name ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $start, $start, $end, $end, $start, $end);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAvailableVehiclesBetween($start, $end) {
    global $conn;

    $sql = "
        SELECT *
        FROM vehicles v
        WHERE v.id NOT IN (
            SELECT vehicle_id
            FROM reservations
            WHERE status != 'Cancelled'
            AND (
                (start_datetime <= ? AND end_datetime >= ?)  -- overlaps start
                OR
                (start_datetime <= ? AND end_datetime >= ?)  -- overlaps end
                OR
                (start_datetime >= ? AND end_datetime <= ?)  -- fully inside
            )
        )
        ORDER BY v.plate ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $start, $start, $end, $end, $start, $end);
    $stmt->execute();
    $result = $stmt->get_result();
    $vehicles = [];
    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
    return $vehicles;
}

function getAvailableDriversBetween($start, $end) {
    global $conn;

    $sql = "SELECT d.user_id, d.full_name
            FROM drivers d
            WHERE d.user_id NOT IN (
                SELECT driver_id 
                FROM reservations
                WHERE driver_id IS NOT NULL
                  AND (
                      (start_datetime <= ? AND end_datetime >= ?) OR
                      (start_datetime <= ? AND end_datetime >= ?) OR
                      (start_datetime >= ? AND end_datetime <= ?)
                  )
            )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $start, $start, $end, $end, $start, $end);

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// 2. UPDATED: Fetches live tracking data for the Driver Profiles modal
function getDispatches() {
    global $conn;
    $sql = "
        SELECT 
            d.*,
            v.plate,
            v.type,
            u.full_name AS driver
        FROM dispatches d
        LEFT JOIN vehicles v ON d.vehicle_id = v.id
        LEFT JOIN drivers dr ON d.driver_id = dr.id
        LEFT JOIN users u ON dr.user_id = u.user_id
        ORDER BY d.dispatch_date DESC
    ";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function isVehicleAvailableForDispatch($vehicle_id, $start, $end) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS cnt FROM dispatches
        WHERE vehicle_id = ?
        AND status IN ('Pending', 'Assigned', 'In Use')
        AND (
            (dispatch_date <= ? AND return_date >= ?)
            OR
            (dispatch_date <= ? AND return_date >= ?)
            OR
            (dispatch_date >= ? AND return_date <= ?)
        )
    ");
    $stmt->bind_param("issssss", $vehicle_id, $start, $start, $end, $end, $start, $end);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return $res['cnt'] == 0;
}

function isDriverAvailableForDispatch($driver_id, $start, $end) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS cnt FROM dispatches
        WHERE driver_id = ?
        AND status IN ('Pending', 'Assigned', 'In Use')
        AND (
            (dispatch_date <= ? AND return_date >= ?)
            OR
            (dispatch_date <= ? AND return_date >= ?)
            OR
            (dispatch_date >= ? AND return_date <= ?)
        )
    ");
    $stmt->bind_param("issssss", $driver_id, $start, $start, $end, $end, $start, $end);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    return $res['cnt'] == 0;
}

function createDispatch($data) {
    global $conn;

    $vehicle_id = $data['vehicle_id'];
    $driver_id = $data['driver_id'] ?: NULL;
    $dispatch_date = $data['dispatch_date'];
    $return_date = $data['return_date'];
    $route = $data['route'];
    $status = 'Pending';

    if (!isVehicleAvailableForDispatch($vehicle_id, $dispatch_date, $return_date)) {
        return ['success' => false, 'message' => 'Vehicle not available for selected period.'];
    }

    if ($driver_id && !isDriverAvailableForDispatch($driver_id, $dispatch_date, $return_date)) {
        return ['success' => false, 'message' => 'Driver not available for selected period.'];
    }

    $stmt = $conn->prepare("
        INSERT INTO dispatches (vehicle_id, driver_id, dispatch_date, return_date, route, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iissss", $vehicle_id, $driver_id, $dispatch_date, $return_date, $route, $status);

    if ($stmt->execute()) {
        return ['success' => true, 'id' => $stmt->insert_id, 'message' => 'Dispatch created successfully.'];
    } else {
        return ['success' => false, 'message' => $stmt->error];
    }
}

function convertReservationToDispatch($reservationId) {
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
    $stmt->bind_param("i", $reservationId);
    $stmt->execute();
    $reservation = $stmt->get_result()->fetch_assoc();

    if (!$reservation) return ['success' => false, 'message' => 'Reservation not found.'];

    return createDispatch([
        'vehicle_id' => $reservation['vehicle_id'],
        'driver_id' => $reservation['driver_id'],
        'dispatch_date' => $reservation['start_datetime'],
        'return_date' => $reservation['end_datetime'],
        'route' => $reservation['purpose'],
    ]);
}

function isVehicleAvailableBetween($vehicle_id, $start, $end) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) as cnt FROM dispatches
        WHERE vehicle_id = ?
        AND status IN ('Pending','Assigned','In Use')
        AND (dispatch_date <= ? AND return_date >= ?)
    ");

    $stmt->bind_param("iss", $vehicle_id, $end, $start);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    return $res['cnt'] == 0;
}

function isDriverAvailableBetween($driver_id, $start, $end) {
    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) as cnt FROM dispatches
        WHERE driver_id = ?
        AND status IN ('Assigned','In Use')
        AND (dispatch_date <= ? AND return_date >= ?)
    ");

    $stmt->bind_param("iss", $driver_id, $end, $start);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    return $res['cnt'] == 0;
}
?>