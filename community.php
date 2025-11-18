<?php
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_name']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : null;
$userAvatar = 'https://cdn-icons-png.flaticon.com/512/4140/4140047.png'; // Default avatar

// Fetch user profile data if logged in
if ($isLoggedIn && isset($_SESSION['user_id'])) {
    require_once 'database.php';
    try {
        $conn = getDatabaseConnection();
    } catch (Exception $e) {
        $conn = null;
    }
    if ($conn) {
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT fullName, profile_character_url FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $userName = $row['fullName'];
            if (!empty($row['profile_character_url'])) {
                $userAvatar = $row['profile_character_url'];
            }
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MaatriSetu Community</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="community.css" />
  <script>
    // Configure Tailwind to match your project colors
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
          <li><a href="community.php" class="text-pink-600 font-semibold">Community</a></li>
          <li><a href="guidance.php" class="hover:text-pink-600">Guidance</a></li>
          <li><a href="schemes.php" class="hover:text-pink-600">Schemes</a></li>
          <li><a href="reminders.php" class="hover:text-pink-600">Reminders</a></li>
          <li><a href="f-a-q.html" class="hover:text-pink-600">FAQ</a></li>
          <?php if ($isLoggedIn): ?>
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

  <!-- Main Layout -->
  <div class="max-w-6xl mx-auto px-6 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      
      <!-- Left Sidebar -->
      <aside class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="h-20 bg-gradient-to-r from-pink-400 to-pink-600"></div>
          <div class="px-6 pb-6">
            <div class="flex justify-center -mt-10">
              <img src="<?php echo htmlspecialchars($userAvatar); ?>" 
                   class="w-20 h-20 rounded-full border-4 border-white" alt="Profile"
                   onerror="this.src='https://cdn-icons-png.flaticon.com/512/4140/4140047.png'">
            </div>
            
            <?php if ($isLoggedIn): ?>
              <div class="text-center mt-4">
                <h4 class="font-semibold text-lg text-gray-800"><?php echo htmlspecialchars($userName); ?></h4>
                <p class="text-gray-600 text-sm">Community Member</p>
                <div class="flex justify-center space-x-6 mt-4 text-sm">
                  <div class="text-center">
                    <p class="font-semibold text-pink-600">142</p>
                    <p class="text-gray-600">Connections</p>
                  </div>
                  <div class="text-center">
                    <p class="font-semibold text-pink-600">3</p>
                    <p class="text-gray-600">Groups</p>
                  </div>
                </div>
                <button onclick="location.href='profile.php'" class="mt-4 bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 text-sm">
                  ⚙️ Settings
                </button>
              </div>
            <?php else: ?>
              <div class="text-center mt-4">
                <div class="w-20 h-20 bg-gray-200 rounded-full mx-auto flex items-center justify-center">
                  <span class="text-2xl text-gray-400">👤</span>
                </div>
                <button onclick="location.href='login.html'" 
                        class="mt-4 bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700">
                  Login to Post
                </button>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <div class="bg-pink-50 rounded-lg p-4 mt-6">
          <h5 class="font-semibold text-pink-800 mb-2">About Community</h5>
          <p class="text-pink-700 text-sm">"Strong. Brave. Beautiful. You are doing amazing, Mama."</p>
        </div>
      </aside>

      <!-- Center Feed -->
      <main class="lg:col-span-2">
        <!-- Search Bar -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
          <div class="relative">
            <input type="text" id="searchInput" placeholder="Search community posts..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" />
            <span class="absolute right-3 top-2.5 text-gray-400">🔍</span>
          </div>
        </div>

        <div class="bg-gradient-to-r from-pink-100 to-purple-100 rounded-lg p-4 mb-6 text-center">
          <p class="text-pink-800 font-medium">"Every heartbeat you feel inside you is a reminder that you are creating life."</p>
        </div>

        <!-- Post Box -->
        <?php if ($isLoggedIn): ?>
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
          <textarea id="postTextarea" placeholder="Share your thoughts, experiences, or questions..." 
                    class="w-full border border-gray-300 rounded-lg p-3 resize-none focus:outline-none focus:ring-2 focus:ring-pink-500" 
                    rows="3"></textarea>
          <div class="flex justify-end items-center mt-4">
            <button id="postBtn" data-user-name="<?php echo htmlspecialchars($userName); ?>" class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700">
              Post
            </button>
          </div>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 text-center">
          <p class="text-gray-600 mb-4">Join our community to share your experiences and connect with other mothers!</p>
          <button onclick="location.href='login.html'" 
                  class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700">
            Login to Post
          </button>
        </div>
        <?php endif; ?>

        <!-- Posts Container -->
        <div id="postsContainer">
          <!-- Posts will be loaded here by JavaScript -->
        </div>
      </main>

      <!-- Right Sidebar -->
      <aside class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
          <h5 class="font-semibold text-gray-800 mb-4">Suggested Groups</h5>
          <div class="space-y-3">
            <div class="bg-pink-50 rounded-lg p-3 flex justify-between items-center">
              <div>
                <p class="font-medium text-gray-800 text-sm">Pregnancy Wellness</p>
                <p class="text-gray-600 text-xs">200+ members</p>
              </div>
              <button class="bg-pink-600 text-white px-3 py-1 rounded-lg hover:bg-pink-700 text-xs">
                Join
              </button>
            </div>
            <div class="bg-pink-50 rounded-lg p-3 flex justify-between items-center">
              <div>
                <p class="font-medium text-gray-800 text-sm">Postpartum Support</p>
                <p class="text-gray-600 text-xs">150+ members</p>
              </div>
              <button class="bg-pink-600 text-white px-3 py-1 rounded-lg hover:bg-pink-700 text-xs">
                Join
              </button>
            </div>
            <div class="bg-pink-50 rounded-lg p-3 flex justify-between items-center">
              <div>
                <p class="font-medium text-gray-800 text-sm">Nutrition & Baby Care</p>
                <p class="text-gray-600 text-xs">180+ members</p>
              </div>
              <button class="bg-pink-600 text-white px-3 py-1 rounded-lg hover:bg-pink-700 text-xs">
                Join
              </button>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
          <h5 class="font-semibold text-gray-800 mb-4">Trending Topics</h5>
          <div class="space-y-2 text-sm">
            <p class="text-pink-600 hover:text-pink-700 cursor-pointer">#PregnancyCare</p>
            <p class="text-pink-600 hover:text-pink-700 cursor-pointer">#PostpartumJourney</p>
            <p class="text-pink-600 hover:text-pink-700 cursor-pointer">#SelfCareForMoms</p>
            <p class="text-pink-600 hover:text-pink-700 cursor-pointer">#BabyNutrition</p>
          </div>
        </div>

        <div class="bg-pink-50 rounded-lg p-6">
          <h5 class="font-semibold text-pink-800 mb-2">Daily Reminder</h5>
          <p class="text-pink-700 text-sm">Take a deep breath. You're doing your best, and that's enough.</p>
        </div>
      </aside>
    </div>
  </div>

 <script>
  // Dropdown functionality
  document.addEventListener('DOMContentLoaded', function() {
    const userMenuButton = document.getElementById('userMenuButton');
    const userMenuDropdown = document.getElementById('userMenuDropdown');
    
    if (userMenuButton && userMenuDropdown) {
      userMenuButton.addEventListener('click', function(e) {
        e.stopPropagation();
        userMenuDropdown.classList.toggle('hidden');
      });
      
      document.addEventListener('click', function(e) {
        if (!userMenuButton.contains(e.target) && !userMenuDropdown.contains(e.target)) {
          userMenuDropdown.classList.add('hidden');
        }
      });
    }
  });

  // Helper function to format time ago
  function getTimeAgo(dateString) {
    const now = new Date();
    const postDate = new Date(dateString);
    const seconds = Math.floor((now - postDate) / 1000);
    
    if (seconds < 60) return 'Just now';
    const minutes = Math.floor(seconds / 60);
    if (minutes < 60) return `${minutes}m ago`;
    const hours = Math.floor(minutes / 60);
    if (hours < 24) return `${hours}h ago`;
    const days = Math.floor(hours / 24);
    return `${days}d ago`;
  }

  // Load posts from database
  let allPosts = [];
  let displayedCount = 0;
  const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
  const currentUserId = <?php echo $isLoggedIn && isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;
  const initialLimit = isLoggedIn ? 2 : 1;
  
  function loadPosts() {
    fetch('get_posts.php')
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          allPosts = data.posts;
          displayedCount = 0;
          const postsContainer = document.getElementById("postsContainer");
          postsContainer.innerHTML = '';
          displayPosts(initialLimit);
          addCTA();
        }
      })
      .catch(error => console.error('Error loading posts:', error));
  }
  
  function displayPosts(count) {
    const postsContainer = document.getElementById("postsContainer");
    const postsToShow = allPosts.slice(displayedCount, displayedCount + count);
    
    postsToShow.forEach(post => {
      addPostToDOM(post);
    });
    
    displayedCount += postsToShow.length;
  }
  
  function addCTA() {
    const postsContainer = document.getElementById("postsContainer");
    const existingCTA = document.getElementById('postsCTA');
    if (existingCTA) existingCTA.remove();
    
    if (!isLoggedIn) {
      // Show login CTA for logged out users
      const loginCTA = document.createElement('div');
      loginCTA.id = 'postsCTA';
      loginCTA.className = 'bg-gradient-to-r from-pink-50 to-purple-50 rounded-lg p-8 text-center border-2 border-pink-200 mb-6';
      loginCTA.innerHTML = `
        <div class="max-w-md mx-auto">
          <svg class="w-16 h-16 mx-auto text-pink-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
          </svg>
          <h3 class="text-2xl font-bold text-gray-800 mb-3">Join Our Community! 💕</h3>
          <p class="text-gray-600 mb-6">Login to view more inspiring stories, helpful tips, and connect with other mothers on their pregnancy journey.</p>
          <button onclick="location.href='login.html'" class="bg-pink-600 text-white px-8 py-3 rounded-lg hover:bg-pink-700 font-semibold shadow-lg transform hover:scale-105 transition-all">
            Login to View More
          </button>
        </div>
      `;
      postsContainer.appendChild(loginCTA);
    } else if (displayedCount < allPosts.length) {
      // Show view more button for logged in users
      const viewMoreBtn = document.createElement('div');
      viewMoreBtn.id = 'postsCTA';
      viewMoreBtn.className = 'text-center mb-6';
      viewMoreBtn.innerHTML = `
        <button id="viewMoreBtn" class="bg-pink-600 text-white px-8 py-3 rounded-lg hover:bg-pink-700 font-semibold shadow-md transition-all">
          View More Posts (${allPosts.length - displayedCount} remaining)
        </button>
      `;
      postsContainer.appendChild(viewMoreBtn);
      
      // Add click handler
      document.getElementById('viewMoreBtn').addEventListener('click', function() {
        displayPosts(5); // Load 5 more posts
        addCTA(); // Update CTA
      });
    }
  }

  // Add post to DOM
  function addPostToDOM(post) {
    const postsContainer = document.getElementById("postsContainer");
    const postDiv = document.createElement("div");
    postDiv.className = "bg-white rounded-lg shadow-md p-6 mb-6";
    postDiv.dataset.postId = post.id;
    
    const timeAgo = getTimeAgo(post.created_at);
    
    // Determine button states based on user reaction
    const likeClass = post.user_reaction === 'like' ? 'text-red-600' : 'text-pink-600';
    const likeFill = post.user_reaction === 'like' ? 'currentColor' : 'none';
    const dislikeClass = post.user_reaction === 'dislike' ? 'text-blue-600' : 'text-gray-600';
    const dislikeFill = post.user_reaction === 'dislike' ? 'currentColor' : 'none';
    const likeCountColor = post.likes >= 0 ? 'text-green-600' : 'text-red-600';
    
    const userAvatar = post.user_avatar || 'https://cdn-icons-png.flaticon.com/512/4140/4140047.png';
    
    const isOwnPost = currentUserId && post.user_id == currentUserId;
    const deleteButton = isOwnPost ? `
      <button class="delete-btn text-red-500 hover:text-red-700 ml-auto" title="Delete post">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
      </button>
    ` : '';

    postDiv.innerHTML = `
      <div class="flex items-center space-x-3 mb-4">
        <img src="${userAvatar}" 
             class="w-12 h-12 rounded-full" alt="user"
             onerror="this.src='https://cdn-icons-png.flaticon.com/512/4140/4140047.png'">
        <div class="flex-1">
          <h5 class="font-semibold text-gray-800">${post.user_name}</h5>
          <small class="text-gray-500">${timeAgo} • Community</small>
        </div>
        ${deleteButton}
      </div>
      <p class="text-gray-700 mb-4">${post.content}</p>
      <div class="flex space-x-4 text-sm items-center">
        <button class="like-btn ${likeClass} hover:text-red-700 flex items-center space-x-1" data-action="like">
          <svg class="w-5 h-5" fill="${likeFill}" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
          </svg>
        </button>
        <span class="like-count font-semibold ${likeCountColor}">${post.likes}</span>
        <button class="dislike-btn ${dislikeClass} hover:text-blue-700 flex items-center space-x-1" data-action="dislike">
          <svg class="w-5 h-5" fill="${dislikeFill}" stroke="currentColor" viewBox="0 0 24 24" style="transform: rotate(180deg)">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
          </svg>
        </button>
        <button class="text-pink-600 hover:text-pink-700 flex items-center space-x-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
          </svg>
          <span>Comment</span>
        </button>
        <button class="text-pink-600 hover:text-pink-700 flex items-center space-x-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
          </svg>
          <span>Share</span>
        </button>
      </div>
    `;
    
    postsContainer.prepend(postDiv);
  }

  // Post functionality for logged-in users
  <?php if ($isLoggedIn): ?>
  const postBtn = document.getElementById("postBtn");
  const textarea = document.getElementById("postTextarea");

  if (postBtn && textarea) {
    postBtn.addEventListener("click", () => {
      const text = textarea.value.trim();
      if (text === "") {
        alert("Please write something before posting 💕");
        return;
      }

      // Send post to backend
      fetch('create_post.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ content: text })
      })
      .then(response => response.text())
      .then(responseText => {
        try {
          const data = JSON.parse(responseText);
          if (data.success) {
            addPostToDOM(data.post);
            textarea.value = "";
          } else {
            alert(data.message || 'Failed to create post');
          }
        } catch (jsonError) {
          alert('Server response error.');
        }
      })
      .catch(error => {
        alert('Network error: ' + error.message);
      });
    });
  }

  // Like/Dislike button functionality
  document.addEventListener('click', function(e) {
    const button = e.target.closest('.like-btn, .dislike-btn');
    if (button) {
      const postDiv = button.closest('[data-post-id]');
      const postId = postDiv ? postDiv.dataset.postId : null;
      const action = button.dataset.action;
      
      if (!postId) return;
      
      fetch('like_post.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ post_id: postId, action: action })
      })
      .then(response => response.text())
      .then(responseText => {
        try {
          const data = JSON.parse(responseText);
          if (data.success) {
            const postDiv = button.closest('[data-post-id]');
            if (!postDiv) return;
            
            const likeBtn = postDiv.querySelector('.like-btn');
            const dislikeBtn = postDiv.querySelector('.dislike-btn');
            const likeCount = postDiv.querySelector('.like-count');
            const likeSvg = likeBtn ? likeBtn.querySelector('svg') : null;
            const dislikeSvg = dislikeBtn ? dislikeBtn.querySelector('svg') : null;
            
            if (!likeCount) return;
            
            // Update count and color
            likeCount.textContent = data.likes;
            likeCount.className = `like-count font-semibold ${data.likes >= 0 ? 'text-green-600' : 'text-red-600'}`;
            
            // Update button states
            if (data.user_reaction === 'like') {
              likeBtn.classList.remove('text-pink-600');
              likeBtn.classList.add('text-red-600');
              likeSvg.setAttribute('fill', 'currentColor');
              
              dislikeBtn.classList.remove('text-blue-600');
              dislikeBtn.classList.add('text-gray-600');
              dislikeSvg.setAttribute('fill', 'none');
            } else if (data.user_reaction === 'dislike') {
              dislikeBtn.classList.remove('text-gray-600');
              dislikeBtn.classList.add('text-blue-600');
              dislikeSvg.setAttribute('fill', 'currentColor');
              
              likeBtn.classList.remove('text-red-600');
              likeBtn.classList.add('text-pink-600');
              likeSvg.setAttribute('fill', 'none');
            } else {
              // No reaction
              likeBtn.classList.remove('text-red-600');
              likeBtn.classList.add('text-pink-600');
              likeSvg.setAttribute('fill', 'none');
              
              dislikeBtn.classList.remove('text-blue-600');
              dislikeBtn.classList.add('text-gray-600');
              dislikeSvg.setAttribute('fill', 'none');
            }
          }
        } catch (jsonError) {
          // Silent fail
        }
      })
      .catch(error => {
        // Silent fail
      });
    }
  });

  // Delete button functionality
  document.addEventListener('click', function(e) {
    const button = e.target.closest('.delete-btn');
    if (button) {
      const postDiv = button.closest('[data-post-id]');
      const postId = postDiv ? postDiv.dataset.postId : null;
      
      if (!postId) return;
      
      if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        return;
      }
      
      fetch('delete_post.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ post_id: postId })
      })
      .then(response => response.text())
      .then(responseText => {
        try {
          const data = JSON.parse(responseText);
          if (data.success) {
            postDiv.style.transition = 'opacity 0.3s ease-out';
            postDiv.style.opacity = '0';
            setTimeout(() => {
              postDiv.remove();
            }, 300);
          } else {
            alert(data.message || 'Failed to delete post');
          }
        } catch (jsonError) {
          alert('Server response error.');
        }
      })
      .catch(error => {
        alert('Network error: ' + error.message);
      });
    }
  });
  <?php endif; ?>

  // Load posts on page load
  loadPosts();

  // Search functionality
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", (e) => {
      const query = e.target.value.toLowerCase();
      const posts = document.querySelectorAll("#postsContainer > div");
      
      posts.forEach(post => {
        const content = post.textContent.toLowerCase();
        post.style.display = content.includes(query) ? "block" : "none";
      });
    });
  }
</script>
</body>
</html>
