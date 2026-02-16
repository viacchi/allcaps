<?php
include '../includes/functions.php';

function getAllRequisitionsFromAPI() {
    $url = "https://log1.microfinancial-1.com/api/v1/psm/external/requisitions?key=63cfb7730dcc34299fa38cb1a620f701";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['data'] ?? $result ?? [];
}

// Fetch API requisitions once
$apiRequisitions = getAllRequisitionsFromAPI();

$apiProducts = [];

/* =========================================
   LOAD PRODUCTS FROM LOGISTICS 1 API
========================================= */
if (isset($_POST['load_products'])) {
    $url = "https://log1.microfinancial-1.com/api/v1/psm/external/products?key=63cfb7730dcc34299fa38cb1a620f701";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    $allProducts = $result['data'] ?? $result;

    // Filter only automotive products
    $apiProducts = array_filter($allProducts, function($product) {
        return strtolower($product['prod_type']) === 'automotive';
    });
}

/* =========================================
   REQUEST PRODUCT → INSERT TO REQUISITION
========================================= */
if (isset($_POST['submit_request'])) {
    $currentYear = date("Y");
    $last = $conn->query("SELECT req_id FROM purchase_requisitions WHERE req_id LIKE 'PR-$currentYear-%' ORDER BY id DESC LIMIT 1");
    $lastRow = $last->fetch_assoc();

    if ($lastRow) {
        $lastNumber = (int)substr($lastRow['req_id'], 8);
        $newNumber = str_pad($lastNumber + 1, 4, "0", STR_PAD_LEFT);
    } else {
        $newNumber = "0001";
    }

    $req_id = "PR-$currentYear-$newNumber";

    $stmt = $conn->prepare("
        INSERT INTO purchase_requisitions
        (req_id, requester, department, request_date, product_id, product_name, quantity, unit_price, status)
        VALUES (?, ?, ?, CURDATE(), ?, ?, ?, ?, 'Pending')
    ");

    $requester = $_SESSION['user_name'] ?? 'Admin';
    $department = $_SESSION['department'] ?? 'Log2 Dept';
    $quantity = $_POST['quantity'] ?? 1;

    $stmt->bind_param(
        "sssssid",
        $req_id,
        $requester,
        $department,
        $_POST['prod_id'],
        $_POST['prod_name'],
        $quantity,
        $_POST['prod_price']
    );

    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Requisitions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function openRequestModal(prod) {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('prod_id').value = prod.prod_id;
            document.getElementById('prod_name').value = prod.prod_name;
            document.getElementById('prod_price').value = prod.prod_price;
            document.getElementById('modalProdName').innerText = prod.prod_name;
            document.getElementById('modalProdDesc').innerText = prod.prod_desc;
            document.getElementById('modalProdPrice').innerText = "₱" + Number(prod.prod_price).toLocaleString();
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">

<?php include '../includes/sidebar.php'; ?>
<?php include '../includes/header.php'; ?>

<div class="ml-0 md:ml-[280px] p-6 min-h-screen">
    <h1 class="text-2xl font-bold mb-6">Purchase Requisitions</h1>

    <!-- LOAD PRODUCTS BUTTON -->
    <form method="POST" class="mb-6">
        <button type="submit" name="load_products"
            class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700">
            Load Products from Logistics 1
        </button>
    </form>

    <!-- SHOW API PRODUCTS -->
    <?php if (!empty($apiProducts)): ?>
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-bold mb-4">Available Products from Logistics 1</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Product ID</th>
                    <th class="text-left py-2">Name</th>
                    <th class="text-left py-2">Price</th>
                    <th class="text-left py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($apiProducts as $product): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2"><?= $product['prod_id'] ?></td>
                    <td class="py-2"><?= $product['prod_name'] ?></td>
                    <td class="py-2">₱<?= number_format($product['prod_price'],2) ?></td>
                    <td class="py-2">
                        <button type="button"
                            onclick='openRequestModal(<?= json_encode($product) ?>)'
                            class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700">
                            Request
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- MODAL FOR REQUEST -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">
            <h2 class="text-lg font-bold mb-2">Request Product</h2>
            <p class="text-sm text-gray-700 mb-2"><strong>Name:</strong> <span id="modalProdName"></span></p>
            <p class="text-sm text-gray-700 mb-2"><strong>Description:</strong> <span id="modalProdDesc"></span></p>
            <p class="text-sm text-gray-700 mb-4"><strong>Price:</strong> <span id="modalProdPrice"></span></p>

            <form method="POST">
                <input type="hidden" id="prod_id" name="prod_id">
                <input type="hidden" id="prod_name" name="prod_name">
                <input type="hidden" id="prod_price" name="prod_price">

                <label class="block mb-2 text-sm">Quantity</label>
                <input type="number" name="quantity" value="1" min="1" class="w-full px-2 py-1 border rounded mb-4">

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit" name="submit_request"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EXISTING REQUISITIONS -->
    <?php
    $result = $conn->query("SELECT * FROM purchase_requisitions ORDER BY created_at DESC");
    ?>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4">My Requisitions</h2>

        <table class="w-full text-sm">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">Req ID</th>
                    <th class="text-left py-2">Product</th>
                    <th class="text-left py-2">Qty</th>
                    <th class="text-left py-2">Unit Price</th>
                    <th class="text-left py-2">Total</th>
                    <th class="text-left py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2"><?= $row['req_id'] ?></td>
                    <td class="py-2"><?= $row['product_name'] ?></td>
                    <td class="py-2"><?= $row['quantity'] ?></td>
                    <td class="py-2">₱<?= number_format($row['unit_price'],2) ?></td>
                    <td class="py-2 font-semibold">
                        ₱<?= number_format($row['unit_price'] * $row['quantity'],2) ?>
                    </td>
                    <td class="py-2">
                        <?php
                        $liveStatus = $row['status']; // Default to local DB status

                        // Only override for API requisitions (EXTPR-XXXX)
                        if (strpos($row['req_id'], 'EXTPR') === 0) {
                            foreach ($apiRequisitions as $apiReq) {
                                if ($apiReq['req_id'] === $row['req_id']) {
                                    $liveStatus = $apiReq['req_status'] ?? $liveStatus;
                                    break;
                                }
                            }
                        }

                        $statusColor = match ($liveStatus) {
                            'Pending' => 'bg-yellow-100 text-yellow-800',
                            'Approved' => 'bg-blue-100 text-blue-800',
                            'Rejected' => 'bg-red-100 text-red-800',
                            'Completed' => 'bg-green-100 text-green-800',
                            default => 'bg-gray-200 text-gray-700'
                        };
                        ?>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $statusColor ?>">
                            <?= $liveStatus ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
