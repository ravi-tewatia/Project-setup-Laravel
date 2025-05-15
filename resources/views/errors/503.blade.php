<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    </head>

    <body>
        <div class="p-8">
            <div class="bg-white rounded-lg shadow-xl">
                <div class="p-16">
                <div>
                    <!-- Insert your svg or image here -->
                </div>

                <div class="mt-8 text-center">
                    <h1>:) Oops!</h1>
                    <h1 class="font-bold text-lg text-gray-700 mb-1">Temporarily down for maintenance</h1>
                    <h1 class="font-bold text-lg text-gray-700 mb-1">We’ll be back soon!</h1>
                    <p class="text-gray-600">Sorry for the inconvenience but we’re performing some maintenance at the moment.
                                    we’ll be back online shortly!<p>
                                    — The Team</p></p>
                    <button class="mt-6 bg-blue-500 hover:bg-blue-400 text-white rounded-full px-12 py-3 shadow-xl focus:outline-none">
                    Take Me Home 
                    </button>
                </div>
            </div>
        </div>
    </body>
</html>