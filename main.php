<?php
$app_name = "Ultimate Dictionary";
$default_word = isset($_GET['word']) ? htmlspecialchars($_GET['word']) : '';
$dark_mode = isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'true';
?>
<!DOCTYPE html>
<html lang="en" class="<?php echo $dark_mode ? 'dark' : ''; ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo $app_name; ?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: { light: '#3b82f6', dark: '#2563eb' },
            secondary: { light: '#10b981', dark: '#059669' }
          }
        }
      }
    }
  </script>
  <style>
    .word-card { transition: all 0.3s ease; }
    .word-card:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
    .dark .word-card:hover { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3); }
    #suggestions { max-height: 200px; overflow-y: auto; }
  </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
  <div class="container mx-auto px-4 py-8">
    <header class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-primary-light dark:text-primary-dark"><i class="fas fa-book mr-2"></i><?php echo $app_name; ?></h1>
      <button id="themeToggle" class="p-2 rounded-full bg-gray-300 dark:bg-gray-700">
        <i class="fas <?php echo $dark_mode ? 'fa-sun' : 'fa-moon'; ?>"></i>
      </button>
    </header>

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

  <style>
    @keyframes shimmer {
      0% { opacity: 0.5; }
      50% { opacity: 1; }
      100% { opacity: 0.5; }
    }
    
    .shimmer {
      animation: shimmer 1.5s infinite ease-in-out;
    }
  </style>

  <script>
    const searchInput = document.getElementById('searchInput');
    const searchBtn = document.getElementById('searchBtn');
    const resultContainer = document.getElementById('resultContainer');
    const loadingShimmer = document.getElementById('loadingShimmer');
    const infoText = document.getElementById('infoText');
    const wordEl = document.getElementById('word');
    const phoneticEl = document.getElementById('phonetic');
    const partOfSpeechEl = document.getElementById('partOfSpeech');
    const definitionsEl = document.getElementById('definitions');
    const examplesEl = document.getElementById('examples');
    const synonymsEl = document.getElementById('synonyms');
    const speakBtn = document.getElementById('speakBtn');

    // Preload audio for faster response
    let audio = new Audio();
    
    function fetchWord(word) {
      if (!word) return;

      // Show loading shimmer and hide results
      loadingShimmer.classList.remove("hidden");
      resultContainer.classList.add("hidden");
      
      // Remove "Searching for..." text after a short delay
      setTimeout(() => {
        infoText.textContent = "";
      }, 500);

      fetch(`https://api.dictionaryapi.dev/api/v2/entries/en/${word}`)
        .then(res => res.json())
        .then(data => {
          if (data.title === "No Definitions Found") {
            alert("Word not found.");
            loadingShimmer.classList.add("hidden");
            return;
          }

          loadingShimmer.classList.add("hidden");
          resultContainer.classList.remove("hidden");

          const entry = data[0];
          wordEl.textContent = entry.word;
          phoneticEl.textContent = entry.phonetics[0]?.text || '';
          partOfSpeechEl.textContent = entry.meanings[0]?.partOfSpeech || '';

          definitionsEl.innerHTML = '';
          entry.meanings[0]?.definitions?.forEach(def => {
            definitionsEl.innerHTML += `<li>${def.definition}</li>`;
          });

          examplesEl.innerHTML = '';
          entry.meanings[0]?.definitions?.forEach(def => {
            if (def.example) {
              examplesEl.innerHTML += `<li>"${def.example}"</li>`;
            }
          });

          synonymsEl.innerHTML = '';
          const synonyms = entry.meanings[0]?.definitions[0]?.synonyms || [];
          if (synonyms.length) {
            synonyms.forEach(s => {
              synonymsEl.innerHTML += `<span class="bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded">${s}</span>`;
            });
          } else {
            synonymsEl.innerHTML = "<p>No synonyms found.</p>";
          }

          // Pre-fetch audio for faster playback
          const audioUrl = entry.phonetics[0]?.audio;
          if (audioUrl) {
            audio.src = audioUrl;
            audio.load();
          }

          speakBtn.onclick = () => {
            if (audioUrl) {
              audio.play().catch(e => {
                // Fallback to speech synthesis if audio playback fails
                const utterance = new SpeechSynthesisUtterance(entry.word);
                speechSynthesis.speak(utterance);
              });
            } else {
              const utterance = new SpeechSynthesisUtterance(entry.word);
              speechSynthesis.speak(utterance);
            }
          };
        })
        .catch(err => {
          alert("Error fetching word!");
          console.error(err);
          loadingShimmer.classList.add("hidden");
        });
    }

    searchBtn.onclick = () => {
      const word = searchInput.value.trim();
      if (word) {
        infoText.textContent = `Searching for: ${word}`;
        window.location.href = `?word=${encodeURIComponent(word)}`;
      }
    };

    searchInput.addEventListener("keypress", e => {
      if (e.key === "Enter") searchBtn.click();
    });

    document.getElementById('themeToggle').addEventListener('click', () => {
      const root = document.documentElement;
      const isDark = root.classList.toggle('dark');
      document.cookie = `darkMode=${isDark}; path=/; max-age=31536000`;
      document.getElementById('themeToggle').innerHTML = `<i class="fas ${isDark ? 'fa-sun' : 'fa-moon'}"></i>`;
    });

    // Load word on page load if passed via PHP
    const initialWord = "<?php echo $default_word; ?>";
    if (initialWord) fetchWord(initialWord);
  </script>
</body>
</html>