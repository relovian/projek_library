<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Team - Projek Library</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #202020ce 0%, #0c0018 100%);
            min-height: 100vh;
            padding: 50px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 1.1em;
        }

        .team-grid {
            display: grid;
            gap: 20px;
        }

        .member-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            border-radius: 12px;
            background: #f8f9fa;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }

        .member-card:hover {
            background: #e9ecef;
            transform: translateX(5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c5c5c5, #111111);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2em;
            flex-shrink: 0;
        }

        .avatar img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .member-info {
            flex: 1;
        }

        .member-name {
            font-weight: 600;
            font-size: 1.1em;
        }

        .member-username {
            color: #666;
            font-size: 0.9em;
        }

        .badge {
            background: #000000;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>👥 Our Team</h1>
            <p>Projek Library Contributors</p>
        </div>

        <!-- Team Members -->
        <div class="team-grid">
            
            <!-- Member 1: relovian -->
            <a href="https://github.com/relovian" class="member-card" target="_blank">
                <div class="avatar">
                    <img src="https://github.com/relovian.png" alt="relovian">
                </div>
                <div class="member-info">
                    <div class="member-name">Relovian Rahmadan S.P</div>
                    <div class="member-username">@relovian</div>
                </div>
                <span class="badge">Leader</span>
            </a>

            <!-- Member 2: Dimassatriaprayogaarbai -->
            <a href="https://github.com/Dimassatriaprayogaarbai" class="member-card" target="_blank">
                <div class="avatar">
                    <img src="https://github.com/Dimassatriaprayogaarbai.png" alt="Dimassatriaprayogaarbai">
                </div>
                <div class="member-info">
                    <div class="member-name">Dimss_spa</div>
                    <div class="member-username">@Dimassatriaprayogaarbai</div>
                </div>
                <span class="badge">Developer</span>
            </a>

            <!-- Member 3: Nalvenzon -->
            <a href="https://github.com/Nalvenzon" class="member-card" target="_blank">
                <div class="avatar">
                    <img src="https://github.com/Nalvenzon.png" alt="Nalvenzon">
                </div>
                <div class="member-info">
                    <div class="member-name">Nalvenzon</div>
                    <div class="member-username">@Nalvenzon</div>
                </div>
                <span class="badge">Developer</span>
            </a>

            <!-- Member 4: lefran-debug -->
            <a href="https://github.com/lefran-debug" class="member-card" target="_blank">
                <div class="avatar">
                    <img src="https://github.com/lefran-debug.png" alt="lefran-debug">
                </div>
                <div class="member-info">
                    <div class="member-name">Aru</div>
                    <div class="member-username">@lefran-debug</div>
                </div>
                <span class="badge">Developer</span>
            </a>

        </div>
    </div>
</body>
</html>