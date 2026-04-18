<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liquidation Form</title>
    <style>
        /* Critical CSS - Inline for fast loading */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            opacity: 0;
            transition: opacity 0.1s ease-in;
        }
        
        body.loaded {
            opacity: 1;
        }
        
        .dashboard-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .header {
            background-color: #1E4575;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            width: 100%;
            border-bottom: 2px solid #8a9bad;
            z-index: 1000;
            flex-shrink: 0;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo {
            width: 50px;
            height: 50px;
            min-width: 50px;
            background-color: rgba(255, 255, 255, 0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
            padding: 4px;
        }
        
        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: 600;
            color: #FFFFFE;
            white-space: nowrap;
        }
        
        .main-content {
            margin-left: 0;
            width: 100%;
            flex: 1;
        }
        
        .page-content {
            padding: 25px 30px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .no-print {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        
        .back-btn, .btn-action {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
        }
        
        .back-btn {
            background-color: #6c757d;
            color: white;
        }
        
        .back-btn:hover {
            background-color: #5a6268;
        }
        
        .btn-action {
            background-color: #1e4575;
            color: white;
        }
        
        .btn-action:hover {
            background-color: #163556;
        }
        
        @media print {
            .header, .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 0;
                background: white;
                opacity: 1 !important;
            }
            .page-content {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <div class="header-left">
                    <div class="logo">
                        <img src="{{ asset('images/ArkCrest_Logo.png') }}" alt="ARCKREST Logo" class="logo-img">
                    </div>
                    <h1 class="company-name">ARCKREST REALTY SERVICES</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="main-content">
            <main class="page-content">
                <!-- Back and Print Buttons -->
                <div class="no-print">
                    <a href="{{ route('departments.admin') }}" class="back-btn">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Back to Expenses</span>
                    </a>
                    <button onclick="window.print()" class="btn-action">
                        <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print
                    </button>
                </div>
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <script>
        // Show content when page is fully loaded
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
        });
        
        // Fallback - show after short delay even if not fully loaded
        setTimeout(function() {
            document.body.classList.add('loaded');
        }, 100);
    </script>
</body>
</html>
