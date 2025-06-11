<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OAuth Callback</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>
    <style>
        body {
            background: #1E1E1E;
            color: #F4EBD9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Istok Web', sans-serif;
        }
    </style>
</head>
<body>
    <p>Logging you in...</p>

    <script>
        const SUPABASE_URL = '{{ config('services.supabase.url') }}';
        const SUPABASE_ANON_KEY = '{{ config('services.supabase.anon_key') }}';

        const supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY, {
            auth: {
                redirectTo: '{{ route('dashboard') }}'
            }
        });

        // Get the access token from query parameters or URL fragment
        const urlParams = new URLSearchParams(window.location.search);
        let accessToken = urlParams.get('access_token');
        let refreshToken = urlParams.get('refresh_token');

        // If not in query params, try URL fragment
        if (!accessToken) {
            const hash = window.location.hash.substring(1);
            const hashParams = new URLSearchParams(hash);
            accessToken = hashParams.get('access_token');
            refreshToken = hashParams.get('refresh_token');
        }

        if (accessToken) {
            // Store the tokens in localStorage for Supabase client
            localStorage.setItem('supabase.auth.token', JSON.stringify({
                access_token: accessToken,
                refresh_token: refreshToken,
                expires_at: Date.now() + (3600 * 1000) // 1 hour from now
            }));

            // Redirect to dashboard without the access token in URL
            window.location.href = '{{ route('dashboard') }}';
        } else {
            console.error("No access token found in URL");
            window.location.href = '{{ route('login') }}';
        }
    </script>
</body>
</html>
