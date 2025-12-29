<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0"
                   style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <!-- Header -->
                <tr>
                    <td style="background-color: #dc3545; padding: 30px; text-align: center;">
                        <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                            ⚠️ Low Stock Alert
                        </h1>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding: 40px 30px;">
                        <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6; color: #333333;">
                            Hello,
                        </p>

                        <p style="margin: 0 0 30px; font-size: 16px; line-height: 1.6; color: #333333;">
                            This is an automated notification to inform you that the stock level for the following
                            product is running low and requires immediate attention:
                        </p>

                        <!-- Product Details Box -->
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px; margin-bottom: 30px;">
                            <tr>
                                <td style="padding: 25px;">
                                    <h2 style="margin: 0 0 15px; font-size: 22px; color: #333333;">
                                        {{ $product->name }}
                                    </h2>

                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="padding: 8px 0; font-size: 15px; color: #666666;">
                                                <strong>Current Stock:</strong>
                                            </td>
                                            <td style="padding: 8px 0; font-size: 15px; color: #dc3545; text-align: right; font-weight: bold;">
                                                {{ $product->stock_quantity }} units
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>

                        <p style="margin: 0 0 25px; font-size: 16px; line-height: 1.6; color: #333333;">
                            Please review and reorder this product to avoid stock-out situations.
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background-color: #f8f9fa; padding: 25px 30px; text-align: center; border-top: 1px solid #dee2e6;">
                        <p style="margin: 0 0 10px; font-size: 13px; color: #6c757d; line-height: 1.5;">
                            This is an automated message. Please do not reply to this email.
                        </p>
                        <p style="margin: 0; font-size: 13px; color: #6c757d;">
                            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
