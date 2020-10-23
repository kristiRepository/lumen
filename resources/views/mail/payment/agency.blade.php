<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body style="margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="padding: 10px 0 30px 0;">
                <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;">
                    <tr>
                        <td align="center" bgcolor="#70bbd9" style="color: #153643; font-size: 28px; font-weight: bold; font-family: Arial, sans-serif;">
                            <a href="#" style="pointer-events: none; cursor: default;">
                                <img src="https://blog.mobility.here.com/sites/default/files/styles/post_main_image/public/2019-11/hero.jpg" width="100%" height="320" style="display: block; object-fit: cover" />
                            </a>
                        </td>
                    </tr>
                   
                    <tr>
                        <td style="padding:30px">
                        <h2>Payment Notification</h2>

                        <p>A payment was submitted to your account by customer {{$customer->name}} {{$customer->surname}} for your product {{$trip->title}}.The payment was made through PayPal and it will be reflected in your bank account with 1-2 business days.</p>
                        
                        <p>Please do not reply this email.</p>


                        </td>
                    </tr>

                    <tr>
                        <td bgcolor="#ee4c50" style="padding: 30px 30px 30px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                              
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>