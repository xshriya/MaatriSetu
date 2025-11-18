<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaatriSetu – Schemes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* CSS for the eligibility checker effects */
        .scheme-card {
            transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .scheme-card.highlight {
            transform: scale(1.03);
            box-shadow: 0 0 15px rgba(219, 39, 119, 0.4);
            border-color: #db2777; /* pink-600 */
        }
        .scheme-card.faded {
            opacity: 0.3;
            transform: scale(0.98);
        }
    </style>
</head>
<body class="bg-pink-50 text-gray-800">

    <header class="bg-white shadow py-4 mb-6">
        <div class="container mx-auto flex items-center justify-between px-4">
            <div class="flex items-center gap-2">
                <img src="logo.jpeg" alt="MaatriSetu Logo" class="h-10 w-auto">
                <span class="text-xl font-bold text-pink-600">MaatriSetu</span>
            </div>
            <nav>
                <ul class="flex gap-6 text-base font-medium">
                    <li><a href="index.php" class="hover:text-pink-600">Home</a></li>
                    <li><a href="community.php" class="hover:text-pink-600">Community</a></li>
                    <li><a href="guidance.php" class="hover:text-pink-600">Guidance</a></li>
                    <li><a href="schemes.php" class="text-pink-600 font-semibold">Schemes</a></li>
                    <li><a href="reminders.php" class="hover:text-pink-600">Reminders</a></li>
                    <li><a href="f-a-q.html" class="hover:text-pink-600">FAQ</a></li>
                    <?php if (isset($_SESSION['user_name'])): ?>
                        <li class="relative">
                            <button id="userMenuButton" class="w-10 h-10 rounded-full bg-pink-600 text-white flex items-center justify-center focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </button>
                            <div id="userMenuDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Settings</a>
                                <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Logout</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="login.html" class="hover:text-pink-600">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-pink-600 text-center mb-4">Government Schemes for Mothers</h2>
        <p class="text-center text-gray-600 mb-8">Answer a few questions to see which schemes you might be eligible for.</p>

        <div class="bg-white max-w-xl mx-auto p-6 rounded-lg shadow-md mb-10 border border-pink-200">
            <h3 class="text-xl font-semibold text-center text-gray-800 mb-4">Quick Eligibility Check</h3>
            <form id="eligibility-form" class="space-y-4">
                <div>
                    <label class="font-medium text-gray-700">Is this your first child?</label>
                    <div class="flex gap-4 mt-1">
                        <label><input type="radio" name="firstChild" value="yes" class="mr-1"> Yes</label>
                        <label><input type="radio" name="firstChild" value="no" class="mr-1"> No</label>
                    </div>
                </div>
                <div>
                    <label class="font-medium text-gray-700">Do you belong to a Below Poverty Line (BPL) household?</label>
                    <div class="flex gap-4 mt-1">
                        <label><input type="radio" name="bpl" value="yes" class="mr-1"> Yes</label>
                        <label><input type="radio" name="bpl" value="no" class="mr-1"> No</label>
                    </div>
                </div>
                <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded-lg hover:bg-pink-700 font-semibold">
                    Show My Schemes
                </button>
            </form>
        </div>
        <div id="results-container" class="hidden max-w-xl mx-auto p-4 mb-8 rounded-lg bg-pink-100 border border-pink-200 flex flex-col items-center">
        <div id="results-content" class="w-full text-center"></div>
        
        <button id="reset-btn" class="mt-4 px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 text-sm font-semibold self-end">
            Start Over
        </button>
    </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="scheme-card bg-white shadow rounded-lg p-6 border border-pink-100" data-bpl="true">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Janani Suraksha Yojana (JSY)</h3>
                <p><strong>Eligibility:</strong> Pregnant women from BPL households.</p>
                <p><strong>Benefits:</strong> Cash assistance for institutional delivery.</p>
                <a href="https://nhm.gov.in/index1.php?lang=1&level=3&sublinkid=841&lid=309" target="_blank" class="inline-block mt-3 text-pink-600 hover:underline font-medium">Learn More →</a>
            </div>

            <div class="scheme-card bg-white shadow rounded-lg p-6 border border-pink-100" data-first-child="true">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Pradhan Mantri Matru Vandana Yojana (PMMVY)</h3>
                <p><strong>Eligibility:</strong> Pregnant & lactating mothers (first child).</p>
                <p><strong>Benefits:</strong> ₹5,000 financial incentive in installments.</p>
                <a href="https://wcd.nic.in/schemes/pradhan-mantri-matru-vandana-yojana" target="_blank" class="inline-block mt-3 text-pink-600 hover:underline font-medium">Learn More →</a>
            </div>

            <div class="scheme-card bg-white shadow rounded-lg p-6 border border-pink-100">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Kilkari</h3>
                <p><strong>Eligibility:</strong> All pregnant women and new mothers.</p>
                <p><strong>Benefits:</strong> Free weekly voice messages about pregnancy and childcare.</p>
                <a href="https://pib.gov.in/PressReleasePage.aspx?PRID=2003571" target="_blank" class="inline-block mt-3 text-pink-600 hover:underline font-medium">Learn More →</a>
            </div>

            <div class="scheme-card bg-white shadow rounded-lg p-6 border border-pink-100">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Surakshit Matritva Aashwasan (SUMAN)</h3>
                <p><strong>Eligibility:</strong> All pregnant women and mothers up to 6 months post-delivery.</p>
                <p><strong>Benefits:</strong> Assured, respectful, and no-cost care.</p>
                <a href="https://suman.mohfw.gov.in/" target="_blank" class="inline-block mt-3 text-pink-600 hover:underline font-medium">Learn More →</a>
            </div>
        </div>
    </main>

    <footer class="bg-white text-center py-6 mt-12 border-t border-gray-200">
        <p class="text-gray-700">© 2025 MaatriSetu. All Rights Reserved.</p>
    </footer>

    <script src="schemes.js"></script>
    <script>
        // User menu dropdown functionality
        const userMenuButton = document.getElementById('userMenuButton');
        const userMenuDropdown = document.getElementById('userMenuDropdown');

        if (userMenuButton) {
            userMenuButton.addEventListener('click', () => {
                userMenuDropdown.classList.toggle('hidden');
            });
            window.addEventListener('click', (e) => {
                if (!userMenuButton.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                    userMenuDropdown.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>