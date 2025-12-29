<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Sales Report</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0"
                   style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <!-- Header -->
                <tr>
                    <td style="background-color: #28a745; padding: 30px; text-align: center;">
                        <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                            📊 Daily Sales Report
                        </h1>
                        <p style="margin: 10px 0 0; color: #ffffff; font-size: 16px;">
                            {{ $date}}
                        </p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding: 40px 30px;">
                        <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6; color: #333333;">
                            Hello,
                        </p>

                        <p style="margin: 0 0 30px; font-size: 16px; line-height: 1.6; color: #333333;">
                            Here is your daily sales summary for today:
                        </p>

                        <!-- Summary Box -->
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="background-color: #e7f5ff; border-left: 4px solid #007bff; border-radius: 4px; margin-bottom: 30px;">
                            <tr>
                                <td style="padding: 25px;">
                                    <h2 style="margin: 0 0 20px; font-size: 20px; color: #333333;">
                                        Summary
                                    </h2>

                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="padding: 8px 0; font-size: 15px; color: #666666;">
                                                <strong>Total Revenue:</strong>
                                            </td>
                                            <td style="padding: 8px 0; font-size: 18px; color: #28a745; text-align: right; font-weight: bold;">
                                                ${{ number_format($totalRevenue ?? 0, 2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px 0; font-size: 15px; color: #666666;">
                                                <strong>Total Products Sold:</strong>
                                            </td>
                                            <td style="padding: 8px 0; font-size: 18px; color: #007bff; text-align: right; font-weight: bold;">
                                                {{ $totalProductsSold ?? 0 }} units
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <!-- Products Table -->
                        @if(isset($products) && count($products) > 0)
                            <h2 style="margin: 0 0 20px; font-size: 20px; color: #333333;">
                                Products Sold
                            </h2>

                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="border: 1px solid #dee2e6; border-radius: 4px; margin-bottom: 30px;">
                                <thead>
                                <tr style="background-color: #f8f9fa;">
                                    <th style="padding: 15px; text-align: left; font-size: 14px; color: #495057; border-bottom: 2px solid #dee2e6;">
                                        Product Name
                                    </th>
                                    <th style="padding: 15px; text-align: center; font-size: 14px; color: #495057; border-bottom: 2px solid #dee2e6;">
                                        Quantity Sold
                                    </th>
                                    <th style="padding: 15px; text-align: right; font-size: 14px; color: #495057; border-bottom: 2px solid #dee2e6;">
                                        Revenue
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td style="padding: 15px; font-size: 14px; color: #333333; border-bottom: 1px solid #dee2e6;">
                                            {{ $product->name }}
                                        </td>
                                        <td style="padding: 15px; text-align: center; font-size: 14px; color: #333333; border-bottom: 1px solid #dee2e6;">
                                            {{ $product->pivot->quantity ?? 0 }}
                                        </td>
                                        <td style="padding: 15px; text-align: right; font-size: 14px; color: #28a745; font-weight: bold; border-bottom: 1px solid #dee2e6;">
                                            ${{ number_format($product->pivot->quantity * $product->pivot->unit_price, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <table width="100%" cellpadding="0" cellspacing="0"
                                   style="background-color: #f8f9fa; border-radius: 4px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px; text-align: center; font-size: 15px; color: #6c757d;">
                                        No products were sold today.
                                    </td>
                                </tr>
                            </table>
                        @endif

                        <p style="margin: 0; font-size: 16px; line-height: 1.6; color: #333333;">
                            Thank you for your business!
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
