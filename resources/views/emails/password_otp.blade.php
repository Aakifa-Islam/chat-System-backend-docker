<h1>Password Reset Request</h1>
<p>Hello {{ $user->name }},</p>
<p>Aapka password reset OTP niche diya gaya hai. Ye sirf 10 minutes ke liye valid hai:</p>
<h2 style="background: #eee; padding: 10px; text-align: center;">{{ $otp }}</h2>
<p>Agar aapne ye request nahi ki, to is email ko ignore karein.</p>