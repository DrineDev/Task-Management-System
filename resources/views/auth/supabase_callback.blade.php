<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Processing Login · Task Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind + Google Fonts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Istok+Web:wght@400;700&family=Inter:wght@400;700&display=swap');
      .font-istok { font-family: 'Istok Web', sans-serif; }
      .font-inter { font-family: 'Inter', sans-serif; }
    </style>
    @vite(['resources/css/login.css', 'resources/js/app.js'])
</head>
<body class="h-screen overflow-hidden bg-[#1E1E1E] text-[#F4EBD9] font-istok">

  <!-- fixed header -->
  <header class="fixed inset-x-0 top-0 h-20 bg-[#D2C5A5] rounded-bl-[60px] rounded-br-[60px]
                 flex items-end justify-center px-4 z-20">
    <nav class="w-36 flex justify-between pb-3 text-sm">
      <a href="{{ route('register') }}" class="text-zinc-800 font-bold">Sign Up</a>
      <a href="{{ route('login') }}"    class="text-zinc-800 font-bold underline">Log In</a>
    </nav>
  </header>

  <!-- main: card centered between header & footer -->
  <main class="absolute inset-x-0 top-20 bottom-20 overflow-auto px-4">
    <div class="h-full flex items-center justify-center">
      <div class="bg-[#26231F] p-6 sm:p-8 rounded-xl shadow-lg w-full max-w-sm space-y-5 flex flex-col items-center">
        <h1 class="text-center text-2xl font-bold text-stone-200 mb-2">Processing Authentication...</h1>
        <div class="w-full flex flex-col items-center">
          <div class="spinner border-4 border-[#C7B89B] border-t-[#ECE3D2] rounded-full w-12 h-12 animate-spin mb-4"></div>
          <p id="status" class="text-white text-sm mb-2">Please wait while we complete your authentication...</p>
          <p id="error" class="text-red-400 text-sm font-inter mb-2" style="display: none;"></p>
          <p id="success" class="text-green-400 text-sm font-inter mb-2" style="display: none;"></p>
          <button class="debug-toggle mt-2 px-3 py-1 bg-[#6c757d] text-white rounded text-xs" onclick="toggleDebug()">Show Debug Info</button>
          <div id="debug" class="w-full mt-2 text-xs text-gray-400 bg-[#23201B] rounded p-2 max-h-40 overflow-y-auto" style="display: none;"></div>
        </div>
      </div>
    </div>
  </main>

  <!-- fixed footer -->
  <footer class="fixed inset-x-0 bottom-0 h-16 bg-[#D2C5A5]
                 rounded-tl-[60px] rounded-tr-[60px]
                 flex items-center justify-center text-zinc-800 text-xs font-istok z-20">
  </footer>

<script>
    // Store the proper dashboard URL from Laravel
    const DASHBOARD_URL = '{{ route("dashboard") }}';
    const LOGIN_URL = '{{ route("login") }}';

    // Debug logging function
    function debugLog(message, data = null) {
        const debug = document.getElementById('debug');
        const entry = document.createElement('div');
        entry.innerHTML = `<strong>${new Date().toISOString()}:</strong> ${message}`;
        if (data) {
            entry.innerHTML += `<pre>${JSON.stringify(data, null, 2)}</pre>`;
        }
        debug.appendChild(entry);
        debug.scrollTop = debug.scrollHeight;
    }

    function toggleDebug() {
        const debug = document.getElementById('debug');
        debug.classList.toggle('visible');
        const button = document.querySelector('.debug-toggle');
        button.textContent = debug.classList.contains('visible') ? 'Hide Debug Info' : 'Show Debug Info';
    }

    // Log initial state
    debugLog('Initial page load', {
        url: window.location.href,
        hash: window.location.hash,
        search: window.location.search,
        pathname: window.location.pathname,
        host: window.location.host,
        port: window.location.port,
        dashboardUrl: DASHBOARD_URL,
        loginUrl: LOGIN_URL
    });

    // Function to handle errors
    function handleError(message, error = null) {
        debugLog('Error occurred: ' + message, error);
        document.getElementById('error').textContent = message;
        document.getElementById('error').style.display = 'block';
        document.getElementById('status').textContent = 'Authentication failed';
        document.querySelector('.spinner').style.display = 'none';

        // Redirect to login after 3 seconds
        setTimeout(() => {
            window.location.href = LOGIN_URL + '?error=' + encodeURIComponent(message);
        }, 3000);
    }

    // Function to handle successful authentication
    function handleSuccess(message = 'Authentication successful! Redirecting...') {
        debugLog('Authentication successful');
        document.getElementById('success').textContent = message;
        document.getElementById('success').style.display = 'block';
        document.getElementById('status').textContent = 'Authentication successful! Redirecting...';
        document.querySelector('.spinner').style.display = 'none';
    }

    // Function to send token to server
    async function sendTokenToServer(accessToken, refreshToken) {
        debugLog('Sending token to server', {
            hasAccessToken: !!accessToken,
            hasRefreshToken: !!refreshToken,
            accessTokenLength: accessToken ? accessToken.length : 0
        });

        try {
            const response = await fetch('{{ route("auth.callback") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({
                    access_token: accessToken,
                    refresh_token: refreshToken
                })
            });

            debugLog('Server response', {
                status: response.status,
                statusText: response.statusText,
                headers: Object.fromEntries(response.headers.entries())
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();
            debugLog('Server response data', data);

            if (data.success) {
                handleSuccess();
                // Use the Laravel-generated dashboard URL
                setTimeout(() => {
                    debugLog('Redirecting to dashboard', { url: DASHBOARD_URL });
                    window.location.href = DASHBOARD_URL;
                }, 1000);
            } else {
                handleError(data.message || 'Authentication failed');
            }
        } catch (error) {
            debugLog('Network error while sending token', {
                message: error.message,
                stack: error.stack
            });
            handleError('Network error while sending token: ' + error.message);
        }
    }

    // Parse URL hash for fragment-based tokens
    function parseHash() {
        const hash = window.location.hash.substring(1);
        debugLog('Parsing URL hash', { hash });

        if (!hash) {
            debugLog('No hash found, checking query parameters');
            parseQueryParams();
            return;
        }

        const params = new URLSearchParams(hash);
        const accessToken = params.get('access_token');
        const refreshToken = params.get('refresh_token');
        const error = params.get('error');
        const errorDescription = params.get('error_description');

        debugLog('Parsed hash parameters', {
            hasAccessToken: !!accessToken,
            hasRefreshToken: !!refreshToken,
            error,
            errorDescription,
            allParams: Object.fromEntries(params.entries())
        });

        if (error) {
            handleError(errorDescription || error || 'Authentication failed');
            return;
        }

        if (!accessToken) {
            debugLog('No access token in hash, checking query parameters');
            parseQueryParams();
            return;
        }

        sendTokenToServer(accessToken, refreshToken);
    }

    // Parse query parameters for direct token passing
    function parseQueryParams() {
        const params = new URLSearchParams(window.location.search);
        const accessToken = params.get('access_token');
        const refreshToken = params.get('refresh_token');
        const error = params.get('error');
        const errorDescription = params.get('error_description');

        debugLog('Parsed query parameters', {
            hasAccessToken: !!accessToken,
            hasRefreshToken: !!refreshToken,
            error,
            errorDescription,
            allParams: Object.fromEntries(params.entries())
        });

        if (error) {
            handleError(errorDescription || error || 'Authentication failed');
            return;
        }

        if (!accessToken) {
            handleError('No access token found in URL hash or query parameters');
            return;
        }

        sendTokenToServer(accessToken, refreshToken);
    }

    // Alternative hash parsing for different OAuth response formats
    function parseHashAlternative() {
        const hash = window.location.hash.substring(1);
        debugLog('Alternative hash parsing', { hash });

        const params = {};
        if (hash) {
            const pairs = hash.split('&');
            for (const pair of pairs) {
                const [key, value] = pair.split('=');
                if (key && value) {
                    params[decodeURIComponent(key)] = decodeURIComponent(value);
                }
            }
        }

        debugLog('Alternative parsed parameters', params);

        if (params.error) {
            handleError(params.error_description || params.error || 'Authentication failed');
            return;
        }

        if (params.access_token) {
            sendTokenToServer(params.access_token, params.refresh_token);
            return;
        }

        // Still no token found
        handleError('No access token found using alternative parsing');
    }

    // Check if we're already processing to avoid double processing
    let isProcessing = false;

    // Start the authentication process
    function startAuthentication() {
        if (isProcessing) {
            debugLog('Already processing, ignoring duplicate call');
            return;
        }

        isProcessing = true;
        debugLog('Starting authentication process');

        // Try different parsing methods
        parseHash();

        // If still no token after 1 second, try alternative parsing
        setTimeout(() => {
            if (document.getElementById('error').style.display === 'none' &&
                document.getElementById('success').style.display === 'none') {
                debugLog('First attempt failed, trying alternative parsing');
                parseHashAlternative();
            }
        }, 1000);
    }

    // Wait for page to fully load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startAuthentication);
    } else {
        startAuthentication();
    }

    // Also try on window load as a fallback
    window.addEventListener('load', () => {
        setTimeout(() => {
            if (!isProcessing) {
                debugLog('Window load fallback triggered');
                startAuthentication();
            }
        }, 500);
    });
</script>
</body>
</html>
