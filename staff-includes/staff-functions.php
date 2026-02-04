<?php
// Shared functions for the dashboard
// Mock data - Replace with database queries

// Get KPI data
function getKPIData() {
    $assigned = getAssigned();
    $driverReviews = getdriverReviews();
    $incident = getIncident();
    $trips = getLogs();

    return [

    'assigned_total' => is_array($assigned) ? count($assigned) : 0,
    'total_Drivers'  => is_array($driverReviews) ? count($driverReviews) : 0,
    'totalIncidents' => is_array($incident) ? count($incident) : 0,
    'total_trips' => is_array($trips) ? count($trips) : 0,
    ];
}


function getAssigned() {
    return [
        ['plate' => 'ABC-1234', 'model' => 'Toyota Hiace', 'type' => 'Van', 'year' => 2022, 'status' => 'Active', 'assigned_date' => 'Oct. 25, 2024', 'last_maintenance' => '2024-08-15', 'remarks' => 'Ready to Deployment'],
        ['plate' => 'XYZ-5678', 'model' => 'Isuzu NPR', 'type' => 'Truck', 'year' => 2021, 'status' => 'Active', 'assigned_date' => 'Oct. 29, 2024', 'last_maintenance' => '2024-09-01', 'remarks' => 'Awaiting Assignment'],
        ['plate' => 'DEF-9012', 'model' => 'Honda CB500', 'type' => 'Motorcycle', 'year' => 2023, 'status' => 'Active', 'assigned_date' => 'Oct. 30, 2024', 'last_maintenance' => '2024-07-20', 'remarks' => 'In Use'],
        ['plate' => 'GHI-3456', 'model' => 'Mitsubishi Fuso', 'type' => 'Truck', 'year' => 2020, 'status' => 'Maintenance', 'assigned_date' => 'Oct. 28, 2024', 'last_maintenance' => '2024-06-10', 'remarks' => 'Under Maintenance'],
        ['plate' => 'JKL-7890', 'model' => 'Toyota Fortuner', 'type' => 'Car', 'year' => 2019, 'status' => 'Maintenance', 'assigned_date' => 'Oct. 27, 2024', 'last_maintenance' => '2024-04-15', 'remarks' => 'For Inspection'],
        ['plate' => 'MNO-2345', 'model' => 'Suzuki Carry', 'type' => 'Van', 'year' => 2022, 'status' => 'Active', 'assigned_date' => 'Oct. 30, 2024', 'last_maintenance' => '2024-08-25', 'remarks' => 'Available'],
    ];
}

function getTrack() {
    return [
        ['date' => '2025-11-12','plate' => 'ABC-1234','fuel-type' => 'Diesel', 'liters' => '32.80 L', 'cost' => '2,160.00', 'mileage' => '125, 430 km','remarks' => 'Refueled at Shell NLEX; Odometer Verified'],
        ['date' => '2025-11-10','plate' => 'DEF-9012','fuel-type' => 'Gasoline', 'liters' => '15.00 L', 'cost' => '1,180.00', 'mileage' => '98, 312 km','remarks' => 'Partial Refill- Emergency Trip'],
        ['date' => '2025-11-03','plate' => 'MNO-2345','fuel-type' => 'Hybrid', 'liters' => '43.75 L', 'cost' => '3,100.50', 'mileage' => '130, 025 km','remarks' => 'Full Tank; Receipt Attached'],

    ];
}

function getTotalFuelCost($track) {
    $total = 0;
    foreach ($track as $row) {
        $clean = floatval(str_replace(['₱', ',', ' '], '', $row['cost']));
        $total += $clean;
    }
    return $total;
}

function getTotalLiters($track) {
    $total = 0;
    foreach ($track as $row) {
        $clean = floatval(str_replace(['L', ',', ' '], '', $row['liters']));
        $total += $clean;
    }
    return $total;
}

function getAverageMileage($track) {
    $total = 0;
    $count = 0;
    foreach ($track as $row) {
        $clean = floatval(str_replace([',', ' '], '', $row['mileage']));
        $total += $clean;
        $count++;
    }
    return $count > 0 ? ($total / $count) : 0;
}


function getLogs() {
    return [
        ['id' => 1, 'vehicle' => 'XYZ-5678', 'model' => 'Isuzu PNR', 'date' => '2025-11-18', 'start' => 'Warehouse 2', 'destination' => 'Makati CBD', 'distance' => '23.4 km', 'duration' => '1 hour and 12 minutes'],
        ['id' => 2, 'vehicle' => 'ABC-1234', 'model' => 'Toyota Hiace', 'date' => '2025-11-19', 'start' => 'Admin Office - Main Branch', 'destination' => 'Pasig City Hall', 'distance' => '14.8 km', 'duration' => '42 minutes'],
        ['id' => 3, 'vehicle' => 'JKL-7890', 'model' => 'Toyota Fortuner', 'date' => '2025-11-20', 'start' => 'Caloocan Yard', 'destination' => 'Quezon City General Hospital', 'distance' => '8.3 km', 'duration' => '27 minutes'],

    ];
}


function getAverageDistance($trips) {
    $total = 0;
    $count = 0;
    foreach ($trips as $row) {
        $clean = floatval(str_replace([',', ' '], '', $row['distance']));
        $total += $clean;
        $count++;
    }
    return $count > 0 ? ($total / $count) : 0;
}

function durationToMinutes($dur){
    if ($dur === null || $dur === '') return 0;
    if (is_numeric($dur)) return (int)$dur;
    if (preg_match('/^(\d+):(\d{1,2})$/', $dur, $m)) {
        return intval($m[1]) * 60 + intval($m[2]);
    }
    // fallback: extract first number found (e.g. "125 km" -> 125)
    if (preg_match('/\d+/', $dur, $m2)) return intval($m2[0]);
    return 0;
}

function getReserv() {
    return [
        ['id' => 1, 'vehicle' => 'XYZ-5678', 'model' => 'Isuzu PNR', 'date' => '2025-11-30', 'destination' => 'Makati CBD', 'status' => 'Approved'],
        ['id' => 2, 'vehicle' => 'ABC-1234', 'model' => 'Toyota Hiace', 'date' => '2025-11-21', 'destination' => 'Pasig City Hall','status' => 'Denied'],
        ['id' => 3, 'vehicle' => 'JKL-7890', 'model' => 'Toyota Fortuner', 'date' => '2025-11-15', 'destination' => 'Quezon City General Hospital','status' => 'Pending'],
        
    ];
}

function getPerform() {
    return [
        ['id' => 1, 'driver' => 'Mike Johnson', 'trips' => '275', 'rating' => '3.8', 'feedback' => '320'],
        ['id' => 2, 'driver' => 'Sarah Williams', 'trips' => '380', 'rating' => '4.7', 'feedback' => '440'],
        ['id' => 3, 'driver' => 'Jane Doe', 'trips' => '432', 'rating' => '4.9', 'feedback' => '500'],

    ];
}

function  getdriverReviews() {
return [
    "Mike Johnson" => [
        ["name"=>"Customer A","initials"=>"CA","date"=>"Dec 01, 2025","rating"=>5,"message"=>"Very punctual and professional."],
        ["name"=>"Supervisor","initials"=>"SP","date"=>"Nov 28, 2025","rating"=>4,"message"=>"Handled deliveries carefully."],
        ["name"=>"Client X","initials"=>"CX","date"=>"Nov 20, 2025","rating"=>5,"message"=>"Excellent service!"]
    ],
    "Sarah Williams" => [
        ["name"=>"Customer B","initials"=>"CB","date"=>"Nov 25, 2025","rating"=>5,"message"=>"Friendly and reliable driver."],
        ["name"=>"Manager","initials"=>"MG","date"=>"Nov 18, 2025","rating"=>4,"message"=>"Good handling of cargo."]
    ],
    "Jane Doe" => [
        ["name"=>"Customer C","initials"=>"CC","date"=>"Nov 22, 2025","rating"=>5,"message"=>"Fast and careful driver."],
        ["name"=>"Supervisor","initials"=>"SP","date"=>"Nov 15, 2025","rating"=>5,"message"=>"Always on time."]
    ]
];
}


function getIncident() 
{
     return
      [
         ['id' => 1, 'vehicle' => 'XYZ-5678', 'model' => 'Isuzu PNR', 'date' => 'Nov. 11, 2025', 'incident_type' => 'Minor Collision ', 'status' => 'Resolved', 'remarks' => 'Driver hit pole during parking'],
         ['id' => 2, 'vehicle' => 'ABC-1234', 'model' => 'Toyota Hiace', 'date' => 'Nov. 25, 2025', 'incident_type' => 'Engine Overheat', 'status' => 'Pending', 'remarks' => 'Possible coolant leakage'],
         ['id' => 3, 'vehicle' => 'JKL-7890', 'model' => 'Toyota Fotuner', 'date' => 'Dec. 10, 2025', 'incident_type' => 'Brake Failure', 'status' => 'Under Investigation', 'remarks' => 'Brakes unresponsive; Needs root cause check'], ]; }
?>