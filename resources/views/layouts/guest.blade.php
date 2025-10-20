<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

<<<<<<< Updated upstream
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
=======
        .top-bar .left {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .top-bar .left img {
            height: 14px;
            width: 14px;
            object-fit: contain;
            vertical-align: middle;
            margin-right: 5px;
        }

        .logo-bar {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px 0;
        }

        .logo-bar img {
            height: 45px;
            margin-right: 10px;
        }

        .logo-bar h1 {
            color: #d82323;
            font-size: 28px;
            letter-spacing: 1px;
            margin: 0;
        }

        /* --- MENU --- */
        nav {
            background-color: #efe6d6;
            text-align: center;
            padding: 10px 0;
        }

        nav a {
            color: #000;
            text-decoration: none;
            font-size: 14px;
            margin: 0 15px;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

        /* --- CONTENT --- */
        main {
            padding: 20px;
            min-height: 70vh;
        }

        /* --- FOOTER --- */
        footer {
            background-color: #ffffff;
            border-top: 1px solid #ddd;
            padding: 30px 10px;
            text-align: center;
            font-size: 13px;
            color: #000;
        }

        footer .logo-footer {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        footer .logo-footer img {
            height: 35px;
            margin-right: 8px;
        }

        footer h3 {
            color: #d82323;
            margin: 0;
            font-size: 22px;
        }

        footer a {
            color: #000;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .footer-links {
            margin-top: 10px;
            color: #555;
        }

        .footer-company {
            margin-top: 15px;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <header>
        <div class="top-bar">
            <!-- Bên trái: logo Facebook -->
            <div class="left">
                <a href="https://facebook.com" target="_blank" 
                style="display: flex; align-items: center; text-decoration: none; color: black; font-weight: bold;">
                    <img src="{{ asset('assets/images/Facebook.png') }}" 
                        alt="Facebook Logo" 
                        style="height: 16px; margin-right: 5px;">
                    Facebook
>>>>>>> Stashed changes
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
