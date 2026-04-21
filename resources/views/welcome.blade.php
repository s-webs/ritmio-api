<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Life Control') }}</title>
    <style>
        :root {
            color-scheme: light dark;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: radial-gradient(circle at center, #1b1f31 0%, #0e111a 100%);
            font-family: Inter, Arial, sans-serif;
        }

        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            animation: fade-in 1s ease-out;
        }

        .logo {
            width: min(55vw, 260px);
            height: auto;
            animation: float 3s ease-in-out infinite, glow 2.5s ease-in-out infinite alternate;
            filter: drop-shadow(0 8px 20px rgba(0, 0, 0, 0.35));
        }

        .caption {
            color: #d9e1ff;
            font-size: 0.95rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            opacity: 0.8;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes glow {
            from { filter: drop-shadow(0 8px 20px rgba(0, 0, 0, 0.35)); }
            to { filter: drop-shadow(0 10px 28px rgba(130, 171, 255, 0.45)); }
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <main class="logo-wrap">
        <img class="logo" src="{{ asset('assets/images/logo.svg') }}" alt="Life Control Logo">
        <div class="caption">Life Control</div>
    </main>
</body>
</html>
