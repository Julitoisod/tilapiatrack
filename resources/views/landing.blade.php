<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TilapiaTrack — Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0a2e1a 0%, #0d4a28 40%, #1a6b3a 70%, #0f5c2e 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative bubbles */
        body::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(34,197,94,0.12) 0%, transparent 70%);
            top: -100px; left: -100px;
            border-radius: 50%;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(16,185,129,0.10) 0%, transparent 70%);
            bottom: -80px; right: -80px;
            border-radius: 50%;
            pointer-events: none;
        }

        .container {
            max-width: 900px;
            width: 100%;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        /* Logo / Icon */
        .logo-wrap {
            margin-bottom: 1.5rem;
        }
        .logo-icon {
            width: 90px; height: 90px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(34,197,94,0.35);
            font-size: 3rem;
        }

        h1 {
            font-size: 2.8rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }
        h1 span {
            color: #4ade80;
        }

        .subtitle {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.65);
            margin-bottom: 3rem;
            font-weight: 400;
        }

        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 600px) {
            .cards { grid-template-columns: 1fr; }
            h1 { font-size: 2rem; }
        }

        .card {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-decoration: none;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            transition: transform 0.22s ease, background 0.22s ease, box-shadow 0.22s ease;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-6px);
            background: rgba(255,255,255,0.11);
            box-shadow: 0 16px 48px rgba(0,0,0,0.3);
        }

        .card-icon {
            width: 70px; height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
        }

        .card-admin .card-icon {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            box-shadow: 0 6px 20px rgba(99,102,241,0.4);
        }

        .card-user .card-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            box-shadow: 0 6px 20px rgba(245,158,11,0.4);
        }

        .card h2 {
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.2px;
        }

        .card p {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.5;
        }

        .card-btn {
            margin-top: 0.75rem;
            padding: 0.65rem 2rem;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: opacity 0.18s;
        }
        .card-btn:hover { opacity: 0.88; }

        .card-admin .card-btn {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff;
        }

        .card-user .card-btn {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
        }

        /* Footer */
        .footer {
            margin-top: 3rem;
            font-size: 0.82rem;
            color: rgba(255,255,255,0.35);
        }
    </style>
</head>
<body>
    <div class="container">

        <div class="logo-wrap">
            <div class="logo-icon">🐟</div>
        </div>

        <h1>Tilapia<span>Track</span></h1>
        <p class="subtitle">Fishpond Monitoring & Management System — Select your portal to continue.</p>

        <div class="cards">

            {{-- Admin Card --}}
            <a href="/admin/login" class="card card-admin">
                <div class="card-icon">🛡️</div>
                <h2>Admin Portal</h2>
                <p>Manage fishponds, beneficiaries, feeding programs, harvests, and system-wide reports.</p>
                <span class="card-btn">Admin Login</span>
            </a>

            {{-- User / Beneficiary Card --}}
            <a href="/app/login" class="card card-user">
                <div class="card-icon">👤</div>
                <h2>User Portal</h2>
                <p>View your fishpond status, feeding schedules, progress reports, and notifications.</p>
                <span class="card-btn">User Login</span>
            </a>

        </div>

        <p class="footer">&copy; {{ date('Y') }} TilapiaTrack. All rights reserved.</p>
    </div>
</body>
</html>
