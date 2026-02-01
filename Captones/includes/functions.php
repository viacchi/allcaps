<?php
// functions.php - Connect to database and fetch data

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1️⃣ Database connection
$conn = new mysqli('localhost', 'root', '', 'logistics2');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// 2️⃣ VEHICLES
function getVehicles() {
    global $conn;
    $sql = "SELECT * FROM vehicles ORDER BY vehicle ASC";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

// 3️⃣ DRIVERS
function getDrivers() {
    global $conn;
    $sql = "SELECT * FROM drivers ORDER BY name ASC";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

// 4️⃣ AVAILABLE DRIVERS
function getAvailableDrivers() {
    global $conn;
    $sql = "SELECT * FROM drivers WHERE status='Active' ORDER BY name ASC";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}


// 5️⃣ TRIPS / DISPATCH ASSIGNMENTS
function getTrips() {
    global $conn;
    $sql = "SELECT t.id, v.vehicle, v.model, v.type, d.name AS driver, t.route, t.dispatch_date, t.return_date, t.status AS availability, v.lat, v.lng
            FROM trips t
            JOIN vehicles v ON t.vehicle_id = v.id
            LEFT JOIN drivers d ON t.driver_id = d.id
            ORDER BY t.dispatch_date DESC";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

// 6️⃣ TRIP SCHEDULES (for calendar)
function getTripSchedules() {
    global $conn;
    $sql = "SELECT t.id, v.vehicle, d.name AS driver, t.route, t.dispatch_date AS date
            FROM trips t
            JOIN vehicles v ON t.vehicle_id = v.id
            LEFT JOIN drivers d ON t.driver_id = d.id
            ORDER BY t.dispatch_date ASC";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

function getDispatchAssignments() {
    global $conn;
    $sql = "SELECT d.id, v.vehicle, v.model, v.type, d.driver_id, dr.name AS driver, d.status, d.dispatch_date, d.route, d.availability, v.lat, v.lng
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
    $sql = "SELECT m.id, v.vehicle, m.type, m.date, m.cost, m.status, m.notes
            FROM maintenance m
            JOIN vehicles v ON m.vehicle_id = v.id
            ORDER BY m.date DESC";
    $res = $conn->query($sql);
    return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}

// Add a new maintenance record
function addMaintenance($data) {
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO maintenance
        (vehicle_id, type, date, cost, notes, status)
        VALUES (?, ?, ?, ?, ?, 'In Progress')
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "issds",
        $data['vehicle_id'],
        $data['type'],
        $data['date'],
        $data['cost'],
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
        WHERE id = ?
    ");

    if (!$stmt) {
        die("Prepare failed (completeMaintenance): " . $conn->error);
    }

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
    global $conn; // your mysqli connection

    $sql = "SELECT fe.id, fe.vehicle_id, v.plate AS vehicle, fe.date, fe.liters, fe.cost, d.name AS driver
            FROM fuel_expenses fe
            LEFT JOIN vehicles v ON fe.vehicle_id = v.id
            LEFT JOIN drivers d ON fe.driver_id = d.id
            ORDER BY fe.date DESC";

    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $expenses = [];
    while ($row = $result->fetch_assoc()) {
        $expenses[] = $row;
    }

    return $expenses;
}

// 10️⃣ APPROVALS
function getApprovals() {
    global $conn;
    $sql = "SELECT a.id, v.vehicle, a.type, a.request_date AS date, a.cost AS amount, a.status
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
        INSERT INTO maintenance (vehicle_id, type, date, cost, status, notes)
        SELECT vehicle_id, type, request_date, cost, 'In Progress', notes
        FROM maintenance_approvals
        WHERE id = ?
    ");
    $stmt->bind_param("i", $approvalId);
    return $stmt->execute();
}

function getComplianceRecords() {
    global $conn;

    $sql = "
        SELECT 
            c.id,
            v.plate AS vehicle,
            c.document_type AS document,
            c.expiry_date AS expiry,
            CASE
                WHEN c.expiry_date < CURDATE() THEN 'Expired'
                WHEN c.expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'Expiring Soon'
                ELSE 'Valid'
            END AS status
        FROM compliance_documents c
        JOIN vehicles v ON c.vehicle_id = v.id
        ORDER BY c.expiry_date ASC
    ";

    $result = $conn->query($sql);

    if (!$result) {
        die("Compliance query failed: " . $conn->error);
    }

    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
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

    $availableDrivers = count(array_filter($drivers, fn($d) => $d['status'] === 'Active'));

    return [
        'total_vehicles'    => $totalVehicles,
        'active_vehicles'   => $activeVehicles,
        'inactive_vehicles' => $inactiveVehicles,
        'maintenance_due'   => $maintenanceDue,
        'available_drivers' => $availableDrivers,
        'active_trips'      => count(array_filter($trips, fn($t) => $t['availability'] === 'Assigned')),
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

function addFuelExpense($vehicle_id, $date, $liters, $cost, $driver_id) {
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO fuel_expenses (vehicle_id, date, liters, cost, driver_id)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("isddi", $vehicle_id, $date, $liters, $cost, $driver_id);
    return $stmt->execute();
}

// DRIVER PROFILES (for Driver Profiles page)
// Keep this version
function getDriverProfiles() {
    global $conn;

    $sql = "
        SELECT 
            d.id,
            d.name,
            d.license,
            d.email,
            d.contact AS phone,
            d.address,
            d.emergency_contact,
            d.blood_type,
            d.status,
            d.join_date,
            d.expiry,
            d.rating,
            d.safety_score,
            d.on_time_rate,
            d.total_trips,
            d.total_distance,
            d.incidents
        FROM drivers d
        ORDER BY d.name ASC
    ";

    $res = $conn->query($sql);
    $drivers = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

    // Temporary certifications
    foreach ($drivers as &$driver) {
        $driver['certifications'] = [
            'Defensive Driving',
            'First Aid',
            'Hazard Handling'
        ];
    }

    return $drivers;
}

function getIncidentCases() {
    global $conn;
    $sql = "SELECT ic.*, d.name AS driver, v.plate AS vehicle
            FROM incident_cases ic
            LEFT JOIN drivers d ON ic.driver_id = d.id
            LEFT JOIN vehicles v ON ic.vehicle_id = v.id
            ORDER BY ic.date DESC";
    $result = $conn->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

// Fetch all driver behavior data
function getDriverBehaviorData() {
    global $conn;
    $sql = "SELECT db.*, d.name AS driver
            FROM driver_behavior db
            JOIN drivers d ON db.driver_id = d.id
            ORDER BY db.score DESC";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'driver' => $row['driver'],
                'score' => (int)$row['score'],
                'speeding' => (int)$row['speeding'],
                'harsh_braking' => (int)$row['harsh_braking'],
                'idle_time' => (int)$row['idle_time'],
                'trips' => (int)$row['trips']
            ];
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
    if ($result->num_rows > 0) {
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
            d.name AS driver,
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

// 1️⃣ Get all transport expenses
function getTransportExpenses() {
    global $conn;

    $sql = "
        SELECT 
            e.expense_id,
            e.date,
            e.category,
            e.amount,
            e.description,
            COALESCE(v.plate, 'N/A') AS vehicle,
            COALESCE(d.name, 'Unassigned') AS driver
        FROM transport_expenses e
        LEFT JOIN vehicles v ON e.vehicle_id = v.id
        LEFT JOIN drivers d ON e.driver_id = d.id
        ORDER BY e.date DESC
    ";

    $result = $conn->query($sql);

    // 🔴 If query fails, show the REAL SQL error
    if (!$result) {
        die("SQL ERROR in getTransportExpenses(): " . $conn->error);
    }

    $expenses = [];
    while ($row = $result->fetch_assoc()) {
        $expenses[] = $row;
    }

    return $expenses;
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

 // 3️⃣ Get fuel cost trends per vehicle
function getFuelConsumptionTrends() {
    global $conn;
    $sql = "SELECT v.plate, v.vehicle, SUM(fe.liters) AS total_liters, SUM(fe.cost) AS total_cost
            FROM vehicles v
            LEFT JOIN fuel_expenses fe ON fe.vehicle_id = v.id
            GROUP BY v.id
            ORDER BY total_cost DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
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
        SELECT v.id, v.plate, v.vehicle,
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
            v.plate AS vehicle,
            v.model,
            v.type,
            d.name AS driver,
            r.reserved_date,
            r.purpose,
            r.notes
        FROM reservations r
        LEFT JOIN vehicles v ON r.vehicle_id = v.id
        LEFT JOIN drivers d ON r.driver_id = d.id
        ORDER BY r.reserved_date DESC
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

// Create new reservation
function createReservation($data) {
    global $conn;

    $vehicle_id = $data['vehicle_id'];
    $driver_id = $data['driver_id'] ?: NULL; // optional
    $reserved_date = $data['reserved_date'];
    $purpose = $data['purpose'];
    $notes = $data['notes'];

    $stmt = $conn->prepare("
        INSERT INTO reservations 
        (vehicle_id, driver_id, reserved_date, purpose, notes) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iisss", $vehicle_id, $driver_id, $reserved_date, $purpose, $notes);
    return $stmt->execute();
}
?>
