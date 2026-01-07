<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <style>
            :root {
                --ukm-red: #E41B13;
                --ukm-blue: #1B365D;
                --ukm-yellow: #FFD100;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                background: linear-gradient(-45deg, var(--ukm-blue), var(--ukm-red), var(--ukm-blue), #0a1929);
                background-size: 400% 400%;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                font-family: 'Instrument Sans', sans-serif;
                color: white;
                overflow: hidden;
                animation: gradientShift 15s ease infinite;
            }

            .particles {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: 1;
            }

            .particle {
                position: absolute;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 50%;
                animation: float 6s ease-in-out infinite;
            }

            .particle:nth-child(1) {
                width: 4px;
                height: 4px;
                top: 20%;
                left: 20%;
                animation-delay: 0s;
            }

            .particle:nth-child(2) {
                width: 6px;
                height: 6px;
                top: 60%;
                left: 80%;
                animation-delay: 2s;
            }

            .particle:nth-child(3) {
                width: 3px;
                height: 3px;
                top: 40%;
                left: 60%;
                animation-delay: 4s;
            }

            .particle:nth-child(4) {
                width: 5px;
                height: 5px;
                top: 80%;
                left: 30%;
                animation-delay: 1s;
            }

            .particle:nth-child(5) {
                width: 4px;
                height: 4px;
                top: 10%;
                left: 70%;
                animation-delay: 3s;
            }

            .splash-container {
                text-align: center;
                z-index: 2;
                animation: slideUp 1.2s ease-out;
            }

            .accent-line {
                width: 0;
                height: 4px;
                background: var(--ukm-yellow);
                margin: 0 auto 2rem;
                border-radius: 2px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                animation: lineGrow 1.5s ease-out 0.5s forwards;
            }

            .logo {
                font-size: 3.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
                letter-spacing: -1px;
                line-height: 1;
                opacity: 0;
                animation: textFadeIn 1s ease-out 0.8s forwards;
                transform: translateY(30px);
            }

            .subtitle {
                font-size: 1.2rem;
                font-weight: 400;
                margin-bottom: 3rem;
                opacity: 0.9;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                letter-spacing: 0.5px;
                opacity: 0;
                animation: textFadeIn 1s ease-out 1.2s forwards;
                transform: translateY(30px);
            }

            .get-started-btn {
                background: rgba(255, 255, 255, 0.95);
                color: var(--ukm-blue);
                border: none;
                padding: 1rem 2.5rem;
                border-radius: 50px;
                font-size: 1.1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                text-decoration: none;
                display: inline-block;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                position: relative;
                overflow: hidden;
                opacity: 0;
                animation: buttonSlideIn 0.8s ease-out 1.6s forwards;
                transform: translateY(50px);
            }

            .get-started-btn::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
                transition: left 0.5s;
            }

            .get-started-btn:hover::before {
                left: 100%;
            }

            .get-started-btn:hover {
                background: white;
                transform: translateY(-3px) scale(1.05);
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4);
                color: var(--ukm-blue);
            }

            .get-started-btn:active {
                transform: translateY(-1px) scale(1.02);
            }

            .background-shapes {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                overflow: hidden;
                z-index: 0;
            }

            .shape {
                position: absolute;
                border-radius: 50%;
                background: linear-gradient(45deg, var(--ukm-yellow), var(--ukm-red));
                opacity: 0.1;
                animation: shapeFloat 8s ease-in-out infinite;
            }

            .shape:nth-child(1) {
                width: 300px;
                height: 300px;
                top: -150px;
                right: -150px;
                animation-delay: 0s;
            }

            .shape:nth-child(2) {
                width: 200px;
                height: 200px;
                bottom: -100px;
                left: -100px;
                animation-delay: 3s;
            }

            .shape:nth-child(3) {
                width: 150px;
                height: 150px;
                top: 50%;
                left: -75px;
                animation-delay: 6s;
            }

            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(50px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes lineGrow {
                from { width: 0; }
                to { width: 80px; }
            }

            @keyframes textFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes buttonSlideIn {
                from {
                    opacity: 0;
                    transform: translateY(50px);
                }
                to {
                    opacity: 1;
                    transform: translateY(50px) translateY(-50px);
                }
            }

            @keyframes float {
                0%, 100% {
                    transform: translateY(0px) rotate(0deg);
                    opacity: 0.1;
                }
                50% {
                    transform: translateY(-20px) rotate(180deg);
                    opacity: 0.3;
                }
            }

            @keyframes shapeFloat {
                0%, 100% {
                    transform: translateY(0px) translateX(0px) rotate(0deg);
                }
                33% {
                    transform: translateY(-30px) translateX(30px) rotate(120deg);
                }
                66% {
                    transform: translateY(20px) translateX(-20px) rotate(240deg);
                }
            }

            @media (max-width: 768px) {
                .logo {
                    font-size: 2.5rem;
                }

                .subtitle {
                    font-size: 1rem;
                    margin-bottom: 2rem;
                }

                .get-started-btn {
                    padding: 0.875rem 2rem;
                    font-size: 1rem;
                }

                .accent-line {
                    animation: lineGrow 1.5s ease-out 0.5s forwards;
                }

                .accent-line {
                    width: 60px;
                }
            }

            @media (max-width: 480px) {
                .logo {
                    font-size: 2rem;
                }

                .subtitle {
                    font-size: 0.9rem;
                }

                .accent-line {
                    width: 50px;
                }
            }
        </style>
    </head>
    <body>
        <div class="background-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        <div class="particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        <div class="splash-container">
            <div class="accent-line"></div>
            <div class="logo">UKM Logbook System</div>
            <div class="subtitle">Keep It Daily, Enter Daily</div>
            <a href="{{ route('home') }}" class="get-started-btn">Get Started</a>
        </div>
    </body>
</html>
