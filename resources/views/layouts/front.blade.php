<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>AsiaKitchen</title>

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"/>

    <!-- Slick -->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/slick.css') }}"/>
    <link type="text/css" rel="stylesheet" href="{{ asset('css/slick-theme.css') }}"/>

    <!-- nouislider -->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/nouislider.min.css') }}"/>

    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" integrity="sha256-46qynGAkLSFpVbEBog43gvNhfrOj+BmwXdxFgVK/Kvc=" crossorigin="anonymous" />
    <!-- <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}"> -->
    
    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('css/style.css') }}"/>

  </head>
  <body>
    <div class="flex-center position-ref full-height">

        <!-- HEADER -->
        @include('wrapper.header')
        <!-- /HEADER -->

        <!-- NAVIGATION -->
        @include('wrapper.nav')
        <!-- /NAVIGATION -->

        <div class="container">
            @yield('content')
        </div>

    </div>

    <!-- FOOTER -->
    @include('wrapper.footer')
    <!-- /FOOTER -->

    <!-- Plugins -->
    @include('wrapper.plugins')
    <!-- /lugins -->
  </body>
</html>
