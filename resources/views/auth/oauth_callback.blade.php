<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OAuth Callback</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js"></script>
</head>
<body>
    <p>Processing authentication...</p>

    <script>
        const SUPABASE_URL = '{{ config('services.supabase.url') }}';
        const SUPABASE_ANON_KEY = '{{ config('services.supabase.anon_key') }}';
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Get the hash fragment
        const hash = window.location.hash.substring(1);
        const params = new URLSearchParams(hash);
        
        // Extract the access token
        const accessToken = params.get('access_token');
        const refreshToken = params.get('refresh_token');
        const expiresAt = params.get('expires_at');
        const expiresIn = params.get('expires_in');

        if (accessToken) {
            // Make a request to our backend to handle the authentication
            fetch('/task-management-system/auth/supabase-login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    access_token: accessToken,
                    refresh_token: refreshToken,
                    expires_at: expiresAt,
                    expires_in: expiresIn
                })
            })
            .then(response => {
                if (response.ok) {
                    // Redirect to dashboard
                    window.location.href = '/task-management-system/dashboard';
                } else {
                    throw new Error('Authentication failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = '/task-management-system/login?error=Authentication failed';
            });
        } else {
            window.location.href = '/task-management-system/login?error=No access token received';
        }
    </script>
</body>
</html> 