<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* Custom Styles */
        body {
            background-color: #1e1e2f; /* Dark background */
            color: #d1d5db; /* Soft gray text */
            font-family: 'Inter', sans-serif; /* Modern font */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            margin: 0; /* Remove default margin */
        }

        h1 {
            color: #4ea4f4; /* Neon blue */
            margin-bottom: 1.5rem; /* Space below the heading */
        }

        .btn-logout {
            background-color: #4ea4f4; /* Neon blue */
            color: #fff; /* White text */
            border: 2px solid #4ea4f4; /* Neon blue border */
            border-radius: 10px; /* Rounded corners */
            padding: 15px 30px; /* Extra padding for a prominent button */
            font-size: 1rem; /* Larger font size */
            font-weight: bold;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.4); /* Add a shadow to the text */
            box-shadow: 0 5px 15px rgba(78, 164, 244, 0.4), 0 3px 5px rgba(0, 0, 0, 0.5); /* 3D effect shadow */
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-logout:hover {
            background-color: #3c94dc; /* Slightly darker neon blue on hover */
            color: #ffffff;
            transform: translateY(-2px); /* Slight upward movement */
            box-shadow: 0 7px 20px rgba(78, 164, 244, 0.5), 0 4px 8px rgba(0, 0, 0, 0.6); /* Enhanced shadow on hover */
        }

        .btn-logout:active {
            transform: translateY(2px); /* Pressed effect */
            box-shadow: 0 3px 10px rgba(78, 164, 244, 0.4), 0 2px 5px rgba(0, 0, 0, 0.5); /* Reduced shadow */
        }

        .card {
            background-color: #2a2a3b; /* Slightly lighter dark background */
            border: 1px solid #4ea4f4; /* Neon blue border */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5); /* Soft shadow */
            padding: 40px; /* Extra padding for a balanced design */
            text-align: center;
            width: 400px;
        }
    </style>
</head>
<body>
<div class="card">
    <h1 class="text-2xl font-bold">Are you sure you want to log out?</h1>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn-logout">
            Yes, Log Out
        </button>
    </form>
</div>
</body>
</html>
