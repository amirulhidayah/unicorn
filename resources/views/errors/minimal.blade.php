<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <style>
        body,
        html {
            padding: 0;
            margin: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #5FD068;
            font-family: 'Montserrat', sans-serif;
            color: #fff
        }

        html {
            background: url('https://static.pexels.com/photos/818/sea-sunny-beach-holiday.jpg');
            background-size: cover;
            background-position: bottom
        }

        .error {
            text-align: center;
            padding: 16px;
            position: relative;
            top: 50%;
            transform: translateY(-50%);
            -webkit-transform: translateY(-50%)
        }

        h1 {
            margin: -10px 0 -30px;
            font-size: calc(17vw + 40px);
            opacity: .8;
            letter-spacing: -17px;
        }

        p {
            opacity: .8;
            font-size: 20px;
            margin: 8px 0 38px 0;
            font-weight: bold
        }

        input,
        button,
        input:focus,
        button:focus {
            border: 0;
            outline: 0 !important;
        }

        input {
            width: 300px;
            padding: 14px;
            max-width: calc(100% - 80px);
            border-radius: 6px 0 0 6px;
            font-weight: 400;
            font-family: 'Montserrat', sans-serif;
        }

        button {
            width: 40px;
            padding: 14.5px 16px 14.5px 12.5px;
            vertical-align: top;
            border-radius: 0 6px 6px 0;
            color: grey;
            background: silver;
            cursor: pointer;
            transition: all 0.4s
        }

        button:hover {
            color: white;
            background: #9A5C32
        }

        .fa-arrow-left {
            position: fixed;
            top: 30px;
            left: 30px;
            font-size: 2em;
            color: white;
            text-decoration: none
        }


        .context {
            width: 100%;
            position: absolute;
            z-index: 9999;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
        }

        .context h1 {
            text-align: center;
            color: #fff;
            font-size: 50px;
        }


        .area {
            background: #5FD068;
            /* background: -webkit-linear-gradient(to left, #8f94fb, #4e54c8); */
            width: 100%;
            height: 100vh;


        }

        .circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .circles li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.2);
            animation: animate 25s linear infinite;
            bottom: -150px;

        }

        .circles li:nth-child(1) {
            left: 25%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
        }


        .circles li:nth-child(2) {
            left: 10%;
            width: 20px;
            height: 20px;
            animation-delay: 2s;
            animation-duration: 12s;
        }

        .circles li:nth-child(3) {
            left: 70%;
            width: 20px;
            height: 20px;
            animation-delay: 4s;
        }

        .circles li:nth-child(4) {
            left: 40%;
            width: 60px;
            height: 60px;
            animation-delay: 0s;
            animation-duration: 18s;
        }

        .circles li:nth-child(5) {
            left: 65%;
            width: 20px;
            height: 20px;
            animation-delay: 0s;
        }

        .circles li:nth-child(6) {
            left: 75%;
            width: 110px;
            height: 110px;
            animation-delay: 3s;
        }

        .circles li:nth-child(7) {
            left: 35%;
            width: 150px;
            height: 150px;
            animation-delay: 7s;
        }

        .circles li:nth-child(8) {
            left: 50%;
            width: 25px;
            height: 25px;
            animation-delay: 15s;
            animation-duration: 45s;
        }

        .circles li:nth-child(9) {
            left: 20%;
            width: 15px;
            height: 15px;
            animation-delay: 2s;
            animation-duration: 35s;
        }

        .circles li:nth-child(10) {
            left: 85%;
            width: 150px;
            height: 150px;
            animation-delay: 0s;
            animation-duration: 11s;
        }

        .btn-back {
            z-index: 9999;
        }



        @keyframes animate {

            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }

            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }
    </style>

</head>


<body>

    <a href="{{ url()->previous() }}" class="fa fa-arrow-left btn-back"></a>
    <div class="error">
        <h1>@yield('code')</h1>
        <p>@yield('message')</p>
    </div>

    <div class="area">
        <ul class="circles">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
</body>

</html>
