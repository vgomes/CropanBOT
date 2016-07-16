<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Cropan Gourmet</title>

    <link rel="stylesheet" href="/css/style.css">
    <style>
        /* Sticky footer styles
-------------------------------------------------- */
        html {
            position: relative;
            min-height: 100%;
        }
        body {
            /* Margin bottom by footer height */
            margin-bottom: 60px;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            /* Set the fixed height of the footer here */
            height: 60px;
            line-height: 60px; /* Vertically center the text there */
            background-color: #f5f5f5;
        }

        .center-cropped {
            object-fit: cover; /* Do not scale the image */
            object-position: center; /* Center the image within the element */
            height: 250px;
            width: 100%;
        }

        /* Custom page CSS
        -------------------------------------------------- */
        /* Not required for template or sticky footer method. */

        body > .container {
            padding: 60px 15px 0;
        }

        .footer > .container {
            padding-right: 15px;
            padding-left: 15px;
        }

        code {
            font-size: 80%;
        }
    </style>
</head>

<body>

<!-- Fixed navbar -->
<div class="pos-f-t">
    <div class="collapse" id="navbar-header">
        <div class="container bg-inverse p-a-1">
            <h3>Collapsed content</h3>
            <p>Toggleable via the navbar brand.</p>
        </div>
    </div>
    <nav class="navbar navbar-light navbar-static-top bg-faded">
        <div class="container">
            <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2">
                &#9776;
            </button>
            <div class="collapse navbar-toggleable-xs" id="exCollapsingNavbar2">
                <a class="navbar-brand" href="#">Sticky footer</a>
                <ul class="nav navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Nav item <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Another nav item</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">More nav</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Last link</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<!-- Begin page content -->
<div class="container">
    @yield('content')
</div>

<footer class="footer">
    <div class="container">
        <span class="text-muted">Place sticky footer content here.</span>
    </div>
</footer>

<script src="/js/script.js"></script>
</body>
</html>
