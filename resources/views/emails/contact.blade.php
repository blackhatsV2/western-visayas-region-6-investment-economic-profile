<!DOCTYPE html>
<html>
<head>
    <title>New Inquiry</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #10b981; border-bottom: 2px solid #10b981; padding-bottom: 10px;">New Investment Inquiry</h2>
        <p>You have received a new inquiry from the Western Visayas Investment Economic Profile website.</p>
        
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 10px; font-weight: bold; width: 150px;">Name:</td>
                <td style="padding: 10px;">{{ $contactData['name'] }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; font-weight: bold;">Email:</td>
                <td style="padding: 10px;">{{ $contactData['email'] }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; font-weight: bold;">Contact Number:</td>
                <td style="padding: 10px;">{{ $contactData['contact'] }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; font-weight: bold;">Message:</td>
                <td style="padding: 10px;">{{ $contactData['message'] }}</td>
            </tr>
        </table>
        
        <p style="margin-top: 30px; font-size: 12px; color: #888; border-top: 1px solid #eee; pt-10;">
            This email was sent from the Investment Funnel Contact Form.
        </p>
    </div>
</body>
</html>
