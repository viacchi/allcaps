<?php
// Shared functions for the dashboard
// Mock data - Replace with database queries

function getVehicles() {
    return [
        ['id' => 1, 'plate' => 'ABC-1234', 'model' => 'Toyota Hiace', 'type' => 'Van', 'year' => 2022, 'status' => 'Active', 'last_maintenance' => '2024-08-15'],
        ['id' => 2, 'plate' => 'XYZ-5678', 'model' => 'Isuzu NPR', 'type' => 'Truck', 'year' => 2021, 'status' => 'Active', 'last_maintenance' => '2024-09-01'],
        ['id' => 3, 'plate' => 'DEF-9012', 'model' => 'Honda CB500', 'type' => 'Motorcycle', 'year' => 2023, 'status' => 'Active', 'last_maintenance' => '2024-07-20'],
        ['id' => 4, 'plate' => 'GHI-3456', 'model' => 'Mitsubishi Fuso', 'type' => 'Truck', 'year' => 2020, 'status' => 'Maintenance', 'last_maintenance' => '2024-06-10'],
        ['id' => 5, 'plate' => 'JKL-7890', 'model' => 'Toyota Fortuner', 'type' => 'Car', 'year' => 2019, 'status' => 'Inactive', 'last_maintenance' => '2024-05-15'],
        ['id' => 6, 'plate' => 'MNO-2345', 'model' => 'Suzuki Carry', 'type' => 'Van', 'year' => 2022, 'status' => 'Active', 'last_maintenance' => '2024-08-25'],
    ];
}

function getMaintenanceRecords() {
    return [
        ['id' => 1, 'vehicle' => 'ABC-1234', 'type' => 'Oil Change', 'date' => '2024-10-15', 'cost' => 2500, 'status' => 'Completed'],
        ['id' => 2, 'vehicle' => 'XYZ-5678', 'type' => 'Tire Replacement', 'date' => '2024-10-18', 'cost' => 8000, 'status' => 'In Progress'],
        ['id' => 3, 'vehicle' => 'DEF-9012', 'type' => 'Engine Inspection', 'date' => '2024-10-20', 'cost' => 5000, 'status' => 'Pending'],
        ['id' => 4, 'vehicle' => 'GHI-3456', 'type' => 'Brake Service', 'date' => '2024-10-12', 'cost' => 6500, 'status' => 'Completed'],
    ];
}

function getFuelExpenses() {
    return [
        ['id' => 1, 'vehicle' => 'ABC-1234', 'date' => '2024-10-10', 'liters' => 50, 'cost' => 2500, 'driver' => 'John Smith'],
        ['id' => 2, 'vehicle' => 'XYZ-5678', 'date' => '2024-10-09', 'liters' => 60, 'cost' => 3000, 'driver' => 'Jane Doe'],
    ];
}

function getApprovals() {
    return [
        ['id' => 1, 'vehicle' => 'ABC-1234', 'type' => 'Maintenance', 'amount' => 5000, 'status' => 'Pending', 'date' => '2024-10-10'],
        ['id' => 2, 'vehicle' => 'DEF-9012', 'type' => 'Repair', 'amount' => 8000, 'status' => 'Approved', 'date' => '2024-10-08'],
    ];
}

function getComplianceRecords() {
    return [
        ['id' => 1, 'vehicle' => 'ABC-1234', 'document' => 'Insurance', 'expiry' => '2025-03-15', 'status' => 'Valid'],
        ['id' => 2, 'vehicle' => 'XYZ-5678', 'document' => 'Registration', 'expiry' => '2024-12-31', 'status' => 'Expiring Soon'],
    ];
}

function getPredictiveData() {
    return [
        ['vehicle' => 'ABC-1234', 'prediction' => 'Oil Change Due', 'confidence' => 95, 'days' => 5],
        ['vehicle' => 'XYZ-5678', 'prediction' => 'Brake Inspection', 'confidence' => 87, 'days' => 12],
    ];
}

// Get KPI data
function getKPIData() {
    $vehicles = getVehicles();
    return [
        'total_vehicles' => count($vehicles),
        'active_vehicles' => count(array_filter($vehicles, fn($v) => $v['status'] === 'Active')),
        'maintenance_due' => count(array_filter($vehicles, fn($v) => $v['status'] === 'Maintenance')),
        'inactive_vehicles' => count(array_filter($vehicles, fn($v) => $v['status'] === 'Inactive')),
    ];
}

function getStatusClass($status) {
    switch($status) {
        case 'Active':
        case 'Completed':
        case 'Valid':
            return 'status-active';
        case 'Maintenance':
        case 'In Progress':
        case 'Pending':
            return 'status-maintenance';
        case 'Inactive':
        case 'Expiring Soon':
            return 'status-inactive';
        default:
            return 'status-maintenance';
    }
}

function getDispatchAssignments() {
    return [
        ['id' => 1, 'vehicle' => 'ABC-1234', 'model' => 'Toyota Hiace', 'type' => 'Van', 'driver' => 'John Smith', 'status' => 'Active', 'dispatch_date' => '2024-10-29', 'route' => 'Manila - Quezon City', 'availability' => 'Assigned'],
        ['id' => 2, 'vehicle' => 'XYZ-5678', 'model' => 'Isuzu NPR', 'type' => 'Truck', 'driver' => 'Jane Doe', 'status' => 'Active', 'dispatch_date' => '2024-10-29', 'route' => 'Manila - Makati', 'availability' => 'Assigned'],
        ['id' => 3, 'vehicle' => 'DEF-9012', 'model' => 'Honda CB500', 'type' => 'Motorcycle', 'driver' => null, 'status' => 'Available', 'dispatch_date' => null, 'route' => null, 'availability' => 'Available'],
        ['id' => 4, 'vehicle' => 'GHI-3456', 'model' => 'Mitsubishi Fuso', 'type' => 'Truck', 'driver' => 'Mike Johnson', 'status' => 'Active', 'dispatch_date' => '2024-10-30', 'route' => 'Manila - Pasig', 'availability' => 'Assigned'],
        ['id' => 5, 'vehicle' => 'JKL-7890', 'model' => 'Toyota Fortuner', 'type' => 'Car', 'driver' => null, 'status' => 'Maintenance', 'dispatch_date' => null, 'route' => null, 'availability' => 'Maintenance'],
        ['id' => 6, 'vehicle' => 'MNO-2345', 'model' => 'Suzuki Carry', 'type' => 'Van', 'driver' => null, 'status' => 'Available', 'dispatch_date' => null, 'route' => null, 'availability' => 'Available'],
    ];
}

function getAvailableDrivers() {
    return [
        ['id' => 1, 'name' => 'John Smith', 'license' => 'N01-12-123456', 'status' => 'On Duty', 'contact' => '0917-123-4567'],
        ['id' => 2, 'name' => 'Jane Doe', 'license' => 'N01-12-234567', 'status' => 'On Duty', 'contact' => '0917-234-5678'],
        ['id' => 3, 'name' => 'Mike Johnson', 'license' => 'N01-12-345678', 'status' => 'On Duty', 'contact' => '0917-345-6789'],
        ['id' => 4, 'name' => 'Sarah Williams', 'license' => 'N01-12-456789', 'status' => 'Available', 'contact' => '0917-456-7890'],
        ['id' => 5, 'name' => 'David Brown', 'license' => 'N01-12-567890', 'status' => 'Available', 'contact' => '0917-567-8901'],
        ['id' => 6, 'name' => 'Emma Wilson', 'license' => 'N01-12-678901', 'status' => 'Off Duty', 'contact' => '0917-678-9012'],
    ];
}

function getDispatchSchedules() {
    return [
        ['date' => '2024-10-29', 'vehicle' => 'ABC-1234', 'driver' => 'John Smith', 'route' => 'Manila - Quezon City'],
        ['date' => '2024-10-29', 'vehicle' => 'XYZ-5678', 'driver' => 'Jane Doe', 'route' => 'Manila - Makati'],
        ['date' => '2024-10-30', 'vehicle' => 'GHI-3456', 'driver' => 'Mike Johnson', 'route' => 'Manila - Pasig'],
        ['date' => '2024-10-31', 'vehicle' => 'ABC-1234', 'driver' => 'Sarah Williams', 'route' => 'Manila - Taguig'],
    ];
}


// Get reservation records
function getReservations() {
    return [
        ['id' => 1, 'vehicle' => 'ABC-1234', 'model' => 'Toyota Hiace', 'type' => 'Van', 'driver' => 'John Smith', 'date' => '2024-10-29', 'time' => '08:00 AM', 'destination' => 'Quezon City Hall', 'purpose' => 'Document Delivery', 'duration' => '4 hours', 'status' => 'In Use', 'created_at' => '2024-10-28'],
        ['id' => 2, 'vehicle' => 'XYZ-5678', 'model' => 'Isuzu NPR', 'type' => 'Truck', 'driver' => 'Jane Doe', 'date' => '2024-10-29', 'time' => '10:00 AM', 'destination' => 'Makati Business District', 'purpose' => 'Equipment Transport', 'duration' => '6 hours', 'status' => 'Assigned', 'created_at' => '2024-10-28'],
        ['id' => 3, 'vehicle' => 'DEF-9012', 'model' => 'Honda CB500', 'type' => 'Motorcycle', 'driver' => null, 'date' => '2024-10-30', 'time' => '09:00 AM', 'destination' => 'BGC Taguig', 'purpose' => 'Urgent Delivery', 'duration' => '2 hours', 'status' => 'Pending Dispatch', 'created_at' => '2024-10-29'],
        ['id' => 4, 'vehicle' => 'GHI-3456', 'model' => 'Mitsubishi Fuso', 'type' => 'Truck', 'driver' => 'Mike Johnson', 'date' => '2024-10-28', 'time' => '07:00 AM', 'destination' => 'Pasig Warehouse', 'purpose' => 'Inventory Transfer', 'duration' => '8 hours', 'status' => 'Completed', 'created_at' => '2024-10-27'],
        ['id' => 5, 'vehicle' => 'JKL-7890', 'model' => 'Toyota Fortuner', 'type' => 'Car', 'driver' => 'Sarah Williams', 'date' => '2024-10-27', 'time' => '02:00 PM', 'destination' => 'Manila Bay Area', 'purpose' => 'Client Meeting', 'duration' => '3 hours', 'status' => 'Cancelled', 'created_at' => '2024-10-26'],
        ['id' => 6, 'vehicle' => 'MNO-2345', 'model' => 'Suzuki Carry', 'type' => 'Van', 'driver' => 'David Brown', 'date' => '2024-10-30', 'time' => '11:00 AM', 'destination' => 'Ortigas Center', 'purpose' => 'Supply Pickup', 'duration' => '5 hours', 'status' => 'Assigned', 'created_at' => '2024-10-29'],
    ];
}

// Get reservation by ID
function getReservationById($id) {
    $reservations = getReservations();
    foreach ($reservations as $reservation) {
        if ($reservation['id'] == $id) {
            return $reservation;
        }
    }
    return null;
}

// Get vehicle availability for calendar
function getVehicleAvailability() {
    return [
        ['date' => '2024-10-29', 'vehicle' => 'ABC-1234', 'status' => 'Assigned', 'driver' => 'John Smith', 'destination' => 'Quezon City', 'time' => '08:00 AM'],
        ['date' => '2024-10-29', 'vehicle' => 'XYZ-5678', 'status' => 'Assigned', 'driver' => 'Jane Doe', 'destination' => 'Makati', 'time' => '10:00 AM'],
        ['date' => '2024-10-29', 'vehicle' => 'GHI-3456', 'status' => 'Maintenance', 'driver' => null, 'destination' => null, 'time' => null],
        ['date' => '2024-10-30', 'vehicle' => 'ABC-1234', 'status' => 'Available', 'driver' => null, 'destination' => null, 'time' => null],
        ['date' => '2024-10-30', 'vehicle' => 'DEF-9012', 'status' => 'Assigned', 'driver' => 'Mike Johnson', 'destination' => 'Pasig', 'time' => '09:00 AM'],
        ['date' => '2024-10-30', 'vehicle' => 'JKL-7890', 'status' => 'Maintenance', 'driver' => null, 'destination' => null, 'time' => null],
        ['date' => '2024-10-31', 'vehicle' => 'ABC-1234', 'status' => 'Assigned', 'driver' => 'Sarah Williams', 'destination' => 'Taguig', 'time' => '11:00 AM'],
        ['date' => '2024-10-31', 'vehicle' => 'MNO-2345', 'status' => 'Available', 'driver' => null, 'destination' => null, 'time' => null],
        ['date' => '2024-11-01', 'vehicle' => 'XYZ-5678', 'status' => 'Assigned', 'driver' => 'David Brown', 'destination' => 'Manila', 'time' => '07:00 AM'],
        ['date' => '2024-11-01', 'vehicle' => 'ABC-1234', 'status' => 'Available', 'driver' => null, 'destination' => null, 'time' => null],
    ];
}

// Get all unique locations
function getLocations() {
    return ['Manila', 'Quezon City', 'Makati', 'Pasig', 'Taguig', 'Mandaluyong', 'BGC', 'Ortigas'];
}

// Get driver behavior analytics
function getDriverBehaviorData() {
    return [
        ['driver' => 'John Smith', 'speeding' => 3, 'harsh_braking' => 5, 'idle_time' => 45, 'score' => 92, 'trips' => 28],
        ['driver' => 'Jane Doe', 'speeding' => 1, 'harsh_braking' => 2, 'idle_time' => 30, 'score' => 96, 'trips' => 32],
        ['driver' => 'Mike Johnson', 'speeding' => 7, 'harsh_braking' => 12, 'idle_time' => 78, 'score' => 78, 'trips' => 25],
        ['driver' => 'Sarah Williams', 'speeding' => 2, 'harsh_braking' => 3, 'idle_time' => 38, 'score' => 94, 'trips' => 30],
        ['driver' => 'David Brown', 'speeding' => 4, 'harsh_braking' => 8, 'idle_time' => 52, 'score' => 85, 'trips' => 27],
        ['driver' => 'Emma Wilson', 'speeding' => 0, 'harsh_braking' => 1, 'idle_time' => 22, 'score' => 98, 'trips' => 35],
    ];
}

// Get behavior incidents
function getBehaviorIncidents() {
    return [
        ['date' => '2024-10-28', 'driver' => 'Mike Johnson', 'type' => 'Speeding', 'severity' => 'High', 'location' => 'EDSA-Quezon Ave', 'speed' => 85],
        ['date' => '2024-10-27', 'driver' => 'John Smith', 'type' => 'Harsh Braking', 'severity' => 'Medium', 'location' => 'C5 Road', 'speed' => null],
        ['date' => '2024-10-26', 'driver' => 'David Brown', 'type' => 'Excessive Idle', 'severity' => 'Low', 'location' => 'Makati Ave', 'speed' => null],
        ['date' => '2024-10-25', 'driver' => 'Mike Johnson', 'type' => 'Speeding', 'severity' => 'High', 'location' => 'SLEX Northbound', 'speed' => 95],
    ];
}

// Get monthly behavior trends
function getMonthlyBehaviorTrends() {
    return [
        ['month' => 'May', 'speeding' => 12, 'harsh_braking' => 18, 'idle_time' => 245],
        ['month' => 'Jun', 'speeding' => 15, 'harsh_braking' => 22, 'idle_time' => 298],
        ['month' => 'Jul', 'speeding' => 10, 'harsh_braking' => 16, 'idle_time' => 210],
        ['month' => 'Aug', 'speeding' => 8, 'harsh_braking' => 14, 'idle_time' => 185],
        ['month' => 'Sep', 'speeding' => 11, 'harsh_braking' => 19, 'idle_time' => 220],
        ['month' => 'Oct', 'speeding' => 17, 'harsh_braking' => 31, 'idle_time' => 265],
    ];
}

function getIncidentCases() {
    return [
        ['id' => 1, 'case_number' => 'INC-2024-001', 'date' => '2024-10-28', 'driver' => 'Mike Johnson', 'vehicle' => 'GHI-3456', 'type' => 'Accident', 'severity' => 'High', 'status' => 'Under Investigation', 'description' => 'Minor collision at intersection', 'location' => 'EDSA-Quezon Ave', 'reported_by' => 'Admin'],
        ['id' => 2, 'case_number' => 'INC-2024-002', 'date' => '2024-10-27', 'driver' => 'John Smith', 'vehicle' => 'ABC-1234', 'type' => 'Traffic Violation', 'severity' => 'Medium', 'status' => 'Resolved', 'description' => 'Illegal parking citation', 'location' => 'Makati CBD', 'reported_by' => 'Traffic Enforcer'],
        ['id' => 3, 'case_number' => 'INC-2024-003', 'date' => '2024-10-26', 'driver' => 'David Brown', 'vehicle' => 'MNO-2345', 'type' => 'Breakdown', 'severity' => 'Low', 'status' => 'Closed', 'description' => 'Engine overheating', 'location' => 'C5 Road', 'reported_by' => 'Driver'],
        ['id' => 4, 'case_number' => 'INC-2024-004', 'date' => '2024-10-25', 'driver' => 'Jane Doe', 'vehicle' => 'XYZ-5678', 'type' => 'Speeding', 'severity' => 'Medium', 'status' => 'Pending Review', 'description' => 'Exceeded speed limit by 20km/h', 'location' => 'SLEX Southbound', 'reported_by' => 'Traffic Camera'],
        ['id' => 5, 'case_number' => 'INC-2024-005', 'date' => '2024-10-24', 'driver' => 'Mike Johnson', 'vehicle' => 'GHI-3456', 'type' => 'Rule Violation', 'severity' => 'High', 'status' => 'Under Investigation', 'description' => 'Driving without proper documentation', 'location' => 'Pasig City', 'reported_by' => 'Security'],
        ['id' => 6, 'case_number' => 'INC-2024-006', 'date' => '2024-10-23', 'driver' => 'Sarah Williams', 'vehicle' => 'JKL-7890', 'type' => 'Customer Complaint', 'severity' => 'Low', 'status' => 'Resolved', 'description' => 'Late delivery complaint', 'location' => 'Taguig BGC', 'reported_by' => 'Customer'],
    ];
}

function getDriverProfiles() {
    return [
        [
            'id' => 1, 
            'name' => 'John Smith', 
            'license' => 'N01-12-123456', 
            'expiry' => '2026-05-15',
            'status' => 'Active',
            'rating' => 4.8,
            'total_trips' => 328,
            'total_distance' => 12450,
            'join_date' => '2022-01-15',
            'phone' => '0917-123-4567',
            'email' => 'john.smith@logistics.com',
            'address' => 'Quezon City, Metro Manila',
            'emergency_contact' => '0917-111-2222',
            'blood_type' => 'O+',
            'incidents' => 2,
            'safety_score' => 92,
            'on_time_rate' => 95,
            'certifications' => ['Defensive Driving', 'First Aid']
        ],
        [
            'id' => 2, 
            'name' => 'Jane Doe', 
            'license' => 'N01-12-234567', 
            'expiry' => '2025-12-20',
            'status' => 'Active',
            'rating' => 4.9,
            'total_trips' => 432,
            'total_distance' => 15200,
            'join_date' => '2021-06-10',
            'phone' => '0917-234-5678',
            'email' => 'jane.doe@logistics.com',
            'address' => 'Makati City, Metro Manila',
            'emergency_contact' => '0917-222-3333',
            'blood_type' => 'A+',
            'incidents' => 1,
            'safety_score' => 96,
            'on_time_rate' => 98,
            'certifications' => ['Defensive Driving', 'Hazmat Handling', 'First Aid']
        ],
        [
            'id' => 3, 
            'name' => 'Mike Johnson', 
            'license' => 'N01-12-345678', 
            'expiry' => '2025-08-30',
            'status' => 'On Leave',
            'rating' => 3.8,
            'total_trips' => 275,
            'total_distance' => 9800,
            'join_date' => '2022-08-20',
            'phone' => '0917-345-6789',
            'email' => 'mike.johnson@logistics.com',
            'address' => 'Pasig City, Metro Manila',
            'emergency_contact' => '0917-333-4444',
            'blood_type' => 'B+',
            'incidents' => 5,
            'safety_score' => 78,
            'on_time_rate' => 82,
            'certifications' => ['Defensive Driving']
        ],
        [
            'id' => 4, 
            'name' => 'Sarah Williams', 
            'license' => 'N01-12-456789', 
            'expiry' => '2026-03-15',
            'status' => 'Active',
            'rating' => 4.7,
            'total_trips' => 380,
            'total_distance' => 14100,
            'join_date' => '2021-11-05',
            'phone' => '0917-456-7890',
            'email' => 'sarah.williams@logistics.com',
            'address' => 'Taguig City, Metro Manila',
            'emergency_contact' => '0917-444-5555',
            'blood_type' => 'AB+',
            'incidents' => 1,
            'safety_score' => 94,
            'on_time_rate' => 96,
            'certifications' => ['Defensive Driving', 'First Aid', 'Heavy Vehicle']
        ],
        [
            'id' => 5, 
            'name' => 'David Brown', 
            'license' => 'N01-12-567890', 
            'expiry' => '2025-11-25',
            'status' => 'Active',
            'rating' => 4.5,
            'total_trips' => 310,
            'total_distance' => 11500,
            'join_date' => '2022-03-12',
            'phone' => '0917-567-8901',
            'email' => 'david.brown@logistics.com',
            'address' => 'Mandaluyong City, Metro Manila',
            'emergency_contact' => '0917-555-6666',
            'blood_type' => 'O-',
            'incidents' => 3,
            'safety_score' => 85,
            'on_time_rate' => 88,
            'certifications' => ['Defensive Driving', 'Customer Service']
        ],
        [
            'id' => 6, 
            'name' => 'Emma Wilson', 
            'license' => 'N01-12-678901', 
            'expiry' => '2026-07-10',
            'status' => 'Active',
            'rating' => 5.0,
            'total_trips' => 485,
            'total_distance' => 18900,
            'join_date' => '2021-02-20',
            'phone' => '0917-678-9012',
            'email' => 'emma.wilson@logistics.com',
            'address' => 'Manila City, Metro Manila',
            'emergency_contact' => '0917-666-7777',
            'blood_type' => 'A-',
            'incidents' => 0,
            'safety_score' => 98,
            'on_time_rate' => 99,
            'certifications' => ['Defensive Driving', 'First Aid', 'Advanced Driving', 'Customer Service']
        ],
    ];
}

function getTripPerformanceReports() {
    return [
        [
            'id' => 1,
            'trip_id' => 'TRP-2024-001',
            'date' => '2024-10-28',
            'vehicle' => 'ABC-1234',
            'vehicle_model' => 'Toyota Hiace',
            'driver' => 'John Smith',
            'start_location' => 'Manila Warehouse',
            'end_location' => 'Quezon City Hub',
            'route' => 'Manila - Quezon City',
            'planned_distance' => 45,
            'actual_distance' => 47,
            'planned_duration' => '2.5 hours',
            'actual_duration' => '2.8 hours',
            'fuel_used' => 12.5,
            'fuel_cost' => 625,
            'status' => 'On-Time',
            'on_time_percentage' => 95,
            'idle_time' => 15,
            'route_deviation' => 2,
            'notes' => 'Smooth delivery, minor traffic delay',
            'departure_time' => '08:00 AM',
            'arrival_time' => '10:48 AM'
        ],
        [
            'id' => 2,
            'trip_id' => 'TRP-2024-002',
            'date' => '2024-10-28',
            'vehicle' => 'XYZ-5678',
            'vehicle_model' => 'Isuzu NPR',
            'driver' => 'Jane Doe',
            'start_location' => 'Manila Warehouse',
            'end_location' => 'Makati Business District',
            'route' => 'Manila - Makati',
            'planned_distance' => 32,
            'actual_distance' => 35,
            'planned_duration' => '1.5 hours',
            'actual_duration' => '2.2 hours',
            'fuel_used' => 18.3,
            'fuel_cost' => 915,
            'status' => 'Delayed',
            'on_time_percentage' => 68,
            'idle_time' => 35,
            'route_deviation' => 3,
            'notes' => 'Heavy traffic on EDSA, customer requested wait time',
            'departure_time' => '09:00 AM',
            'arrival_time' => '11:13 AM'
        ],
        [
            'id' => 3,
            'trip_id' => 'TRP-2024-003',
            'date' => '2024-10-27',
            'vehicle' => 'DEF-9012',
            'vehicle_model' => 'Honda CB500',
            'driver' => 'Mike Johnson',
            'start_location' => 'Manila Hub',
            'end_location' => 'BGC Taguig',
            'route' => 'Manila - Taguig',
            'planned_distance' => 28,
            'actual_distance' => 28,
            'planned_duration' => '1 hour',
            'actual_duration' => '0.9 hours',
            'fuel_used' => 3.2,
            'fuel_cost' => 160,
            'status' => 'On-Time',
            'on_time_percentage' => 100,
            'idle_time' => 5,
            'route_deviation' => 0,
            'notes' => 'Early delivery, no issues',
            'departure_time' => '02:00 PM',
            'arrival_time' => '02:54 PM'
        ],
        [
            'id' => 4,
            'trip_id' => 'TRP-2024-004',
            'date' => '2024-10-27',
            'vehicle' => 'GHI-3456',
            'vehicle_model' => 'Mitsubishi Fuso',
            'driver' => 'David Brown',
            'start_location' => 'Manila Warehouse',
            'end_location' => 'Pasig Distribution Center',
            'route' => 'Manila - Pasig',
            'planned_distance' => 38,
            'actual_distance' => 38,
            'planned_duration' => '2 hours',
            'actual_duration' => '2.1 hours',
            'fuel_used' => 22.1,
            'fuel_cost' => 1105,
            'status' => 'On-Time',
            'on_time_percentage' => 95,
            'idle_time' => 12,
            'route_deviation' => 0,
            'notes' => 'On schedule, good performance',
            'departure_time' => '10:00 AM',
            'arrival_time' => '12:06 PM'
        ],
        [
            'id' => 5,
            'trip_id' => 'TRP-2024-005',
            'date' => '2024-10-26',
            'vehicle' => 'ABC-1234',
            'vehicle_model' => 'Toyota Hiace',
            'driver' => 'Sarah Williams',
            'start_location' => 'Quezon City Hub',
            'end_location' => 'Ortigas Center',
            'route' => 'Quezon City - Ortigas',
            'planned_distance' => 22,
            'actual_distance' => 24,
            'planned_duration' => '1.2 hours',
            'actual_duration' => '1.8 hours',
            'fuel_used' => 8.5,
            'fuel_cost' => 425,
            'status' => 'Delayed',
            'on_time_percentage' => 67,
            'idle_time' => 28,
            'route_deviation' => 2,
            'notes' => 'Road construction caused detour',
            'departure_time' => '03:00 PM',
            'arrival_time' => '04:48 PM'
        ],
        [
            'id' => 6,
            'trip_id' => 'TRP-2024-006',
            'date' => '2024-10-26',
            'vehicle' => 'JKL-7890',
            'vehicle_model' => 'Toyota Fortuner',
            'driver' => 'Emma Wilson',
            'start_location' => 'Manila Office',
            'end_location' => 'Manila Bay Area',
            'route' => 'Manila - Manila Bay',
            'planned_distance' => 15,
            'actual_distance' => 0,
            'planned_duration' => '0.8 hours',
            'actual_duration' => '0 hours',
            'fuel_used' => 0,
            'fuel_cost' => 0,
            'status' => 'Cancelled',
            'on_time_percentage' => 0,
            'idle_time' => 0,
            'route_deviation' => 0,
            'notes' => 'Client cancelled meeting last minute',
            'departure_time' => '-',
            'arrival_time' => '-'
        ],
        [
            'id' => 7,
            'trip_id' => 'TRP-2024-007',
            'date' => '2024-10-25',
            'vehicle' => 'MNO-2345',
            'vehicle_model' => 'Suzuki Carry',
            'driver' => 'John Smith',
            'start_location' => 'Manila Warehouse',
            'end_location' => 'Mandaluyong Shopping District',
            'route' => 'Manila - Mandaluyong',
            'planned_distance' => 18,
            'actual_distance' => 18,
            'planned_duration' => '1 hour',
            'actual_duration' => '0.95 hours',
            'fuel_used' => 6.2,
            'fuel_cost' => 310,
            'status' => 'On-Time',
            'on_time_percentage' => 100,
            'idle_time' => 8,
            'route_deviation' => 0,
            'notes' => 'Perfect timing, customer satisfied',
            'departure_time' => '11:00 AM',
            'arrival_time' => '11:57 AM'
        ],
    ];
}

function getTripStatistics() {
    $trips = getTripPerformanceReports();
    return [
        'total_trips' => count($trips),
        'on_time_trips' => count(array_filter($trips, fn($t) => $t['status'] === 'On-Time')),
        'delayed_trips' => count(array_filter($trips, fn($t) => $t['status'] === 'Delayed')),
        'cancelled_trips' => count(array_filter($trips, fn($t) => $t['status'] === 'Cancelled')),
        'total_distance' => array_sum(array_column($trips, 'actual_distance')),
        'total_fuel' => array_sum(array_column($trips, 'fuel_used')),
        'avg_on_time_percentage' => round(array_sum(array_column($trips, 'on_time_percentage')) / count($trips)),
    ];
}

function getTransportExpenses() {
    return [
        ['id' => 1, 'expense_id' => 'EXP-2024-001', 'date' => '2024-10-28', 'category' => 'Fuel', 'amount' => 2500, 'vehicle' => 'ABC-1234', 'driver' => 'John Smith', 'description' => 'Fuel refill at Petron'],
        ['id' => 2, 'expense_id' => 'EXP-2024-002', 'date' => '2024-10-28', 'category' => 'Maintenance', 'amount' => 5000, 'vehicle' => 'XYZ-5678', 'driver' => 'Jane Doe', 'description' => 'Oil change and filter replacement'],
        ['id' => 3, 'expense_id' => 'EXP-2024-003', 'date' => '2024-10-27', 'category' => 'Fuel', 'amount' => 3000, 'vehicle' => 'DEF-9012', 'driver' => 'Mike Johnson', 'description' => 'Fuel refill at Shell'],
        ['id' => 4, 'expense_id' => 'EXP-2024-004', 'date' => '2024-10-27', 'category' => 'Repairs', 'amount' => 8500, 'vehicle' => 'GHI-3456', 'driver' => 'David Brown', 'description' => 'Brake pad replacement'],
        ['id' => 5, 'expense_id' => 'EXP-2024-005', 'date' => '2024-10-26', 'category' => 'Fuel', 'amount' => 1800, 'vehicle' => 'ABC-1234', 'driver' => 'John Smith', 'description' => 'Fuel refill at Caltex'],
        ['id' => 6, 'expense_id' => 'EXP-2024-006', 'date' => '2024-10-26', 'category' => 'Licensing', 'amount' => 4200, 'vehicle' => 'JKL-7890', 'driver' => 'Sarah Williams', 'description' => 'Vehicle registration renewal'],
        ['id' => 7, 'expense_id' => 'EXP-2024-007', 'date' => '2024-10-25', 'category' => 'Maintenance', 'amount' => 3500, 'vehicle' => 'MNO-2345', 'driver' => 'Emma Wilson', 'description' => 'Tire rotation and alignment'],
        ['id' => 8, 'expense_id' => 'EXP-2024-008', 'date' => '2024-10-25', 'category' => 'Fuel', 'amount' => 2200, 'vehicle' => 'XYZ-5678', 'driver' => 'Jane Doe', 'description' => 'Fuel refill at Petron'],
        ['id' => 9, 'expense_id' => 'EXP-2024-009', 'date' => '2024-10-24', 'category' => 'Misc', 'amount' => 800, 'vehicle' => 'ABC-1234', 'driver' => 'John Smith', 'description' => 'Parking and toll fees'],
        ['id' => 10, 'expense_id' => 'EXP-2024-010', 'date' => '2024-10-24', 'category' => 'Repairs', 'amount' => 12000, 'vehicle' => 'GHI-3456', 'driver' => 'David Brown', 'description' => 'Engine diagnostic and repair'],
        ['id' => 11, 'expense_id' => 'EXP-2024-011', 'date' => '2024-10-23', 'category' => 'Fuel', 'amount' => 2800, 'vehicle' => 'DEF-9012', 'driver' => 'Mike Johnson', 'description' => 'Fuel refill at Shell'],
        ['id' => 12, 'expense_id' => 'EXP-2024-012', 'date' => '2024-10-23', 'category' => 'Maintenance', 'amount' => 6500, 'vehicle' => 'MNO-2345', 'driver' => 'Emma Wilson', 'description' => 'Air conditioning service'],
        ['id' => 13, 'expense_id' => 'EXP-2024-013', 'date' => '2024-10-22', 'category' => 'Fuel', 'amount' => 1900, 'vehicle' => 'JKL-7890', 'driver' => 'Sarah Williams', 'description' => 'Fuel refill at Caltex'],
        ['id' => 14, 'expense_id' => 'EXP-2024-014', 'date' => '2024-10-22', 'category' => 'Misc', 'amount' => 1200, 'vehicle' => 'XYZ-5678', 'driver' => 'Jane Doe', 'description' => 'Car wash and detailing'],
        ['id' => 15, 'expense_id' => 'EXP-2024-015', 'date' => '2024-10-21', 'category' => 'Repairs', 'amount' => 4500, 'vehicle' => 'ABC-1234', 'driver' => 'John Smith', 'description' => 'Battery replacement'],
    ];
}

function getTransportCostSummary() {
    $expenses = getTransportExpenses();
    $totalCost = array_sum(array_column($expenses, 'amount'));
    
    // Calculate category breakdown
    $categories = [];
    foreach ($expenses as $expense) {
        if (!isset($categories[$expense['category']])) {
            $categories[$expense['category']] = 0;
        }
        $categories[$expense['category']] += $expense['amount'];
    }
    
    // Sort by amount descending
    arsort($categories);
    
    return [
        'total_cost' => $totalCost,
        'monthly_change' => -8.5, // Negative means decrease
        'top_categories' => array_slice($categories, 0, 3, true),
        'category_breakdown' => $categories,
        'avg_daily_cost' => round($totalCost / 30),
    ];
}

function getFuelConsumptionTrends() {
    return [
        ['month' => 'May', 'consumption' => 450, 'cost' => 22500],
        ['month' => 'Jun', 'consumption' => 480, 'cost' => 24000],
        ['month' => 'Jul', 'consumption' => 420, 'cost' => 21000],
        ['month' => 'Aug', 'consumption' => 390, 'cost' => 19500],
        ['month' => 'Sep', 'consumption' => 435, 'cost' => 21750],
        ['month' => 'Oct', 'consumption' => 410, 'cost' => 20500],
    ];
}

function getVehicleCostComparison() {
    $vehicles = getVehicles();
    $expenses = getTransportExpenses();
    
    $vehicleCosts = [];
    foreach ($expenses as $expense) {
        if (!isset($vehicleCosts[$expense['vehicle']])) {
            $vehicleCosts[$expense['vehicle']] = 0;
        }
        $vehicleCosts[$expense['vehicle']] += $expense['amount'];
    }
    
    $fleetAverage = array_sum($vehicleCosts) / count($vehicleCosts);
    
    return [
        'vehicle_costs' => $vehicleCosts,
        'fleet_average' => round($fleetAverage),
    ];
}

function getOptimizationInsights() {
    return [
        [
            'type' => 'cost_saving',
            'title' => 'High Fuel Consumption Detected',
            'description' => 'Vehicle GHI-3456 has 25% higher fuel consumption than fleet average. Consider maintenance check.',
            'potential_savings' => 3500,
            'priority' => 'High',
            'icon' => 'fa-gas-pump',
            'color' => 'red'
        ],
        [
            'type' => 'maintenance',
            'title' => 'Preventive Maintenance Opportunity',
            'description' => 'Scheduling regular maintenance for ABC-1234 can reduce repair costs by up to 40%.',
            'potential_savings' => 5000,
            'priority' => 'Medium',
            'icon' => 'fa-wrench',
            'color' => 'yellow'
        ],
        [
            'type' => 'route',
            'title' => 'Route Optimization Available',
            'description' => 'Alternative routes for Manila-Quezon City trips can save 15% in fuel costs.',
            'potential_savings' => 2000,
            'priority' => 'Medium',
            'icon' => 'fa-route',
            'color' => 'blue'
        ],
        [
            'type' => 'efficiency',
            'title' => 'Driver Training Recommendation',
            'description' => 'Fuel-efficient driving training for 3 drivers can improve overall efficiency by 12%.',
            'potential_savings' => 4500,
            'priority' => 'Low',
            'icon' => 'fa-graduation-cap',
            'color' => 'green'
        ],
    ];
}

function getNotifications() {
    return [
        [
            'id' => 1,
            'type' => 'maintenance',
            'title' => 'Maintenance Due',
            'message' => 'Vehicle ABC-1234 requires oil change within 3 days',
            'time' => '5 minutes ago',
            'read' => false,
            'icon' => 'fa-wrench',
            'color' => 'yellow',
            'link' => '/CAPTONES/module_1/maintenance-tracker.php'
        ],
        [
            'id' => 2,
            'type' => 'incident',
            'title' => 'New Incident Reported',
            'message' => 'Driver Mike Johnson reported a minor collision',
            'time' => '1 hour ago',
            'read' => false,
            'icon' => 'fa-exclamation-triangle',
            'color' => 'red',
            'link' => '/CAPTONES/module_3/incident-case-management.php'
        ],
        [
            'id' => 3,
            'type' => 'approval',
            'title' => 'Pending Approval',
            'message' => '2 maintenance requests waiting for your approval',
            'time' => '2 hours ago',
            'read' => false,
            'icon' => 'fa-clipboard-check',
            'color' => 'blue',
            'link' => '/CAPTONES/module_1/maintenance-approvals.php'
        ],
        [
            'id' => 4,
            'type' => 'trip',
            'title' => 'Trip Completed',
            'message' => 'Trip TRP-2024-015 completed successfully',
            'time' => '3 hours ago',
            'read' => true,
            'icon' => 'fa-check-circle',
            'color' => 'green',
            'link' => '/CAPTONES/module_3/trip-performance.php'
        ],
        [
            'id' => 5,
            'type' => 'fuel',
            'title' => 'High Fuel Consumption',
            'message' => 'Vehicle GHI-3456 showing 25% higher consumption',
            'time' => '5 hours ago',
            'read' => true,
            'icon' => 'fa-gas-pump',
            'color' => 'red',
            'link' => '/CAPTONES/module_4/transport-cost-optimization.php'
        ],
        [
            'id' => 6,
            'type' => 'compliance',
            'title' => 'License Expiring Soon',
            'message' => 'Vehicle registration for XYZ-5678 expires in 15 days',
            'time' => '1 day ago',
            'read' => true,
            'icon' => 'fa-file-contract',
            'color' => 'yellow',
            'link' => '/CAPTONES/module_1/compliance-licensing.php'
        ],
        [
            'id' => 7,
            'type' => 'driver',
            'title' => 'Driver Performance Alert',
            'message' => 'Driver training recommended for 3 drivers',
            'time' => '1 day ago',
            'read' => true,
            'icon' => 'fa-user-check',
            'color' => 'blue',
            'link' => '/CAPTONES/module_3/driver-profiles.php'
        ],
        [
            'id' => 8,
            'type' => 'reservation',
            'title' => 'New Reservation',
            'message' => 'Vehicle reservation request for tomorrow',
            'time' => '2 days ago',
            'read' => true,
            'icon' => 'fa-calendar-check',
            'color' => 'green',
            'link' => '/CAPTONES/module_2/reservation-management.php'
        ],
    ];
}

function getUnreadNotificationCount() {
    $notifications = getNotifications();
    return count(array_filter($notifications, fn($n) => !$n['read']));
}
?>