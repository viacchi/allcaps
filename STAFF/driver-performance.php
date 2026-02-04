<?php
include '../staff-includes/staff-functions.php';

$driverReviews = getdriverReviews();
$performance = getPerform();
$totalDrivers = count($performance);

$totalFeedback = 0;
$totalTrips = 0;
$totalRating = 0;
foreach ($performance as $row) {
    $totalFeedback += (int)$row['feedback'];
    $totalTrips += (int)$row['trips'];
    $totalRating += (float)$row['rating'];
}
$averageRating = $totalDrivers > 0 ? round($totalRating / $totalDrivers, 1) : 0;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Performance- Logistics Staff</title>
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

<?php include '../staff-includes/staff-sidebar.php'; ?>

<div class="ml-0 md:ml-[280px] min-h-screen">

<?php include '../staff-includes/staff-header.php'; ?>

<main class="p-6">
    <div class="mb-8">
        <p class="text-gray-600 mt-2">Shows the perfomance rating and feedback of drivers assigned to the staff</p>
    </div>


  <!-- KPI Cards -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-slate-500">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-gray-600 text-sm font-medium">Total Drivers</div>
          <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $totalDrivers; ?></div>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
          <i class="fa-solid fa-users text-slate-600 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-sky-500">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-gray-600 text-sm font-medium">Total Feedbacks</div>
          <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $totalFeedback; ?></div>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
          <i class="fa-solid fa-comment-dots text-sky-600 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-yellow-500">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-gray-600 text-sm font-medium">Average Rating</div>
          <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $averageRating; ?></div>
        </div>
        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
          <i class="fa-solid fa-star text-yellow-600 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg p-5 shadow-sm border-t-4 border-emerald-500">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-gray-600 text-sm font-medium">Trips Completed</div>
          <div class="text-3xl font-bold text-gray-900 my-2"><?php echo $totalTrips; ?></div>
        </div>
        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
          <i class="fa-solid fa-clipboard-check text-emerald-600 text-xl"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Table -->
  <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
    <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex justify-between items-center">
      <h2 class="text-lg font-bold text-gray-900">Driver Performance List</h2>
    </div>

    <!-- Filters -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex gap-3 flex-wrap">
        <input type="text" id="searchInput" placeholder="Search..." onkeyup="filterTable()" class="flex-1 px-4 py-2 border border-gray-300 rounded-md text-sm">
        <select id="driverFilter" onchange="filterTable()" class="px-4 py-2 border border-gray-300 rounded-md text-sm bg-white">
          <option value="">All Drivers</option>
          <?php
            $driverList = array_unique(array_column($performance,'driver'));
            foreach ($driverList as $d){
              echo '<option value="'.htmlspecialchars($d).'">'.htmlspecialchars($d).'</option>';
            }
          ?>
        </select>
      </div>
    </div>

    <!-- Table Body -->
    <div class="overflow-x-auto max-h-[52vh] overflow-auto">
      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">ID</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Driver</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Trips</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Rating</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600">Feedbacks</th>
            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600">Actions</th>
          </tr>
        </thead>
                       <tbody id="tableBody">
                        <?php foreach ($performance as $row): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">

                            <td class="px-5 py-4 text-sm font-semibold  text-primary-green">
                                #<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?>
                            </td>

                            <td class="px-5 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-primary-green rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            <?php echo strtoupper(substr($row['driver'], 0, 2)); ?>
                                        </div>
                                        <span class="text-gray-900"><?php echo $row['driver']; ?></span>
                                    </div>
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">
                                <?php echo $row['trips'] ? '<i class="fas fa-route mr-1"></i>' .htmlspecialchars($row['trips']): '<span class="text-gray-400">-</span>' ; ?>
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">
                                  <?php echo $row['rating'] ? ' <i class="fa-solid fa-star text-yellow-500 mr-1"></i>' .htmlspecialchars($row['rating']): '<span class="text-gray-400">-</span>' ; ?>
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">
                                <?php echo $row['feedback'] ? '<i class="fa-solid fa-comment-dots text-blue-500 mr-1"></i>' . htmlspecialchars($row['feedback']): '<span class="text-gray-400">-</span>' ; ?>
                            </td>

                        <td class="px-5 py-4 text-sm text-center">
                            <div class="flex items-center justify-center gap-2">

                        <button onclick='viewPerformDetail(<?php echo json_encode($row); ?>)' class="px-3 py-1.5 bg-green-600 text-white rounded-md text-xs font-semibold hover:bg-green-700 transition-all inline-flex items-center gap-1.5">
                                <i class="fas fa-eye"></i> View Feedback
                            </button>
                            
                            <button onclick='ratePerformDetail(<?php echo json_encode($row); ?>)' class="px-3 py-1.5 bg-yellow-500 text-white rounded-md text-xs font-semibold hover:bg-yellow-700 transition-all inline-flex items-center gap-1.5">
                                <i class="fa-solid fa-star"></i> Rate Driver
                            </button>
                        </div>
                    </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</main>

<!-- VIEW FEEDBACK MODAL -->
<div id="viewFeedbackModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto shadow-2xl relative">
    <div class="bg-primary-green text-white px-6 py-4 flex justify-between items-center rounded-t-lg">
      <h3 class="text-xl font-bold">Driver Feedback</h3>
      <button onclick="closeModal('viewFeedbackModal')" class="text-white text-2xl hover:text-gray-200">&times;</button>
    </div>

    <div class="p-6 space-y-6">
      <div class="space-y-3">
        <p><span class="text-gray-600 font-semibold">Driver:</span> <span id="fbDriver" class="font-bold"></span></p>
        <p><span class="text-gray-600 font-semibold">Rating:</span> <span id="fbRating" class="font-bold"></span></p>
        <p><span class="text-gray-600 font-semibold">Feedback Count:</span> <span id="fbFeedback" class="font-bold"></span></p>
      </div>

      <hr>

      <div>
        <h4 class="text-md font-semibold text-gray-700 mb-4">Recent Reviews</h4>
        <div id="recentReviewsBox" class="space-y-4"></div>
        <button id="seeMoreBtn" onclick="loadMoreReviews()" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm hover:bg-gray-200">See More Feedback</button>
      </div>
    </div>
  </div>
</div>

<!-- RATE DRIVER MODAL -->
<div id="rateDriverModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-lg w-full max-w-xl shadow-2xl relative">
    <div class="bg-yellow-500 text-white px-6 py-4 flex justify-between items-center rounded-t-lg">
      <h3 class="text-xl font-bold">Rate Driver</h3>
      <button onclick="closeModal('rateDriverModal')" class="text-white text-2xl hover:text-gray-200">&times;</button>
    </div>
    <div class="p-6 space-y-4">
      <div>
        <label class="font-semibold text-gray-700">Driver Name:</label>
        <p id="rateDriverName" class="font-bold"></p>
      </div>
      <div class="flex gap-1 mb-4 text-2xl cursor-pointer" id="starContainer">
        <i class="fa-solid fa-star text-gray-300" data-star="1"></i>
        <i class="fa-solid fa-star text-gray-300" data-star="2"></i>
        <i class="fa-solid fa-star text-gray-300" data-star="3"></i>
        <i class="fa-solid fa-star text-gray-300" data-star="4"></i>
        <i class="fa-solid fa-star text-gray-300" data-star="5"></i>
      </div>
      <div>
        <label class="font-semibold text-gray-700">Feedback:</label>
        <textarea id="rateDriverFeedback" class="border rounded px-3 py-2 w-full h-24"></textarea>
      </div>
      <button onclick="submitRating()" class="w-full py-2 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700">Submit Rating</button>
    </div>
  </div>
</div>

<script>
const allReviews = <?php echo json_encode($driverReviews); ?>;
let currentDriver = "";
let reviewPage = 1;
const reviewsPerPage = 2;

function filterTable() {
  const search = document.getElementById("searchInput").value.toLowerCase();
  const driverSel = document.getElementById("driverFilter").value.toLowerCase();
  document.querySelectorAll("#tableBody tr").forEach(row => {
    const driver = row.cells[1].textContent.toLowerCase();
    const trips = row.cells[2].textContent.toLowerCase();
    const rating = row.cells[3].textContent.toLowerCase();
    const feedback = row.cells[4].textContent.toLowerCase();
    const searchMatch = !search || driver.includes(search) || trips.includes(search) || rating.includes(search) || feedback.includes(search);
    const driverMatch = !driverSel || driver.includes(driverSel);
    row.style.display = (searchMatch && driverMatch) ? "" : "none";
  });
}

function openModal(id){ document.getElementById(id).classList.remove("hidden"); }
function closeModal(id){ document.getElementById(id).classList.add("hidden"); }

function viewPerformDetail(data){
  document.getElementById("fbDriver").textContent = data.driver;
  document.getElementById("fbRating").textContent = data.rating;
  document.getElementById("fbFeedback").textContent = data.feedback;
  currentDriver = data.driver;
  reviewPage = 1;
  loadRecentReviews();
  openModal('viewFeedbackModal');
}

function loadRecentReviews(){
  const container = document.getElementById("recentReviewsBox");
  container.innerHTML = "";
  if(!allReviews[currentDriver] || allReviews[currentDriver].length === 0){
    container.innerHTML = `<p class="text-gray-500 text-sm">No additional reviews.</p>`;
    document.getElementById("seeMoreBtn").style.display = "none";
    return;
  }
  const limit = reviewPage * reviewsPerPage;
  const reviewsToShow = allReviews[currentDriver].slice(0, limit);
  reviewsToShow.forEach(r => {
    container.innerHTML += `
      <div class="border border-gray-200 rounded-lg p-4">
        <div class="flex items-center justify-between mb-2">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-bold">${r.initials}</div>
            <div>
              <div class="font-semibold text-sm text-gray-900">${r.name}</div>
              <div class="text-xs text-gray-500">${r.date}</div>
            </div>
          </div>
          <div class="text-yellow-500">${"⭐".repeat(r.rating)}</div>
        </div>
        <p class="text-sm text-gray-700">"${r.message}"</p>
      </div>`;
  });
  document.getElementById("seeMoreBtn").style.display = allReviews[currentDriver].length > limit ? "block" : "none";
}

function loadMoreReviews(){ reviewPage++; loadRecentReviews(); }

function ratePerformDetail(data){
  document.getElementById("rateDriverName").textContent = data.driver;
  openModal('rateDriverModal');
}

// Star Rating
let selectedStars = 0;
document.querySelectorAll("#starContainer i").forEach(star => {
  star.addEventListener("click", function(){
    selectedStars = this.dataset.star;
    document.querySelectorAll("#starContainer i").forEach(s=>{
      s.classList.remove("text-yellow-400");
      s.classList.add("text-gray-300");
    });
    for(let i=1;i<=selectedStars;i++){
      document.querySelector(`#starContainer i[data-star='${i}']`).classList.replace("text-gray-300","text-yellow-400");
    }
  });
});

function submitRating(){ alert("Rating submitted: "+selectedStars+" stars"); closeModal('rateDriverModal'); }
</script>
</body>
</html>
