<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.html?redirect=reminders.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Reminders - MaatriSetu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pink: {
                            600: '#ec4899',
                            700: '#be185d'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-pink-100 text-gray-800">

    <!-- Header -->
    <header class="bg-white shadow py-4 mb-6">
        <div class="container mx-auto flex items-center justify-between px-4">
            <div class="flex items-center gap-2">
                <img src="logo.jpeg" alt="MaatriSetu Logo" class="h-10 w-auto" />
                <span class="text-xl font-bold text-pink-600">MaatriSetu</span>
            </div>
            <nav>
                <ul class="flex gap-6 text-base font-medium">
                    <li><a href="index.php" class="hover:text-pink-600">Home</a></li>
                    <li><a href="community.php" class="hover:text-pink-600">Community</a></li>
                    <li><a href="guidance.php" class="hover:text-pink-600">Guidance</a></li>
                    <li><a href="schemes.php" class="hover:text-pink-600">Schemes</a></li>
                    <li><a href="reminders.php" class="text-pink-600 font-semibold">Reminders</a></li>
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

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-6 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Health Reminders</h1>
            <p class="text-gray-600">Stay on top of your health with personalized reminders and nearby healthcare facilities.</p>
        </div>

        <!-- Success/Error Messages -->
        <div id="messageContainer" class="hidden mb-6">
            <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <span id="successText"></span>
            </div>
            <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <span id="errorText"></span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Create New Reminder -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Create New Reminder</h2>
                    
                    <form id="reminderForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Reminder Type</label>
                            <select id="reminderType" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                                <option value="">Select reminder type</option>
                                <option value="checkup">Health Checkup</option>
                                <option value="medication">Medication</option>
                                <option value="vaccination">Vaccination</option>
                                <option value="prenatal">Prenatal Appointment</option>
                                <option value="postnatal">Postnatal Checkup</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                                <input type="date" id="reminderDate" required 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Time</label>
                                <input type="time" id="reminderTime" required 
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="reminderDescription" rows="3" placeholder="Add details about your appointment or reminder..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500"></textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="notifyRelative" class="mr-2">
                            <label for="notifyRelative" class="text-sm text-gray-700">
                                Also notify relative (Emergency Contact)
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-pink-600 text-white py-2 px-4 rounded-lg hover:bg-pink-700 transition duration-200">
                            Create Reminder
                        </button>
                    </form>
                </div>

                <!-- Your Reminders -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Your Upcoming Reminders</h2>
                    
                    <div id="remindersList" class="space-y-4">
                        <!-- Sample reminders will be populated here -->
                        <div class="text-center py-8" id="noReminders">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500">No reminders set yet. Create your first reminder above!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Nearby Healthcare Facilities -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Nearby Healthcare</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Based on your registered address
                    </p>
                    
                    <div id="healthcareFacilities" class="space-y-3">
                        <div class="text-center py-4">
                            <button onclick="findNearbyFacilities()" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 text-sm">
                                Find Nearby Facilities
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <button onclick="setQuickReminder('checkup')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-pink-50 rounded flex items-center space-x-2">
                            <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Set Monthly Checkup</span>
                        </button>
                        <button onclick="setQuickReminder('medication')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-pink-50 rounded flex items-center space-x-2">
                            <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                            <span>Daily Medication</span>
                        </button>
                        <button onclick="setQuickReminder('vaccination')" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-pink-50 rounded flex items-center space-x-2">
                            <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.871 4A17.926 17.926 0 003 12c0 2.874.673 5.59 1.871 8m14.13 0a17.926 17.926 0 001.87-8c0-2.874-.673-5.59-1.87-8M9 9h1.246a1 1 0 01.961.725l1.586 5.55a1 1 0 00.961.725H15m1-7h-.08a2 2 0 00-1.519.698L9.6 15.302A2 2 0 018.08 16H8"></path>
                            </svg>
                            <span>Vaccination Schedule</span>
                        </button>
                    </div>
                </div>

                <!-- Emergency Contacts -->
                <div class="bg-pink-50 rounded-lg p-4">
                    <h4 class="font-semibold text-pink-800 mb-2">Emergency Contacts</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-pink-700">Ambulance:</span>
                            <span class="font-medium text-pink-800">108</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-pink-700">Women Helpline:</span>
                            <span class="font-medium text-pink-800">1091</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-pink-700">Health Helpline:</span>
                            <span class="font-medium text-pink-800">104</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Reminders data
        let reminders = [];
        let reminderTimers = [];
        let messageTimeout = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('reminderDate').min = today;
            
            requestNotificationPermission();

            // Load reminders from database
            loadReminders();
        });

        // Load reminders from database
        function loadReminders() {
            fetch('get_reminders.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        reminders = data.reminders;
                        displayReminders();
                        scheduleReminderNotifications();
                    } else {
                        showMessage('error', data.message || 'Failed to load reminders');
                    }
                })
                .catch(error => {
                    console.error('Error loading reminders:', error);
                    showMessage('error', 'Failed to load reminders');
                });
        }

        // Handle form submission
        document.getElementById('reminderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const type = document.getElementById('reminderType').value;
            const date = document.getElementById('reminderDate').value;
            const time = document.getElementById('reminderTime').value;
            const description = document.getElementById('reminderDescription').value;
            const notifyRelative = document.getElementById('notifyRelative').checked;
            
            // Send to backend
            fetch('create_reminder.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    type: type,
                    date: date,
                    time: time,
                    description: description,
                    notifyRelative: notifyRelative
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data && data.success) {
                    // Add new reminder to list
                    reminders.push(data.reminder);
                    displayReminders();
                    scheduleReminderNotifications();
                    
                    // Reset form
                    document.getElementById('reminderForm').reset();
                    
                    // Show success message
                    showMessage('success', 'Reminder created successfully!');
                } else {
                    showMessage('error', (data && data.message) || 'Failed to create reminder');
                }
            })
            .catch(error => {
                console.error('Error creating reminder:', error);
                showMessage('error', 'Failed to create reminder: ' + error.message);
                // Reload reminders to check if it was actually created
                setTimeout(() => {
                    loadReminders();
                }, 1000);
            });
        });

        // Display reminders
        function displayReminders() {
            const container = document.getElementById('remindersList');
            const noReminders = document.getElementById('noReminders');
            
            if (reminders.length === 0) {
                noReminders.style.display = 'block';
                container.innerHTML = '';
                return;
            }
            
            noReminders.style.display = 'none';
            
            // Sort reminders by date and time
            reminders.sort((a, b) => {
                const dateA = new Date(a.date + ' ' + a.time);
                const dateB = new Date(b.date + ' ' + b.time);
                return dateA - dateB;
            });
            
            container.innerHTML = reminders.map(reminder => `
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50" data-reminder-id="${reminder.id}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="inline-block w-3 h-3 bg-pink-600 rounded-full"></span>
                                <h3 class="font-medium text-gray-800 capitalize">
                                    ${getReminderTypeLabel(reminder.type)}
                                </h3>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">
                                ${formatDate(reminder.date)} at ${formatTime(reminder.time)}
                            </p>
                            ${reminder.description ? `<p class="text-gray-700 text-sm mb-2">${reminder.description}</p>` : ''}
                            ${reminder.notifyRelative ? '<p class="text-pink-600 text-xs">Relative will be notified</p>' : ''}
                        </div>
                        <button onclick="deleteReminder(${reminder.id})" class="text-gray-400 hover:text-red-600 ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Delete reminder
        function deleteReminder(id) {
            if (confirm('Are you sure you want to delete this reminder?')) {
                fetch('delete_reminder.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove from local array
                        reminders = reminders.filter(reminder => reminder.id !== id);
                        displayReminders();
                        scheduleReminderNotifications();
                        showMessage('success', 'Reminder deleted successfully!');
                    } else {
                        showMessage('error', data.message || 'Failed to delete reminder');
                    }
                })
                .catch(error => {
                    console.error('Error deleting reminder:', error);
                    showMessage('error', 'Failed to delete reminder');
                });
            }
        }

        // Find nearby healthcare facilities using real API
function findNearbyFacilities() {
    const container = document.getElementById('healthcareFacilities');
    container.innerHTML = '<div class="text-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-pink-600 mx-auto"></div><p class="text-sm text-gray-600 mt-2">Searching nearby hospitals...</p></div>';
    
    // Call the PHP backend
    fetch('hospital_search.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.hospitals) {
            displayHospitals(data.hospitals, data.search_address);
        } else {
            showError('Failed to load nearby hospitals: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Network error while searching for hospitals');
    });
}

// Store all hospitals for view more functionality
let allHospitals = [];
let showingAll = false;

// Display hospitals in the UI
function displayHospitals(hospitals, searchAddress) {
    const container = document.getElementById('healthcareFacilities');
    allHospitals = hospitals;
    showingAll = false;
    
    // Show only first 5 hospitals initially
    const hospitalsToShow = hospitals.slice(0, 5);
    const hasMore = hospitals.length > 5;
    
    let html = `<div class="mb-3 text-xs text-gray-600">Found ${hospitals.length} facilities near: ${searchAddress}</div><div class="space-y-3" id="hospitalsList">`;
    
    hospitalsToShow.forEach((hospital, index) => {
        const typeColor = hospital.type === 'Government' ? 'bg-green-100 text-green-700' : 'bg-pink-100 text-pink-700';
        
        html += `
            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-all duration-200">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-medium text-gray-800 text-sm">${hospital.name}</h4>
                    <span class="text-xs px-2 py-1 ${typeColor} rounded">${hospital.type}</span>
                </div>
                <p class="text-xs text-gray-600 mb-1">${hospital.address}</p>
                <p class="text-xs text-gray-600 mb-1">Distance: ${hospital.distance}</p>
                ${hospital.phone !== 'Not available' && hospital.phone !== 'Contact via Google' ? 
                    `<p class="text-xs text-pink-600 mb-1">${hospital.phone}</p>` : 
                    `<p class="text-xs text-blue-600 mb-1"><a href="${hospital.google_maps_url}" target="_blank" class="hover:underline">Contact via Google</a></p>`
                }
                <p class="text-xs text-gray-500 mb-2">${hospital.services}</p>
            </div>
        `;
    });
    
    html += '</div>';
    
    // Add "View More" button if there are more than 5 hospitals
    if (hasMore) {
        html += `
            <div class="text-center mt-3">
                <button onclick="toggleViewMore()" id="viewMoreBtn" 
                        class="text-xs text-pink-600 hover:text-pink-700 hover:underline">
                    View ${hospitals.length - 5} more facilities ↓
                </button>
            </div>
        `;
    }
    
    container.innerHTML = html;
}

// Toggle view more/less functionality
function toggleViewMore() {
    const container = document.getElementById('hospitalsList');
    const viewMoreBtn = document.getElementById('viewMoreBtn');
    
    if (!showingAll) {
        // Show all hospitals
        showingAll = true;
        let html = '';
        
        allHospitals.forEach((hospital, index) => {
            const typeColor = hospital.type === 'Government' ? 'bg-green-100 text-green-700' : 'bg-pink-100 text-pink-700';
            
            html += `
                <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-all duration-200">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-medium text-gray-800 text-sm">${hospital.name}</h4>
                        <span class="text-xs px-2 py-1 ${typeColor} rounded">${hospital.type}</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-1">${hospital.address}</p>
                    <p class="text-xs text-gray-600 mb-1">Distance: ${hospital.distance}</p>
                    ${hospital.phone !== 'Not available' && hospital.phone !== 'Contact via Google' ? 
                        `<p class="text-xs text-pink-600 mb-1">${hospital.phone}</p>` : 
                        `<p class="text-xs text-blue-600 mb-1"><a href="${hospital.google_maps_url}" target="_blank" class="hover:underline">Contact via Google</a></p>`
                    }
                    <p class="text-xs text-gray-500 mb-2">${hospital.services}</p>
                </div>
            `;
        });
        
        container.innerHTML = html;
        viewMoreBtn.innerHTML = 'View less ↑';
    } else {
        // Show only first 5
        showingAll = false;
        displayHospitals(allHospitals, 'your address');
    }
}


// Show error message
function showError(message) {
    const container = document.getElementById('healthcareFacilities');
    container.innerHTML = `
        <div class="text-center py-4">
            <svg class="w-12 h-12 text-red-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-600 text-sm">${message}</p>
            <button onclick="findNearbyFacilities()" class="mt-2 text-pink-600 text-sm hover:underline">Try Again</button>
        </div>
    `;
}

        function clearScheduledNotifications() {
            reminderTimers.forEach(timerId => clearTimeout(timerId));
            reminderTimers = [];
        }

        function scheduleReminderNotifications() {
            clearScheduledNotifications();

            const now = new Date();
            reminders.forEach(reminder => {
                const reminderDateTime = new Date(`${reminder.date}T${reminder.time}:00`);
                const timeDifference = reminderDateTime.getTime() - now.getTime();

                if (timeDifference > 0) {
                    const timerId = setTimeout(() => {
                        triggerReminderNotification(reminder);
                    }, timeDifference);
                    reminderTimers.push(timerId);
                }
            });
        }

        function requestNotificationPermission() {
            if (!('Notification' in window)) {
                console.warn('Browser notifications are not supported.');
                return;
            }

            if (Notification.permission === 'default') {
                Notification.requestPermission().then(permission => {
                    if (permission !== 'granted') {
                        console.warn('Notification permission denied by user.');
                    }
                });
            }
        }

        function triggerReminderNotification(reminder) {
            const title = `Reminder: ${getReminderTypeLabel(reminder.type)}`;
            const body = `${formatDate(reminder.date)} at ${formatTime(reminder.time)}${reminder.description ? ' - ' + reminder.description : ''}`;

            if ('Notification' in window && Notification.permission === 'granted') {
                const notification = new Notification(title, {
                    body: body,
                    icon: 'logo.jpeg',
                    tag: `reminder-${reminder.id}`
                });

                notification.onclick = () => {
                    window.focus();
                };
            } else {
                showMessage('success', body);
            }
        }

        // Quick reminder setup
        function setQuickReminder(type) {
            document.getElementById('reminderType').value = type;
            
            // Set default date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('reminderDate').value = tomorrow.toISOString().split('T')[0];
            
            // Set default time based on type
            let defaultTime = '09:00';
            if (type === 'medication') defaultTime = '08:00';
            if (type === 'checkup') defaultTime = '10:00';
            
            document.getElementById('reminderTime').value = defaultTime;
            
            // Scroll to form
            document.getElementById('reminderForm').scrollIntoView({ behavior: 'smooth' });
        }

        // Utility functions
        function getReminderTypeLabel(type) {
            const labels = {
                'checkup': 'Health Checkup',
                'medication': 'Medication',
                'vaccination': 'Vaccination',
                'prenatal': 'Prenatal Appointment',
                'postnatal': 'Postnatal Checkup',
                'other': 'Other'
            };
            return labels[type] || type;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }

        function formatTime(timeString) {
            const [hours, minutes] = timeString.split(':');
            const date = new Date();
            date.setHours(parseInt(hours), parseInt(minutes));
            return date.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
        }

        function showMessage(type, text) {
            const messageContainer = document.getElementById('messageContainer');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            
            // Clear any existing timeout
            if (messageTimeout) {
                clearTimeout(messageTimeout);
            }
            
            messageContainer.classList.remove('hidden');
            
            if (type === 'success') {
                successMessage.classList.remove('hidden');
                errorMessage.classList.add('hidden');
                document.getElementById('successText').textContent = text;
            } else {
                errorMessage.classList.remove('hidden');
                successMessage.classList.add('hidden');
                document.getElementById('errorText').textContent = text;
            }
            
            // Hide message after 5 seconds
            messageTimeout = setTimeout(() => {
                messageContainer.classList.add('hidden');
            }, 5000);
        }

        // Dropdown menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('userMenuButton');
            const userMenuDropdown = document.getElementById('userMenuDropdown');
            
            if (userMenuButton && userMenuDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userMenuDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenuButton.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                        userMenuDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
