<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
  <header class="w-full px-6 py-4 bg-gray-100 dark:bg-gray-800 shadow-md flex justify-between items-center fixed top-0 z-50">
  <!-- Left: Logo & App Name -->
  <div class="flex items-center gap-3">
    <i class="fas fa-book text-2xl text-primary-light dark:text-primary-dark"></i>
    <h1 class="text-2xl sm:text-3xl font-bold text-primary-light dark:text-primary-dark"><?php echo $app_name; ?></h1>
  </div>

  <!-- Right: Theme toggle + Profile -->
  <div class="flex items-center gap-4">
    <!-- Theme Toggle with Tooltip -->
    <div class="relative group">
      <button id="themeToggle" class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200">
        <i class="fas <?php echo $dark_mode ? 'fa-sun' : 'fa-moon'; ?>"></i>
      </button>
      <span class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 text-xs bg-black text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200">
        Toggle Theme
      </span>
    </div>

    <!-- Profile Picture with Dropdown -->
    <div class="relative group">
<?php if (isset($_SESSION['user'])): ?>
  <!-- Profile picture and dropdown -->
  <div class="relative group">
    <img
      src="<?= htmlspecialchars($_SESSION['user']['profile_pic'] ?? 'assets/img/default-profile.png') ?>"
      alt="Profile"
      class="h-10 w-10 rounded-full cursor-pointer border-2 border-red-600 object-cover shadow-md"
      title="<?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?>"
    />
    <div
      class="absolute right-0 mt-2 w-44 bg-gray-50 dark:bg-gray-800 rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity text-sm z-20 text-gray-900 dark:text-gray-100"
    >
      <p
        class="px-4 py-2 border-b border-gray-300 dark:border-gray-700 truncate"
        title="<?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?>"
      >
        <?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?>
      </p>
      <a
        href="logout.php"
        class="block px-4 py-2 hover:bg-red-600 hover:text-white rounded-b-md transition"
      >Logout</a>
    </div>
  </div>
<?php else: ?>
        <!-- Single Login/Signup Button -->
        <a
          href="login.php"
          class="px-4 py-2 rounded-md border border-red-600 text-red-600 hover:bg-red-600 hover:text-white transition flex items-center space-x-2"
        >
          <i class="fas fa-user-circle"></i>
          <span>Signin</span>
        </a>
        <?php endif; ?>
  </div>
</header>
<?php echo '<div style="height:20px;"></div>';?>
  <div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto">
      <div class="relative flex items-center bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <input type="text" id="searchInput" value="<?php echo $default_word; ?>" placeholder="Search for a word..."
          class="flex-grow px-4 py-3 bg-transparent text-black dark:text-white outline-none" />
        <button id="searchBtn" class="px-4 py-3 bg-primary-light dark:bg-primary-dark text-white">
          <i class="fas fa-search"></i>
        </button>
      </div>
      <div id="suggestions" class="hidden absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-300 dark:border-gray-700"></div>
    </div>

    <p id="infoText" class="text-center mt-4"><?php echo $default_word ? 'Searching for: ' . $default_word : 'Type a word and press Enter'; ?></p>

    <!-- Shimmer loading effect -->
    <div id="loadingShimmer" class="hidden mt-10 max-w-3xl mx-auto">
      <div class="word-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
        <div class="flex justify-between items-center">
          <div class="w-full">
            <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded shimmer mb-2 w-3/4"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded shimmer mb-2 w-1/2"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded shimmer w-1/3"></div>
          </div>
          <div class="h-8 w-8 bg-gray-200 dark:bg-gray-700 rounded-full shimmer"></div>
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-6">
        <div class="word-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
          <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded shimmer mb-4 w-1/3"></div>
          <div class="space-y-3">
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded shimmer w-full"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded shimmer w-5/6"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded shimmer w-4/6"></div>
          </div>
        </div>
        <div class="space-y-6">
          <div class="word-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded shimmer mb-4 w-1/4"></div>
            <div class="space-y-3">
              <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded shimmer w-full"></div>
              <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded shimmer w-5/6"></div>
            </div>
          </div>
          <div class="word-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded shimmer mb-4 w-1/3"></div>
            <div class="flex flex-wrap gap-2">
              <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded shimmer w-16"></div>
              <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded shimmer w-20"></div>
              <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded shimmer w-14"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="resultContainer" class="mt-10 max-w-3xl mx-auto hidden">
      <div class="word-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
        <div class="flex justify-between items-center">
          <div>
            <h2 id="word" class="text-3xl font-bold">_</h2>
            <p id="phonetic" class="italic text-secondary-light dark:text-secondary-dark">_</p>
            <p id="partOfSpeech" class="font-semibold text-sm">_</p>
          </div>
          <div>
            <button id="speakBtn" class="p-2 bg-gray-200 dark:bg-gray-700 rounded-full">
              <i class="fas fa-volume-up"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-6">
        <div class="word-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
          <h3 class="text-xl font-bold mb-2">Definitions</h3>
          <ul id="definitions" class="list-disc list-inside space-y-2 text-gray-700 dark:text-gray-300"></ul>
        </div>
        <div class="space-y-6">
          <div class="word-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold mb-2">Examples</h3>
            <ul id="examples" class="space-y-2 text-gray-700 dark:text-gray-300"></ul>
          </div>
          <div class="word-card bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold mb-2">Synonyms</h3>
            <div id="synonyms" class="flex flex-wrap gap-2 text-primary-light dark:text-primary-dark"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>