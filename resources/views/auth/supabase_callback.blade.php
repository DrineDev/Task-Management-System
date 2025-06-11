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
        // Get the access token from URL fragment
        const hash = window.location.hash.substring(1);
        const params = new URLSearchParams(hash);
        const accessToken = params.get('access_token');
        const refreshToken = params.get('refresh_token');
        const expiresAt = params.get('expires_at');
        const expiresIn = params.get('expires_in');

        if (accessToken) {
            // Send the tokens to the server
            fetch('/task-management-system/auth/supabase-login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                    throw new Error('Login failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = '/task-management-system/login';
            });
        } else {
            window.location.href = '/task-management-system/login';
        }
    </script>
</body>
</html>
