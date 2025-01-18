<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtual ID</title>
</head>

<body>
    <div style="width: 100%; height: 250px; margin: auto; font-family: Arial, sans-serif;">
        <!-- Virtual ID Card Front -->
        <table
            style="width: 70%; height: 200px; max-width: 400px; margin: auto; background-color: #e2e8f0; border-radius: 10px; text-align: center; padding: 20px; border-collapse: collapse; background-image: url('images/Vector.png'); background-size: cover; background-position: center;">
            <!-- ID Card Header -->
            <tr>
                <td style="width: 100%; text-align: center;" colspan="2">
                    <div style="display: inline-block; vertical-align: middle; text-align: center;">
                        <img src="images/ndc_logo.png" alt="NDC Logo"
                            style="height: 45px; display: inline-block; vertical-align: middle;">
                    </div>
                    <div style="display: inline-block; vertical-align: middle; text-align: left;">
                        <h2 style="font-size: 18px; font-weight: bold; margin: 0;">
                            <span style="color: #3b82f6;">NATIONAL</span>
                            <span style="color: #22c55e;">DEVELOPMENT</span>
                            <span style="color: #f97316;">COMPANY</span>
                        </h2>
                    </div>
                </td>
            </tr>

            <!-- Profile Photo -->
            <tr>
                <td style="text-align: center;" colspan="2">
                    <div style="height: 160px">
                        <div style="display: inline-block; text-align: center; margin-top: 20px;">
                            <img src="{{ $profilePhotoPath }}" alt="Profile Photo"
                                style="width: 110px; height: 110px; border: 2px solid white; object-fit: cover; margin-left: 0px;">
                            <p style="font-size: 12px; margin-top: 5px;">{{ $position }}</p>
                        </div>

                        <!-- Personal Details -->
                        <div
                            style="display: inline-block; margin-top:40px; text-align: left; margin-left: 30px; line-height: .6 ; font-size: 13px;">
                            <p><strong>Name:</strong> {{ $name }}</p>
                            <p><strong>Employee Code:</strong> {{ $emp_code }}</p>
                            <p><strong>Date of Birth:</strong> {{ $dateOfBirth }}</p>
                            <p><strong>Place of Birth:</strong> {{ $placeOfBirth }}</p>
                            <p><strong>Sex:</strong> {{ $sex }}</p>
                            <p><strong>Civil Status:</strong> {{ $civilStatus }}</p>
                            <p><strong>Blood Type:</strong> {{ $bloodType }}</p>
                        </div>
                    </div>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td style="text-align: center; font-size: 10px; color: white; line-height: 0; margin-top: -20px;"
                    colspan="2">
                    <p>Issued by NDC</p>
                </td>
            </tr>

        </table>

        <!-- Virtual ID Card Back -->
        <table
            style="width: 70%; height: 200px; max-width: 400px; margin: 20px auto; background-color: #e2e8f0; border-radius: 10px; text-align: center; padding: 20px; border-collapse: collapse; background-image: url('images/Vector.png'); background-size: cover; background-position: center;">
            <tr>
                <!-- Terms and Conditions -->
                <td
                    style="width: 70%; vertical-align: top; padding: 10px; font-size: 10px; color: #000; text-align: left;">
                    <h3 style="font-weight: bold; margin-bottom: 10px;">TERMS AND CONDITIONS:</h3>
                    <ul style="list-style-type: disc; padding-left: 20px; margin: 0;">
                        <li>This ID is valid for official purposes only.</li>
                        <li>Any misuse of this ID is subject to legal consequences.</li>
                        <li>Please contact support for any issues.</li>
                    </ul>
                </td>

                <!-- QR Code -->
                <td style="width: 30%; text-align: center; vertical-align: middle;">
                    <div
                        style="width: 100px; height: 100px; display: inline-block; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                        <img src="data:image/png;base64,{{ $qrCode }}" width="100" height="100" />
                    </div>
                </td>

            </tr>
        </table>


    </div>
</body>

</html>
