<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ginger Tree</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />



    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<style>
  .video-screen {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 50;
      background: black;
    }

    .video-screen video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .hidden {
      display: none;
    }
</style>

<body class="h-full font-sans bg-black">

    <div id="video-screen" class="video-screen">
        <video autoplay muted playsinline id="intro-video">
            <source
                src="{{asset('img/4K INTRO BOLTONI.mp4')}}"
                type="video/mp4" />
            Your browser does not support video.
        </video>
    </div>
    <!-- Main Wrapper -->
    <div id="main-content" class="hidden flex flex-col md:flex-row h-full
              py-6 sm:py-8 md:py-[10rem] lg:py-16 xl:py-[10rem]
              px-4 sm:px-6 md:px-8 lg:px-12 xl:px-[20rem] 2xl:px-[30rem]">

        <!-- Left Side -->
        <div class="w-full md:w-1/2 relative flex items-center justify-center min-h-[250px] sm:min-h-[300px] md:min-h-0">

            <!-- Background Image -->
            <div class="absolute inset-0 flex items-center justify-center opacity-80">
                <img src="{{asset('img/boltoni-logo2.png')}}"
                    alt="Bird"
                    class="h-full w-full object-cover mix-blend-overlay">
            </div>

            <!-- Boltoni Logo - Center -->
            <div class="relative z-10  px-4 sm:px-6 md:px-8 py-3 sm:py-4 md:py-6 rounded-lg">
                <img src="{{asset('img/boltoni logo-03.svg')}}" alt="Boltoni Logo" class="h-16 sm:h-12 md:h-14 lg:h-[6rem]">
            </div>

            <!-- SS Logo - Bottom -->
            <div class="absolute bottom-4 sm:bottom-6 md:bottom-8 left-1/2 transform -translate-x-1/2 z-10 px-4 sm:px-6 md:px-8 py-3 sm:py-4 md:py-6 rounded-lg">
                <img src="images/" alt="Ginger Tree Logo" class="h-10 sm:h-12 md:h-14 lg:h-16">
            </div>
        </div>

        <!-- Right Side - Login -->
        <div class="w-full md:w-1/2 bg-white flex items-center justify-center py-6 sm:py-8 md:py-10 lg:py-12 xl:py-0">

            <div class="w-full max-w-md px-4 sm:px-6 md:px-8 lg:px-10 xl:px-12">

                <h1 class="text-xl sm:text-2xl font-bold text-red-600 text-center mb-6 sm:mb-8">
                    Login Account
                </h1>

                <form method="POST" action="{{ url('/login') }}" class="space-y-4 sm:space-y-6">
                @csrf
                    <!-- Email -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4">
                            <div class="w-1 h-6 sm:h-8 bg-red-600 rounded-full"></div>
                        </div>
                        <input
                            type="text"
                            name="email"
                            placeholder="Email/ID"
                            class="w-full pl-7 sm:pl-8 pr-3 sm:pr-4 py-2 sm:py-3 bg-gray-50 border-0 focus:outline-none focus:ring-2 focus:ring-red-600 rounded-md text-gray-700 text-sm sm:text-base">
                    </div>

                    <!-- Password -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 sm:pl-4">
                            <div class="w-1 h-6 sm:h-8 bg-red-600 rounded-full"></div>
                        </div>
                        <input
                            type="password"
                            name="password"
                            placeholder="Password"
                            class="w-full pl-7 sm:pl-8 pr-3 sm:pr-4 py-2 sm:py-3 bg-gray-50 border-0 focus:outline-none focus:ring-2 focus:ring-red-600 rounded-md text-gray-700 text-sm sm:text-base">
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-3 h-3 sm:w-4 sm:h-4 text-red-600 border-gray-300 rounded focus:ring-red-600">
                            <span class="text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-red-600 hover:text-red-700 font-medium">Forgot a number?</a>
                    </div>

                    <!-- Button -->
                    <button
                        type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 sm:py-3 rounded-full transition-colors duration-200 uppercase tracking-wide text-sm sm:text-base">
                        LOGIN
                    </button>
                </form>
            </div>
        </div>

    </div>
<script src="{{asset('js/main.js')}}"></script>
</body>

</html>
