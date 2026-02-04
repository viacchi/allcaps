<?php
include 'driver-includes/driver-functions.php';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Dashboard - Logistics Driver</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        />
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
        <?php include 'driver-includes/driver-sidebar.php'; ?>

        <!-- Main Content -->
        <div class="ml-0 md:ml-[280px] min-h-screen">
            <!-- Header -->
            <?php include 'driver-includes/driver-header.php'; ?>

            <!-- Page Content -->
            <main class="p-6">
                <!-- Page Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Dashboard</h2>
                    <p class="text-gray-600 mt-2">
                        Welcome to your logistics management system
                    </p>
                </div>