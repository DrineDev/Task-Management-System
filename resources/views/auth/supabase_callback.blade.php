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

        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

        supabase.auth.getSession().then(async ({ data, error }) => {
            if (error || !data.session) {
                console.error("Session error:", error);
                alert("Failed to get session.");
                return;
            }

            try {
                const response = await fetch('/auth/supabase-login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({
                        access_token: data.session.access_token,
                        refresh_token: data.session.refresh_token,
                        user: data.session.user
                    })
                });

                if (response.ok) {
                    // ✅ Redirect user to dashboard
                    window.location.href = '/dashboard';
                } else {
                    console.error("Login failed:", await response.text());
                    alert('Login failed. See console for details.');
                }
            } catch (e) {
                console.error("Network error:", e);
                alert("Unexpected error. See console for details.");
            }
        });
    </script>
</body>
</html>
