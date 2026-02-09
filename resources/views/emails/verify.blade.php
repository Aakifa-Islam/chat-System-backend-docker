<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <h2>Hello, {{ $user->name }}!</h2>
    <p>Aapka account register ho gaya hai. Account verify karne ke liye niche diye gaye button par click karein:</p>
    
    <div style="margin: 30px 0;">
        <a href="{{ $url }}" 
           style="background-color: #4CAF50; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
           Verify My Email
        </a>
    </div>

    <p>Agar aapne ye account create nahi kiya, to is email ko ignore karein.</p>
    <p>Shukriya!<br>Team Chat App</p>
</body>
</html>